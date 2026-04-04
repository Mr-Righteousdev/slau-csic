<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionCandidate extends Model
{
    /** @use HasFactory<\Database\Factories\ElectionCandidateFactory> */
    use HasFactory;

    protected $fillable = [
        'election_id',
        'user_id',
        'name',
        'photo',
        'manifesto',
        'agenda',
        'sort_order',
    ];

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ElectionVote::class);
    }
}
