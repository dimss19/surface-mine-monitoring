<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtilizationRequest;
use App\Models\Unit;
use App\Models\UnitUtilization;
use Illuminate\Http\Request;

class UtilizationController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitUtilization::with(['unit', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc');

        if ($request->filled('tanggal_start') && $request->filled('tanggal_end')) {
            $query->whereBetween('tanggal', [$request->tanggal_start, $request->tanggal_end]);
        } elseif ($request->filled('tanggal_start')) {
            $query->whereDate('tanggal', $request->tanggal_start);
        }

        $utilizations = $query->paginate(15);

        // unit status = latest entry per unit (pgsql DISTINCT ON)
        $latestStatus = UnitUtilization::selectRaw('DISTINCT ON (unit_id) unit_id, tipe')
            ->orderBy('unit_id')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->pluck('tipe', 'unit_id');

        return view('utilization.index', compact('utilizations', 'latestStatus'));
    }

    public function create()
    {
        $units = Unit::where('is_active', true)->orderBy('kode')->get();

        $latestStatus = UnitUtilization::selectRaw('DISTINCT ON (unit_id) unit_id, tipe')
            ->orderBy('unit_id')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->pluck('tipe', 'unit_id');

        return view('operator.utilization.create', compact('units', 'latestStatus'));
    }

    public function store(StoreUtilizationRequest $request)
    {
        $exists = UnitUtilization::where('unit_id', $request->unit_id)
            ->where('tanggal', $request->tanggal)
            ->where('tipe', $request->tipe)
            ->where('deskripsi', $request->deskripsi)
            ->exists();

        if ($exists) {
            if ($request->header('X-Offline-Replay') === '1') {
                return response()->json(['success' => true, 'replayed' => true], 200);
            }
            return back()->with('error', 'Entri utilization yang sama sudah tercatat.');
        }

        UnitUtilization::create([
            'unit_id' => $request->unit_id,
            'tipe' => $request->tipe,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'user_id' => auth()->id(),
        ]);

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Data utilization berhasil disimpan!');
    }
}
