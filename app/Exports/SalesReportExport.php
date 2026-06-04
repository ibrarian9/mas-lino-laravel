<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    protected int $year;
    protected array $months;
    protected $topMenus;
    protected array $sawRanking;

    public function __construct(int $year, array $months, $topMenus, array $sawRanking)
    {
        $this->year = $year;
        $this->months = $months;
        $this->topMenus = $topMenus;
        $this->sawRanking = $sawRanking;
    }

    public function sheets(): array
    {
        return [
            'Penjualan Bulanan'  => new Sheets\PenjualanBulananSheet($this->year, $this->months),
            'Menu Terlaris'      => new Sheets\MenuTerlarisSheet($this->year, $this->topMenus),
            'Rekomendasi SAW'    => new Sheets\RekomendasiSAWSheet($this->sawRanking),
        ];
    }
}
