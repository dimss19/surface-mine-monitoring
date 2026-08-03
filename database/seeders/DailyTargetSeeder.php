<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyTarget;
use App\Models\Material;

class DailyTargetSeeder extends Seeder
{
    public function run(): void
    {
        $materials = Material::whereIn('nama', ['Bauxite Ore (Raw)', 'Mining Tuff', 'Cake'])->get();

        if ($materials->isEmpty()) {
            return;
        }

        // Create targets for today and past 7 days
        for ($i = 0; $i < 7; $i++) {
            $tanggal = now()->subDays($i)->toDateString();

            foreach ($materials as $material) {
                $target = match ($material->nama) {
                    'Bauxite Ore (Raw)' => rand(80, 120),
                    'Mining Tuff' => rand(30, 60),
                    'Cake' => rand(20, 40),
                    default => rand(20, 50),
                };

                DailyTarget::updateOrCreate(
                    ['material_id' => $material->id, 'tanggal' => $tanggal],
                    ['target_ritasi' => $target]
                );
            }
        }
    }
}
