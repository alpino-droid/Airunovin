<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    /**
     * Nama tabel di database
     */
    protected $table = 'clubs';

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'nama',
        'induk_organisasi',
        'deskripsi',
        'logo',
        'id_provinsi',
        'city',
        'gform_link',
        'id_user',
    ];

    // ==========================================
    // RELASI
    // ==========================================

    /**
     * Relasi ke User (Pemilik Club)
     * Club dimiliki oleh 1 User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // ==========================================
    // ACCESSOR
    // ==========================================

    /**
     * Mendapatkan URL logo club
     * Jika logo ada di storage, return URL-nya
     * Jika tidak, return gambar default
     */
    public function getLogoUrlAttribute()
    {
        // Cek apakah logo ada di storage
        if ($this->logo && file_exists(public_path('storage/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }
        
        // Jika tidak ada, return gambar default
        return asset('img/balnkLogo.png');
    }

    /**
     * Mendapatkan singkatan deskripsi
     * Untuk ditampilkan di card/list
     */
    public function getShortDescriptionAttribute($length = 100)
    {
        if (strlen($this->deskripsi) > $length) {
            return substr($this->deskripsi, 0, $length) . '...';
        }
        return $this->deskripsi;
    }

    // ==========================================
    // METHOD BANTUAN
    // ==========================================

    /**
     * Cek apakah user adalah pemilik club
     */
    public function isOwner(User $user): bool
    {
        return $this->id_user === $user->id;
    }

    /**
     * Cek apakah user bisa mengedit club
     * Hanya pemilik yang bisa mengedit
     */
    public function canEdit(User $user): bool
    {
        return $this->id_user === $user->id;
    }
}