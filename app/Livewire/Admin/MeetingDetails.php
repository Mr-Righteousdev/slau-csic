<?php

namespace App\Livewire\Admin;

use App\Models\Attendance;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MeetingDetails extends Component
{
    public Meeting $meeting;

    public $showQrCode = false;

    public $searchTerm = '';

    public $selectedUsers = [];

    public $showManualAttendanceModal = false;

    // Real-time polling
    public function getListeners()
    {
        return [
            "echo-private:meetings.{$this->meeting->id},AttendanceRecorded" => 'refreshAttendance',
        ];
    }

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function openAttendance()
    {
        if (! Auth::user()->can('open_attendance')) {
            session()->flash('error', 'You do not have permission to open attendance.');

            return;
        }

        $this->meeting->openAttendance();

        Auth::user()->logActivity('opened_attendance', 'Meeting', $this->meeting->id);

        session()->flash('success', 'Attendance is now open!');

        // Broadcast to all connected clients
        // broadcast(new AttendanceOpened($this->meeting))->toOthers();
    }

    public function closeAttendance()
    {
        if (! Auth::user()->can('close_attendance')) {
            session()->flash('error', 'You do not have permission to close attendance.');

            return;
        }

        $this->meeting->closeAttendance();

        Auth::user()->logActivity('closed_attendance', 'Meeting', $this->meeting->id);

        session()->flash('success', 'Attendance has been closed.');

        $this->showQrCode = false;
    }

    public function toggleQrCode()
    {
        $this->showQrCode = ! $this->showQrCode;
    }

    public function openManualAttendanceModal()
    {
        $this->showManualAttendanceModal = true;
        $this->selectedUsers = [];
        $this->searchTerm = '';
    }

    public function closeManualAttendanceModal()
    {
        $this->showManualAttendanceModal = false;
        $this->selectedUsers = [];
        $this->searchTerm = '';
    }

    public function recordManualAttendance()
    {
        if (! Auth::user()->can('record_attendance_manual')) {
            session()->flash('error', 'You do not have permission to record attendance manually.');

            return;
        }

        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Please select at least one member.');

            return;
        }

        $recorded = 0;
        $skipped = 0;

        foreach ($this->selectedUsers as $userId) {
            $user = User::find($userId);

            if (! $user) {
                continue;
            }

            // Check if already checked in
            if ($this->meeting->hasUserAttended($user)) {
                $skipped++;

                continue;
            }

            // Record attendance
            $this->meeting->recordAttendance($user, 'manual', [
                'marked_by' => Auth::id(),
                'ip_address' => request()->ip(),
            ]);

            $user->updateAttendanceCount();
            $recorded++;
        }

        $this->closeManualAttendanceModal();

        session()->flash('success', "Recorded attendance for {$recorded} member(s). {$skipped} already checked in.");

        // Refresh the component
        $this->meeting->refresh();
    }

    public function removeAttendance($attendanceId)
    {
        if (! Auth::user()->can('edit_attendance')) {
            session()->flash('error', 'You do not have permission to remove attendance.');

            return;
        }

        $attendance = Attendance::find($attendanceId);

        if ($attendance && $attendance->meeting_id === $this->meeting->id) {
            $user = $attendance->user;
            $attendance->delete();
            $user->updateAttendanceCount();

            session()->flash('success', 'Attendance record removed.');

            $this->meeting->refresh();
        }
    }

    public function refreshAttendance()
    {
        $this->meeting->refresh();
    }

    public function getAvailableMembersProperty()
    {
        $query = User::activeMembers()
            ->whereNotIn('id', $this->meeting->attendees->pluck('id'));

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('email', 'like', '%'.$this->searchTerm.'%')
                    ->orWhere('student_id', 'like', '%'.$this->searchTerm.'%');
            });
        }

        return $query->limit(50)->get();
    }

    public function render()
    {
        return view('livewire.admin.meeting-details', [
            'attendees' => $this->meeting->attendance()
                ->with('user')
                ->orderBy('checked_in_at', 'desc')
                ->get(),
            'availableMembers' => $this->showManualAttendanceModal ? $this->availableMembers : collect(),
        ]);
    }
}
