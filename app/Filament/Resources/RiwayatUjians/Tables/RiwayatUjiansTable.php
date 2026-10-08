<?php

namespace App\Filament\Resources\RiwayatUjians\Tables;

use App\Models\Ujians;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RiwayatUjiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'soal.mataKuliah',
                'attempts' => fn ($q) => $q->where('user_id', auth()->id()),
            ]))
            ->columns([
                TextColumn::make('judul_ujian')
                    ->label('Ujian')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Ujians $record) => $record->soal?->mataKuliah?->nama),

                TextColumn::make('status_pengerjaan')
                    ->label('Status')
                    ->state(fn (Ujians $record) => $record->attemptOleh(auth()->id())?->isSelesai() ? 'Selesai' : 'Tidak Dikerjakan')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Selesai' ? 'success' : 'danger')
                    ->visibleFrom('sm'),

                TextColumn::make('waktu_selesai')
                    ->label('Batas Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->visibleFrom('lg'),

                TextColumn::make('skor')
                    ->label('Nilai Akhir')
                    ->state(function (Ujians $record) {
                        $attempt = $record->attemptOleh(auth()->id());

                        return $attempt?->isMenungguPenilaian() ? 'Menunggu Penilaian' : $attempt?->skor_akhir;
                    })
                    ->placeholder('-') // Ujian terlewat / tidak dikerjakan
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        ! is_int($state) => 'gray',
                        $state >= 75 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->weight('bold'),
            ])
            ->defaultSort('waktu_selesai', 'desc')
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateHeading('Belum ada riwayat ujian')
            ->emptyStateDescription('Ujian yang sudah Anda kumpulkan akan tampil di sini.')
            ->recordActions([
                ViewAction::make()->label('Lihat Hasil'),
            ]);
    }
}
