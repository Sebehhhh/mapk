<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// Rute Otentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Dashboard Umum (admin & siswa bisa akses)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Rute Admin Only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);
    Route::resource('student-parents', StudentParentController::class);
    Route::resource('subjects', SubjectController::class); 
    Route::resource('scores', ScoreController::class);
    Route::resource('exam-cards', ExamCardController::class); 
});

// Rute Siswa (jika perlu nanti)
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/student-profile', [ProfileController::class, 'index'])->name('student-profile');
});