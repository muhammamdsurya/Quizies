<?php

namespace App\Filament\Resources\MakeSoals\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MakeSoalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['mataKuliah', 'user'])->withCount('detailSoals'))
            ->columns([
                TextColumn::make('nama_soal')
                    ->label('Paket Soal')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn ($record) => $record->mataKuliah?->nama),

                // Menampilkan nama Dosen (User) dari relasi user_id
                TextColumn::make('user.name')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable()
                    ->visibleFrom('md'),

                // Menampilkan Jenis Soal (UTS/UAS/Kuis) dengan huruf besar
                TextColumn::make('jenis_soal')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('tipe_soal')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state) => $state === 'pg' ? 'info' : 'danger')
                    ->formatStateUsing(fn (string $state): string => $state === 'pg' ? 'Pilihan Ganda' : 'Esai'),

                TextColumn::make('detail_soals_count')
                    ->label('Jumlah Soal')
                    ->suffix(' soal')
                    ->sortable(),

                // Menampilkan Tanggal saja (tanpa jam)
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->date('d F Y') // Format: 08 Januari 2026
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
