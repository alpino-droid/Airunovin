<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPurchase extends Model
{
    use HasFactory;

    protected $table = 'product_purchases';

    protected $fillable = [
        'id_user',
        'id_seller',
        'id_product',
        'id_marketplace',
        'order_code',
        'nama_produk',
        'gambar_produk',
        'nama_penjual',
        'harga_satuan',
        'jumlah',
        'biaya_pengiriman',
        'total_harga',
        'metode_pengiriman',
        'metode_pembayaran',
        'nama_pembeli',
        'no_wa_pembeli',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'integer',
            'jumlah' => 'integer',
            'biaya_pengiriman' => 'integer',
            'total_harga' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_seller');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class, 'id_marketplace');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match (strtolower(trim($this->status ?? ''))) {
            'selesai', 'success' => 'bg-success',
            'menunggu diproses', 'diproses', 'proses', 'pending' => 'bg-warning text-dark',
            'siap diambil', 'dikirim' => 'bg-info text-dark',
            'dibatalkan', 'batal' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
