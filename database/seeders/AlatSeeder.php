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
        for ($i = 1; $i <= 45; $i++) {
            \App\Models\Alat::create([
                'kode' => 'EX' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => 'Excavator Model ' . $i,
            ]);
        }
    }
}
