<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventRegistration as EventRegistrationModel;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventRegistration extends Component implements HasForms
{
    use InteractsWithForms;

    public Event $event;

    public $registered = false;

    public $remainingSpots = 0;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->remainingSpots = $event->remaining_spots;

        if (Auth::check()) {
            $existingRegistration = EventRegistrationModel::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->first();

            $this->registered = $existingRegistration && $existingRegistration->status === 'registered';
        }
    }

    public function register()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->event->is_full) {
            Notification::make()
                ->title('Event Full')
                ->body('This event is already full.')
                ->danger()
                ->send();

            return;
        }

        if ($this->event->registration_deadline && now()->isAfter($this->event->registration_deadline)) {
            Notification::make()
                ->title('Registration Closed')
                ->body('Registration for this event has closed.')
                ->danger()
                ->send();

            return;
        }

        EventRegistrationModel::create([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
            'status' => 'registered',
            'registered_at' => now(),
        ]);

        $this->registered = true;
        $this->remainingSpots = $this->event->remaining_spots - 1;

        Notification::make()
            ->title('Registration Successful')
            ->body('You have been registered for this event.')
            ->success()
            ->send();
    }

    public function unregister()
    {
        if (! Auth::check()) {
            return;
        }

        $registration = EventRegistrationModel::where('event_id', $this->event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($registration) {
            $registration->update(['status' => 'cancelled']);
            $this->registered = false;
            $this->remainingSpots = $this->event->remaining_spots + 1;

            Notification::make()
                ->title('Registration Cancelled')
                ->body('You have been unregistered from this event.')
                ->success()
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.event-registration');
    }
}
