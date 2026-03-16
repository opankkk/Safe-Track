<?php

namespace App\Livewire\HSEManager;

use App\Models\Report as ReportModel;
use App\Models\ReportLog;
use Livewire\Component;

class Report extends Component
{
    public string $filterJenis = 'all';
    public string $filterStatus = 'all';
    public string $search = '';

    public ?int $selectedReportId = null;
    public ?ReportModel $selectedReportData = null;

    public ?int $rejectReportId = null;
    public string $managerRejectNote = '';

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public function getReportsProperty()
    {
        return ReportModel::with(['action', 'pic', 'plan', 'attachments'])
            ->whereIn('sub_status', [
                ReportModel::SUB_PIC_WORKING, 
                ReportModel::SUB_REPORT_VERIFICATION_MANAGER,
                ReportModel::SUB_REPORT_VERIFICATION_HSE,
                ReportModel::SUB_REPORT_REJECTED_MANAGER,
                ReportModel::SUB_REPORT_PENDING_HSE,
                ReportModel::SUB_CLOSED
            ])
            ->whereHas('action', function($q){
                $q->whereIn('status', ['pending', 'approved', 'rejected']);
            })
            ->when($this->filterJenis !== 'all', function($q) {
                if ($this->filterJenis === 'ua') $q->where('type', 'unsafe_action');
                elseif ($this->filterJenis === 'uc') $q->where('type', 'unsafe_condition');
                elseif ($this->filterJenis === 'accident') $q->where('type', 'accident');
            })
            ->when($this->filterStatus !== 'all', function($q) {
                if ($this->filterStatus === 'pending') {
                    $q->whereIn('sub_status', [ReportModel::SUB_REPORT_VERIFICATION_MANAGER, ReportModel::SUB_REPORT_PENDING_HSE]);
                } elseif ($this->filterStatus === 'open') {
                    $q->whereIn('sub_status', [
                        ReportModel::SUB_REPORT_VERIFICATION_HSE,
                        ReportModel::SUB_REPORT_REJECTED_MANAGER,
                        ReportModel::SUB_PIC_WORKING
                    ]);
                } elseif ($this->filterStatus === 'close') {
                    $q->where('sub_status', ReportModel::SUB_CLOSED);
                }
            })
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('report_number', 'like', '%' . $this->search . '%')
                   ->orWhereHas('pic', fn($q3) => $q3->where('name', 'like', '%' . $this->search . '%'));
            }))
            ->latest('updated_at')
            ->get();
    }

    public function approveResult(int $id)
    {
        $report = ReportModel::with(['accidentDetail', 'unsafeDetail'])->findOrFail($id);
        $this->selectedReportId = $id;
        $this->selectedReportData = $report;
        
        if (!in_array($report->sub_status, [ReportModel::SUB_REPORT_VERIFICATION_MANAGER, ReportModel::SUB_REPORT_PENDING_HSE]) || !$report->action) {
            $this->errorMsg = 'Belum ada dokumen Hasil (Manager Approval).';
            return;
        }

        $report->action->update(['status' => 'approved']);
        $report->update([
            'status'     => 'open',
            'sub_status' => ReportModel::SUB_REPORT_VERIFICATION_HSE,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => ReportModel::SUB_REPORT_VERIFICATION_MANAGER,
            'status_to'   => ReportModel::SUB_REPORT_VERIFICATION_HSE,
            'message'     => 'Manager menyetujui Hasil Tindak Lanjut. Status: Verifikasi Hasil HSE.',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Hasil disetujui dan diteruskan ke HSE untuk review final.');
    }

    public function openRejectModal(int $id)
    {
        $this->rejectReportId = $id;
        $this->selectedReportData = ReportModel::with(['accidentDetail', 'unsafeDetail'])->find($id);
        $this->managerRejectNote = '';
        $this->dispatch('open-modal', modal: 'modalRejectReport');
    }

    public function submitRejectResult()
    {
        $this->validate([
            'managerRejectNote' => 'required|string|min:5'
        ], [
            'managerRejectNote.required' => 'Catatan penolakan wajib diisi.',
            'managerRejectNote.min' => 'Catatan minimal 5 karakter.'
        ]);

        $report = ReportModel::findOrFail($this->rejectReportId);

        if (!in_array($report->sub_status, [ReportModel::SUB_REPORT_VERIFICATION_MANAGER, ReportModel::SUB_REPORT_PENDING_HSE]) || !$report->action) {
            $this->errorMsg = 'Belum ada dokumen Hasil.';
            return;
        }

        $report->action->update([
            'status'       => 'rejected',
            'manager_note' => $this->managerRejectNote,
        ]);

        $report->update([
            'status'     => 'open',
            'sub_status' => ReportModel::SUB_REPORT_REJECTED_MANAGER, 
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => ReportModel::SUB_REPORT_VERIFICATION_MANAGER,
            'status_to'   => ReportModel::SUB_REPORT_REJECTED_MANAGER,
            'message'     => 'Manager menolak Hasil. Catatan: ' . $this->managerRejectNote,
        ]);

        $this->dispatch('swal:toast', type: 'error', message: 'Hasil PIC ditolak');
        $this->dispatch('close-modal', modal: 'mod  alRejectReport');
        $this->reset(['rejectReportId', 'managerRejectNote']);
    }

    public function render()
    {
        return view('livewire.h-s-e-manager.report', [
            'reports' => $this->reports,
        ])->extends('layouts.app')->section('content');
    }
}
