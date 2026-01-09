<?php

namespace App\Filament\Resources\Ujians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class UjiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            // Mengambil nama mata kuliah melalui relasi soal -> mataKuliah
            TextColumn::make('soal.mataKuliah.nama')
                ->label('Mata Kuliah')
                ->searchable()
                ->sortable(),

            TextColumn::make('judul_ujian')
                ->label('Judul Ujian')
                ->searchable(),

           TextColumn::make('waktu_mulai')
                ->label('Mulai')
                ->dateTime('d M Y, H:i') // Format: 09 Jan 2026, 10:00
                ->sortable(),

           TextColumn::make('waktu_selesai')
                ->label('Selesai')
                ->dateTime('d M Y, H:i')
                ->sortable(),

            // Tambahan: Badge Status untuk memudahkan Dosen/Kaprodi melihat kondisi ujian
           TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->getStateUsing(function ($record) {
    $now = now();

    // Debugging: baris ini akan memunculkan waktu sekarang dan waktu mulai di layar (opsional)
    // dd($now, $record->waktu_mulai);

    if ($now->lessThan($record->waktu_mulai)) return 'Mendatang';
    if ($now->greaterThan($record->waktu_selesai)) return 'Selesai';

    return 'Aktif';
})
                ->color(fn (string $state): string => match ($state) {
                    'Mendatang' => 'info',
                    'Selesai' => 'danger',
                    'Aktif' => 'success',
                }),

            // Menampilkan durasi
           TextColumn::make('durasi_menit')
                ->label('Durasi')
                ->suffix(' Menit'),
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
