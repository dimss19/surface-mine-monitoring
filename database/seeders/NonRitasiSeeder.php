<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NonRitasi;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\Area;

class NonRitasiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = Pegawai::all();
        $nonDtUnits = Unit::whereIn('tipe', ['excavator', 'bulldozer', 'motor_grader', 'loader'])->get();
        $areas = Area::take(15)->get();

        if ($pegawai->isEmpty() || $nonDtUnits->isEmpty() || $areas->isEmpty()) {
            return;
        }

        $lokasiList = ['Pit 1 North', 'Pit 2 South', 'Workshop', 'Office Area', 'Fuel Station', 'Stockpile 1', 'Haul Road A', 'Crusher Area'];
        $deskripsiList = ['Dozing', 'Grading', 'Drilling', 'Blasting preparation', 'Excavating', 'Loading', 'Pushing', 'Maintaining haul road'];
        $shifts = ['siang', 'malam'];
        $jamMulaiList = ['06:00', '07:00', '18:00', '19:00'];
        $jamSelesaiList = ['17:00', '18:00', '05:00', '06:00'];

        for ($day = 0; $day < 30; $day++) {
            $tanggal = now()->subDays($day)->format('Y-m-d');

            foreach ($shifts as $shift) {
                $unitsPerShift = $nonDtUnits->random(min(3, $nonDtUnits->count()));
                foreach ($unitsPerShift as $unit) {
                    $peg = $pegawai->random();
                    $hmAwal = rand(5000, 14000) + (rand(0, 99) / 100);
                    $hmTotal = rand(6, 11) + (rand(0, 99) / 100);

                    NonRitasi::create([
                        'pegawai_id' => $peg->id,
                        'unit_id' => $unit->id,
                        'area_id' => $areas->random()->id,
                        'tanggal' => $tanggal,
                        'shift' => $shift,
                        'hm_awal' => $hmAwal,
                        'hm_akhir' => $hmAwal + $hmTotal,
                        'hm_total' => $hmTotal,
                        'jam_mulai' => $jamMulaiList[array_rand($jamMulaiList)],
                        'jam_selesai' => $jamSelesaiList[array_rand($jamSelesaiList)],
                        'lokasi_pekerjaan' => $lokasiList[array_rand($lokasiList)],
                        'deskripsi_pekerjaan' => $deskripsiList[array_rand($deskripsiList)],
                        'status' => $day > 2 ? 'validated' : 'pending',
                    ]);
                }
            }
        }
    }
}
