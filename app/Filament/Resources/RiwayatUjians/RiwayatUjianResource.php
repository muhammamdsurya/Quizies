<?php

namespace App\Filament\Resources\RiwayatUjians;

use App\Filament\Resources\RiwayatUjians\Pages\CreateRiwayatUjian;
use App\Filament\Resources\RiwayatUjians\Pages\EditRiwayatUjian;
use App\Filament\Resources\RiwayatUjians\Pages\ListRiwayatUjians;
use App\Filament\Resources\RiwayatUjians\Pages\ViewRiwayatUjian;
use App\Filament\Resources\RiwayatUjians\Schemas\RiwayatUjianForm;
use App\Filament\Resources\RiwayatUjians\Schemas\RiwayatUjianInfolist;
use App\Filament\Resources\RiwayatUjians\Tables\RiwayatUjiansTable;
use App\Models\Ujians;
// Tambahkan blok import Infolist ini secara lengkap:
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\ListUjians\Tables\ListUjiansTable;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RiwayatUjianResource extends Resource
{
    protected static ?string $model = Ujians::class;

    protected static ?string $navigationLabel = 'Riwayat Ujian';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'RiwayatUjian';

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Riwayat Ujian';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Riwayat Ujian';

    public static function shouldRegisterNavigation(): bool
{
    return auth()->user()->role === 'mahasiswa';
}

    public static function canCreate(): bool
{
    // Mengembalikan false akan menyembunyikan tombol "New" di header tabel
    return false;
}

    public static function getEloquentQuery(): Builder
    {
        // 3. Filter agar hanya menampilkan yang sudah selesai ATAU sudah pernah dikerjakan
        return parent::getEloquentQuery()
            ->where(function (Builder $query) {
                $query->where('waktu_selesai', '<', now())
                      ->orWhereHas('attempts', function (Builder $q) {
                          $q->where('user_id', Auth::id());
                      });
            });
    }

    public static function table(Table $table): Table
    {
        // 4. Gunakan konfigurasi tabel yang sudah ada agar tidak kerja dua kali
        return ListUjiansTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RiwayatUjianInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayatUjians::route('/'),
            'view' => Pages\ViewRiwayatUjian::route('/{record}'),
        ];
    }


}
