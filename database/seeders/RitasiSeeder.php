<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ritasi;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\Area;
use App\Models\Material;

class RitasiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = Pegawai::take(10)->get();
        $units = Unit::where('tipe', 'dump_truck')->take(5)->get();
        $areas = Area::take(5)->get();
        $materials = Material::whereIn('kategori', ['ore', 'waste'])->take(5)->get();

        if ($pegawai->isEmpty() || $units->isEmpty() || $areas->isEmpty() || $materials->isEmpty()) {
            return;
        }

        $lokasiList = ['Pit 1 North', 'Pit 2 South', 'East Dump', 'Haul Road A', 'South Pit'];
        $deskripsiList = ['Hauling ore', 'Hauling waste', 'Hauling overburden'];
        $shifts = ['siang', 'malam'];
        $statuses = ['pending', 'validated', 'in_progress'];

        $usedKeys = [];

        for ($i = 0; $i < 50; $i++) {
            $pegawaiId = $pegawai->random()->id;
            $shift = $shifts[array_rand($shifts)];
            $tanggal = now()->subDays(rand(0, 30))->format('Y-m-d');

            $key = $pegawaiId . '-' . $tanggal . '-' . $shift;
            if (in_array($key, $usedKeys)) {
                continue;
            }
            $usedKeys[] = $key;

            $hmAwal = rand(10000, 15000) + (rand(0, 99) / 100);
            $hmTotal = rand(6, 11) + (rand(0, 99) / 100);

            Ritasi::create([
                'pegawai_id' => $pegawaiId,
                'unit_id' => $units->random()->id,
                'area_id' => $areas->random()->id,
                'material_id' => $materials->random()->id,
                'tanggal' => $tanggal,
                'shift' => $shift,
                'hm_awal' => $hmAwal,
                'hm_akhir' => $hmAwal + $hmTotal,
                'hm_total' => $hmTotal,
                'jumlah_ritasi' => rand(8, 20),
                'lokasi_pekerjaan' => $lokasiList[array_rand($lokasiList)],
                'deskripsi_pekerjaan' => $deskripsiList[array_rand($deskripsiList)],
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
