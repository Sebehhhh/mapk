<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Extracurricular;
use App\Models\Hero;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{
    $hero = Hero::where('is_active', true)->latest()->first();
    $pengumuman = Pengumuman::orderByDesc('tanggal')->paginate(6);
    $about = About::first();
    // Jika ekstrakurikuler (opsional):
    $extracurriculars = Extracurricular::latest()->get();

    // return view('welcome', compact('pengumuman', 'hero', 'about'));
    // Kalau ekstrakurikuler: tambahkan ke compact
    return view('welcome', compact('pengumuman', 'hero', 'about', 'extracurriculars'));
}
}
