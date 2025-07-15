<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extracurricular;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    // List semua ekstrakurikuler (tampil di tabel)
    public function index()
    {
        $extracurriculars = Extracurricular::orderByDesc('created_at')->paginate(10);
        return view('extracurriculars.index', compact('extracurriculars'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'is_active'   => 'required|boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('extracurriculars', 'public');
        }

        Extracurricular::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'photo'       => $photoPath,
            // 'is_active'   => $validated['is_active'],
        ]);

        return redirect()->route('extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    // Update data
    public function update(Request $request, $id)
    {
        $ekstra = Extracurricular::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'is_active'   => 'required|boolean',
        ]);

        // Foto baru?
        if ($request->hasFile('photo')) {
            if ($ekstra->photo && Storage::disk('public')->exists($ekstra->photo)) {
                Storage::disk('public')->delete($ekstra->photo);
            }
            $ekstra->photo = $request->file('photo')->store('extracurriculars', 'public');
        }

        $ekstra->name        = $validated['name'];
        $ekstra->description = $validated['description'];
        // $ekstra->is_active   = $validated['is_active'];
        $ekstra->save();

        return redirect()->route('extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil diupdate.');
    }

    // Hapus data
    public function destroy($id)
    {
        $ekstra = Extracurricular::findOrFail($id);
        if ($ekstra->photo && Storage::disk('public')->exists($ekstra->photo)) {
            Storage::disk('public')->delete($ekstra->photo);
        }
        $ekstra->delete();
        return redirect()->route('extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}