<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Rating;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing seeded data
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Rating::truncate();
        DetailPesanan::truncate();
        Pesanan::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $menus = Menu::where('is_active', true)->get();

        if ($menus->isEmpty()) {
            $this->command->warn('No active menus found. Run BundlingSeeder first.');
            return;
        }

        $year = now()->year;
        $mejas = ['A1', 'A2', 'A3', 'B1', 'B2', 'B3', 'C1', 'C2'];
        $metodeBayar = ['tunai', 'non_tunai'];

        $ulasanSamples = [
            'Enak banget!', 'Rasanya pas di lidah', 'Porsinya mantap',
            'Coklatnya kerasa banget', 'Recommended!', 'Bakal pesan lagi',
            'Rotinya lembut', 'Harga terjangkau, rasa premium', 'Suka banget!',
            'Favorit keluarga', 'Anak-anak suka', 'Manisnya pas',
            'Ukurannya besar, puas', 'Worth it harganya', 'Top markotop!',
            'Pelayanan cepat, rasa oke', 'Bundlingnya hemat banget',
            null, null, null, // some without review text
        ];

        $currentMonth = now()->month;
        $orderCounter = 0;
        $ratingCounter = 0;

        for ($month = 1; $month <= $currentMonth; $month++) {
            $ordersThisMonth = rand(8, 15) + ($month * 2);

            for ($o = 0; $o < $ordersThisMonth; $o++) {
                $orderCounter++;
                $day = rand(1, min(28, Carbon::create($year, $month)->daysInMonth));
                $hour = rand(10, 21);
                $minute = rand(0, 59);
                $waktuPesan = Carbon::create($year, $month, $day, $hour, $minute);

                $orderId = 'ORD-' . $waktuPesan->format('Ymd') . '-' . str_pad($orderCounter, 4, '0', STR_PAD_LEFT);
                $meja = $mejas[array_rand($mejas)];
                $metode = $metodeBayar[array_rand($metodeBayar)];

                // Pick 1–3 random menu items
                $numItems = rand(1, 3);
                $selectedMenus = $menus->random(min($numItems, $menus->count()));
                $totalBayar = 0;
                $details = [];

                foreach ($selectedMenus as $menu) {
                    $qty = rand(1, 3);
                    $subtotal = $menu->harga_c1 * $qty;
                    $totalBayar += $subtotal;
                    $details[] = [
                        'id_menu'   => $menu->id_menu,
                        'kuantitas' => $qty,
                        'subtotal'  => $subtotal,
                    ];
                }

                // 80% lunas, 10% menunggu, 10% gagal
                $rand = rand(1, 100);
                if ($rand <= 80) {
                    $statusPembayaran = 'lunas';
                    $statusPesanan = collect(['selesai', 'selesai', 'selesai', 'diproses'])->random();
                } elseif ($rand <= 90) {
                    $statusPembayaran = 'menunggu';
                    $statusPesanan = 'baru';
                } else {
                    $statusPembayaran = 'gagal';
                    $statusPesanan = 'baru';
                }

                $pesanan = Pesanan::create([
                    'id_pesanan'        => $orderId,
                    'no_meja'           => $meja,
                    'total_bayar'       => $totalBayar,
                    'metode_bayar'      => $metode,
                    'status_pembayaran' => $statusPembayaran,
                    'status_pesanan'    => $statusPesanan,
                    'midtrans_order_id' => $metode === 'non_tunai' ? $orderId : null,
                    'waktu_pesan'       => $waktuPesan,
                ]);

                foreach ($details as $detail) {
                    DetailPesanan::create(array_merge($detail, [
                        'id_pesanan' => $orderId,
                    ]));
                }

                // Generate ratings for completed lunas orders (70% chance)
                if ($statusPembayaran === 'lunas' && $statusPesanan === 'selesai' && rand(1, 100) <= 70) {
                    foreach ($details as $detail) {
                        $ratingCounter++;

                        // Weighted random: more 4s and 5s, fewer 1s and 2s
                        $ratingValues = [5, 5, 5, 5, 4, 4, 4, 3, 3, 2];
                        $nilai = $ratingValues[array_rand($ratingValues)];

                        Rating::create([
                            'id_pesanan'    => $orderId,
                            'id_menu'       => $detail['id_menu'],
                            'nilai_bintang' => $nilai,
                            'ulasan'        => $ulasanSamples[array_rand($ulasanSamples)],
                            'waktu_rating'  => $waktuPesan->copy()->addMinutes(rand(30, 180)),
                        ]);
                    }
                }
            }
        }

        // Recalculate menu stats from actual data
        foreach ($menus as $menu) {
            $totalSold = DetailPesanan::where('id_menu', $menu->id_menu)
                ->whereHas('pesanan', fn($q) => $q->where('status_pembayaran', 'lunas'))
                ->sum('kuantitas');

            $ratings = Rating::where('id_menu', $menu->id_menu)->get();
            $avgRating = $ratings->count() > 0 ? round($ratings->avg('nilai_bintang'), 2) : 0;

            $menu->update([
                'total_order_c2'     => $totalSold,
                'rating_rata_rata_c3' => $avgRating,
                'jumlah_rating'      => $ratings->count(),
            ]);
        }

        $this->command->info("✅ Seeded {$orderCounter} orders, {$ratingCounter} ratings across {$currentMonth} months of {$year}.");
    }
}
