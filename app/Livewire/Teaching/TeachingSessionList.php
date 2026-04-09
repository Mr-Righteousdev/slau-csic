<?php

namespace App\Livewire\Teaching;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TeachingSessionList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public bool $showModal = false;

    protected $listeners = ['refreshSessions' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function startSession(int $meetingId): void
    {
        if (! Auth::user()->can('mark attendance')) {
            session()->flash('error', 'You do not have permission to start sessions.');

            return;
        }

        $meeting = Meeting::findOrFail($meetingId);

        $allowedStartTime = $meeting->scheduled_at->copy()->subMinutes(15);

        if (! now()->gte($allowedStartTime)) {
            session()->flash('error', 'Cannot start session before 15 minutes before scheduled time.');

            return;
        }

        if ($meeting->hasStarted()) {
            session()->flash('error', 'Session has already started.');

            return;
        }

        $meeting->update([
            'started_at' => now(),
            'attendance_open' => true,
        ]);

        session()->flash('success', 'Session started! Attendance is now open.');
        $this->dispatch('refreshSessions');
    }

    public function canStartSession(Meeting $meeting): bool
    {
        $allowedStartTime = $meeting->scheduled_at->copy()->subMinutes(15);

        return Auth::user()->can('mark attendance')
            && now()->gte($allowedStartTime)
            && ! $meeting->hasStarted();
    }

    public function getSessionsProperty()
    {
        return Meeting::teachingSessions()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                return match ($this->statusFilter) {
                    'scheduled' => $query->whereNull('started_at')->where('scheduled_at', '>', now()),
                    'ongoing' => $query->where('attendance_open', true),
                    'completed' => $query->whereNotNull('ended_at'),
                    default => $query,
                };
            })
            ->orderByDesc('scheduled_at')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.teaching.teaching-session-list', [
            'sessions' => $this->sessions,
        ]);
    }
}
