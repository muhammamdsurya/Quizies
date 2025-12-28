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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MahasiswaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'mahasiswa';

    protected static ?string $navigationLabel = 'Data Mahasiswa';

    protected static ?string $pluralModelLabel = 'Data Mahasiswa';

    protected static ?string $modelLabel = 'Data Mahasiswa';

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
            Section::make('Informasi Akun')
                ->description('Data login dan identitas Mahasiswa')
                ->columns(2)
                ->schema([TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255), TextInput::make('email')->label('Alamat Email')->email()->required()->unique(ignoreRecord: true), TextInput::make('password')->label('Password')->password()->required()->visibleOn('create'), Hidden::make('role')->default('mahasiswa')]),

            Section::make('Detail Profil')
                ->relationship('mahasiswaProfile')
                ->columns(2)
                ->schema([
                    TextInput::make('nim')->numeric()->label('NIM')->required(),
                    TextInput::make('semester')->numeric()->label('Semester')->required(),
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

    /**
     * TABLE
     */
    public static function table(Table $table): Table
    {
        return MahasiswasTable::configure($table);
    }

     // Tambahkan method ini di dalam class MataKuliahResource
public static function canCreate(): bool
{
    return auth()->user()->role === 'kaprodi';
}

    /**
     * Pages
     */
    public static function getPages(): array
    {
        if (request()->is('*/create') && auth()->user()?->role !== 'kaprodi') {
        abort(redirect('/admin'));
    }
        return [
            'index'  => ListMahasiswas::route('/'),
            'create' => CreateMahasiswa::route('/create'),
            'view'   => ViewMahasiswa::route('/{record}'),
            'edit'   => EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
