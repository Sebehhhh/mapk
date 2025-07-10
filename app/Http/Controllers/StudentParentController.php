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
                    $user->where('name', 'like', "%{$q}%");
                })
                ->orWhere('father_name', 'like', "%{$q}%")
                ->orWhere('mother_name', 'like', "%{$q}%");
        }

        $parents = $query->orderByDesc('id')
                         ->paginate(10)
                         ->withQueryString();

        return view('student_parents.index', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:students,id',
            'father_name'  => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',
            'father_job'   => 'nullable|string|max:100',
            'mother_name'  => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',
            'mother_job'   => 'nullable|string|max:100',
        ]);

        StudentParent::create($validated);

        return redirect()
            ->route('student-parents.index')
            ->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $parent = StudentParent::findOrFail($id);

        $validated = $request->validate([
            'father_name'  => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',
            'father_job'   => 'nullable|string|max:100',
            'mother_name'  => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',
            'mother_job'   => 'nullable|string|max:100',
        ]);

        $parent->update($validated);

        return redirect()
            ->route('student-parents.index')
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $parent = StudentParent::findOrFail($id);
        $parent->delete();

        return redirect()
            ->route('student-parents.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }
}