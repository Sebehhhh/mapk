<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamCardController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\StudentProgressController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\SubjectController;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute Otentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/exam-cards/download-pdf', [ExamCardController::class, 'downloadPdf'])->name('exam-cards.download-pdf');
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
    Route::get('/rekap-ranking/pdf', [App\Http\Controllers\ScoreController::class, 'rekapPdf'])->name('scores.rekap.pdf');
    Route::resource('student-progresses', StudentProgressController::class);
    // Manajemen Homepage
    Route::resource('heroes', HeroController::class);
    Route::resource('extracurriculars', ExtracurricularController::class);
    Route::resource('abouts', AboutController::class);
    Route::post('/subject-users/store-batch', [StudentSubjectController::class, 'storeBatch'])->name('subject-users.store-batch');
    Route::get('/get-mapel', [StudentSubjectController::class, 'getMapel']);
});

// Rute Siswa

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/nilai', [ScoreController::class, 'studentIndex'])->name('student-scores');
    Route::get('/subject', [StudentSubjectController::class, 'subject'])->name('subjects.subject');
    Route::delete('/student-profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('student-profile.delete-photo');
});
