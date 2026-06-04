<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NormalisasiSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Normalisasi';
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 35, 'C' => 15, 'D' => 15, 'E' => 15];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);
        $sheet->getStyle('A3:E3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '5C3D2E']],
        ]);

        return [];
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['MATRIKS NORMALISASI'];
        $rows[] = [];
        $rows[] = ['No', 'Nama Menu', 'R1 (Harga)', 'R2 (Popularitas)', 'R3 (Rating)'];

        foreach ($this->data['normalized'] as $idx => $item) {
            $rows[] = [
                $idx + 1,
                $item['nama'],
                $item['r1'],
                $item['r2'],
                $item['r3'],
            ];
        }

        $rows[] = [];
        $rows[] = ['RUMUS NORMALISASI:'];
        $rows[] = ['R1 = Min(C1) / C1  (Cost — semakin kecil semakin baik)'];
        $rows[] = ['R2 = C2 / Max(C2)  (Benefit — semakin besar semakin baik)'];
        $rows[] = ['R3 = C3 / Max(C3)  (Benefit — semakin besar semakin baik)'];

        return $rows;
    }
}
