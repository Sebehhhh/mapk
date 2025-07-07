<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    // Tampil halaman utama (tabel + modal)
    public function index()
    {
        $about = About::first();
        return view('abouts.index', compact('about'));
    }

    // Simpan data baru (handle form modal)
    public function store(Request $request)
    {
        if (About::count() > 0) {
            return redirect()->route('abouts.index')->with('error', 'Profil sudah ada. Tidak boleh lebih dari satu.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'kepala_sekolah' => 'nullable|string|max:255',
            'nip_kepsek' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|string|max:8',
            'akreditasi' => 'nullable|string|max:10',
            'jumlah_siswa' => 'nullable|integer',
            'jumlah_guru' => 'nullable|integer',
            'fasilitas' => 'nullable|string',
            'struktur_organisasi' => 'nullable|string|max:255',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('abouts', 'public');
        }

        About::create(array_merge($validated, ['photo' => $photoPath]));

        return redirect()->route('abouts.index')->with('success', 'Profil sekolah berhasil dibuat.');
    }

    // Update profil (handle form modal)
    public function update(Request $request, $id)
    {
        $about = About::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'kepala_sekolah' => 'nullable|string|max:255',
            'nip_kepsek' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|string|max:8',
            'akreditasi' => 'nullable|string|max:10',
            'jumlah_siswa' => 'nullable|integer',
            'jumlah_guru' => 'nullable|integer',
            'fasilitas' => 'nullable|string',
            'struktur_organisasi' => 'nullable|string|max:255',
        ]);

        // Handle update foto/logo
        if ($request->hasFile('photo')) {
            if ($about->photo && Storage::disk('public')->exists($about->photo)) {
                Storage::disk('public')->delete($about->photo);
            }
            $about->photo = $request->file('photo')->store('abouts', 'public');
        }

        $about->fill($validated);
        $about->save();

        return redirect()->route('abouts.index')->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    // Hapus profil (opsional, biasanya tidak dipakai)
    public function destroy($id)
    {
        $about = About::findOrFail($id);
        if ($about->photo && Storage::disk('public')->exists($about->photo)) {
            Storage::disk('public')->delete($about->photo);
        }
        $about->delete();
        return redirect()->route('abouts.index')->with('success', 'Profil sekolah berhasil dihapus.');
    }
}