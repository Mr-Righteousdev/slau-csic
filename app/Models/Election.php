<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    /** @use HasFactory<\Database\Factories\ElectionFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'position',
        'description',
        'status',
        'starts_at',
        'ends_at',
        'results_visible',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'results_visible' => 'boolean',
        ];
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(ElectionCandidate::class)->orderBy('sort_order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ElectionVote::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open'
            && (! $this->starts_at || $this->starts_at->isPast())
            && (! $this->ends_at || $this->ends_at->isFuture());
    }
}
