<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Support\Collection;

class EligibilityService
{
    public function isEligible(User $user): bool
    {
        $attendanceRate = $this->getAttendanceRate($user);
        $sessionsAttended = $this->getSessionsAttended($user);

        return $attendanceRate >= 75 && $sessionsAttended >= 5;
    }

    public function getEligibilityDetails(User $user): array
    {
        $attendanceRate = $this->getAttendanceRate($user);
        $sessionsAttended = $this->getSessionsAttended($user);
        $totalSessions = $this->getTotalSessions();

        return [
            'is_eligible' => $attendanceRate >= 75 && $sessionsAttended >= 5,
            'attendance_rate' => $attendanceRate,
            'sessions_attended' => $sessionsAttended,
            'total_sessions' => $totalSessions,
            'meets_attendance_threshold' => $attendanceRate >= 75,
            'meets_sessions_threshold' => $sessionsAttended >= 5,
        ];
    }

    public function getAllEligibleMembers(): Collection
    {
        return User::activeMembers()
            ->get()
            ->filter(fn ($user) => $this->isEligible($user));
    }

    public function getAllIneligibleMembers(): Collection
    {
        return User::activeMembers()
            ->get()
            ->filter(fn ($user) => ! $this->isEligible($user));
    }

    public function getEligibilityStats(): array
    {
        $allMembers = User::activeMembers()->get();
        $totalMembers = $allMembers->count();
        $eligibleMembers = $allMembers->filter(fn ($user) => $this->isEligible($user))->count();
        $ineligibleMembers = $totalMembers - $eligibleMembers;

        $totalSessions = $this->getTotalSessions();
        $averageAttendance = $totalMembers > 0
            ? round($allMembers->avg(fn ($user) => $this->getAttendanceRate($user)), 2)
            : 0;

        return [
            'total_members' => $totalMembers,
            'eligible_members' => $eligibleMembers,
            'ineligible_members' => $ineligibleMembers,
            'eligibility_rate' => $totalMembers > 0 ? round(($eligibleMembers / $totalMembers) * 100, 2) : 0,
            'total_sessions' => $totalSessions,
            'average_attendance_rate' => $averageAttendance,
        ];
    }

    private function getAttendanceRate(User $user): float
    {
        $totalSessions = $this->getTotalSessions();

        if ($totalSessions === 0) {
            return 0;
        }

        $attendedSessions = $user->attendance()
            ->whereHas('meeting', function ($query) {
                $query->teachingSessions()
                    ->completedTeachingSessions();
            })
            ->whereIn('status', ['present', 'late'])
            ->count();

        return round(($attendedSessions / $totalSessions) * 100, 2);
    }

    private function getSessionsAttended(User $user): int
    {
        return $user->attendance()
            ->whereHas('meeting', function ($query) {
                $query->teachingSessions()
                    ->completedTeachingSessions();
            })
            ->whereIn('status', ['present', 'late'])
            ->count();
    }

    private function getTotalSessions(): int
    {
        return Meeting::teachingSessions()
            ->completedTeachingSessions()
            ->count();
    }
}
