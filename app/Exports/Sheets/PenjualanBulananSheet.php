<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenjualanBulananSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected int $year;
    protected array $months;

    public function __construct(int $year, array $months)
    {
        $this->year = $year;
        $this->months = $months;
    }

    public function title(): string
    {
        return 'Penjualan Bulanan';
    }

    public function columnWidths(): array
    {
        return ['A' => 6, 'B' => 16, 'C' => 18, 'D' => 22, 'E' => 16];
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

        // Total row
        $totalRow = 4 + 12;
        $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFF9E6']],
        ]);

        return [];
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = ["LAPORAN PENJUALAN BULANAN — TAHUN {$this->year}"];
        $rows[] = [];
        $rows[] = ['No', 'Bulan', 'Total Pesanan', 'Total Pendapatan (Rp)', 'Pesanan Lunas'];

        $totalPesanan = 0;
        $totalPendapatan = 0;
        $totalLunas = 0;

        foreach ($this->months as $idx => $m) {
            $rows[] = [$idx, $m['nama_bulan'], $m['total_pesanan'], $m['total_pendapatan'], $m['pesanan_lunas']];
            $totalPesanan += $m['total_pesanan'];
            $totalPendapatan += $m['total_pendapatan'];
            $totalLunas += $m['pesanan_lunas'];
        }

        $rows[] = ['', 'TOTAL', $totalPesanan, $totalPendapatan, $totalLunas];

        return $rows;
    }
}
