<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'cover_image', 'price',
        'demo_video_url', 'features', 'screenshots', 'is_featured', 'file_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'screenshots' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(AddonCategory::class, 'category_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
