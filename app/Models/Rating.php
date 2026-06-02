<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'tb_rating';
    protected $primaryKey = 'id_rating';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan', 'id_menu', 'nilai_bintang', 'ulasan', 'waktu_rating',
    ];

    protected $casts = [
        'waktu_rating' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
