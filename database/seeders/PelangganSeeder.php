<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        DB::table('pelanggan')->insert([
            [
                'nama_pelanggan' => 'Andi Saputra',
                'email' => 'andi@example.com',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'poin_membership' => 100,
                'tipe_pelanggan' => 'tipe 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pelanggan' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'no_hp' => '081298765432',
                'alamat' => 'Jl. Sudirman No. 20, Bandung',
                'poin_membership' => 200,
                'tipe_pelanggan' => 'tipe 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pelanggan' => 'Citra Dewi',
                'email' => 'citra@example.com',
                'no_hp' => '082345678901',
                'alamat' => 'Jl. Diponegoro No. 30, Surabaya',
                'poin_membership' => 150,
                'tipe_pelanggan' => 'tipe 3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
