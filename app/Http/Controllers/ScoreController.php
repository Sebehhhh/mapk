<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student; // Import model Student
use App\Models\Subject; // Import model Subject
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data nilai dari database, dengan eager loading relasi student dan subject
        // Diurutkan berdasarkan created_at terbaru, dan dipaginasi 10 data per halaman
        $scores = Score::with(['student.user', 'subject'])->orderBy('created_at', 'desc')->paginate(10);

        // Mengambil semua data siswa dan mata pelajaran untuk dropdown di modal
        $students = Student::orderBy('nisn', 'asc')->get();
        $subjects = Subject::orderBy('name', 'asc')->get();
        // Mengirim data ke view 'scores.index'
        return view('scores.index', compact('scores', 'students', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'subject_id'   => 'required|exists:subjects,id',
            'attendance'   => 'required|integer|min:0|max:100',
            'assignment'   => 'required|integer|min:0|max:100',
            'mid_exam'     => 'required|integer|min:0|max:100',
            'final_exam'   => 'required|integer|min:0|max:100',
        ], [
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'subject_id.exists' => 'Mata pelajaran tidak ditemukan.',
        ]);

        try {
            $subject = Subject::findOrFail($validatedData['subject_id']);

            $validatedData['semester'] = $subject->semester;
            $validatedData['year'] = date('Y');

            $existingScore = Score::where('student_id', $validatedData['student_id'])
                ->where('subject_id', $validatedData['subject_id'])
                ->where('semester', $validatedData['semester'])
                ->where('year', $validatedData['year'])
                ->first();

            if ($existingScore) {
                return redirect()->back()->withInput()->with('error', 'Nilai untuk siswa, mata pelajaran, semester, dan tahun ini sudah ada.');
            }

            // Simpan data tanpa total_score dan rank
            Score::create($validatedData);

            return redirect()->route('scores.index')->with('success', 'Nilai berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Score $score)
    {
        $validatedData = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'subject_id'   => 'required|exists:subjects,id',
            'attendance'   => 'required|integer|min:0|max:100',
            'assignment'   => 'required|integer|min:0|max:100',
            'mid_exam'     => 'required|integer|min:0|max:100',
            'final_exam'   => 'required|integer|min:0|max:100',
        ], [
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'subject_id.exists' => 'Mata pelajaran tidak ditemukan.',
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

            $score->update($validatedData);

            return redirect()->route('scores.index')->with('success', 'Nilai berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui nilai: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score) // Menggunakan Route Model Binding
    {
        try {
            // Menghapus record nilai dari database
            $score->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('scores.index')->with('success', 'Nilai berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->with('error', 'Gagal menghapus nilai: ' . $e->getMessage());
        }
    }

    public function rekap(Request $request)
    {
        // Ambil filter kelas & semester (default: XII Genap biar yg terbaru)
        $kelas = $request->input('kelas', 'XII');
        $semester = $request->input('semester', 'genap');

        // Ambil semua siswa yg punya nilai di kelas dan semester tsb
        $students = \App\Models\Student::whereHas('scores', function ($q) use ($kelas, $semester) {
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
                    $nilaiAkhir[] =
                        ($score->attendance * 0.10) +
                        ($score->assignment * 0.20) +
                        ($score->mid_exam * 0.30) +
                        ($score->final_exam * 0.40);
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

        // Ranking
        usort($rekap, fn($a, $b) => $b['avg'] <=> $a['avg']);
        foreach ($rekap as $i => &$row) {
            $row['rank'] = $i + 1;
        }

        // Data untuk filter di view (optional, bisa di-hardcode juga)
        $availableClasses = ['X', 'XI', 'XII'];
        $availableSemesters = ['ganjil' => 'Ganjil', 'genap' => 'Genap'];

        return view('scores.rekap', compact('rekap', 'kelas', 'semester', 'availableClasses', 'availableSemesters'));
    }


    public function studentIndex(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404, 'Siswa tidak ditemukan');
        }

        // Semua nilai milik siswa (dengan relasi subject)
        $allScores = $student->scores()->with('subject')->get();

        // Data kelas dan semester yang tersedia dari data nilai
        $classLevels = $allScores->pluck('subject.class_level')->unique()->sort()->values();
        $semesters = ['ganjil', 'genap']; // statis

        // Kelas terbaru (paling tinggi) default
        $kelasTerbaru = $classLevels->contains('XII') ? 'XII' : ($classLevels->contains('XI') ? 'XI' : 'X');
        $semTerbaru = $allScores->where('subject.class_level', $kelasTerbaru)->pluck('semester')->unique();
        $semesterDefault = $semTerbaru->contains('genap') ? 'genap' : 'ganjil';

        // Ambil filter dari request atau pakai default
        $filterKelas = $request->input('kelas', $kelasTerbaru);
        $filterSemester = $request->input('semester', $semesterDefault);

        // Filter data nilai yang ditampilkan
        $scores = $allScores
            ->where('subject.class_level', $filterKelas)
            ->where('semester', $filterSemester)
            ->values();

        // Hitung nilai akhir per mapel (sesuai bobot)
        foreach ($scores as $score) {
            $score->nilai_akhir = round(
                ($score->attendance * 0.10) +
                    ($score->assignment * 0.20) +
                    ($score->mid_exam * 0.30) +
                    ($score->final_exam * 0.40),
                1
            );
        }

        // Hitung rata-rata nilai akhir untuk tampilan sendiri
        $nilai_akhir_rata2 = $scores->count() > 0 ? round($scores->avg('nilai_akhir'), 2) : 0;

        // ---- LOGIKA RANKING START ----
        // Ambil semua siswa di kelas+semester ini
        $allStudents = \App\Models\Student::whereHas('scores', function ($q) use ($filterKelas, $filterSemester) {
            $q->where('semester', $filterSemester)
                ->whereHas('subject', function ($q2) use ($filterKelas) {
                    $q2->where('class_level', $filterKelas);
                });
        })->with(['scores.subject', 'user'])->get();

        // Hitung rata-rata nilai akhir setiap siswa di kelas+semester tsb
        $rankingData = [];
        foreach ($allStudents as $s) {
            $studentScores = $s->scores
                ->where('semester', $filterSemester)
                ->where('subject.class_level', $filterKelas);

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

        // Urutkan ranking dan cari ranking siswa yang login
        usort($rankingData, fn($a, $b) => $b['avg'] <=> $a['avg']);

        $ranking = '-';
        foreach ($rankingData as $idx => $row) {
            if ($row['student_id'] == $student->id) {
                $ranking = $idx + 1;
                break;
            }
        }
        // ---- LOGIKA RANKING END ----

        // Data filter dropdown
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
