<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'site_notifications';

    protected $fillable = [
        'id_user',
        'title',
        'message',
        'type',
        'category',
        'link',
        'icon',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForCategory($query, ?string $category)
    {
        if (empty($category) || $category === 'all' || $category === 'semua') {
            return $query;
        }

        // Jika 'seller' atau 'penjual'
        if ($category === 'seller' || $category === 'penjual') {
            return $query->whereIn('category', ['seller', 'penjual', 'all']);
        }

        // Jika 'buyer' atau 'pembeli'
        if ($category === 'buyer' || $category === 'pembeli') {
            return $query->whereIn('category', ['buyer', 'pembeli', 'all']);
        }

        return $query->where('category', $category);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function markAsRead(): bool
    {
        if ($this->isUnread()) {
            return $this->update(['read_at' => now()]);
        }

        return true;
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match (strtolower($this->type)) {
            'event' => 'bg-success-subtle text-success',
            'marketplace', 'produk' => 'bg-warning-subtle text-warning',
            'club', 'komunitas' => 'bg-danger-subtle text-danger',
            'akun', 'profil' => 'bg-info-subtle text-info',
            default => 'bg-primary-subtle text-primary',
        };
    }

    public function getTypeIconAttribute(): string
    {
        if (!empty($this->attributes['icon'])) {
            return $this->attributes['icon'];
        }

        return match (strtolower($this->type)) {
            'event' => 'bi-calendar-event',
            'marketplace', 'produk' => 'bi-bag-check',
            'club', 'komunitas' => 'bi-shield-shaded',
            'akun', 'profil' => 'bi-person-circle',
            default => 'bi-bell-fill',
        };
    }
}
