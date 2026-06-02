<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Services\SAWService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('customer.home');
    }

    public function setMeja(Request $request)
    {
        $request->validate([
            'no_meja' => 'required|string|max:10|regex:/^[A-Za-z0-9\-]+$/',
        ]);

        session(['no_meja' => $request->no_meja]);
        return redirect()->route('customer.menu');
    }

    public function menu()
    {
        if (!session('no_meja')) {
            return redirect()->route('customer.home')->with('error', 'Silakan masukkan nomor meja terlebih dahulu.');
        }

        $reguler = Menu::where('kategori', 'reguler')->where('is_active', true)->get();
        $bundling = Menu::where('kategori', 'bundling')->where('is_active', true)->get();

        $sawService = new SAWService();
        $rekomendasi = array_slice($sawService->calculate(), 0, 3);

        $cart = session('cart', []);
        $cartCount = array_sum(array_column($cart, 'qty'));

        return view('customer.menu', compact('reguler', 'bundling', 'rekomendasi', 'cart', 'cartCount'));
    }

    public function rekomendasi()
    {
        $sawService = new SAWService();
        $results = $sawService->calculate();
        return response()->json($results);
    }

    public function status($id_pesanan)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id_pesanan);
        return view('customer.order-status', compact('pesanan'));
    }

    public function statusJson($id_pesanan)
    {
        $pesanan = Pesanan::findOrFail($id_pesanan);
        return response()->json([
            'status_pesanan'    => $pesanan->status_pesanan,
            'status_pembayaran' => $pesanan->status_pembayaran,
        ]);
    }
}
