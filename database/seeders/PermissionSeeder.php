<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'unit.view', 'label' => 'Lihat Unit', 'group' => 'unit'],
            ['name' => 'unit.create', 'label' => 'Tambah Unit', 'group' => 'unit'],
            ['name' => 'unit.edit', 'label' => 'Edit Unit', 'group' => 'unit'],
            ['name' => 'unit.delete', 'label' => 'Hapus Unit', 'group' => 'unit'],
            ['name' => 'unit.export', 'label' => 'Export Unit', 'group' => 'unit'],

            ['name' => 'material.view', 'label' => 'Lihat Material', 'group' => 'material'],
            ['name' => 'material.create', 'label' => 'Tambah Material', 'group' => 'material'],
            ['name' => 'material.edit', 'label' => 'Edit Material', 'group' => 'material'],
            ['name' => 'material.delete', 'label' => 'Hapus Material', 'group' => 'material'],
            ['name' => 'material.export', 'label' => 'Export Material', 'group' => 'material'],

            ['name' => 'area.view', 'label' => 'Lihat Area', 'group' => 'area'],
            ['name' => 'area.create', 'label' => 'Tambah Area', 'group' => 'area'],
            ['name' => 'area.edit', 'label' => 'Edit Area', 'group' => 'area'],
            ['name' => 'area.delete', 'label' => 'Hapus Area', 'group' => 'area'],

            ['name' => 'pegawai.view', 'label' => 'Lihat Pegawai', 'group' => 'pegawai'],
            ['name' => 'pegawai.create', 'label' => 'Tambah Pegawai', 'group' => 'pegawai'],
            ['name' => 'pegawai.edit', 'label' => 'Edit Pegawai', 'group' => 'pegawai'],
            ['name' => 'pegawai.delete', 'label' => 'Hapus Pegawai', 'group' => 'pegawai'],

            ['name' => 'ritasi.view', 'label' => 'Lihat Ritasi', 'group' => 'ritasi'],
            ['name' => 'ritasi.create', 'label' => 'Input Ritasi', 'group' => 'ritasi'],
            ['name' => 'ritasi.validate', 'label' => 'Validasi Ritasi', 'group' => 'ritasi'],

            ['name' => 'non-ritasi.view', 'label' => 'Lihat Non-Ritasi', 'group' => 'non-ritasi'],
            ['name' => 'non-ritasi.create', 'label' => 'Input Non-Ritasi', 'group' => 'non-ritasi'],
            ['name' => 'non-ritasi.validate', 'label' => 'Validasi Non-Ritasi', 'group' => 'non-ritasi'],

            ['name' => 'pemantauan.view', 'label' => 'Lihat Pemantauan', 'group' => 'pemantauan'],
            ['name' => 'pemantauan.create', 'label' => 'Buat Pemantauan', 'group' => 'pemantauan'],

            ['name' => 'laporan.harian', 'label' => 'Laporan Harian', 'group' => 'laporan'],
            ['name' => 'laporan.mingguan', 'label' => 'Laporan Mingguan', 'group' => 'laporan'],
            ['name' => 'laporan.bulanan', 'label' => 'Laporan Bulanan', 'group' => 'laporan'],
            ['name' => 'laporan.export', 'label' => 'Export Laporan', 'group' => 'laporan'],

            ['name' => 'hak-akses.view', 'label' => 'Lihat Hak Akses', 'group' => 'hak-akses'],
            ['name' => 'hak-akses.edit', 'label' => 'Edit Hak Akses', 'group' => 'hak-akses'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
