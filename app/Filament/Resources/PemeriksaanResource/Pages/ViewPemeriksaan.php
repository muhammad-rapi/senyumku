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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $resepObat = $this->record->resepObat;

        if ($resepObat) {
            $data['resepObatDetails'] = $resepObat->resepObatDetails->map(function ($detail) {
                return [
                    'obat_id' => $detail->obat_id,
                    'jumlah' => $detail->jumlah,
                    'dosis' => $detail->dosis,
                ];
            })->toArray();
        } else {
            $data['resepObatDetails'] = [];
        }

        return $data;
    }
}
