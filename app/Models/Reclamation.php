<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $fillable = [
        'user_id', 'purchase_id', 'subject', 'message',
        'status', 'admin_reply', 'replied_at',
        'is_read_admin', 'is_read_client',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
            'is_read_admin' => 'boolean',
            'is_read_client' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'open' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            'in_progress' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            'resolved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'closed' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
            default => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => ucfirst($this->status),
        };
    }
}
