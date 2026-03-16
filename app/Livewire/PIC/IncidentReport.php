<?php

namespace App\Livewire\PIC;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Report;
use App\Models\ReportPlans;
use App\Models\ReportActions;
use App\Models\ReportLog;
use Illuminate\Support\Facades\Storage;


class IncidentReport extends Component
{
    use WithFileUploads;

    public string $filterStatus = 'all';
    public string $filterJenis  = 'all';
    public string $search = '';

    public ?int $uploadPlanReportId = null;
    public $planFile = null;

    public ?int $uploadResultReportId = null;
    public $resultFile = null;

    public ?int $acknowledgeRejectedReportId = null;

    public ?string $successMsg = null;
    public ?string $errorMsg   = null;

    public function getReportsProperty()
    {
        return Report::with(['plan', 'action', 'attachments', 'unsafeDetail'])
            ->whereIn('type', ['unsafe_action', 'unsafe_condition'])
            ->where('sub_status', '!=', Report::SUB_PENDING_HSE)
            ->when($this->filterJenis !== 'all', function ($q) {
                $map = ['ua' => 'unsafe_action', 'uc' => 'unsafe_condition'];
                return $q->where('type', $map[$this->filterJenis] ?? $this->filterJenis);
            })
            ->when($this->filterStatus !== 'all', function($q) {
                if ($this->filterStatus === 'pending') {
                    $q->whereIn('sub_status', [Report::SUB_WAITING_PIC, Report::SUB_PLAN_REJECTED_MANAGER, Report::SUB_REPORT_REJECTED_MANAGER, Report::SUB_REPORT_REJECTED_HSE]);
                } elseif ($this->filterStatus === 'open') {
                    $q->whereIn('sub_status', [Report::SUB_PLAN_VERIFICATION, Report::SUB_PLAN_APPROVED_MANAGER, Report::SUB_PIC_WORKING, Report::SUB_REPORT_VERIFICATION_MANAGER, Report::SUB_REPORT_VERIFICATION_HSE]);
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

    public function openUploadPlanModal(int $id): void
    {
        $this->uploadPlanReportId = $id;
        $this->planFile = null;
        $this->dispatch('open-modal', modal: 'modalUploadPlan');
    }

    public function submitUploadPlan(): void
    {
        $this->validate(['planFile' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120']);

        $report = Report::findOrFail($this->uploadPlanReportId);

        if (!in_array($report->sub_status, [Report::SUB_WAITING_PIC, Report::SUB_PLAN_REJECTED_MANAGER])) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

        $path = $this->planFile->store('reports/plans', 'public');

        if ($report->plan) {
            Storage::disk('public')->delete($report->plan->file_path);
            $report->plan->delete();
        }

        ReportPlans::create([
            'report_id'   => $report->id,
            'uploaded_by' => auth()->id(),
            'file_path'   => $path,
            'status'      => 'pending',
        ]);

        $report->update(['status' => 'open', 'sub_status' => Report::SUB_PLAN_VERIFICATION]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => $report->getOriginal('sub_status'),
            'status_to'   => Report::SUB_PLAN_VERIFICATION,
            'message'     => 'PIC mengupload dokumen Plan Tindak Lanjut.',
        ]);

        $this->reset(['uploadPlanReportId', 'planFile']);
        $this->dispatch('swal:toast', type: 'success', message: 'Dokumen Plan berhasil diupload.');
        $this->dispatch('close-modal', modal: 'modalUploadPlan');
    }

    public function startWorking(int $id): void
    {
        $report = Report::findOrFail($id);
        if ($report->sub_status !== Report::SUB_PLAN_APPROVED_MANAGER) return;

        $report->update(['status' => 'open', 'sub_status' => Report::SUB_PIC_WORKING]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PLAN_APPROVED_MANAGER,
            'status_to'   => Report::SUB_PIC_WORKING,
            'message'     => 'PIC mengkonfirmasi mulai pengerjaan.',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Status: PIC Dalam Pengerjaan.');
    }

    public function openUploadResultModal(int $id): void
    {
        $this->uploadResultReportId = $id;
        $this->resultFile = null;
        $this->dispatch('open-modal', modal: 'modalUploadResult');
    }

    public function submitUploadResult(): void
    {
        $this->validate(['resultFile' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120']);

        $report = Report::findOrFail($this->uploadResultReportId);
        if (!in_array($report->sub_status, [Report::SUB_PIC_WORKING, Report::SUB_REPORT_REJECTED_MANAGER, Report::SUB_REPORT_REJECTED_HSE])) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

        $path = $this->resultFile->store('reports/results', 'public');

        if ($report->action) {
            Storage::disk('public')->delete($report->action->file_path);
            $report->action->delete();
        }

        ReportActions::create([
            'report_id'   => $report->id,
            'uploaded_by' => auth()->id(),
            'file_path'   => $path,
            'status'      => 'pending',
        ]);

        $report->update([
            'sub_status' => Report::SUB_REPORT_VERIFICATION_MANAGER,
        ]);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_PIC_WORKING,
            'status_to'   => Report::SUB_REPORT_VERIFICATION_MANAGER,
            'message'     => 'PIC mengupload dokumen Hasil Tindak Lanjut. Status: Verifikasi Hasil Manager.',
        ]);

        $this->reset(['uploadResultReportId', 'resultFile']);
        $this->dispatch('swal:toast', type: 'success', message: 'Dokumen Hasil berhasil diupload. Menunggu persetujuan Manager.');
        $this->dispatch('close-modal', modal: 'modalUploadResult');
    }

    public function acknowledgeRejected(int $id): void
    {
        $report = Report::findOrFail($id);
        if (!in_array($report->sub_status, [Report::SUB_REPORT_REJECTED_MANAGER, Report::SUB_REPORT_REJECTED_HSE, Report::SUB_PIC_WORKING])) return;

        $report->update(['status' => 'open', 'sub_status' => Report::SUB_PIC_WORKING, 'hse_note' => null]);

        if ($report->action) $report->action->update(['status' => 'rejected']);

        ReportLog::create([
            'report_id'   => $report->id,
            'user_id'     => auth()->id() ?? 1,
            'status_from' => Report::SUB_REPORT_REJECTED_MANAGER,
            'status_to'   => Report::SUB_PIC_WORKING,
            'message'     => 'PIC menerima penolakan dan kembali dalam pengerjaan.',
        ]);

        $this->dispatch('swal:toast', type: 'success', message: 'Kembali ke PIC Dalam Pengerjaan.');
    }

    public function render()
    {
        return view('livewire.p-i-c.incident-report', [
            'reports' => $this->reports,
        ])->extends('layouts.app')->section('content');
    }
}
