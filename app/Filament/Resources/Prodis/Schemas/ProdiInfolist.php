<?php

namespace App\Filament\Resources\Prodis\Schemas;

use App\Filament\Resources\MataKuliahs\Schemas\MataKuliahInfolist;
use App\Models\Prodi;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class ProdiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Program Studi')
                    ->icon(Heroicon::OutlinedBuildingLibrary)
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 5])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama Program Studi')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpan(['default' => 1, 'sm' => 2]),
                        TextEntry::make('kode')->label('Kode')->badge()->color('gray'),
                        TextEntry::make('jumlah_dosen')
                            ->label('Dosen')
                            ->state(fn (Prodi $record) => $record->dosenProfiles()->count())
                            ->icon(Heroicon::OutlinedAcademicCap)
                            ->suffix(' dosen'),
                        TextEntry::make('jumlah_mahasiswa')
                            ->label('Mahasiswa')
                            ->state(fn (Prodi $record) => $record->mahasiswaProfiles()->count())
                            ->icon(Heroicon::OutlinedUserGroup)
                            ->suffix(' mahasiswa'),
                    ]),

                Section::make('Mata Kuliah')
                    ->icon(Heroicon::OutlinedBookOpen)
                    ->description(fn (Prodi $record) => $record->mataKuliahs->count().' mata kuliah · '.$record->mataKuliahs->sum('sks').' SKS')
                    ->columnSpanFull()
                    ->schema([
                        MataKuliahInfolist::kartu('mataKuliahs'),
                    ]),
            ]);
    }
}
