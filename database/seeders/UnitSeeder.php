<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['kode' => 'EXC-001', 'nama' => 'Excavator PC2000-8', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC2000-8', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'DT-104', 'nama' => 'Dump Truck HD785-7', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'BDZ-022', 'nama' => 'Bulldozer D375A-6', 'tipe' => 'bulldozer', 'merk' => 'Komatsu', 'model' => 'D375A-6', 'tahun' => 2018, 'status' => 'maintenance'],
            ['kode' => 'EXC-005', 'nama' => 'Excavator R220LC-9', 'tipe' => 'excavator', 'merk' => 'Hyundai', 'model' => 'R220LC-9', 'tahun' => 2019, 'status' => 'breakdown'],
            ['kode' => 'MG-011', 'nama' => 'Motor Grader GD825A-2', 'tipe' => 'motor_grader', 'merk' => 'Komatsu', 'model' => 'GD825A-2', 'tahun' => 2022, 'status' => 'standby'],
            ['kode' => 'EXC-022', 'nama' => 'Excavator Long Arm', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC1250-8', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'EXC-024', 'nama' => 'Excavator PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'EXC-025', 'nama' => 'Excavator PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'EXC-027', 'nama' => 'Excavator PC340', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC340-8', 'tahun' => 2022, 'status' => 'active'],
            ['kode' => 'EXC-028', 'nama' => 'Excavator PC320', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC320-8', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'EXC-029', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2022, 'status' => 'active'],
            ['kode' => 'EXC-032', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'EXC-033', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'EXC-034', 'nama' => 'Excavator SY215', 'tipe' => 'excavator', 'merk' => 'Sany', 'model' => 'SY215C', 'tahun' => 2023, 'status' => 'active'],
            ['kode' => 'DT-1042', 'nama' => 'Dump Truck DT-1042', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2020, 'status' => 'active'],
            ['kode' => 'DT-1055', 'nama' => 'Dump Truck DT-1055', 'tipe' => 'dump_truck', 'merk' => 'Komatsu', 'model' => 'HD785-7', 'tahun' => 2021, 'status' => 'active'],
            ['kode' => 'EX-2015', 'nama' => 'Excavator EX-2015', 'tipe' => 'excavator', 'merk' => 'Komatsu', 'model' => 'PC200-8', 'tahun' => 2019, 'status' => 'active'],
            ['kode' => 'LV-008', 'nama' => 'Leviathan LV-008', 'tipe' => 'loader', 'merk' => 'Caterpillar', 'model' => '966M', 'tahun' => 2020, 'status' => 'active'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
