<?php

namespace App\Http\Controllers;

use App\Models\DailyTarget;
use Illuminate\Http\Request;

class AdminTargetController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'tanggal' => 'required|date',
            'target_ritasi' => 'required|integer|min:0',
        ]);

        DailyTarget::updateOrCreate(
            ['material_id' => $request->material_id, 'tanggal' => $request->tanggal],
            ['target_ritasi' => $request->target_ritasi]
        );

        return redirect()->route('admin.master-data.index', ['tab' => 'target'])
            ->with('success', 'Target harian berhasil disimpan');
    }

    public function destroy(DailyTarget $target)
    {
        $target->delete();
        return redirect()->route('admin.master-data.index', ['tab' => 'target'])
            ->with('success', 'Target harian berhasil dihapus');
    }
}
