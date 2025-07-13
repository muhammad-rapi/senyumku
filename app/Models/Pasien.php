<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'telepon',
        'tanggal_lahir',
    ];

    /**
     * Get the pendaftarans for the pasien.
     */
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    /**
     * Get the pemeriksaans for the pasien.
     */
    public function pemeriksaans(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    /**
     * Get the rekam medis for the pasien.
     */
    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    /**
     * Get the pembayarans for the pasien.
     */
    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }
}
