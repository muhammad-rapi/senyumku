<?php

namespace App\Filament\Resources\ResepObatResource\Pages;

use App\Filament\Resources\ResepObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewResepObat extends ViewRecord
{
    protected static string $resource = ResepObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
