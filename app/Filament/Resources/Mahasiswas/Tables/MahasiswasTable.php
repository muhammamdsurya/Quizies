<?php

namespace App\Filament\Resources\Mahasiswas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

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
                    ->label('semester')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('mahasiswaProfile.status_aktif')
                    ->label('status')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                ->visible(fn () => auth()->user()?->role === 'kaprodi'),

                 DeleteAction::make()
                ->visible(fn () => auth()->user()?->role === 'kaprodi')
                ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
