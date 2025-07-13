<?php

namespace App\Filament\Resources\ResepObatResource\Pages;

use App\Filament\Resources\ResepObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResepObats extends ListRecords
{
    protected static string $resource = ResepObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
