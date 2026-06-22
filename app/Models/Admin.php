<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'tb_admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = ['username', 'password', 'role'];

    protected $hidden = ['password'];

    public function isManajemen(): bool
    {
        return $this->role === 'manajemen';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_admin', 'id_admin');
    }
}
