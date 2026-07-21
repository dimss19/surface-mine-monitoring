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

        $jenisMap = [
            'Excavator PC200' => 'excavator', 'Excavator PC300' => 'excavator', 'Excavator PC400' => 'excavator',
            'Dump Truck HD785' => 'dump_truck', 'Dump Truck HD465' => 'dump_truck',
            'Bulldozer D85ESS' => 'dozer', 'Bulldozer D155A' => 'dozer',
            'Motor Grader GD535' => 'grader',
            'Wheel Loader WA380' => 'loader',
            'Articulated Dump Truck HM400' => 'dump_truck',
        ];

        for ($i = 1; $i <= 45; $i++) {
            $model = $models[array_rand($models)];
            \App\Models\Alat::create([
                'jenis' => $jenisMap[$model] ?? 'lainnya',
                'nama' => $model . ' - Unit ' . $i,
            ]);
        }
    }
}
