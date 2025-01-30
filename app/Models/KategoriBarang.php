<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'kategori_barang';
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
    ];

    public function itemBarang()
    {
        return $this->hasMany(ItemBarang::class, 'kategori_id');
    }
}
