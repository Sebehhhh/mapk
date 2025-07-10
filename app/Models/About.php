<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',        // Judul
        'photo',        // Gambar
        'description',  // Deskripsi
        'address',      // Alamat
        'instagram',    // Instagram
        'akreditasi',   // Akreditasi
        'email',        // Email
    ];
}