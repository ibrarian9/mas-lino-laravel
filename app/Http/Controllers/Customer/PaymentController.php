<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'metode_bayar' => 'required|in:tunai,non_tunai',
        ]);

        $cart = session('cart', []);
        $noMeja = session('no_meja');

        if (empty($cart) || !$noMeja) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong atau nomor meja belum diatur.');
        }

        $totalBayar = array_sum(array_column($cart, 'subtotal'));
        $orderId = Pesanan::generateOrderId();

        // Create pesanan
        $pesanan = Pesanan::create([
            'id_pesanan'        => $orderId,
            'no_meja'           => $noMeja,
            'total_bayar'       => $totalBayar,
            'metode_bayar'      => $request->metode_bayar,
            'status_pembayaran' => 'menunggu',
            'status_pesanan'    => 'baru',
            'midtrans_order_id' => $request->metode_bayar === 'non_tunai' ? $orderId : null,
            'waktu_pesan'       => now(),
        ]);

        // Create detail pesanan
        foreach ($cart as $item) {
            DetailPesanan::create([
                'id_pesanan' => $orderId,
                'id_menu'    => $item['id_menu'],
                'kuantitas'  => $item['qty'],
                'subtotal'   => $item['subtotal'],
            ]);
        }

        // Clear cart & store last order id for status nav
        session()->forget('cart');
        session(['last_order_id' => $orderId]);

        return redirect()->route('payment.show', $orderId);
    }

    public function show($id_pesanan)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id_pesanan);
        $snapToken = null;

        if ($pesanan->metode_bayar === 'non_tunai' && $pesanan->status_pembayaran === 'menunggu') {
            try {
                $midtrans = new MidtransService();

                $items = [];
                foreach ($pesanan->details as $detail) {
                    $items[] = [
                        'id'       => $detail->id_menu,
                        'price'    => $detail->menu->harga_c1,
                        'quantity' => $detail->kuantitas,
                        'name'     => substr($detail->menu->nama_menu, 0, 50),
                    ];
                }

                $snapToken = $midtrans->createSnapToken([
                    'order_id'    => $pesanan->id_pesanan,
                    'total_bayar' => $pesanan->total_bayar,
                    'no_meja'     => $pesanan->no_meja,
                    'items'       => $items,
                ]);
            } catch (\Exception $e) {
                \Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            }
        }

        return view('customer.payment', compact('pesanan', 'snapToken'));
    }

    public function confirmCash($id_pesanan)
    {
        $pesanan = Pesanan::findOrFail($id_pesanan);
        // Cash confirmation is done by admin, customer is just notified
        return redirect()->route('order.status', $id_pesanan);
    }
}
