<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'registered_at',
        'attended_at',
        'notes',
        'custom_fields',
        'payment_completed',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'attended_at' => 'datetime',
            'custom_fields' => 'array',
            'payment_completed' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasAttended(): bool
    {
        return ! is_null($this->attended_at);
    }

    public function isWaitlisted(): bool
    {
        return $this->status === 'waitlist';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
