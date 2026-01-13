<?php

namespace App\Filament\Resources\SettingSoals;

use App\Filament\Resources\SettingSoals\Pages\CreateSettingSoal;
use App\Filament\Resources\SettingSoals\Pages\EditSettingSoal;
use App\Filament\Resources\SettingSoals\Pages\ListSettingSoals;
use App\Filament\Resources\SettingSoals\Pages\ViewSettingSoal;
use App\Filament\Resources\SettingSoals\Schemas\SettingSoalForm;
use App\Filament\Resources\SettingSoals\Schemas\SettingSoalInfolist;
use App\Filament\Resources\SettingSoals\Tables\SettingSoalsTable;
use App\Models\SettingSoal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SettingSoalResource extends Resource
{
    protected static ?string $model = SettingSoal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'SettingSoal';

    protected static ?string $navigationLabel = 'Pengaturan Soal';

     // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Pengaturan Soal';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Pengaturan Soal';

    protected static ?string $maxWidth = 'full';

    public static function canViewAny(): bool
{
    return auth()->user()->role === 'kaprodi';
}

    public static function form(Schema $schema): Schema
{
    return $schema->schema([
        Section::make('Konfigurasi Ujian')
            ->schema([
                TextInput::make('tahun_akademik')->required()->placeholder('2025/2026'),
                ToggleButtons::make('jenis_soal_options')
    ->label('Jenis Soal yang Diizinkan')
    ->options([
        'uts' => 'UTS',
        'uas' => 'UAS',
        'kuis' => 'Kuis',
    ])
    ->icons([
        'uts' => 'heroicon-o-academic-cap',
        'uas' => 'heroicon-o-briefcase',
        'kuis' => 'heroicon-o-question-mark-circle',
    ])
    ->colors([
        'uts' => 'primary',
        'uas' => 'success',
        'kuis' => 'warning',
    ])
    ->multiple() // Karena Kaprodi bisa mengizinkan lebih dari satu jenis sekaligus
    ->inline()   // Agar tombol berjejer ke samping
    ->required(),
                ToggleButtons::make('tipe_soal_options')
    ->label('Tipe Soal yang Tersedia')
    ->options([
        'pg' => 'Pilihan Ganda',
        'esai' => 'Esai',
    ])
    ->icons([
        'pg' => 'heroicon-o-list-bullet',
        'esai' => 'heroicon-o-pencil-square',
    ])
    ->colors([
        'pg' => 'primary',
        'esai' => 'info',
    ])
    ->multiple() // Agar bisa pilih lebih dari satu (seperti checkbox)
    ->inline()
    ->required(),
            ])
            ->columnSpanFull(),
    ]);
}

    public static function infolist(Schema $schema): Schema
    {
        return SettingSoalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingSoalsTable::configure($table);
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
            'index' => ListSettingSoals::route('/'),
            'create' => CreateSettingSoal::route('/create'),
            'view' => ViewSettingSoal::route('/{record}'),
            'edit' => EditSettingSoal::route('/{record}/edit'),
        ];
    }

}
