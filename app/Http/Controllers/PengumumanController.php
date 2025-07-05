<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    // Display a listing of the announcements.
    public function index()
    {
        $pengumuman = Pengumuman::orderByDesc('tanggal')->paginate(10);
        return view('pengumuman.index', compact('pengumuman'));
    }

    // Store a newly created announcement in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:100',
            'isi'     => 'required|string',
            'tanggal' => 'required|date',
        ]);

        Pengumuman::create($validated);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    // Update the specified announcement in storage.
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul'   => 'required|string|max:100',
            'isi'     => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $pengumuman->update($validated);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // Remove the specified announcement from storage.
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}