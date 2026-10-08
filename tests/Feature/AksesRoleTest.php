<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AksesRoleTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = $this->buatDataUjian();
    }

    private function halaman(): array
    {
        $prodi = $this->data['prodi']->id;
        $matkul = $this->data['mataKuliah']->id;
        $soal = $this->data['soal']->id;
        $ujian = $this->data['ujian']->id;

        return [
            'prodi.create' => '/admin/prodi/create',
            'prodi.edit' => "/admin/prodi/{$prodi}/edit",
            'matkul.create' => '/admin/matkul/create',
            'matkul.edit' => "/admin/matkul/{$matkul}/edit",
            'dosen.create' => '/admin/dosen/create',
            'mahasiswa.index' => '/admin/mahasiswa',
            'bank-soal.index' => '/admin/make-soals',
            'bank-soal.edit' => "/admin/make-soals/{$soal}/edit",
            'setting-soal.index' => '/admin/setting-soals',
            'ujian.index' => '/admin/ujians',
            'ujian.create' => '/admin/ujians/create',
            'ujian.view' => "/admin/ujians/{$ujian}",
            'ujian-tersedia' => '/admin/daftar-ujian-mahasiswa',
            'riwayat' => '/admin/riwayat-ujians',
        ];
    }

    /**
     * Halaman yang tidak diizinkan harus ditolak di server, bukan hanya disembunyikan tombolnya.
     */
    private function assertAkses(User $user, array $boleh): void
    {
        $this->actingAs($user);

        foreach ($this->halaman() as $nama => $url) {
            $response = $this->get($url);

            if (in_array($nama, $boleh, true)) {
                $this->assertSame(200, $response->status(), "{$user->role} seharusnya BISA membuka {$nama} ({$url})");
            } else {
                // Ditolak = diarahkan ke dashboard, atau 404 karena record di luar query role tersebut
                $ditolak = $response->isRedirect(url('/admin')) || $response->status() === 404;
                $this->assertTrue($ditolak, "{$user->role} seharusnya TIDAK bisa membuka {$nama} ({$url}), status {$response->status()}");
            }
        }
    }

    public function test_akses_kaprodi(): void
    {
        $kaprodi = User::factory()->create(['role' => 'kaprodi']);

        $this->assertAkses($kaprodi, [
            'prodi.create', 'prodi.edit', 'matkul.create', 'matkul.edit', 'dosen.create', 'mahasiswa.index',
            'bank-soal.index', 'bank-soal.edit', 'setting-soal.index', 'ujian.index', 'ujian.create', 'ujian.view',
        ]);
    }

    public function test_akses_dosen(): void
    {
        $this->assertAkses($this->data['dosen'], [
            'matkul.create', 'matkul.edit', 'mahasiswa.index',
            'bank-soal.index', 'bank-soal.edit', 'ujian.index', 'ujian.create', 'ujian.view',
        ]);
    }

    public function test_akses_mahasiswa(): void
    {
        $this->assertAkses($this->data['mahasiswa'], ['ujian-tersedia', 'riwayat']);
    }

    public function test_dosen_tidak_bisa_membuka_ujian_dosen_lain(): void
    {
        $lain = $this->buatDataUjian();

        $this->actingAs($this->data['dosen'])
            ->get("/admin/ujians/{$lain['ujian']->id}")
            ->assertNotFound();
    }
}
