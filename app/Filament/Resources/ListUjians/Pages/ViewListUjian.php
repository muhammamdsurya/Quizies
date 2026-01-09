<?php

namespace App\Filament\Resources\ListUjians\Pages;

use App\Filament\Resources\ListUjians\ListUjianResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewListUjian extends ViewRecord
{
    protected static string $resource = ListUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
