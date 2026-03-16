<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportUnsafe extends Component
{
    use WithFileUploads;
    
    protected function messages()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'email'    => 'Format :attribute tidak valid.',
            'date'     => 'Format tanggal pada :attribute tidak valid.',
            'image'    => ':attribute harus berupa gambar.',
            'mimes'    => ':attribute harus berformat: :values.',
            'max'      => ':attribute tidak boleh lebih dari :max KB.',
            'min'      => ':attribute minimal harus berisi :min item.',
            'date_format' => 'Format waktu :attribute harus HH:mm.',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'ua_tanggal_pengamatan' => 'Tanggal Pengamatan',
            'ua_waktu_pengamatan'   => 'Waktu Pengamatan',
            'ua_nama'               => 'Nama Pelapor',
            'ua_status'             => 'Status',
            'ua_departemen'         => 'Departemen',
            'ua_perusahaan'         => 'Nama Perusahaan',
            'ua_perilaku'           => 'Perilaku yang Diamati',
            'ua_lokasi'             => 'Lokasi',
            'ua_dampak'             => 'Dampak',
            'ua_perbaikan'          => 'Perbaikan/Pencegahan',
            'ua_email_atasan'       => 'Email Atasan',
            'ua_foto_sebelum'       => 'Foto Sebelum',
            'ua_foto_sesudah'       => 'Foto Sesudah',
            'uc_tanggal_pengamatan' => 'Tanggal Pengamatan',
            'uc_waktu_pengamatan'   => 'Waktu Pengamatan',
            'uc_nama'               => 'Nama Pelapor',
            'uc_status'             => 'Status',
            'uc_departemen'         => 'Departemen',
            'uc_perusahaan'         => 'Nama Perusahaan',
            'uc_kondisi'            => 'Kondisi yang Diamati',
            'uc_lokasi'             => 'Lokasi',
            'uc_dampak'             => 'Dampak',
            'uc_perbaikan'          => 'Perbaikan/Pencegahan',
            'uc_email_atasan'       => 'Email Atasan',
            'uc_foto_sebelum'       => 'Foto Sebelum',
            'uc_foto_sesudah'       => 'Foto Sesudah',
        ];
    }

    // Unsafe Action Properties
    public $ua_tanggal_pengamatan;
    public $ua_waktu_pengamatan;
    public $ua_nama;
    public $ua_status;
    public $ua_departemen;
    public $ua_perusahaan;
    public $ua_perilaku;
    public $ua_foto_sebelum;
    public $ua_foto_sesudah;
    public $ua_lokasi;
    public $ua_dampak;
    public $ua_perbaikan;
    public $ua_email_atasan = '';

    // Unsafe Condition Properties
    public $uc_tanggal_pengamatan;
    public $uc_waktu_pengamatan;
    public $uc_nama;
    public $uc_status;
    public $uc_departemen;
    public $uc_perusahaan;
    public $uc_kondisi;
    public $uc_foto_sebelum;
    public $uc_foto_sesudah;
    public $uc_lokasi;
    public $uc_dampak;
    public $uc_perbaikan;
    public $uc_email_atasan = '';

    public function saveUnsafeAction()
    {
        $this->validate([
            'ua_tanggal_pengamatan' => 'required|date',
            'ua_waktu_pengamatan' => 'required|date_format:H:i',
            'ua_nama' => 'required|string|max:255',
            'ua_status' => 'required|string',
            'ua_departemen' => 'required|string',
            'ua_perusahaan' => 'nullable|string',
            'ua_perilaku' => 'required|string',
            'ua_lokasi' => 'required|string',
            'ua_dampak' => 'required|string',
            'ua_perbaikan' => 'required|string',
            'ua_email_atasan' => 'required|email',
            'ua_foto_sebelum' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'ua_foto_sesudah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $this->processSave('unsafe_action', [
            'tanggal_pengamatan' => $this->ua_tanggal_pengamatan,
            'waktu_pengamatan' => $this->ua_waktu_pengamatan,
            'reporter_name' => $this->ua_nama,
            'status_pengamat' => $this->ua_status,
            'departemen' => $this->ua_departemen,
            'perusahaan' => $this->ua_status !== 'Karyawan Pamitra' ? $this->ua_perusahaan : null,
            'deskripsi_pengamatan' => $this->ua_perilaku,
            'lokasi' => $this->ua_lokasi,
            'dampak' => $this->ua_dampak,
            'perbaikan' => $this->ua_perbaikan,
            'email_atasan' => $this->ua_email_atasan,
            'foto_sebelum' => $this->ua_foto_sebelum,
            'foto_sesudah' => $this->ua_foto_sesudah
        ]);
    }

    public function saveUnsafeCondition()
    {
        $this->validate([
            'uc_tanggal_pengamatan' => 'required|date',
            'uc_waktu_pengamatan' => 'required|date_format:H:i',
            'uc_nama' => 'required|string|max:255',
            'uc_status' => 'required|string',
            'uc_departemen' => 'required|string',
            'uc_perusahaan' => 'nullable|string',
            'uc_kondisi' => 'required|string',
            'uc_lokasi' => 'required|string',
            'uc_dampak' => 'required|string',
            'uc_perbaikan' => 'required|string',
            'uc_email_atasan' => 'required|email',
            'uc_foto_sebelum' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'uc_foto_sesudah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $this->processSave('unsafe_condition', [
            'tanggal_pengamatan' => $this->uc_tanggal_pengamatan,
            'waktu_pengamatan' => $this->uc_waktu_pengamatan,
            'reporter_name' => $this->uc_nama,
            'status_pengamat' => $this->uc_status,
            'departemen' => $this->uc_departemen,
            'perusahaan' => $this->uc_status !== 'Karyawan Pamitra' ? $this->uc_perusahaan : null,
            'deskripsi_pengamatan' => $this->uc_kondisi,
            'lokasi' => $this->uc_lokasi,
            'dampak' => $this->uc_dampak,
            'perbaikan' => $this->uc_perbaikan,
            'email_atasan' => $this->uc_email_atasan,
            'foto_sebelum' => $this->uc_foto_sebelum,
            'foto_sesudah' => $this->uc_foto_sesudah
        ]);
    }

    private function processSave($type, $data)
    {
        DB::beginTransaction();
        try {
            $report = Report::create([
                'type' => $type,
                'reporter_name' => $data['reporter_name'],
                'superior_email' => $data['email_atasan'],
                'status' => 'pending'
            ]);

            $report->unsafeDetail()->create([
                'tanggal_pengamatan' => $data['tanggal_pengamatan'],
                'waktu_pengamatan' => $data['waktu_pengamatan'],
                'status_pengamat' => $data['status_pengamat'],
                'departemen' => $data['departemen'],
                'perusahaan' => $data['perusahaan'],
                'deskripsi_pengamatan' => $data['deskripsi_pengamatan'],
                'lokasi' => $data['lokasi'],
                'dampak' => $data['dampak'],
                'perbaikan' => $data['perbaikan'],
            ]);

            $folderType = $type === 'unsafe_action' ? 'ua' : 'uc';
            $year = date('Y');
            $month = date('m');
            $folder = "reports/{$folderType}/{$year}/{$month}";

            // Handle Foto Sebelum
            if ($data['foto_sebelum']) {
                $file = $data['foto_sebelum'];
                $filename = 'before_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($folder, $filename, 'public');
                $report->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'category' => 'evidence_before'
                ]);
            }

            // Handle Foto Sesudah
            if ($data['foto_sesudah']) {
                $file = $data['foto_sesudah'];
                $filename = 'after_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($folder, $filename, 'public');
                $report->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'category' => 'evidence_after'
                ]);
            }

            // Generate Combined PDF Record
            $report->load('attachments');
            $pdf = Pdf::loadView('pdf.unsafe', [
                'report' => $report,
                'detail' => $report->unsafeDetail,
                'type' => $type
            ]);
            $pdfFilename = 'pdf_report_' . time() . '_' . uniqid() . '.pdf';
            $pdfPath = $folder . '/' . $pdfFilename;
            \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, $pdf->output());

            $report->attachments()->create([
                'file_path' => $pdfPath,
                'file_name' => $pdfFilename,
                'category' => 'pdf_report'
            ]);

            DB::commit();

            session()->flash('success', 'Laporan berhasil dikirim dengan Nomor: ' . $report->report_number);
            
            // Reset form input
            if ($type === 'unsafe_action') {
                $this->reset(['ua_tanggal_pengamatan','ua_waktu_pengamatan','ua_nama','ua_status','ua_departemen','ua_perusahaan','ua_perilaku','ua_foto_sebelum','ua_foto_sesudah','ua_lokasi','ua_dampak','ua_perbaikan','ua_email_atasan']);
                $this->dispatch('scrollToTop', modal: 'modalUnsafeAction');
                $this->dispatch('swal:toast', type: 'success', message: 'Laporan Unsafe Action Berhasil Dikirim!');
            } else {
                $this->reset(['uc_tanggal_pengamatan','uc_waktu_pengamatan','uc_nama','uc_status','uc_departemen','uc_perusahaan','uc_kondisi','uc_foto_sebelum','uc_foto_sesudah','uc_lokasi','uc_dampak','uc_perbaikan','uc_email_atasan']);
                $this->dispatch('scrollToTop', modal: 'modalUnsafeCondition');
                $this->dispatch('swal:toast', type: 'success', message: 'Laporan Unsafe Condition Berhasil Dikirim!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->dispatch('swal:toast', type: 'error', message: 'Gagal mengirim laporan: ' . $e->getMessage());
            
            if ($type === 'unsafe_action') {
                $this->dispatch('scrollToTop', modal: 'modalUnsafeAction');
            } else {
                $this->dispatch('scrollToTop', modal: 'modalUnsafeCondition');
            }
        }
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.public.report-unsafe');
    }
}
