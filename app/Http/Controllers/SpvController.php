<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ritasi;
use App\Models\NonRitasi;
use App\Models\DailyTarget;
use App\Models\Material;
use App\Models\Unit;

class SpvController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $metrics = [
            'total_ritasi' => Ritasi::count(),
            'total_non_ritasi' => NonRitasi::count(),
        ];

        // Target Bar data
        $materials = Material::whereIn('nama', ['Ore', 'Tuff', 'Cake'])->get();
        $targets = [];
        foreach ($materials as $material) {
            $actual = Ritasi::where('material_id', $material->id)
                ->where('tanggal', today())
                ->sum('jumlah_ritasi');
            $target = DailyTarget::where('material_id', $material->id)
                ->where('tanggal', today())
                ->first();
            $targets[] = [
                'material' => $material->nama,
                'actual' => $actual,
                'target' => $target->target_ritasi ?? 0,
                'percentage' => $target && $target->target_ritasi > 0 
                    ? round($actual / $target->target_ritasi * 100) 
                    : 0,
            ];
        }

        // Jam Values data (Daily Breakdown)
        $currentShift = now()->hour >= 6 && now()->hour < 18 ? 'siang' : 'malam';
        $units = Unit::where('is_active', true)->get();
        $unitHours = [];
        foreach ($units as $unit) {
            $ritasi = Ritasi::where('unit_id', $unit->id)
                ->where('tanggal', today())
                ->where('shift', $currentShift)
                ->first();
            $actual = $ritasi ? $ritasi->hm_total : 0;
            $target = 8;
            $unitHours[] = [
                'unit' => $unit->kode,
                'actual' => $actual,
                'remaining' => max(0, $target - $actual),
                'target' => $target,
            ];
        }

        // Info Panel data
        $runningUnits = Ritasi::where('tanggal', today())
            ->distinct('unit_id')
            ->count('unit_id');

        $bdUnits = Unit::where('status', 'breakdown')->count();

        $standbyUnits = Unit::where('is_active', true)
            ->where('status', '!=', 'breakdown')
            ->count() - $runningUnits;

        $totalHours = Ritasi::where('tanggal', today())->sum('hm_total');
        $targetHours = $runningUnits * 8;
        $pencapaian = $targetHours > 0 ? round($totalHours / $targetHours * 100) : 0;

        // Fuel Consumption data
        $totalFuel = Ritasi::where('tanggal', today())->sum('fuel_consumption');
        $totalFuel += NonRitasi::where('tanggal', today())->sum('fuel_consumption');

        return view('spv.dashboard', compact(
            'metrics',
            'targets',
            'unitHours',
            'runningUnits',
            'standbyUnits',
            'bdUnits',
            'totalHours',
            'pencapaian',
            'totalFuel'
        ));
    }

    public function index()
    {
        return $this->dashboard();
    }
}
