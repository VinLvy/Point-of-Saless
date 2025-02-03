<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan';
    protected $fillable = [
        'pelanggan_id',
        'petugas_id',
        'tipe_pelanggan',
        'total_belanja',
        'diskon',
        'poin_digunakan',
        'total_akhir',
        'uang_dibayar',
        'kembalian',
        'tanggal_transaksi',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id'); // Sesuai dengan model User di Laravel
    }

    public function detail()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'laporan_penjualan_id');
    }
}
