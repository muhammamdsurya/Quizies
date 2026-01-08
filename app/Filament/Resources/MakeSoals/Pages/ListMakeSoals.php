<?php

namespace App\Filament\Resources\MakeSoals\Pages;

use App\Filament\Resources\MakeSoals\MakeSoalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMakeSoals extends ListRecords
{
    protected static string $resource = MakeSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
