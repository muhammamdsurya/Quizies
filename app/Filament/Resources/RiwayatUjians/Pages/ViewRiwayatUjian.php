<?php

namespace App\Filament\Resources\RiwayatUjians\Pages;

use App\Filament\Resources\RiwayatUjians\RiwayatUjianResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRiwayatUjian extends ViewRecord
{
    protected static string $resource = RiwayatUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
