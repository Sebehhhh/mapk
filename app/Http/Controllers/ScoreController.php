<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student; // Import model Student
use App\Models\Subject; // Import model Subject
use Illuminate\Http\Request;

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
}
