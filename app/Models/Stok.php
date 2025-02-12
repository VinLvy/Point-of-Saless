<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';
    protected $fillable = [
        'item_id',
        'jumlah_stok',
        'expired_date',
        'buy_date',
    ];

    // Relasi ke item barang
    public function itemBarang()
    {
        return $this->belongsTo(ItemBarang::class, 'item_id');
    }
}
