<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelanggan';
    protected $fillable = [
        'nama_pelanggan',
        'email',
        'no_hp',
        'alamat',
        'tipe_pelanggan',
        'poin_membership',
    ];

    public function laporanPenjualan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'pelanggan_id');
    }

    public function tambahPoin($poin_didapat)
    {
        $this->increment('poin_membership', $poin_didapat);

        if ($this->poin_membership >= 100000 && $this->tipe_pelanggan !== 'tipe 1') {
            $this->update(['tipe_pelanggan' => 'tipe 1']);
        }
    }

    protected $dates = ['deleted_at'];
}
