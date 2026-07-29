<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPegawai;
use App\Models\Area;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        
        $totalRitasi = $pegawai->ritasis()->count();
        $totalNonRitasi = $pegawai->nonRitasis()->count();
        $totalHm = $pegawai->absensiPegawais()->sum('hm_total');
        
        return view('operator.dashboard', compact('pegawai', 'totalRitasi', 'totalNonRitasi', 'totalHm'));
    }

    public function index()
    {
        $areaOptions = Area::orderBy('nama')->pluck('nama', 'id')->toArray();
        $alatOptions = Alat::orderBy('nama')->pluck('nama', 'id')->toArray();

        return view('pegawai.rekapan.create', compact('areaOptions', 'alatOptions'));
    }

    public function createRekapan()
    {
        $areaOptions = Area::orderBy('nama')->pluck('nama', 'id')->toArray();
        $alatOptions = Alat::orderBy('nama')->pluck('nama', 'id')->toArray();

        return view('pegawai.rekapan.create', compact('areaOptions', 'alatOptions'));
    }

    public function riwayat()
    {
        $pegawaiId = Auth::user()->pegawai_id;

        $rekapans = AbsensiPegawai::with(['area', 'alat'])
            ->where('pegawai_id', $pegawaiId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalHm = AbsensiPegawai::where('pegawai_id', $pegawaiId)
            ->whereNotNull('hm_total')
            ->sum('hm_total');

        return view('pegawai.rekapan.index', compact('rekapans', 'totalHm'));
    }

    public function storeRekapan(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'alat_id' => 'required|exists:alats,id',
            'shift' => 'required|in:siang,malam',
            'tanggal' => 'required|date',
            'tipe_pekerjaan' => 'required|in:unit_non_ritasi,unit_ritasi,pekerjaan_general',
            'hm_awal' => 'required|numeric|min:0',
            'hm_akhir' => 'required|numeric|min:0',
            'deskripsi_pekerjaan' => 'nullable|string',
            'konsumsi_fuel' => 'nullable|numeric|min:0',
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

            return back()->with('error', 'Anda sudah melakukan absensi pada shift dan tanggal tersebut.');
        }

        $validated['pegawai_id'] = $pegawaiId;
        $validated['hm_total'] = $validated['hm_akhir'] - $validated['hm_awal'];

        AbsensiPegawai::create($validated);

        return back()->with('success', 'Rekapan operator berhasil disubmit! Terima kasih.');
    }
}
