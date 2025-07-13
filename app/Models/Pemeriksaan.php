<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'dokter_id',
        'tanggal_pemeriksaan',
        'diagnosa',
        'catatan_medis',
        'biaya_pemeriksaan',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'biaya_pemeriksaan' => 'decimal:2',
    ];

    /**
     * Get the pendaftaran associated with the examination.
     */
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    /**
     * Get the pasien that owns the examination.
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the dokter that performed the examination.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    /**
     * Get the rekam medis associated with the examination.
     */
    public function rekamMedis(): HasOne
    {
        return $this->hasOne(RekamMedis::class);
    }

    /**
     * Get the resep obat associated with the examination.
     */
    public function resepObat(): HasOne
    {
        return $this->hasOne(ResepObat::class);
    }

    /**
     * Get the pembayaran associated with the examination.
     */
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }
}

