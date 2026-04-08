<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'cover_image', 'price',
        'original_price', 'demo_video_url', 'features', 'screenshots', 'is_featured',
        'badge_text', 'file_path', 'requires_license', 'license_price', 'license_tiers',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'license_price' => 'decimal:2',
            'features' => 'array',
            'screenshots' => 'array',
            'license_tiers' => 'array',
            'is_featured' => 'boolean',
            'requires_license' => 'boolean',
        ];
    }

    /**
     * Returns license tiers if defined, otherwise falls back to a single
     * default tier using license_price (or addon price).
     * Each tier: { label, quantity, price }
     */
    public function getEffectiveLicenseTiers(): array
    {
        if (!empty($this->license_tiers)) {
            return $this->license_tiers;
        }
        // Fallback to single tier
        return [[
            'label' => 'Standard License',
            'quantity' => 1,
            'price' => (float) ($this->license_price ?? $this->price),
        ]];
    }

    public function category()
    {
        return $this->belongsTo(AddonCategory::class, 'category_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
