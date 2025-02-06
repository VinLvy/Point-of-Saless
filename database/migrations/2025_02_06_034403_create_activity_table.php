<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');
            $table->string('action'); // login, logout, tambah, edit, hapus
            $table->string('model')->nullable(); // Nama tabel yang diubah
            $table->unsignedBigInteger('model_id')->nullable(); // ID dari data yang diubah
            $table->json('old_data')->nullable(); // Data sebelum perubahan (untuk edit/hapus)
            $table->json('new_data')->nullable(); // Data setelah perubahan (untuk edit/tambah)
            $table->string('ip_address')->nullable(); // IP pengguna
            $table->text('user_agent')->nullable(); // Info perangkat
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
