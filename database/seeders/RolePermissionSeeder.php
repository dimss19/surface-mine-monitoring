<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            RolePermission::create([
                'role' => 'admin',
                'permission_id' => $permission->id,
                'allowed' => true,
            ]);

            $spvAllowed = in_array($permission->group, ['pemantauan', 'laporan']) ||
                          in_array($permission->name, ['unit.view', 'material.view', 'area.view', 'pegawai.view', 'ritasi.view', 'non-ritasi.view']);

            RolePermission::create([
                'role' => 'spv',
                'permission_id' => $permission->id,
                'allowed' => $spvAllowed,
            ]);

            $pegawaiAllowed = in_array($permission->name, ['ritasi.create', 'non-ritasi.create']);

            RolePermission::create([
                'role' => 'pegawai',
                'permission_id' => $permission->id,
                'allowed' => $pegawaiAllowed,
            ]);
        }
    }
}
