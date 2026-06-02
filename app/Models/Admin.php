<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'tb_admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = ['username', 'password'];

    protected $hidden = ['password'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_admin', 'id_admin');
    }
}
