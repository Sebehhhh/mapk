<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk admin
        $angkatanLabels = $angkatanData = $genderLabels = $genderData = null;
        $students_count = $users_count = $subjects_count = $student_parents_count = $exam_cards_count = $scores_count = null;

        // Data untuk siswa
        $student = $subjects_count_siswa = $average_score = $latest_exam_card_year = $nilaiData = null;

        if (auth()->user()->role === 'admin') {
            // Statistik angkatan
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

        if (auth()->user()->role === 'siswa') {
            $student = auth()->user()->student;

            // Ambil semua skor siswa (dengan relasi subject)
            $allScores = $student->scores()->with('subject')->get();

            // Cek kelas & semester yang tersedia
            $classLevels = $allScores->pluck('subject.class_level')->unique()->sort()->values();
            $kelasTerbaru = $classLevels->contains('XII') ? 'XII' : ($classLevels->contains('XI') ? 'XI' : 'X');
            $semTerbaru = $allScores->where('subject.class_level', $kelasTerbaru)->pluck('semester')->unique();
            $semesterTerbaru = $semTerbaru->contains('genap') ? 'genap' : 'ganjil';

            // Filter untuk kelas & semester terakhir
            $scores = $allScores
                ->where('subject.class_level', $kelasTerbaru)
                ->where('semester', $semesterTerbaru)
                ->values();

            // Hitung nilai akhir per mapel (sama seperti di studentIndex)
            foreach ($scores as $score) {
                $score->nilai_akhir = round(
                    ($score->attendance * 0.10) +
                        ($score->assignment * 0.20) +
                        ($score->mid_exam * 0.30) +
                        ($score->final_exam * 0.40),
                    1
                );
            }

            // Rata-rata nilai akhir
            $nilai_akhir_rata2 = $scores->count() > 0 ? round($scores->avg('nilai_akhir'), 2) : 0;
            $subjects_count = $scores->count();

            // ---- LOGIKA RANKING SAMA SEPERTI DI STUDENTINDEX ----
            $allStudents = \App\Models\Student::whereHas('scores', function ($q) use ($kelasTerbaru, $semesterTerbaru) {
                $q->where('semester', $semesterTerbaru)
                    ->whereHas('subject', function ($q2) use ($kelasTerbaru) {
                        $q2->where('class_level', $kelasTerbaru);
                    });
            })->with(['scores.subject', 'user'])->get();

            $rankingData = [];
            foreach ($allStudents as $s) {
                $studentScores = $s->scores
                    ->where('semester', $semesterTerbaru)
                    ->where('subject.class_level', $kelasTerbaru);

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

            $ranking = '-';
            foreach ($rankingData as $idx => $row) {
                if ($row['student_id'] == $student->id) {
                    $ranking = $idx + 1;
                    break;
                }
            }
            // ------------------------------------------------------

            // Untuk grafik nilai (per semester)
            $scoresPerSemester = $allScores
                ->groupBy('semester')
                ->map(function ($group) {
                    return round($group->avg(function ($score) {
                        return ($score->attendance * 0.10) +
                            ($score->assignment * 0.20) +
                            ($score->mid_exam * 0.30) +
                            ($score->final_exam * 0.40);
                    }), 2);
                });

            $nilaiData = [
                'labels' => $scoresPerSemester->keys()->toArray(),
                'data' => $scoresPerSemester->values()->toArray(),
            ];

            return view('dashboard.index', [
                // ...tambahkan data admin jika perlu
                'student' => $student,
                'subjects_count_siswa' => $subjects_count,
                'nilai_akhir_rata2' => $nilai_akhir_rata2,
                'ranking' => $ranking,
                'kelasTerbaru' => $kelasTerbaru,
                'semesterTerbaru' => $semesterTerbaru,
                'nilaiData' => $nilaiData,
            ]);
        }
    }
}
