<?php

namespace App\Http\Controllers;

use App\Models\PemantauanLapangan;
use App\Models\Area;
use App\Models\Alat;
use App\Models\AbsensiPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpvPemantauanController extends Controller
{
    public function index(Request $request)
    {
        $query = PemantauanLapangan::where('spv_id', Auth::id())
            ->with(['area', 'alat'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $pemantauans = $query->paginate(10);
        
        $areas = Auth::user()->areas()->pluck('nama', 'areas.id')->toArray();
        
        return view('spv.pemantauan.index', compact('pemantauans', 'areas'));
    }

    public function create()
    {
        $areas = Auth::user()->areas()->pluck('nama', 'areas.id')->toArray();
        $alats = Alat::orderBy('nama')->pluck('nama', 'id')->toArray();
        
        return view('spv.pemantauan.create', compact('areas', 'alats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => ['required', 'exists:areas,id', function ($attribute, $value, $fail) {
                if (!Auth::user()->areas()->where('areas.id', $value)->exists()) {
                    $fail('Area yang dipilih tidak valid atau tidak ditugaskan kepada Anda.');
                }
            }],
            'alat_id' => 'required|exists:alats,id',
            'tanggal' => 'required|date',
            'shift' => 'required|in:siang,malam',
            'deskripsi' => 'required|string',
            'kendala' => 'nullable|string',
            'progress_persen' => 'required|integer|min:0|max:100',
            'progress_status' => 'required|in:belum_mulai,proses,selesai',
            'foto' => 'required|array|min:1|max:10',
            'foto.*' => 'image|max:5120'
        ]);
        
        // Prevent duplicate report for same shift/area
        $exists = PemantauanLapangan::where('spv_id', Auth::id())
            ->where('area_id', $validated['area_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('shift', $validated['shift'])
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Anda sudah membuat laporan untuk Area dan Shift ini pada tanggal yang sama.')->withInput();
        }

        DB::beginTransaction();
        try {
            $pemantauan = PemantauanLapangan::create([
                'spv_id' => Auth::id(),
                'area_id' => $validated['area_id'],
                'alat_id' => $validated['alat_id'],
                'tanggal' => $validated['tanggal'],
                'shift' => $validated['shift'],
                'deskripsi' => $validated['deskripsi'],
                'kendala' => $validated['kendala'],
                'progress_persen' => $validated['progress_persen'],
                'progress_status' => $validated['progress_status'],
            ]);

            // Handle Media Upload via Spatie Media Library
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $pemantauan->addMedia($file)->toMediaCollection('pemantauan_fotos');
                }
            }

            DB::commit();
            return redirect()->route('spv.pemantauan.index')->with('success', 'Laporan pemantauan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PemantauanLapangan $pemantauan)
    {
        if ($pemantauan->spv_id !== Auth::id()) abort(403);
        
        $absensi = AbsensiPegawai::where('area_id', $pemantauan->area_id)
            ->where('tanggal', $pemantauan->tanggal)
            ->where('shift', $pemantauan->shift)
            ->with('pegawai')
            ->get();
            
        return view('spv.pemantauan.show', compact('pemantauan', 'absensi'));
    }
}
