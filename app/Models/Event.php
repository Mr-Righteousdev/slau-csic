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
        'is_recurring',
        'parent_event_id',
        'cancelled_at',
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
            'is_recurring' => 'boolean',
            'cancelled_at' => 'datetime',
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

    public function getRecurrenceAttribute()
    {
        return $this->recurrence()->first();
    }

    /**
     * Check if this event is a recurring event series master
     */
    public function isRecurring(): bool
    {
        return (bool) $this->is_recurring;
    }

    /**
     * Check if this event is an occurrence of a recurring series
     */
    public function isOccurrence(): bool
    {
        return $this->parent_event_id !== null;
    }

    /**
     * Get the master event for this occurrence
     */
    public function masterEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'parent_event_id');
    }

    /**
     * Get all occurrences of this recurring event
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Event::class, 'parent_event_id');
    }

    /**
     * Check if this is the master event of a series
     */
    public function isMasterEvent(): bool
    {
        return $this->is_recurring && ! $this->parent_event_id;
    }

    /**
     * Get all future occurrences (including the master if future)
     */
    public function getFutureOccurrences()
    {
        $query = Event::where(function ($q) {
            $q->where('parent_event_id', $this->id)
                ->orWhere('id', $this->id);
        })
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc');

        return $query;
    }

    /**
     * Sync changes to all future occurrences
     */
    public function syncOccurrences(): void
    {
        if (! $this->is_recurring) {
            return;
        }

        $fieldsToSync = [
            'description',
            'location',
            'max_participants',
            'registration_required',
            'is_public',
            'registration_deadline',
            'requirements',
            'registration_fee',
            'external_link',
        ];

        $this->occurrences()
            ->where('start_date', '>', now())
            ->update(array_fill_keys($fieldsToSync, null));
    }

    /**
     * Skip a specific occurrence date
     */
    public function skipOccurrence(Event $occurrence): void
    {
        $occurrence->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
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

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
