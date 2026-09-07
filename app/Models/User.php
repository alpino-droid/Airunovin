<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'nama',
        'profile_picture',
        'phone',
        'province',
        'city',
        // 'role' → TIDAK ADA, karena tidak ada di database
    ];

    /**
     * Kolom yang disembunyikan
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // ACCESSOR
    // ==========================================

    /**
     * Accessor untuk mendapatkan URL foto profil
     * Default: public/img/blankPhotoProfile.png
     */
    public function getProfilePictureUrlAttribute()
    {
        // Jika ada foto di database dan file-nya ada di storage
        if ($this->profile_picture && file_exists(public_path('storage/' . $this->profile_picture))) {
            return asset('storage/' . $this->profile_picture);
        }
        
        // Jika tidak ada foto, gunakan default dari public/img/
        return asset('img/blankPhotoProfile.png');
    }

    // ==========================================
    // RELASI
    // ==========================================

    /**
     * Relasi ke Club (sebagai pemilik)
     * User memiliki banyak Club
     */
    public function ownedClubs(): HasMany
    {
        return $this->hasMany(Club::class, 'id_user');
    }

    /**
     * Relasi ke Event (sebagai pembuat)
     * User membuat banyak Event
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Relasi ke Marketplace (sebagai pemilik)
     * User memiliki banyak Marketplace
     */
    public function marketplaces(): HasMany
    {
        return $this->hasMany(Marketplace::class);
    }

    // ==========================================
    // PERMISSION CHECK (Tanpa Role)
    // ==========================================

    /**
     * Cek apakah user adalah pemilik club
     */
    public function isOwnerOf(Club $club): bool
    {
        return $club->id_user === $this->id;
    }

    /**
     * Cek apakah user bisa mengedit club
     * Hanya pemilik club yang bisa mengedit
     */
    public function canEditClub(Club $club): bool
    {
        return $club->id_user === $this->id;
    }

    /**
     * Cek apakah user bisa mengedit event
     * Hanya pembuat event yang bisa mengedit
     */
    public function canEditEvent(Event $event): bool
    {
        return $event->user_id === $this->id;
    }

    /**
     * Cek apakah user bisa mengedit marketplace
     * Hanya pemilik marketplace yang bisa mengedit
     */
    public function canEditMarketplace(Marketplace $marketplace): bool
    {
        return $marketplace->user_id === $this->id;
    }
}