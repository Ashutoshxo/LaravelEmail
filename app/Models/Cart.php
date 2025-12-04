<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'items',
        'total_amount',
        'last_activity_at',
        'abandoned_email_sent_at'
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'last_activity_at' => 'datetime',
        'abandoned_email_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAbandoned($query, int $minutes = 30)
    {
        return $query->whereNull('abandoned_email_sent_at')
                    ->whereNotNull('last_activity_at')
                    ->where('last_activity_at', '<=', now()->subMinutes($minutes))
                    ->whereJsonLength('items', '>', 0);
    }

    public function markEmailAsSent(): void
    {
        $this->update(['abandoned_email_sent_at' => now()]);
    }
}