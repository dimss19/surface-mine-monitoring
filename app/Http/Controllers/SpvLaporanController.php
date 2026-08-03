<?php

namespace App\Http\Controllers;

use App\Models\Ritasi;
use App\Models\NonRitasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SpvLaporanController extends Controller
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
        
        return view('spv.laporan.index', compact('ritasis', 'nonRitasis'));
    }

    public function harian(Request $request)
    {
        $date = $request->get('tanggal', today()->toDateString());
        
        $ritasis = Ritasi::with(['pegawai', 'unit', 'area', 'material'])
            ->whereDate('tanggal', $date)
            ->orderBy('shift')
            ->orderBy('pegawai_id')
            ->get();
        
        $nonRitasis = NonRitasi::with(['pegawai', 'unit', 'area'])
            ->whereDate('tanggal', $date)
            ->orderBy('shift')
            ->orderBy('pegawai_id')
            ->get();
        
        return view('spv.laporan.harian', compact('ritasis', 'nonRitasis', 'date'));
    }

    public function mingguan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfWeek()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfWeek()->toDateString());
        
        $ritasis = Ritasi::with(['pegawai', 'unit', 'area', 'material'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();
        
        $nonRitasis = NonRitasi::with(['pegawai', 'unit', 'area'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();
        
        return view('spv.laporan.mingguan', compact('ritasis', 'nonRitasis', 'startDate', 'endDate'));
    }

    public function bulanan(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $ritasis = Ritasi::with(['pegawai', 'unit', 'area', 'material'])
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal')
            ->get();
        
        $nonRitasis = NonRitasi::with(['pegawai', 'unit', 'area'])
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal')
            ->get();
        
        return view('spv.laporan.bulanan', compact('ritasis', 'nonRitasis', 'month', 'year'));
    }

    public function export(Request $request, string $type)
    {
        $user = auth()->user();
        $areaIds = $user->areas->pluck('id')->toArray();
        
        $query = Ritasi::with(['pegawai', 'unit', 'area', 'material'])
            ->whereIn('area_id', $areaIds)
            ->orderBy('tanggal', 'asc');
        
        $nonRitasiQuery = NonRitasi::with(['pegawai', 'unit', 'area'])
            ->whereIn('area_id', $areaIds)
            ->orderBy('tanggal', 'asc');
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
            $nonRitasiQuery->whereDate('tanggal', $request->tanggal);
        }
        
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
            $nonRitasiQuery->where('shift', $request->shift);
        }
        
        $ritasis = $query->get();
        $nonRitasis = $nonRitasiQuery->get();
        
        $filename = "Laporan_SPV_" . $type . "_" . date('Y-m-d_His') . ".xls";
        $sanitized = str_replace(['"', "\r", "\n"], '', $filename);
        
        return response()->view('spv.export.excel', compact('ritasis', 'nonRitasis'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $sanitized . '"');
    }
}