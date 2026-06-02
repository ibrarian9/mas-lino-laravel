<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'tb_pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan', 'no_meja', 'total_bayar', 'metode_bayar',
        'status_pembayaran', 'status_pesanan', 'midtrans_order_id',
        'waktu_pesan', 'id_admin',
    ];

    protected $casts = [
        'waktu_pesan' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_pesanan', 'id_pesanan');
    }

    public static function generateOrderId(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('waktu_pesan', today())->count() + 1;
        return 'ORD-' . $date . '-' . str_pad($lastOrder, 4, '0', STR_PAD_LEFT);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_bayar, 0, ',', '.');
    }
}
