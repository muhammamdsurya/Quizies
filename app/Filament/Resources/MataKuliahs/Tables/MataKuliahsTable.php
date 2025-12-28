<?php

namespace App\Filament\Resources\MataKuliahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MataKuliahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prodi_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kode')
                    ->searchable(),
                    TextColumn::make('dosens.user.name') // Mengambil nama dari relasi dosens -> user
                ->label('Dosen Pengajar')
                ->listWithLineBreaks() // Menampilkan dosen berderet ke bawah
                ->searchable(),        // Agar bisa dicari berdasarkan nama dosen
                TextColumn::make('semester')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('sks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
        ->visible(fn () => in_array(auth()->user()->role, ['kaprodi', 'dosen'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
