<?php

namespace App\Filament\Resources\MakeSoals\Pages;

use App\Filament\Resources\MakeSoals\MakeSoalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMakeSoal extends EditRecord
{
    protected static string $resource = MakeSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
