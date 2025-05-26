<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule untuk validasi unik

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::orderBy('name', 'asc')->paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     * (Tidak perlu mengembalikan view terpisah karena form create ada di index.blade.php)
     */
    public function create()
    {
        // Method ini biasanya digunakan untuk menampilkan form pembuatan.
        // Karena form create sudah ada di view index.blade.php (modal),
        // Anda mungkin tidak perlu melakukan apa-apa di sini atau bisa mengembalikan redirect.
        return redirect()->route('subjects.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan kombinasi nama, semester, dan level kelas unik
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester)
                                 ->where('class_level', $request->class_level);
                }),
            ],
            'semester' => 'required|string|max:50',
            'class_level' => 'required|string|max:50',
        ], [
            'name.unique' => 'Mata pelajaran dengan nama, semester, dan level kelas ini sudah ada.',
        ]);

        try {
            // Membuat record mata pelajaran baru di database
            Subject::create($validatedData);

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * (Tidak perlu mengembalikan view terpisah untuk show karena tidak ada halaman detail subject)
     */
    public function show(Subject $subject) // Menggunakan Route Model Binding
    {
        // Method ini biasanya digunakan untuk menampilkan detail satu record.
        // Jika Anda tidak memiliki halaman detail terpisah untuk mata pelajaran,
        // Anda mungkin tidak perlu melakukan apa-apa di sini atau bisa mengembalikan redirect.
        return redirect()->route('subjects.index');
    }

    /**
     * Show the form for editing the specified resource.
     * (Tidak perlu mengembalikan view terpisah karena form edit ada di index.blade.php)
     */
    public function edit(Subject $subject) // Menggunakan Route Model Binding
    {
        // Method ini biasanya digunakan untuk menampilkan form edit.
        // Karena form edit sudah ada di view index.blade.php (modal),
        // Anda mungkin tidak perlu melakukan apa-apa di sini atau bisa mengembalikan redirect.
        return redirect()->route('subjects.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject) // Menggunakan Route Model Binding
    {
        // Validasi data yang masuk dari form
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan kombinasi nama, semester, dan level kelas unik, kecuali untuk mata pelajaran ini sendiri
                Rule::unique('subjects')->ignore($subject->id)->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester)
                                 ->where('class_level', $request->class_level);
                }),
            ],
            'semester' => 'required|string|max:50',
            'class_level' => 'required|string|max:50',
        ], [
            'name.unique' => 'Mata pelajaran dengan nama, semester, dan level kelas ini sudah ada.',
        ]);

        try {
            // Mengupdate record mata pelajaran di database
            $subject->update($validatedData);

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject) // Menggunakan Route Model Binding
    {
        try {
            // Menghapus record mata pelajaran dari database
            $subject->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->with('error', 'Gagal menghapus mata pelajaran: ' . $e->getMessage());
        }
    }
}
