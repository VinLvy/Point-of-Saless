<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run()
    {
        DB::table('petugas')->insert([
            [
                'nama_petugas' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'administrator',
            ],
            [
                'nama_petugas' => 'Kasir',
                'email' => 'kasir@gmail.com',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ],
        ]);
    }
}
