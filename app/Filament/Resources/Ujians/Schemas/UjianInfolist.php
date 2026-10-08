<?php

namespace App\Filament\Resources\Ujians\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UjianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ujian')
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('judul_ujian')->label('Judul Ujian'),
                        TextEntry::make('soal.mataKuliah.nama')->label('Mata Kuliah'),
                        TextEntry::make('soal.nama_soal')->label('Paket Soal'),
                        TextEntry::make('waktu_mulai')->label('Waktu Mulai')->dateTime('d M Y, H:i'),
                        TextEntry::make('waktu_selesai')->label('Waktu Selesai')->dateTime('d M Y, H:i'),
                        TextEntry::make('durasi_menit')->label('Durasi')->suffix(' menit'),
                    ]),
            ]);
    }
}
