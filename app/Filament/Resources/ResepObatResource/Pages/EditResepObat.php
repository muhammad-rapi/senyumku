<?php

namespace App\Filament\Resources\ResepObatResource\Pages;

use App\Filament\Resources\ResepObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResepObat extends EditRecord
{
    protected static string $resource = ResepObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
