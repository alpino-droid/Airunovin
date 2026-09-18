<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_marketplace',
        'nama',
        'harga',
        'merk',
        'jenis',
        'kondisi',
        'stok',
        'lokasi',
        'deskripsi',
        'gambar',
        'payment_methods',
    ];

    protected function casts(): array
    {
        return [
            'payment_methods' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class, 'id_marketplace');
    }
}
