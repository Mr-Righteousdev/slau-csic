<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventCards extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
        'perPage' => ['except' => 12],
    ];

    public function mount()
    {
        // Initialize component
    }

    public function getEventsProperty()
    {
        $query = Event::where('is_public', true)
            ->whereIn('status', ['published', 'ongoing'])
            ->with(['organizer', 'registrations']);

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters
        switch ($this->filter) {
            case 'upcoming':
                $query->where('start_date', '>=', now());
                break;
            case 'past':
                $query->where('start_date', '<', now());
                break;
            case 'registered':
                if (Auth::check()) {
                    $query->whereHas('registrations', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
                }
                break;
            case 'my_events':
                if (Auth::check()) {
                    $query->where('organizer_id', Auth::id());
                }
                break;
            default:
                // Filter by event type if it's a valid type
                if (in_array($this->filter, ['workshop', 'competition', 'ctf', 'bootcamp', 'awareness_campaign', 'talk', 'social', 'hackathon'])) {
                    $query->where('type', $this->filter);
                }
                break;
        }

        return $query->orderBy('start_date', 'asc')
                   ->paginate($this->perPage);
    }

    public function getCanCreateEventsProperty()
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']);
    }

    public function registerForEvent($eventId)
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'Please login to register for events.', type: 'error');
            return;
        }

        $event = Event::findOrFail($eventId);

        // Check if already registered
        $existing = $event->registrations()
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $this->dispatch('show-notification', message: 'You are already registered for this event.', type: 'warning');
            return;
        }

        // Check if event is full
        if ($event->max_participants && $event->registered_count >= $event->max_participants) {
            $this->dispatch('show-notification', message: 'This event is full.', type: 'error');
            return;
        }

        // Register user
        $event->registrations()->create([
            'user_id' => Auth::id(),
            'registered_at' => now(),
            'status' => 'registered',
        ]);

        $this->dispatch('show-notification', message: 'Successfully registered for event!', type: 'success');
    }

    public function unregisterFromEvent($eventId)
    {
        if (!Auth::check()) {
            return;
        }

        $event = Event::findOrFail($eventId);

        $registration = $event->registrations()
            ->where('user_id', Auth::id())
            ->first();

        if ($registration) {
            $registration->delete();
            $this->dispatch('show-notification', message: 'Successfully unregistered from event.', type: 'success');
        } else {
            $this->dispatch('show-notification', message: 'You were not registered for this event.', type: 'warning');
        }
    }

    public function deleteEvent($eventId)
    {
        if (!Auth::check()) {
            return;
        }

        $event = Event::findOrFail($eventId);

        // Only allow organizer or admin to delete
        if ($event->organizer_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            $this->dispatch('show-notification', message: 'You can only delete your own events.', type: 'error');
            return;
        }

        $event->delete();
        $this->dispatch('show-notification', message: 'Event deleted successfully.', type: 'success');
    }

    /**
     * Get event type color
     */
    public function getEventTypeColor($event): string
    {
        return match($event->type) {
            'workshop' => 'green',
            'competition' => 'red',
            'ctf' => 'red',
            'talk' => 'blue',
            'bootcamp' => 'purple',
            'awareness_campaign' => 'yellow',
            'social' => 'indigo',
            'hackathon' => 'red',
            default => 'gray'
        };
    }

    /**
     * Check if user is registered for this event
     */
    public function isUserRegistered($event): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $event->registrations()
            ->where('user_id', Auth::user()->id)
            ->exists();
    }

    /**
     * Check if user can edit this event
     */
    public function canUserEdit($event): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $event->organizer_id === Auth::id() ||
               Auth::user()->hasRole('admin');
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming($event): bool
    {
        return $event->start_date > now();
    }

    /**
     * Check if event is full
     */
    public function isFull($event): bool
    {
        return $event->max_participants &&
               $event->registered_count >= $event->max_participants;
    }

    public function render()
    {
        return view('livewire.event-cards', [
            'events' => $this->events,
            'canCreateEvents' => $this->canCreateEvents,
        ]);
    }
}
