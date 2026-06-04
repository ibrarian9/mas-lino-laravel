<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PreferensiSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Preferensi & Ranking';
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 35, 'C' => 18, 'D' => 8];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);
        $sheet->getStyle('A3:D3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '5C3D2E']],
        ]);

        // Highlight rank #1 row
        if (!empty($this->data['ranking'])) {
            $sheet->getStyle('A4:D4')->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFF9E6']],
            ]);
        }

        return [];
    }

    public function array(): array
    {
        $rows = [];

        $bobot = $this->data['bobot'];
        $rows[] = ['NILAI PREFERENSI & RANKING'];
        $rows[] = [];
        $rows[] = ['No', 'Nama Menu', 'Nilai Vi', 'Rank'];

        foreach ($this->data['ranking'] as $idx => $item) {
            $rows[] = [
                $idx + 1,
                $item['nama'],
                $item['vi'],
                $item['rank'],
            ];
        }

        $rows[] = [];
        $rows[] = ['RUMUS PREFERENSI:'];
        $rows[] = ['Vi = (W1 × R1) + (W2 × R2) + (W3 × R3)'];
        $rows[] = ['W1 = ' . $bobot['c1']['nilai'] . ' (Harga)', 'W2 = ' . $bobot['c2']['nilai'] . ' (Popularitas)', 'W3 = ' . $bobot['c3']['nilai'] . ' (Rating)'];

        return $rows;
    }
}
