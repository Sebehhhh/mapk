<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderByDesc('tanggal')->paginate(6);
        return view('welcome', compact('pengumuman'));
    }
}
