<?php

namespace App\Filament\Resources\MakeSoals\Schemas;

use App\Models\DetailSoal;
use App\Models\Soals;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class MakeSoalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Paket Soal')
                    ->icon(Heroicon::OutlinedClipboardDocumentList)
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 4])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('nama_soal')
                            ->label('Nama Paket Soal')
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpan(['default' => 1, 'sm' => 2]),
                        TextEntry::make('mataKuliah.nama')->label('Mata Kuliah'),
                        TextEntry::make('settingSoal.tahun_akademik')->label('Tahun Akademik'),
                        TextEntry::make('jenis_soal')
                            ->label('Jenis Soal')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => strtoupper($state))
                            ->color(fn (string $state) => match ($state) {
                                'uts' => 'primary',
                                'uas' => 'success',
                                default => 'warning',
                            }),
                        TextEntry::make('tipe_soal')
                            ->label('Tipe Soal')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => $state === 'pg' ? 'Pilihan Ganda' : 'Esai')
                            ->color(fn (string $state) => $state === 'pg' ? 'info' : 'danger'),
                        TextEntry::make('jumlah_soal')
                            ->label('Jumlah Soal')
                            ->state(fn (Soals $record) => $record->detailSoals->count())
                            ->suffix(' soal'),
                        TextEntry::make('user.name')->label('Dibuat Oleh')->icon(Heroicon::OutlinedUser),
                    ]),

                Section::make('Butir Soal')
                    ->icon(Heroicon::OutlinedListBullet)
                    ->description(fn (Soals $record) => $record->tipe_soal === 'pg'
                        ? 'Opsi bertanda centang hijau adalah kunci jawaban.'
                        : null)
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('detailSoals')
                            ->hiddenLabel()
                            ->placeholder('Paket soal ini belum memiliki butir soal.')
                            ->schema([
                                TextEntry::make('pertanyaan')
                                    ->label(fn (DetailSoal $record) => 'Soal Nomor '.$record->nomor_soal)
                                    ->markdown(),

                                Grid::make(['default' => 1, 'md' => 2])
                                    ->visible(fn (DetailSoal $record) => $record->tipe_soal === 'pg')
                                    ->schema(collect(['a', 'b', 'c', 'd'])->map(fn (string $opsi) => self::opsi($opsi))->all()),

                                TextEntry::make('petunjuk_esai')
                                    ->label('Petunjuk Pengerjaan')
                                    ->icon(Heroicon::OutlinedQuestionMarkCircle)
                                    ->color('gray')
                                    ->placeholder('-')
                                    ->visible(fn (DetailSoal $record) => $record->tipe_soal === 'esai'),
                            ]),
                    ]),
            ]);
    }

    // Opsi PG; kunci jawaban ditandai centang hijau dan huruf tebal
    private static function opsi(string $opsi): TextEntry
    {
        $isKunci = fn (DetailSoal $record) => $record->kunci_jawaban === $opsi;

        return TextEntry::make('opsi_'.$opsi)
            ->label('Opsi '.strtoupper($opsi))
            ->icon(fn (DetailSoal $record) => $isKunci($record) ? Heroicon::CheckCircle : null)
            ->iconColor('success')
            ->color(fn (DetailSoal $record) => $isKunci($record) ? 'success' : null)
            ->weight(fn (DetailSoal $record) => $isKunci($record) ? FontWeight::Bold : null);
    }
}
