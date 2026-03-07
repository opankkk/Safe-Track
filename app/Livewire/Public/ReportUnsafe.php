<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportUnsafe extends Component
{
    use WithFileUploads;

    // Unsafe Action Properties
    public $ua_tanggal_pengamatan;
    public $ua_waktu_pengamatan;
    public $ua_nama;
    public $ua_status;
    public $ua_departemen;
    public $ua_perusahaan;
    public $ua_perilaku;
    public $ua_foto = [];
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
    public $uc_foto = [];
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
            'ua_foto' => 'required|array|min:1|max:5',
            'ua_foto.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120'
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
            'fotos' => $this->ua_foto
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
            'uc_foto' => 'required|array|min:1|max:5',
            'uc_foto.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120'
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
            'fotos' => $this->uc_foto
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
            $folder = "public/reports/{$folderType}/{$year}/{$month}";

            foreach ($data['fotos'] as $file) {
                $filename = 'evidence_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($folder, $filename);

                $report->attachments()->create([
                    'file_path' => str_replace('public/', '', $path),
                    'file_name' => $file->getClientOriginalName(),
                    'category' => 'evidence'
                ]);
            }

            DB::commit();

            session()->flash('success' . ($type == 'unsafe_action' ? 'UA' : 'UC'), 'Laporan berhasil dikirim dengan Nomor: ' . $report->report_number);
            
            // Reset form input
            if ($type === 'unsafe_action') {
                $this->reset(['ua_tanggal_pengamatan','ua_waktu_pengamatan','ua_nama','ua_status','ua_departemen','ua_perusahaan','ua_perilaku','ua_foto','ua_lokasi','ua_dampak','ua_perbaikan','ua_email_atasan']);
                $this->dispatch('scrollToTop', modal: 'modalUnsafeAction');
            } else {
                $this->reset(['uc_tanggal_pengamatan','uc_waktu_pengamatan','uc_nama','uc_status','uc_departemen','uc_perusahaan','uc_kondisi','uc_foto','uc_lokasi','uc_dampak','uc_perbaikan','uc_email_atasan']);
                $this->dispatch('scrollToTop', modal: 'modalUnsafeCondition');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            
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
