<?php

namespace App\Http\Controllers;

use App\Models\Ritasi;
use App\Models\Unit;
use Illuminate\Http\Request;

class SpvUtilizationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 12;
        $statusFilter = $request->get('status', 'all');
        
        $query = Unit::with('areas');
        
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        $units = $query->orderBy('nama')->get();

        $utilization = $units->map(function ($unit) {
            $todayRitasi = Ritasi::where('unit_id', $unit->id)
                ->where('tanggal', today())
                ->sum('hm_total');

            $target = 8;
            return [
                'unit' => $unit,
                'status' => $unit->status,
                'hours_today' => $todayRitasi,
                'target' => $target,
                'utilization_pct' => min(100, round($todayRitasi / $target * 100)),
                'last_update' => Ritasi::where('unit_id', $unit->id)
                    ->latest('updated_at')
                    ->value('updated_at'),
            ];
        });

        $paginated = $utilization->chunk($perPage);
        $currentPage = $request->get('page', 1);
        $currentPageItems = $paginated[$currentPage - 1] ?? collect();
        $totalPages = $paginated->count();

        return view('spv.utilization', compact('utilization', 'currentPageItems', 'currentPage', 'totalPages', 'statusFilter'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,maintenance,breakdown,standby',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $unit->update([
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('spv.utilization.index')
            ->with('success', "Status {$unit->nama} berhasil diperbarui.");
    }
}
