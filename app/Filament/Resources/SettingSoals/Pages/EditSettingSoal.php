<?php

namespace App\Filament\Resources\SettingSoals\Pages;

use App\Filament\Resources\SettingSoals\SettingSoalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSettingSoal extends EditRecord
{
    protected static string $resource = SettingSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
