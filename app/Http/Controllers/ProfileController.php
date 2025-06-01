<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        return view('profile.student', compact('student'));
    }
}
