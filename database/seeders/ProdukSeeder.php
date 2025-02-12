<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        // Seeder untuk kategori_barang
        DB::table('kategori_barang')->insert([
            ['kode_kategori' => 'KTG001', 'nama_kategori' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kategori' => 'KTG002', 'nama_kategori' => 'Pakaian', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kategori' => 'KTG003', 'nama_kategori' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seeder untuk item_barang
        DB::table('item_barang')->insert([
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop',
                'harga_beli' => 10000000,
                'harga_jual_1' => 11000000,
                'harga_jual_2' => 12000000,
                'harga_jual_3' => 13000000,
                'harga_per_pack' => 10500000,
                'minimal_stok' => 5,
                'kategori_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Kaos Polos',
                'harga_beli' => 50000,
                'harga_jual_1' => 55000,
                'harga_jual_2' => 60000,
                'harga_jual_3' => 65000,
                'harga_per_pack' => 52000,
                'minimal_stok' => 20,
                'kategori_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Biskuit',
                'harga_beli' => 15000,
                'harga_jual_1' => 16500,
                'harga_jual_2' => 18000,
                'harga_jual_3' => 19500,
                'harga_per_pack' => 15500,
                'minimal_stok' => 50,
                'kategori_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Seeder untuk stok berdasarkan item_barang
        DB::table('stok')->insert([
            [
                'item_id' => 1, // Laptop
                'jumlah_stok' => 50,
                'expired_date' => Carbon::create(2027, 12, 31),
                'buy_date' => Carbon::create(2024, 1, 15),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'item_id' => 2, // Kaos Polos
                'jumlah_stok' => 200,
                'expired_date' => Carbon::create(2026, 6, 15),
                'buy_date' => Carbon::create(2024, 2, 10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'item_id' => 3, // Biskuit
                'jumlah_stok' => 500,
                'expired_date' => Carbon::create(2025, 3, 30),
                'buy_date' => Carbon::create(2024, 3, 5),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
