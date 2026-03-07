<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportAccident extends Component
{
    use WithFileUploads;

    public $jenis_insiden;
    public $lampiran_pelaporan;
    public $lampiran_investigasi;
    
    public $nama_pelapor;
    public $no_handphone;
    public $jenis_kelamin;
    public $lokasi_kerja;
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

    protected function rules()
    {
        return [
            'jenis_insiden' => 'required|string',
            'lampiran_pelaporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'lampiran_investigasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nama_pelapor' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:20',
            'jenis_kelamin' => 'required|string',
            'lokasi_kerja' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'nama_korban' => 'nullable|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
            'uraian_insiden' => 'required|string',
            'foto_insiden' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'apd' => 'required|string',
            'apd_alasan' => 'nullable|string',
            'kondisi_korban' => 'required|string',
            'kerusakan_property' => 'required|string',
            'pencemaran_lingkungan' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'email_atasan' => 'required|email'
        ];
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
                'status' => 'pending'
            ]);

            // Format kondisi korban (max 1 diset array sesuai skema db json)
            $kondisiFix = $this->kondisi_korban === 'Yang lain' ? $this->kondisi_lain : $this->kondisi_korban;

            // Format tindak lanjut
            $tindakLanjutFix = $this->tindak_lanjut === 'Lainnya' ? $this->tindak_lanjut_lain : $this->tindak_lanjut;

            $report->accidentDetail()->create([
                'jenis_insiden' => $this->jenis_insiden,
                'no_handphone' => $this->no_handphone,
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
                'kondisi_korban' => json_encode([$kondisiFix]),
                'kerusakan_property' => $this->kerusakan_property,
                'pencemaran_lingkungan' => $this->pencemaran_lingkungan,
                'tindak_lanjut' => $tindakLanjutFix,
            ]);

            $year = date('Y');
            $month = date('m');
            $folder = "public/reports/accident/{$year}/{$month}";

            if ($this->foto_insiden) {
                $filename = 'evidence_' . time() . '_' . uniqid() . '.' . $this->foto_insiden->getClientOriginalExtension();
                $path = $this->foto_insiden->storeAs($folder, $filename);
                $report->attachments()->create([
                    'file_path' => str_replace('public/', '', $path),
                    'file_name' => $this->foto_insiden->getClientOriginalName(),
                    'category' => 'evidence'
                ]);
            }

            if ($this->lampiran_pelaporan) {
                $filename = 'form_pelaporan_' . time() . '_' . uniqid() . '.' . $this->lampiran_pelaporan->getClientOriginalExtension();
                $path = $this->lampiran_pelaporan->storeAs($folder, $filename);
                $report->attachments()->create([
                    'file_path' => str_replace('public/', '', $path),
                    'file_name' => $this->lampiran_pelaporan->getClientOriginalName(),
                    'category' => 'lampiran_pelaporan'
                ]);
            }

            if ($this->lampiran_investigasi) {
                $filename = 'form_investigasi_' . time() . '_' . uniqid() . '.' . $this->lampiran_investigasi->getClientOriginalExtension();
                $path = $this->lampiran_investigasi->storeAs($folder, $filename);
                $report->attachments()->create([
                    'file_path' => str_replace('public/', '', $path),
                    'file_name' => $this->lampiran_investigasi->getClientOriginalName(),
                    'category' => 'lampiran_investigasi'
                ]);
            }

            DB::commit();

            session()->flash('success', 'Laporan Accident berhasil dikirim dengan Nomor: ' . $report->report_number);
            return redirect()->route('public.report.accident');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.public.report-accident');
    }
}
