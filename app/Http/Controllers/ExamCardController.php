<?php

namespace App\Http\Controllers;

use App\Models\ExamCard;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamCardController extends Controller
{
    public function index()
    {
        $examCards = ExamCard::with('student.user')->orderBy('created_at', 'desc')->paginate(10);
        $students = Student::with('user')->get();

        return view('exam-cards.index', compact('examCards', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_type' => 'required|string|max:20',   // e.g., UTS, UAS
            'semester'   => 'required|string|max:10',  // e.g., Ganjil, Genap
            'year'       => 'required|digits:4',
            'status'     => 'required|in:0,1',          // integer: 0 atau 1
        ]);

        // Pastikan status disimpan sebagai integer
        $data = $request->only(['student_id', 'exam_type', 'semester', 'year']);
        $data['status'] = (int) $request->input('status');

        ExamCard::create($data);

        return redirect()->route('exam-cards.index')->with('success', 'Kartu ujian berhasil ditambahkan.');
    }

    public function update(Request $request, ExamCard $examCard)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_type' => 'required|string|max:20',
            'semester'   => 'required|string|max:10',
            'year'       => 'required|digits:4',
            'status'     => 'required|in:0,1',
        ]);

        $data = $request->only(['student_id', 'exam_type', 'semester', 'year']);
        $data['status'] = (int) $request->input('status');

        $examCard->update($data);

        return redirect()->route('exam-cards.index')->with('success', 'Kartu ujian berhasil diperbarui.');
    }

    public function destroy(ExamCard $examCard)
    {
        $examCard->delete();

        return redirect()->route('exam-cards.index')->with('success', 'Kartu ujian berhasil dihapus.');
    }
}