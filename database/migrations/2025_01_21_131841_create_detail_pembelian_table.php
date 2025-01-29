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
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');       // Relasi ke tabel produk
            $table->integer('jumlah');                                                        // Jumlah produk yang dibeli
            $table->integer('harga');                                                         // Harga satuan produk
            $table->integer('total_harga');                                                   // Total harga per produk (jumlah x harga satuan)
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembelian');
        Schema::dropIfExists('pembelian');
    }
};
