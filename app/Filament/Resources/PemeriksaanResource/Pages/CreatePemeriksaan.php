<?php

namespace App\Filament\Resources\PemeriksaanResource\Pages;

use App\Filament\Resources\PemeriksaanResource;
use App\Models\Obat;
use App\Models\Pendaftaran;
use App\Models\RekamMedis;
use App\Models\ResepObat;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePemeriksaan extends CreateRecord
{
    protected static string $resource = PemeriksaanResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $pendaftaran = Pendaftaran::find($data['pendaftaran_id']);
        $data['pasien_id'] = $pendaftaran->pasien_id;
        $resepObatDetails = $data['resepObatDetails'] ?? null;
        unset($data['resepObatDetails']);

        $pemeriksaan = static::getModel()::create($data);

        if (!empty($resepObatDetails)) {
            $resepObat = ResepObat::create([
                'pemeriksaan_id' => $pemeriksaan->id,
                'dokter_id' => $pemeriksaan->dokter_id,
                'tanggal_resep' => $pemeriksaan->tanggal_pemeriksaan,
            ]);

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

        return $pemeriksaan;
    }

    protected function afterCreate(): void
    {
        if ($this->record) {
            RekamMedis::create([
                'pemeriksaan_id' => $this->record->id,
                'pasien_id' => $this->record->pasien_id,
                'dokter_id' => $this->record->dokter_id,
                'tanggal_rekam_medis' => $this->record->tanggal_pemeriksaan,
                'hasil_pemeriksaan' => $this->record->diagnosa,
                'tindakan' => $this->record->catatan_medis,
            ]);
        }
    }
}
