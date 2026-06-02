<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function show($id_pesanan)
    {
        $pesanan = Pesanan::with('details.menu')->findOrFail($id_pesanan);

        if ($pesanan->status_pesanan !== 'selesai') {
            return redirect()->route('order.status', $id_pesanan)
                ->with('error', 'Pesanan belum selesai.');
        }

        // Get existing ratings for this order
        $existingRatings = Rating::where('id_pesanan', $id_pesanan)
            ->pluck('id_menu')
            ->toArray();

        return view('customer.rating', compact('pesanan', 'existingRatings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan'          => 'required|exists:tb_pesanan,id_pesanan',
            'ratings'             => 'required|array',
            'ratings.*.id_menu'   => 'required|exists:tb_menu,id_menu',
            'ratings.*.nilai'     => 'required|integer|between:1,5',
            'ratings.*.ulasan'    => 'nullable|string|max:500',
        ]);

        $idPesanan = $request->id_pesanan;

        foreach ($request->ratings as $ratingData) {
            // Check for duplicate
            $exists = Rating::where('id_pesanan', $idPesanan)
                ->where('id_menu', $ratingData['id_menu'])
                ->exists();

            if ($exists) {
                continue; // Skip if already rated
            }

            Rating::create([
                'id_pesanan'    => $idPesanan,
                'id_menu'       => $ratingData['id_menu'],
                'nilai_bintang' => $ratingData['nilai'],
                'ulasan'        => $ratingData['ulasan'] ?? null,
                'waktu_rating'  => now(),
            ]);

            // Update rata-rata rating di menu
            $menu = Menu::find($ratingData['id_menu']);
            if ($menu) {
                $avgRating = Rating::where('id_menu', $ratingData['id_menu'])->avg('nilai_bintang');
                $jumlahRating = Rating::where('id_menu', $ratingData['id_menu'])->count();

                $menu->update([
                    'rating_rata_rata_c3' => round($avgRating, 2),
                    'jumlah_rating'       => $jumlahRating,
                ]);
            }
        }

        return redirect()->route('order.status', $idPesanan)
            ->with('success', 'Terima kasih atas rating Anda!');
    }
}
