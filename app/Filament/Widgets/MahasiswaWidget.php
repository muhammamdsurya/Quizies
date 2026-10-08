<?php

namespace App\Filament\Widgets;

use App\Models\SettingSoal;
use App\Models\UjianAttempt;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MahasiswaWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    // Keamanan: Hanya Mahasiswa yang bisa melihat
    public static function canView(): bool
    {
        return auth()->user()->hasRole('mahasiswa');
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $profile = $user->mahasiswaProfile; // bisa null jika profil belum dibuat Kaprodi

        $tahunAkademik = SettingSoal::where('is_active', true)->latest()->value('tahun_akademik');

        $selesai = UjianAttempt::where('user_id', $user->id)->whereNotNull('selesai_pada');
        $jumlahSelesai = (clone $selesai)->count();
        $rataRata = (clone $selesai)->whereNotNull('skor_akhir')->avg('skor_akhir');

        return [
            Stat::make('Semester', $profile?->semester ?? '-')
                ->description('Status: '.ucfirst($profile?->status_aktif ?? 'profil belum lengkap'))
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Mata Kuliah', ($profile ? $profile->mataKuliahs()->count() : 0).' MK')
                ->description($tahunAkademik ? "Tahun Akademik {$tahunAkademik}" : 'Mata kuliah yang diambil')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Ujian Dikerjakan', $jumlahSelesai.' Ujian')
                ->description($rataRata !== null ? 'Rata-rata nilai: '.round($rataRata) : 'Belum ada nilai')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),
        ];
    }
}
