<?php

namespace App\Filament\Resources\Ujians;

use App\Filament\Resources\Ujians\Pages\CreateUjian;
use App\Filament\Resources\Ujians\Pages\EditUjian;
use App\Filament\Resources\Ujians\Pages\ListUjians;
use App\Filament\Resources\Ujians\Pages\ViewUjian;
use App\Filament\Resources\Ujians\RelationManagers\AttemptsRelationManager;
use App\Filament\Resources\Ujians\Schemas\UjianInfolist;
use App\Filament\Resources\Ujians\Tables\UjiansTable;
use App\Models\Ujians;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UjianResource extends Resource
{
    protected static ?string $model = Ujians::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'judul_ujian';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Buat Ujian';

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Data Ujian';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Data Ujian';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('kaprodi', 'dosen');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = parent::getEloquentQuery();

        // Kaprodi melihat semua ujian, dosen hanya ujian buatannya
        return $user->hasRole('kaprodi') ? $query : $query->where('user_id', $user->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Hidden::make('user_id')
                ->default(fn () => auth()->id()),

            Section::make('Informasi Ujian')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('judul_ujian')
                        ->label('Judul Ujian')
                        ->required()
                        ->maxLength(255),

                    Select::make('soals_id')
                        ->label('Pilih Paket Soal')
                        ->relationship(
                            name: 'soal',
                            titleAttribute: 'nama_soal',
                            modifyQueryUsing: function ($query) {
                                $user = auth()->user();

                                // Kaprodi bisa ambil semua soal, dosen hanya soal miliknya sendiri
                                return $user->hasRole('kaprodi') ? $query : $query->where('user_id', $user->id);
                            }
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => ($record->mataKuliah?->nama ?? '-')." - {$record->nama_soal}")
                        ->searchable()
                        ->preload()
                        ->required()
                        // Paket soal tidak boleh diganti setelah ada mahasiswa yang mengerjakan
                        ->disabled(fn (?Ujians $record) => $record?->attempts()->exists())
                        ->helperText(fn (?Ujians $record) => $record?->attempts()->exists()
                            ? 'Paket soal terkunci karena ujian sudah dikerjakan mahasiswa.'
                            : null),
                ]),

            Section::make('Jadwal & Durasi')
                ->columnSpanFull()
                ->columns(['default' => 1, 'md' => 3])
                ->schema([
                    DateTimePicker::make('waktu_mulai')
                        ->label('Waktu Mulai')
                        ->seconds(false)
                        ->required(),
                    DateTimePicker::make('waktu_selesai')
                        ->label('Waktu Selesai')
                        ->seconds(false)
                        ->required()
                        ->after('waktu_mulai'),
                    TextInput::make('durasi_menit')
                        ->label('Durasi (Menit)')
                        ->integer()
                        ->minValue(1)
                        ->maxValue(1440)
                        ->required()
                        ->helperText('Waktu pengerjaan tiap mahasiswa, dihitung sejak ia mulai.'),
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
            AttemptsRelationManager::class,
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
