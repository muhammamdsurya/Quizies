<?php

namespace App\Filament\Resources\ListUjians\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use App\Models\UjianAttempt;
use Illuminate\Support\Facades\Auth;

class ListUjiansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Judul Ujian
                TextColumn::make('judul_ujian')
                    ->label('Ujian')
                    ->searchable(),

                // Nama Mata Kuliah melalui relasi
                TextColumn::make('soal.mataKuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable(),

                // Status Pengerjaan (Pasti 'Sudah' karena ini riwayat)
                TextColumn::make('status_pengerjaan')
                    ->label('Status')
                    ->state(function ($record) {
                        $exists = UjianAttempt::where('user_id', Auth::id())
                            ->where('ujian_id', $record->id)
                            ->exists();
                        return $exists ? 'Sudah Dikerjakan' : 'Waktu Habis';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sudah Dikerjakan' => 'success',
                        'Waktu Habis' => 'danger',
                        default => 'gray',
                    }),

                // Durasi
                TextColumn::make('durasi_menit')
                    ->label('Durasi')
                    ->suffix(' Menit'),

                // Batas Waktu Selesai
                TextColumn::make('waktu_selesai')
                    ->label('Batas Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                // KOLOM NILAI / SKOR AKHIR
                TextColumn::make('skor')
                    ->label('Nilai Akhir')
                    ->state(function ($record) {
                        $attempt = UjianAttempt::where('user_id', Auth::id())
                            ->where('ujian_id', $record->id)
                            ->first();

                        return $attempt ? $attempt->skor_akhir : null;
                    })
                    ->placeholder('-') // Jika tidak ada attempt (ujian terlewat)
                    ->badge()
                    ->color('info')
                    ->weight('bold'),
            ])
            // PENGATURAN AGAR TABEL MATI (READ-ONLY)
            ->actions([]) // Menghapus tombol 'kerjakan' atau tombol lainnya
            ->bulkActions([]) // Menghapus checkbox massal
            ; // Membuat baris tidak bisa diklik (tidak masuk ke View/Edit)
    }
}
