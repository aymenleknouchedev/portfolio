<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'addon_id',
        'user_id',
        'purchase_id',
        'stripe_subscription_id',
        'status',
        'is_lifetime',
        'machine_id',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_lifetime' => 'boolean',
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

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
