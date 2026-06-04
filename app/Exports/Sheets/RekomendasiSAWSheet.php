<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekomendasiSAWSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected array $sawRanking;

    public function __construct(array $sawRanking)
    {
        $this->sawRanking = $sawRanking;
    }

    public function title(): string
    {
        return 'Rekomendasi SAW';
    }

    public function columnWidths(): array
    {
        return ['A' => 6, 'B' => 35, 'C' => 18, 'D' => 12, 'E' => 12, 'F' => 12, 'G' => 14, 'H' => 18];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);
        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '5C3D2E']],
        ]);

        // Highlight top 3
        for ($i = 4; $i <= min(6, 3 + count($this->sawRanking)); $i++) {
            $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFF9E6']],
            ]);
        }

        return [];
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = ['REKOMENDASI MENU — METODE SAW'];
        $rows[] = [];
        $rows[] = ['Rank', 'Nama Menu', 'Harga (Rp)', 'R1', 'R2', 'R3', 'Skor Vi', 'Status'];

        foreach ($this->sawRanking as $idx => $item) {
            $rows[] = [
                $idx + 1,
                $item['nama'],
                $item['harga'],
                $item['r1'],
                $item['r2'],
                $item['r3'],
                $item['vi'],
                $idx < 3 ? 'Direkomendasikan' : '-',
            ];
        }

        $rows[] = [];
        $rows[] = ['Bobot: W1 = 0.30 (Harga/Cost)', 'W2 = 0.25 (Popularitas/Benefit)', 'W3 = 0.45 (Rating/Benefit)'];
        $rows[] = ['Vi = (W1 × R1) + (W2 × R2) + (W3 × R3)'];

        return $rows;
    }
}
