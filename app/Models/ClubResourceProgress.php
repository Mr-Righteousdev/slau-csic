<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubResourceProgress extends Model
{
    /** @use HasFactory<\Database\Factories\ClubResourceProgressFactory> */
    use HasFactory;

    protected $table = 'club_resource_progress';

    protected $fillable = [
        'club_resource_id',
        'user_id',
        'status',
        'progress_percentage',
        'completed_units',
        'score',
        'ranking',
        'notes',
        'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'last_activity_at' => 'datetime',
        ];
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(ClubResource::class, 'club_resource_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
