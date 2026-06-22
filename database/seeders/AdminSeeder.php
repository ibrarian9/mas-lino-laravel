<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_admin')->insert([
            [
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'manajemen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username'   => 'kasir',
                'password'   => Hash::make('kasir123'),
                'role'       => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
