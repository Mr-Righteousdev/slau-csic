<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CtfCompetition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'status',
        'is_public',
        'max_score',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function challenges(): HasMany
    {
        return $this->hasMany(CtfChallenge::class);
    }

    public function activeChallenges(): HasMany
    {
        return $this->hasMany(CtfChallenge::class)->where('is_active', true);
    }

    public function submissions(): HasManyThrough
    {
        return $this->hasManyThrough(CtfSubmission::class, CtfChallenge::class);
    }

    public function writeups(): HasManyThrough
    {
        return $this->hasManyThrough(CtfWriteup::class, CtfChallenge::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeCurrentlyActive($query)
    {
        return $query->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    public function isActive(): bool
    {
        return $this->status === 'published' && $this->is_public &&
            $this->start_date <= now() &&
            ($this->end_date === null || $this->end_date >= now());
    }

    public function solvedChallengesCount($user): int
    {
        return $this->challenges()
            ->whereHas('submissions', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('is_correct', true);
            })
            ->count();
    }
}
