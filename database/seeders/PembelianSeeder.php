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
            
        ];

        // Insert data pembelian
        DB::table('pembelian')->insert($pembelian);

        // Data detail pembelian
        $detailPembelian = [
        
        ];

        // Insert data detail pembelian
        DB::table('detail_pembelian')->insert($detailPembelian);
    }
}
