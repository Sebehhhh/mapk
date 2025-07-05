<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    // Nama tabel kalau tidak plural otomatis
    protected $table = 'pengumuman';

    // Kolom yang bisa diisi mass assignment
    protected $fillable = [
        'judul',
        'tanggal',
        'isi',
    ];

    // Konversi tanggal ke Carbon otomatis
    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}