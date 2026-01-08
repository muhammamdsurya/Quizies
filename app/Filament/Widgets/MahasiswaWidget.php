<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MahasiswaWidget extends BaseWidget
{
    // 1. Keamanan: Hanya Mahasiswa yang bisa melihat
    public static function canView(): bool
    {
        return auth()->user()->role === 'mahasiswa';
    }

    protected static ?int $sort = 2;

    protected function getStats(): array
    {

        $user = auth()->user();
        $profile = $user->mahasiswaProfile;

        // Jika profil belum ada, jangan tampilkan data
        // if (!$profile) return [];

        return [
            // Stat 1: Semester Saat Ini
            Stat::make('Semester', $profile->semester)
                ->description('Status: ' . ucfirst($profile->status_aktif))
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            // Stat 2: Mata Kuliah yang Diambil
            Stat::make('Mata Kuliah', $profile->mataKuliahs()->count() . ' MK')
                ->description('Tahun Akademik 2025/2026')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            // Stat 3: Tagihan / Kehadiran (Contoh Stat Menarik)
            Stat::make('Kehadiran', '95%')
                ->description('Sangat Baik')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([80, 85, 90, 92, 95])
                ->color('info'),
        ];
    }
}
