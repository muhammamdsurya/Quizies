<?php

namespace App\Filament\Resources\ListUjians\Pages;

use App\Filament\Resources\ListUjians\ListUjianResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditListUjian extends EditRecord
{
    protected static string $resource = ListUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
