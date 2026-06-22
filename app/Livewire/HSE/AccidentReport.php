<?php

namespace App\Livewire\HSE;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Report;
use App\Models\ReportLog;

#[Layout('layouts.app')]
class AccidentReport extends Component
{
    public string $filterStatus = 'all';
    public string $search = '';

    // Approve: ID + catatan HSE
    public ?int $approveReportId = null;
    public string $hseTindakLanjut = '';

    // Reject
    public ?int $rejectReportId = null;

    // Approve Final
    public ?int $approveFinalReportId = null;

    // Reject Final
    public ?int $rejectFinalReportId = null;
    public string $hseNote = '';

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public function getReportsProperty()
    {
        return Report::with(['attachments', 'accidentDetail', 'action', 'plan'])
            ->where('type', 'accident')
            ->when($this->filterStatus !== 'all', function($q) {
                if ($this->filterStatus === 'pending') {
                    $q->where('sub_status', 'pending_hse');
                } elseif ($this->filterStatus === 'open') {
                    $q->whereIn('sub_status', ['waiting_pic', 'plan_verification', 'plan_rejected_manager', 'plan_approved_manager', 'pic_working', Report::SUB_REPORT_VERIFICATION_HSE, 'report_rejected_manager']);
                } elseif ($this->filterStatus === 'close') {
                    $q->where('sub_status', 'closed');
                }
            })
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('report_number', 'like', '%' . $this->search . '%')
                   ->orWhere('reporter_name', 'like', '%' . $this->search . '%');
            }))
            ->latest()
            ->get();
    }

    #[\Livewire\Attributes\On('hseApproveAccident')]
    public function approveFromJs(int $id, string $note): void
    {
        $this->approveReportId = $id;
        $this->hseTindakLanjut = $note;
        $this->submitApprove();
    }

    #[\Livewire\Attributes\On('hseRejectAccident')]
    public function rejectFromJs(int $id): void
    {
        $this->rejectReportId = $id;
        $this->submitReject();
    }

    #[\Livewire\Attributes\On('hseApproveFinalAccident')]
    public function approveFinalFromJs(int $id): void
    {
        $this->approveFinal($id);
    }

    #[\Livewire\Attributes\On('hseRejectFinalAccident')]
    public function rejectFinalFromJs(int $id, string $note): void
    {
        $this->rejectFinalReportId = $id;
        $this->hseNote = $note;
        $this->submitRejectFinal();
    }

    // HSE Setujui 
    public function openApproveModal(int $id): void
    {
        $this->approveReportId  = $id;
        $this->hseTindakLanjut  = '';
        $this->dispatch('open-modal', modal: 'modalHseApprove');
    }

    public function submitApprove(): void
    {
        $this->validate([
            'hseTindakLanjut' => 'required|string|min:5',
        ], ['hseTindakLanjut.required' => 'Catatan / pengendalian wajib diisi.']);

        $report = Report::findOrFail($this->approveReportId);

        if ($report->sub_status !== Report::SUB_PENDING_HSE) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

        $report->update([
            'status'           => 'open',
            'sub_status'       => Report::SUB_WAITING_PIC,
            'hse_tindak_lanjut'=> $this->hseTindakLanjut,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PENDING_HSE,
            'status_to'   => Report::SUB_WAITING_PIC,
            'message'     => 'HSE menyetujui laporan. Catatan: ' . $this->hseTindakLanjut,
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan disetujui dan diteruskan ke PIC.');
        $this->reset(['approveReportId', 'hseTindakLanjut']);
        $this->dispatch('close-modal', modal: 'modalHseApprove');
    }

    // HSE Tolak 
    public function openRejectModal(int $id): void
    {
        $this->rejectReportId = $id;
        $this->dispatch('open-modal', modal: 'modalHseReject');
    }

    public function submitReject(): void
    {
        $report = Report::findOrFail($this->rejectReportId);

        if ($report->sub_status !== Report::SUB_PENDING_HSE) {
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
            'status_from' => Report::SUB_PENDING_HSE,
            'status_to'   => Report::SUB_CLOSED,
            'message'     => 'HSE menolak laporan. Laporan ditutup.',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan ditolak dan ditutup.');
        $this->reset(['rejectReportId']);
        $this->dispatch('close-modal', modal: 'modalHseReject');
    }

    //HSE Setujui Final
    public function approveFinal(int $id): void
    {
        $report = Report::findOrFail($id);

        if (!in_array($report->sub_status, [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_HSE])) {
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

    // HSE Kembalikan ke PIC
    public function openRejectFinalModal(int $id): void
    {
        $this->rejectFinalReportId = $id;
        $this->hseNote = '';
        $this->dispatch('open-modal', modal: 'modalHseRejectFinal');
    }

    public function submitRejectFinal(): void
    {
        $this->validate([
            'hseNote' => 'required|string|min:5',
        ], ['hseNote.required' => 'Catatan pengembalian wajib diisi.']);

        $report = Report::findOrFail($this->rejectFinalReportId);

        if (!in_array($report->sub_status, [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_HSE])) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

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
            'message'     => 'HSE mengembalikan ke PIC. Catatan: ' . $this->hseNote,
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Laporan dikembalikan ke PIC.');
        $this->reset(['rejectFinalReportId', 'hseNote']);
        $this->dispatch('close-modal', modal: 'modalHseRejectFinal');
    }

    public function render()
    {
        return view('livewire.h-s-e.accident-report', [
            'reports' => $this->reports,
        ]);
    }
}
