<?php

namespace App\Filament\Resources\MakeSoals;

use App\Filament\Resources\MakeSoals\Pages\CreateMakeSoal;
use App\Filament\Resources\MakeSoals\Pages\EditMakeSoal;
use App\Filament\Resources\MakeSoals\Pages\ListMakeSoals;
use App\Filament\Resources\MakeSoals\Pages\ViewMakeSoal;
use App\Filament\Resources\MakeSoals\Schemas\MakeSoalForm;
use App\Filament\Resources\MakeSoals\Schemas\MakeSoalInfolist;
use App\Filament\Resources\MakeSoals\Tables\MakeSoalsTable;
use App\Models\Soals;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Grid;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\MataKuliah;
use App\Models\SettingSoal;
use Filament\Forms\Components\Repeater;
// HAPUS baris ini jika ada:
// use Filament\Schemas\Get;

// TAMBAHKAN baris ini:
use Filament\Forms\Get;
use Filament\Forms\Form;

class MakeSoalResource extends Resource
{
    protected static ?string $model = Soals::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MakeSoal';

     protected static ?int $navigationSort = 0;

    protected static ?string $maxWidth = 'full';

    protected static ?string $navigationLabel = 'Bank Soal';

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Bank Soal';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Bank Soal';


    public static function canViewAny(): bool
{
    return auth()->user()->role !== 'mahasiswa';
}

   public static function getEloquentQuery(): Builder
{
    $user = auth()->user();
    $query = parent::getEloquentQuery();

    if ($user->role === 'dosen') {
        // PERBAIKAN: Filter soal yang kolom user_id nya sesuai dengan ID Dosen login
        return $query->where('user_id', $user->id);
    }

    if ($user->role === 'mahasiswa') {
        // Sesuaikan dengan kolom status aktif di tabel Soals Anda
        return $query->where('is_published', true);
    }

    return $query;
}

