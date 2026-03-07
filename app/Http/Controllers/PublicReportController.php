<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicReportController extends Controller
{
    public function storeAccident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reporter_name' => 'required|string|max:255',
            'superior_email' => 'required|email|max:255',
            'jenis_insiden' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:255',
            'lokasi_kerja' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'nama_korban' => 'nullable|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
            'uraian_insiden' => 'required|string',
            'apd' => 'required|string|max:255',
            'apd_alasan' => 'nullable|string|max:255',
            'kondisi_korban' => 'required|array',
            'kerusakan_property' => 'nullable|string',
            'pencemaran_lingkungan' => 'nullable|string',
            'tindak_lanjut' => 'required|string|max:255',
            'evidence' => 'required|array|min:1',
            'evidence.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $report = Report::create([
                'type' => 'accident',
                'reporter_name' => $request->reporter_name,
                'superior_email' => $request->superior_email,
                'status' => 'pending'
            ]);

            $report->accidentDetail()->create([
                'jenis_insiden' => $request->jenis_insiden,
                'jenis_kelamin' => $request->jenis_kelamin,
                'lokasi_kerja' => $request->lokasi_kerja,
                'departemen' => $request->departemen,
                'nama_korban' => $request->nama_korban,
                'tempat' => $request->tempat,
                'tanggal' => $request->tanggal,
                'pukul' => $request->pukul,
                'uraian_insiden' => $request->uraian_insiden,
                'apd' => $request->apd,
                'apd_alasan' => $request->apd_alasan,
                'kondisi_korban' => json_encode($request->kondisi_korban),
                'kerusakan_property' => $request->kerusakan_property,
                'pencemaran_lingkungan' => $request->pencemaran_lingkungan,
                'tindak_lanjut' => $request->tindak_lanjut,
            ]);

            if ($request->hasFile('evidence')) {
                foreach ($request->file('evidence') as $file) {
                    $year = date('Y');
                    $month = date('m');
                    $filename = 'evidence_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs("public/reports/accident/{$year}/{$month}", $filename);

                    $report->attachments()->create([
                        'file_path' => str_replace('public/', '', $path),
                        'file_name' => $file->getClientOriginalName(),
                        'category' => 'evidence'
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Laporan Accident berhasil dikirim.', 
                'report_number' => $report->report_number
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Gagal menyimpan laporan.', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeUnsafe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:unsafe_action,unsafe_condition',
            'reporter_name' => 'required|string|max:255',
            'superior_email' => 'required|email|max:255',
            'tanggal_pengamatan' => 'required|date',
            'waktu_pengamatan' => 'required|date_format:H:i',
            'status_pengamat' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'deskripsi_pengamatan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'dampak' => 'required|string',
            'perbaikan' => 'required|string',
            'evidence' => 'required|array|min:1',
            'evidence.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $report = Report::create([
                'type' => $request->type,
                'reporter_name' => $request->reporter_name,
                'superior_email' => $request->superior_email,
                'status' => 'pending'
            ]);

            $report->unsafeDetail()->create([
                'tanggal_pengamatan' => $request->tanggal_pengamatan,
                'waktu_pengamatan' => $request->waktu_pengamatan,
                'status_pengamat' => $request->status_pengamat,
                'departemen' => $request->departemen,
                'perusahaan' => $request->perusahaan,
                'deskripsi_pengamatan' => $request->deskripsi_pengamatan,
                'lokasi' => $request->lokasi,
                'dampak' => $request->dampak,
                'perbaikan' => $request->perbaikan,
            ]);

            if ($request->hasFile('evidence')) {
                foreach ($request->file('evidence') as $file) {
                    $year = date('Y');
                    $month = date('m');
                    $folderType = $request->type === 'unsafe_action' ? 'ua' : 'uc';
                    
                    $filename = 'evidence_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs("public/reports/{$folderType}/{$year}/{$month}", $filename);

                    $report->attachments()->create([
                        'file_path' => str_replace('public/', '', $path),
                        'file_name' => $file->getClientOriginalName(),
                        'category' => 'evidence'
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Laporan ' . str_replace('_', ' ', $request->type) . ' berhasil dikirim.', 
                'report_number' => $report->report_number
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Gagal menyimpan laporan.', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
