<?php

namespace App\Filament\Resources\Dosens;

use App\Filament\Resources\Dosens\Pages\CreateDosen;
use App\Filament\Resources\Dosens\Pages\EditDosen;
use App\Filament\Resources\Dosens\Pages\ListDosens;
use App\Filament\Resources\Dosens\Pages\ViewDosen;
use App\Filament\Resources\Dosens\Schemas\DosenInfolist;
use App\Filament\Resources\Dosens\Tables\DosensTable;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DosenResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'dosen';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Data Dosen';

    protected static ?string $pluralModelLabel = 'Data Dosen';

    protected static ?string $modelLabel = 'Data Dosen';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'name'; // Gunakan nama kolom yang ada (contoh: 'name')

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = parent::getEloquentQuery()->where('role', 'dosen');

        // 1. Jika Role Kaprodi atau Dosen, tampilkan semua data dosen
        if (in_array($user->role, ['kaprodi', 'dosen'])) {
            return $query;
        }

        // 2. Jika Role Mahasiswa, hanya tampilkan dosen yang mengajar mahasiswa tersebut
        if ($user->role === 'mahasiswa' && $user->mahasiswaProfile) {
            return $query->whereHas('dosenProfile.mataKuliahs.mahasiswas', function ($q) use ($user) {
                $q->where('mahasiswa_profiles.id', $user->mahasiswaProfile->id);
            });
        }

        // 3. Jika role tidak dikenal, kembalikan query kosong demi keamanan
        return $query->whereRaw('1=0');
    }

    /**
     * Form Configuration (Filament v4 Style)
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Informasi Akun')
                ->description('Data login dan identitas dosen')
                ->columnSpanFull()
                ->columns(2)
                ->schema([TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255), TextInput::make('email')->label('Alamat Email')->email()->required()->unique(ignoreRecord: true), TextInput::make('password')->label('Password')->password()->required()->visibleOn('create'), Hidden::make('role')->default('dosen')]),

            Section::make('Detail Profil')
                ->columnSpanFull()
                ->relationship('dosenProfile')
                ->columns(2)
                ->schema([
                    TextInput::make('nidn')->numeric()->label('NIDN')->required()->unique(ignoreRecord: true),
                    TextInput::make('jabatan')->label('Jabatan')->required(),
                    DatePicker::make('tanggal_masuk')->label('Tanggal Masuk')->required(),
                    Select::make('status_aktif') // ❌ pindah ke sini
                        ->label('Status')
                        ->options([
                            'aktif' => 'Aktif',
                            'non-aktif' => 'Non-Aktif',
                        ])
                        ->required(),
                    Select::make('prodi_id')->label('Program Studi')->relationship('prodi', 'nama')->searchable()->preload()->required(),
                    Select::make('mataKuliahs')->label('Mata Kuliah')->relationship('mataKuliahs', 'nama')->multiple()->searchable()->preload(),
                ]),
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
