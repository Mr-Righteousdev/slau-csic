<?php

namespace App\Livewire\Teaching;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherEvents extends Component
{
    public $events = [];

    public $viewMode = 'list';

    public $filter = 'upcoming';

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $user = Auth::user();

        $query = Event::query()
            ->published()
            ->where(function ($q) use ($user) {
                $q->where('is_public', true)
                    ->orWhere('organizer_id', $user->id);
            });

        if ($this->filter === 'upcoming') {
            $query->where('start_date', '>=', now());
        } elseif ($this->filter === 'past') {
            $query->where('end_date', '<', now());
        }

        $this->events = $query->orderBy('start_date', 'asc')->get();
    }

    public function updatedFilter()
    {
        $this->loadEvents();
    }

    public function rsvp(Event $event, string $status)
    {
        $user = Auth::user();

        EventRegistration::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $user->id,
            ],
            [
                'rsvp_status' => $status,
                'registered_at' => now(),
            ]
        );

        $this->loadEvents();
        $this->dispatch('notify', ['message' => 'RSVP updated to '.ucfirst($status)]);
    }

    public function getUserRsvpStatus(Event $event): ?string
    {
        $user = Auth::user();
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        return $registration?->rsvp_status;
    }

    public function render()
    {
        return view('livewire.teaching.teacher-events');
    }
}
