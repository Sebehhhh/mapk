<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'photo',
        'vision',
        'mission',
        'address',
        'latitude',
        'longitude',
        'email',
        'phone',
        'whatsapp',
        'website',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'kepala_sekolah',
        'nip_kepsek',
        'tahun_berdiri',
        'akreditasi',
        'jumlah_siswa',
        'jumlah_guru',
        'fasilitas',
        'struktur_organisasi',
    ];
}