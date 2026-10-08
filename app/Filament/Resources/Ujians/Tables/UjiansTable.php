<?php

namespace App\Filament\Resources\Ujians\Tables;

use App\Models\Ujians;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UjiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with('soal.mataKuliah')
                ->withCount([
                    'attempts',
                    // Attempt selesai yang skornya masih null = ada esai yang belum dinilai
                    'attempts as perlu_dinilai_count' => fn (Builder $q) => $q->whereNotNull('selesai_pada')->whereNull('skor_akhir'),
                ]))
            ->columns([
                TextColumn::make('judul_ujian')
                    ->label('Judul Ujian')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Ujians $record) => $record->soal?->mataKuliah?->nama),

                TextColumn::make('waktu_mulai')
                    ->label('Mulai')
                    ->dateTime('d M Y, H:i') // Format: 09 Jan 2026, 10:00
                    ->sortable()
                    ->visibleFrom('lg'),

                TextColumn::make('waktu_selesai')
                    ->label('Selesai')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->visibleFrom('md'),

                // Badge Status untuk memudahkan Dosen/Kaprodi melihat kondisi ujian
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Ujians $record) => match (true) {
                        now()->lessThan($record->waktu_mulai) => 'Mendatang',
                        $record->isSudahBerakhir() => 'Selesai',
                        default => 'Aktif',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Mendatang' => 'info',
                        'Selesai' => 'gray',
                        default => 'success',
                    }),

                TextColumn::make('durasi_menit')
                    ->label('Durasi')
                    ->suffix(' menit')
                    ->visibleFrom('md'),

                TextColumn::make('attempts_count')
                    ->label('Peserta')
                    ->description(fn (Ujians $record) => $record->perlu_dinilai_count ? "{$record->perlu_dinilai_count} perlu dinilai" : null)
                    ->sortable(),
            ])
            ->defaultSort('waktu_mulai', 'desc')
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
