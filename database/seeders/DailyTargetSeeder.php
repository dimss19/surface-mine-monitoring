<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTarget;
use App\Models\Material;

class DailyTargetSeeder extends Seeder
{
    public function run(): void
    {
        $materials = Material::whereIn('nama', ['Bauxite Ore (Raw)', 'Mining Tuff', 'Cake', 'Pasir Hitam', 'Tuff Off'])->get();

        if ($materials->isEmpty()) {
            return;
        }

        foreach ($materials as $material) {
            $target = match ($material->nama) {
                'Bauxite Ore (Raw)' => rand(80, 150),
                'Mining Tuff' => rand(30, 70),
                'Cake' => rand(20, 50),
                'Pasir Hitam' => rand(40, 80),
                'Tuff Off' => rand(25, 60),
                default => rand(20, 60),
            };

            // Target is set once per material (not per date).
            DailyTarget::updateOrCreate(
                ['material_id' => $material->id],
                ['target_ritasi' => $target]
            );
        }
    }
}
