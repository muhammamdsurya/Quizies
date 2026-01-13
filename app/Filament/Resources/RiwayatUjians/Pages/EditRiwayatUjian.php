<?php

namespace App\Filament\Resources\RiwayatUjians\Pages;

use App\Filament\Resources\RiwayatUjians\RiwayatUjianResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatUjian extends EditRecord
{
    protected static string $resource = RiwayatUjianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
