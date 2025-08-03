<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pendaftaran) {
            $pendaftaran->status = 'Selesai';
        });
    }

    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'tanggal_pendaftaran',
        'waktu_pendaftaran',
        'keluhan',
        'status',
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'date',
        'waktu_pendaftaran' => 'datetime',
    ];

    /**
     * Get the pasien that owns the pendaftaran.
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the dokter that owns the pendaftaran.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }
}
