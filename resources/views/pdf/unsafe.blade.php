<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Temuan - {{ $report->report_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid {{ $type === 'unsafe_action' ? '#ffc107' : '#17a2b8' }}; padding-bottom: 15px; }
        .header h2 { margin: 0; color: {{ $type === 'unsafe_action' ? '#d39e00' : '#117a8b' }}; }
        .header p { margin: 5px 0 0; font-size: 13px; color: #666; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; vertical-align: top; }
        .table th { background-color: #f8f9fa; width: 35%; text-align: left; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .badge-ua { background-color: #ffc107; color: #212529; }
        .badge-uc { background-color: #17a2b8; color: #fff; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; color: #777; }
        .section-title { background-color: #343a40; color: #fff; padding: 8px 12px; font-size: 15px; margin-bottom: 0; border-radius: 4px 4px 0 0; }
        .section-body { border: 1px solid #343a40; padding: 15px; margin-bottom: 20px; border-radius: 0 0 4px 4px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN TEMUAN ({{ str_replace('_', ' ', strtoupper($type)) }})</h2>
        <p>No Laporan: <strong>{{ $report->report_number }}</strong> | Tanggal Lapor: {{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
    </div>

    <div class="section-title">Informasi Pengamat</div>
    <div class="section-body">
        <table class="table">
            <tr>
                <th>Nama Pengamat</th>
                <td>{{ $report->reporter_name }}</td>
            </tr>
            <tr>
                <th>Email Atasan</th>
                <td>{{ $report->superior_email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status / Bagian</th>
                <td>
                    {{ $detail->status_pengamat ?? '-' }} 
                    @if($detail->perusahaan)
                        (Perusahaan: {{ $detail->perusahaan }})
                    @endif
                </td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>{{ $detail->departemen ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Temuan</div>
    <div class="section-body">
        <table class="table">
            <tr>
                <th>Jenis Temuan</th>
                <td>
                    @if($type === 'unsafe_action')
                        <span class="badge badge-ua">Unsafe Action</span>
                    @else
                        <span class="badge badge-uc">Unsafe Condition</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Waktu Pengamatan</th>
                <td>
                    {{ $detail->tanggal_pengamatan ? \Carbon\Carbon::parse($detail->tanggal_pengamatan)->format('d M Y') : '-' }}, {{ $detail->waktu_pengamatan ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Lokasi Spesifik</th>
                <td>{{ $detail->lokasi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Deskripsi Pengamatan</th>
                <td>{!! nl2br(e($detail->deskripsi_pengamatan ?? '-')) !!}</td>
            </tr>
            <tr>
                <th>Potensi Bahaya / Dampak</th>
                <td>{!! nl2br(e($detail->potensi_bahaya ?? ($detail->dampak ?? '-'))) !!}</td>
            </tr>
            <tr>
                <th>Tindakan Perbaikan</th>
                <td>{!! nl2br(e($detail->tindakan_perbaikan ?? ($detail->perbaikan ?? '-'))) !!}</td>
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
