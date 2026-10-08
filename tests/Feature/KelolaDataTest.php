<?php

namespace Tests\Feature;

use App\Filament\Resources\MakeSoals\Pages\CreateMakeSoal;
use App\Filament\Resources\MataKuliahs\Pages\CreateMataKuliah;
use App\Filament\Resources\Ujians\Pages\CreateUjian;
use App\Models\Soals;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KelolaDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_membuat_bank_soal_dengan_nomor_urut_dan_tipe_mengikuti_paket(): void
    {
        $data = $this->buatDataUjian();
        $this->actingAs($data['dosen']);
        $undoRepeaterFake = Repeater::fake(); // ganti item kosong bawaan dengan data uji

        Livewire::test(CreateMakeSoal::class)
            ->fillForm([
                'mata_kuliah_id' => $data['mataKuliah']->id,
                'nama_soal' => 'Kuis Baru',
                'setting_soal_id' => $data['soal']->setting_soal_id,
                'jenis_soal' => 'kuis',
                'tipe_soal' => 'pg',
                'detailSoals' => [
                    ['pertanyaan' => 'Soal A', 'opsi_a' => '1', 'opsi_b' => '2', 'opsi_c' => '3', 'opsi_d' => '4', 'kunci_jawaban' => 'a'],
                    ['pertanyaan' => 'Soal B', 'opsi_a' => '1', 'opsi_b' => '2', 'opsi_c' => '3', 'opsi_d' => '4', 'kunci_jawaban' => 'd'],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $undoRepeaterFake();

        $paket = Soals::where('nama_soal', 'Kuis Baru')->sole();

        $this->assertSame($data['dosen']->id, $paket->user_id);
        $this->assertSame([1, 2], $paket->detailSoals->pluck('nomor_soal')->all());
        $this->assertSame(['pg', 'pg'], $paket->detailSoals->pluck('tipe_soal')->all());
    }

    public function test_jadwal_selesai_ujian_harus_setelah_waktu_mulai(): void
    {
        $data = $this->buatDataUjian();
        $this->actingAs($data['dosen']);

        Livewire::test(CreateUjian::class)
            ->fillForm([
                'judul_ujian' => 'Ujian Salah Jadwal',
                'soals_id' => $data['soal']->id,
                'waktu_mulai' => now()->addDay()->format('Y-m-d H:i'),
                'waktu_selesai' => now()->format('Y-m-d H:i'),
                'durasi_menit' => 0,
            ])
            ->call('create')
            ->assertHasFormErrors(['waktu_selesai' => 'after', 'durasi_menit' => 'min']);
    }

    public function test_halaman_detail_menampilkan_data_dengan_rapi(): void
    {
        $data = $this->buatDataUjian();
        $matkul = $data['mataKuliah'];
        $mahasiswa = $data['mahasiswa'];
        $this->actingAs(User::factory()->create(['role' => 'kaprodi']));

        $this->get("/admin/mahasiswa/{$mahasiswa->id}")->assertOk()
            ->assertSee('Mata Kuliah yang Diambil')
            ->assertSee($matkul->nama)
            ->assertSee("1 mata kuliah · {$matkul->sks} SKS")
            ->assertSee($mahasiswa->mahasiswaProfile->nim);

        $this->get("/admin/dosen/{$data['dosen']->id}")->assertOk()
            ->assertSee('Mata Kuliah yang Diampu')
            ->assertSee($matkul->nama);

        $this->get("/admin/prodi/{$data['prodi']->id}")->assertOk()->assertSee($matkul->nama);
        $this->get("/admin/setting-soals/{$data['soal']->setting_soal_id}")->assertOk()->assertSee('Pilihan Ganda');

        $this->get("/admin/make-soals/{$data['soal']->id}")->assertOk()
            ->assertSee('Soal Nomor 1')
            ->assertSee('Warna langit cerah?'); // markdown meng-encode '+' pada soal 1, jadi cek soal 2
    }

    public function test_daftar_mahasiswa_di_mata_kuliah_hanya_untuk_dosen_dan_kaprodi(): void
    {
        $data = $this->buatDataUjian();
        $url = "/admin/matkul/{$data['mataKuliah']->id}";
        $nim = $data['mahasiswa']->mahasiswaProfile->nim;

        $this->actingAs($data['dosen'])->get($url)->assertOk()->assertSee($nim);
        $this->actingAs($data['mahasiswa'])->get($url)->assertOk()->assertDontSee($nim);
    }

    public function test_kode_mata_kuliah_terisi_otomatis_dari_kode_prodi(): void
    {
        $data = $this->buatDataUjian();
        $this->actingAs(User::factory()->create(['role' => 'kaprodi']));

        Livewire::test(CreateMataKuliah::class)
            ->fillForm(['semester' => 3, 'sks' => 2])
            ->fillForm(['prodi_id' => $data['prodi']->id])
            ->assertSchemaStateSet(['kode' => strtoupper($data['prodi']->kode).'-03-2']);
    }
}
