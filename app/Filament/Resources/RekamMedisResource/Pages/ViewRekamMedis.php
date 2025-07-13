<?php

namespace App\Filament\Resources\RekamMedisResource\Pages;

use App\Filament\Resources\RekamMedisResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRekamMedis extends ViewRecord
{
    protected static string $resource = RekamMedisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
