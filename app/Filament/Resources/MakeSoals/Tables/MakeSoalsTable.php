<?php

namespace App\Filament\Resources\MakeSoals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MakeSoalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            // Menampilkan nama Mata Kuliah dari relasi
            TextColumn::make('mataKuliah.nama')
                ->label('Mata Kuliah')
                ->searchable()
                ->sortable(),

            // Menampilkan nama Dosen (User) dari relasi user_id
            TextColumn::make('user.name')
                ->label('Dosen')
                ->searchable()
                ->sortable(),

            // Menampilkan Jenis Soal (UTS/UAS) dengan format huruf besar di awal
            TextColumn::make('jenis_soal')
                ->label('Jenis Soal')
                ->badge() // Membuat tampilan seperti label/badge
                ->formatStateUsing(fn (string $state): string => strtoupper($state)),

            // Menampilkan Tanggal saja (tanpa jam)
            TextColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->date('d F Y') // Format: 08 Januari 2026
                ->sortable(),
        ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
