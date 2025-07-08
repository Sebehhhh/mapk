<?php

namespace App\Http\Controllers;

use App\Models\ExamCard;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamCardController extends Controller
{
    public function downloadPdf()
    {
        $user = auth()->user();
        if ($user->role == 'admin') {
            $examCards = ExamCard::with(['student.user'])->get();
        } else {
            $examCards = ExamCard::with(['student.user'])
                ->where('student_id', $user->student->id)
                ->get();
        }

        // Tampilkan PDF di browser (tidak auto-download)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exam-cards.pdf', compact('examCards'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('kartu_ujian_' . date('Y-m-d') . '.pdf');
    }

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

    public function show($id)
    {
        $student = Auth::user()->student;

        // Cegah akses jika user mencoba akses data siswa lain
        if (!$student || $student->id != $id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $examCards = ExamCard::where('student_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('exam-cards.show', compact('student', 'examCards'));
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
