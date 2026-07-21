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
            'email' => 'admin@surface-mine.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Supervisor 1',
            'email' => 'spv1@surface-mine.com',
            'password' => Hash::make('password'),
            'role' => 'spv',
        ]);

        foreach (\App\Models\Pegawai::all() as $pegawai) {
            \App\Models\User::create([
                'name' => $pegawai->nama,
                'email' => 'pegawai.' . $pegawai->id . '@mine.local',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id,
            ]);
        }
    }
}
