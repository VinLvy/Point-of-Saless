<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tabel pembelian untuk mencatat transaksi secara umum
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->bigInteger('total_belanja'); // Total harga semua produk
            $table->bigInteger('total_bayar');  // Jumlah uang yang dibayarkan
            $table->bigInteger('kembalian')->nullable(); // Kembalian, jika ada
            $table->integer('poin_digunakan')->default(0); // Poin yang digunakan pelanggan
            $table->dateTime('tanggal_pembelian'); // Tanggal dan waktu pembelian
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade'); // Petugas yang menangani pembelian
            $table->timestamps();
        });

        // Tabel detail_pembelian untuk mencatat detail produk per transaksi
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade'); // Relasi ke tabel pembelian
            $table->json('produk'); // JSON untuk menyimpan detail produk dalam satu baris
            $table->integer('total_harga'); // Total harga dari semua produk dalam transaksi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembelian');
        Schema::dropIfExists('pembelian');
    }
};
