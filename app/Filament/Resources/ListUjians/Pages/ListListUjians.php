<?php

namespace App\Filament\Resources\ListUjians\Pages;

use App\Filament\Resources\ListUjians\ListUjianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListListUjians extends ListRecords
{
    protected static string $resource = ListUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
