<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marketplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'nama',
        'deskripsi',
        'logo',
        'status',
    ];

    public function setStatusAttribute($value): void
    {
        $val = strtolower(trim((string) $value));
        if ($val === 'pending') $val = 'panding';
        if ($val === 'terima' || $val === 'terimakasih' || $val === 'active') $val = 'diterima';
        if ($val === 'ditolak' || $val === 'inactive') $val = 'tolak';
        $this->attributes['status'] = in_array($val, ['panding', 'tolak', 'diterima']) ? $val : 'panding';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_marketplace');
    }
}
