<?php

namespace App\Livewire\Admin;

use App\Models\Meeting;
use Livewire\Component;
use Livewire\WithPagination;

class MeetingList extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, upcoming, past, today

    protected $listeners = ['meeting-created' => '$refresh'];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $query = Meeting::with('creator')
            ->orderBy('scheduled_at', 'desc');

        if ($this->filter === 'upcoming') {
            $query->upcoming();
        } elseif ($this->filter === 'past') {
            $query->past();
        } elseif ($this->filter === 'today') {
            $query->today();
        }

        return view('livewire.admin.meeting-list', [
            'meetings' => $query->paginate(10),
        ]);
    }
}
