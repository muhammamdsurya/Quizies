<?php

namespace App\Filament\Resources\Mahasiswas\Schemas;

use App\Filament\Resources\MataKuliahs\Schemas\MataKuliahInfolist;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class MahasiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->icon(Heroicon::OutlinedUser)
                    ->columns(['default' => 1, 'sm' => 2])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold),
                        TextEntry::make('email')
                            ->label('Alamat Email')
                            ->icon(Heroicon::OutlinedEnvelope)
                            ->copyable(),
                    ]),

                Section::make('Detail Profil')
                    ->icon(Heroicon::OutlinedIdentification)
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 5])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('mahasiswaProfile.nim')
                            ->label('NIM')
                            ->fontFamily(FontFamily::Mono)
                            ->copyable()
                            ->placeholder('-'),
                        TextEntry::make('mahasiswaProfile.prodi.nama')->label('Program Studi')->placeholder('-'),
                        TextEntry::make('mahasiswaProfile.semester')->label('Semester')->prefix('Semester ')->placeholder('-'),
                        TextEntry::make('mahasiswaProfile.tanggal_masuk')->label('Tanggal Masuk')->date('d F Y')->placeholder('-'),
                        TextEntry::make('mahasiswaProfile.status_aktif')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => ucfirst($state))
                            ->color(fn (string $state) => $state === 'aktif' ? 'success' : 'danger')
                            ->placeholder('-'),
                    ]),

                Section::make('Mata Kuliah yang Diambil')
                    ->icon(Heroicon::OutlinedBookOpen)
                    ->description(fn (User $record) => self::ringkasan($record))
                    ->columnSpanFull()
                    ->schema([
                        MataKuliahInfolist::kartu('mahasiswaProfile.mataKuliahs'),
                    ]),
            ]);
    }

    private static function ringkasan(User $record): string
    {
        $mataKuliah = $record->mahasiswaProfile?->mataKuliahs ?? collect();

        return $mataKuliah->count().' mata kuliah · '.$mataKuliah->sum('sks').' SKS';
    }
}
