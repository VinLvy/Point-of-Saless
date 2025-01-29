<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tabel kategori barang
        Schema::create('kategori_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori')->unique();
            $table->string('nama_kategori')->unique();
            $table->timestamps();
        });

        // Tabel item barang
        Schema::create('item_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->date('tanggal_kedaluarsa');
            $table->date('tanggal_pembelian');
            $table->integer('harga_beli');
            $table->integer('harga_jual_1'); // HPP + 10%
            $table->integer('harga_jual_2'); // HPP + 20%
            $table->integer('harga_jual_3'); // HPP + 30%
            $table->integer('stok');
            $table->integer('minimal_stok');
            $table->foreignId('kategori_id')->constrained('kategori_barang')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_barang');
        Schema::dropIfExists('kategori_barang');
    }
};
