<?php

namespace App\Filament\Resources\RiwayatUjians\Schemas;

use App\Models\DetailSoal;
use App\Models\JawabanMahasiswa;
use App\Models\UjianAttempt;
use App\Models\Ujians;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RiwayatUjianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ujian')
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 4])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('judul_ujian')->label('Nama Ujian'),
                        TextEntry::make('soal.mataKuliah.nama')->label('Mata Kuliah'),
                        TextEntry::make('selesai_dikerjakan')
                            ->label('Dikumpulkan')
                            ->state(fn (Ujians $record) => self::attempt($record)?->selesai_pada)
                            ->dateTime('d M Y, H:i')
                            ->placeholder('Tidak dikerjakan'),
                        TextEntry::make('nilai_akhir')
                            ->label('Nilai Akhir')
                            ->state(function (Ujians $record) {
                                $attempt = self::attempt($record);

                                return $attempt?->isMenungguPenilaian() ? 'Menunggu Penilaian' : $attempt?->skor_akhir;
                            })
                            ->placeholder('-')
                            ->badge()
                            ->size('lg')
                            ->color(fn ($state) => is_int($state) ? 'success' : 'gray'),
                    ]),

                Section::make('Detail Jawaban Anda')
                    ->description(fn (Ujians $record) => $record->isSudahBerakhir()
                        ? null
                        : 'Kunci jawaban ditampilkan setelah jadwal ujian berakhir.')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('detailSoals') // Relasi ke butir soal
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('pertanyaan')
                                    ->label(fn (DetailSoal $record) => 'Soal Nomor '.$record->nomor_soal)
                                    ->markdown(),

                                TextEntry::make('jawaban_mahasiswa')
                                    ->label('Jawaban Anda')
                                    ->state(function (DetailSoal $record, $livewire) {
                                        $jawaban = self::jawaban($record, $livewire->getRecord())?->jawaban;

                                        if (blank($jawaban)) {
                                            return 'Tidak Dijawab';
                                        }

                                        return $record->tipe_soal === 'pg'
                                            ? strtoupper($jawaban).'. '.$record->{'opsi_'.$jawaban}
                                            : $jawaban;
                                    })
                                    ->color(fn ($state) => $state === 'Tidak Dijawab' ? 'danger' : null),

                                TextEntry::make('hasil')
                                    ->label('Hasil')
                                    ->state(fn (DetailSoal $record, $livewire) => self::jawaban($record, $livewire->getRecord())?->jawaban === $record->kunci_jawaban ? 'Benar' : 'Salah')
                                    ->badge()
                                    ->color(fn (string $state) => $state === 'Benar' ? 'success' : 'danger')
                                    ->visible(fn (DetailSoal $record, $livewire) => $record->tipe_soal === 'pg' && $livewire->getRecord()->isSudahBerakhir()),

                                TextEntry::make('kunci_jawaban')
                                    ->label('Kunci Jawaban')
                                    ->formatStateUsing(fn (string $state, DetailSoal $record) => strtoupper($state).'. '.$record->{'opsi_'.$state})
                                    ->visible(fn (DetailSoal $record, $livewire) => $record->tipe_soal === 'pg' && $livewire->getRecord()->isSudahBerakhir()),

                                TextEntry::make('nilai_esai')
                                    ->label('Nilai Esai')
                                    ->state(fn (DetailSoal $record, $livewire) => self::jawaban($record, $livewire->getRecord())?->nilai_esai)
                                    ->placeholder('Belum dinilai')
                                    ->badge()
                                    ->visible(fn (DetailSoal $record) => $record->tipe_soal === 'esai'),

                                TextEntry::make('catatan_dosen')
                                    ->label('Catatan Dosen')
                                    ->state(fn (DetailSoal $record, $livewire) => self::jawaban($record, $livewire->getRecord())?->catatan_dosen)
                                    ->visible(fn (DetailSoal $record, $livewire) => $record->tipe_soal === 'esai' && filled(self::jawaban($record, $livewire->getRecord())?->catatan_dosen)),
                            ]),
                    ]),
            ]);
    }

    private static function attempt(Ujians $ujian): ?UjianAttempt
    {
        return $ujian->attemptOleh(auth()->id());
    }

    // Jawaban mahasiswa untuk soal ini pada attempt ujian INI (paket soal bisa dipakai beberapa ujian)
    private static function jawaban(DetailSoal $soal, Ujians $ujian): ?JawabanMahasiswa
    {
        return self::attempt($ujian)?->jawabanMahasiswas->firstWhere('detail_soal_id', $soal->id);
    }
}
