<?php

namespace App\Filament\Resources\RiwayatUjians\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Components\TextEntry;

use App\Models\JawabanMahasiswa;

class RiwayatUjianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ujian')
                ->schema([
                    TextEntry::make('nama_soal')->label('Nama Ujian'),
                    TextEntry::make('mataKuliah.nama_matkul')->label('Mata Kuliah'),
                ])->columns(2),

            Section::make('Detail Jawaban Anda')
                ->schema([
                    RepeatableEntry::make('detailSoals') // Relasi ke butir soal
                        ->label('Daftar Soal & Jawaban')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('pertanyaan')
                                        ->label(fn ($record, $component) => "Pertanyaan Nomor " . $record->nomor_soal)
                                        ->markdown(),

                                    // Menampilkan Jawaban Mahasiswa
                                    TextEntry::make('jawaban_mahasiswa')
                                        ->label('Jawaban Anda:')
                                        ->getStateUsing(function ($record) {
                                            // Cari jawaban mahasiswa di tabel jawaban_mahasiswa
                                            // Berdasarkan user yang sedang login dan ID soal ini
                                            $jawaban = \App\Models\JawabanMahasiswa::where('detail_soal_id', $record->id)
                                                ->whereHas('attempt', function ($q) {
                                                    $q->where('user_id', auth()->id());
                                                })->first();

                                            return $jawaban ? $jawaban->jawaban : 'Tidak Dijawab';
                                        })
                                        ->badge()
                                        ->color(fn ($state) => $state === 'Tidak Dijawab' ? 'danger' : 'success'),

                                    // Opsional: Tampilkan Kunci Jawaban jika tipe soal PG
                                    TextEntry::make('kunci_jawaban')
                                        ->label('Kunci Jawaban:')
                                        ->visible(fn ($record) => $record->tipe_soal === 'pg'),
                                ])
                        ])
                ])
            ]);
    }
}
