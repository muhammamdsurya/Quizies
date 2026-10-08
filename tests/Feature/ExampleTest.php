<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_landing_page_menampilkan_statistik_panduan_dan_faq(): void
    {
        $this->buatDataUjian();

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Mahasiswa', 'Dosen', 'Mata Kuliah', 'Ujian Dikerjakan'])
            ->assertSee('Apa itu Kuiz Digital?')
            ->assertSee('Untuk Mahasiswa')
            ->assertSee('Untuk Dosen')
            ->assertSee('Pertanyaan yang sering diajukan')
            ->assertSee('Masuk Portal');
    }
}