    public static function form(Schema $schema): Schema
{
    return $schema->schema([
        Hidden::make('user_id')
        ->default(auth()->id()),

    Section::make('Identitas Ujian')
        ->description('Lengkapi data identitas ujian di bawah ini.')
        ->columns(2)
        ->schema([
            Select::make('mata_kuliah_id')
                ->label('Mata Kuliah')
                ->relationship(
                    name: 'mataKuliah',
                    titleAttribute: 'nama',
                    modifyQueryUsing: function ($query) {
                        $user = auth()->user();
                        if ($user->role === 'kaprodi') return $query;

                        return $query->whereHas('dosenProfiles', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                    }
                )
                ->preload()
                ->searchable()
                ->required()
                , // Melebar di baris pertama
TextInput::make('nama_soal')
    ->label('Nama Paket Soal')
    ->placeholder('Contoh: Soal UTS Basis Data Kelas A')
    ->required()
    ->maxLength(255),
            Select::make('setting_soal_id')
                ->label('Tahun Akademik')
                ->relationship('settingSoal', 'tahun_akademik')
                ->placeholder('Pilih Tahun Akademik Terlebih Dahulu')
                ->live()
                ->required()
                ->columnSpanFull(),

            ToggleButtons::make('jenis_soal')
                ->label('Jenis Soal')
                ->options(function ($get) {
        $setting = \App\Models\SettingSoal::find($get('setting_soal_id'));

        if (! $setting || ! $setting->jenis_soal_options) {
            return [];
        }

        // Ambil array dari database, misalnya: ['uts', 'uas']
        $options = $setting->jenis_soal_options;

        // Ubah setiap key menjadi tulisan yang Anda inginkan
        $formattedOptions = [];
        foreach ($options as $opt) {
            $formattedOptions[$opt] = match ($opt) {
                'uts'  => 'Ulangan Tengah Semester (UTS)',
                'uas'  => 'Ulangan Akhir Semester (UAS)',
                'kuis' => 'Kuis / Tugas Harian',
                default => ucfirst($opt),
            };
        }

        return $formattedOptions;
    })

                ->icons([
                    'uts' => 'heroicon-o-academic-cap',
                    'uas' => 'heroicon-o-briefcase',
                    'kuis' => 'heroicon-o-chat-bubble-bottom-center-text',
                ])
                ->colors([
                    'uts' => 'primary',
                    'uas' => 'success',
                    'kuis' => 'warning',
                ])
                ->inline()
                ->required()
                // Pesan bantu jika Tahun Akademik belum dipilih
                ->helperText(fn ($get) => ! $get('setting_soal_id') ? '⚠️ Silakan pilih Tahun Akademik untuk melihat pilihan Jenis Soal.' : null),

            ToggleButtons::make('tipe_soal')
            ->extraAttributes([
                    'class' => 'fi-fo-toggle-buttons-large', ])
                ->label('Tipe Soal')
                ->live()
                ->options(function ($get) {
        $setting = \App\Models\SettingSoal::find($get('setting_soal_id'))
        ;

        if (! $setting || ! $setting->tipe_soal_options) {
            return [];
        }

        // Ambil array tipe soal dari database, misal: ['pg', 'esai']
        $options = $setting->tipe_soal_options;

        $formattedOptions = [];
        foreach ($options as $opt) {
            $formattedOptions[$opt] = match ($opt) {
                'pg'   => 'Pilihan Ganda (Multiple Choice)',
                'esai' => 'Esai / Uraian (Essay)',
                default => ucfirst($opt),
            };
        }

        return $formattedOptions;
    })
                ->icons([
                    'pg' => 'heroicon-o-list-bullet',
                    'esai' => 'heroicon-o-pencil-square',
                ])
                ->colors([
                    'pg' => 'info',
                    'esai' => 'danger',
                ])
                ->inline()
                ->live() // Tetap live untuk memicu visibility Repeater di bawahnya
                ->required()
                ->helperText(fn ($get) => ! $get('setting_soal_id') ? '⚠️ Silakan pilih Tahun Akademik untuk melihat pilihan Tipe Soal.' : null),
        ]) ->columnSpanFull(),

    Repeater::make('detailSoals') // Sesuai nama fungsi relasi di model Soal
        ->relationship() // Ini kunci agar tersimpan ke tabel detail_soal
        ->label('Butir-Butir Soal')
        ->itemLabel(function (array $state, $uuid, $component): ?string {
        $index = array_search($uuid, array_keys($component->getState())) + 1;
        return 'Soal Nomor ' . ($state['nomor_soal'] ?? $index);
    })
        ->collapsible()
        ->cloneable()
        ->schema([

            // HIDDEN FIELD: Tambahkan ini agar tipe_soal dari induk ikut tersimpan ke anak
      TextInput::make('tipe_soal')
    ->hidden()
    // Ambil nilai dari induk secara real-time
    ->default(fn ($get) => $get('../../tipe_soal'))
    // Pastikan nilai dikirim ke database saat simpan
    ->dehydrated(true)
    // Paksa update nilai jika tipe_soal di atas berubah
    ->afterStateHydrated(fn ($set, $get) => $set('tipe_soal', $get('../../tipe_soal'))),

           TextInput::make('nomor_soal')
           ->hidden(),

            MarkdownEditor::make('pertanyaan')
                ->required()
                ->columnSpanFull(),

            // Form Dinamis PG
            Grid::make(2)
                ->schema([
                // Gunakan required kondisional agar tidak error saat input esai
                TextInput::make('opsi_a')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                TextInput::make('opsi_b')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                TextInput::make('opsi_c')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                TextInput::make('opsi_d')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                Select::make('kunci_jawaban')
                    ->options(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'])
                    ->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
            ])
            ->visible(fn ($get) => $get('../../tipe_soal') === 'pg'),

           // Form Dinamis Esai
        Textarea::make('petunjuk_esai')
            ->label('Petunjuk Pengerjaan Esai')
            ->required(fn ($get) => $get('../../tipe_soal') === 'esai')
            ->visible(fn ($get) => $get('../../tipe_soal') === 'esai'),
        ])
        ->createItemButtonLabel('Tambah Soal')
        ->columnSpanFull(),
]);
}

    public static function infolist(Schema $schema): Schema
    {
        return MakeSoalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MakeSoalsTable::configure($table);
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
            'index' => ListMakeSoals::route('/'),
            'create' => CreateMakeSoal::route('/create'),
            'view' => ViewMakeSoal::route('/{record}'),
            'edit' => EditMakeSoal::route('/{record}/edit'),
        ];
    }
}
