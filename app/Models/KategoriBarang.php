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

    public static function generateKodeKategori()
    {
        $latest = self::latest()->first();
        if (!$latest) {
        return 'KTG001';
    }

        $lastNumber = (int) substr($latest->kode_kategori, 3);
        return 'KTG' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }


    public function itemBarang()
    {
        return $this->hasMany(ItemBarang::class, 'kategori_id');
    }
}
