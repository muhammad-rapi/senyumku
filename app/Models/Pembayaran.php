<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemeriksaan_id',
        'pasien_id',
        'tanggal_pembayaran',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'biaya_pemeriksaan',
        'biaya_obat',
        'status',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_pembayaran' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($pembayaran) {
            $pembayaran->pasien_id = $pembayaran->pemeriksaan->pasien_id;
        });
    }

    /**
     * Get the pemeriksaan that owns the pembayaran.
     */
    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class);
    }

    /**
     * Get the pasien that made the payment.
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }
}
