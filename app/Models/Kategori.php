<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    // Relasi ke Produk (satu kategori memiliki banyak produk)
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
