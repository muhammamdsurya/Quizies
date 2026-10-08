<?php

namespace App\Filament\Resources\MataKuliahs\Schemas;

use App\Models\MataKuliah;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class MataKuliahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mata Kuliah')
                    ->icon(Heroicon::OutlinedBookOpen)
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 4])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama Mata Kuliah')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpan(['default' => 1, 'sm' => 2]),
                        TextEntry::make('kode')->label('Kode')->badge()->color('gray')->copyable(),
                        TextEntry::make('prodi.nama')->label('Program Studi'),
                        TextEntry::make('semester')->label('Semester')->prefix('Semester '),
                        TextEntry::make('sks')->label('Beban')->suffix(' SKS')->badge()->color('info'),
                        TextEntry::make('mahasiswas_count')
                            ->label('Jumlah Mahasiswa')
                            ->state(fn (MataKuliah $record) => $record->mahasiswas()->count())
                            ->suffix(' mahasiswa'),
                    ]),

                Section::make('Dosen Pengampu')
                    ->icon(Heroicon::OutlinedAcademicCap)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('dosens.user.name')
                            ->hiddenLabel()
                            ->badge()
                            ->color('primary')
                            ->placeholder('Belum ada dosen pengampu.'),
                    ]),

                // Daftar nama + NIM hanya untuk Kaprodi/Dosen (tidak dibuka ke sesama mahasiswa)
                Section::make('Mahasiswa Terdaftar')
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->collapsible()
                    ->columnSpanFull()
                    ->visible(fn () => auth()->user()->hasRole('kaprodi', 'dosen'))
                    ->schema([
                        RepeatableEntry::make('mahasiswas')
                            ->hiddenLabel()
                            ->grid(['default' => 1, 'sm' => 2, 'xl' => 3])
                            ->placeholder('Belum ada mahasiswa yang mengambil mata kuliah ini.')
                            ->schema([
                                TextEntry::make('user.name')->hiddenLabel()->weight(FontWeight::SemiBold),
                                TextEntry::make('nim')->hiddenLabel()->prefix('NIM ')->color('gray'),
                            ]),
                    ]),
            ]);
    }

    /**
     * Daftar mata kuliah dalam bentuk kartu (dipakai di halaman Mahasiswa, Dosen, dan Prodi).
     */
    public static function kartu(string $relasi): RepeatableEntry
    {
        return RepeatableEntry::make($relasi)
            ->hiddenLabel()
            ->grid(['default' => 1, 'md' => 2, 'xl' => 3])
            ->placeholder('Belum ada mata kuliah.')
            ->schema([
                TextEntry::make('nama')
                    ->hiddenLabel()
                    ->weight(FontWeight::Bold)
                    ->size(TextSize::Large),

                Flex::make([
                    TextEntry::make('kode')->hiddenLabel()->badge()->color('gray')->grow(false),
                    TextEntry::make('sks')->hiddenLabel()->suffix(' SKS')->badge()->color('info')->grow(false),
                    TextEntry::make('semester')->hiddenLabel()->prefix('Semester ')->badge()->color('warning')->grow(false),
                ]),

                TextEntry::make('dosens.user.name')
                    ->label('Dosen Pengampu')
                    ->listWithLineBreaks()
                    ->icon(Heroicon::OutlinedAcademicCap)
                    ->placeholder('Belum ada dosen'),
            ]);
    }
}
