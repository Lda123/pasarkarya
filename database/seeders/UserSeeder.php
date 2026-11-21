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
            'nama' => 'Administrator',
            'email' => 'admin@pasarkarya.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_hp' => '081200000001',
        ]);

        User::create([
            'nama' => 'Sari Wulandari',
            'email' => 'sari@mahasiswa.test',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'no_hp' => '081200000002',
        ]);

        User::create([
            'nama' => 'Dito Pratama',
            'email' => 'dito@mahasiswa.test',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa',
            'no_hp' => '081200000003',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@pembeli.test',
            'password' => Hash::make('pembeli123'),
            'role' => 'pembeli',
            'no_hp' => '081200000004',
        ]);

        User::create([
            'nama' => 'Rina Marlina',
            'email' => 'rina@pembeli.test',
            'password' => Hash::make('pembeli123'),
            'role' => 'pembeli',
            'no_hp' => '081200000005',
        ]);
    }
}