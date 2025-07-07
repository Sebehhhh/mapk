<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    // Display a listing of the heroes.
    public function index()
    {
        $heroes = Hero::orderByDesc('created_at')->paginate(10);
        return view('heroes.index', compact('heroes'));
    }

    // Store a newly created hero in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('heroes', 'public');
        }

        Hero::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'],
            'photo' => $photoPath,
        ]);

        return redirect()->route('heroes.index')->with('success', 'Hero berhasil ditambahkan.');
    }

    // Update the specified hero in storage.
    public function update(Request $request, $id)
    {
        $hero = Hero::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $hero->title = $validated['title'];
        $hero->description = $validated['description'];
        $hero->is_active = $validated['is_active'];

        // Photo logic
        if ($request->hasFile('photo')) {
            if ($hero->photo && Storage::disk('public')->exists($hero->photo)) {
                Storage::disk('public')->delete($hero->photo);
            }
            $hero->photo = $request->file('photo')->store('heroes', 'public');
        }

        $hero->save();

        return redirect()->route('heroes.index')->with('success', 'Hero berhasil diperbarui.');
    }

    // Remove the specified hero from storage.
    public function destroy($id)
    {
        $hero = Hero::findOrFail($id);

        if ($hero->photo && Storage::disk('public')->exists($hero->photo)) {
            Storage::disk('public')->delete($hero->photo);
        }

        $hero->delete();

        return redirect()->route('heroes.index')->with('success', 'Hero berhasil dihapus.');
    }
}
