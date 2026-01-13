<?php

namespace App\Filament\Resources\Ujians;

use App\Filament\Resources\Ujians\Pages\CreateUjian;
use App\Filament\Resources\Ujians\Pages\EditUjian;
use App\Filament\Resources\Ujians\Pages\ListUjians;
use App\Filament\Resources\Ujians\Pages\ViewUjian;
use App\Filament\Resources\Ujians\Schemas\UjianForm;
use App\Filament\Resources\Ujians\Schemas\UjianInfolist;
use App\Filament\Resources\Ujians\Tables\UjiansTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Ujians;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UjianResource extends Resource
{
    protected static ?string $model = Ujians::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Ujian';

     protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Buat Ujian';

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Data Ujian';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Data Ujian';

    public static function shouldRegisterNavigation(): bool
{
    return in_array(auth()->user()->role, ['dosen', 'kaprodi']);
}

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    $user = auth()->user();

    if ($user->role === 'kaprodi') {
        return $query;
    }

    return $query->where('user_id', $user->id);
}

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Hidden::make('user_id')
        ->default(auth()->id()),
            Section::make('Informasi Ujian')
                ->schema([
                    TextInput::make('judul_ujian')->required(),

                    Select::make('soals_id')
                        ->label('Pilih Paket Soal')
                        ->relationship(
                            name: 'soal',
                            titleAttribute: 'id', // Nanti kita percantik tampilannya
                            modifyQueryUsing: function ($query) {
                                $user = auth()->user();

                                // Jika Kaprodi: Bisa ambil semua soal
                                if ($user->role === 'kaprodi') {
                                    return $query;
                                }

                                // Jika Dosen: Hanya soal miliknya sendiri
                                return $query->where('user_id', $user->id);
                            }
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->mataKuliah->nama} - {$record->nama_soal} ")
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),

            Section::make('Jadwal & Durasi')
                ->columns(2)
                ->schema([
                    DateTimePicker::make('waktu_mulai')->required(),
                    DateTimePicker::make('waktu_selesai')->required(),
                    TextInput::make('durasi_menit')
                        ->label('Durasi (Menit)')
                        ->numeric()
                        ->required(),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UjianInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UjiansTable::configure($table);
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
            'index' => ListUjians::route('/'),
            'create' => CreateUjian::route('/create'),
            'view' => ViewUjian::route('/{record}'),
            'edit' => EditUjian::route('/{record}/edit'),
        ];
    }
}
