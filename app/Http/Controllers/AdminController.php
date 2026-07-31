<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use App\Models\Material;
use App\Models\Area;
use App\Models\Ritasi;
use App\Models\NonRitasi;
use App\Models\DailyTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $period = $request->get('period', 'daily');
        
        // Main materials (Ore, Tuff, Cake) - always 3
        $mainMaterials = ['Ore', 'Tuff', 'Cake'];
        
        // ===== DAILY DATA =====
        $dailyData = $this->buildDailyData($mainMaterials);
        
        // ===== WEEKLY DATA =====
        $weeklyData = $this->buildWeeklyData($mainMaterials);
        
        // ===== MONTHLY DATA =====
        $monthlyData = $this->buildMonthlyData();
        
        // ===== SHIFT SEGMENTS (for gantt chart) =====
        $shiftSegments = $this->buildShiftSegments();
        
        // ===== ALL MATERIALS FOR HORIZONTAL BARS =====
        $allMaterialsHbar = $this->buildAllMaterialsHbar();
        
        // ===== AVAILABILITY & UoA PER EQUIPMENT TYPE =====
        $availabilityUoA = $this->buildAvailabilityUoA();
        
        // ===== STAT STRIP DATA =====
        $statStrip = $this->buildStatStrip($shiftSegments, $period);
        
        $dashboardData = [
            'period' => $period,
            'daily' => $dailyData,
            'weekly' => $weeklyData,
            'monthly' => $monthlyData,
            'shift_segments' => $shiftSegments,
            'all_materials_hbar' => $allMaterialsHbar,
            'availability' => $availabilityUoA['availability'],
            'uoa' => $availabilityUoA['uoa'],
            'stat_strip' => $statStrip,
        ];

        // Legacy metrics for backward compatibility
        $metrics = [
            'total_ritasi' => Ritasi::count(),
            'unit_aktif' => Unit::where('status', 'active')->count() . ' / ' . Unit::count(),
            'jam_kerja' => Ritasi::sum('hm_total') . 'h',
            'general_tasks' => NonRitasi::count() . ' Tasks',
            'daily_hauling' => array_sum($dailyData['materials']->pluck('value')->toArray()),
            'total_hours' => $statStrip['total_running_hours'],
            'pencapaian_jam' => $statStrip['achievement_pct'],
            'total_bd' => $statStrip['total_bd_hours'],
            'total_standby' => $statStrip['total_standby_hours'],
        ];

        return view('admin.dashboard', compact('dashboardData', 'metrics'));
    }

    private function buildDailyData($mainMaterials)
    {
        $materials = [];
        $totalHauling = 0;
        
        foreach ($mainMaterials as $materialName) {
            $material = Material::where('nama', $materialName)->first();
            if (!$material) {
                $materials[] = [
                    'name' => $materialName,
                    'value' => 0,
                    'target' => 0,
                    'main' => true,
                ];
                continue;
            }
            
            $actual = Ritasi::where('material_id', $material->id)
                ->where('tanggal', today())
                ->sum('jumlah_ritasi');
            $target = DailyTarget::where('material_id', $material->id)
                ->where('tanggal', today())
                ->first();
            $targetVal = $target->target_ritasi ?? 0;
            
            $materials[] = [
                'name' => $materialName,
                'value' => (int) $actual,
                'target' => (int) $targetVal,
                'main' => true,
            ];
            $totalHauling += $actual;
        }
        
        // Add other materials
        $otherMaterials = Material::whereNotIn('nama', $mainMaterials)->get();
        foreach ($otherMaterials as $material) {
            $actual = Ritasi::where('material_id', $material->id)
                ->where('tanggal', today())
                ->sum('jumlah_ritasi');
            $target = DailyTarget::where('material_id', $material->id)
                ->where('tanggal', today())
                ->first();
            $targetVal = $target->target_ritasi ?? 0;
            
            if ($actual > 0 || $targetVal > 0) {
                $materials[] = [
                    'name' => $material->nama,
                    'value' => (int) $actual,
                    'target' => (int) $targetVal,
                    'main' => false,
                ];
                $totalHauling += $actual;
            }
        }
        
        return [
            'label' => 'Daily',
            'materials' => collect($materials),
            'haulingLabel' => 'Daily All Hauling',
            'haulingTotal' => $totalHauling,
        ];
    }
    
    private function buildWeeklyData($mainMaterials)
    {
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();
        
        $materials = [];
        $totalHauling = 0;
        
        foreach ($mainMaterials as $materialName) {
            $material = Material::where('nama', $materialName)->first();
            if (!$material) {
                $materials[] = [
                    'name' => $materialName,
                    'value' => 0,
                    'target' => 0,
                    'main' => true,
                ];
                continue;
            }
            
            $actual = Ritasi::where('material_id', $material->id)
                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                ->sum('jumlah_ritasi');
            $target = DailyTarget::where('material_id', $material->id)
                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                ->sum('target_ritasi');
            
            $materials[] = [
                'name' => $materialName,
                'value' => (int) $actual,
                'target' => (int) $target,
                'main' => true,
            ];
            $totalHauling += $actual;
        }
        
        $otherMaterials = Material::whereNotIn('nama', $mainMaterials)->get();
        foreach ($otherMaterials as $material) {
            $actual = Ritasi::where('material_id', $material->id)
                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                ->sum('jumlah_ritasi');
            $target = DailyTarget::where('material_id', $material->id)
                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                ->sum('target_ritasi');
            
            if ($actual > 0 || $target > 0) {
                $materials[] = [
                    'name' => $material->nama,
                    'value' => (int) $actual,
                    'target' => (int) $target,
                    'main' => false,
                ];
                $totalHauling += $actual;
            }
        }
        
        return [
            'label' => 'Weekly',
            'materials' => collect($materials),
            'haulingLabel' => 'Weekly All Hauling (WTD)',
            'haulingTotal' => $totalHauling,
        ];
    }
    
    private function buildMonthlyData()
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();
        $daysInMonth = now()->daysInMonth;
        $currentDay = now()->day;
        
        $materials = [];
        $dailyBreakdown = [];
        $cumulative = 0;
        $totalHauling = 0;
        
        // Get all materials that have data this month
        $materialsWithData = Material::whereHas('ritasis', function($q) use ($monthStart, $monthEnd) {
            $q->whereBetween('tanggal', [$monthStart, $monthEnd]);
        })->get();
        
        // For each day, calculate totals per material
        $mainMaterials = ['Ore', 'Tuff', 'Cake'];
        $oreMaterials = ['Ore'];
        
        for ($day = 1; $day <= $currentDay; $day++) {
            $date = now()->startOfMonth()->addDays($day - 1)->toDateString();
            $dayOre = 0;
            $dayOthers = 0;
            
            foreach ($materialsWithData as $material) {
                $actual = Ritasi::where('material_id', $material->id)
                    ->where('tanggal', $date)
                    ->sum('jumlah_ritasi');
                
                if (in_array($material->nama, $oreMaterials)) {
                    $dayOre += $actual;
                } else {
                    $dayOthers += $actual;
                }
            }
            
            $dailyBreakdown[] = [
                'day' => $day,
                'ore' => (int) $dayOre,
                'others' => (int) $dayOthers,
            ];
            $cumulative += $dayOre + $dayOthers;
        }
        
        // Monthly totals per material
        foreach ($materialsWithData as $material) {
            $actual = Ritasi::where('material_id', $material->id)
                ->whereBetween('tanggal', [$monthStart, $monthEnd])
                ->sum('jumlah_ritasi');
            $target = DailyTarget::where('material_id', $material->id)
                ->whereBetween('tanggal', [$monthStart, $monthEnd])
                ->sum('target_ritasi');
            
            $isMain = in_array($material->nama, $mainMaterials);
            $materials[] = [
                'name' => $material->nama,
                'value' => (int) $actual,
                'target' => (int) $target,
                'main' => $isMain,
            ];
            $totalHauling += $actual;
        }
        
        return [
            'label' => 'Monthly',
            'materials' => collect($materials),
            'haulingLabel' => 'Monthly All Hauling (MTD)',
            'haulingTotal' => $totalHauling,
            'daily_breakdown' => $dailyBreakdown,
            'labels' => range(1, $currentDay),
        ];
    }
    
    private function buildShiftSegments()
    {
        $units = Unit::orderBy('kode')->get();
        $daySegments = [];
        $nightSegments = [];
        
        foreach ($units as $unit) {
            // Day shift (06:00-18:00) - 12 hours
            $dayRitasis = Ritasi::where('unit_id', $unit->id)
                ->where('tanggal', today())
                ->where('shift', 'siang')
                ->get();
            
            $dayHours = $dayRitasis->sum('hm_total');
            $dayStatus = $unit->status === 'breakdown' ? 'breakdown' : ($dayHours > 0 ? 'running' : 'standby');
            
            if ($unit->status === 'breakdown') {
                $daySegments[] = [
                    'name' => $unit->kode,
                    'segs' => [['t' => 'breakdown', 'h' => 12]],
                ];
            } elseif ($dayHours > 0) {
                // If unit has ritasi, it's running for those hours, rest standby
                $runningHours = min(12, (float) $dayHours);
                $standbyHours = 12 - $runningHours;
                $segs = [];
                if ($runningHours > 0) $segs[] = ['t' => 'running', 'h' => round($runningHours, 1)];
                if ($standbyHours > 0) $segs[] = ['t' => 'standby', 'h' => round($standbyHours, 1)];
                $daySegments[] = [
                    'name' => $unit->kode,
                    'segs' => $segs,
                ];
            } else {
                $daySegments[] = [
                    'name' => $unit->kode,
                    'segs' => [['t' => 'standby', 'h' => 12]],
                ];
            }
            
            // Night shift (18:00-06:00) - 12 hours
            $nightRitasis = Ritasi::where('unit_id', $unit->id)
                ->where('tanggal', today())
                ->where('shift', 'malam')
                ->get();
            
            $nightHours = $nightRitasis->sum('hm_total');
            $nightStatus = $unit->status === 'breakdown' ? 'breakdown' : ($nightHours > 0 ? 'running' : 'standby');
            
            if ($unit->status === 'breakdown') {
                $nightSegments[] = [
                    'name' => $unit->kode,
                    'segs' => [['t' => 'breakdown', 'h' => 12]],
                ];
            } elseif ($nightHours > 0) {
                $runningHours = min(12, (float) $nightHours);
                $standbyHours = 12 - $runningHours;
                $segs = [];
                if ($runningHours > 0) $segs[] = ['t' => 'running', 'h' => round($runningHours, 1)];
                if ($standbyHours > 0) $segs[] = ['t' => 'standby', 'h' => round($standbyHours, 1)];
                $nightSegments[] = [
                    'name' => $unit->kode,
                    'segs' => $segs,
                ];
            } else {
                $nightSegments[] = [
                    'name' => $unit->kode,
                    'segs' => [['t' => 'standby', 'h' => 12]],
                ];
            }
        }
        
        return [
            'day' => $daySegments,
            'night' => $nightSegments,
        ];
    }
    
    private function buildAllMaterialsHbar()
    {
        $materials = Material::all();
        $hbarData = [];
        
        foreach ($materials as $material) {
            $actual = Ritasi::where('material_id', $material->id)
                ->where('tanggal', today())
                ->sum('jumlah_ritasi');
            
            if ($actual > 0) {
                $hbarData[] = [
                    'name' => $material->nama,
                    'val' => (int) $actual,
                ];
            }
        }
        
        // Sort by value descending
        usort($hbarData, fn($a, $b) => $b['val'] <=> $a['val']);
        
        return $hbarData;
    }
    
    private function buildAvailabilityUoA()
    {
        // Equipment types based on unit kode prefix
        $types = ['Exc', 'Sany', 'ADT', 'Dozer'];
        $availability = [];
        $uoa = [];
        
        foreach ($types as $type) {
            $units = Unit::where('kode', 'like', $type . '%')->get();
            $totalUnits = $units->count();
            
            if ($totalUnits === 0) {
                $availability[$type] = 0;
                $uoa[$type] = 0;
                continue;
            }
            
            // Availability = (units not in breakdown) / total units * 100
            $breakdownUnits = $units->where('status', 'breakdown')->count();
            $availPct = round(($totalUnits - $breakdownUnits) / $totalUnits * 100);
            $availability[$type] = $availPct;
            
            // UoA (Utilization of Availability) = running hours / available hours * 100
            $availableUnits = $totalUnits - $breakdownUnits;
            $availableHours = $availableUnits * 24; // 24 hours per day
            
            $runningHours = 0;
            foreach ($units as $unit) {
                if ($unit->status !== 'breakdown') {
                    $runningHours += Ritasi::where('unit_id', $unit->id)
                        ->where('tanggal', today())
                        ->sum('hm_total');
                }
            }
            
            $uoaPct = $availableHours > 0 ? round($runningHours / $availableHours * 100) : 0;
            $uoa[$type] = min(100, max(0, $uoaPct));
        }
        
        return [
            'availability' => $availability,
            'uoa' => $uoa,
        ];
    }
    
    private function buildStatStrip($shiftSegments, $period)
    {
        $hoursPerUnit = 12; // Daily = 12 hours per shift
        
        $dayRunning = 0;
        $dayBreakdown = 0;
        $dayStandby = 0;
        $nightRunning = 0;
        $nightBreakdown = 0;
        $nightStandby = 0;
        
        foreach ($shiftSegments['day'] as $unit) {
            foreach ($unit['segs'] as $seg) {
                if ($seg['t'] === 'running') $dayRunning += $seg['h'];
                elseif ($seg['t'] === 'breakdown') $dayBreakdown += $seg['h'];
                elseif ($seg['t'] === 'standby') $dayStandby += $seg['h'];
            }
        }
        
        foreach ($shiftSegments['night'] as $unit) {
            foreach ($unit['segs'] as $seg) {
                if ($seg['t'] === 'running') $nightRunning += $seg['h'];
                elseif ($seg['t'] === 'breakdown') $nightBreakdown += $seg['h'];
                elseif ($seg['t'] === 'standby') $nightStandby += $seg['h'];
            }
        }
        
        $totalRunning = round($dayRunning + $nightRunning, 1);
        $totalBD = round($dayBreakdown + $nightBreakdown, 1);
        $totalStandby = round($dayStandby + $nightStandby, 1);
        
        // Achievement % = total running hours / (running units * hours per unit * 2 shifts) * 100
        $runningUnits = collect($shiftSegments['day'])
            ->merge($shiftSegments['night'])
            ->unique('name')
            ->filter(fn($u) => collect($u['segs'])->contains('t', 'running'))
            ->count();
        
        $possibleHours = $runningUnits * $hoursPerUnit * 2;
        $achievementPct = $possibleHours > 0 ? round($totalRunning / $possibleHours * 100, 1) : 0;
        
        return [
            'total_running_hours' => $totalRunning,
            'achievement_pct' => $achievementPct,
            'total_bd_hours' => $totalBD,
            'total_standby_hours' => $totalStandby,
        ];
    }

    public function export(Request $request)
    {
        return redirect()->route('admin.laporan.index');
    }
}