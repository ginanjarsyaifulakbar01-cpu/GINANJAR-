<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name'     => 'Admin Ginx',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // 2. Akun Petugas
        User::create([
            'name'     => 'Petugas Perpus',
            'email'    => 'petugas@gmail.com',
            'password' => Hash::make('petugas123'),
            'role'     => 'petugas',
        ]);

        // 3. Akun Anggota
        User::create([
            'name'     => 'Siswa Ginx',
            'email'    => 'anggota@gmail.com',
            'password' => Hash::make('anggota123'),
            'role'     => 'anggota',
        ]);
    }
}