<?php

namespace App\Filament\Resources\MakeSoals\Pages;

use App\Filament\Resources\MakeSoals\MakeSoalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMakeSoal extends ViewRecord
{
    protected static string $resource = MakeSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
