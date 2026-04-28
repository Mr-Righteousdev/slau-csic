<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CtfChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'ctf_competition_id',
        'ctf_category_id',
        'title',
        'slug',
        'description',
        'flag_hash',
        'points',
        'difficulty',
        'is_active',
        'hint',
        'hint_cost',
        'max_attempts',
        'tags',
        'sort_order',
    ];

    protected $casts = [
        'points' => 'integer',
        'hint_cost' => 'integer',
        'max_attempts' => 'integer',
        'sort_order' => 'integer',
        'tags' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CtfChallenge $challenge) {
            if (empty($challenge->slug)) {
                $challenge->slug = Str::slug($challenge->title);
            }
        });

        static::updating(function (CtfChallenge $challenge) {
            if (isset($challenge->flag) && ! empty($challenge->flag)) {
                $challenge->flag_hash = hash('sha256', $challenge->flag);
            }
        });
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(CtfCompetition::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CtfCategory::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(CtfSubmission::class);
    }

    public function writeups(): HasMany
    {
        return $this->hasMany(CtfWriteup::class);
    }

    public function setFlagAttribute($flag)
    {
        $this->attributes['flag_hash'] = hash('sha256', $flag);
    }

    public function verifyFlag($flag): bool
    {
        return Hash::check($flag, $this->flag_hash);
    }

    public function isSolvedBy($user): bool
    {
        return $this->submissions()
            ->where('user_id', $user->id)
            ->where('is_correct', true)
            ->exists();
    }

    public function getSolveCount(): int
    {
        return $this->submissions()
            ->where('is_correct', true)
            ->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
