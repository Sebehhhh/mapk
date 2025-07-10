<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    // Tampil halaman utama
    public function index()
    {
        $about = About::first();
        return view('abouts.index', compact('about'));
    }

    // Simpan profil baru (maksimal satu)
    public function store(Request $request)
    {
        if (About::exists()) {
            return redirect()
                ->route('abouts.index')
                ->with('error', 'Profil sudah ada. Tidak boleh lebih dari satu.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'     => 'nullable|string|max:255',
            'instagram'   => 'nullable|string|max:255',
            'akreditasi'  => 'nullable|string|max:10',
            'email'       => 'nullable|email|max:255',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('abouts', 'public');
        }

        About::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'photo'       => $photoPath,
            'address'     => $validated['address']   ?? null,
            'instagram'   => $validated['instagram'] ?? null,
            'akreditasi'  => $validated['akreditasi'] ?? null,
            'email'       => $validated['email']     ?? null,
        ]);

        return redirect()
            ->route('abouts.index')
            ->with('success', 'Profil sekolah berhasil dibuat.');
    }

    // Perbarui profil
    public function update(Request $request, $id)
    {
        $about = About::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'     => 'nullable|string|max:255',
            'instagram'   => 'nullable|string|max:255',
            'akreditasi'  => 'nullable|string|max:10',
            'email'       => 'nullable|email|max:255',
        ]);

        if ($request->hasFile('photo')) {
            if ($about->photo && Storage::disk('public')->exists($about->photo)) {
                Storage::disk('public')->delete($about->photo);
            }
            $about->photo = $request->file('photo')->store('abouts', 'public');
        }

        $about->fill([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'address'     => $validated['address']   ?? null,
            'instagram'   => $validated['instagram'] ?? null,
            'akreditasi'  => $validated['akreditasi'] ?? null,
            'email'       => $validated['email']     ?? null,
        ]);
        $about->save();

        return redirect()
            ->route('abouts.index')
            ->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    // Hapus profil (opsional)
    public function destroy($id)
    {
        $about = About::findOrFail($id);

        if ($about->photo && Storage::disk('public')->exists($about->photo)) {
            Storage::disk('public')->delete($about->photo);
        }

        $about->delete();

        return redirect()
            ->route('abouts.index')
            ->with('success', 'Profil sekolah berhasil dihapus.');
    }
}
