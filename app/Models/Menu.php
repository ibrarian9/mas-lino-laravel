<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tb_menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu', 'kategori', 'deskripsi', 'gambar',
        'harga_normal', 'diskon', 'harga_c1',
        'total_order_c2', 'rating_rata_rata_c3', 'jumlah_rating',
        'is_bundle', 'is_active',
    ];

    protected $casts = [
        'is_bundle' => 'boolean',
        'is_active' => 'boolean',
        'rating_rata_rata_c3' => 'float',
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_menu', 'id_menu');
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_c1, 0, ',', '.');
    }

    public function getFormattedHargaNormalAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_normal, 0, ',', '.');
    }
}
