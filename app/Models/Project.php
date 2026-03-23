<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'hero_image', 'hero_video',
        'gallery', 'software_used', 'process_steps', 'category', 'category_id', 'published_at',
    ];

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    protected function casts(): array
    {
        return [
            'software_used' => 'array',
            'process_steps' => 'array',
            'gallery' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}