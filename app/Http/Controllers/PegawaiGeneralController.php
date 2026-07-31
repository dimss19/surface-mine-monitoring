<?php

namespace App\Http\Controllers;

use App\Models\NonRitasi;
use App\Models\Area;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiGeneralController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        
        $units = Unit::orderBy('nama')->pluck('nama', 'id')->toArray();
        $areas = Area::orderBy('nama')->pluck('nama', 'id')->toArray();

        return view('operator.general.create', compact('pegawai', 'units', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'unit_id' => 'required|exists:units,id',
            'shift' => 'required|in:siang,malam',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi_pekerjaan' => 'nullable|string',
            'deskripsi_pekerjaan' => 'nullable|string',
            'is_overtime' => 'nullable|boolean',
        ]);

        $pegawaiId = Auth::user()->pegawai_id;

        $validated['pegawai_id'] = $pegawaiId;
        $validated['status'] = 'pending';

        NonRitasi::create($validated);

        return back()->with('success', 'Data pekerjaan general berhasil disimpan!');
    }
}
