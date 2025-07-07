<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentParent;

class StudentParentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentParent::with('student.user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('student.user', function ($user) use ($q) {
                $user->where('name', 'like', "%$q%");
            })
                ->orWhere('father_name', 'like', "%$q%")
                ->orWhere('mother_name', 'like', "%$q%");
        }

        $parents = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('student_parents.index', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'father_name' => 'nullable|string',
            'father_phone' => 'nullable|string',
            'father_job' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_phone' => 'nullable|string',
            'mother_job' => 'nullable|string',
        ]);

        StudentParent::create($request->all());

        return redirect()->route('student-parents.index')->with('success', 'Data orang tua berhasil ditambahkan.');
    }
}
