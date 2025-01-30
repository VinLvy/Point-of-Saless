<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        DB::table('kategori_barang')->insert([
            ['kode_kategori' => 'KTG001', 'nama_kategori' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kategori' => 'KTG002', 'nama_kategori' => 'Pakaian', 'created_at' => now(), 'updated_at' => now()],
            ['kode_kategori' => 'KTG003', 'nama_kategori' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

class ItemBarangSeeder extends Seeder
{
    public function run()
    {
        DB::table('item_barang')->insert([
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop',
                'tanggal_kedaluarsa' => '2027-12-31',
                'tanggal_pembelian' => '2024-01-15',
                'harga_beli' => 10000000,
                'harga_jual_1' => 11000000,
                'harga_jual_2' => 12000000,
                'harga_jual_3' => 13000000,
                'stok' => 50,
                'minimal_stok' => 5,
                'kategori_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Kaos Polos',
                'tanggal_kedaluarsa' => '2026-06-15',
                'tanggal_pembelian' => '2024-02-10',
                'harga_beli' => 50000,
                'harga_jual_1' => 55000,
                'harga_jual_2' => 60000,
                'harga_jual_3' => 65000,
                'stok' => 200,
                'minimal_stok' => 20,
                'kategori_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Biskuit',
                'tanggal_kedaluarsa' => '2025-03-30',
                'tanggal_pembelian' => '2024-03-05',
                'harga_beli' => 15000,
                'harga_jual_1' => 16500,
                'harga_jual_2' => 18000,
                'harga_jual_3' => 19500,
                'stok' => 500,
                'minimal_stok' => 50,
                'kategori_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
