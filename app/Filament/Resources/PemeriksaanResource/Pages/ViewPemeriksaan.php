<?php

namespace App\Filament\Resources\PemeriksaanResource\Pages;

use App\Filament\Resources\PemeriksaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPemeriksaan extends ViewRecord
{
    protected static string $resource = PemeriksaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
