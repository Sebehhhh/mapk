<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // --- Ambil filter kelas (class_level) ---
        $class_level = $request->class_level;

        // Siswa diurutkan dan bisa difilter by kelas
        $students = Student::with('user')
            ->when($class_level, function ($q) use ($class_level) {
                $q->where('class', $class_level);
            })
            ->orderBy('nisn', 'asc')
            ->get();

        // Mapel juga bisa difilter by kelas
        $subjects = Subject::when($class_level, function ($q) use ($class_level) {
            $q->where('class_level', $class_level);
        })
            ->orderBy('name', 'asc')
            ->get();

        $mapels = [];

        // --- Form multi input nilai (input nilai banyak mapel sekaligus) ---
        if (
            $request->filled('student_id')
            && $request->filled('semester')
            && !$request->has('filter_table')
        ) {
            $student = Student::find($request->student_id);

            if ($student && $student->user) {
                // Hanya mapel yg diambil siswa di semester itu, dan by kelas
                $mapels = $student->user
                    ->subjects()
                    ->where('subjects.semester', $request->semester)
                    ->when($class_level, function ($q) use ($class_level) {
                        $q->where('subjects.class_level', $class_level);
                    })
                    ->orderBy('name', 'asc')
                    ->get();
            }
        }

        // --- Filtering tabel list nilai ---
        $query = Score::with(['student.user', 'subject']);

        // Jika filter table aktif (pakai tombol filter)
        if ($request->has('filter_table')) {
            if ($request->student_id) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->semester) {
                $query->where('semester', $request->semester);
            }
            if ($class_level) {
                $query->whereHas('subject', function ($q) use ($class_level) {
                    $q->where('class_level', $class_level);
                });
            }
            if ($request->subject_id) {
                $query->where('subject_id', $request->subject_id);
            }
        }

        $scores = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('scores.index', compact('scores', 'students', 'subjects', 'mapels'));
    }

    /**
     * Store nilai satuan (modal lama, masih dipakai di edit).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'subject_id'   => 'required|exists:subjects,id',
            'attendance'   => 'nullable|integer|min:0|max:100',
            'assignment'   => 'nullable|integer|min:0|max:100',
            'mid_exam'     => 'nullable|integer|min:0|max:100',
            'final_exam'   => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $subject = Subject::findOrFail($validatedData['subject_id']);
            $validatedData['semester'] = $subject->semester;
            $validatedData['year'] = date('Y');

            // Cek double entry
            $existingScore = Score::where('student_id', $validatedData['student_id'])
                ->where('subject_id', $validatedData['subject_id'])
                ->where('semester', $validatedData['semester'])
                ->where('year', $validatedData['year'])
                ->first();
            if ($existingScore) {
                return redirect()->back()->withInput()->with('error', 'Nilai untuk siswa, mata pelajaran, semester, dan tahun ini sudah ada.');
            }

            Score::create($validatedData);
            return redirect()->route('scores.index')->with('success', 'Nilai berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Store multiple nilai sekaligus (multi mapel).
     */
    public function storeMulti(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester' => 'required|in:ganjil,genap',
            'scores' => 'required|array',
        ]);

        $student_id = $request->student_id;
        $semester = $request->semester;
        $year = date('Y');

        foreach ($request->scores as $subject_id => $nilai) {
            // Validasi nilai per mapel - sekarang boleh kosong
            $hasAnyValue = false;
            
            // Cek apakah ada nilai yang diisi
            foreach (['attendance', 'assignment', 'mid_exam', 'final_exam'] as $field) {
                if (isset($nilai[$field]) && $nilai[$field] !== '' && $nilai[$field] !== null) {
                    $hasAnyValue = true;
                    // Validasi range nilai
                    if ($nilai[$field] < 0 || $nilai[$field] > 100) {
                        continue 2; // Skip ke subject berikutnya
                    }
                }
            }
            
            // Skip jika tidak ada nilai yang diisi
            if (!$hasAnyValue) {
                continue;
            }

            // Cek double entry
            $exists = Score::where('student_id', $student_id)
                ->where('subject_id', $subject_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->first();
            if ($exists) continue; // Skip jika sudah ada

            // Prepare data dengan handling null values
            $scoreData = [
                'student_id' => $student_id,
                'subject_id' => $subject_id,
                'semester' => $semester,
                'year' => $year,
                'attendance' => isset($nilai['attendance']) && $nilai['attendance'] !== '' ? $nilai['attendance'] : null,
                'assignment' => isset($nilai['assignment']) && $nilai['assignment'] !== '' ? $nilai['assignment'] : null,
                'mid_exam' => isset($nilai['mid_exam']) && $nilai['mid_exam'] !== '' ? $nilai['mid_exam'] : null,
                'final_exam' => isset($nilai['final_exam']) && $nilai['final_exam'] !== '' ? $nilai['final_exam'] : null,
            ];

            Score::create($scoreData);
        }

        return redirect()->route('scores.index')->with('success', 'Nilai berhasil disimpan. Nilai yang kosong akan tetap kosong dan bisa diisi kemudian.');
    }

    /**
     * Update nilai satuan.
     */
    public function update(Request $request, Score $score)
    {
        $validatedData = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'subject_id'   => 'required|exists:subjects,id',
            'attendance'   => 'nullable|integer|min:0|max:100',
            'assignment'   => 'nullable|integer|min:0|max:100',
            'mid_exam'     => 'nullable|integer|min:0|max:100',
            'final_exam'   => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $subject = Subject::findOrFail($validatedData['subject_id']);
            $validatedData['semester'] = $subject->semester;
            $validatedData['year'] = date('Y');

            $existingScore = Score::where('student_id', $validatedData['student_id'])
                ->where('subject_id', $validatedData['subject_id'])
                ->where('semester', $validatedData['semester'])
                ->where('year', $validatedData['year'])
                ->where('id', '!=', $score->id)
                ->first();

            if ($existingScore) {
                return redirect()->back()->withInput()->with('error', 'Nilai untuk siswa, mata pelajaran, semester, dan tahun ini sudah ada.');
            }

            // Handle null values for empty inputs
            $updateData = $validatedData;
            foreach (['attendance', 'assignment', 'mid_exam', 'final_exam'] as $field) {
                if (isset($updateData[$field]) && $updateData[$field] === '') {
                    $updateData[$field] = null;
                }
            }
            
            $score->update($updateData);
            return redirect()->route('scores.index')->with('success', 'Nilai berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui nilai: ' . $e->getMessage());
        }
    }

    /**
     * Update partial scores (untuk update nilai yang belum lengkap).
     */
    public function updatePartial(Request $request, Score $score)
    {
        $validatedData = $request->validate([
            'attendance'   => 'nullable|integer|min:0|max:100',
            'assignment'   => 'nullable|integer|min:0|max:100',
            'mid_exam'     => 'nullable|integer|min:0|max:100',
            'final_exam'   => 'nullable|integer|min:0|max:100',
        ]);

        try {
            // Handle null values for empty inputs
            $updateData = [];
            foreach (['attendance', 'assignment', 'mid_exam', 'final_exam'] as $field) {
                if (isset($validatedData[$field])) {
                    $updateData[$field] = $validatedData[$field] === '' ? null : $validatedData[$field];
                }
            }
            
            $score->update($updateData);
            return redirect()->route('scores.index')->with('success', 'Nilai berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui nilai: ' . $e->getMessage());
        }
    }

    /**
     * Remove nilai.
     */
    public function destroy(Score $score)
    {
        try {
            $score->delete();
            return redirect()->route('scores.index')->with('success', 'Nilai berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus nilai: ' . $e->getMessage());
        }
    }

    /**
     * Rekap ranking
     */
    public function rekap(Request $request)
    {
        $kelas = $request->input('kelas', 'XII');
        $semester = $request->input('semester', 'genap');

        $students = Student::whereHas('scores', function ($q) use ($kelas, $semester) {
            $q->where('semester', $semester)
                ->whereHas('subject', function ($q2) use ($kelas) {
                    $q2->where('class_level', $kelas);
                });
        })->with(['user', 'scores.subject'])->get();

        $rekap = [];
        foreach ($students as $siswa) {
            $nilaiAkhir = [];
            foreach ($siswa->scores as $score) {
                if ($score->semester == $semester && $score->subject->class_level == $kelas) {
                    // Hanya hitung jika semua nilai sudah diisi
                    if ($score->isComplete()) {
                        $nilaiAkhir[] =
                            ($score->attendance * 0.10) +
                            ($score->assignment * 0.20) +
                            ($score->mid_exam * 0.30) +
                            ($score->final_exam * 0.40);
                    }
                }
            }
            $avg = count($nilaiAkhir) ? round(array_sum($nilaiAkhir) / count($nilaiAkhir), 2) : 0;
            $rekap[] = [
                'siswa' => $siswa,
                'kelas' => $kelas,
                'semester' => $semester,
                'avg' => $avg,
            ];
        }
        usort($rekap, fn($a, $b) => $b['avg'] <=> $a['avg']);
        foreach ($rekap as $i => &$row) {
            $row['rank'] = $i + 1;
        }

        $availableClasses = ['X', 'XI', 'XII'];
        $availableSemesters = ['ganjil' => 'Ganjil', 'genap' => 'Genap'];

        return view('scores.rekap', compact('rekap', 'kelas', 'semester', 'availableClasses', 'availableSemesters'));
    }

    /**
     * Siswa lihat nilai sendiri.
     */
    public function studentIndex(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $allScores = $student->scores()->with('subject')->get();
        $classLevels = $allScores->pluck('subject.class_level')->unique()->sort()->values();
        $semesters = ['ganjil', 'genap'];

        $kelasTerbaru = $classLevels->contains('XII') ? 'XII' : ($classLevels->contains('XI') ? 'XI' : 'X');
        $semTerbaru = $allScores->where('subject.class_level', $kelasTerbaru)->pluck('semester')->unique();
        $semesterDefault = $semTerbaru->contains('genap') ? 'genap' : 'ganjil';

        $filterKelas = $request->input('kelas', $kelasTerbaru);
        $filterSemester = $request->input('semester', $semesterDefault);

        $scores = $allScores
            ->where('subject.class_level', $filterKelas)
            ->where('semester', $filterSemester)
            ->values();

        foreach ($scores as $score) {
            // Hanya hitung nilai akhir jika semua nilai sudah diisi
            if ($score->isComplete()) {
                $score->nilai_akhir = round(
                    ($score->attendance * 0.10) +
                        ($score->assignment * 0.20) +
                        ($score->mid_exam * 0.30) +
                        ($score->final_exam * 0.40),
                    1
                );
            } else {
                $score->nilai_akhir = null; // Belum lengkap
            }
        }

        // Hitung rata-rata hanya dari nilai yang sudah lengkap
        $completedScores = $scores->filter(function ($score) {
            return $score->nilai_akhir !== null;
        });
        $nilai_akhir_rata2 = $completedScores->count() > 0 ? round($completedScores->avg('nilai_akhir'), 2) : 0;

        // Ranking
        $allStudents = Student::whereHas('scores', function ($q) use ($filterKelas, $filterSemester) {
            $q->where('semester', $filterSemester)
                ->whereHas('subject', function ($q2) use ($filterKelas) {
                    $q2->where('class_level', $filterKelas);
                });
        })->with(['scores.subject', 'user'])->get();

        $rankingData = [];
        foreach ($allStudents as $s) {
            $studentScores = $s->scores
                ->where('semester', $filterSemester)
                ->where('subject.class_level', $filterKelas);

            $nilaiAkhir = $studentScores->map(function ($score) {
                // Hanya hitung jika semua nilai sudah diisi
                if ($score->isComplete()) {
                    return ($score->attendance * 0.10) +
                        ($score->assignment * 0.20) +
                        ($score->mid_exam * 0.30) +
                        ($score->final_exam * 0.40);
                }
                return null; // Jika belum lengkap
            })->filter(function ($nilai) {
                return $nilai !== null; // Filter out null values
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

        $availableClasses = ['X', 'XI', 'XII'];
        $availableSemesters = ['ganjil' => 'Ganjil', 'genap' => 'Genap'];

        return view('student.scores.index', [
            'scores' => $scores,
            'student' => $student,
            'nilai_akhir_rata2' => $nilai_akhir_rata2,
            'ranking' => $ranking,
            'filterKelas' => $filterKelas,
            'filterSemester' => $filterSemester,
            'availableClasses' => $availableClasses,
            'availableSemesters' => $availableSemesters,
        ]);
    }
}
