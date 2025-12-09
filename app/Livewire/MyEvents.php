<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyEvents extends Component
{
    public $upcomingEvents = [];

    public $pastEvents = [];

    public $instructedEvents = [];

    public $pendingFeedback = [];

    public function mount()
    {
        $user = Auth::user();

        $this->upcomingEvents = Event::whereHas('registrations', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 'registered');
        })
            ->where('start_date', '>', now())
            ->with(['registrations'])
            ->orderBy('start_date')
            ->get();

        $this->pastEvents = Event::whereHas('registrations', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereNotNull('attended_at');
        })
            ->where('start_date', '<', now())
            ->with(['registrations'])
            ->orderBy('start_date', 'desc')
            ->get();

        $this->instructedEvents = Event::where('organizer_id', $user->id)
            ->with(['registrations'])
            ->orderBy('start_date', 'desc')
            ->get();

        $this->pendingFeedback = Event::whereHas('registrations', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereNotNull('attended_at');
        })
            ->where('end_date', '<', now())
            ->whereDoesntHave('feedback', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.my-events');
    }
}
