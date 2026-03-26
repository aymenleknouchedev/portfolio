<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'price_range',
        'example_image', 'is_active', 'whatsapp_number',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getWhatsappUrl(): string
    {
        $phone = $this->whatsapp_number
            ?: \App\Models\Setting::get('social_whatsapp')
            ?: config('fraxionfx.whatsapp_number');

        // Strip https://wa.me/ prefix if present (stored as full URL in social settings)
        $phone = preg_replace('#^https?://wa\.me/#', '', $phone);

        return "https://wa.me/{$phone}?text=" . urlencode("Hello, I'm interested in your {$this->title} service.");
    }
}
