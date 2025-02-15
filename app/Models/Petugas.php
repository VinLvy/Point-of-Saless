<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // Tambahkan SoftDeletes

    protected $table = 'petugas';

    protected $fillable = [
        'nama_petugas',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at']; // Tambahkan ini untuk mendukung soft delete
}