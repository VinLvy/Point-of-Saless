<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian'; // Nama tabel
    protected $fillable = [
        'pembelian_id',
        'produk',
        'total_harga',
    ];

    /**
     * Relasi ke model Pembelian.
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    /**
     * Mendekode kolom JSON produk.
     */
    public function getProdukAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Encode data produk sebelum disimpan.
     */
    public function setProdukAttribute($value)
    {
        $this->attributes['produk'] = json_encode($value);
    }
}
