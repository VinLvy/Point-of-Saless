<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian'; // Nama tabel
    protected $fillable = [
        'pelanggan_id',
        'total_belanja',
        'total_bayar',
        'kembalian',
        'poin_digunakan',
        'tanggal_pembelian',
        'petugas_id',
    ];

    /**
     * Relasi ke model Pelanggan.
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Relasi ke model Petugas.
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Relasi ke model DetailPembelian.
     */
    public function detailPembelian()
    {
        return $this->hasOne(DetailPembelian::class, 'pembelian_id');
    }
}
