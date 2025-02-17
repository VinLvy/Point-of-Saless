<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_barang';
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'harga_beli',
        'harga_jual_1',
        'harga_jual_2',
        'harga_jual_3',
        'harga_per_pack',
        'minimal_stok',
        'kategori_id',
    ];    

    protected static function boot()
    {
        parent::boot();

        // Event creating untuk mengisi kode_barang otomatis
        static::creating(function ($item) {
            // Ambil item terakhir termasuk yang dihapus
            $lastItem = self::withTrashed()->orderBy('kode_barang', 'desc')->first();
            $lastCode = $lastItem ? $lastItem->kode_barang : 'BRG000';

            // Ekstrak angka dari kode terakhir, lalu tambahkan 1
            $number = (int) substr($lastCode, 3) + 1;
            $item->kode_barang = 'BRG' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    // Relasi ke stok
    public function stok()
    {
        return $this->hasMany(Stok::class, 'item_id');
    }

    protected $dates = ['deleted_at'];
}
