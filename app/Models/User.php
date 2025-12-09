<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Impersonate, Notifiable;

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
        'privacy_settings',
        'approval_notes',
        'approved_by',
        'approved_at',
        'suspension_reason',
        'suspended_until',
        'suspended_by',
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
            'privacy_settings' => 'array',
            'approved_at' => 'datetime',
            'suspended_until' => 'datetime',
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

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
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
            if (Storage::exists('public/'.$this->profile_photo)) {
                return Storage::url($this->profile_photo);
            }

            // Check if it's in public storage
            if (file_exists(public_path('storage/'.$this->profile_photo))) {
                return asset('storage/'.$this->profile_photo);
            }
        }

        // Fallback to generated avatar
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=FFFFFF&background=6366f1&bold=true';
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

    // ============================================
    // MEMBER MANAGEMENT METHODS
    // ============================================

    // Relationships for approval workflow
    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedUsers()
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    public function suspendedByUser()
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    public function suspendedUsers()
    {
        return $this->hasMany(User::class, 'suspended_by');
    }

    // Privacy Settings Methods
    public function getPrivacySettingsAttribute($value): array
    {
        $defaults = [
            'show_email' => false,
            'show_phone' => false,
            'show_discord' => true,
            'show_attendance' => false,
            'show_program' => true,
            'show_year' => true,
            'allow_contact' => true,
            'show_profile' => true,
        ];

        return array_merge($defaults, $value ? json_decode($value, true) : []);
    }

    public function setPrivacySettingsAttribute($value)
    {
        $this->attributes['privacy_settings'] = is_array($value) ? json_encode($value) : $value;
    }

    public function canShowField(string $field): bool
    {
        return ($this->privacy_settings[$field] ?? false) && $this->show_profile;
    }

    public function canBeContacted(): bool
    {
        return ($this->privacy_settings['allow_contact'] ?? true) && $this->show_profile;
    }

    // Approval Workflow Methods
    public function isPendingApproval(): bool
    {
        return $this->membership_status === 'pending' && is_null($this->approved_at);
    }

    public function isApproved(): bool
    {
        return ! is_null($this->approved_at) && $this->membership_status !== 'rejected';
    }

    public function isRejected(): bool
    {
        return $this->membership_status === 'rejected';
    }

    public function approve(?User $approver = null, ?string $notes = null): bool
    {
        try {
            $this->update([
                'membership_status' => 'active',
                'approved_at' => now(),
                'approved_by' => $approver?->id,
                'approval_notes' => $notes,
            ]);

            $this->logActivity('member_approved', 'User', $this->id, null, [
                'approved_by' => $approver?->name,
                'approved_at' => now()->toDateTimeString(),
                'notes' => $notes,
            ]);

            // Send approval notification
            $this->notify(new \App\Notifications\MemberApprovalNotification($approver, $notes));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function reject(?User $rejecter = null, ?string $notes = null): bool
    {
        try {
            $this->update([
                'membership_status' => 'rejected',
                'approved_by' => $rejecter?->id,
                'approval_notes' => $notes,
            ]);

            $this->logActivity('member_rejected', 'User', $this->id, null, [
                'rejected_by' => $rejecter?->name,
                'rejected_at' => now()->toDateTimeString(),
                'notes' => $notes,
            ]);

            // Send rejection notification
            $this->notify(new \App\Notifications\MemberRejectionNotification($rejecter, $notes));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function suspend(string $reason, ?User $suspender = null, ?\DateTime $until = null): bool
    {
        try {
            $this->update([
                'membership_status' => 'suspended',
                'suspension_reason' => $reason,
                'suspended_until' => $until,
                'suspended_by' => $suspender?->id,
            ]);

            $this->logActivity('member_suspended', 'User', $this->id, null, [
                'suspended_by' => $suspender?->name,
                'reason' => $reason,
                'suspended_until' => $until ? $until->format('Y-m-d H:i:s') : null,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Auto-alumni detection
    public function shouldBeAlumni(): bool
    {
        if (! $this->year_of_study || $this->membership_type === 'alumni') {
            return false;
        }

        // Assume 4-year program, calculate expected graduation year
        $currentYear = now()->year;
        $expectedGraduationYear = $currentYear + (4 - $this->year_of_study);

        // If current year is past expected graduation, mark as alumni
        return $currentYear > $expectedGraduationYear;
    }

    public function convertToAlumni(): bool
    {
        try {
            $this->update([
                'membership_type' => 'alumni',
                'membership_status' => 'active',
            ]);

            $this->logActivity('member_converted_to_alumni', 'User', $this->id, null, [
                'converted_at' => now()->toDateTimeString(),
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Member Statistics Methods
    public function getMemberStats(): array
    {
        return [
            'total_attendance' => $this->attendance()->count(),
            'attendance_rate' => $this->getAttendanceRate(),
            'events_attended' => $this->eventRegistrations()->where('attended', true)->count(),
            'projects_led' => $this->projects()->count(),
            'projects_participated' => $this->projectMemberships()->count(),
            'trainings_completed' => $this->trainingEnrollments()->where('completed', true)->count(),
            'meetings_this_semester' => $this->meetingsThisSemester(),
            'is_active_this_semester' => $this->isActiveThisSemester(),
            'membership_duration' => $this->joined_at ? now()->diffInMonths($this->joined_at) : 0,
        ];
    }

    // Scopes for member management
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at')
            ->where('membership_status', '!=', 'rejected');
    }

    public function scopeRejected($query)
    {
        return $query->where('membership_status', 'rejected');
    }

    public function scopeSuspended($query)
    {
        return $query->where('membership_status', 'suspended');
    }

    public function scopeAlumni($query)
    {
        return $query->where('membership_type', 'alumni');
    }

    public function scopePubliclyVisible($query)
    {
        return $query->approved()
            ->where('membership_status', 'active')
            ->where(function ($q) {
                $q->where('privacy_settings->show_profile', true)
                    ->orWhereNull('privacy_settings->show_profile');
            });
    }

    // In app/Models/User.php
    public function scopeForDirectory($query)
    {
        return $query->where(function ($q) {
            // Include publicly visible users
            $q->where(function ($inner) {
                $inner->approved()
                    ->where('membership_status', 'active')
                    ->where(function ($privacy) {
                        $privacy->where('privacy_settings->show_profile', true)
                            ->orWhereNull('privacy_settings->show_profile');
                    });
            });

            // Also include admin users regardless of settings
            // ->orWhere(function($inner) {
            //     $inner->where('is_admin', true)
            //         ->orWhereHas('roles', function($roleQuery) {
            //             $roleQuery->where('name', 'admin');
            //         });
            // });
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function finePayments()
    {
        return $this->hasMany(FinePayment::class, 'recorded_by');
    }

    public function issuedFines()
    {
        return $this->hasMany(Fine::class, 'issued_by');
    }

    public function waivedFines()
    {
        return $this->hasMany(Fine::class, 'waived_by');
    }

    public function fineAppeals()
    {
        return $this->hasMany(FineAppeal::class, 'reviewed_by');
    }

    // Accessors for public display
    public function getShowEmailAttribute(): bool
    {
        return $this->canShowField('show_email');
    }

    public function getShowPhoneAttribute(): bool
    {
        return $this->canShowField('show_phone');
    }

    public function getShowDiscordAttribute(): bool
    {
        return $this->canShowField('show_discord');
    }

    public function getShowAttendanceAttribute(): bool
    {
        return $this->canShowField('show_attendance');
    }

    public function getShowProgramAttribute(): bool
    {
        return $this->canShowField('show_program');
    }

    public function getShowYearAttribute(): bool
    {
        return $this->canShowField('show_year');
    }

    public function getShowProfileAttribute(): bool
    {
        return $this->canShowField('show_profile');
    }

    public function getCanBeContactedAttribute(): bool
    {
        return $this->canBeContacted();
    }
}
