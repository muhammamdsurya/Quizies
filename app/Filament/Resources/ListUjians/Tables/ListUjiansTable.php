<?php

namespace App\Filament\Resources\ListUjians\Tables;

use App\Models\UjianAttempt;
use App\Models\Ujians;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListUjiansTable
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
                    // Ringkasan untuk layar kecil, kolom detail disembunyikan di ponsel
                    ->description(fn (Ujians $record) => "{$record->durasi_menit} menit · s.d. {$record->waktu_selesai->translatedFormat('d M, H:i')}"),

                TextColumn::make('status_pengerjaan')
                    ->label('Status')
                    ->state(fn (Ujians $record) => $record->attemptOleh(auth()->id()) ? 'Sedang Dikerjakan' : 'Belum Dikerjakan')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Sedang Dikerjakan' ? 'warning' : 'gray')
                    ->visibleFrom('sm'),

                TextColumn::make('durasi_menit')
                    ->label('Durasi')
                    ->suffix(' menit')
                    ->visibleFrom('lg'),

                TextColumn::make('waktu_selesai')
                    ->label('Batas Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->visibleFrom('lg'),
            ])
            // Kelompokkan ujian per mata kuliah
            ->defaultGroup(Group::make('soal.mataKuliah.nama')->label('Mata Kuliah')->collapsible())
            ->defaultSort('waktu_selesai')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateHeading('Belum ada ujian yang tersedia')
            ->emptyStateDescription('Ujian akan muncul di sini ketika jadwalnya sudah dimulai.')
            ->recordActions([
                Action::make('kerjakan')
                    ->label(fn (Ujians $record) => $record->attemptOleh(auth()->id()) ? 'Lanjutkan' : 'Mulai Ujian')
                    ->color('success')
                    ->icon('heroicon-o-play')
                    ->button()
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-clock')
                    ->modalHeading(fn (Ujians $record) => $record->attemptOleh(auth()->id()) ? 'Lanjutkan Ujian?' : 'Mulai Ujian?')
                    ->modalDescription(fn (Ujians $record) => "Durasi {$record->durasi_menit} menit. Waktu tetap berjalan walaupun halaman ujian ditutup.")
                    ->modalSubmitActionLabel('Ya, kerjakan')
                    ->action(function (Ujians $record) {
                        // Record berasal dari query resource, jadi ujian pasti aktif & diikuti mahasiswa ini
                        $attempt = UjianAttempt::firstOrCreate(
                            ['user_id' => auth()->id(), 'ujian_id' => $record->id],
                            ['mulai_pada' => now()],
                        );

                        return redirect()->route('ujian.kerjakan', $attempt->id);
                    }),
            ]);
    }
}
