<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPegawai;
use App\Models\Area;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function index()
    {
        return view('pegawai.index');
    }

    public function createAbsensi()
    {
        $areaOptions = Area::orderBy('nama')->pluck('nama', 'id')->toArray();
        $alatOptions = Alat::orderBy('nama')->pluck('nama', 'id')->toArray();

        return view('pegawai.absensi.create', compact('areaOptions', 'alatOptions'));
    }

    public function storeAbsensi(Request $request)
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
        ]);

        $pegawaiId = Auth::user()->pegawai_id;

        if ($validated['hm_akhir'] < $validated['hm_awal']) {
            return $this->failure($request, 'HM Akhir harus lebih besar atau sama dengan HM Awal.');
        }

        $exists = AbsensiPegawai::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $validated['tanggal'])
            ->where('shift', $validated['shift'])
            ->exists();

        if ($exists) {
            if ($request->header('X-Offline-Replay') === '1') {
                return response()->json(['success' => true, 'replayed' => true], 200);
            }

            return $this->failure($request, 'Anda sudah melakukan absensi pada shift dan tanggal tersebut.');
        }

        $validated['pegawai_id'] = $pegawaiId;
        $validated['hm_total'] = $validated['hm_akhir'] - $validated['hm_awal'];

        AbsensiPegawai::create($validated);

        return $this->success($request);
    }

    private function wantsJson(Request $request): bool
    {
        return $request->acceptsJson()
            || $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    private function success(Request $request)
    {
        if ($this->wantsJson($request)) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('pegawai.absensi.create')->with('success', 'Absensi berhasil disubmit! Terima kasih.');
    }

    private function failure(Request $request, string $message)
    {
        if ($this->wantsJson($request)) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        return back()->with('error', $message)->withInput();
    }
}
