<?php

namespace App\Http\Controllers;

use App\Services\DashboardReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardReportService $reports)
    {
        $tab = $request->query('tab', 'daily');

        $data = match ($tab) {
            'weekly'   => $reports->weeklyData($request),
            'monthly'  => $reports->monthlyData($request),
            default    => $reports->dailyData($request),
        };

        return view('dashboard.index', $data + ['tab' => $tab]);
    }

    public function export(Request $request, DashboardReportService $reports)
    {
        $period = $request->query('period', 'daily');
        $data = $reports->exportData($request, $period);

        $filename = 'PA-UA_' . $period . '_' . now()->format('Ymd_His') . '.xls';

        return response()->view('dashboard.export.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
