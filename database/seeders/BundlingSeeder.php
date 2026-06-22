<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BundlingSeeder extends Seeder
{
    public function run(): void
    {
        $bundles = [
            ['nama_menu' => '2 Si Kecil + 2 Roti Biasa',  'harga_normal' => 24000, 'diskon' => 1000, 'harga_c1' => 23000, 'total_order_c2' => 0.400183],
            ['nama_menu' => '2 Si Kecil + 2 Roti Bakar',  'harga_normal' => 26000, 'diskon' => 1000, 'harga_c1' => 25000, 'total_order_c2' => 0.699956],
            ['nama_menu' => '4 Si Kecil + 4 Roti Biasa',  'harga_normal' => 48000, 'diskon' => 2000, 'harga_c1' => 46000, 'total_order_c2' => 0.400183],
            ['nama_menu' => '4 Si Kecil + 4 Roti Bakar',  'harga_normal' => 52000, 'diskon' => 2000, 'harga_c1' => 50000, 'total_order_c2' => 0.699956],
            ['nama_menu' => '2 Si Besar + 2 Roti Biasa',  'harga_normal' => 34000, 'diskon' => 3000, 'harga_c1' => 31000, 'total_order_c2' => 0.277889],
            ['nama_menu' => '2 Si Besar + 2 Roti Bakar',  'harga_normal' => 36000, 'diskon' => 3000, 'harga_c1' => 33000, 'total_order_c2' => 0.577662],
            ['nama_menu' => '4 Si Besar + 4 Roti Biasa',  'harga_normal' => 68000, 'diskon' => 4000, 'harga_c1' => 64000, 'total_order_c2' => 0.277889],
            ['nama_menu' => '4 Si Besar + 4 Roti Bakar',  'harga_normal' => 72000, 'diskon' => 4000, 'harga_c1' => 68000, 'total_order_c2' => 0.577662],
            ['nama_menu' => '1 Si Jumbo + 6 Roti Biasa',  'harga_normal' => 72000, 'diskon' => 6000, 'harga_c1' => 66000, 'total_order_c2' => 0.122268],
            ['nama_menu' => '1 Si Jumbo + 6 Roti Bakar',  'harga_normal' => 78000, 'diskon' => 8000, 'harga_c1' => 70000, 'total_order_c2' => 0.422042],
        ];

        foreach ($bundles as $bundle) {
            DB::table('tb_menu')->insert([
                'nama_menu'           => $bundle['nama_menu'],
                'kategori'            => 'bundling',
                'deskripsi'           => 'Paket bundling ' . $bundle['nama_menu'],
                'gambar'              => null,
                'harga_normal'        => $bundle['harga_normal'],
                'diskon'              => $bundle['diskon'],
                'harga_c1'            => $bundle['harga_c1'],
                'total_order_c2'      => $bundle['total_order_c2'],
                'rating_rata_rata_c3' => 3.0,
                'jumlah_rating'       => 0,
                'is_bundle'           => true,
                'is_active'           => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }
}
