<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\SoftDeletes;

class KategoriBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_barang';
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            // Ambil kode kategori terakhir
            $lastKategori = self::orderBy('id', 'desc')->first();
            $lastCode = $lastKategori ? $lastKategori->kode_kategori : 'KTG000';

            // Generate kode kategori baru
            $number = (int) substr($lastCode, 3) + 1;
            $kategori->kode_kategori = 'KTG' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }


    public function itemBarang()
    {
        return $this->hasMany(ItemBarang::class, 'kategori_id');
    }

    protected $dates = ['deleted_at'];
}
