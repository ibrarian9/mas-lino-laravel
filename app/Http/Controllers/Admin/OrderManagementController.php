<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('details.menu')->orderByDesc('waktu_pesan');

        if ($request->filled('status_pesanan')) {
            $query->where('status_pesanan', $request->status_pesanan);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_pesan', $request->tanggal);
        }

        $pesanan = $query->paginate(15);

        return view('admin.orders.index', compact('pesanan'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('details.menu', 'admin')->findOrFail($id);
        return view('admin.orders.show', compact('pesanan'));
    }

    public function validateCash($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->metode_bayar !== 'tunai') {
            return back()->with('error', 'Pesanan ini bukan pembayaran tunai.');
        }

        $pesanan->status_pembayaran = 'lunas';
        $pesanan->id_admin = Auth::guard('admin')->id();
        $pesanan->save();

        return back()->with('success', 'Pembayaran tunai telah divalidasi.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai,dibatalkan',
        ]);

        $pesanan = Pesanan::with('details.menu')->findOrFail($id);
        $pesanan->status_pesanan = $request->status;
        $pesanan->id_admin = Auth::guard('admin')->id();
        $pesanan->save();

        // Update total_order_c2 saat pesanan selesai
        if ($request->status === 'selesai') {
            foreach ($pesanan->details as $detail) {
                if ($detail->menu && $detail->menu->is_bundle) {
                    $detail->menu->increment('total_order_c2', $detail->kuantitas);
                }
            }
        }

        return back()->with('success', 'Status pesanan diperbarui menjadi: ' . $request->status);
    }

    public function pesananBaru()
    {
        $count = Pesanan::where('status_pesanan', 'baru')
            ->where('status_pembayaran', 'lunas')
            ->count();

        return response()->json(['count' => $count]);
    }
}
