<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Impersonate, Notifiable;

    use LogsActivity;

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
        'faculty',
        'year_of_study',
        'date_of_birth',
        'gender',
        'residence',
        'membership_type',
        'membership_status',
        'is_discord_member',
        'discord_username',
        'joined_at',
        'bio',
        'headline',
        'specialization_track',
        'notable_problems_solved',
        'achievements_summary',
        'competition_rank',
        'emergency_contact_name',
        'emergency_contact_phone',
        'github_username',
        'linkedin_url',
        'htb_profile_url',
        'htb_username',
        'htb_profile_data',
        'htb_last_synced_at',
        'profile_photo',
        'privacy_settings',
        'approval_notes',
        'approved_by',
        'approved_at',
        'suspension_reason',
        'suspended_until',
        'suspended_by',
        'attendance_count',
        'total_sessions_attended',
        'current_streak',
        'longest_streak',
        'bonus_points',
        'score',
        'rank',
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
            'date_of_birth' => 'date',
            'is_discord_member' => 'boolean',
            'privacy_settings' => 'array',
            'htb_profile_data' => 'array',
            'htb_last_synced_at' => 'datetime',
            'approved_at' => 'datetime',
            'suspended_until' => 'datetime',
            'rank_changed_at' => 'datetime',
        ];
    }

    protected static $logAttributes = ['name', 'email'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email']);
    }

    public function canImpersonate(): bool
    {
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

    public function memberProjects()
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot(['role', 'joined_at', 'left_at', 'contribution'])
            ->withTimestamps();
    }

    public function competitionParticipations()
    {
        return $this->hasMany(CompetitionParticipants::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, 'competition_participants')
            ->withPivot(['team_name', 'role'])
            ->withTimestamps();
    }

    public function electionVotes(): HasMany
    {
        return $this->hasMany(ElectionVote::class);
    }

    public function clubResourceProgress(): HasMany
    {
        return $this->hasMany(ClubResourceProgress::class);
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
    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile_photo) {
            if (filter_var($this->profile_photo, FILTER_VALIDATE_URL)) {
                return $this->profile_photo;
            }

            if (Storage::exists('public/'.$this->profile_photo)) {
                return Storage::url($this->profile_photo);
            }

            if (file_exists(public_path('storage/'.$this->profile_photo))) {
                return asset('storage/'.$this->profile_photo);
            }
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=FFFFFF&background=6366f1&bold=true';
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->avatar_url;
    }

    public function getAvatarInitialsAttribute(): string
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
        $showProfile = (bool) ($this->privacy_settings['show_profile'] ?? true);

        if ($field === 'show_profile') {
            return $showProfile;
        }

        return (bool) ($this->privacy_settings[$field] ?? false) && $showProfile;
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

            $this->assignRole('member');

            // $this->logActivity('member_approved', 'User', $this->id, null, [
            //     'approved_by' => $approver?->name,
            //     'approved_at' => now()->toDateTimeString(),
            //     'notes' => $notes,
            // ]);

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

            // $this->logActivity('member_rejected', 'User', $this->id, null, [
            //     'rejected_by' => $rejecter?->name,
            //     'rejected_at' => now()->toDateTimeString(),
            //     'notes' => $notes,
            // ]);

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

            // $this->logActivity('member_suspended', 'User', $this->id, null, [
            //     'suspended_by' => $suspender?->name,
            //     'reason' => $reason,
            //     'suspended_until' => $until ? $until->format('Y-m-d H:i:s') : null,
            // ]);

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

            // $this->logActivity('member_converted_to_alumni', 'User', $this->id, null, [
            //     'converted_at' => now()->toDateTimeString(),
            // ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Member Statistics Methods
    public function getMemberStats(): array
    {
        $competitionParticipations = $this->competitionParticipations()->count();
        $portalProgress = $this->clubResourceProgress()->get();

        return [
            'total_attendance' => $this->attendance()->count(),
            'attendance_rate' => $this->getAttendanceRate(),
            'events_attended' => $this->eventRegistrations()->where('status', 'attended')->count(),
            'projects_led' => $this->projects()->count(),
            'projects_participated' => $this->projectMemberships()->count(),
            'competition_entries' => $competitionParticipations,
            'latest_competition_rank' => $this->competition_rank,
            'club_portal_active_tracks' => $portalProgress->where('status', 'in_progress')->count(),
            'club_portal_completed_tracks' => $portalProgress->where('status', 'completed')->count(),
            'club_portal_average_progress' => (int) round($portalProgress->avg('progress_percentage') ?? 0),
            'trainings_completed' => $this->trainingEnrollments()->where('status', 'completed')->count(),
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

    // ============================================
    // LEADERBOARD HELPERS
    // ============================================

    public function getTeachingSessionAttendanceRate(): float
    {
        $totalSessions = Meeting::teachingSessions()
            ->completedTeachingSessions()
            ->count();

        if ($totalSessions === 0) {
            return 0;
        }

        $attendedSessions = $this->attendance()
            ->whereHas('meeting', function ($query) {
                $query->teachingSessions()
                    ->completedTeachingSessions();
            })
            ->whereIn('status', ['present', 'late'])
            ->count();

        return round(($attendedSessions / $totalSessions) * 100, 2);
    }

    public function getTeachingSessionsAttended(): int
    {
        return $this->attendance()
            ->whereHas('meeting', function ($query) {
                $query->teachingSessions()
                    ->completedTeachingSessions();
            })
            ->whereIn('status', ['present', 'late'])
            ->count();
    }

    public function getTotalTeachingSessions(): int
    {
        return Meeting::teachingSessions()
            ->completedTeachingSessions()
            ->count();
    }

    public function calculateScore(): float
    {
        $attendanceRate = $this->getTeachingSessionAttendanceRate();
        $consistencyScore = min($this->current_streak * 10, 100);
        $bonusPoints = $this->bonus_points ?? 0;

        return ($attendanceRate * 0.7) + ($consistencyScore * 0.2) + ($bonusPoints * 0.1);
    }

    public function isEligible(): bool
    {
        $attendanceRate = $this->getTeachingSessionAttendanceRate();
        $sessionsAttended = $this->getTeachingSessionsAttended();

        return $attendanceRate >= 75 && $sessionsAttended >= 5;
    }

    public function getAttendanceRank(): int
    {
        return User::where('score', '>', $this->score)->count() + 1;
    }

    // ============================================
    // GAMIFICATION METHODS
    // ============================================

    /**
     * Rank thresholds for automatic rank upgrades.
     */
    public const RANK_THRESHOLDS = [
        'bronze' => 0,
        'silver' => 200,
        'gold' => 500,
        'platinum' => 1000,
    ];

    /**
     * Get the user's point transactions.
     */
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Get badges earned by this user.
     */
    public function earnedBadges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get the total points earned by this user.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->pointTransactions()->sum('points') ?? 0;
    }

    /**
     * Get the current rank based on total points.
     */
    public function getCurrentRankAttribute(): string
    {
        $points = $this->total_points;

        if ($points >= self::RANK_THRESHOLDS['platinum']) {
            return 'platinum';
        }
        if ($points >= self::RANK_THRESHOLDS['gold']) {
            return 'gold';
        }
        if ($points >= self::RANK_THRESHOLDS['silver']) {
            return 'silver';
        }

        return 'bronze';
    }

    /**
     * Sync the user's rank based on their current total points.
     */
    public function syncRank(): void
    {
        $newRank = $this->current_rank;

        if ($this->rank !== $newRank) {
            $this->update([
                'rank' => $newRank,
                'rank_changed_at' => now(),
            ]);
        }
    }
}
