<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventRecurrence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateEvent extends Component
{
    public $title = '';

    public $description = '';

    public $type = 'workshop';

    public $start_date = '';

    public $end_date = '';

    public $location = '';

    public $max_participants = '';

    public $registration_required = true;

    public $is_public = true;

    public $registration_deadline = '';

    public $requirements = '';

    public $registration_fee = '';

    public $external_link = '';

    // Recurrence fields
    public $recurrence_enabled = false;

    public $recurrence_pattern = 'weekly';

    public $recurrence_ends_at = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['workshop', 'competition', 'ctf', 'bootcamp', 'awareness_campaign', 'talk', 'social', 'hackathon'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'registration_required' => ['boolean'],
            'is_public' => ['boolean'],
            'registration_deadline' => ['nullable', 'date', 'before:start_date'],
            'requirements' => ['nullable', 'string'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'external_link' => ['nullable', 'url'],
            'recurrence_enabled' => ['boolean'],
            'recurrence_pattern' => ['required_if:recurrence_enabled,true', Rule::in(['weekly', 'biweekly', 'monthly'])],
            'recurrence_ends_at' => ['nullable', 'date', 'after:start_date'],
        ];
    }

    public function render()
    {
        return view('livewire.create-event');
    }

    public function saveEvent()
    {
        $this->validate();

        $event = Event::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'max_participants' => $this->max_participants ?: null,
            'registration_required' => $this->registration_required,
            'is_public' => $this->is_public,
            'registration_deadline' => $this->registration_deadline ?: null,
            'requirements' => $this->requirements,
            'registration_fee' => $this->registration_fee ?: null,
            'external_link' => $this->external_link ?: null,
            'organizer_id' => Auth::id(),
            'status' => 'scheduled',
            'is_recurring' => $this->recurrence_enabled,
        ]);

        // Create recurrence record if enabled
        if ($this->recurrence_enabled) {
            EventRecurrence::create([
                'event_id' => $event->id,
                'pattern' => $this->recurrence_pattern,
                'interval' => 1,
                'ends_at' => $this->recurrence_ends_at ? \Carbon\Carbon::parse($this->recurrence_ends_at)->endOfDay() : null,
            ]);
        }

        $this->dispatch('show-notification', message: 'Event created successfully!', type: 'success');
        $this->dispatch('event-created');
        $this->reset();
    }
}
