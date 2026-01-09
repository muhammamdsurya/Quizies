<?php

namespace App\Filament\Resources\Prodis;

use App\Filament\Resources\Prodis\Pages\CreateProdi;
use App\Filament\Resources\Prodis\Pages\EditProdi;
use App\Filament\Resources\Prodis\Pages\ListProdis;
use App\Filament\Resources\Prodis\Pages\ViewProdi;
use App\Filament\Resources\Prodis\Schemas\ProdiForm;
use App\Filament\Resources\Prodis\Schemas\ProdiInfolist;
use App\Filament\Resources\Prodis\Tables\ProdisTable;
use App\Models\Prodi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

     protected static ?string $slug = 'prodi';

     protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Program Studi';

    protected static ?string $pluralModelLabel = 'Program Studi';

    protected static ?string $modelLabel = 'Program Studi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ProdiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProdiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProdisTable::configure($table);
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
            'index' => ListProdis::route('/'),
            'create' => CreateProdi::route('/create'),
            'view' => ViewProdi::route('/{record}'),
            'edit' => EditProdi::route('/{record}/edit'),
        ];
    }

}
