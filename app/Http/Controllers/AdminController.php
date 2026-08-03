<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\NonRitasi;
use App\Models\Ritasi;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::today());
        $dateTo = $request->input('date_to', Carbon::today());
        $period = $request->input('period', 'daily');

        $areas = Area::all();

        $ritasiData = Ritasi::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->select('area_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('area_id')
            ->pluck('total', 'area_id');

        $nonRitasiData = NonRitasi::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->select('area_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('area_id')
            ->pluck('total', 'area_id');

        $activeUnits = Ritasi::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->select('area_id', \DB::raw('COUNT(DISTINCT unit_id) as total'))
            ->groupBy('area_id')
            ->pluck('total', 'area_id');

        $unitStatuses = Ritasi::whereBetween('tanggal', [$dateFrom, $dateTo])
            ->join('units', 'ritasis.unit_id', '=', 'units.id')
            ->select('units.area_id', 'units.status')
            ->get()
            ->groupBy('area_id')
            ->map(fn ($items) => $items->pluck('status')->first());

        $dashboardData = $areas->map(function ($area) use ($ritasiData, $nonRitasiData, $activeUnits, $unitStatuses) {
            return [
                'area' => $area,
                'total_ritasi' => $ritasiData->get($area->id, 0),
                'total_non_ritasi' => $nonRitasiData->get($area->id, 0),
                'active_units' => $activeUnits->get($area->id, 0),
                'unit_status' => $unitStatuses->get($area->id, 'Tidak Ada Data'),
            ];
        });

        $summary = [
            'total_ritasi' => $ritasiData->sum(),
            'total_non_ritasi' => $nonRitasiData->sum(),
            'active_units' => $activeUnits->sum(),
            'total_areas' => $areas->count(),
        ];

        return view('dashboard.index', compact('dashboardData', 'summary', 'dateFrom', 'dateTo', 'period'));
    }
}