<?php

namespace App\Filament\Resources\SettingSoals\Pages;

use App\Filament\Resources\SettingSoals\SettingSoalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSettingSoal extends ViewRecord
{
    protected static string $resource = SettingSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
