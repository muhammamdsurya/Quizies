<?php

namespace App\Filament\Resources\Mahasiswas;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Mahasiswas\Pages\CreateMahasiswa;
use App\Filament\Resources\Mahasiswas\Pages\EditMahasiswa;
use App\Filament\Resources\Mahasiswas\Pages\ListMahasiswas;
use App\Filament\Resources\Mahasiswas\Pages\ViewMahasiswa;
use App\Filament\Resources\Mahasiswas\Tables\MahasiswasTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MahasiswaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'mahasiswa';

    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $pluralModelLabel = 'Mahasiswa';

    protected static ?string $modelLabel = 'Mahasiswa';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Ambil hanya user role mahasiswa
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'mahasiswa');
    }

    /**
     * FORM
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Nama')
                ->required(),

            TextInput::make('email')
                ->email()
                ->required(),

            Hidden::make('role')
                ->default('mahasiswa'),
        ]);
    }

    /**
     * TABLE
     */
    public static function table(Table $table): Table
    {
        return MahasiswasTable::configure($table);
    }

    /**
     * Pages
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'view'   => ViewMahasiswa::route('/{record}'),
            'edit'   => EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
