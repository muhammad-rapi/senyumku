<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'spesialisasi',
        'telepon',
        'email',
    ];

    /**
     * Get the pendaftarans for the dokter.
     */
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    /**
     * Get the pemeriksaans for the dokter.
     */
    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    /**
     * Get the rekam medis for the dokter.
     */
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    /**
     * Get the resep obats for the dokter.
     */
    public function resepObats(): HasMany
    {
        return $this->hasMany(ResepObat::class);
    }
}