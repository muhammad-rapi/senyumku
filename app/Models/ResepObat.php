<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResepObat extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemeriksaan_id',
        'dokter_id',
        'tanggal_resep',
        'instruksi_umum',
    ];

    protected $casts = [
        'tanggal_resep' => 'date',
    ];

    /**
     * Get the pemeriksaan that owns the resep obat.
     */
    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class);
    }

    /**
     * Get the dokter that issued the resep obat.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function resepObatDetails(): HasMany
    {
        return $this->hasMany(ResepObatDetail::class);
    }
}
