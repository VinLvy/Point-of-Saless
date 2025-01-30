<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_laporan_penjualan';
    protected $fillable = [
        'laporan_penjualan_id',
        'produk_id',
        'jumlah',
        'harga',
        'total_harga',
    ];

    public function laporanPenjualan()
    {
        return $this->belongsTo(LaporanPenjualan::class, 'laporan_penjualan_id');
    }

    public function produk()
    {
        return $this->belongsTo(ItemBarang::class, 'produk_id');
    }
}
