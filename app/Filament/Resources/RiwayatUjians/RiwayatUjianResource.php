<?php

namespace App\Filament\Resources\RiwayatUjians;

use App\Filament\Resources\RiwayatUjians\Pages\ListRiwayatUjians;
use App\Filament\Resources\RiwayatUjians\Pages\ViewRiwayatUjian;
use App\Filament\Resources\RiwayatUjians\Schemas\RiwayatUjianInfolist;
use App\Filament\Resources\RiwayatUjians\Tables\RiwayatUjiansTable;
use App\Models\Ujians;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RiwayatUjianResource extends Resource
{
    protected static ?string $model = Ujians::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Riwayat Ujian';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'judul_ujian';

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Riwayat Ujian';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Riwayat Ujian';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('mahasiswa');
    }

    // Riwayat hanya bisa dilihat, tidak bisa diubah
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        // Ujian yang sudah dikumpulkan, ATAU ujian mata kuliah yang diikuti dan jadwalnya sudah lewat
        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($userId) {
                $query->whereHas('attempts', fn (Builder $q) => $q->where('user_id', $userId)->whereNotNull('selesai_pada'))
                    ->orWhere(fn (Builder $q) => $q
                        ->where('waktu_selesai', '<', now())
                        ->whereHas('soal.mataKuliah.mahasiswas', fn (Builder $m) => $m->where('user_id', $userId)));
            });
    }

    public static function table(Table $table): Table
    {
        return RiwayatUjiansTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RiwayatUjianInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRiwayatUjians::route('/'),
            'view' => ViewRiwayatUjian::route('/{record}'),
        ];
    }
}
