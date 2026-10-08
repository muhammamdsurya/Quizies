<?php

namespace App\Filament\Resources\ListUjians\Pages;

use App\Filament\Resources\ListUjians\ListUjianResource;
use App\Models\UjianAttempt;
use Filament\Resources\Pages\ListRecords;

class ListListUjians extends ListRecords
{
    protected static string $resource = ListUjianResource::class;

    public function mount(): void
    {
        // Attempt yang waktunya habis dikumpulkan otomatis agar pindah ke Riwayat
        UjianAttempt::selesaikanYangKedaluwarsa(auth()->id());

        parent::mount();
    }
}
