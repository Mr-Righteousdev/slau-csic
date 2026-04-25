<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventDetails extends Component
{
    public Event $event;

    public ?string $userRsvpStatus = null;

    public bool $isFull = false;

    public int|string $remainingSpots = 0;

    public function mount(Event $event)
    {
        $this->event = $event->load('registrations');
        $this->loadRsvpState();
    }

    public function loadRsvpState(): void
    {
        if (Auth::check()) {
            $registration = $this->event->registrations()
                ->where('user_id', Auth::id())
                ->first();

            $this->userRsvpStatus = $registration?->rsvp_status;
        }

        $this->isFull = $this->event->is_full;
        $this->remainingSpots = $this->getRemainingSpots();
    }

    public function rsvpForEvent(): void
    {
        if (! Auth::check()) {
            $this->dispatch('show-notification', message: 'Please login to RSVP for events.', type: 'error');

            return;
        }

        if ($this->isFull) {
            $this->dispatch('show-notification', message: 'This event is full.', type: 'error');

            return;
        }

        $this->event->registrations()->updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'status' => 'registered',
                'rsvp_status' => 'attending',
                'registered_at' => now(),
            ]
        );

        $this->loadRsvpState();
        $this->dispatch('show-notification', message: "You're confirmed for this event!", type: 'success');
    }

    public function cancelRsvp(): void
    {
        if (! Auth::check()) {
            return;
        }

        $registration = $this->event->registrations()
            ->where('user_id', Auth::id())
            ->first();

        if ($registration) {
            $registration->update(['rsvp_status' => 'not_attending']);
            $this->loadRsvpState();
            $this->dispatch('show-notification', message: "You've declined this event.", type: 'success');
        }
    }

    public function isUserAttending(): bool
    {
        return $this->userRsvpStatus === 'attending';
    }

    public function getRsvpStatus(): ?string
    {
        return $this->userRsvpStatus;
    }

    public function getRemainingSpots(): int|string
    {
        if (! $this->event->max_participants) {
            return 'Unlimited';
        }

        return $this->event->remaining_spots;
    }

    public function render()
    {
        return view('livewire.event-details');
    }
}
