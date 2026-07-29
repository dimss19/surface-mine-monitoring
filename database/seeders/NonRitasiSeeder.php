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
        $pegawai = Pegawai::take(10)->get();
        $units = Unit::whereIn('tipe', ['excavator', 'bulldozer', 'motor_grader'])->take(5)->get();
        $areas = Area::take(5)->get();

        if ($pegawai->isEmpty() || $units->isEmpty() || $areas->isEmpty()) {
            return;
        }

        $lokasiList = ['Pit 1 North', 'Workshop', 'Office Area', 'Fuel Station'];
        $deskripsiList = ['Dozing', 'Grading', 'Drilling', 'Blasting preparation'];
        $shifts = ['siang', 'malam'];
        $statuses = ['pending', 'validated'];
        $jamMulaiList = ['06:00', '07:00', '18:00', '19:00'];
        $jamSelesaiList = ['17:00', '18:00', '05:00', '06:00'];

        $usedKeys = [];

        for ($i = 0; $i < 30; $i++) {
            $pegawaiId = $pegawai->random()->id;
            $shift = $shifts[array_rand($shifts)];
            $tanggal = now()->subDays(rand(0, 30))->format('Y-m-d');

            $key = $pegawaiId . '-' . $tanggal . '-' . $shift;
            if (in_array($key, $usedKeys)) {
                continue;
            }
            $usedKeys[] = $key;

            $hmAwal = rand(5000, 12000) + (rand(0, 99) / 100);
            $hmTotal = rand(6, 11) + (rand(0, 99) / 100);

            NonRitasi::create([
                'pegawai_id' => $pegawaiId,
                'unit_id' => $units->random()->id,
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
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
