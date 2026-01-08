<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserInfo extends StatsOverviewWidget
{
    // Membuat widget memenuhi lebar layar agar tidak kecil
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = auth()->user();

        // 1. Stat Dasar (Nama & Email)
        $stats = [
            Stat::make('Informasi Akun', $user->name)
                ->description($user->email)
                ->descriptionIcon('heroicon-m-user')
                ->color('primary'),

            Stat::make('Role Akun', strtoupper($user->role))
                ->description('Status akses Anda di sistem')
                ->color('warning'),
        ];

        // 2. Logika Dinamis: Jika Mahasiswa, tambahkan stat NIM & Prodi
        if ($user->role === 'mahasiswa' && $user->mahasiswaProfile) {
            $stats[] = Stat::make('NIM', $user->mahasiswaProfile->nim)
                ->description($user->mahasiswaProfile->prodi->nama ?? 'Prodi tidak terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success');
        }

        // 3. Logika Dinamis: Jika Dosen, tambahkan stat NIDN
        if ($user->role === 'dosen' && $user->dosenProfile) {
            $stats[] = Stat::make('NIDN', $user->dosenProfile->nidn)
                ->description($user->dosenProfile->jabatan)
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success');
        }

        return $stats;
    }
}
