<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Support\Facades\Storage;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'phone',
        'program',
        'year_of_study',
        'membership_type',
        'membership_status',
        'is_discord_member',
        'discord_username',
        'joined_at',
        'bio',
        'github_username',
        'linkedin_url',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {

        return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'joined_at' => 'date',
        'is_discord_member' => 'boolean',
    ];
    }

     public function canImpersonate()
    {
        // For example
        return $this->hasRole('super-admin');
    }

    public function canBeImpersonated(): bool
    {
        return ! $this->hasRole('super-admin');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'created_by');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'lead_id');
    }

    public function projectMemberships()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'instructor_id');
    }

    public function trainingEnrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActiveMembers($query)
    {
        return $query->where('membership_status', 'active')
                    ->where('membership_type', 'active');
    }

    public function scopeAssociateMembers($query)
    {
        return $query->where('membership_type', 'associate');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('membership_status', 'pending');
    }

    public function scopeExecutiveBoard($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->whereIn('name', [
                'president',
                'vice_president',
                'secretary',
                'treasurer',
                'head_projects',
                'head_ctf',
                'head_media',
                'head_innovations',
                'head_discipline',
            ]);
        });
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function isActiveMember(): bool
    {
        return $this->membership_status === 'active'
            && $this->membership_type === 'active';
    }

    public function isExecutiveMember(): bool
    {
        return $this->hasAnyRole([
            'admin',
            'president',
            'vice_president',
            'secretary',
            'treasurer',
            'head_projects',
            'head_ctf',
            'head_media',
            'head_innovations',
            'head_discipline',
        ]);
    }

    public function canVote(): bool
    {
        return $this->isActiveMember() && $this->hasPermissionTo('vote_in_elections');
    }

    public function getAttendanceRate(): float
    {
        $totalMeetings = Meeting::where('scheduled_at', '<=', now())
            ->where('created_at', '>=', Carbon::parse($this->joined_at))
            ->count();

        if ($totalMeetings === 0) {
            return 0;
        }

        $attendedMeetings = $this->attendance()->count();

        return round(($attendedMeetings / $totalMeetings) * 100, 2);
    }

    public function hasAttendedMeeting(Meeting $meeting): bool
    {
        return $this->attendance()
            ->where('meeting_id', $meeting->id)
            ->exists();
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->email;
    }

    public function getRoleNamesAttribute(): string
    {
        return $this->roles->pluck('name')->map(function ($role) {
            return ucfirst(str_replace('_', ' ', $role));
        })->join(', ');
    }

    // ============================================
    // ACTIVITY TRACKING
    // ============================================

    public function logActivity(string $action, string $model, $modelId, array $oldValues = null, array $newValues = null)
    {
        return ActivityLog::create([
            'user_id' => $this->id,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => "$action $model",
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // In User model
    public function getAvatarUrlAttribute()
    {
        // If user has a custom profile photo
        if ($this->profile_photo) {
            // Check if it's a full URL
            if (filter_var($this->profile_photo, FILTER_VALIDATE_URL)) {
                return $this->profile_photo;
            }

            // Check if it's in storage
            if (Storage::exists('public/' . $this->profile_photo)) {
                return Storage::url($this->profile_photo);
            }

            // Check if it's in public storage
            if (file_exists(public_path('storage/' . $this->profile_photo))) {
                return asset('storage/' . $this->profile_photo);
            }
        }

        // Fallback to generated avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=6366f1&bold=true';
    }

    public function getAvatarInitialsAttribute()
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    // ============================================
    // ATTENDANCE METHODS
    // ============================================

    public function updateAttendanceCount()
    {
        $this->update([
            'attendance_count' => $this->attendance()->count(),
        ]);
    }

    public function meetingsThisSemester()
    {
        // Assuming semester starts in September or February
        $semesterStart = now()->month >= 9
            ? now()->setMonth(9)->startOfMonth()
            : now()->setMonth(2)->startOfMonth();

        return $this->attendance()
            ->whereHas('meeting', function ($query) use ($semesterStart) {
                $query->where('scheduled_at', '>=', $semesterStart);
            })
            ->count();
    }

    public function isActiveThisSemester(): bool
    {
        // Active = attended at least 2 meetings this semester (per constitution)
        return $this->meetingsThisSemester() >= 2;
    }
}
