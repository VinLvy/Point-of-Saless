<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
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
        'poin_didapat',
        'total_akhir',
        'uang_dibayar',
        'kembalian',
        'tanggal_transaksi',
        'kode_transaksi',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            $lastTransaction = DB::table('laporan_penjualan')->latest('id')->first();
            $nextNumber = $lastTransaction ? intval(substr($lastTransaction->kode_transaksi, 2)) + 1 : 1;
            $transaksi->kode_transaksi = 'PB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'laporan_penjualan_id');
    }
}
