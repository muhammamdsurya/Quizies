<?php

namespace Tests;

use App\Models\DetailSoal;
use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\SettingSoal;
use App\Models\Soals;
use App\Models\Ujians;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    /**
     * Satu mata kuliah lengkap: dosen pengampu, mahasiswa terdaftar, paket soal, dan ujian yang sedang berlangsung.
     * PG berisi 2 soal (kunci: b, a); esai berisi 1 soal.
     */
    protected function buatDataUjian(string $tipe = 'pg', array $ujian = []): array
    {
        $prodi = Prodi::factory()->create();
        $mataKuliah = MataKuliah::factory()->create(['prodi_id' => $prodi->id, 'kode' => 'MK-'.Str::random(8)]);

        $dosen = DosenProfile::factory()->create(['prodi_id' => $prodi->id]);
        $mataKuliah->dosens()->attach($dosen);

        $mahasiswa = MahasiswaProfile::factory()->create(['prodi_id' => $prodi->id, 'status_aktif' => 'aktif']);
        $mahasiswa->mataKuliahs()->attach($mataKuliah);

        $setting = SettingSoal::create([
            'tahun_akademik' => '2025/2026',
            'jenis_soal_options' => ['uts', 'uas', 'kuis'],
            'tipe_soal_options' => ['pg', 'esai'],
            'is_active' => true,
        ]);

        $soal = Soals::create([
            'setting_soal_id' => $setting->id,
            'mata_kuliah_id' => $mataKuliah->id,
            'user_id' => $dosen->user_id,
            'nama_soal' => 'Paket '.$tipe,
            'jenis_soal' => 'kuis',
            'tipe_soal' => $tipe,
        ]);

        if ($tipe === 'pg') {
            DetailSoal::create(['soals_id' => $soal->id, 'pertanyaan' => 'Berapa 2 + 2?', 'opsi_a' => '3', 'opsi_b' => '4', 'opsi_c' => '5', 'opsi_d' => '6', 'kunci_jawaban' => 'b']);
            DetailSoal::create(['soals_id' => $soal->id, 'pertanyaan' => 'Warna langit cerah?', 'opsi_a' => 'Biru', 'opsi_b' => 'Merah', 'opsi_c' => 'Hijau', 'opsi_d' => 'Kuning', 'kunci_jawaban' => 'a']);
        } else {
            DetailSoal::create(['soals_id' => $soal->id, 'pertanyaan' => 'Jelaskan konsep OOP.', 'petunjuk_esai' => 'Minimal satu paragraf.']);
        }

        $ujian = Ujians::create(array_merge([
            'judul_ujian' => 'Ujian '.$tipe,
            'user_id' => $dosen->user_id,
            'soals_id' => $soal->id,
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now()->addDay(),
            'durasi_menit' => 30,
        ], $ujian));

        return [
            'prodi' => $prodi,
            'mataKuliah' => $mataKuliah,
            'soal' => $soal,
            'ujian' => $ujian,
            'dosen' => $dosen->user,
            'mahasiswa' => $mahasiswa->user,
        ];
    }
}
