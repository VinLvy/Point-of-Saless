<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'petugas_id',
        'action',
        'model',
        'model_id',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    public function getDeskripsi()
    {
        $petugas = $this->petugas->nama_petugas ?? 'Petugas Tidak Diketahui';
        $model = ucfirst($this->model ?? '-');
        $model_id = $this->model_id ?? '-';
        $nama_data = $this->new_data['nama_barang'] ?? $this->new_data['nama_kategori'] ?? 'Data';

        switch ($this->action) {
            case 'tambah':
                return "$petugas menambahkan $model $nama_data";
            case 'edit':
                return "$petugas mengedit $model $nama_data";
            case 'hapus':
                return "$petugas menghapus $model $nama_data";
            case 'login':
                return "$petugas melakukan login";
            case 'logout':
                return "$petugas melakukan logout";
            default:
                return "$petugas melakukan aksi $this->action pada $model $model_id";
        }
    }
}
