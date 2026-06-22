<?php

namespace App\Livewire\HSE;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Report;
use App\Models\ReportLog;

#[Layout('layouts.app')]
class IncidentReport extends Component
{
    public string $filterStatus = 'all';
    public string $filterJenis  = 'all';
    public string $search = '';

    public ?int $approveReportId = null;
    public string $hseTindakLanjut = '';

    public ?int $rejectReportId = null;

    public ?int $rejectFinalReportId = null;
    public string $hseNote = '';

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    #[\Livewire\Attributes\On('hseApproveIncident')]
    public function approveFromJs(int $id, string $note): void
    {
        $this->approveReportId = $id;
        $this->hseTindakLanjut = $note;
        $this->submitApprove();
    }

    #[\Livewire\Attributes\On('hseRejectIncident')]
    public function rejectFromJs(int $id): void
    {
        $this->rejectReportId = $id;
        $this->submitReject();
    }

    #[\Livewire\Attributes\On('hseApproveFinalIncident')]
    public function approveFinalFromJs(int $id): void
    {
        $this->approveFinal($id);
    }

    #[\Livewire\Attributes\On('hseRejectFinalIncident')]
    public function rejectFinalFromJs(int $id, string $note): void
    {
        $this->rejectFinalReportId = $id;
        $this->hseNote = $note;
        $this->submitRejectFinal();
    }

    public function getReportsProperty()
    {
        return Report::with(['plan', 'action', 'attachments', 'unsafeDetail'])
            ->whereIn('type', ['unsafe_action', 'unsafe_condition'])
            ->when($this->filterJenis !== 'all', function($q) {
                $map = ['ua' => 'unsafe_action', 'uc' => 'unsafe_condition'];
                return $q->where('type', $map[$this->filterJenis] ?? $this->filterJenis);
            })
            ->when($this->filterStatus !== 'all', function($q) {
                if ($this->filterStatus === 'pending') {
                    $q->where('sub_status', 'pending_hse');
                } elseif ($this->filterStatus === 'open') {
                    $q->whereIn('sub_status', ['waiting_pic', 'plan_verification', 'plan_rejected_manager', 'plan_approved_manager', 'pic_working', Report::SUB_REPORT_VERIFICATION_HSE, 'report_rejected_manager']);
                } elseif ($this->filterStatus === 'close') {
                    $q->where('sub_status', 'closed');
                }
            })
            ->when($this->search, fn($q) => $q->where(function($q2) {
                $q2->where('report_number', 'like', '%'.$this->search.'%')
                   ->orWhere('reporter_name', 'like', '%'.$this->search.'%');
            }))
            ->latest()
            ->get();
    }

    public function openApproveModal(int $id): void
    {
        $this->approveReportId = $id;
        $this->hseTindakLanjut = '';
        $this->dispatch('open-modal', modal: 'modalHseApprove');
    }

    public function submitApprove(): void
    {
        $this->validate(['hseTindakLanjut' => 'required|string|min:5'], [
            'hseTindakLanjut.required' => 'Catatan tindak lanjut wajib diisi.',
        ]);

        $report = Report::findOrFail($this->approveReportId);
        if ($report->sub_status !== Report::SUB_PENDING_HSE) {
            $this->errorMsg = 'Laporan sudah diproses.'; return;
        }

        $report->update([
            'status'            => 'open',
            'sub_status'        => Report::SUB_WAITING_PIC,
            'hse_tindak_lanjut' => $this->hseTindakLanjut,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PENDING_HSE,
            'status_to'   => Report::SUB_WAITING_PIC,
            'message'     => 'HSE menyetujui. Tindak lanjut: ' . $this->hseTindakLanjut,
        ]); 

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan disetujui dan diteruskan ke PIC.');
        $this->reset(['approveReportId', 'hseTindakLanjut']);
        $this->dispatch('close-modal', modal: 'modalHseApprove');
    }

    public function openRejectModal(int $id): void
    {
        $this->rejectReportId = $id;
        $this->dispatch('open-modal', modal: 'modalHseReject');
    }

    public function submitReject(): void
    {
        $report = Report::findOrFail($this->rejectReportId);
        if ($report->sub_status !== Report::SUB_PENDING_HSE) {
            $this->errorMsg = 'Laporan sudah diproses.'; return;
        }

        $report->update(['status' => 'closed', 'sub_status' => Report::SUB_CLOSED]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PENDING_HSE,
            'status_to'   => Report::SUB_CLOSED,
            'message'     => 'HSE menolak laporan. Laporan ditutup.',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan ditolak.');
        $this->reset(['rejectReportId']);
        $this->dispatch('close-modal', modal: 'modalHseReject');
    }

    public function approveFinal(int $id): void
    {
        $report = Report::findOrFail($id);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_HSE])) return;

        $report->update(['status' => 'closed', 'sub_status' => Report::SUB_CLOSED]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => $report->sub_status,
            'status_to'   => Report::SUB_CLOSED,
            'message'     => 'HSE menyetujui hasil akhir. Laporan ditutup (Close).',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan selesai dan ditutup.');
    }

    public function openRejectFinalModal(int $id): void
    {
        $this->rejectFinalReportId = $id;
        $this->hseNote = '';
        $this->dispatch('open-modal', modal: 'modalHseRejectFinal');
    }

    public function submitRejectFinal(): void
    {
        $this->validate(['hseNote' => 'required|string|min:5']);

        $report = Report::findOrFail($this->rejectFinalReportId);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_HSE])) return;

        $report->update([
            'status'     => 'open',
            'sub_status' => Report::SUB_REPORT_REJECTED_HSE,
            'hse_note'   => $this->hseNote,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => $report->sub_status,
            'status_to'   => Report::SUB_REPORT_REJECTED_HSE,
            'message'     => 'HSE menolak hasil. Catatan: ' . $this->hseNote,
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Penolakan berhasil dikirim ke PIC.');
        $this->reset(['rejectFinalReportId', 'hseNote']);
        $this->dispatch('close-modal', modal: 'modalHseRejectFinal');
    }

    public function render()
    {
        return view('livewire.h-s-e.incident-report', [
            'reports' => $this->reports,
        ]);
    }
}
