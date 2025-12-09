<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;

class EventDetails extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function render()
    {
        return view('livewire.event-details');
    }
}
