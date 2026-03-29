<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = ['ip_hash', 'visited_date'];

    protected function casts(): array
    {
        return [
            'visited_date' => 'date',
        ];
    }
}
