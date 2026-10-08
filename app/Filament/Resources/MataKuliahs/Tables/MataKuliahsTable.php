<?php

namespace App\Filament\Resources\MataKuliahs\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Mata Kuliah')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('prodi.nama')
                    ->label('Program Studi')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('dosens.user.name') // Mengambil nama dari relasi dosens -> user
                    ->label('Dosen Pengajar')
                    ->listWithLineBreaks() // Menampilkan dosen berderet ke bawah
                    ->searchable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sks')
                    ->label('SKS')
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
            ->defaultSort('kode')
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
