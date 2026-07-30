<?php

namespace App\Http\Controllers;

use App\Models\Ritasi;
use App\Models\Unit;

class SpvUtilizationController extends Controller
{
    public function index()
    {
        $units = Unit::with('area')->get();

        $utilization = [];
        foreach ($units as $unit) {
            $todayRitasi = Ritasi::where('unit_id', $unit->id)
                ->where('tanggal', today())
                ->sum('hm_total');

            $target = 8;
            $utilization[] = [
                'unit' => $unit,
                'status' => $unit->status,
                'hours_today' => $todayRitasi,
                'target' => $target,
                'utilization_pct' => min(100, round($todayRitasi / $target * 100)),
                'last_update' => Ritasi::where('unit_id', $unit->id)
                    ->latest('updated_at')
                    ->value('updated_at'),
            ];
        }

        return view('spv.utilization', compact('utilization'));
    }
}
