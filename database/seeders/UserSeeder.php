<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = Pegawai::first();

        $users = [
            ['name' => 'Super Admin', 'username' => 'admin', 'role' => 'admin', 'pegawai_id' => null],
            ['name' => 'Supervisor', 'username' => 'spv', 'role' => 'spv', 'pegawai_id' => null],
            ['name' => 'Operator', 'username' => 'operator', 'role' => 'pegawai', 'pegawai_id' => $pegawai?->id],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'pegawai_id' => $user['pegawai_id'],
                ]
            );
        }
    }
}
