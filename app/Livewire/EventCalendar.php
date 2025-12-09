<?php

namespace App\Livewire;

use App\Models\Event as CalendarEvent;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EventCalendar extends Component
{
    public $events = [];
    public $selectedEvent = null;
    public $modalOpen = false;
    public $canCreateEvent = false;

    public function mount()
    {
        // Debug: Check if Event model exists
        Log::debug('EventCalendar mounted');

        try {
            // Try to use the Event model
            $count = CalendarEvent::count();
            Log::debug('Event model exists, count: ' . $count);
        } catch (\Exception $e) {
            Log::error('Event model error: ' . $e->getMessage());
        }

        // Check if user can create events
        $this->canCreateEvent = Auth::check() && Auth::user()->hasAnyRole(['admin', 'super-admin', 'member']);

        $this->loadEvents();
    }

    public function loadEvents()
    {
        Log::debug('Loading events...');

        try {
            $events = CalendarEvent::where('is_public', true)
                ->where('status', 'scheduled')
                ->get();

            Log::debug('Found ' . $events->count() . ' events');

            $this->events = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->toIso8601String(),
                    'end' => $event->end_date->toIso8601String(),
                    'color' => $this->getEventColor($event->type),
                    'type' => $event->type,
                    'location' => $event->location,
                    'description' => $event->description,
                    'url' => route('events.show', $event->slug),
                    'allDay' => $event->start_date->format('H:i:s') === '00:00:00' &&
                               $event->end_date->format('H:i:s') === '00:00:00',
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Error loading events: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            $this->events = [];
        }
    }

    protected function getEventColor($type)
    {
        return match ($type) {
            'workshop' => '#10b981', // green
            'seminar' => '#3b82f6',  // blue
            'meeting' => '#8b5cf6',  // purple
            'social' => '#f59e0b',   // amber
            'competition' => '#ef4444', // red
            default => '#6b7280',    // gray
        };
    }

    #[On('eventClick')]
    public function handleEventClick($eventId)
    {
        try {
            $event = CalendarEvent::find($eventId);
            if ($event) {
                $this->selectedEvent = $event;
                $this->modalOpen = true;
            }
        } catch (\Exception $e) {
            Log::error('Error handling event click: ' . $e->getMessage());
        }
    }

    #[On('dateClick')]
    public function handleDateClick($date)
    {
        $this->dispatch('open-create-modal', date: $date);
    }

    #[On('refreshCalendar')]
    public function refreshEvents()
    {
        $this->loadEvents();
        $this->dispatch('calendar-refreshed');
    }

    public function createEvent($eventData)
    {
        // Only allow authenticated users to create events
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'You must be logged in to create events.', type: 'error');
            return;
        }

        try {
            $event = CalendarEvent::create([
                'title' => $eventData['title'],
                'description' => $eventData['description'] ?? '',
                'start_date' => $eventData['start_date'],
                'end_date' => $eventData['end_date'],
                'location' => $eventData['location'] ?? '',
                'type' => $this->mapColorToType($eventData['color'] ?? 'primary'),
                'organizer_id' => Auth::id(),
                'is_public' => true,
                'registration_required' => true,
                'status' => 'scheduled',
            ]);

            $this->loadEvents();
            $this->dispatch('show-notification', message: 'Event created successfully!', type: 'success');
            
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            $this->dispatch('show-notification', message: 'Failed to create event.', type: 'error');
        }
    }

    public function updateEvent($eventId, $eventData)
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification', message: 'You must be logged in to update events.', type: 'error');
            return;
        }

        try {
            $event = CalendarEvent::findOrFail($eventId);
            
            // Only allow organizer or admin to update
            if ($event->organizer_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                $this->dispatch('show-notification', message: 'You can only update your own events.', type: 'error');
                return;
            }

            $event->update([
                'title' => $eventData['title'],
                'description' => $eventData['description'] ?? '',
                'start_date' => $eventData['start_date'],
                'end_date' => $eventData['end_date'],
                'location' => $eventData['location'] ?? '',
                'type' => $this->mapColorToType($eventData['color'] ?? 'primary'),
            ]);

            $this->loadEvents();
            $this->dispatch('show-notification', message: 'Event updated successfully!', type: 'success');
            
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            $this->dispatch('show-notification', message: 'Failed to update event.', type: 'error');
        }
    }

    

    private function mapColorToType($color)
    {
        return match ($color) {
            'Danger' => 'competition',
            'Success' => 'workshop',
            'Warning' => 'seminar',
            'Primary' => 'meeting',
            default => 'meeting',
        };
    }

    public function render()
    {
        Log::debug('Rendering EventCalendar with ' . count($this->events) . ' events');
        return view('livewire.event-calendar');
    }
}
