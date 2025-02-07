<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $fillable = [
        'nama_pelanggan',
        'email',
        'no_hp',
        'alamat',
        'tipe_pelanggan',
        'poin_membership',
    ];

    public function laporanPenjualan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'pelanggan_id');
    }
}
