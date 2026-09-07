<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'deskripsi',
        'poster',
    ];

    protected $casts = [
        'poster' => 'array',
    ];

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
