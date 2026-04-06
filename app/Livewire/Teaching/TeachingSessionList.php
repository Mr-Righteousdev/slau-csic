<?php

namespace App\Livewire\Teaching;

use App\Models\Meeting;
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
