<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class event extends Model
{
    use HasFactory;

    public const MAX_POSTERS = 3;

    protected $table = 'event';

    protected $fillable = [
        'id_user',
        'nama',
        'tanggal',
        'penyelenggara',
        'lokasi',
        'id_provinsi',
        'kota',
        'sumber',
        'htm',
        'kelasPertandingan',
        'deskripsi',
        'poster',
    ];

    protected $casts = [
        'poster' => 'array',
        'tanggal' => 'date',
    ];

    public function getPosterAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return $decoded;
            }

            if (is_string($decoded) && $decoded !== '') {
                return [$decoded];
            }
        }

        return [];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public static function posterValidationRules(): array
    {
        return [
            'poster' => ['nullable', 'array', 'max:' . self::MAX_POSTERS],
            'poster.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    public static function maxPosterCount(): int
    {
        return self::MAX_POSTERS;
    }
}
