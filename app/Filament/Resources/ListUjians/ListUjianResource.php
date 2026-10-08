<?php

namespace App\Filament\Resources\ListUjians;

use App\Filament\Resources\ListUjians\Pages\ListListUjians;
use App\Filament\Resources\ListUjians\Tables\ListUjiansTable;
use App\Models\Ujians;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListUjianResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Ujians::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    protected static ?string $navigationLabel = 'Ujian Tersedia';

    protected static ?string $slug = 'daftar-ujian-mahasiswa'; // Slug harus unik

    protected static ?string $recordTitleAttribute = 'judul_ujian';

    protected static bool $isGloballySearchable = false;

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Ujian Tersedia';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Ujian Tersedia';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('mahasiswa');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        return parent::getEloquentQuery()
            ->where('waktu_mulai', '<=', now())
            ->where('waktu_selesai', '>=', now())
            // Hanya ujian yang sudah berisi soal
            ->whereHas('soal.detailSoals')
            // Hanya ujian dari Mata Kuliah yang diambil mahasiswa yang sedang login
            ->whereHas('soal.mataKuliah.mahasiswas', fn (Builder $query) => $query->where('user_id', $userId))
            // Ujian yang sudah dikumpulkan pindah ke Riwayat; yang belum selesai bisa dilanjutkan
            ->whereDoesntHave('attempts', fn (Builder $query) => $query->where('user_id', $userId)->whereNotNull('selesai_pada'));
    }

    public static function table(Table $table): Table
    {
        return ListUjiansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListUjians::route('/'),
        ];
    }
}
