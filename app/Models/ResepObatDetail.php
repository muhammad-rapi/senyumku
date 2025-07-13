<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResepObatDetail extends Model
{
    use HasFactory;

    protected $table = 'resep_obat_detail';
    protected $fillable = [
        'obat_id',
        'resep_obat_id',
        'jumlah',
        'dosis',
    ];

    /**
     * Get the pemeriksaan that owns the resep obat.
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    /**
     * Get the dokter that issued the resep obat.
     */
    public function resepObat(): BelongsTo
    {
        return $this->belongsTo(ResepObat::class);
    }

}
