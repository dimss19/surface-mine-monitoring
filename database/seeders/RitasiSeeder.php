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
        $pegawai = Pegawai::all();
        $dtUnits = Unit::where('tipe', 'dump_truck')->get();
        $areas = Area::take(15)->get();
        $materials = Material::whereIn('kategori', ['ore', 'waste'])->get();

        if ($pegawai->isEmpty() || $dtUnits->isEmpty() || $areas->isEmpty() || $materials->isEmpty()) {
            return;
        }

        $lokasiList = ['Pit 1 North', 'Pit 2 South', 'East Dump', 'Haul Road A', 'South Pit', 'North Pit', 'West Pit', 'Stockpile 1', 'Stockpile 2', 'Crusher Area'];
        $deskripsiList = ['Hauling ore', 'Hauling waste', 'Hauling overburden', 'Hauling topsoil'];
        $shifts = ['siang', 'malam'];

        for ($day = 0; $day < 30; $day++) {
            $tanggal = now()->subDays($day)->format('Y-m-d');

            foreach ($shifts as $shift) {
                $unitsPerShift = $dtUnits->random(min(4, $dtUnits->count()));
                foreach ($unitsPerShift as $unit) {
                    $peg = $pegawai->random();
                    $hmAwal = rand(8000, 16000) + (rand(0, 99) / 100);
                    $hmTotal = rand(6, 11) + (rand(0, 99) / 100);
                    $jumlahRitasi = rand(8, 25);
                    $material = $materials->random();
                    $tonPerRit = match ($material->kategori) {
                        'ore' => rand(30, 80),
                        default => rand(40, 100),
                    };

                    Ritasi::create([
                        'pegawai_id' => $peg->id,
                        'unit_id' => $unit->id,
                        'area_id' => $areas->random()->id,
                        'material_id' => $material->id,
                        'tanggal' => $tanggal,
                        'shift' => $shift,
                        'hm_awal' => $hmAwal,
                        'hm_akhir' => $hmAwal + $hmTotal,
                        'hm_total' => $hmTotal,
                        'jumlah_ritasi' => $jumlahRitasi,
                        'quantity_tonnes' => $jumlahRitasi * $tonPerRit,
                        'fuel_consumption' => $hmTotal * rand(25, 45),
                        'lokasi_pekerjaan' => $lokasiList[array_rand($lokasiList)],
                        'deskripsi_pekerjaan' => $deskripsiList[array_rand($deskripsiList)],
                        'status' => $day > 2 ? 'validated' : 'pending',
                    ]);
                }
            }
        }
    }
}
