<?php

namespace App\Filament\Resources\MataKuliahs;

use App\Models\User;
use App\Models\DosenProfile;
use App\Models\Prodi;
use App\Filament\Resources\MataKuliahs\Pages\CreateMataKuliah;
use App\Filament\Resources\MataKuliahs\Pages\EditMataKuliah;
use App\Filament\Resources\MataKuliahs\Pages\ListMataKuliahs;
use App\Filament\Resources\MataKuliahs\Pages\ViewMataKuliah;
use App\Filament\Resources\MataKuliahs\Schemas\MataKuliahForm;
use App\Filament\Resources\MataKuliahs\Schemas\MataKuliahInfolist;
use App\Filament\Resources\MataKuliahs\Tables\MataKuliahsTable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;

    protected static ?int $navigationSort = 4;


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MataKuliah';

    protected static ?string $slug = 'matkul';

    protected static ?string $navigationLabel = 'Mata Kuliah';

    protected static ?string $pluralModelLabel = 'Mata Kuliah';

    protected static ?string $modelLabel = 'Mata Kuliah';

public static function getEloquentQuery(): Builder
{
    $user = auth()->user();
    $query = parent::getEloquentQuery();

    // 1. Kaprodi / Admin → Bisa melihat semua data
    if (in_array($user->role, ['kaprodi'])) {
        return $query;
    }

    // 2. Dosen → Hanya melihat mata kuliah yang diampunya
    if ($user->role === 'dosen' && $user->dosenProfile) {
        return $query->whereHas(
            'dosens',
            fn ($q) => $q->where('dosen_profiles.id', $user->dosenProfile->id)
        );
    }

    // 3. Mahasiswa → Hanya melihat mata kuliah yang diambil (Dosen pengampu otomatis ikut tampil)
    if ($user->role === 'mahasiswa' && $user->mahasiswaProfile) {
        return $query->whereHas(
            'mahasiswas',
            fn ($q) => $q->where('mahasiswa_profiles.id', $user->mahasiswaProfile->id)
        );
    }

    // 4. Role lain atau jika profil tidak ditemukan → Tampilkan data kosong
    return $query->whereRaw('1=0');
}

    public static function form(Schema $schema): Schema
{
    return $schema->schema([
        TextInput::make('nama')
            ->label('Nama Mata Kuliah')
            ->required()
            ->maxLength(255),

        Select::make('prodi_id')
            ->label('Program Studi')
            ->relationship('prodi', 'nama')
            ->searchable()
            ->preload()
            ->required()
            ->live() // Aktifkan mode live agar perubahan terdeteksi
            ->afterStateUpdated(fn (Set $set, Get $get) => self::generateKode($set, $get)),

        TextInput::make('kode')
            ->label('Kode')
            ->required()
            ->unique(ignoreRecord: true)
            // Biarkan user melihat tapi tidak mengedit manual (opsional)
            ->readonly(),

        TextInput::make('semester')
            ->label('Semester')
            ->numeric()
            ->minValue(1)
            ->maxValue(8)
            ->default(2)
            ->required()
            ->live(onBlur: true) // Update kode setelah user selesai input angka
            ->afterStateUpdated(fn (Set $set, Get $get) => self::generateKode($set, $get)),

        TextInput::make('sks')
            ->label('SKS')
            ->numeric()
            ->minValue(1)
            ->maxValue(6)
            ->default(3)
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, Get $get) => self::generateKode($set, $get)),

        Select::make('dosens') // Nama field HARUS sama dengan nama fungsi relasi di model
    ->label('Dosen Pengajar')
    ->relationship(
        name: 'dosens',
        titleAttribute: 'id', // Tetap simpan ID dosen
        modifyQueryUsing: fn (Builder $query) => $query
            ->join('users', 'users.id', '=', 'dosen_profiles.user_id') // Join ke tabel users
            ->select('dosen_profiles.*') // Pastikan hanya kolom dosen_profiles yang diambil agar tidak bentrok
    )
    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name)
    ->multiple()
    ->searchable(['users.name']) // Sekarang SQL bisa menemukan users.name karena sudah di-join
    ->preload()
    ->required(),    ]);
}

// Fungsi Helper untuk Generate Kode
public static function generateKode(Set $set, Get $get)
{
    $prodiId = $get('prodi_id');
    $semester = $get('semester');
    $sks = $get('sks');

    if ($prodiId && $semester && $sks) {
        // 1. Ambil nama prodi dari DB berdasarkan ID
        $prodiName = Prodi::find($prodiId)?->nama;

        if ($prodiName) {
            // 2. Ambil inisial (Sistem Informasi -> SI)
            $initials = collect(explode(' ', $prodiName))
                ->map(fn ($word) => strtoupper($word[0]))
                ->join('');

            // 3. Format Semester (misal 1 jadi 01)
            $formattedSemester = str_pad($semester, 2, '0', STR_PAD_LEFT);

            // 4. Set nilai ke kolom kode: SI-01-3
            $set('kode', "{$initials}-{$formattedSemester}-{$sks}");
        }
    }
}

    public static function infolist(Schema $schema): Schema
    {
        return MataKuliahInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MataKuliahsTable::configure($table);
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
            'index' => ListMataKuliahs::route('/'),
            'create' => CreateMataKuliah::route('/create'),
            'view' => ViewMataKuliah::route('/{record}'),
            'edit' => EditMataKuliah::route('/{record}/edit'),
        ];

    }
}
