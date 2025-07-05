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
    // Untuk form multi input nilai
    $students = Student::with('user')->orderBy('nisn', 'asc')->get();
    $subjects = Subject::orderBy('name', 'asc')->get();
    $mapels = [];

    // Jika request filter siswa & semester (multi input)
    if ($request->filled('student_id') && $request->filled('semester') && !$request->has('filter_table')) {
        $student = Student::find($request->student_id);
        // Ambil mapel dari pivot subject_user (hanya mapel yg diambil siswa di semester itu)
        if ($student && $student->user) {
            $mapels = $student->user
                ->subjects()
                ->where('subjects.semester', $request->semester)
                ->orderBy('name', 'asc')
                ->get();
        }
    }

    // --- FILTERING UNTUK TABLE LIST NILAI ---
    $query = Score::with(['student.user', 'subject']);

    // Cek apakah filter table aktif (pakai tombol submit name/filter_table)
    if ($request->has('filter_table')) {
        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->semester) {
            $query->where('semester', $request->semester);
        }
        if ($request->class_level) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('class_level', $request->class_level);
            });
        }
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
    }

    $scores = $query->orderBy('created_at', 'desc')->paginate(10);

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
            'attendance'   => 'required|integer|min:0|max:100',
            'assignment'   => 'required|integer|min:0|max:100',
            'mid_exam'     => 'required|integer|min:0|max:100',
            'final_exam'   => 'required|integer|min:0|max:100',
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
            // Validasi nilai per mapel
            if (
                !isset($nilai['attendance'], $nilai['assignment'], $nilai['mid_exam'], $nilai['final_exam']) ||
                $nilai['attendance'] < 0 || $nilai['attendance'] > 100 ||
                $nilai['assignment'] < 0 || $nilai['assignment'] > 100 ||
                $nilai['mid_exam'] < 0 || $nilai['mid_exam'] > 100 ||
                $nilai['final_exam'] < 0 || $nilai['final_exam'] > 100
            ) {
                continue; // Skip jika ada nilai tidak valid
            }

            // Cek double entry
            $exists = Score::where('student_id', $student_id)
                ->where('subject_id', $subject_id)
                ->where('semester', $semester)
                ->where('year', $year)
                ->first();
            if ($exists) continue; // Skip jika sudah ada

            Score::create([
                'student_id' => $student_id,
                'subject_id' => $subject_id,
                'semester' => $semester,
                'year' => $year,
                'attendance' => $nilai['attendance'],
                'assignment' => $nilai['assignment'],
                'mid_exam' => $nilai['mid_exam'],
                'final_exam' => $nilai['final_exam'],
            ]);
        }

        return redirect()->route('scores.index')->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Update nilai satuan.
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
            $score->nilai_akhir = round(
                ($score->attendance * 0.10) +
                    ($score->assignment * 0.20) +
                    ($score->mid_exam * 0.30) +
                    ($score->final_exam * 0.40),
                1
            );
        }

        $nilai_akhir_rata2 = $scores->count() > 0 ? round($scores->avg('nilai_akhir'), 2) : 0;

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
