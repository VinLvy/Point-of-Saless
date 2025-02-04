<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tabel laporan penjualan untuk mencatat transaksi secara umum
        Schema::create('laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->string('tipe_pelanggan');
            $table->bigInteger('total_belanja'); 
            $table->bigInteger('diskon')->default(0);
            $table->integer('poin_digunakan')->default(0);
            $table->integer('poin_didapat')->default(0);
            $table->bigInteger('total_akhir');
            $table->bigInteger('uang_dibayar')->default(0);
            $table->bigInteger('kembalian')->default(0);
            $table->dateTime('tanggal_transaksi'); 
            $table->timestamps();
        });

        // Tabel detail laporan penjualan untuk mencatat produk yang dibeli per transaksi
        Schema::create('detail_laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_penjualan_id')->constrained('laporan_penjualan')->onDelete('cascade'); // Relasi ke laporan penjualan
            $table->foreignId('produk_id')->constrained('item_barang')->onDelete('cascade'); // Relasi ke produk
            $table->integer('jumlah');
            $table->integer('harga'); 
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_laporan_penjualan');
        Schema::dropIfExists('laporan_penjualan');
    }
};
