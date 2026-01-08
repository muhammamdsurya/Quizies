<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Prodi;
use App\Models\MataKuliah;
use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KaprodiWidget extends StatsOverviewWidget
{
    // 1. Logika Otorisasi: Hanya Dosen dan Kaprodi yang bisa melihat
    public static function canView(): bool
    {
        $user = auth()->user();

        // Kembalikan true jika role adalah kaprodi ATAU dosen
        return $user && in_array($user->role, ['kaprodi', 'dosen']);
    }

    protected static ?int $sort = 2;
   // Mengatur agar widget tampil dalam 1 baris penuh (opsional)
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            // Stat 1: Jumlah Mahasiswa
            Stat::make('Total Mahasiswa', MahasiswaProfile::count())
                ->description('Mahasiswa terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Contoh data grafik tren
                ->color('success'),

            // Stat 2: Jumlah Dosen
            Stat::make('Total Dosen', DosenProfile::count())
                ->description('Dosen pengajar aktif')
                ->descriptionIcon('heroicon-m-users')
                ->chart([3, 5, 2, 8, 4, 7, 4])
                ->color('primary'),

            // Stat 3: Jumlah Mata Kuliah
            Stat::make('Mata Kuliah', MataKuliah::count())
                ->description('Total mata kuliah aktif')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning'),

            // Stat 4: Jumlah Program Studi
            Stat::make('Program Studi', Prodi::count())
                ->description('Total program studi')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('info'),
        ];
    }
}
