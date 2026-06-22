<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

use App\Models\Rating;
use App\Models\DetailPesanan;

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

    public function resetData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Rating::truncate();
        DetailPesanan::truncate();
        Pesanan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset rating stats
        Menu::query()->update([
            'rating_rata_rata_c3' => 3.0,
            'jumlah_rating'       => 0,
        ]);

        // Kembalikan nilai C2 bundling ke nilai awal
        $c2Awal = [
            1  => 0.400183,
            2  => 0.699956,
            3  => 0.400183,
            4  => 0.699956,
            5  => 0.277889,
            6  => 0.577662,
            7  => 0.277889,
            8  => 0.577662,
            9  => 0.122268,
            10 => 0.422042,
        ];

        foreach ($c2Awal as $id => $val) {
            Menu::where('id_menu', $id)->update(['total_order_c2' => $val]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Semua data pesanan, detail, rating, dan statistik menu berhasil direset.');
    }
}
