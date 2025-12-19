<?php

namespace App\Filament\Resources\Mahasiswas;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Mahasiswas\Pages\CreateMahasiswa;
use App\Filament\Resources\Mahasiswas\Pages\EditMahasiswa;
use App\Filament\Resources\Mahasiswas\Pages\ListMahasiswas;
use App\Filament\Resources\Mahasiswas\Pages\ViewMahasiswa;
use App\Filament\Resources\Mahasiswas\Schemas\MahasiswaForm;
use App\Filament\Resources\Mahasiswas\Schemas\MahasiswaInfolist;
use App\Filament\Resources\Mahasiswas\Tables\MahasiswasTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Mahasiswa;

class MahasiswaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'mahasiswa';

    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $pluralModelLabel = 'Mahasiswa';

    protected static ?string $modelLabel = 'Mahasiswa';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'Mahasiswa';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->mahasiswa();
    }

    public static function form(Schema $schema): Schema
    {
        return $form->schema([
        // Isian form Anda di sini
        \Filament\Forms\Components\TextInput::make('name')->required(),
        \Filament\Forms\Components\TextInput::make('email')->email()->required(),
        \Filament\Forms\Components\TextInput::make('nim')->required(),
        \Filament\Forms\Components\Hidden::make('role')->default('mahasiswa'),
    ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MahasiswaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MahasiswasTable::configure($table);
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
            'index' => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'view' => ViewMahasiswa::route('/{record}'),
            'edit' => EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
