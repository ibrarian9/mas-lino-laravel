<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SAWReportExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            'Bobot & Kriteria' => new Sheets\BobotKriteriaSheet($this->data),
            'Normalisasi'      => new Sheets\NormalisasiSheet($this->data),
            'Preferensi'       => new Sheets\PreferensiSheet($this->data),
        ];
    }
}
