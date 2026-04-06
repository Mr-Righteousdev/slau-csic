<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'scheduled_at',
        'started_at',
        'ended_at',
        'location',
        'meeting_code',
        'code_expires_minutes',
        'attendance_open',
        'duration_minutes',
        'expected_attendees',
        'created_by',
        'agenda',
        'minutes',
        'late_threshold_minutes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'attendance_open' => 'boolean',
    ];

    // ============================================
    // BOOT METHOD - Auto-generate meeting code
    // ============================================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($meeting) {
            if (! $meeting->meeting_code) {
                $meeting->meeting_code = self::generateUniqueMeetingCode();
            }
        });
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'attendance')
            ->withPivot('checked_in_at', 'check_in_method')
            ->withTimestamps();
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'desc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeTeachingSessions($query)
    {
        return $query->where('type', 'teaching_session');
    }

    public function scopeCompletedTeachingSessions($query)
    {
        return $query->where('type', 'teaching_session')
            ->whereNotNull('ended_at');
    }

    public function scopeActiveTeachingSessions($query)
    {
        return $query->where('type', 'teaching_session')
            ->where('attendance_open', true);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public static function generateUniqueMeetingCode(): string
    {
        do {
            // Generate 8-character alphanumeric code
            $code = strtoupper(Str::random(8));
        } while (self::where('meeting_code', $code)->exists());

        return $code;
    }

    public function getQrCodeUrl(): string
    {
        return route('attendance.verify', ['code' => $this->meeting_code]);
    }

    public function openAttendance(): void
    {
        $this->update([
            'attendance_open' => true,
            'started_at' => $this->started_at ?? now(),
        ]);
    }

    public function closeAttendance(): void
    {
        $this->update([
            'attendance_open' => false,
            'ended_at' => $this->ended_at ?? now(),
        ]);
    }

    public function getAttendanceCount(): int
    {
        return $this->attendance()->count();
    }

    public function getAttendanceRate(): float
    {
        if ($this->expected_attendees === 0) {
            return 0;
        }

        return round(($this->getAttendanceCount() / $this->expected_attendees) * 100, 2);
    }

    public function isAttendanceOpen(): bool
    {
        return $this->attendance_open;
    }

    public function hasStarted(): bool
    {
        return $this->started_at !== null;
    }

    public function hasEnded(): bool
    {
        return $this->ended_at !== null;
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at > now();
    }

    public function isPast(): bool
    {
        return $this->scheduled_at <= now();
    }

    public function getStatusAttribute(): string
    {
        if ($this->hasEnded()) {
            return 'completed';
        }

        if ($this->attendance_open) {
            return 'ongoing';
        }

        if ($this->isUpcoming()) {
            return 'scheduled';
        }

        return 'past';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'gray',
            'ongoing' => 'green',
            'scheduled' => 'blue',
            'past' => 'gray',
            default => 'gray',
        };
    }

    public function getTypeDisplayAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->type));
    }

    public function hasUserAttended(User $user): bool
    {
        return $this->attendance()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function recordAttendance(User $user, string $method = 'manual', array $additionalData = []): Attendance
    {
        return $this->attendance()->create(array_merge([
            'user_id' => $user->id,
            'checked_in_at' => now(),
            'check_in_method' => $method,
        ], $additionalData));
    }

    // ============================================
    // TEACHING SESSION HELPERS
    // ============================================

    public function isTeachingSession(): bool
    {
        return $this->type === 'teaching_session';
    }

    public function getLateThresholdMinutes(): int
    {
        return $this->late_threshold_minutes ?? 15;
    }

    public function getStartTime(): ?\Carbon\Carbon
    {
        return $this->scheduled_at;
    }

    public function getEndTime(): ?\Carbon\Carbon
    {
        if ($this->ended_at) {
            return $this->ended_at;
        }

        if ($this->scheduled_at && $this->duration_minutes) {
            return $this->scheduled_at->copy()->addMinutes($this->duration_minutes);
        }

        return null;
    }

    public function getPresentCount(): int
    {
        return $this->attendance()->where('status', 'present')->count();
    }

    public function getLateCount(): int
    {
        return $this->attendance()->where('status', 'late')->count();
    }

    public function getAbsentCount(): int
    {
        return $this->attendance()
            ->where('status', 'absent')
            ->orWhereNull('status')
            ->count();
    }

    public function isEligibleForCheckIn(): bool
    {
        if (! $this->isTeachingSession()) {
            return false;
        }

        if (! $this->attendance_open) {
            return false;
        }

        if ($this->hasEnded()) {
            return false;
        }

        return true;
    }
}
