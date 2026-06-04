<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SAWService;
use App\Exports\SAWReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class SAWReportController extends Controller
{
    public function index()
    {
        $sawService = new SAWService();
        $result = $sawService->calculateDetailed();

        return view('admin.saw-report', [
            'bobot'      => $result['bobot'],
            'raw'        => $result['raw'],
            'minMax'     => $result['min_max'],
            'normalized' => $result['normalized'],
            'ranking'    => $result['ranking'],
        ]);
    }

    public function exportExcel()
    {
        $sawService = new SAWService();
        $result = $sawService->calculateDetailed();

        $filename = 'SAW_Report_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new SAWReportExport($result),
            $filename,
            ExcelFormat::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}
