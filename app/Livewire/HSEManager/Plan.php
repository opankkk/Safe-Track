<?php

namespace App\Livewire\HSEManager;

use App\Models\Report;
use App\Models\ReportLog;
use Livewire\Component;

class Plan extends Component
{
    public string $search = '';
    public string $filterType = 'all';

    public ?int $selectedReportId = null;
    public ?Report $selectedReportData = null;
    public string $managerNote = '';

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public function getReportsProperty()
    {
        return Report::with(['plan', 'pic'])
            ->whereIn('sub_status', [
                Report::SUB_PLAN_VERIFICATION,
                Report::SUB_PLAN_APPROVED_MANAGER,
                Report::SUB_PLAN_REJECTED_MANAGER,
                Report::SUB_PIC_WORKING,
                Report::SUB_REPORT_VERIFICATION_MANAGER,
                Report::SUB_REPORT_PENDING_HSE,
                Report::SUB_REPORT_REJECTED_MANAGER,
                Report::SUB_CLOSED
            ])
            ->whereHas('plan')
            ->when($this->filterType !== 'all', fn($q) => $q->where('type', $this->filterType))
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('report_number', 'like', '%' . $this->search . '%')
                   ->orWhereHas('pic', fn($q3) => $q3->where('name', 'like', '%' . $this->search . '%'));
            }))
            ->latest()
            ->get();
    }

    public function openApproveModal(int $id)
    {
        $this->selectedReportId = $id;
        $this->selectedReportData = Report::with(['accidentDetail', 'unsafeDetail'])->find($id);
        $this->managerNote = '';
        $this->dispatch('open-modal', modal: 'modalFollowUpPlan');
    }

    public function submitApprovePlan()
    {
        $this->validate([
            'managerNote' => 'required|string|min:5'
        ], [
            'managerNote.required' => 'Catatan plan tindak lanjut wajib diisi.',
            'managerNote.min' => 'Catatan minimal 5 karakter.'
        ]);

        $report = Report::findOrFail($this->selectedReportId);
        if ($report->sub_status !== Report::SUB_PLAN_VERIFICATION) return;

        $report->plan?->update([
            'status' => 'approved',
            'manager_note' => $this->managerNote
        ]);
        
        $report->update([
            'status'     => 'open',
            'sub_status' => Report::SUB_PLAN_APPROVED_MANAGER,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PLAN_VERIFICATION,
            'status_to'   => Report::SUB_PLAN_APPROVED_MANAGER,
            'message'     => 'Manager menyetujui Plan Tindak Lanjut. Catatan: ' . $this->managerNote,
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Plan berhasil disetujui. PIC dapat memulai pengerjaan.');
        $this->dispatch('close-modal', modal: 'modalFollowUpPlan');
        $this->reset(['selectedReportId', 'managerNote']);
    }
    
    public ?int $rejectReportId = null;
    public string $managerRejectNote = '';

    public function openRejectModal(int $id)
    {
        $this->rejectReportId = $id;
        $this->selectedReportData = Report::with(['accidentDetail', 'unsafeDetail'])->find($id);
        $this->managerRejectNote = '';
        $this->dispatch('open-modal', modal: 'modalRejectPlan');
    }

    public function submitRejectPlan()
    {
        $this->validate([
            'managerRejectNote' => 'required|string|min:5'
        ], [
            'managerRejectNote.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $report = Report::findOrFail($this->rejectReportId);
        if ($report->sub_status !== Report::SUB_PLAN_VERIFICATION) return;

        $report->plan?->update([
            'status'       => 'rejected',
            'manager_note' => $this->managerRejectNote,
        ]);

        $report->update([
            'status'     => 'open',
            'sub_status' => Report::SUB_PLAN_REJECTED_MANAGER, // PIC must re-upload plan
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PLAN_VERIFICATION,
            'status_to'   => Report::SUB_PLAN_REJECTED_MANAGER,
            'message'     => 'Manager menolak Plan. Catatan: ' . $this->managerRejectNote . '. Silakan upload ulang.',
        ]);

        $this->dispatch('swal:toast', type: 'error', message: 'Plan ditolak. PIC diberitahu untuk upload ulang.');
        $this->dispatch('close-modal', modal: 'modalRejectPlan');
        $this->reset(['rejectReportId', 'managerRejectNote']);
    }

    public function approveResult(int $id)
    {
        $report = Report::with('action')->findOrFail($id);
        
        if (!in_array($report->sub_status, [Report::SUB_REPORT_VERIFICATION_MANAGER, Report::SUB_REPORT_PENDING_HSE]) || !$report->action) {
            $this->errorMsg = 'Belum ada dokumen Hasil (Manager Approval).';
            return;
        }

        $oldStatus = $report->sub_status;
        $report->action->update(['status' => 'approved']);
        $report->update([
            'status'     => 'open',
            'sub_status' => Report::SUB_REPORT_VERIFICATION_HSE,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => $oldStatus,
            'status_to'   => Report::SUB_REPORT_VERIFICATION_HSE,
            'message'     => 'Manager menyetujui Hasil Tindak Lanjut. Status: Verifikasi Hasil HSE.',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Hasil disetujui dan diteruskan ke HSE untuk review final.');
    }

    public function openRejectReportModal(int $id)
    {
        $this->rejectReportId = $id;
        $this->selectedReportData = Report::with(['accidentDetail', 'unsafeDetail', 'action'])->find($id);
        $this->managerRejectNote = '';
        $this->dispatch('open-modal', modal: 'modalRejectReport');
    }

    public function submitRejectResult()
    {
        $this->validate([
            'managerRejectNote' => 'required|string|min:5'
        ]);

        $report = Report::findOrFail($this->rejectReportId);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_VERIFICATION_MANAGER, Report::SUB_REPORT_PENDING_HSE]) || !$report->action) {
            $this->errorMsg = 'Belum ada dokumen Hasil.';
            return;
        }

        $report->action->update([
            'status'       => 'rejected',
            'manager_note' => $this->managerRejectNote,
        ]);

        $report->update([
            'status'     => 'open',
            'sub_status' => Report::SUB_REPORT_REJECTED_MANAGER, 
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => $report->sub_status,
            'status_to'   => Report::SUB_REPORT_REJECTED_MANAGER,
            'message'     => 'Manager menolak Hasil. Catatan: ' . $this->managerRejectNote,
        ]);

        $this->dispatch('swal:toast', type: 'error', message: 'Hasil PIC ditolak dan dikembalikan ke PIC.');
        $this->dispatch('close-modal', modal: 'modalRejectReport');
        $this->reset(['rejectReportId', 'managerRejectNote']);
    }

    public function render()
    {
        return view('livewire.h-s-e-manager.plan', [
            'reports' => $this->reports,
        ])->extends('layouts.app')->section('content');
    }
}
