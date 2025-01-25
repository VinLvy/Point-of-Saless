<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPembelianSeeder extends Seeder
{
    public function run()
    {
        DB::table('detail_pembelian')->insert([
            [
                'produk_id' => 1,  // Sesuaikan dengan produk yang sudah di-insert
                'pelanggan_id' => 1,  // Sesuaikan dengan pelanggan yang sudah di-insert
                'petugas_id' => 1,  // Sesuaikan dengan petugas yang sudah di-insert
                'jumlah' => 3,
                'total_harga' => 1500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
