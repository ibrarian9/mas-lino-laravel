<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $totalPesananHariIni = Pesanan::whereDate('waktu_pesan', $today)->count();
        $totalPendapatanHariIni = Pesanan::whereDate('waktu_pesan', $today)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_bayar');

        $pesananPerStatus = [
            'baru'      => Pesanan::where('status_pesanan', 'baru')->whereDate('waktu_pesan', $today)->count(),
            'diproses'  => Pesanan::where('status_pesanan', 'diproses')->whereDate('waktu_pesan', $today)->count(),
            'selesai'   => Pesanan::where('status_pesanan', 'selesai')->whereDate('waktu_pesan', $today)->count(),
        ];

        $pesananTerbaru = Pesanan::with('details.menu')
            ->orderByDesc('waktu_pesan')
            ->limit(10)
            ->get();

        $topBundles = Menu::where('is_bundle', true)
            ->where('total_order_c2', '>', 0)
            ->orderByDesc('total_order_c2')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesananHariIni',
            'totalPendapatanHariIni',
            'pesananPerStatus',
            'pesananTerbaru',
            'topBundles'
        ));
    }
}
