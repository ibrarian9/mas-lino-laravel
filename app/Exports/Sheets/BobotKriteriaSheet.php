<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BobotKriteriaSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Bobot & Kriteria';
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 35, 'C' => 18, 'D' => 18, 'E' => 15];
    }

    public function styles(Worksheet $sheet): array
    {
        // Bobot header row
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);
        // Bobot table header
        $sheet->getStyle('A3:D3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '5C3D2E']],
        ]);
        // Data kriteria header
        $bobotRows = count($this->data['bobot']) + 4;
        $kriteriaHeaderRow = $bobotRows + 1;
        $sheet->getStyle("A{$kriteriaHeaderRow}:E{$kriteriaHeaderRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);
        $dataHeaderRow = $kriteriaHeaderRow + 2;
        $sheet->getStyle("A{$dataHeaderRow}:E{$dataHeaderRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E07B39']],
        ]);

        return [];
    }

    public function array(): array
    {
        $rows = [];

        // Section 1: Bobot
        $rows[] = ['BOBOT KRITERIA'];
        $rows[] = [];
        $rows[] = ['Kriteria', 'Nama', 'Bobot', 'Tipe'];

        foreach ($this->data['bobot'] as $key => $b) {
            $rows[] = [strtoupper($key), $b['nama'], $b['nilai'], $b['tipe']];
        }

        $rows[] = [];

        // Section 2: Data Kriteria
        $rows[] = ['DATA KRITERIA (ALTERNATIF)'];
        $rows[] = [];
        $rows[] = ['No', 'Nama Menu', 'C1 (Harga)', 'C2 (Popularitas)', 'C3 (Rating)'];

        foreach ($this->data['raw'] as $idx => $item) {
            $rows[] = [
                $idx + 1,
                $item['nama'],
                $item['c1'],
                $item['c2'],
                round($item['c3'], 2),
            ];
        }

        $rows[] = [];

        // Min/Max reference
        $rows[] = ['REFERENSI MIN/MAX'];
        $rows[] = [];
        foreach ($this->data['min_max'] as $key => $mm) {
            $rows[] = [strtoupper($key), $mm['label'], $mm['value']];
        }

        return $rows;
    }
}
