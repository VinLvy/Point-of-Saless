<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        DB::table('pelanggan')->insert([
            [
                'nama_pelanggan' => 'Budi Santoso',
                'no_hp' => '08123',
                'alamat' => 'TA',
                'email' => 'budi@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
