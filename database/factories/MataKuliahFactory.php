<?php

namespace Database\Factories;
use App\Models\MataKuliah;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    protected $model = MataKuliah::class;

    public function definition(): array
    {
        $sks = fake()->randomElement([2, 3]);
        $semester = fake()->numberBetween(1, 8);

        return [
            'nama' => fake()->words(3, true),
            'sks' => $sks,
            'semester' => $semester,
            'kode' => '', // nanti diisi di seeder setelah mengetahui prodi
        ];
    }
}
