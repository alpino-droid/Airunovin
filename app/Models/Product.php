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
        'unit',
        'sparepart',
        'aksesoris',
        'jenis',
        'kondisi',
        'stok',
        'lokasi',
        'deskripsi',
        'gambar',
        'payment_methods',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payment_methods' => 'array',
        ];
    }

    public function setStatusAttribute($value): void
    {
        $val = strtolower(trim((string) $value));
        if ($val === 'pending') $val = 'panding';
        if ($val === 'terima' || $val === 'terimakasih' || $val === 'active') $val = 'diterima';
        if ($val === 'ditolak' || $val === 'inactive') $val = 'tolak';
        $this->attributes['status'] = in_array($val, ['panding', 'tolak', 'diterima']) ? $val : 'panding';
    }

    public function setUnitAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['unit'] = null;
            return;
        }

        $val = strtolower(trim((string) $value));
        if (str_contains($val, 'shotgun') || str_contains($val, 'shootgun')) {
            $val = 'shootgun';
        } elseif (str_contains($val, 'machine') || str_contains($val, 'macine')) {
            $val = 'macinegun';
        } elseif (str_contains($val, 'sniper')) {
            $val = 'sniper';
        } elseif (str_contains($val, 'handgun') || str_contains($val, 'pistol')) {
            $val = 'handgun';
        } elseif (str_contains($val, 'rifle')) {
            $val = 'rifle';
        }

        $this->attributes['unit'] = in_array($val, ['rifle', 'shootgun', 'macinegun', 'sniper', 'handgun']) ? $val : null;
    }

    public function getJenisAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }
        return $this->unit ?: ($this->sparepart ?: ($this->aksesoris ?: $this->merk));
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
