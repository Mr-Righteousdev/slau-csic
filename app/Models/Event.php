<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    protected $fillable = [
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'location',
        'banner_image',
        'gallery',
        'max_participants',
        'registration_required',
        'is_public',
        'registration_deadline',
        'status',
        'organizer_id',
        'requirements',
        'registration_fee',
        'external_link',
    ];

    

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'registration_deadline' => 'datetime',
            'gallery' => 'array',
            'registration_required' => 'boolean',
            'is_public' => 'boolean',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(EventFeedback::class);
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_instructors')
            ->withPivot('role', 'guest_details')
            ->withTimestamps();
    }

    public function resources(): HasMany
    {
        return $this->hasMany(EventResource::class);
    }

    public function recurrence(): HasMany
    {
        return $this->hasMany(EventRecurrence::class);
    }

    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->where('status', 'registered')->count();
    }

    public function getAttendedCountAttribute(): int
    {
        return $this->registrations()->whereNotNull('attended_at')->count();
    }

    public function getIsFullAttribute(): bool
    {
        return $this->max_participants && $this->registered_count >= $this->max_participants;
    }

    public function getRemainingSpotsAttribute(): int
    {
        if (! $this->max_participants) {
            return 999;
        }

        return max(0, $this->max_participants - $this->registered_count);
    }
}
