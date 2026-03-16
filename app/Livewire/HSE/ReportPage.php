<?php

namespace App\Livewire\HSE;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Report;
use App\Models\ReportLog;

#[Layout('layouts.app')]
class ReportPage extends Component
{
    public string $filterJenis  = 'all';
    public string $filterStatus = 'all';
    public string $search = '';

    public ?int $rejectFinalReportId = null;
    public string $hseRejectNote = '';

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    #[\Livewire\Attributes\On('hseApproveFinalReport')]
    public function approveFinal(int $id): void
    {
        $report = Report::findOrFail($id);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_VERIFICATION_HSE, Report::SUB_REPORT_PENDING_HSE])) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

        $report->update([
            'status'     => 'closed',
            'sub_status' => Report::SUB_CLOSED,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => $report->sub_status,
            'status_to'   => Report::SUB_CLOSED,
            'message'     => 'HSE menyetujui hasil akhir. Laporan ditutup (Close).',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan berhasil ditutup (Close).');
    }

    #[\Livewire\Attributes\On('hseApproveFinalReport')]
    public function approveFinalFromJs(int $id): void
    {
        $this->approveFinal($id);
    }

    #[\Livewire\Attributes\On('hseRejectFinalReport')]
    public function rejectFinalFromJs(int $id, string $note): void
    {
        $this->rejectFinalReportId = $id;
        $this->hseRejectNote = $note;
        $this->submitRejectFinal();
    }

    public function getReportsProperty()
    {
        return Report::with(['attachments', 'unsafeDetail', 'accidentDetail', 'plan', 'action'])
            ->whereIn('type', ['unsafe_action', 'unsafe_condition', 'accident'])
            ->where('sub_status', Report::SUB_REPORT_VERIFICATION_HSE)
            ->when($this->filterJenis !== 'all', function ($q) {
                $map = ['ua' => 'unsafe_action', 'uc' => 'unsafe_condition', 'accident' => 'accident'];
                return $q->where('type', $map[$this->filterJenis] ?? $this->filterJenis);
            })
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('report_number', 'like', '%' . $this->search . '%')
                   ->orWhere('reporter_name', 'like', '%' . $this->search . '%');
            }))
            ->latest()
            ->get();
    }

    // HSE Kembalikan ke PIC 
    public function submitRejectFinal(): void
    {
        $this->validate([
            'hseRejectNote' => 'required|string|min:5',
        ], ['hseRejectNote.required' => 'Catatan pengembalian wajib diisi.']);

        $report = Report::findOrFail($this->rejectFinalReportId);
        if ($report->sub_status !== Report::SUB_REPORT_VERIFICATION_HSE) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

        $report->update([
            'status'     => 'open',
            'sub_status' => Report::SUB_REPORT_REJECTED_HSE,
            'hse_note'   => $this->hseRejectNote,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_REPORT_VERIFICATION_HSE,
            'status_to'   => Report::SUB_REPORT_REJECTED_HSE,
            'message'     => 'HSE mengembalikan ke PIC. Catatan: ' . $this->hseRejectNote,
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan dikembalikan ke PIC.');
        $this->reset(['rejectFinalReportId', 'hseRejectNote']);
    }

    public function render()
    {
        return view('livewire.h-s-e.report', [
            'reports' => $this->reports,
        ]);
    }
}
