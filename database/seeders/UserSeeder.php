<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Dosen
        User::updateOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Sirojul Munir',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'nidn' => '0414047101',
            ]
        );

        // Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Muhammad Rizky Syawalian Lubis',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'nim' => '110225177',
            ]
        );

        // Asdos
        User::updateOrCreate(
            ['email' => 'asdos@example.com'],
            [
                'name' => 'Yasmine Artamevia',
                'password' => Hash::make('password'),
                'role' => 'asdos',
                'nim' => '110222009',
                'dosen_id' => 1,
            ]
        );
    }
}