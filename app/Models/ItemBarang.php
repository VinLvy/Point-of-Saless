<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBarang extends Model
{
    use HasFactory;

    protected $table = 'item_barang';
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'tanggal_kedaluarsa',
        'tanggal_pembelian',
        'harga_beli',
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'stok',
        'minimal_stok',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function detailLaporanPenjualan()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'produk_id');
    }
}
