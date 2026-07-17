<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPegawai;
use App\Models\Pegawai;
use App\Models\Area;
use App\Models\Alat;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function create()
    {
        $pegawaiOptions = Pegawai::orderBy('nama')->pluck('nama', 'id')->toArray();
        $areaOptions = Area::orderBy('nama')->pluck('nama', 'id')->toArray();
        $alatOptions = Alat::orderBy('nama')->pluck('nama', 'id')->toArray();
        
        return view('absensi.create', compact('pegawaiOptions', 'areaOptions', 'alatOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'area_id' => 'required|exists:areas,id',
            'alat_id' => 'required|exists:alats,id',
            'shift' => 'required|in:siang,malam',
            'tanggal' => 'required|date',
        ]);

        // Validate if already submitted for this date/shift
        $exists = AbsensiPegawai::where('pegawai_id', $validated['pegawai_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('shift', $validated['shift'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pegawai ini sudah melakukan absensi pada shift dan tanggal tersebut.')->withInput();
        }

        AbsensiPegawai::create($validated);

        return redirect()->route('absensi.create')->with('success', 'Absensi berhasil disubmit! Terima kasih.');
    }
}
