<?php

namespace App\Services;

class AccidentInvestigationPdfGenerator
{
    public function generate(array $data): string
    {
        $pdf = new \setasign\Fpdi\Fpdi('P', 'mm', 'A4');
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 8);
        $template = resource_path('file/investigasi.pdf');

        if (! is_file($template)) {
            throw new \RuntimeException('Template PDF investigasi tidak ditemukan.');
        }

        $pageCount = $pdf->setSourceFile($template);
        if ($pageCount < 2) {
            throw new \RuntimeException('Template PDF investigasi harus memiliki dua halaman.');
        }

        $this->page($pdf, 1);
        $this->header($pdf, $data, 1);
        $this->checks($pdf, $data['jenis_insiden'] ?? '', [
            'Near Miss' => [33, 38.0], 'Gangguan Kesehatan' => [73, 38.0],
            'Kebakaran' => [113, 38.0], 'Kecelakaan' => [153, 38.0],
        ]);
        $this->text($pdf, 26, 52, $data['nama_payroll'] ?? '', 34);
        $this->text($pdf, 80, 52, $data['peralatan_bahan'] ?? '', 35);
        $this->text($pdf, 128, 52, $this->date($data['tanggal'] ?? ''), 34);
        $this->text($pdf, 174, 52, $data['jam'] ?? '', 16);
        $this->text($pdf, 39, 64.2, $data['departemen'] ?? '', 39);
        $this->text($pdf, 86, 64.2, $data['section'] ?? '', 36);
        $this->text($pdf, 139, 64.2, $data['jabatan'] ?? '', 46);
        $this->text($pdf, 32, 71.5, $data['umur'] ?? '', 39);
        $this->text($pdf, 103, 71.5, $data['lama_bekerja'] ?? '', 95);
        $this->checks($pdf, $data['shift'] ?? '', ['1st' => [19.5, 91], '2nd' => [32, 91], '3rd' => [44.5, 91]]);
        $this->checks($pdf, $data['lembur'] ?? '', ['Yes' => [61, 91], 'No' => [76, 91]]);
        $this->checks($pdf, $data['klasifikasi'] ?? '', [
            'P3K' => [97, 86], 'MTI' => [122, 86], 'LWD' => [147, 86], 'Fatality' => [172, 86],
        ]);
        $this->multi($pdf, 35, 90, 171, 4, $data['saksi'] ?? '', 2);
        $this->multi($pdf, 19, 113, 171, 4, $data['penyebab_luka'] ?? '', 4);
        $this->multi($pdf, 19, 132, 171, 4, $data['gambaran'] ?? '', 20);
        $this->text($pdf, 36, 214.2, $data['tempat'] ?? '', 148);
        $this->multi($pdf, 19, 230, 171, 4, $data['deskripsi'] ?? '', 15);

