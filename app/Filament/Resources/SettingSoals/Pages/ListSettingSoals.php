<?php

namespace App\Filament\Resources\SettingSoals\Pages;

use App\Filament\Resources\SettingSoals\SettingSoalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSettingSoals extends ListRecords
{
    protected static string $resource = SettingSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
