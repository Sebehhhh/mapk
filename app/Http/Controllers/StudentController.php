<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index() {
        $students = Student::with('user')->paginate(10);
        $users = User::where('role', 'siswa')->get();
        return view('students.index', compact('students', 'users'));
    }
    
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nisn' => 'required|unique:students,nisn',
            'gender' => 'required|in:L,P',
            'class' => 'required',
            'address' => 'nullable',
            'birth_date' => 'required|date',
            'phone' => 'nullable',
        ]);
    
        Student::create($request->all());
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }
    
    public function update(Request $request, Student $student) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'gender' => 'required|in:L,P',
            'class' => 'required',
            'address' => 'nullable',
            'birth_date' => 'required|date',
            'phone' => 'nullable',
        ]);
    
        $student->update($request->all());
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }
    
    public function destroy(Student $student) {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
