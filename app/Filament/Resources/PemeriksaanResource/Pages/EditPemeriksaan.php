<?php

namespace App\Filament\Resources\PemeriksaanResource\Pages;

use App\Filament\Resources\PemeriksaanResource;
use App\Models\Obat;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPemeriksaan extends EditRecord
{
    protected static string $resource = PemeriksaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $resepObatDetails = $data['resepObatDetails'] ?? [];
        unset($data['resepObatDetails']);

        $this->record->update($data);

        $resepObat = $this->record->resepObat;

        if ($resepObat) {
            foreach ($resepObat->resepObatDetails as $oldDetail) {
                $obat = Obat::find($oldDetail->obat_id);
                if ($obat) {
                    $obat->stok += $oldDetail->jumlah;
                    $obat->save();
                }
            }

            $resepObat->resepObatDetails()->delete();

            foreach ($resepObatDetails as $detail) {
                $resepObat->resepObatDetails()->create([
                    'obat_id' => $detail['obat_id'],
                    'jumlah' => $detail['jumlah'],
                    'dosis' => $detail['dosis'],
                ]);

                $obat = Obat::find($detail['obat_id']);
                if ($obat) {
                    $obat->stok -= $detail['jumlah'];
                    $obat->save();
                }
            }
        }

        return $this->record;
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
