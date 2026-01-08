<?php

namespace App\Filament\Resources\SettingSoals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class SettingSoalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun_akademik')
                ->label('Tahun Akademik')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->color('primary'),

            TextColumn::make('jenis_soal_options')
                ->label('Opsi Jenis')
                ->badge() // Mengubah array menjadi badge terpisah
                ->separator(',') // Memisahkan jika data disimpan sebagai string koma
                ->color('info')
                ->formatStateUsing(fn (string $state): string => strtoupper($state)),

            TextColumn::make('tipe_soal_options')
                ->label('Opsi Tipe')
                ->badge()
                ->color('success')
               ->formatStateUsing(fn (string $state): string => strtoupper($state)),

            IconColumn::make('is_active')
                ->label('Status')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),

            TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
