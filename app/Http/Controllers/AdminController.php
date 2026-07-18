<?php

namespace App\Http\Controllers;

use App\Models\PemantauanLapangan;
use App\Models\User;
use App\Models\Area;
use App\Models\Alat;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $metrics = [
            'total_spv' => User::where('role', 'spv')->count(),
            'total_area' => Area::count(),
            'laporan_hari_ini' => PemantauanLapangan::whereDate('tanggal', today())->count(),
        ];
        
        $query = PemantauanLapangan::with(['spv', 'area', 'alat'])->orderBy('created_at', 'desc');
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('spv_id')) {
            $query->where('spv_id', $request->spv_id);
        }
        
        $pemantauans = $query->paginate(10);
        $spvs = User::where('role', 'spv')->pluck('name', 'id')->toArray();
        
        $spvList = User::where('role', 'spv')
            ->withCount('areas')
            ->with(['pemantauanLapangans' => function($q) {
                $q->latest()->limit(1);
            }])
            ->get();

        return view('admin.dashboard', compact('metrics', 'pemantauans', 'spvs', 'spvList'));
    }

    public function export(Request $request)
    {
        $query = PemantauanLapangan::with(['spv', 'area', 'alat', 'media'])->orderBy('tanggal', 'asc');
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('spv_id')) {
            $query->where('spv_id', $request->spv_id);
        }
        
        $pemantauans = $query->get();

        $filename = "Laporan_Pemantauan_" . date('Y-m-d_His') . ".xls";
        
        return response()->view('admin.export.excel', compact('pemantauans'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
