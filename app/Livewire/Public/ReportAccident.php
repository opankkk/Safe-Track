<?php

namespace App\Livewire\Public;

use App\Services\AccidentPelaporanPdfGenerator;
use App\Services\AccidentInvestigationPdfGenerator;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportAccident extends ReportUnsafe
{

    public $jenis_insiden;
    public $lampiran_pelaporan;
    public ?string $generated_pelaporan_path = null;
    public ?string $generated_pelaporan_name = null;
    public ?string $generated_investigasi_path = null;
    public ?string $generated_investigasi_name = null;

    public array $pelaporan = [
        'nomor_dokumen' => 'HSE-PJK/FR 24-01',
        'tanggal_terbit' => '',
        'jenis_insiden' => '',
        'nama' => '',
        'tanggal_lahir' => '',
        'jenis_kelamin' => '',
        'alamat' => '',
        'departemen' => '',
        'tempat' => '',
        'tanggal' => '',
        'pukul' => '',
        'uraian' => '',
        'apd' => '',
        'apd_alasan' => '',
        'keterangan_luka' => '',
        'koordinator' => '',
        'kepala_divisi' => '',
    ];
    public array $investigasi = [];
    
    public $nama_pelapor;
    public $nip;
    public $no_handphone;
    public $no_telepon;
    public $jenis_kelamin;
    public $lokasi_kerja;
    public $lokasi_kerja_lain;
    public $departemen;
    public $nama_korban;
    
    public $tempat;
    public $tanggal;
    public $pukul;
    public $uraian_insiden;
    public $foto_insiden;
    
    public $apd;
    public $apd_alasan;
    
    public $kondisi_korban;
    public $kondisi_lain;
    
    public $kerusakan_property;
    public $pencemaran_lingkungan;
    
    public $tindak_lanjut;
    public $tindak_lanjut_lain;
    
    public $email_atasan = '';

    public function mount(): void
    {
        $this->pelaporan['tanggal_terbit'] = $this->currentIssueDate();
        $this->resetInvestigationForm();
    }

    protected function rules()
    {
        return [
            'jenis_insiden' => 'required|string',
            'lampiran_pelaporan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nama_pelapor' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'no_handphone' => 'required|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|string',
            'lokasi_kerja' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'nama_korban' => 'nullable|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
            'uraian_insiden' => 'required|string',
            'foto_insiden' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'apd' => 'required|string',
            'apd_alasan' => 'nullable|string',
            'kondisi_korban' => 'required|string',
            'kerusakan_property' => 'required|string',
            'pencemaran_lingkungan' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'email_atasan' => 'required|email'
        ];
    }

    public function updatedDepartemen($value)
    {
        $emails = [
            'HSE' => 'angga.trilaksono@pamitra.co.id',
            'Procurement' => 'adi.wibowo@pamitra.co.id',
            'Project Manager' => 'fajar.pratama@pamitra.co.id',
            'BOD & GM' => 'shaffan.zain@pamitra.co.id',
            'EBD' => 'farid@pamitra.co.id',
            'FAT' => 'guruh.alvianda@pamitra.co.id',
            'HCFC' => 'hanifah.junior@pamitra.co.id',
            'IT' => 'it@pamitra.co.id',
            'Legal' => 'legal@pamitra.co.id',
            'Workshop' => 'erik.dewanto@pamitra.co.id',
        ];

        $this->email_atasan = $emails[$value] ?? '';
    }

    public function generatePelaporanPdf(AccidentPelaporanPdfGenerator $generator): void
    {
        $this->pelaporan['tanggal_terbit'] = $this->currentIssueDate();

        $validated = $this->validate([
            'pelaporan.nomor_dokumen' => 'required|string|max:100',
            'pelaporan.tanggal_terbit' => 'nullable|string|max:100',
            'pelaporan.jenis_insiden' => 'required|in:Near miss,Kecelakaan perorangan,Gangguan Kesehatan',
            'pelaporan.nama' => 'required|string|max:255',
            'pelaporan.tanggal_lahir' => 'nullable|date',
            'pelaporan.jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'pelaporan.alamat' => 'nullable|string|max:500',
            'pelaporan.departemen' => 'required|string|max:100',
            'pelaporan.tempat' => 'required|string|max:255',
            'pelaporan.tanggal' => 'required|date',
            'pelaporan.pukul' => 'required|date_format:H:i',
            'pelaporan.uraian' => 'required|string|max:1000',
            'pelaporan.apd' => 'required|in:Ya,Tidak',
            'pelaporan.apd_alasan' => 'nullable|string|max:255',
            'pelaporan.keterangan_luka' => 'required|string|max:1000',
            'pelaporan.koordinator' => 'nullable|string|max:255',
            'pelaporan.kepala_divisi' => 'nullable|string|max:255',
        ]);

        if ($this->generated_pelaporan_path) {
            Storage::disk('local')->delete($this->generated_pelaporan_path);
        }

        $filename = 'form-pelaporan-kecelakaan-' . now()->format('Ymd-His') . '.pdf';
        $path = 'generated/accident/' . session()->getId() . '/' . $filename;
        Storage::disk('local')->put($path, $generator->generate($validated['pelaporan']));

        $this->generated_pelaporan_path = $path;
        $this->generated_pelaporan_name = $filename;
        $this->dispatch('pelaporan-pdf-generated');
        $this->dispatch('swal:toast', type: 'success', message: 'Formulir pelaporan berhasil dibuat menjadi PDF.');
    }

    public function downloadGeneratedPelaporan()
    {
        if (! $this->generated_pelaporan_path || ! Storage::disk('local')->exists($this->generated_pelaporan_path)) {
            $this->dispatch('swal:toast', type: 'error', message: 'PDF formulir belum dibuat.');

            return null;
        }

        return Storage::disk('local')->download(
            $this->generated_pelaporan_path,
            $this->generated_pelaporan_name ?? 'form-pelaporan-kecelakaan.pdf'
        );
    }

    public function generateInvestigationPdf(AccidentInvestigationPdfGenerator $generator): void
    {
        $this->investigasi['tanggal_terbit'] = $this->currentIssueDate();
        $validated = $this->validate([
            'investigasi.nomor_dokumen_1' => 'required|string|max:100',
            'investigasi.nomor_dokumen_2' => 'required|string|max:100',
            'investigasi.halaman_1' => 'required|string|max:30',
            'investigasi.halaman_2' => 'required|string|max:30',
            'investigasi.jenis_insiden' => 'required|string',
            'investigasi.nama_payroll' => 'required|string|max:255',
            'investigasi.tanggal' => 'nullable|date',
            'investigasi.jam' => 'nullable|date_format:H:i',
            'investigasi.departemen' => 'nullable|string|max:100',
            'investigasi.factors' => 'array',
            'investigasi.tindakan' => 'array',
        ]);
        $data = array_merge($this->investigasi, $validated['investigasi']);
        if ($this->generated_investigasi_path) Storage::disk('local')->delete($this->generated_investigasi_path);
        $filename = 'laporan-investigasi-kecelakaan-' . now()->format('Ymd-His') . '.pdf';
        $path = 'generated/accident/' . session()->getId() . '/' . $filename;
        Storage::disk('local')->put($path, $generator->generate($data));
        $this->generated_investigasi_path = $path;
        $this->generated_investigasi_name = $filename;
        $this->dispatch('investigasi-pdf-generated');
        $this->dispatch('swal:toast', type: 'success', message: 'Laporan investigasi berhasil dibuat menjadi PDF.');
    }

    public function downloadGeneratedInvestigation()
    {
        if (! $this->generated_investigasi_path || ! Storage::disk('local')->exists($this->generated_investigasi_path)) return null;
        return Storage::disk('local')->download($this->generated_investigasi_path, $this->generated_investigasi_name ?? 'laporan-investigasi.pdf');
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $report = Report::create([
                'type' => 'accident',
                'reporter_name' => $this->nama_pelapor,
                'superior_email' => $this->email_atasan,
                'status' => 'pending',
                'sub_status' => Report::SUB_PENDING_HSE
            ]);

            $kondisiFix = $this->kondisi_korban === 'Yang lain' ? $this->kondisi_lain : $this->kondisi_korban;

            $tindakLanjutFix = $this->tindak_lanjut === 'Lainnya' ? $this->tindak_lanjut_lain : $this->tindak_lanjut;

            $report->accidentDetail()->create([
                'jenis_insiden' => $this->jenis_insiden,
                'no_handphone' => $this->no_handphone,
                'nip' => $this->nip,
                'no_telepon' => $this->no_telepon,
                'jenis_kelamin' => $this->jenis_kelamin,
                'lokasi_kerja' => $this->lokasi_kerja,
                'departemen' => $this->departemen,
                'nama_korban' => $this->nama_korban,
                'tempat' => $this->tempat,
                'tanggal' => $this->tanggal,
                'pukul' => $this->pukul,
                'uraian_insiden' => $this->uraian_insiden,
                'apd' => $this->apd,
                'apd_alasan' => $this->apd === 'Tidak / Lainnya' ? $this->apd_alasan : null,
                'kondisi_korban' => [$kondisiFix],
                'kerusakan_property' => $this->kerusakan_property,
                'pencemaran_lingkungan' => $this->pencemaran_lingkungan,
                'tindak_lanjut' => $tindakLanjutFix,
            ]);

            $year = date('Y');
            $month = date('m');
            $folder = "reports/accident/{$year}/{$month}";
            $pelaporanPdfForMerge = null;
            $investigasiPdfForMerge = null;

            if ($this->foto_insiden) {
                $filename = 'evidence_' . time() . '_' . uniqid() . '.' . $this->foto_insiden->getClientOriginalExtension();
                $path = $this->foto_insiden->storeAs($folder, $filename, 'public');
                $report->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $this->foto_insiden->getClientOriginalName(),
                    'category' => 'evidence'
                ]);
            }

            if ($this->lampiran_pelaporan) {
                $filename = 'form_pelaporan_' . time() . '_' . uniqid() . '.' . $this->lampiran_pelaporan->getClientOriginalExtension();
                $path = $this->lampiran_pelaporan->storeAs($folder, $filename, 'public');
                $report->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $this->lampiran_pelaporan->getClientOriginalName(),
                    'category' => 'lampiran_pelaporan'
                ]);
                $pelaporanPdfForMerge = strtolower($this->lampiran_pelaporan->getClientOriginalExtension()) === 'pdf'
                    ? $this->lampiran_pelaporan->getRealPath()
                    : null;
            } elseif ($this->generated_pelaporan_path && Storage::disk('local')->exists($this->generated_pelaporan_path)) {
                $filename = 'form_pelaporan_' . time() . '_' . uniqid() . '.pdf';
                $path = $folder . '/' . $filename;
                Storage::disk('public')->put($path, Storage::disk('local')->get($this->generated_pelaporan_path));
                $report->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $this->generated_pelaporan_name ?? $filename,
                    'category' => 'lampiran_pelaporan'
                ]);
                $pelaporanPdfForMerge = Storage::disk('public')->path($path);
            }

            if ($this->generated_investigasi_path && Storage::disk('local')->exists($this->generated_investigasi_path)) {
                $filename = 'form_investigasi_' . time() . '_' . uniqid() . '.pdf';
                $path = $folder . '/' . $filename;
                Storage::disk('public')->put($path, Storage::disk('local')->get($this->generated_investigasi_path));
                $report->attachments()->create(['file_path' => $path, 'file_name' => $this->generated_investigasi_name ?? $filename, 'category' => 'lampiran_investigasi']);
                $investigasiPdfForMerge = Storage::disk('public')->path($path);
            }

            $report->load('attachments');
            $pdf = Pdf::loadView('pdf.accident', [
                'report' => $report,
                'detail' => $report->accidentDetail
            ]);
            $pdfFilename = 'Report-' . str_replace('/', '-', $report->report_number) . '.pdf';
            $pdfPath = $folder . '/' . $pdfFilename;
            \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, $pdf->output());

            $report->attachments()->create([
                'file_path' => $pdfPath,
                'file_name' => $pdfFilename,
                'category' => 'pdf_report'
            ]);

            // PDF Merging Logic
            $originalPdfPath = \Illuminate\Support\Facades\Storage::disk('public')->path($pdfPath);
            $hasMerge = false;
            $fpdi = new \setasign\Fpdi\Fpdi();
            
            try {
                // 1. Add pages from the main PDF (DomPDF generated)
                $pageCount = $fpdi->setSourceFile($originalPdfPath);
                for ($n = 1; $n <= $pageCount; $n++) {
                    $tplIdx = $fpdi->importPage($n);
                    $size = $fpdi->getTemplateSize($tplIdx);
                    $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $fpdi->useTemplate($tplIdx);
                }
                
                // 2. Add pages from lampiran_pelaporan (if PDF)
                if ($pelaporanPdfForMerge) {
                    $pageCount = $fpdi->setSourceFile($pelaporanPdfForMerge);
                    for ($n = 1; $n <= $pageCount; $n++) {
                        $tplIdx = $fpdi->importPage($n);
                        $size = $fpdi->getTemplateSize($tplIdx);
                        $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $fpdi->useTemplate($tplIdx);
                    }
                    $hasMerge = true;
                }
                
                // 3. Add pages from lampiran_investigasi
                if ($investigasiPdfForMerge) {
                    $pageCount = $fpdi->setSourceFile($investigasiPdfForMerge);
                    for ($n = 1; $n <= $pageCount; $n++) {
                        $tplIdx = $fpdi->importPage($n);
                        $size = $fpdi->getTemplateSize($tplIdx);
                        $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $fpdi->useTemplate($tplIdx);
                    }
                    $hasMerge = true;
                }
                
                if ($hasMerge) {
                    $fpdi->Output($originalPdfPath, 'F');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('PDF Merge Error: ' . $e->getMessage());
            }

            DB::commit();

            if ($this->generated_pelaporan_path) {
                Storage::disk('local')->delete($this->generated_pelaporan_path);
            }
            if ($this->generated_investigasi_path) Storage::disk('local')->delete($this->generated_investigasi_path);

            $this->dispatch('swal:toast', type: 'success', message: 'Laporan Accident berhasil dikirim dengan Nomor: ' . $report->report_number);
            
            $this->reset([
                'jenis_insiden', 'lampiran_pelaporan',
                'nama_pelapor', 'nip', 'no_handphone', 'no_telepon', 'jenis_kelamin', 'lokasi_kerja',
                'departemen', 'nama_korban', 'tempat', 'tanggal', 'pukul', 
                'uraian_insiden', 'foto_insiden', 'apd', 'apd_alasan', 
                'kondisi_korban', 'kondisi_lain', 'kerusakan_property', 
                'pencemaran_lingkungan', 'tindak_lanjut', 'tindak_lanjut_lain', 
                'email_atasan'
            ]);
            $this->resetPelaporanForm();
            $this->resetInvestigationForm();

            $this->dispatch('scrollToTop', modal: 'modalAccidentReport');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:toast', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
            $this->dispatch('scrollToTop', modal: 'modalAccidentReport');
        }
    }

    private function resetPelaporanForm(): void
    {
        $this->generated_pelaporan_path = null;
        $this->generated_pelaporan_name = null;
        $this->pelaporan = [
            'nomor_dokumen' => 'HSE-PJK/FR 24-01',
            'tanggal_terbit' => $this->currentIssueDate(),
            'jenis_insiden' => '',
            'nama' => '',
            'tanggal_lahir' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'departemen' => '',
            'tempat' => '',
            'tanggal' => '',
            'pukul' => '',
            'uraian' => '',
            'apd' => '',
            'apd_alasan' => '',
            'keterangan_luka' => '',
            'koordinator' => '',
            'kepala_divisi' => '',
        ];
    }

    private function currentIssueDate(): string
    {
        return now('Asia/Jakarta')->format('d M Y');
    }

    private function resetInvestigationForm(): void
    {
        $this->generated_investigasi_path = null;
        $this->generated_investigasi_name = null;
        $this->investigasi = [
            'nomor_dokumen_1' => 'HSE-PJK/FR 24-01', 'nomor_dokumen_2' => 'HSE-PJK/FR 24-01',
            'tanggal_terbit' => $this->currentIssueDate(), 'halaman_1' => '1 dari 2', 'halaman_2' => '2 dari 2',
            'jenis_insiden' => '', 'nama_payroll' => '', 'peralatan_bahan' => '', 'umur' => '', 'tanggal' => '', 'jam' => '',
            'departemen' => '', 'section' => '', 'jabatan' => '', 'lama_bekerja' => '', 'shift' => '', 'lembur' => '', 'klasifikasi' => '',
            'saksi' => '', 'penyebab_luka' => '', 'tempat' => '', 'deskripsi' => '', 'gambaran' => '', 'factors' => [],
            'unsafe_condition' => '', 'unsafe_action' => '', 'biaya_peralatan' => '', 'biaya_pengobatan' => '', 'hari_hilang' => '', 'total_biaya' => '',
            'tindakan' => ['dept_penanggung_jawab' => '', 'rencana' => '', 'selesai' => '', 'efektif' => '', 'uraian' => ''],
            'disiapkan_nama' => '', 'disiapkan_jabatan' => '', 'disiapkan_departemen' => '', 'tim_penyelidik' => '', 'perbaikan_nama' => '', 'perbaikan_tanggal' => '',
        ];
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.public.report-accident');
    }
}
