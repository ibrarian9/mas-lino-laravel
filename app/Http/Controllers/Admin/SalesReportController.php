<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Services\SAWService;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $availableYears = Pesanan::selectRaw('YEAR(waktu_pesan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [$year];
        }

        // Monthly sales summary
        $monthlySales = Pesanan::selectRaw('
                MONTH(waktu_pesan) as bulan,
                COUNT(*) as total_pesanan,
                SUM(CASE WHEN status_pembayaran = "lunas" THEN total_bayar ELSE 0 END) as total_pendapatan,
                SUM(CASE WHEN status_pembayaran = "lunas" THEN 1 ELSE 0 END) as pesanan_lunas,
                SUM(CASE WHEN status_pembayaran = "menunggu" THEN 1 ELSE 0 END) as pesanan_menunggu,
                SUM(CASE WHEN status_pembayaran = "gagal" THEN 1 ELSE 0 END) as pesanan_gagal
            ')
            ->whereYear('waktu_pesan', $year)
            ->groupByRaw('MONTH(waktu_pesan)')
            ->orderByRaw('MONTH(waktu_pesan)')
            ->get()
            ->keyBy('bulan');

        // Fill all 12 months
        $months = [];
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        for ($m = 1; $m <= 12; $m++) {
            $data = $monthlySales->get($m);
            $months[$m] = [
                'bulan'             => $m,
                'nama_bulan'        => $namaBulan[$m],
                'total_pesanan'     => $data->total_pesanan ?? 0,
                'total_pendapatan'  => $data->total_pendapatan ?? 0,
                'pesanan_lunas'     => $data->pesanan_lunas ?? 0,
                'pesanan_menunggu'  => $data->pesanan_menunggu ?? 0,
                'pesanan_gagal'     => $data->pesanan_gagal ?? 0,
            ];
        }

        // Yearly totals
        $totalPendapatan = array_sum(array_column($months, 'total_pendapatan'));
        $totalPesanan = array_sum(array_column($months, 'total_pesanan'));
        $totalLunas = array_sum(array_column($months, 'pesanan_lunas'));

        // Top selling menus for the year
        $topMenus = DetailPesanan::select('tb_detail_pesanan.id_menu')
            ->selectRaw('tb_menu.nama_menu, tb_menu.kategori, tb_menu.harga_c1')
            ->selectRaw('SUM(tb_detail_pesanan.kuantitas) as total_terjual')
            ->selectRaw('SUM(tb_detail_pesanan.subtotal) as total_revenue')
            ->join('tb_pesanan', 'tb_detail_pesanan.id_pesanan', '=', 'tb_pesanan.id_pesanan')
            ->join('tb_menu', 'tb_detail_pesanan.id_menu', '=', 'tb_menu.id_menu')
            ->whereYear('tb_pesanan.waktu_pesan', $year)
            ->where('tb_pesanan.status_pembayaran', 'lunas')
            ->groupBy('tb_detail_pesanan.id_menu', 'tb_menu.nama_menu', 'tb_menu.kategori', 'tb_menu.harga_c1')
            ->orderByDesc('total_terjual')
            ->get();

        // SAW recommendations
        $sawService = new SAWService();
        $sawRanking = $sawService->calculate();

        return view('admin.sales-report', compact(
            'year', 'availableYears', 'months', 'namaBulan',
            'totalPendapatan', 'totalPesanan', 'totalLunas',
            'topMenus', 'sawRanking'
        ));
    }

    public function exportExcel(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Rebuild the same data for export
        $monthlySales = Pesanan::selectRaw('
                MONTH(waktu_pesan) as bulan,
                COUNT(*) as total_pesanan,
                SUM(CASE WHEN status_pembayaran = "lunas" THEN total_bayar ELSE 0 END) as total_pendapatan,
                SUM(CASE WHEN status_pembayaran = "lunas" THEN 1 ELSE 0 END) as pesanan_lunas
            ')
            ->whereYear('waktu_pesan', $year)
            ->groupByRaw('MONTH(waktu_pesan)')
            ->orderByRaw('MONTH(waktu_pesan)')
            ->get()
            ->keyBy('bulan');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $data = $monthlySales->get($m);
            $months[$m] = [
                'nama_bulan'       => $namaBulan[$m],
                'total_pesanan'    => $data->total_pesanan ?? 0,
                'total_pendapatan' => $data->total_pendapatan ?? 0,
                'pesanan_lunas'    => $data->pesanan_lunas ?? 0,
            ];
        }

        $topMenus = DetailPesanan::select('tb_detail_pesanan.id_menu')
            ->selectRaw('tb_menu.nama_menu, tb_menu.kategori, tb_menu.harga_c1')
            ->selectRaw('SUM(tb_detail_pesanan.kuantitas) as total_terjual')
            ->selectRaw('SUM(tb_detail_pesanan.subtotal) as total_revenue')
            ->join('tb_pesanan', 'tb_detail_pesanan.id_pesanan', '=', 'tb_pesanan.id_pesanan')
            ->join('tb_menu', 'tb_detail_pesanan.id_menu', '=', 'tb_menu.id_menu')
            ->whereYear('tb_pesanan.waktu_pesan', $year)
            ->where('tb_pesanan.status_pembayaran', 'lunas')
            ->groupBy('tb_detail_pesanan.id_menu', 'tb_menu.nama_menu', 'tb_menu.kategori', 'tb_menu.harga_c1')
            ->orderByDesc('total_terjual')
            ->get();

        $sawService = new SAWService();
        $sawRanking = $sawService->calculate();

        $filename = 'Laporan_Penjualan_' . $year . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(
            new SalesReportExport($year, $months, $topMenus, $sawRanking),
            $filename,
            ExcelFormat::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}
