<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventInstructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'role',
        'guest_details',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPrimary(): bool
    {
        return $this->role === 'primary';
    }

    public function isCoInstructor(): bool
    {
        return $this->role === 'co-instructor';
    }

    public function isGuestSpeaker(): bool
    {
        return $this->role === 'guest_speaker';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }
}
