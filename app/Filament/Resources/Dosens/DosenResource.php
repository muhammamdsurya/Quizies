<?php

namespace App\Filament\Resources\Dosens;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Dosens\Pages\CreateDosen;
use App\Filament\Resources\Dosens\Pages\EditDosen;
use App\Filament\Resources\Dosens\Pages\ListDosens;
use App\Filament\Resources\Dosens\Pages\ViewDosen;
use App\Filament\Resources\Dosens\Schemas\DosenForm;
use App\Filament\Resources\Dosens\Schemas\DosenInfolist;
use App\Filament\Resources\Dosens\Tables\DosensTable;
use BackedEnum;
use Dosen;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class DosenResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'dosen';

    // Label yang muncul di menu samping
    protected static ?string $navigationLabel = 'Dosen';

    // Label yang muncul di judul halaman (Daftar Dosen)
    protected static ?string $pluralModelLabel = 'Dosen';
    
    // Label untuk satu data (Tambah Dosen)
    protected static ?string $modelLabel = 'Dosen';

   protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'Dosen';

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->dosen();
}

    public static function form(Schema $schema): Schema
    {
        return $form->schema([
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\TextInput::make('email')->email()->required(),
        Forms\Components\TextInput::make('password')->password()->required()->visibleOn('create'),
        Forms\Components\Hidden::make('role')->default('dosen'), // Otomatis tersimpan sebagai dosen
    ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DosenInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DosensTable::configure($table);
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
            'index' => ListDosens::route('/'),
            'create' => CreateDosen::route('/create'),
            'view' => ViewDosen::route('/{record}'),
            'edit' => EditDosen::route('/{record}/edit'),
        ];
    }
}
