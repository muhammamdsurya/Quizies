<?php

namespace App\Filament\Resources\RiwayatUjians\Pages;

use App\Filament\Resources\RiwayatUjians\RiwayatUjianResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatUjians extends ListRecords
{
    protected static string $resource = RiwayatUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
