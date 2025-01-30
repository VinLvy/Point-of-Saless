<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PelangganSeeder::class,      // Seeder pelanggan harus dijalankan terlebih dahulu
            PetugasSeeder::class,        // Seeder petugas
            ProdukSeeder::class,         // Seeder produk
            ItemBarangSeeder::class,
        ]);
    }
}
