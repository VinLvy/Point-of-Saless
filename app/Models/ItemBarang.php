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

    protected static function boot()
    {
        parent::boot();

        // Event creating akan dipanggil sebelum data disimpan ke database
        static::creating(function ($item) {
            // Ambil kode barang terakhir
            $lastItem = self::orderBy('id', 'desc')->first();
            $lastCode = $lastItem ? $lastItem->kode_barang : 'BRG000';

            // Generate kode barang baru
            $number = (int) substr($lastCode, 3) + 1; // Ambil angka dari kode terakhir dan tambahkan 1
            $item->kode_barang = 'BRG' . str_pad($number, 3, '0', STR_PAD_LEFT); // Format kode barang
        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function detailLaporanPenjualan()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'produk_id');
    }
}
