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
        $kategoriBarang = [
            ['kode_kategori' => 'KTG001', 'nama_kategori' => 'Makanan'],
            ['kode_kategori' => 'KTG002', 'nama_kategori' => 'Minuman'],
            ['kode_kategori' => 'KTG003', 'nama_kategori' => 'Obat'],
            ['kode_kategori' => 'KTG004', 'nama_kategori' => 'Kosmetik dan Perawatan Tubuh'],
            ['kode_kategori' => 'KTG005', 'nama_kategori' => 'Produk Rumah Tangga'],
            ['kode_kategori' => 'KTG006', 'nama_kategori' => 'Produk Bayi'],
            ['kode_kategori' => 'KTG007', 'nama_kategori' => 'Produk Hewan Peliharaan'],
        ];

        DB::table('kategori_barang')->insert($kategoriBarang);

        // Seeder untuk item_barang
        $itemBarang = [
            ['kode_barang' => 'BRG001', 'nama_barang' => 'Roti Tawar', 'satuan' => 'Pack', 'harga_beli' => 5000, 'harga_jual_1' => 5500, 'harga_jual_2' => 6000, 'harga_jual_3' => 6500, 'minimal_stok' => 10, 'kategori_id' => 1],
            ['kode_barang' => 'BRG002', 'nama_barang' => 'Susu UHT', 'satuan' => 'Kotak', 'harga_beli' => 8000, 'harga_jual_1' => 8800, 'harga_jual_2' => 9600, 'harga_jual_3' => 10400, 'minimal_stok' => 15, 'kategori_id' => 2],
            ['kode_barang' => 'BRG003', 'nama_barang' => 'Obat Flu', 'satuan' => 'Strip', 'harga_beli' => 10000, 'harga_jual_1' => 11000, 'harga_jual_2' => 12000, 'harga_jual_3' => 13000, 'minimal_stok' => 5, 'kategori_id' => 3],
            ['kode_barang' => 'BRG004', 'nama_barang' => 'Shampoo', 'satuan' => 'Botol', 'harga_beli' => 15000, 'harga_jual_1' => 16500, 'harga_jual_2' => 18000, 'harga_jual_3' => 19500, 'minimal_stok' => 8, 'kategori_id' => 4],
            ['kode_barang' => 'BRG005', 'nama_barang' => 'Sabun Cuci Piring', 'satuan' => 'Botol', 'harga_beli' => 12000, 'harga_jual_1' => 13200, 'harga_jual_2' => 14400, 'harga_jual_3' => 15600, 'minimal_stok' => 12, 'kategori_id' => 5],
            ['kode_barang' => 'BRG006', 'nama_barang' => 'Popok Bayi', 'satuan' => 'Pack', 'harga_beli' => 50000, 'harga_jual_1' => 55000, 'harga_jual_2' => 60000, 'harga_jual_3' => 65000, 'minimal_stok' => 6, 'kategori_id' => 6],
            ['kode_barang' => 'BRG007', 'nama_barang' => 'Makanan Kucing', 'satuan' => 'Kg', 'harga_beli' => 30000, 'harga_jual_1' => 33000, 'harga_jual_2' => 36000, 'harga_jual_3' => 39000, 'minimal_stok' => 7, 'kategori_id' => 7],
            ['kode_barang' => 'BRG008', 'nama_barang' => 'Makanan Anjing', 'satuan' => 'Kg', 'harga_beli' => 35000, 'harga_jual_1' => 38500, 'harga_jual_2' => 42000, 'harga_jual_3' => 45500, 'minimal_stok' => 5, 'kategori_id' => 7],
        ];

        DB::table('item_barang')->insert($itemBarang);
    }
}
