<?php

namespace Tests\Feature;

use App\Filament\Resources\ListUjians\Pages\ListListUjians;
use App\Filament\Resources\RiwayatUjians\RiwayatUjianResource;
use App\Filament\Resources\Ujians\Pages\ViewUjian;
use App\Filament\Resources\Ujians\RelationManagers\AttemptsRelationManager;
use App\Livewire\KerjakanUjian;
use App\Models\DetailSoal;
use App\Models\JawabanMahasiswa;
use App\Models\UjianAttempt;
use App\Models\Ujians;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UjianFlowTest extends TestCase
{
    use RefreshDatabase;

    private function mulai(array $data, array $attrs = []): UjianAttempt
    {
        return UjianAttempt::create(array_merge([
            'user_id' => $data['mahasiswa']->id,
            'ujian_id' => $data['ujian']->id,
            'mulai_pada' => now(),
        ], $attrs));
    }

    public function test_mahasiswa_hanya_melihat_ujian_aktif_dari_matkul_yang_diambil(): void
    {
        $data = $this->buatDataUjian();
        $lain = $this->buatDataUjian(); // matkul lain yang tidak diambil
        $lampau = Ujians::create(['judul_ujian' => 'Lampau', 'user_id' => $data['dosen']->id, 'soals_id' => $data['soal']->id, 'waktu_mulai' => now()->subDays(3), 'waktu_selesai' => now()->subDays(2), 'durasi_menit' => 30]);

        $this->actingAs($data['mahasiswa']);

        Livewire::test(ListListUjians::class)
            ->assertCanSeeTableRecords([$data['ujian']])
            ->assertCanNotSeeTableRecords([$lain['ujian'], $lampau]);
    }

    public function test_mulai_ujian_membuat_satu_attempt_dan_bisa_dilanjutkan(): void
    {
        $data = $this->buatDataUjian();
        $this->actingAs($data['mahasiswa']);

        Livewire::test(ListListUjians::class)
            ->callTableAction('kerjakan', $data['ujian'])
            ->assertRedirect(route('ujian.kerjakan', UjianAttempt::first()->id));

        // Klik lagi (misal setelah tab tertutup) melanjutkan attempt yang sama
        Livewire::test(ListListUjians::class)->callTableAction('kerjakan', $data['ujian']);

        $this->assertSame(1, UjianAttempt::count());
    }

    public function test_jawaban_pg_dinilai_saat_dikumpulkan(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data);
        $this->actingAs($data['mahasiswa']);

        Livewire::test(KerjakanUjian::class, ['attempt_id' => $attempt->id])
            ->call('simpanJawaban', 'b') // soal 1 benar
            ->call('nextSoal')
            ->call('simpanJawaban', 'c') // soal 2 salah
            ->call('simpanJawaban', 'x') // opsi tidak valid diabaikan
            ->assertSet('jawabanDipilih', 'c')
            ->call('finish')
            ->assertRedirect(RiwayatUjianResource::getUrl('view', ['record' => $data['ujian']->id]));

        $attempt->refresh();
        $this->assertNotNull($attempt->selesai_pada);
        $this->assertSame(50, $attempt->skor_akhir);
    }

    public function test_navigasi_soal_tidak_bisa_keluar_batas(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data);
        $this->actingAs($data['mahasiswa']);

        Livewire::test(KerjakanUjian::class, ['attempt_id' => $attempt->id])
            ->call('goTo', 99)
            ->assertSet('currentSoalIndex', 0)
            ->call('prevSoal')
            ->assertSet('currentSoalIndex', 0)
            ->call('goTo', 1)
            ->assertSet('currentSoalIndex', 1);
    }

    public function test_sisa_waktu_dihitung_dari_waktu_mulai_bukan_direset_saat_reload(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data, ['mulai_pada' => now()->subMinutes(10)]); // durasi 30 menit
        $this->actingAs($data['mahasiswa']);

        Livewire::test(KerjakanUjian::class, ['attempt_id' => $attempt->id])
            ->assertViewHas('sisaDetik', fn ($detik) => $detik > 1190 && $detik <= 1200);
    }

    public function test_sisa_waktu_tidak_melewati_jadwal_selesai_ujian(): void
    {
        $data = $this->buatDataUjian('pg', ['waktu_selesai' => now()->addMinutes(5), 'durasi_menit' => 60]);
        $attempt = $this->mulai($data);
        $this->actingAs($data['mahasiswa']);

        Livewire::test(KerjakanUjian::class, ['attempt_id' => $attempt->id])
            ->assertViewHas('sisaDetik', fn ($detik) => $detik <= 300);
    }

    public function test_jawaban_ditolak_dan_ujian_dikumpulkan_setelah_waktu_habis(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data);
        $this->actingAs($data['mahasiswa']);

        $komponen = Livewire::test(KerjakanUjian::class, ['attempt_id' => $attempt->id]);

        $this->travel(31)->minutes();

        $komponen->call('simpanJawaban', 'b')->assertRedirect();

        $this->assertSame(0, JawabanMahasiswa::count());
        $this->assertNotNull($attempt->fresh()->selesai_pada);
    }

    public function test_attempt_yang_kedaluwarsa_dikumpulkan_otomatis(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data, ['mulai_pada' => now()->subMinutes(45)]);
        $this->actingAs($data['mahasiswa']);

        Livewire::test(ListListUjians::class)->assertCanNotSeeTableRecords([$data['ujian']]);

        $this->assertNotNull($attempt->fresh()->selesai_pada);
        $this->assertSame(0, $attempt->fresh()->skor_akhir);
    }

    public function test_tidak_bisa_mengerjakan_ulang_setelah_dikumpulkan(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data, ['selesai_pada' => now(), 'skor_akhir' => 100]);

        $this->actingAs($data['mahasiswa'])
            ->get(route('ujian.kerjakan', $attempt->id))
            ->assertRedirect(RiwayatUjianResource::getUrl('view', ['record' => $data['ujian']->id]));

        $this->assertSame(100, $attempt->fresh()->skor_akhir);
    }

    public function test_attempt_milik_mahasiswa_lain_ditolak(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data);
        $lain = $this->buatDataUjian();

        $this->actingAs($lain['mahasiswa'])
            ->get(route('ujian.kerjakan', $attempt->id))
            ->assertRedirect('/admin'); // 403 diarahkan ke dashboard dengan notifikasi
    }

    public function test_pertanyaan_dirender_sebagai_markdown_tanpa_html_mentah(): void
    {
        $data = $this->buatDataUjian();
        DetailSoal::where('soals_id', $data['soal']->id)->where('nomor_soal', 1)
            ->update(['pertanyaan' => "**Tebal** <script>alert('xss')</script>"]);
        $attempt = $this->mulai($data);

        $this->actingAs($data['mahasiswa'])
            ->get(route('ujian.kerjakan', $attempt->id))
            ->assertOk()
            ->assertSee('<strong>Tebal</strong>', false)
            ->assertDontSee("<script>alert('xss')</script>", false);
    }

    public function test_esai_menunggu_penilaian_lalu_dinilai_dosen(): void
    {
        $data = $this->buatDataUjian('esai');
        $attempt = $this->mulai($data);

        $this->actingAs($data['mahasiswa']);
        Livewire::test(KerjakanUjian::class, ['attempt_id' => $attempt->id])
            ->set('jawabanDipilih', 'OOP adalah paradigma berbasis objek.')
            ->call('finish');

        $attempt->refresh();
        $this->assertTrue($attempt->isMenungguPenilaian());

        $jawaban = JawabanMahasiswa::sole();
        $this->assertSame('OOP adalah paradigma berbasis objek.', $jawaban->jawaban);

        $this->actingAs($data['dosen']);
        Livewire::test(AttemptsRelationManager::class, ['ownerRecord' => $data['ujian'], 'pageClass' => ViewUjian::class])
            ->assertTableActionVisible('nilai', $attempt)
            ->callTableAction('nilai', $attempt, data: [
                'jawabanMahasiswas' => ["record-{$jawaban->id}" => ['nilai_esai' => 80, 'catatan_dosen' => 'Bagus']],
            ])
            ->assertHasNoTableActionErrors();

        $this->assertSame(80, $attempt->fresh()->skor_akhir);
        $this->assertSame('Bagus', $jawaban->fresh()->catatan_dosen);
    }

    public function test_riwayat_menyembunyikan_kunci_sampai_jadwal_ujian_berakhir(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data);
        JawabanMahasiswa::create(['ujian_attempt_id' => $attempt->id, 'detail_soal_id' => DetailSoal::first()->id, 'jawaban' => 'b']);
        $attempt->selesaikan();

        $url = RiwayatUjianResource::getUrl('view', ['record' => $data['ujian']->id]);
        $this->actingAs($data['mahasiswa']);

        $this->get($url)->assertOk()->assertSee('B. 4')->assertDontSee('Kunci Jawaban');

        $this->travel(2)->days();

        $this->get($url)->assertOk()->assertSee('Kunci Jawaban');
    }

    public function test_riwayat_tidak_mencampur_jawaban_dari_ujian_lain_dengan_paket_soal_sama(): void
    {
        $data = $this->buatDataUjian();
        $attempt = $this->mulai($data);
        JawabanMahasiswa::create(['ujian_attempt_id' => $attempt->id, 'detail_soal_id' => DetailSoal::first()->id, 'jawaban' => 'b']);
        $attempt->selesaikan();

        // Ujian lain yang sudah lewat memakai paket soal yang sama, tidak dikerjakan
        $lampau = Ujians::create(['judul_ujian' => 'Remedial', 'user_id' => $data['dosen']->id, 'soals_id' => $data['soal']->id, 'waktu_mulai' => now()->subDays(3), 'waktu_selesai' => now()->subDays(2), 'durasi_menit' => 30]);

        $this->actingAs($data['mahasiswa'])
            ->get(RiwayatUjianResource::getUrl('view', ['record' => $lampau->id]))
            ->assertOk()
            ->assertSee('Tidak Dijawab')
            ->assertDontSee('Benar'); // jawaban 'b' dari ujian lain tidak boleh terhitung
    }
}
