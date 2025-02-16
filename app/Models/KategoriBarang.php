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
            $latestKategori = KategoriBarang::withTrashed()
                ->orderByRaw("CAST(SUBSTRING(kode_kategori, 4) AS UNSIGNED) DESC")
                ->first();

            if ($latestKategori) {
                $lastNumber = (int) substr($latestKategori->kode_kategori, 3); // Ambil angka dari KTGXXX
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $kategori->kode_kategori = 'KTG' . str_pad($newNumber, 3, '0', STR_PAD_LEFT); // Format KTGXXX
        });
    }

    public function itemBarang()
    {
        return $this->hasMany(ItemBarang::class, 'kategori_id');
    }

    protected $dates = ['deleted_at'];
}