        $this->page($pdf, 2);
        $this->header($pdf, $data, 2);
        $factorPositions = [
            'Ruang gerak yang terbatas/sempit' => [19, 48], 'Housekeeping yang tidak memadai' => [19, 52],
            'Kondisi lingkungan yang berbahaya' => [19, 56], 'Terpapar kebisingan tinggi' => [19, 60],
            'Terpapar radiasi' => [19, 64], 'Suhu ekstrim' => [19, 68], 'Penerangan kurang/berlebih' => [19, 72],
            'Ventilasi kurang memadai' => [19, 76], 'Terpapar getaran yang berlebihan/lama' => [19, 80],
            'Perilaku/attitude yang kurang' => [19, 94], 'Kurang istirahat/tidur' => [19, 98],
            'Kurang pengetahuan atau ketrampilan' => [19, 102], 'Mengoperasikan alat yang bukan wewenangnya' => [19, 106],
            'Gagal mengamankan/tidak memasang LOTO' => [19, 110], 'Memakai peralatan yang rusak' => [19, 114],
            'Tidak memakai/salah menggunakan APD' => [19, 118], 'Posisi kerja yang tidak aman/tidak ergonomis' => [19, 122],
            'Cara pengangkatan yang tidak tepat' => [19, 126], 'Menggunakan alat tidak benar/pemaksaan peralatan' => [19, 130],
            'Tidak melaksanakan prosedur/standard kerja dengan benar' => [19, 134], 'Bercanda/bermain-main' => [19, 138],
            'Pelindung pada alat atau alat peringatan tidak memadai' => [109, 44], 'APD tidak memadai' => [109, 48],
            'Alat/material tidak memadai atau rusak' => [109, 52], 'Desain perancangan tidak memadai/tidak ergonomis' => [109, 56],
            'Spesifikasi pembelian tidak memadai' => [109, 60], 'Bahaya bahan mudah meledak/terbakar' => [109, 64],
            'Perkakas/peralatan/material tidak memadai' => [109, 68], 'Aus dan rusak normal' => [109, 72],
            'Kerusakan akibat kecelakaan/abnormal' => [109, 76], 'Sistem peringatan tidak memadai' => [109, 92],
            'Belum ada standard/prosedur' => [109, 96], 'Prosedur/standard tidak dapat diimplementasikan' => [109, 100],
            'Cara pemuatan/penyimpanan tidak aman' => [109, 108], 'Pemeliharaan tidak memadai' => [109, 112],
            'Kurangnya pengawasan/supervisi' => [109, 116],
        ];
        $selectedFactors = $data['factors'] ?? [];
        foreach ($factorPositions as $factor => [$x, $y]) {
            if (! in_array($factor, $selectedFactors, true)) {
                $this->strikeFactor($pdf, $x, $y);
            }
        }
        $this->multi($pdf, 19, 154, 171, 4, $data['unsafe_condition'] ?? '', 5);
        $this->multi($pdf, 19, 172, 171, 4, $data['unsafe_action'] ?? '', 5);
        $this->text($pdf, 18, 204, $data['biaya_peralatan'] ?? '', 50, 'C');
        $this->text($pdf, 68, 204, $data['biaya_pengobatan'] ?? '', 45, 'C');
        $this->text($pdf, 113, 204, $data['hari_hilang'] ?? '', 45, 'C');
        $this->text($pdf, 158, 204, $data['total_biaya'] ?? '', 32, 'C');
        $action = $data['tindakan'] ?? [];
        $this->text($pdf, 18, 226, $action['uraian'] ?? '', 92);
        $this->text($pdf, 110, 226, $action['dept_penanggung_jawab'] ?? '', 48);
        $this->text($pdf, 158, 226, $action['rencana'] ?? '', 18);
        $this->text($pdf, 176, 226, $action['efektif'] ?? '', 14, 'C');
        $this->text($pdf, 45, 249, $data['disiapkan_nama'] ?? '', 28);
        $this->text($pdf, 45, 255, $data['disiapkan_jabatan'] ?? '', 28);
        $this->text($pdf, 45, 260, $data['disiapkan_departemen'] ?? '', 28);
        $this->multi($pdf, 77, 254, 49, 4, $data['tim_penyelidik'] ?? '', 4);
        $this->text($pdf, 140, 262, $data['perbaikan_nama'] ?? '', 38);
        $this->text($pdf, 140, 268, $this->date($data['perbaikan_tanggal'] ?? ''), 38);

        return $pdf->Output('S');
    }

    private function page(\setasign\Fpdi\Fpdi $pdf, int $page): void
    {
        $template = $pdf->importPage($page);
        $size = $pdf->getTemplateSize($template);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($template);
    }

    private function header(\FPDF $pdf, array $data, int $page): void
    {
        $this->text($pdf, 47, 13, $data["nomor_dokumen_{$page}"] ?? '', 58);
        $this->text($pdf, 165, 13, $data['tanggal_terbit'] ?? '', 28);
        $halaman = preg_split('/\s+dari\s+/i', (string) ($data["halaman_{$page}"] ?? ''));
        $this->text($pdf, 165, 22, $halaman[0] ?? '', 8, 'C');
        $this->text($pdf, 184, 22, $halaman[1] ?? '', 8, 'C');
    }

    private function checks(\FPDF $pdf, string $selected, array $positions): void
    {
        if (isset($positions[$selected])) $this->check($pdf, ...$positions[$selected]);
    }

    private function check(\FPDF $pdf, float $x, float $y): void
    {
        $pdf->SetLineWidth(.5); $pdf->Line($x, $y + 1.5, $x + 1.2, $y + 2.8); $pdf->Line($x + 1.2, $y + 2.8, $x + 3.5, $y + .4); $pdf->SetLineWidth(.2);
    }

    private function strikeFactor(\FPDF $pdf, float $x, float $y): void
    {
        $lineWidth = $x < 100 ? 73 : 70;
        $pdf->SetDrawColor(90, 90, 90);
        $pdf->SetLineWidth(.15);
        $pdf->Line($x + 4, $y + 2, $x + $lineWidth, $y + 2);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
    }

    private function text(\FPDF $pdf, float $x, float $y, mixed $value, float $width, string $align = 'L'): void
    {
        $pdf->SetXY($x, $y); $pdf->Cell($width, 4, $this->encode((string) $value), 0, 0, $align);
    }

    private function multi(\FPDF $pdf, float $x, float $y, float $width, float $height, mixed $value, int $lines): void
    {
        $pdf->SetXY($x, $y); $text = wordwrap($this->encode((string) $value), max(10, (int) ($width / 2)), "\n", true);
        $pdf->MultiCell($width, $height, implode("\n", array_slice(explode("\n", $text), 0, $lines)));
    }

    private function date(?string $value): string
    {
        return $value ? date('d-m-Y', strtotime($value)) : '';
    }

    private function encode(string $value): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $value) ?: $value;
    }
}
