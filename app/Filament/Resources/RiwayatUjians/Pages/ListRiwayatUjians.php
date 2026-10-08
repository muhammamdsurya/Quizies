<?php

namespace App\Filament\Resources\RiwayatUjians\Pages;

use App\Filament\Resources\RiwayatUjians\RiwayatUjianResource;
use App\Models\UjianAttempt;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatUjians extends ListRecords
{
    protected static string $resource = RiwayatUjianResource::class;

    public function mount(): void
    {
        // Attempt yang waktunya habis dikumpulkan otomatis agar nilainya tampil
        UjianAttempt::selesaikanYangKedaluwarsa(auth()->id());

        parent::mount();
    }
}
