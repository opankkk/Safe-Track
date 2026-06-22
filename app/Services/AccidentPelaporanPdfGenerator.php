<?php

namespace App\Services;

class AccidentPelaporanPdfGenerator
{
    public function generate(array $data): string
    {
        $template = resource_path('file/pelaporan-kecelakaan-template.png');

        if (! is_file($template)) {
            throw new \RuntimeException('Template gambar formulir pelaporan kecelakaan tidak ditemukan.');
        }

        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->Image($template, 0, 0, 210, 297, 'PNG');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 9);

        $this->text($pdf, 50, 13.2, $data['nomor_dokumen'] ?? '');
        $this->text($pdf, 162, 13.2, $data['tanggal_terbit'] ?? '', 34);

        $incidentChecks = [
            'Near miss' => [34.8, 41.0],
            'Kecelakaan perorangan' => [72.3, 41.0],
            'Gangguan Kesehatan' => [121.3, 41.0],
        ];
        if (isset($incidentChecks[$data['jenis_insiden'] ?? ''])) {
            [$x, $y] = $incidentChecks[$data['jenis_insiden']];
            $this->check($pdf, $x, $y);
        }

        $this->text($pdf, 92, 61.8, $data['nama'] ?? '', 68);
        $this->text($pdf, 92, 69.8, $this->date($data['tanggal_lahir'] ?? null), 68);
        $this->text($pdf, 92, 77.9, $data['jenis_kelamin'] ?? '', 68);
        $this->text($pdf, 92, 86.3, $data['alamat'] ?? '', 68);
        $this->text($pdf, 92, 94.4, $data['departemen'] ?? '', 68);

        $this->text($pdf, 92, 114.7, $data['tempat'] ?? '', 68);
        $this->text($pdf, 92, 122.8, $this->date($data['tanggal'] ?? null), 68);
        $this->text($pdf, 92, 131.1, $data['pukul'] ?? '', 68);
        $this->linedText($pdf, $data['uraian'] ?? '', [
            [98, 143, 62],
            [38, 151.8, 122],
            [38, 159.3, 122],
        ]);

        if (($data['apd'] ?? '') === 'Ya') {
            $this->check($pdf, 113.8, 168.8);
        } elseif (($data['apd'] ?? '') === 'Tidak') {
            $this->check($pdf, 149.5, 168.8);
        }

        $this->text($pdf, 92, 175.5, $data['apd_alasan'] ?? '', 68);
        $this->linedText($pdf, $data['keterangan_luka'] ?? '', [
            [38, 193.8, 122],
            [38, 201.5, 122],
        ]);

        $this->centeredText($pdf, 95, 260.0, 50, $data['koordinator'] ?? '');
        $this->centeredText($pdf, 155, 260.0, 31, $data['kepala_divisi'] ?? '');

        return $pdf->Output('S');
    }

    private function text(\FPDF $pdf, float $x, float $y, ?string $value, float $maxWidth = 0): void
    {
        $pdf->SetXY($x, $y);
        $text = $this->fitText($pdf, $this->encode($value ?? ''), $maxWidth);
        $pdf->Cell($maxWidth, 4, $text);
    }

    private function linedText(\FPDF $pdf, ?string $value, array $lines): void
    {
        $words = preg_split('/\s+/', trim($this->encode($value ?? ''))) ?: [];

        foreach ($lines as [$x, $y, $width]) {
            $line = '';
            while ($words !== []) {
                $candidate = trim($line . ' ' . $words[0]);
                if ($line !== '' && $pdf->GetStringWidth($candidate) > $width) {
                    break;
                }
                $line = $candidate;
                array_shift($words);
            }
            $this->text($pdf, $x, $y, $line, $width);
        }
    }

    private function centeredText(\FPDF $pdf, float $x, float $y, float $width, ?string $value): void
    {
        $pdf->SetXY($x, $y);
        $pdf->Cell($width, 4, $this->fitText($pdf, $this->encode($value ?? ''), $width), 0, 0, 'C');
    }

    private function fitText(\FPDF $pdf, string $value, float $maxWidth): string
    {
        if ($maxWidth <= 0 || $pdf->GetStringWidth($value) <= $maxWidth) {
            return $value;
        }

        while ($value !== '' && $pdf->GetStringWidth($value . '...') > $maxWidth) {
            $value = mb_substr($value, 0, -1);
        }

        return rtrim($value) . '...';
    }

    private function check(\FPDF $pdf, float $x, float $y): void
    {
        $pdf->SetLineWidth(0.6);
        $pdf->Line($x, $y + 1.8, $x + 1.2, $y + 3.0);
        $pdf->Line($x + 1.2, $y + 3.0, $x + 3.6, $y + 0.5);
        $pdf->SetLineWidth(0.2);
    }

    private function date(?string $date): string
    {
        if (! $date) {
            return '';
        }

        return date('d-m-Y', strtotime($date));
    }

    private function encode(string $value): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $value) ?: $value;
    }
}
