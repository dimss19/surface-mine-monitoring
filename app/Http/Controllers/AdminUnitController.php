<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Area;
use Illuminate\Http\Request;

class AdminUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%")
                  ->orWhere('model', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $units = $query->orderBy('kode')->paginate(10);
        
        return view('admin.master-data.index', [
            'activeTab' => 'unit',
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:units,kode',
            'nama' => 'required|string',
            'tipe' => 'required|in:excavator,dump_truck,bulldozer,motor_grader,loader,other',
            'merk' => 'nullable|string',
            'model' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kapasitas' => 'nullable|numeric|min:0',
            'fuel_consumption_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,maintenance,breakdown,standby',
            'keterangan' => 'nullable|string',
        ]);

        Unit::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'unit'])
            ->with('success', 'Unit berhasil ditambahkan');
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:units,kode,' . $unit->id,
            'nama' => 'required|string',
            'tipe' => 'required|in:excavator,dump_truck,bulldozer,motor_grader,loader,other',
            'merk' => 'nullable|string',
            'model' => 'nullable|string',
            'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kapasitas' => 'nullable|numeric|min:0',
            'fuel_consumption_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,maintenance,breakdown,standby',
            'keterangan' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'unit'])
            ->with('success', 'Unit berhasil diupdate');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.master-data.index', ['tab' => 'unit'])
            ->with('success', 'Unit berhasil dihapus');
    }

    public function export()
    {
        return response()->download('units.xlsx');
    }
}