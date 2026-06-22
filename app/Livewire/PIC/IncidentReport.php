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
use PhpOffice\PhpWord\TemplateProcessor;


class IncidentReport extends Component
{
    use WithFileUploads;

    public string $filterStatus = 'all';
    public string $filterJenis  = 'all';
    public string $search = '';

    public ?int $uploadPlanReportId = null;
    public $planFile = null;
    public ?string $planTanggal = null;
    public ?string $planWaktu = null;
    public ?string $planLokasi = null;
    public ?string $planDepartemen = null;
    public ?string $planDeskripsi = null;
    public ?string $planTindakan = null;
    public ?string $planTindakanLanjut = null;
    public ?string $planNamaPelapor = null;
    public ?string $planNomorPelapor = null;
    public ?string $planJabatan = null;

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
        $report = Report::with('unsafeDetail')->findOrFail($id);
        $detail = $report->unsafeDetail;

        $this->uploadPlanReportId = $id;
        $this->planFile = null;
        $this->planTanggal = $detail?->tanggal_pengamatan?->format('Y-m-d') ?? now('Asia/Jakarta')->format('Y-m-d');
        $this->planWaktu = $detail?->waktu_pengamatan ? substr((string) $detail->waktu_pengamatan, 0, 5) : now('Asia/Jakarta')->format('H:i');
        $this->planLokasi = $detail?->lokasi ?? '';
        $this->planDepartemen = $detail?->departemen ?? '';
        $this->planDeskripsi = '';
        $this->planTindakan = '';
        $this->planTindakanLanjut = '';
        $this->planNamaPelapor = $report->reporter_name ?? '';
        $this->planNomorPelapor = $detail?->nip ?? '';
        $this->planJabatan = '';

        $this->dispatch('open-modal', modal: 'modalUploadPlan');
    }

    public function submitUploadPlan(): void
    {
        $this->validate([
            'planTanggal' => 'required|date',
            'planWaktu' => 'required|date_format:H:i',
            'planLokasi' => 'required|string|max:255',
            'planDepartemen' => 'required|string|max:255',
            'planDeskripsi' => 'required|string',
            'planTindakan' => 'required|string',
            'planTindakanLanjut' => 'required|string',
            'planNamaPelapor' => 'required|string|max:255',
            'planNomorPelapor' => 'nullable|string|max:100',
            'planJabatan' => 'nullable|string|max:255',
        ]);

        $report = Report::with('unsafeDetail')->findOrFail($this->uploadPlanReportId);

        if (!in_array($report->sub_status, [Report::SUB_WAITING_PIC, Report::SUB_PLAN_REJECTED_MANAGER])) {
            $this->errorMsg = 'Status laporan tidak sesuai.';
            return;
        }

        $templatePath = resource_path('file/FORMULIR PELAPORAN UA UC.docx');
        if (!file_exists($templatePath)) {
            $this->dispatch('swal:toast', type: 'error', message: 'Template formulir tidak ditemukan.');
            return;
        }

        $oldStatus = $report->sub_status;
        $folder = 'reports/plans/' . date('Y/m');
        $filename = 'Plan-' . str_replace('/', '-', $report->report_number) . '-' . time() . '.docx';
        $path = $folder . '/' . $filename;
        Storage::disk('public')->makeDirectory($folder);

        try {
            $template = new TemplateProcessor($templatePath);
            $template->setValue('tanggal', $this->planTanggal);
            $template->setValue('waktu', $this->planWaktu);
            $template->setValue('lokasi', $this->planLokasi);
            $template->setValue('unsafe_action', $report->type === 'unsafe_action' ? 'X' : '');
            $template->setValue('unsafe_condition', $report->type === 'unsafe_condition' ? 'X' : '');
            $template->setValue('departemen', $this->planDepartemen);
            $template->setValue('deskripsi', $this->planDeskripsi);
            $template->setValue('tindakan', $this->planTindakan);
            $template->setValue('tindakan_lanjut', $this->planTindakanLanjut);
            $template->setValue('nama_pelapor', $this->planNamaPelapor);
            $template->setValue('nomor_pelapor', $this->planNomorPelapor ?: '-');
            $template->setValue('jabatan', $this->planJabatan ?: '');
            $template->saveAs(Storage::disk('public')->path($path));
        } catch (\Throwable $e) {
            $this->dispatch('swal:toast', type: 'error', message: 'Gagal membuat dokumen: ' . $e->getMessage());
            return;
        }

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
            'status_from' => $oldStatus,
            'status_to'   => Report::SUB_PLAN_VERIFICATION,
            'message'     => 'PIC mengisi dan mengirim Formulir Pelaporan Inspeksi K3LL.',
        ]);

        $this->resetPlanForm();
        $this->dispatch('swal:toast', type: 'success', message: 'Formulir berhasil dibuat dan dikirim.');
        $this->dispatch('close-modal', modal: 'modalUploadPlan');
    }

    private function resetPlanForm(): void
    {
        $this->reset([
            'uploadPlanReportId',
            'planFile',
            'planTanggal',
            'planWaktu',
            'planLokasi',
            'planDepartemen',
            'planDeskripsi',
            'planTindakan',
            'planTindakanLanjut',
            'planNamaPelapor',
            'planNomorPelapor',
            'planJabatan',
        ]);
    }

    public function startWorking(int $id): void
    {
        $report = Report::findOrFail($id);
        if ($report->sub_status !== Report::SUB_PLAN_APPROVED_MANAGER) return;

        $report->update([
            'status' => 'open', 
            'sub_status' => Report::SUB_PIC_WORKING,
            'due_date' => now()->addHours(48)
        ]);

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

        $folder = 'reports/results/' . date('Y/m');
        $filename = 'Result-' . str_replace('/', '-', $report->report_number) . '-' . time() . '.' . $this->resultFile->getClientOriginalExtension();
        $path = $this->resultFile->storeAs($folder, $filename, 'public');

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
