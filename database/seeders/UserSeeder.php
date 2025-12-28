<?php

namespace Database\Seeders;

use App\Models\User; // ⬅️ WAJIB
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
   public function run(): void
{
    // 1. Akun KAPRODI
    User::create([
        'name' => 'Kaprodi User',
        'email' => 'kaprodi@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'kaprodi',
    ]);

    // 2. Akun DOSEN
    User::create([
        'name' => 'Dosen User',
        'email' => 'dosen@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'dosen',
    ]);

    // 3. Akun MAHASISWA
    User::create([
        'name' => 'Mahasiswa User',
        'email' => 'mahasiswa@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'mahasiswa',
    ]);

    User::factory()->count(20)->create([
        'role' => fn () => fake()->randomElement(['dosen', 'mahasiswa'])
    ]);
}
}
