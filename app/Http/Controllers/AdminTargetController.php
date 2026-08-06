<?php

namespace App\Http\Controllers;

use App\Models\DailyTarget;
use App\Models\Material;
use Illuminate\Http\Request;

class AdminTargetController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyTarget::with('material');

        if ($request->filled('search')) {
            $query->whereHas('material', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%");
            });
        }

        $targets = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.master-data.index', [
            'activeTab' => 'target',
            'targets' => $targets,
            'materials' => Material::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'target_ritasi' => 'required|integer|min:0',
        ]);

        DailyTarget::updateOrCreate(
            ['material_id' => $request->material_id],
            ['target_ritasi' => $request->target_ritasi]
        );

        return redirect()->route('admin.master-data.index', ['tab' => 'target'])
            ->with('success', 'Target harian berhasil disimpan');
    }

    public function edit(DailyTarget $target)
    {
        return response()->json($target->load('material'));
    }

    public function update(Request $request, DailyTarget $target)
    {
        $validated = $request->validate([
            'target_ritasi' => 'required|integer|min:0',
        ]);

        $target->update($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'target'])
            ->with('success', 'Target harian berhasil diupdate');
    }

    public function destroy(DailyTarget $target)
    {
        $target->delete();
        return redirect()->route('admin.master-data.index', ['tab' => 'target'])
            ->with('success', 'Target harian berhasil dihapus');
    }
}