<?php

namespace App\Livewire\Teaching;

use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTeachingSession extends Component
{
    public string $title = '';

    public string $description = '';

    public string $scheduled_date = '';

    public string $scheduled_time = '';

    public int $duration_minutes = 60;

    public string $location = '';

    public int $expected_attendees = 0;

    public int $late_threshold_minutes = 15;

    public int $code_expires_minutes = 30;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'location' => 'required|string|max:255',
            'expected_attendees' => 'nullable|integer|min:0',
            'late_threshold_minutes' => 'required|integer|min:1|max:60',
            'code_expires_minutes' => 'required|integer|min:5|max:120',
        ];
    }

    protected function messages()
    {
        return [
            'scheduled_date.after_or_equal' => 'The session date must be today or a future date.',
        ];
    }

    public function createSession()
    {
        $this->validate();

        $scheduledAt = \Carbon\Carbon::parse($this->scheduled_date.' '.$this->scheduled_time);

        $meeting = Meeting::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => 'teaching_session',
            'scheduled_at' => $scheduledAt,
            'duration_minutes' => $this->duration_minutes,
            'location' => $this->location,
            'expected_attendees' => $this->expected_attendees,
            'late_threshold_minutes' => $this->late_threshold_minutes,
            'code_expires_minutes' => $this->code_expires_minutes,
            'attendance_open' => false,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Teaching session created successfully!');

        return redirect()->route('admin.teaching-sessions.detail', $meeting);
    }

    public function render()
    {
        return view('livewire.teaching.create-teaching-session');
    }
}
