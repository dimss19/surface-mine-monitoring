<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = ['Excavator PC200', 'Excavator PC300', 'Excavator PC400', 'Dump Truck HD785', 'Dump Truck HD465', 'Bulldozer D85ESS', 'Bulldozer D155A', 'Motor Grader GD535', 'Wheel Loader WA380', 'Articulated Dump Truck HM400'];

        for ($i = 1; $i <= 45; $i++) {
            $model = $models[array_rand($models)];
            $kode = strtoupper(substr($model, 0, 2)) . str_pad($i, 3, '0', STR_PAD_LEFT);
            \App\Models\Alat::create([
                'kode' => $kode,
                'nama' => $model . ' - Unit ' . $i,
            ]);
        }
    }
}
