<?php

namespace Database\Seeders;
use App\Models\Prodi;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh manual beberapa prodi
        $prodis = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika'],
            ['kode' => 'TK', 'nama' => 'Teknik Komputer']
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }

        // Atau generate fake 5 prodi tambahan
        Prodi::factory()->count(4)->create();
    }
}
