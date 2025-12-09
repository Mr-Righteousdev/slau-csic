<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'rating',
        'content_quality',
        'instructor_rating',
        'pace_rating',
        'feedback_text',
        'suggestions',
        'is_anonymous',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
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
}
