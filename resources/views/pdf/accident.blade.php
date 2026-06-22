<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Accident - {{ $report->report_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #dc3545; padding-bottom: 15px; }
        .header h2 { margin: 0; color: #dc3545; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #666; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; vertical-align: top; }
        .table th { background-color: #f8f9fa; width: 35%; text-align: left; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .badge-accident { background-color: #dc3545; color: #fff; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; color: #777; }
        .section-title { background-color: #343a40; color: #fff; padding: 8px 12px; font-size: 15px; margin-bottom: 0; border-radius: 4px 4px 0 0; }
        .section-body { border: 1px solid #343a40; padding: 15px; margin-bottom: 20px; border-radius: 0 0 4px 4px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN KECELAKAAN (ACCIDENT)</h2>
        <p>No Laporan: <strong>{{ $report->report_number }}</strong> | Tanggal Lapor: {{ $report->created_at ? $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}</p>
    </div>

    <div class="section-title">Informasi Pelapor</div>
    <div class="section-body">
        <table class="table">
            <tr>
                <th>Nama Pelapor</th>
                <td>{{ $report->reporter_name }}</td>
            </tr>
            <tr>
                <th>Email Atasan</th>
                <td>{{ $report->superior_email ?? '-' }}</td>
            </tr>
            <tr>
                <th>No Handphone</th>
                <td>{{ $detail->no_handphone ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin Pelapor</th>
                <td>{{ $detail->jenis_kelamin ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Insiden</div>
    <div class="section-body">
        <table class="table">
            <tr>
                <th>Jenis Insiden</th>
                <td><span class="badge badge-accident">{{ $detail->jenis_insiden ?? '-' }}</span></td>
            </tr>
            <tr>
                <th>Lokasi Kerja Pelapor</th>
                <td>{{ $detail->lokasi_kerja ?? '-' }}</td>
            </tr>
            <tr>
                <th>Lokasi Kejadian</th>
                <td>{{ $detail->tempat ?? '-' }}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>{{ $detail->departemen ?? '-' }}</td>
            </tr>
            <tr>
                <th>Waktu Kejadian</th>
                <td>{{ $detail->tanggal ? \Carbon\Carbon::parse($detail->tanggal)->format('d M Y') : '-' }}, {{ $detail->pukul ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama Korban (Jika ada)</th>
                <td>{{ $detail->nama_korban ?: 'Tidak ada korban' }}</td>
            </tr>
            <tr>
                <th>Kondisi Korban</th>
                <td>
                    @php
                        $kondisi = $detail->kondisi_korban;
                        if (is_string($kondisi)) {
                            $kondisi = json_decode($kondisi, true) ?? [];
                        }
                    @endphp
                    {{ is_array($kondisi) ? implode(', ', $kondisi) : $kondisi }}
                </td>
            </tr>
            <tr>
                <th>Kronologi / Uraian Insiden</th>
                <td>{!! nl2br(e($detail->uraian_insiden ?? '-')) !!}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Lingkungan & Tindak Lanjut</div>
    <div class="section-body">
        <table class="table">
            <tr>
                <th>Penggunaan APD</th>
                <td>
                    {{ $detail->apd ?? '-' }}
                    @if($detail->apd === 'Tidak / Lainnya' && $detail->apd_alasan)
                        <br><small>Alasan: {{ $detail->apd_alasan }}</small>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Kerusakan Property</th>
                <td>{{ $detail->kerusakan_property ?? '-' }}</td>
            </tr>
            <tr>
                <th>Pencemaran Lingkungan</th>
                <td>{{ $detail->pencemaran_lingkungan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tindak Lanjut Sementara</th>
                <td>{{ $detail->tindak_lanjut ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @if($report->attachments && $report->attachments->where('category', '!=', 'pdf_report')->count() > 0)
    <div style="page-break-before: always;"></div>
    <div class="section-title">Dokumen Lampiran / Foto</div>
    <div class="section-body" style="text-align: center; border: 1px solid #343a40; padding: 15px; border-radius: 0 0 4px 4px;">
        @foreach($report->attachments->where('category', '!=', 'pdf_report') as $attachment)
            @php
                $ext = pathinfo($attachment->file_path, PATHINFO_EXTENSION);
                $path = storage_path('app/public/' . $attachment->file_path);
                $base64 = '';
                if(file_exists($path)) {
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . strtolower($ext) . ';base64,' . base64_encode($data);
                }
            @endphp
            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']) && $base64)
                <div style="margin-bottom: 20px; text-align: center;">
                    <p style="text-align: left; font-weight: bold; font-size: 13px; color: #555; border-bottom: 1px solid #ccc; padding-bottom: 5px;">{{ str_replace('_', ' ', strtoupper($attachment->category)) }} - {{ $attachment->file_name }}</p>
                    <img src="{{ $base64 }}" style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; padding: 4px; border-radius: 4px; display: block; margin: 0 auto;">
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="footer">
        Dicetak oleh Sistem HSE | {{ now()->format('d M Y, H:i:s') }}
    </div>

</body>
</html>
