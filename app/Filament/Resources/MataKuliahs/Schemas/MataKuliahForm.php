<?php

namespace App\Filament\Resources\MataKuliahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MataKuliahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('prodi_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kode')
                    ->required(),
                TextInput::make('semester')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('sks')
                    ->required()
                    ->numeric()
                    ->default(3),
            ]);
    }
}
