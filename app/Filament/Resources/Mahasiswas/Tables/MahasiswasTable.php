<?php

namespace App\Filament\Resources\Mahasiswas\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MahasiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('mahasiswaProfile.nim')
                    ->label('NIM'),

                TextColumn::make('mahasiswaProfile.prodi.nama')
                    ->label('Prodi'),

                TextColumn::make('mahasiswaProfile.semester')
                    ->label('Semester')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('mahasiswaProfile.status_aktif')
                    ->label('Status')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            // Aksi dikelompokkan dalam menu agar tabel tetap ringkas di layar kecil
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
