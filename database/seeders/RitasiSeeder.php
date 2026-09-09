<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Material;
use App\Models\Pegawai;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RitasiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::orderBy('id')->get();
        // Operational dump trucks (DT-003 to DT-006)
        $dtUnits = Unit::where('tipe', 'dump_truck')
            ->whereNotIn('kode', ['DT-001', 'DT-002'])
            ->get();
        $areas = Area::where('status', 'active')
            ->whereNotIn('kode', ['AREA-012', 'AREA-013', 'AREA-014'])
            ->get();
        $materials = Material::whereIn('kategori', ['ore', 'waste'])->get();
        $spvUser = User::where('role', 'spv')->first();

        if ($pegawais->isEmpty() || $dtUnits->isEmpty() || $areas->isEmpty() || $materials->isEmpty()) {
            return;
        }

        $deskripsiList = ['Hauling Bauxite Ore ke Stockpile', 'Hauling Overburden ke Disposal 1', 'Hauling Mining Tuff ke Crusher'];
        $shifts = ['siang', 'malam'];

        $rows = [];

        // Generate hauling data including day 0 (today) and past 30 days
        for ($day = 0; $day <= 30; $day++) {
            $tanggal = now()->subDays($day)->format('Y-m-d');

            foreach ($shifts as $shift) {
                $shiftPegawais = $pegawais->shuffle()->take(rand(3, 5));
                $usedUnits = [];

                foreach ($shiftPegawais as $peg) {
                    $availableUnits = $dtUnits->whereNotIn('id', $usedUnits);
                    if ($availableUnits->isEmpty()) {
                        break;
                    }
                    $unit = $availableUnits->random();
                    $usedUnits[] = $unit->id;

                    $area = $areas->random();
                    $material = $materials->random();

                    $hmTotal = round(rand(70, 105) / 10, 1);
                    $hmAwal = 1250.0 + ($day * 15) + rand(1, 10);
                    $hmAkhir = $hmAwal + $hmTotal;
                    $ritasi = rand(10, 22);
                    $quantity = round($ritasi * ($unit->kapasitas ? $unit->kapasitas * 0.9 : 85.0), 2);
                    $fuel = round($hmTotal * ($unit->fuel_consumption_rate ?: 35.0) * 0.95, 1);

                    $isValidated = $day > 0 || rand(0, 1) === 1;

                    $rows[] = [
                        'pegawai_id' => $peg->id,
                        'unit_id' => $unit->id,
                        'area_id' => $area->id,
                        'material_id' => $material->id,
                        'tanggal' => $tanggal,
                        'shift' => $shift,
                        'hm_awal' => $hmAwal,
                        'hm_akhir' => $hmAkhir,
                        'hm_total' => $hmTotal,
                        'jumlah_ritasi' => $ritasi,
                        'quantity' => $quantity,
                        'quantity_unit' => 'ton',
                        'fuel_consumption' => $fuel,
                        'deskripsi_pekerjaan' => $deskripsiList[array_rand($deskripsiList)],
                        'kendala' => rand(0, 10) > 8 ? 'Antrean di crusher sempat padat 15 menit' : null,
                        'status' => $isValidated ? 'validated' : 'pending',
                        'validated_by' => $isValidated ? $spvUser?->id : null,
                        'validated_at' => $isValidated ? now()->subDays($day)->addHours(12) : null,
                        'created_at' => now()->subDays($day),
                        'updated_at' => now()->subDays($day),
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('ritasis')->insert($chunk);
        }
    }
}
