<?php

namespace App\Filament\Resources\Ujians\RelationManagers;

use App\Models\JawabanMahasiswa;
use App\Models\UjianAttempt;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';

    protected static ?string $title = 'Hasil Mahasiswa';

    protected static ?string $modelLabel = 'hasil';

    // Dosen perlu menilai esai langsung dari halaman detail ujian
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('user'))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('mulai_pada')
                    ->label('Mulai')
                    ->dateTime('d M Y, H:i')
                    ->visibleFrom('md'),
                TextColumn::make('selesai_pada')
                    ->label('Dikumpulkan')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Sedang mengerjakan'),
                TextColumn::make('skor_akhir')
                    ->label('Nilai')
                    ->state(fn (UjianAttempt $record) => $record->isMenungguPenilaian() ? 'Perlu Dinilai' : $record->skor_akhir)
                    ->placeholder('-')
                    ->badge()
                    ->color(fn ($state) => is_int($state) ? 'success' : 'warning')
                    ->sortable(),
            ])
            ->defaultSort('selesai_pada', 'desc')
            ->emptyStateHeading('Belum ada mahasiswa yang mengerjakan')
            ->recordActions([
                EditAction::make('nilai')
                    ->label('Nilai Esai')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn (UjianAttempt $record) => 'Penilaian Esai: '.$record->user->name)
                    ->modalSubmitActionLabel('Simpan Nilai')
                    ->visible(fn (UjianAttempt $record) => $record->isSelesai()
                        && $this->getOwnerRecord()->soal->tipe_soal === 'esai')
                    ->schema([
                        Repeater::make('jawabanMahasiswas')
                            ->relationship(modifyQueryUsing: fn (Builder $query) => $query
                                ->whereHas('detailSoal', fn (Builder $q) => $q->where('tipe_soal', 'esai'))
                                ->whereNotNull('jawaban'))
                            ->hiddenLabel()
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->schema([
                                TextEntry::make('pertanyaan')
                                    ->label(fn (?JawabanMahasiswa $record) => 'Soal Nomor '.$record?->detailSoal?->nomor_soal)
                                    ->state(fn (?JawabanMahasiswa $record) => $record?->detailSoal?->pertanyaan)
                                    ->markdown(),
                                TextEntry::make('jawaban')
                                    ->label('Jawaban Mahasiswa')
                                    ->state(fn (?JawabanMahasiswa $record) => $record?->jawaban),
                                TextInput::make('nilai_esai')
                                    ->label('Nilai (0 - 100)')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->required(),
                                Textarea::make('catatan_dosen')
                                    ->label('Catatan untuk Mahasiswa')
                                    ->rows(2),
                            ]),
                    ])
                    // Hitung ulang skor akhir setelah nilai esai disimpan
                    ->after(fn (UjianAttempt $record) => $record->hitungSkor()),
            ]);
    }
}
