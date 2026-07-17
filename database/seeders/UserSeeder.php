<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@surface-mine.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);
        
        \App\Models\User::create([
            'name' => 'Supervisor 1',
            'email' => 'spv1@surface-mine.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'spv',
        ]);
    }
}
