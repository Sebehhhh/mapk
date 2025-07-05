<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('user')
            ->when($request->class, function ($q) use ($request) {
                $q->where('class', $request->class);
            })
            ->when($request->gender, function ($q) use ($request) {
                $q->where('gender', $request->gender);
            })
            ->when($request->q, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'like', '%' . $request->q . '%');
                    })->orWhere('nisn', 'like', '%' . $request->q . '%');
                });
            })
            ->paginate(10)
            ->withQueryString(); // penting biar filter nggak ilang saat pagination

        $users = User::where('role', 'siswa')->get();

        return view('students.index', compact('students', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nisn' => 'required|unique:students,nisn',
            'gender' => 'required|in:L,P',
            'class' => 'required',
            'address' => 'nullable',
            'birth_date' => 'required|date',
            'phone' => 'nullable',
            'place_of_birth' => 'nullable|string',
            'religion' => 'nullable|string',
            'province' => 'nullable|string',
            'district' => 'nullable|string',
            'sub_district' => 'nullable|string',
            'village' => 'nullable|string',
            'origin_school_name' => 'nullable|string',
            'origin_school_address' => 'nullable|string',
            'graduation_year' => 'nullable|digits:4',
        ]);

        $studentData = $request->only([
            'user_id',
            'nisn',
            'gender',
            'class',
            'address',
            'birth_date',
            'phone',
            'place_of_birth',
            'religion',
            'province',
            'district',
            'sub_district',
            'village',
            'origin_school_name',
            'origin_school_address',
            'graduation_year'
        ]);

        Student::create($studentData);
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'gender' => 'required|in:L,P',
            'class' => 'required',
            'address' => 'nullable',
            'birth_date' => 'required|date',
            'phone' => 'nullable',
            'place_of_birth' => 'nullable|string',
            'religion' => 'nullable|string',
            'province' => 'nullable|string',
            'district' => 'nullable|string',
            'sub_district' => 'nullable|string',
            'village' => 'nullable|string',
            'origin_school_name' => 'nullable|string',
            'origin_school_address' => 'nullable|string',
            'graduation_year' => 'nullable|digits:4',
        ]);

        $studentData = $request->only([
            'user_id',
            'nisn',
            'gender',
            'class',
            'address',
            'birth_date',
            'phone',
            'place_of_birth',
            'religion',
            'province',
            'district',
            'sub_district',
            'village',
            'origin_school_name',
            'origin_school_address',
            'graduation_year'
        ]);

        $student->update($studentData);
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
