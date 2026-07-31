<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Material;
use App\Models\Unit;
use App\Models\DailyTarget;
use App\Models\Ritasi;
use App\Models\Area;
use App\Models\Pegawai;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding dashboard data...');

        // Get required models
        $areas = Area::pluck('id')->toArray();
        $pegawais = Pegawai::pluck('id')->toArray();

        // ===== MATERIALS =====
        // Main materials (matching dashboard design names)
        $ore = Material::firstOrCreate(
            ['kode' => 'MAT-ORE-001'],
            ['nama' => 'Ore (Tuff Paste KCN)', 'satuan' => 'Tonnes', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active']
        );

        $tuff = Material::firstOrCreate(
            ['kode' => 'MAT-TUFF-001'],
            ['nama' => 'Tuff Paste KCN', 'satuan' => 'Tonnes', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active']
        );

        $cake = Material::firstOrCreate(
            ['kode' => 'MAT-CAKE-001'],
            ['nama' => 'Cake DST', 'satuan' => 'Tonnes', 'kategori' => 'ore', 'stok' => 99999, 'stok_minimal' => 0, 'status' => 'active']
        );

        // Other materials
        $materials = [
            ['kode' => 'MAT-TO-001', 'nama' => 'Tuff Off', 'satuan' => 'Tonnes', 'kategori' => 'ore'],
            ['kode' => 'MAT-BP-001', 'nama' => 'Batu Pica (5/15)', 'satuan' => 'Tonnes', 'kategori' => 'ore'],
            ['kode' => 'MAT-MT-001', 'nama' => 'Mining Tuff', 'satuan' => 'Tonnes', 'kategori' => 'ore'],
            ['kode' => 'MAT-PA-001', 'nama' => 'Pasir Hitam', 'satuan' => 'Tonnes', 'kategori' => 'ore'],
            ['kode' => 'MAT-MU-001', 'nama' => 'Mud - Lumpur', 'satuan' => 'Tonnes', 'kategori' => 'waste'],
            ['kode' => 'MAT-WA-001', 'nama' => 'Waste', 'satuan' => 'Tonnes', 'kategori' => 'waste'],
        ];

        $materialIds = [];
        foreach ($materials as $m) {
            $mat = Material::firstOrCreate(
                ['kode' => $m['kode']],
                array_merge($m, ['stok' => 99999, 'stok_minimal' => 0, 'status' => 'active'])
            );
            $materialIds[$m['nama']] = $mat->id;
        }

        // ===== UNITS =====
        // Match dashboard design units exactly
        $unitConfigs = [
            // Excavators - Exc type (should be breakdown for daily)
            ['kode' => 'EX022', 'nama' => 'EX022 Long Arm', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC1250-8', 'tahun' => 2020, 'status' => 'breakdown'],
            ['kode' => 'EX024', 'nama' => 'EX024 PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2021, 'status' => 'breakdown'],
            ['kode' => 'EX025', 'nama' => 'EX025 PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2021, 'status' => 'breakdown'],
            ['kode' => 'EX027', 'nama' => 'EX027 PC340', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC340-8', 'tahun' => 2022, 'status' => 'breakdown'],
            ['kode' => 'EX028', 'nama' => 'EX028 PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2020, 'status' => 'active'],
            // Excavators - Sany type
            ['kode' => 'EX029', 'nama' => 'EX029 SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2022, 'status' => 'active'],
            ['kode' => 'EX032', 'nama' => 'EX032 SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'EX033', 'nama' => 'EX033 SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'EX034', 'nama' => 'EX034 SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            // ADT (Dump trucks) - ADT type
            ['kode' => 'ADT101', 'nama' => 'ADT-101', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'ADT102', 'nama' => 'ADT-102', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'ADT103', 'nama' => 'ADT-103', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'ADT104', 'nama' => 'ADT-104', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2022, 'status' => 'active'],
            // Dozer
            ['kode' => 'DZ001', 'nama' => 'Dozer D375A', 'tipe' => 'bulldozer', 'merk' => 'Komatsu', 'model' => 'D375A-6', 'tahun' => 2018, 'status' => 'active'],
            ['kode' => 'DZ002', 'nama' => 'Dozer D375A', 'tipe' => 'bulldozer', 'merk' => 'Komatsu', 'model' => 'D375A-6', 'tahun' => 2019, 'status' => 'active'],
            ['kode' => 'DZ003', 'nama' => 'Dozer D375A', 'tipe' => 'bulldozer', 'merk' => 'Komatsu', 'model' => 'D375A-6', 'tahun' => 2020, 'status' => 'breakdown'],
        ];

        $unitIds = [];
        foreach ($unitConfigs as $uc) {
            $unit = Unit::firstOrCreate(
                ['kode' => $uc['kode']],
                array_merge($uc, ['model' => $uc['model'] ?? '', 'tahun' => $uc['tahun'] ?? 2020])
            );
            $unitIds[$uc['kode']] = $unit->id;
        }

        // ===== DAILY TARGETS =====
        $today = today()->toDateString();
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $createTarget = function ($materialId, $date, $target) {
            DailyTarget::updateOrCreate(
                ['material_id' => $materialId, 'tanggal' => $date],
                ['target_ritasi' => $target]
            );
        };

        // Today's targets (matching design)
        $createTarget($ore->id, $today, 1150);
        $createTarget($tuff->id, $today, 300);
        $createTarget($cake->id, $today, 750);
        foreach ($materialIds as $name => $id) {
            if (!in_array($name, ['Tuff Paste KCN', 'Cake DST', 'Ore (Tuff Paste KCN)'])) {
                $createTarget($id, $today, 150);
            }
        }

        // Weekly targets (7 days)
        foreach (range(0, 6) as $d) {
            $date = now()->startOfWeek()->addDays($d)->toDateString();
            $createTarget($ore->id, $date, 1150);
            $createTarget($tuff->id, $date, 300);
            $createTarget($cake->id, $date, 750);
            foreach ($materialIds as $name => $id) {
                if (!in_array($name, ['Tuff Paste KCN', 'Cake DST', 'Ore (Tuff Paste KCN)'])) {
                    $createTarget($id, $date, 150);
                }
            }
        }

        // Monthly targets (current day of month)
        $daysInMonth = now()->day;
        foreach (range(1, $daysInMonth) as $d) {
            $date = now()->startOfMonth()->addDays($d - 1)->toDateString();
            $createTarget($ore->id, $date, 1150);
            $createTarget($tuff->id, $date, 300);
            $createTarget($cake->id, $date, 750);
            foreach ($materialIds as $name => $id) {
                if (!in_array($name, ['Tuff Paste KCN', 'Cake DST', 'Ore (Tuff Paste KCN)'])) {
                    $createTarget($id, $date, 150);
                }
            }
        }

        // ===== RITASI DATA =====
        // Clear existing ritasi for this month
        Ritasi::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->delete();

        $usedKeys = [];
        $createRitasi = function ($unitKode, $shift, $hours, $materialId, $ritasiCount, $pegawaiId, $areaId, $date = null) use ($today, $unitIds, &$usedKeys) {
            $date = $date ?? $today;
            $key = $pegawaiId . '-' . $date . '-' . $shift;
            if (in_array($key, $usedKeys)) {
                return;
            }
            $usedKeys[] = $key;

            $hmAwal = rand(10000, 15000);
            Ritasi::create([
                'pegawai_id' => $pegawaiId,
                'unit_id' => $unitIds[$unitKode],
                'area_id' => $areaId,
                'material_id' => $materialId,
                'tanggal' => $date,
                'shift' => $shift,
                'hm_awal' => $hmAwal,
                'hm_akhir' => $hmAwal + $hours,
                'hm_total' => $hours,
                'jumlah_ritasi' => (int) round($ritasiCount),
                'lokasi_pekerjaan' => 'Pit A',
                'deskripsi_pekerjaan' => 'Loading & Hauling',
                'status' => 'completed',
                'fuel_consumption' => round($hours * 12, 1),
            ]);
        };

        // ===== TODAY'S RITASI (Daily) =====
        // Design targets:
        // Ore (Tuff Paste KCN): 1227 ritasi, target 1150
        // Tuff Paste KCN: 260 ritasi, target 300
        // Cake DST: 700 ritasi, target 750

        // Ore - Day shift (total ~400 ritasi)
        $createRitasi('EX029', 'siang', 5, $ore->id, 200, $pegawais[0], $areas[0]);
        $createRitasi('EX032', 'siang', 2, $ore->id, 80, $pegawais[1], $areas[1]);
        $createRitasi('EX033', 'siang', 3, $ore->id, 120, $pegawais[2], $areas[2]);
        $createRitasi('EX034', 'siang', 2, $ore->id, 80, $pegawais[3], $areas[3]);

        // Ore - Night shift (total ~827 ritasi)
        $createRitasi('EX028', 'malam', 7, $ore->id, 250, $pegawais[4], $areas[4]);
        $createRitasi('EX029', 'malam', 7, $ore->id, 250, $pegawais[5], $areas[5]);
        $createRitasi('EX032', 'malam', 1, $ore->id, 40, $pegawais[6], $areas[6]);
        $createRitasi('EX033', 'malam', 4, $ore->id, 160, $pegawais[7], $areas[7]);
        $createRitasi('EX034', 'malam', 0, $ore->id, 127, $pegawais[8], $areas[0]); // breakdown but add ritasi

        // Tuff Paste KCN - Day shift (breakdown units: 0 ritasi)
        $createRitasi('EX024', 'siang', 0, $tuff->id, 0, $pegawais[9], $areas[0]);
        $createRitasi('EX025', 'siang', 0, $tuff->id, 0, $pegawais[10], $areas[1]);
        $createRitasi('EX027', 'siang', 0, $tuff->id, 0, $pegawais[11], $areas[2]);
        $createRitasi('EX028', 'siang', 0, $tuff->id, 0, $pegawais[12], $areas[3]);

        // Tuff Paste KCN - Night shift (260 ritasi)
        $createRitasi('EX028', 'malam', 5, $tuff->id, 90, $pegawais[13], $areas[4]);
        $createRitasi('EX033', 'malam', 4, $tuff->id, 170, $pegawais[14], $areas[5]);

        // Cake DST - Day shift (400 ritasi)
        $createRitasi('EX032', 'siang', 2, $cake->id, 150, $pegawais[15], $areas[6]);
        $createRitasi('EX033', 'siang', 3, $cake->id, 200, $pegawais[16], $areas[7]);
        $createRitasi('EX034', 'siang', 2, $cake->id, 150, $pegawais[17], $areas[0]);
        $createRitasi('EX029', 'siang', 5, $cake->id, 200, $pegawais[18], $areas[1]);

        // Cake DST - Night shift (300 ritasi)
        $createRitasi('EX028', 'malam', 2, $cake->id, 150, $pegawais[19], $areas[2]);
        $createRitasi('EX032', 'malam', 0, $cake->id, 50, $pegawais[20], $areas[3]);
        $createRitasi('EX033', 'malam', 0, $cake->id, 50, $pegawais[21], $areas[4]);
        $createRitasi('EX034', 'malam', 0, $cake->id, 50, $pegawais[22], $areas[5]);

        // Other materials for horizontal bars
        $otherMaterials = [
            'Waste' => 26,
            'Mud - Lumpur' => 40,
            'Pasir Hitam' => 90,
            'Mining Tuff' => 120,
            'Batu Pica (5/15)' => 175,
            'Tuff Off' => 260,
        ];

        foreach ($otherMaterials as $name => $count) {
            if (isset($materialIds[$name])) {
                $unit = array_rand($unitIds);
                $shift = rand(0, 1) ? 'siang' : 'malam';
                $createRitasi($unit, $shift, 2, $materialIds[$name], $count, $pegawais[array_rand($pegawais)], $areas[array_rand($areas)]);
            }
        }

        // ===== WEEKLY RITASI (last 7 days) =====
        foreach (range(0, 6) as $d) {
            $date = now()->startOfWeek()->addDays($d)->toDateString();
            $multiplier = 1 + ($d * 0.15);

            // Ore
            $createRitasi('EX029', 'siang', 5 * $multiplier, $ore->id, 200 * $multiplier, $pegawais[0], $areas[0], $date);
            $createRitasi('EX032', 'siang', 2 * $multiplier, $ore->id, 80 * $multiplier, $pegawais[1], $areas[1], $date);
            $createRitasi('EX033', 'siang', 3 * $multiplier, $ore->id, 120 * $multiplier, $pegawais[2], $areas[2], $date);
            $createRitasi('EX034', 'siang', 2 * $multiplier, $ore->id, 80 * $multiplier, $pegawais[3], $areas[3], $date);
            $createRitasi('EX028', 'malam', 7 * $multiplier, $ore->id, 250 * $multiplier, $pegawais[4], $areas[4], $date);
            $createRitasi('EX029', 'malam', 7 * $multiplier, $ore->id, 250 * $multiplier, $pegawais[5], $areas[5], $date);
            $createRitasi('EX032', 'malam', 1 * $multiplier, $ore->id, 40 * $multiplier, $pegawais[6], $areas[6], $date);
            $createRitasi('EX033', 'malam', 4 * $multiplier, $ore->id, 160 * $multiplier, $pegawais[7], $areas[7], $date);

            // Tuff Paste KCN
            $createRitasi('EX028', 'malam', 5 * $multiplier, $tuff->id, 90 * $multiplier, $pegawais[13], $areas[6], $date);
            $createRitasi('EX033', 'malam', 4 * $multiplier, $tuff->id, 170 * $multiplier, $pegawais[14], $areas[7], $date);

            // Cake
            $createRitasi('EX028', 'malam', 2 * $multiplier, $cake->id, 150 * $multiplier, $pegawais[21], $areas[6], $date);
            $createRitasi('EX032', 'siang', 2 * $multiplier, $cake->id, 150 * $multiplier, $pegawais[26], $areas[3], $date);
            $createRitasi('EX033', 'siang', 3 * $multiplier, $cake->id, 200 * $multiplier, $pegawais[27], $areas[4], $date);
            $createRitasi('EX034', 'siang', 2 * $multiplier, $cake->id, 150 * $multiplier, $pegawais[28], $areas[5], $date);
            $createRitasi('EX029', 'siang', 5 * $multiplier, $cake->id, 200 * $multiplier, $pegawais[29], $areas[6], $date);
        }

        // ===== MONTHLY RITASI (days 1 to current day) =====
        $currentDay = now()->day;
        foreach (range(1, $currentDay) as $d) {
            $date = now()->startOfMonth()->addDays($d - 1)->toDateString();
            $multiplier = 0.5 + ($d / $currentDay) * 1.5;

            // Ore
            $createRitasi('EX029', 'siang', 5 * $multiplier, $ore->id, 200 * $multiplier, $pegawais[0], $areas[0], $date);
            $createRitasi('EX032', 'siang', 2 * $multiplier, $ore->id, 80 * $multiplier, $pegawais[1], $areas[1], $date);
            $createRitasi('EX033', 'siang', 3 * $multiplier, $ore->id, 120 * $multiplier, $pegawais[2], $areas[2], $date);
            $createRitasi('EX034', 'siang', 2 * $multiplier, $ore->id, 80 * $multiplier, $pegawais[3], $areas[3], $date);
            $createRitasi('EX028', 'malam', 7 * $multiplier, $ore->id, 250 * $multiplier, $pegawais[4], $areas[4], $date);
            $createRitasi('EX029', 'malam', 7 * $multiplier, $ore->id, 250 * $multiplier, $pegawais[5], $areas[5], $date);
            $createRitasi('EX032', 'malam', 1 * $multiplier, $ore->id, 40 * $multiplier, $pegawais[6], $areas[6], $date);
            $createRitasi('EX033', 'malam', 4 * $multiplier, $ore->id, 160 * $multiplier, $pegawais[7], $areas[7], $date);

            // Tuff Paste KCN
            $createRitasi('EX028', 'malam', 5 * $multiplier, $tuff->id, 90 * $multiplier, $pegawais[13], $areas[6], $date);
            $createRitasi('EX033', 'malam', 4 * $multiplier, $tuff->id, 170 * $multiplier, $pegawais[14], $areas[7], $date);

            // Cake
            $createRitasi('EX028', 'malam', 2 * $multiplier, $cake->id, 150 * $multiplier, $pegawais[21], $areas[6], $date);
            $createRitasi('EX032', 'siang', 2 * $multiplier, $cake->id, 150 * $multiplier, $pegawais[26], $areas[3], $date);
            $createRitasi('EX033', 'siang', 3 * $multiplier, $cake->id, 200 * $multiplier, $pegawais[27], $areas[4], $date);
            $createRitasi('EX034', 'siang', 2 * $multiplier, $cake->id, 150 * $multiplier, $pegawais[28], $areas[5], $date);
            $createRitasi('EX029', 'siang', 5 * $multiplier, $cake->id, 200 * $multiplier, $pegawais[29], $areas[6], $date);
        }

        $this->command->info('Dashboard data seeded successfully!');
    }
}