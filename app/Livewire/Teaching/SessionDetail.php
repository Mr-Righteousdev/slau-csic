<?php

namespace App\Livewire\Teaching;

use App\Models\Meeting;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\LeaderboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SessionDetail extends Component
{
    public Meeting $meeting;

    protected $listeners = ['refreshAttendance' => '$refresh'];

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting->load(['attendance.user']);
    }

    public function startSession()
    {
        if (! Auth::user()->can('mark attendance')) {
            session()->flash('error', 'You do not have permission to start the session.');

            return;
        }

        $this->meeting->update([
            'started_at' => now(),
            'attendance_open' => true,
        ]);

        $this->meeting->refresh();
        session()->flash('success', 'Attendance is now open!');
    }

    public function endSession()
    {
        if (! Auth::user()->can('mark attendance')) {
            session()->flash('error', 'You do not have permission to end the session.');

            return;
        }

        $attendanceService = app(AttendanceService::class);
        $attendanceService->finalizeAbsences($this->meeting);

        $this->meeting->closeAttendance();
        $this->meeting->refresh();

        $leaderboardService = app(LeaderboardService::class);
        $leaderboardService->recalculateAll();

        session()->flash('success', 'Session ended. Scores have been recalculated.');
    }

    public function getAttendeesProperty()
    {
        return $this->meeting->attendance()
            ->with('user')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'name' => $attendance->user->name,
                    'student_id' => $attendance->user->student_id,
                    'avatar_url' => $attendance->user->avatar_url,
                    'status' => $attendance->status,
                    'status_display' => $attendance->status_display,
                    'checked_in_at' => $attendance->checked_in_at,
                    'check_in_method' => $attendance->check_in_method,
                    'is_auto_absent' => $attendance->is_auto_absent,
                ];
            });
    }

    public function getActiveMembersProperty()
    {
        return User::activeMembers()
            ->whereDoesntHave('attendance', function ($query) {
                $query->where('meeting_id', $this->meeting->id);
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'student_id' => $user->student_id,
                    'avatar_url' => $user->avatar_url,
                ];
            });
    }

    public function canStartSession(): bool
    {
        $allowedStartTime = $this->meeting->scheduled_at->copy()->subMinutes(15);

        return Auth::user()->can('mark attendance')
            && now()->gte($allowedStartTime)
            && ! $this->meeting->hasStarted();
    }

    public function canEndSession(): bool
    {
        return Auth::user()->can('mark attendance')
            && $this->meeting->attendance_open;
    }

    public function render()
    {
        return view('livewire.teaching.session-detail');
    }
}
