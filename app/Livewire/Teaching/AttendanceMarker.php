<?php

namespace App\Livewire\Teaching;

use App\Models\Meeting;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AttendanceMarker extends Component
{
    public Meeting $session;

    public ?string $selectedUserId = null;

    public string $selectedStatus = 'present';

    public function mount(Meeting $session)
    {
        $this->session = $session;
    }

    public function markAttendance()
    {
        if (! $this->selectedUserId) {
            session()->flash('error', 'Please select a member.');

            return;
        }

        if (! Auth::user()->can('mark attendance')) {
            session()->flash('error', 'You do not have permission to mark attendance.');

            return;
        }

        $user = User::find($this->selectedUserId);
        if (! $user) {
            session()->flash('error', 'User not found.');

            return;
        }

        $attendanceService = app(AttendanceService::class);
        $attendanceService->markManually($this->session, $user, $this->selectedStatus, Auth::user());

        $this->selectedUserId = null;
        $this->selectedStatus = 'present';

        $this->dispatch('refreshAttendance');
        session()->flash('success', 'Attendance marked successfully!');
    }

    public function updateStatus(string $attendanceId, string $status)
    {
        if (! Auth::user()->can('mark attendance')) {
            session()->flash('error', 'You do not have permission to update attendance.');

            return;
        }

        $attendance = $this->session->attendance()->find($attendanceId);
        if ($attendance) {
            $attendance->update([
                'status' => $status,
                'check_in_method' => 'admin_override',
                'marked_by' => Auth::id(),
            ]);

            $this->dispatch('refreshAttendance');
            session()->flash('success', 'Attendance updated!');
        }
    }

    public function getAttendeesProperty()
    {
        return $this->session->attendance()
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
                ];
            });
    }

    public function getUnmarkedMembersProperty()
    {
        return User::activeMembers()
            ->whereDoesntHave('attendance', function ($query) {
                $query->where('meeting_id', $this->session->id);
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

    public function render()
    {
        return view('livewire.teaching.attendance-marker', [
            'attendees' => $this->attendees,
            'unmarkedMembers' => $this->unmarkedMembers,
        ]);
    }
}
