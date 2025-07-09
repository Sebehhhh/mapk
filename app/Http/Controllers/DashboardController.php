<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentProgress;

class DashboardController extends Controller
{
    public function index()
    {
        // Set semua variabel awal null/null-safe
        $angkatanLabels = $angkatanData = $genderLabels = $genderData = null;
        $students_count = $users_count = $subjects_count = $student_parents_count = $exam_cards_count = $scores_count = null;
        $student = $subjects_count_siswa = $average_score = $ranking = $kelasTerbaru = $semesterTerbaru = $nilaiData = null;
        $historiNilai = null;

        // Ambil role user
        $role = Auth::user()->role ?? null;

        if ($role === 'admin') {
            // Data statistik admin
            $angkatan = \App\Models\Student::selectRaw('graduation_year, COUNT(*) as total')
                ->groupBy('graduation_year')
                ->orderBy('graduation_year', 'asc')
                ->get();
            $angkatanLabels = $angkatan->pluck('graduation_year')->toArray();
            $angkatanData = $angkatan->pluck('total')->toArray();

            $genderLabels = ['Laki-laki', 'Perempuan'];
            $genderData = [
                \App\Models\Student::where('gender', 'L')->count(),
                \App\Models\Student::where('gender', 'P')->count()
            ];

            $students_count        = \App\Models\Student::count();
            $users_count           = \App\Models\User::count();
            $subjects_count        = \App\Models\Subject::count();
            $student_parents_count = \App\Models\StudentParent::count();
            $exam_cards_count      = \App\Models\ExamCard::count();
            $scores_count          = \App\Models\Score::count();
        }

        if ($role === 'siswa') {
            $student = Auth::user()->student;

            if ($student) {
                // ================================
                // Ambil progress aktif/terakhir siswa
                // ================================
                $progress = $student->progresses()->orderByDesc('year')
                    ->orderByRaw("FIELD(class_level, 'XII','XI','X')")
                    ->orderByRaw("FIELD(semester, 'genap','ganjil')")
                    ->first();

                // Jika progress tidak ada, fallback ke null
                $kelasTerbaru = $semesterTerbaru = $tahunTerbaru = null;
                if ($progress) {
                    $kelasTerbaru = $progress->class_level;
                    $semesterTerbaru = $progress->semester;
                    $tahunTerbaru = $progress->year;
                }

                // ================================
                // Semua skor siswa dengan subject
                // ================================
                $allScores = $student->scores()->with('subject')->get();

                // Filter nilai sesuai progress aktif
                $scores = collect();
                if ($kelasTerbaru && $semesterTerbaru) {
                    $scores = $allScores
                        ->where('subject.class_level', $kelasTerbaru)
                        ->where('semester', $semesterTerbaru)
                        ->values();
                }

                // Hitung nilai akhir per mapel
                foreach ($scores as $score) {
                    $score->nilai_akhir = round(
                        ($score->attendance * 0.10) +
                            ($score->assignment * 0.20) +
                            ($score->mid_exam * 0.30) +
                            ($score->final_exam * 0.40),
                        1
                    );
                }

                // Rata-rata nilai akhir di kelas/semester progress aktif
                $average_score = $scores->count() > 0 ? round($scores->avg('nilai_akhir'), 2) : 0;
                $subjects_count_siswa = $scores->count();

                // Logika ranking di kelas/semester progress aktif
                $ranking = '-';
                if ($kelasTerbaru && $semesterTerbaru) {
                    $allStudents = \App\Models\Student::whereHas('progresses', function ($q) use ($kelasTerbaru, $semesterTerbaru, $tahunTerbaru) {
                        $q->where('class_level', $kelasTerbaru)
                            ->where('semester', $semesterTerbaru)
                            ->where('year', $tahunTerbaru);
                    })->with(['scores.subject', 'user', 'progresses'])->get();

                    $rankingData = [];
                    foreach ($allStudents as $s) {
                        // Cari nilai di kelas/semester progress yang sama
                        $studentScores = $s->scores
                            ->where('subject.class_level', $kelasTerbaru)
                            ->where('semester', $semesterTerbaru);

                        $nilaiAkhir = $studentScores->map(function ($score) {
                            return ($score->attendance * 0.10) +
                                ($score->assignment * 0.20) +
                                ($score->mid_exam * 0.30) +
                                ($score->final_exam * 0.40);
                        });

                        $avg = $nilaiAkhir->count() ? round($nilaiAkhir->avg(), 2) : 0;
                        $rankingData[] = [
                            'student_id' => $s->id,
                            'avg' => $avg
                        ];
                    }
                    usort($rankingData, fn($a, $b) => $b['avg'] <=> $a['avg']);

                    foreach ($rankingData as $idx => $row) {
                        if ($row['student_id'] == $student->id) {
                            $ranking = $idx + 1;
                            break;
                        }
                    }
                }

                // =======================
                // Histori Nilai per Kelas & Semester (tabel, bukan grafik)
                // =======================
                $kelasList = ['X', 'XI', 'XII'];
                $semesterList = ['ganjil', 'genap'];
                $historiNilai = [];
                foreach ($kelasList as $kelas) {
                    foreach ($semesterList as $semester) {
                        $nilaiKelasSemester = $allScores
                            ->where('subject.class_level', $kelas)
                            ->where('semester', $semester);

                        $avgNilai = $nilaiKelasSemester->count() > 0
                            ? round($nilaiKelasSemester->map(function ($score) {
                                return ($score->attendance * 0.10) +
                                    ($score->assignment * 0.20) +
                                    ($score->mid_exam * 0.30) +
                                    ($score->final_exam * 0.40);
                            })->avg(), 2)
                            : '-';

                        $historiNilai[] = [
                            'kelas'    => $kelas,
                            'semester' => $semester,
                            'nilai'    => $avgNilai,
                        ];
                    }
                }

                // NilaiData & chart tidak diperlukan lagi untuk siswa
                $nilaiData = null;
            } else {
                // Jika student null, set default aman
                $allScores = collect();
                $scores = collect();
                $classLevels = collect();
                $kelasTerbaru = null;
                $semesterTerbaru = null;
                $average_score = 0;
                $subjects_count_siswa = 0;
                $ranking = '-';
                $historiNilai = [];
                $nilaiData = null;
            }
        }

        // Return satu pintu, data lengkap, null-safe
        return view('dashboard.index', [
            // Admin
            'angkatanLabels'        => $angkatanLabels,
            'angkatanData'          => $angkatanData,
            'genderLabels'          => $genderLabels,
            'genderData'            => $genderData,
            'students_count'        => $students_count,
            'users_count'           => $users_count,
            'subjects_count'        => $subjects_count,
            'student_parents_count' => $student_parents_count,
            'exam_cards_count'      => $exam_cards_count,
            'scores_count'          => $scores_count,

            // Siswa
            'student'               => $student,
            'subjects_count_siswa'  => $subjects_count_siswa,
            'nilai_akhir_rata2'     => $average_score,
            'ranking'               => $ranking,
            'kelasTerbaru'          => $kelasTerbaru,
            'semesterTerbaru'       => $semesterTerbaru,
            'historiNilai'          => $historiNilai,
        ]);
    }
}
