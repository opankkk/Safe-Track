<?php

namespace App\Livewire\HSEManager;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportLog;

class IncidentReport extends Component
{
    public string $filterJenis = 'all';
    public string $filterStatus = 'all';
    public string $search = '';

    public ?int $selectedReportId = null;
    public ?Report $selectedReportData = null;
    public string $managerNote = '';
    public ?int $rejectReportId = null;
    public string $managerRejectNote = '';

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public function getReportsProperty()
    {
        return Report::with(['plan', 'action', 'attachments', 'unsafeDetail'])
            ->whereIn('type', ['unsafe_action', 'unsafe_condition'])
            ->when($this->filterJenis !== 'all', fn($q) => $q->where('type', $this->filterJenis == 'ua' ? 'unsafe_action' : 'unsafe_condition'))
            ->when($this->filterStatus !== 'all', function($q) {
                if ($this->filterStatus === 'pending') {
                    $q->whereIn('sub_status', [
                        Report::SUB_PLAN_VERIFICATION, 
                        Report::SUB_REPORT_VERIFICATION_MANAGER, 
                        Report::SUB_REPORT_PENDING_HSE
                    ]);
                } elseif ($this->filterStatus === 'open') {
                    $q->whereNotIn('sub_status', [
                        Report::SUB_PENDING_HSE, 
                        Report::SUB_CLOSED, 
                        Report::SUB_REPORT_REJECTED_MANAGER, 
                        Report::SUB_PLAN_REJECTED_MANAGER
                    ]);
                } elseif ($this->filterStatus === 'close') {
                    $q->whereIn('sub_status', [
                        Report::SUB_CLOSED, 
                        Report::SUB_REPORT_REJECTED_MANAGER, 
                        Report::SUB_PLAN_REJECTED_MANAGER
                    ]);
                }
            })
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('report_number', 'like', '%' . $this->search . '%')
                   ->orWhere('reporter_name', 'like', '%' . $this->search . '%');
            }))
            ->latest()
            ->get();
    }

    // --- Action Methods ---

    public function openApproveModal(int $id)
    {
        $this->selectedReportId = $id;
        $this->selectedReportData = Report::with(['unsafeDetail', 'pic'])->find($id);
        $this->managerNote = '';
        $this->dispatch('open-modal', modal: 'modalFollowUpPlan');
    }

    public function submitApprovePlan()
    {
        $this->validate(['managerNote' => 'required|string|min:5']);
        $report = Report::findOrFail($this->selectedReportId);
        if ($report->sub_status !== Report::SUB_PLAN_VERIFICATION) return;

        $report->plan?->update(['status' => 'approved', 'manager_note' => $this->managerNote]);
        $report->update(['status' => 'open', 'sub_status' => Report::SUB_PLAN_APPROVED_MANAGER]);
        
        \App\Models\ReportLog::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'status_from' => Report::SUB_PLAN_VERIFICATION,
            'status_to' => Report::SUB_PLAN_APPROVED_MANAGER,
            'message' => 'Manager menyetujui Plan. Catatan: ' . $this->managerNote
        ]);

        $this->successMsg = 'Plan berhasil disetujui.';
        $this->dispatch('close-modal', modal: 'modalFollowUpPlan');
    }

    public function openRejectModal(int $id)
    {
        $this->rejectReportId = $id;
        $this->selectedReportData = Report::with(['unsafeDetail', 'pic'])->find($id);
        $this->managerRejectNote = '';
        $this->dispatch('open-modal', modal: 'modalRejectPlan');
    }

    public function submitRejectPlan()
    {
        $this->validate(['managerRejectNote' => 'required|string|min:5']);
        $report = Report::findOrFail($this->rejectReportId);
        if ($report->sub_status !== Report::SUB_PLAN_VERIFICATION) return;

        $report->plan?->update(['status' => 'rejected', 'manager_note' => $this->managerRejectNote]);
        $report->update(['status' => 'open', 'sub_status' => Report::SUB_PLAN_REJECTED_MANAGER]);
        
        \App\Models\ReportLog::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'status_from' => Report::SUB_PLAN_VERIFICATION,
            'status_to' => Report::SUB_PLAN_REJECTED_MANAGER,
            'message' => 'Manager menolak Plan. Catatan: ' . $this->managerRejectNote
        ]);

        $this->errorMsg = 'Plan ditolak.';
        $this->dispatch('close-modal', modal: 'modalRejectPlan');
    }

    public function approveResult(int $id)
    {
        $report = Report::with('action')->findOrFail($id);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_VERIFICATION_MANAGER, Report::SUB_REPORT_PENDING_HSE]) || !$report->action) {
            $this->errorMsg = 'Belum ada dokumen Hasil.';
            return;
        }

        $oldStatus = $report->sub_status;
        $report->action->update(['status' => 'approved']);
        $report->update(['sub_status' => Report::SUB_REPORT_VERIFICATION_HSE]);

        \App\Models\ReportLog::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'status_from' => $oldStatus,
            'status_to' => Report::SUB_REPORT_VERIFICATION_HSE,
            'message' => 'Manager menyetujui Hasil.'
        ]);

        $this->successMsg = 'Hasil disetujui.';
    }

    public function openRejectReportModal(int $id)
    {
        $this->rejectReportId = $id;
        $this->selectedReportData = Report::with(['unsafeDetail', 'action', 'pic'])->find($id);
        $this->managerRejectNote = '';
        $this->dispatch('open-modal', modal: 'modalRejectReport');
    }

    public function submitRejectResult()
    {
        $this->validate(['managerRejectNote' => 'required|string|min:5']);
        $report = Report::findOrFail($this->rejectReportId);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_VERIFICATION_MANAGER, Report::SUB_REPORT_PENDING_HSE]) || !$report->action) {
            $this->errorMsg = 'Belum ada dokumen Hasil.';
            return;
        }

        $report->action->update(['status' => 'rejected', 'manager_note' => $this->managerRejectNote]);
        $report->update(['sub_status' => Report::SUB_REPORT_REJECTED_MANAGER]);

        \App\Models\ReportLog::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'status_from' => $report->sub_status,
            'status_to' => Report::SUB_REPORT_REJECTED_MANAGER,
            'message' => 'Manager menolak Hasil. Catatan: ' . $this->managerRejectNote
        ]);

        $this->errorMsg = 'Hasil ditolak.';
        $this->dispatch('close-modal', modal: 'modalRejectReport');
    }

    public function render()
    {
        return view('livewire.h-s-e-manager.incident-report', [
            'reports' => $this->reports,
        ])->extends('layouts.app')->section('content');
    }
}
