<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpvController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $metrics = [
            'total_area' => $user->areas()->count(),
            'laporan_bulan_ini' => \App\Models\PemantauanLapangan::where('spv_id', $user->id)
                                    ->whereMonth('tanggal', date('m'))
                                    ->whereYear('tanggal', date('Y'))
                                    ->count(),
        ];

        return view('spv.dashboard', compact('metrics'));
    }
}
