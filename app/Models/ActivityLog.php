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
        $model = ucwords(str_replace('_', ' ', $this->model ?? '-'));
        $model_id = $this->model_id ?? '-';

        // Ambil nama dari new_data atau old_data jika aksi adalah "hapus"
        $nama_data = $this->new_data['nama_barang'] ?? $this->old_data['nama_barang']
            ?? $this->new_data['nama_kategori'] ?? $this->old_data['nama_kategori']
            ?? $this->new_data['nama_pelanggan'] ?? $this->old_data['nama_pelanggan']
            ?? $this->new_data['nama_petugas'] ?? $this->old_data['nama_petugas']
            ?? $this->new_data['total_belanja']
            ?? 'Data';

        if (isset($this->new_data['total_akhir']) || isset($this->old_data['total_belanja'])) {
            $nama_data = 'Rp ' . number_format($nama_data, 0, ',', '.');
        }

        switch ($this->action) {
            case 'transaksi':
                return "$petugas melakukan transaksi penjualan sebesar $nama_data";
            case 'tambah':
                // Cek apakah stok bertambah dan tampilkan nama barangnya
                if (!empty($this->new_data['stok'])) {
                    return "$petugas menambahkan stok untuk barang $nama_data";
                }
                return "$petugas menambahkan $nama_data pada $model";
            case 'edit':
                return "$petugas mengedit $nama_data pada $model";
            case 'hapus':
                return "$petugas menghapus $nama_data pada $model";
            case 'login':
                return "$petugas melakukan login";
            case 'logout':
                return "$petugas melakukan logout";
            default:
                return "$petugas melakukan aksi $this->action pada $model $model_id";
        }
    }
}
