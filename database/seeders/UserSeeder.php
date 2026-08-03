<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Supervisor 1',
            'username' => 'spv1',
            'password' => Hash::make('password'),
            'role' => 'spv',
        ]);

        $i = 1;
        foreach (\App\Models\Pegawai::all() as $pegawai) {
            \App\Models\User::create([
                'name' => $pegawai->nama,
                'username' => 'pegawai.' . $i,
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id,
            ]);
            $i++;
        }
    }
}
