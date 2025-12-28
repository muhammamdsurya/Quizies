<?php

namespace App\Filament\Resources\MataKuliahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MataKuliahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('prodi_id')
                    ->numeric(),
                TextEntry::make('kode'),
                TextEntry::make('semester')
                    ->numeric(),
                    TextEntry::make('dosens.user.name')
                ->label('Dosen Pengajar')
                ->badge() // Menampilkan nama dosen dalam bentuk label/badge yang rapi
                ->color('info'),
                TextEntry::make('nama'),
                TextEntry::make('sks')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
