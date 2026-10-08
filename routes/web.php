<?php

use App\Livewire\KerjakanUjian;
use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use App\Models\MataKuliah;
use App\Models\UjianAttempt;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    // Angka nyata untuk bagian statistik di landing page
    return view('welcome', [
        'statistik' => [
            ['label' => 'Mahasiswa', 'nilai' => MahasiswaProfile::count(), 'ikon' => 'fa-user-graduate'],
            ['label' => 'Dosen', 'nilai' => DosenProfile::count(), 'ikon' => 'fa-chalkboard-user'],
            ['label' => 'Mata Kuliah', 'nilai' => MataKuliah::count(), 'ikon' => 'fa-book'],
            ['label' => 'Ujian Dikerjakan', 'nilai' => UjianAttempt::whereNotNull('selesai_pada')->count(), 'ikon' => 'fa-clipboard-check'],
        ],
    ]);
})->name('home');

// Dashboard utama aplikasi ada di panel Filament
Route::redirect('dashboard', '/admin')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/ujian/kerjakan/{attempt_id}', KerjakanUjian::class)
        ->whereNumber('attempt_id')
        ->name('ujian.kerjakan');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});
