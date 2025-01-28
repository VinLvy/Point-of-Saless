<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembelianSeeder extends Seeder
{
    public function run()
    {
        // Data pembelian
        $pembelian = [
            [
                'pelanggan_id' => 1,
                'petugas_id' => 3,
                'total_belanja' => 10400000,
                'total_bayar' => 20000000,
                'poin_digunakan' => 0,
                'tanggal_pembelian' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelanggan_id' => 1,
                'petugas_id' => 3,
                'total_belanja' => 10000000,
                'total_bayar' => 20000000,
                'poin_digunakan' => 0,
                'tanggal_pembelian' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data pembelian
        DB::table('pembelian')->insert($pembelian);

        // Data detail pembelian
        $detailPembelian = [
            [
                'pembelian_id' => 1,
                'produk' => json_encode([
                    [
                        'id' => 1,
                        'nama' => 'Mouse',
                        'jumlah' => 2,
                        'harga_satuan' => 200000,
                        'total_harga' => 400000,
                    ],
                    [
                        'id' => 2,
                        'nama' => 'Laptop',
                        'jumlah' => 1,
                        'harga_satuan' => 1000000,
                        'total_harga' => 1000000,
                    ],
                ]),
                'total_harga' => 10400000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pembelian_id' => 2,
                'produk' => json_encode([
                    [
                        'id' => 1,
                        'nama' => 'Laptop',
                        'jumlah' => 1,
                        'harga_satuan' => 10000000,
                        'total_harga' => 10000000,
                    ],
                ]),
                'total_harga' => 10000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data detail pembelian
        DB::table('detail_pembelian')->insert($detailPembelian);
    }
}
