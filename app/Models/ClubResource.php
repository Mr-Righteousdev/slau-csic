<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClubResource extends Model
{
    /** @use HasFactory<\Database\Factories\ClubResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'platform',
        'difficulty',
        'status',
        'location',
        'cta_label',
        'external_url',
        'summary',
        'details',
        'target_total',
        'points',
        'starts_at',
        'ends_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(ClubResourceProgress::class);
    }
}
