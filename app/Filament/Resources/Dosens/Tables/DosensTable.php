<?php

namespace App\Filament\Resources\Dosens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DosensTable
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

                TextColumn::make('dosenProfile.nidn')
                    ->label('NIDN'),

                TextColumn::make('dosenProfile.prodi.nama')
                    ->label('Prodi'),

                TextColumn::make('dosenProfile.prodi.jabatan')
                    ->label('Jabatan')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dosenProfile.prodi.status_aktif')
                    ->label('Status')
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
                DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()?->role === 'kaprodi'),
            ]),
            ]);
    }
}
