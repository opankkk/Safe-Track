<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Models\ReportLog;
use App\Models\HseNotification;
use Carbon\Carbon;

class CloseExpiredReports extends Command
{
    protected $signature = 'reports:close-expired';

    protected $description = 'Menutup laporan yang melewati batas waktu 2x24 jam dan mengirim notifikasi peringatan.';

    public function handle()
    {
        $now = now();

        // ─────────────────────────────────────────────────────────────
        // 1. PERINGATAN H-6 JAM: laporan yang due_date-nya 6 jam lagi
        //    dan belum pernah dikirim notifikasi peringatan ini.
        // ─────────────────────────────────────────────────────────────
        $warningReports = Report::where('sub_status', Report::SUB_PIC_WORKING)
            ->whereBetween('due_date', [$now->copy()->addHours(5)->addMinutes(55), $now->copy()->addHours(6)->addMinutes(5)])
            ->whereDoesntHave('logs', function ($q) {
                $q->where('message', 'like', '%peringatan 6 jam%');
            })
            ->get();

        foreach ($warningReports as $report) {
            $typeLabel = $this->typeLabel($report->type);
            $dueAt     = Carbon::parse($report->due_date)->timezone('Asia/Jakarta')->format('d M Y, H:i');

            // Log peringatan
            ReportLog::create([
                'report_id'   => $report->id,
                'user_id'     => 1,
                'status_from' => Report::SUB_PIC_WORKING,
                'status_to'   => Report::SUB_PIC_WORKING,
                'message'     => "Sistem mengirim peringatan 6 jam sebelum batas waktu pengerjaan. Deadline: {$dueAt}.",
            ]);

            $baseWarning = [
                'report_id' => $report->id,
                'type'      => 'warning_due',
                'title'     => "⚠️ Peringatan Deadline — {$typeLabel}",
                'body'      => "Laporan {$report->report_number} ({$typeLabel}) akan mencapai batas waktu 2×24 jam pada {$dueAt}. Segera selesaikan pengerjaan.",
            ];

            // Kirim ke HSE
            HseNotification::sendToRoles(['hse'], array_merge($baseWarning, ['href' => $this->typeHref($report->type, 'hse')]));
            // Kirim ke Manager
            HseNotification::sendToRoles(['manager'], array_merge($baseWarning, ['href' => url('/hse-manager/report')]));
            // Kirim ke PIC yang ditugaskan
            HseNotification::sendToPic($report->pic_id, array_merge($baseWarning, ['href' => $this->typeHref($report->type, 'pic')]));

            $this->info("Warning sent for {$report->report_number} (due: {$dueAt}).");
        }

        // ─────────────────────────────────────────────────────────────
        // 2. AUTO-CLOSE: laporan yang melewati due_date
        // ─────────────────────────────────────────────────────────────
        $expiredReports = Report::where('sub_status', Report::SUB_PIC_WORKING)
            ->where('due_date', '<', $now)
            ->get();

        foreach ($expiredReports as $report) {
            $typeLabel = $this->typeLabel($report->type);
            $dueAt     = Carbon::parse($report->due_date)->timezone('Asia/Jakarta')->format('d M Y, H:i');

            // Update status laporan
            $report->update([
                'status'     => 'closed',
                'sub_status' => Report::SUB_CLOSED,
                'hse_note'   => "Laporan ini ditutup secara otomatis oleh sistem karena PIC tidak menyelesaikan tindak lanjut dalam batas waktu 2×24 jam (deadline: {$dueAt}). Silakan koordinasikan tindak lanjut selanjutnya dengan tim HSE.",
            ]);

            // Log penutupan otomatis
            ReportLog::create([
                'report_id'   => $report->id,
                'user_id'     => 1,
                'status_from' => Report::SUB_PIC_WORKING,
                'status_to'   => Report::SUB_CLOSED,
                'message'     => "Sistem secara otomatis menutup laporan karena melewati batas waktu pengerjaan 2×24 jam (deadline: {$dueAt}). Status diubah menjadi Close.",
            ]);

            // Susun data notifikasi (href berbeda per role)
            $hseHref = $this->typeHref($report->type, 'hse');
            $picHref = $this->typeHref($report->type, 'pic');

            $baseNotif = [
                'report_id' => $report->id,
                'type'      => 'expired_close',
                'title'     => "🔴 Laporan Ditutup Otomatis — {$typeLabel}",
                'body'      => "Laporan {$report->report_number} ({$typeLabel}) telah ditutup secara otomatis karena melewati batas waktu pengerjaan 2×24 jam (deadline: {$dueAt}). PIC tidak menyelesaikan tindak lanjut tepat waktu.",
            ];

            // Kirim ke HSE Officer
            HseNotification::sendToRoles(['hse'], array_merge($baseNotif, ['href' => $hseHref]));
            // Kirim ke Manager
            HseNotification::sendToRoles(['manager'], array_merge($baseNotif, ['href' => url('/hse-manager/report')]));
            // Kirim ke PIC yang ditugaskan
            HseNotification::sendToPic($report->pic_id, array_merge($baseNotif, ['href' => $picHref]));

            $this->info("Closed: {$report->report_number} (expired at {$dueAt}).");
        }

        $this->info('Check expired reports completed.');
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'accident'         => 'Accident Report',
            'unsafe_action'    => 'Unsafe Action',
            'unsafe_condition' => 'Unsafe Condition',
            default            => 'Laporan',
        };
    }

    private function typeHref(string $type, string $role = 'hse'): string
    {
        if ($role === 'pic') {
            return match ($type) {
                'accident'         => url('/pic/accident'),
                'unsafe_action',
                'unsafe_condition' => url('/pic/incident'),
                default            => url('/'),
            };
        }
        return match ($type) {
            'accident'         => url('/hse/accident'),
            'unsafe_action',
            'unsafe_condition' => url('/hse/incident'),
            default            => url('/'),
        };
    }
}
