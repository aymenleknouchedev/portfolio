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
        $raw = $this->whatsapp_number
            ?: \App\Models\Setting::get('contact_phone')
            ?: config('fraxionfx.whatsapp_number');

        // Strip full URL prefix if stored as https://wa.me/NUMBER
        $raw = preg_replace('#^https?://wa\.me/#', '', trim($raw));
        // Keep only digits (strip +, spaces, dashes)
        $phone = preg_replace('/\D/', '', $raw);

        return "https://wa.me/{$phone}?text=" . urlencode("Hello, I'm interested in your {$this->title} service.");
    }
}
