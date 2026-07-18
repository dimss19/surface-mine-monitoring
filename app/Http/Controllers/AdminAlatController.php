<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AdminAlatController extends Controller
{
    public function index()
    {
        $alats = Alat::orderBy('nama')->paginate(10);
        return view('admin.alat.index', compact('alats'));
    }

    public function create()
    {
        return view('admin.alat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:alats',
            'jenis' => 'required|string|max:255',
        ]);

        Alat::create($validated);
        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil ditambahkan.');
    }

    public function edit(Alat $alat)
    {
        return view('admin.alat.edit', compact('alat'));
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:alats,nama,' . $alat->id,
            'jenis' => 'required|string|max:255',
        ]);

        $alat->update($validated);
        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        // Simple check if it has relation
        if ($alat->pemantauanLapangans()->count() > 0) {
            return back()->with('error', 'Gagal menghapus! Alat ini sudah digunakan dalam laporan pemantauan.');
        }
        
        $alat->delete();
        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil dihapus.');
    }
}
