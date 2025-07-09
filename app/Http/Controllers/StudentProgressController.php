<?php

namespace App\Http\Controllers;

use App\Models\StudentProgress;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentProgressController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentProgress::with(['student.user']);

        // FILTER & SEARCH
        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('q')) {
            $query->whereHas('student.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        $progresses = $query->orderByDesc('year')
            ->orderByRaw("FIELD(class_level, 'XII','XI','X')")
            ->orderByRaw("FIELD(semester, 'genap','ganjil')")
            ->paginate(10);

        // Untuk dropdown siswa di modal tambah
        $students = Student::with('user')->orderBy('id', 'desc')->get();

        return view('student_progresses.index', compact('progresses', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'class_level'  => 'required|in:X,XI,XII',
            'semester'     => 'required|in:ganjil,genap',
            'year'         => 'required|string|max:9',
            'status'       => 'required|in:aktif,lulus,naik,tinggal',
        ]);

        StudentProgress::create($request->all());

        return redirect()->route('student-progresses.index')->with('success', 'Progress siswa berhasil ditambahkan.');
    }

    public function update(Request $request, StudentProgress $studentProgress)
    {
        $request->validate([
            'class_level'  => 'required|in:X,XI,XII',
            'semester'     => 'required|in:ganjil,genap',
            'year'         => 'required|string|max:9',
            'status'       => 'required|in:aktif,lulus,naik,tinggal',
        ]);

        $studentProgress->update($request->all());

        return redirect()->route('student-progresses.index')->with('success', 'Progress siswa berhasil diupdate.');
    }

    public function destroy(StudentProgress $studentProgress)
    {
        $studentProgress->delete();
        return redirect()->route('student-progresses.index')->with('success', 'Progress siswa berhasil dihapus.');
    }
}