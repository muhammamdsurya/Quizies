<?php

namespace App\Filament\Resources\SettingSoals\Schemas;

use App\Models\SettingSoal;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class SettingSoalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konfigurasi Ujian')
                    ->icon(Heroicon::OutlinedCog6Tooth)
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('tahun_akademik')
                            ->label('Tahun Akademik')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (bool $state) => $state ? 'Aktif' : 'Nonaktif')
                            ->color(fn (bool $state) => $state ? 'success' : 'gray'),
                        TextEntry::make('jumlah_paket')
                            ->label('Dipakai oleh')
                            ->state(fn (SettingSoal $record) => $record->soals()->count())
                            ->suffix(' paket soal'),
                        TextEntry::make('jenis_soal_options')
                            ->label('Jenis Soal yang Diizinkan')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => strtoupper($state))
                            ->color(fn (string $state) => match ($state) {
                                'uts' => 'primary',
                                'uas' => 'success',
                                default => 'warning',
                            }),
                        TextEntry::make('tipe_soal_options')
                            ->label('Tipe Soal yang Tersedia')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => $state === 'pg' ? 'Pilihan Ganda' : 'Esai')
                            ->color(fn (string $state) => $state === 'pg' ? 'info' : 'danger'),
                    ]),
            ]);
    }
}
