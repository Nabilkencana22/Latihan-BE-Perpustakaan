<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Perpustakaan',
            'email'    => 'admin@perpus.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Petugas Perpus',
            'email'    => 'petugas@perpus.com',
            'password' => Hash::make('password123'),
            'role'     => 'petugas',
        ]);

        User::create([
            'name'     => 'Anggota Perpus',
            'email'    => 'anggota@perpus.com',
            'password' => Hash::make('password123'),
            'role'     => 'anggota',
        ]);
    }
}