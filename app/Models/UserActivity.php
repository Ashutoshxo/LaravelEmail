<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'time_spent_seconds',
        'session_started_at',
        'last_activity_at',
        'help_email_sent_at',
        'help_email_sent_date',
        'user_agent',
        'ip_address',
    ];

    protected $casts = [
        'session_started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'help_email_sent_at' => 'datetime',
        'help_email_sent_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markHelpEmailAsSent(): void
    {
        $this->update([
            'help_email_sent_at' => now(),
            'help_email_sent_date' => today(),
        ]);
    }
}