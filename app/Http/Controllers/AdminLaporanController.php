<?php

namespace App\Http\Controllers;

use App\Models\Ritasi;
use App\Models\NonRitasi;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Ritasi::with(['pegawai', 'unit', 'area', 'material'])
            ->orderBy('tanggal', 'desc');
        
        $nonRitasiQuery = NonRitasi::with(['pegawai', 'unit', 'area'])
            ->orderBy('tanggal', 'desc');
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
            $nonRitasiQuery->whereDate('tanggal', $request->tanggal);
        }
        
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
            $nonRitasiQuery->where('shift', $request->shift);
        }
        
        $ritasis = $query->paginate(15);
        $nonRitasis = $nonRitasiQuery->paginate(15);
        
        return view('admin.laporan.index', compact('ritasis', 'nonRitasis'));
    }

    public function export(Request $request)
    {
        $query = Ritasi::with(['pegawai', 'unit', 'area', 'material'])
            ->orderBy('tanggal', 'asc');
        
        $nonRitasiQuery = NonRitasi::with(['pegawai', 'unit', 'area'])
            ->orderBy('tanggal', 'asc');
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
            $nonRitasiQuery->whereDate('tanggal', $request->tanggal);
        }
        
        $ritasis = $query->get();
        $nonRitasis = $nonRitasiQuery->get();
        
        $filename = "Laporan_Pemantauan_" . date('Y-m-d_His') . ".xls";
        $sanitized = str_replace(['"', "\r", "\n"], '', $filename);
        
        return response()->view('admin.export.excel', compact('ritasis', 'nonRitasis'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $sanitized . '"');
    }
}