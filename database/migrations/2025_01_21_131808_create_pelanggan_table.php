<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tabel pelanggan
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('email')->unique()->nullable();
            $table->string('no_hp');
            $table->text('alamat');
            $table->integer('poin_membership')->default(0);
            $table->enum('tipe_pelanggan', ['tipe 1', 'tipe 2', 'tipe 3']);
            $table->timestamps();
            $table->softDeletes(); // Soft Delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
};
