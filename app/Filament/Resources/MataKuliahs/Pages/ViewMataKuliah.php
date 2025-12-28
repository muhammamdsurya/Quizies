<?php

namespace App\Filament\Resources\MataKuliahs\Pages;

use App\Filament\Resources\MataKuliahs\MataKuliahResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMataKuliah extends ViewRecord
{
    protected static string $resource = MataKuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
