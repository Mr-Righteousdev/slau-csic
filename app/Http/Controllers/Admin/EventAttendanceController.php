<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EventAttendanceController extends Controller
{
    public function show(Event $event): View
    {
        $event->load(['registrations.user']);

        return view('pages.admin.events.attendance', [
            'title' => 'Event Attendance',
            'event' => $event,
        ]);
    }

    public function mark(Event $event, EventRegistration $registration): RedirectResponse
    {
        abort_unless($registration->event_id === $event->id, 404);

        $registration->update([
            'attended_at' => now(),
            'status' => 'attended',
        ]);

        return back()->with('status', 'Attendance marked successfully.');
    }
}
