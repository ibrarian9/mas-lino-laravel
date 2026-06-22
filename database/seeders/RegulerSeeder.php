<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegulerSeeder extends Seeder
{
    public function run(): void
    {
        $regulers = [
            ['nama_menu' => 'Air Mineral',  'harga_normal' => 5000,  'harga_c1' => 5000],
            ['nama_menu' => 'Pop Mie',      'harga_normal' => 8000,  'harga_c1' => 8000],
            ['nama_menu' => 'Roti Bakar',   'harga_normal' => 7000,  'harga_c1' => 7000],
            ['nama_menu' => 'Roti Biasa',   'harga_normal' => 5000,  'harga_c1' => 5000],
            ['nama_menu' => 'Si Besar',     'harga_normal' => 12000, 'harga_c1' => 12000],
            ['nama_menu' => 'Si Jumbo',     'harga_normal' => 18000, 'harga_c1' => 18000],
            ['nama_menu' => 'Si Kecil',     'harga_normal' => 7000,  'harga_c1' => 7000],
        ];

        foreach ($regulers as $item) {
            DB::table('tb_menu')->insert([
                'nama_menu'           => $item['nama_menu'],
                'kategori'            => 'reguler',
                'deskripsi'           => $item['nama_menu'],
                'gambar'              => null,
                'harga_normal'        => $item['harga_normal'],
                'diskon'              => 0,
                'harga_c1'            => $item['harga_c1'],
                'total_order_c2'      => 0,
                'rating_rata_rata_c3' => 3.0,
                'jumlah_rating'       => 0,
                'is_bundle'           => false,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }
}
