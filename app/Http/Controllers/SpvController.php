<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\DashboardDataTrait;

class SpvController extends Controller
{
    use DashboardDataTrait;

    public function dashboard(Request $request)
    {
        $period = $request->get('period', 'daily');
        
        $cacheKey = 'spv_dashboard_' . $period . '_' . today()->toDateString();
        $dashboardData = Cache::remember($cacheKey, 60, fn() => $this->buildDashboardData($period));
        $metrics = $this->buildMetrics($dashboardData['daily'], $dashboardData['stat_strip']);

        return view('spv.dashboard', compact('dashboardData', 'metrics'));
    }

    public function index()
    {
        return $this->dashboard();
    }
}
