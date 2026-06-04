<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MenuTerlarisSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected int $year;
    protected $topMenus;

    public function __construct(int $year, $topMenus)
    {
        $this->year = $year;
        $this->topMenus = $topMenus;
    }

    public function title(): string
    {
        return 'Menu Terlaris';
    }

    public function columnWidths(): array
    {
        return ['A' => 6, 'B' => 35, 'C' => 14, 'D' => 18, 'E' => 16, 'F' => 22];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
        ]);
        $sheet->getStyle('A3:F3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E07B39']],
        ]);

        // Highlight top 3
        for ($i = 4; $i <= min(6, 3 + $this->topMenus->count()); $i++) {
            $sheet->getStyle("A{$i}:F{$i}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFF9E6']],
            ]);
        }

        return [];
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = ["MENU TERLARIS — TAHUN {$this->year}"];
        $rows[] = [];
        $rows[] = ['No', 'Nama Menu', 'Kategori', 'Harga (Rp)', 'Total Terjual', 'Total Revenue (Rp)'];

        foreach ($this->topMenus as $idx => $menu) {
            $rows[] = [
                $idx + 1,
                $menu->nama_menu,
                ucfirst($menu->kategori),
                $menu->harga_c1,
                $menu->total_terjual,
                $menu->total_revenue,
            ];
        }

        return $rows;
    }
}
