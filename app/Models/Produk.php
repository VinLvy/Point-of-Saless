<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = [
        'nama_produk', 
        'kode_produk', 
        'harga', 
        'stok', 
        'deskripsi', 
        'gambar', 
        'kategori_id'
    ];

    // Relasi ke Kategori (produk memiliki satu kategori)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
