<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamCardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// Rute Otentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [HomeController::class, 'index']);

// Rute Dashboard Umum (admin & siswa bisa akses)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/student-profile', [ProfileController::class, 'index'])->name('student-profile');
    Route::get('/student-profile/edit', [ProfileController::class, 'edit'])->name('student-profile.edit');
    Route::put('/student-profile/update-student', [ProfileController::class, 'updateStudent'])->name('student-profile.update-student');
    Route::put('/student-profile/update-parent', [ProfileController::class, 'updateParent'])->name('student-profile.update-parent');
    Route::put('/student-profile/update-user', [ProfileController::class, 'updateUser'])->name('student-profile.update-user');
    Route::put('/student-profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('student-profile.update-photo');
    Route::resource('exam-cards', ExamCardController::class);
    Route::resource('pengumuman', PengumumanController::class);
    
});

// Rute Admin Only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('students', StudentController::class);
    Route::resource('student-parents', StudentParentController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('scores', ScoreController::class);
    Route::post('scores/store-multi', [ScoreController::class, 'storeMulti'])->name('scores.store-multi');
    Route::resource('subject-users', StudentSubjectController::class);
    Route::get('/rekap-ranking', [ScoreController::class, 'rekap'])->name('scores.rekap');
});

// Rute Siswa

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/nilai', [ScoreController::class, 'studentIndex'])->name('student-scores');
    Route::get('/subject', [StudentSubjectController::class, 'subject'])->name('subjects.subject');
    
});
