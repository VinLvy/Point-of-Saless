<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        DB::table('produk')->insert([
            [
                'nama_produk' => 'Laptop',
                'kode_produk' => 'LPT123',
                'harga' => 5000000,
                'stok' => 10,
                'deskripsi' => 'Laptop gaming dengan spesifikasi tinggi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Mouse',
                'kode_produk' => 'MSE456',
                'harga' => 200000,
                'stok' => 50,
                'deskripsi' => 'Mouse wireless untuk keperluan kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
