<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat',
        'satuan',
        'stok',
        'harga',
        'deskripsi',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

}
