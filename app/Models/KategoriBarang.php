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
            // Generate kode kategori baru dengan 5 angka random
            $kategori->kode_kategori = 'KTG' . mt_rand(10000, 99999);
        });
    }

    public function itemBarang()
    {
        return $this->hasMany(ItemBarang::class, 'kategori_id');
    }

    protected $dates = ['deleted_at'];
}
