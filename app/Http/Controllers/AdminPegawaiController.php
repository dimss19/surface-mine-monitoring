<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class AdminPegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama')->paginate(10);
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:pegawais',
        ]);

        Pegawai::create($validated);
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:pegawais,nama,' . $pegawai->id,
        ]);

        $pegawai->update($validated);
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->absensiPegawais()->count() > 0) {
            return back()->with('error', 'Gagal menghapus! Pegawai ini sudah memiliki riwayat absensi.');
        }

        $pegawai->delete();
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
