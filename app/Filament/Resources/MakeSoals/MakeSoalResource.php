<?php

namespace App\Filament\Resources\MakeSoals;

use App\Filament\Resources\MakeSoals\Pages\CreateMakeSoal;
use App\Filament\Resources\MakeSoals\Pages\EditMakeSoal;
use App\Filament\Resources\MakeSoals\Pages\ListMakeSoals;
use App\Filament\Resources\MakeSoals\Pages\ViewMakeSoal;
use App\Filament\Resources\MakeSoals\Schemas\MakeSoalInfolist;
use App\Filament\Resources\MakeSoals\Tables\MakeSoalsTable;
use App\Models\SettingSoal;
use App\Models\Soals;
use BackedEnum;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MakeSoalResource extends Resource
{
    protected static ?string $model = Soals::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_soal';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Bank Soal';

    // Mengganti judul halaman (Title) dan Breadcrumbs
    protected static ?string $pluralModelLabel = 'Bank Soal';

    // Mengganti label untuk satu record (misal saat View)
    protected static ?string $modelLabel = 'Bank Soal';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('kaprodi', 'dosen');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = parent::getEloquentQuery();

        // Kaprodi melihat semua paket soal, dosen hanya miliknya sendiri
        return $user->hasRole('kaprodi') ? $query : $query->where('user_id', $user->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Hidden::make('user_id')
                ->default(fn () => auth()->id()),

            Section::make('Identitas Ujian')
                ->description('Lengkapi data identitas ujian di bawah ini.')
                ->columns(['default' => 1, 'md' => 2])
                ->schema([
                    Select::make('mata_kuliah_id')
                        ->label('Mata Kuliah')
                        ->relationship(
                            name: 'mataKuliah',
                            titleAttribute: 'nama',
                            modifyQueryUsing: function ($query) {
                                $user = auth()->user();
                                if ($user->hasRole('kaprodi')) {
                                    return $query;
                                }

                                return $query->whereHas('dosens', fn ($q) => $q->where('user_id', $user->id));
                            }
                        )
                        ->preload()
                        ->searchable()
                        ->required(),

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
                        ->options(fn ($get) => self::opsiDariSetting($get('setting_soal_id'), 'jenis_soal_options', [
                            'uts' => 'Ulangan Tengah Semester (UTS)',
                            'uas' => 'Ulangan Akhir Semester (UAS)',
                            'kuis' => 'Kuis / Tugas Harian',
                        ]))
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
                        ->helperText(fn ($get) => ! $get('setting_soal_id') ? 'Silakan pilih Tahun Akademik untuk melihat pilihan Jenis Soal.' : null),

                    ToggleButtons::make('tipe_soal')
                        ->label('Tipe Soal')
                        ->options(fn ($get) => self::opsiDariSetting($get('setting_soal_id'), 'tipe_soal_options', [
                            'pg' => 'Pilihan Ganda (Multiple Choice)',
                            'esai' => 'Esai / Uraian (Essay)',
                        ]))
                        ->icons([
                            'pg' => 'heroicon-o-list-bullet',
                            'esai' => 'heroicon-o-pencil-square',
                        ])
                        ->colors([
                            'pg' => 'info',
                            'esai' => 'danger',
                        ])
                        ->inline()
                        ->live() // Memicu visibility field PG/Esai di Repeater
                        ->required()
                        ->helperText(fn ($get) => ! $get('setting_soal_id') ? 'Silakan pilih Tahun Akademik untuk melihat pilihan Tipe Soal.' : null),
                ])
                ->columnSpanFull(),

            Repeater::make('detailSoals') // Sesuai nama fungsi relasi di model Soals
                ->relationship()
                ->orderColumn('nomor_soal') // Nomor soal mengikuti urutan item (bisa di-drag)
                ->label('Butir-Butir Soal')
                ->itemLabel(function (array $state, $uuid, $component): string {
                    return 'Soal Nomor '.(array_search($uuid, array_keys($component->getState())) + 1);
                })
                ->collapsible()
                ->cloneable()
                ->schema([
                    MarkdownEditor::make('pertanyaan')
                        ->required()
                        ->columnSpanFull(),

                    // Form Dinamis PG
                    Grid::make(['default' => 1, 'md' => 2])
                        ->schema([
                            TextInput::make('opsi_a')->label('Opsi A')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                            TextInput::make('opsi_b')->label('Opsi B')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                            TextInput::make('opsi_c')->label('Opsi C')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                            TextInput::make('opsi_d')->label('Opsi D')->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                            Select::make('kunci_jawaban')
                                ->label('Kunci Jawaban')
                                ->options(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'])
                                ->required(fn ($get) => $get('../../tipe_soal') === 'pg'),
                        ])
                        ->visible(fn ($get) => $get('../../tipe_soal') === 'pg'),

                    // Form Dinamis Esai
                    Textarea::make('petunjuk_esai')
                        ->label('Petunjuk Pengerjaan Esai')
                        ->required(fn ($get) => $get('../../tipe_soal') === 'esai')
                        ->visible(fn ($get) => $get('../../tipe_soal') === 'esai'),
                ])
                ->addActionLabel('Tambah Soal')
                ->columnSpanFull(),
        ]);
    }

    /**
     * Ambil opsi yang diizinkan Kaprodi pada Pengaturan Soal, beri label yang mudah dibaca.
     */
    private static function opsiDariSetting(?string $settingId, string $kolom, array $label): array
    {
        $opsi = SettingSoal::find($settingId)?->{$kolom} ?? [];

        return collect($opsi)->mapWithKeys(fn ($opt) => [$opt => $label[$opt] ?? ucfirst($opt)])->all();
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
