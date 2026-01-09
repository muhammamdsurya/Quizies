<?php

namespace App\Filament\Resources\ListUjians;

use App\Filament\Resources\ListUjians\Pages\CreateListUjian;
use App\Filament\Resources\ListUjians\Pages\EditListUjian;
use App\Filament\Resources\ListUjians\Pages\ListListUjians;
use App\Filament\Resources\ListUjians\Pages\ViewListUjian;
use App\Filament\Resources\ListUjians\Schemas\ListUjianForm;
use App\Filament\Resources\ListUjians\Schemas\ListUjianInfolist;
use App\Filament\Resources\ListUjians\Tables\ListUjiansTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\Ujians;
use Filament\Actions\Action;
use App\Models\UjianAttempt;
use BackedEnum;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ListUjianResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Ujians::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Ujian Tersedia';
protected static ?string $slug = 'daftar-ujian-mahasiswa'; // Slug harus unik

    protected static ?string $recordTitleAttribute = 'ListUjian';

    public static function shouldRegisterNavigation(): bool
{
    return auth()->user()->role === 'mahasiswa';
}

    public static function getEloquentQuery(): Builder
{
    // Hanya tampilkan ujian yang sedang berlangsung (Mulai <= Sekarang <= Selesai)
    return parent::getEloquentQuery()
        ->where('waktu_mulai', '<=', now())
        ->where('waktu_selesai', '>=', now());
}

    public static function form(Schema $schema): Schema
    {
        return ListUjianForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ListUjianInfolist::configure($schema);
    }

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('judul_ujian')->label('Ujian'),
            TextColumn::make('soal.mataKuliah.nama')->label('Mata Kuliah'),

            // --- KOLOM STATUS BARU ---
            TextColumn::make('status_pengerjaan')
                ->label('Status')
                ->state(fn ($record) => $record->attempts->where('user_id', auth()->id())->first() ? 'Sudah' : 'Belum')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Sudah' => 'success',
                    'Belum' => 'gray',
                }),
            // -------------------------

            TextColumn::make('durasi_menit')->label('Durasi (Menit)'),
            TextColumn::make('waktu_selesai')
                ->label('Batas Waktu')
                ->dateTime('H:i T'),

                TextColumn::make('skor')
    ->label('Nilai')
    ->state(function ($record) {
        $attempt = \App\Models\UjianAttempt::where('user_id', auth()->id())
            ->where('ujian_id', $record->id)
            ->first();
        return $attempt ? $attempt->skor_akhir : null;
    })
    ->placeholder('-') // Jika belum ada nilai
    ->badge()
    ->color('info'),
        ])
        ->actions([
            Action::make('kerjakan')
                ->label('Mulai Ujian')
                ->color('success')
                ->icon('heroicon-o-play')
                ->requiresConfirmation()
                ->modalHeading('Mulai Ujian?')
                ->action(function ($record) {
                    $attempt = \App\Models\UjianAttempt::create([
                        'user_id' => auth()->id(),
                        'ujian_id' => $record->id,
                        'mulai_pada' => now(),
                    ]);

                    return redirect()->route('ujian.kerjakan', $attempt->id);
                })
                // Logika hidden ini sudah benar untuk menghilangkan tombol jika sudah ada attempt
                ->hidden(fn ($record) =>
                    \App\Models\UjianAttempt::where('user_id', auth()->id())
                        ->where('ujian_id', $record->id)
                        ->exists()
                ),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListUjians::route('/'),
            'create' => CreateListUjian::route('/create'),
            'view' => ViewListUjian::route('/{record}'),
            'edit' => EditListUjian::route('/{record}/edit'),
        ];
    }
}
