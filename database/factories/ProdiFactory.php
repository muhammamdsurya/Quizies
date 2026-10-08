<?php

namespace Database\Factories;

use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prodi>
 */
class ProdiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Prodi::class;

    public function definition(): array
    {
        $faker = fake('id_ID'); // instance bersama agar unique() berlaku antar baris

        return [
            'kode' => strtoupper($faker->unique()->lexify('???')), // 3 huruf, beda dari kode prodi tetap (2 huruf)
            'nama' => $faker->words(3, true),
        ];
    }
}
