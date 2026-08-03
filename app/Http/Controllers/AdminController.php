<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\DashboardDataTrait;

class AdminController extends Controller
{
    use DashboardDataTrait;

    public function dashboard(Request $request)
    {
        $period = $request->get('period', 'daily');
        
        $cacheKey = 'admin_dashboard_' . $period . '_' . today()->toDateString();
        $dashboardData = Cache::remember($cacheKey, 60, fn() => $this->buildDashboardData($period));
        $metrics = $this->buildMetrics($dashboardData['daily'], $dashboardData['stat_strip']);

        return view('admin.dashboard', compact('dashboardData', 'metrics'));
    }
}
