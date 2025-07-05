<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectController extends Controller
{
    public function subject(Request $request)
    {
        $user = Auth::user();

        $query = $user->subjects()->withPivot('year');

        // Filter
        if ($request->filled('year')) {
            $query->wherePivot('year', $request->year);
        }
        if ($request->filled('semester')) {
            $query->where('subjects.semester', $request->semester);
        }
        if ($request->filled('class_level')) {
            $query->where('subjects.class_level', $request->class_level);
        }

        $subjects = $query->paginate(10)->withQueryString();

        // *** Ini penting! ***
        // Pluck harus spesifik dari tabel 'subjects'
        $availableYears = $user->subjects()->distinct()->pluck('subject_user.year');
        $availableSemesters = $user->subjects()->distinct()->pluck('subjects.semester'); // <-- Perbaiki ini!
        $availableClasses = $user->subjects()->distinct()->pluck('subjects.class_level');

        return view('subjects.subject', [
            'subjects' => $subjects,
            'availableYears' => $availableYears,
            'availableSemesters' => $availableSemesters,
            'availableClasses' => $availableClasses,
            'selectedYear' => $request->year,
            'selectedSemester' => $request->semester,
            'selectedClass' => $request->class_level,
        ]);
    }

    public function index(Request $request)
    {
        // Filter opsional
        $selectedYear = $request->year;
        $selectedSemester = $request->semester;
        $selectedClass = $request->class_level;

        // Query data user (siswa)
        $students = \App\Models\User::where('role', 'siswa')->orderBy('name')->get();

        // Query data mapel
        $subjects = \App\Models\Subject::query();
        if ($selectedSemester) $subjects->where('semester', $selectedSemester);
        if ($selectedClass) $subjects->where('class_level', $selectedClass);
        $subjects = $subjects->orderBy('name')->get();

        // Data pivot
        $pivotQuery = \App\Models\SubjectUser::query();
        if ($selectedYear) $pivotQuery->where('year', $selectedYear);
        if ($selectedSemester) $pivotQuery->whereHas('subject', fn($q) => $q->where('semester', $selectedSemester));
        if ($selectedClass) $pivotQuery->whereHas('subject', fn($q) => $q->where('class_level', $selectedClass));
        $subjectUsers = $pivotQuery->with(['user', 'subject'])->get();

        // Dropdown Tahun
        $availableYears = \App\Models\SubjectUser::distinct()->pluck('year')->unique()->sort()->values();
        $availableSemesters = \App\Models\Subject::distinct()->pluck('semester')->unique()->sort()->values();
        $availableClasses = \App\Models\Subject::distinct()->pluck('class_level')->unique()->sort()->values();

        return view('student.subjects.index', [
            'students' => $students,
            'subjects' => $subjects,
            'subjectUsers' => $subjectUsers,
            'availableYears' => $availableYears,
            'availableSemesters' => $availableSemesters,
            'availableClasses' => $availableClasses,
            'selectedYear' => $selectedYear,
            'selectedSemester' => $selectedSemester,
            'selectedClass' => $selectedClass,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'year' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        // Cek mapping sudah ada atau belum
        $exists = \App\Models\SubjectUser::where('user_id', $validated['user_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Mapping mapel ke siswa untuk tahun tersebut sudah ada.');
        }

        // Simpan mapping baru
        \App\Models\SubjectUser::create([
            'user_id'    => $validated['user_id'],
            'subject_id' => $validated['subject_id'],
            'year'       => $validated['year'],
        ]);

        return redirect()->route('subject-users.index')->with('success', 'Mapel berhasil ditambahkan ke siswa.');
    }

    public function destroy($id)
    {
        $subjectUser = \App\Models\SubjectUser::findOrFail($id);
        $subjectUser->delete();

        return redirect()->route('subject-users.index')->with('success', 'Mapping mapel siswa berhasil dihapus.');
    }
}
