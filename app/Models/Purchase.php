<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'addon_id', 'paypal_order_id',
        'amount', 'quantity', 'license_tier', 'status', 'download_token', 'expires_at',
        'promo_code', 'promo_discount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'promo_discount' => 'decimal:2',
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }

    public function license()
    {
        return $this->hasOne(License::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
