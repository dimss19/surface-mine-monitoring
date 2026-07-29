<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPegawai;
use App\Models\Area;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiGeneralController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        
        $areaOptions = Area::orderBy('nama')->pluck('nama', 'id')->toArray();
        $alatOptions = Alat::orderBy('nama')->pluck('nama', 'id')->toArray();

        return view('operator.general.create', compact('pegawai', 'areaOptions', 'alatOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'alat_id' => 'required|exists:alats,id',
            'shift' => 'required|in:siang,malam',
            'tanggal' => 'required|date',
            'hm_awal' => 'required|numeric|min:0',
            'hm_akhir' => 'required|numeric|min:0',
            'deskripsi_pekerjaan' => 'nullable|string',
        ]);

        $pegawaiId = Auth::user()->pegawai_id;

        if ($validated['hm_akhir'] < $validated['hm_awal']) {
            return back()->with('error', 'HM Akhir harus lebih besar atau sama dengan HM Awal.');
        }

        $exists = AbsensiPegawai::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $validated['tanggal'])
            ->where('shift', $validated['shift'])
            ->exists();

        if ($exists) {
            if ($request->header('X-Offline-Replay') === '1') {
                return response()->json(['success' => true, 'replayed' => true], 200);
            }
            return back()->with('error', 'Anda sudah melakukan input pekerjaan pada shift dan tanggal tersebut.');
        }

        $validated['pegawai_id'] = $pegawaiId;
        $validated['tipe_pekerjaan'] = 'pekerjaan_general';
        $validated['hm_total'] = $validated['hm_akhir'] - $validated['hm_awal'];

        AbsensiPegawai::create($validated);

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Data pekerjaan general berhasil disimpan!');
    }
}