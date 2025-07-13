<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis'; // Nama tabel eksplisit

    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'pemeriksaan_id',
        'tanggal_rekam_medis',
        'riwayat_penyakit',
        'hasil_pemeriksaan',
        'tindakan',
        'resep_obat_text',
    ];

    protected $casts = [
        'tanggal_rekam_medis' => 'date',
    ];

    /**
     * Get the pasien that owns the medical record.
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the dokter that created the medical record.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    /**
     * Get the pemeriksaan associated with the medical record.
     */
    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class);
    }
}
