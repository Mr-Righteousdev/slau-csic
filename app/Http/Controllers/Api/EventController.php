<?php

// ============================================
// API Event Controller
// app/Http/Controllers/Api/EventController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_public', true)
            ->whereIn('status', ['published', 'scheduled'])
            ->where('start_date', '>=', now()->subMonths(1)) // Include past events from last month
            ->orderBy('start_date')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'type' => $event->type,
                    'start_date' => $event->start_date->toISOString(),
                    'end_date' => $event->end_date ? $event->end_date->toISOString() : null,
                    'location' => $event->location,
                    'banner_image' => $event->banner_image,
                    'max_participants' => $event->max_participants,
                    'registration_required' => $event->registration_required,
                    'registration_deadline' => $event->registration_deadline,
                ];
            });

        return response()->json([
            'data' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:workshop,seminar,meeting,social,competition',
            'is_public' => 'boolean',
            'registration_required' => 'boolean',
            'status' => 'required|in:draft,scheduled,published,cancelled',
        ]);

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? $validated['start_date'],
            'location' => $validated['location'] ?? '',
            'type' => $validated['type'],
            'organizer_id' => $request->user()->id,
            'is_public' => $validated['is_public'] ?? true,
            'registration_required' => $validated['registration_required'] ?? true,
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }

    public function update(Request $request, Event $event)
    {
        // Check if user can edit this event
        if ($event->organizer_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'error' => 'You can only edit your own events'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:workshop,seminar,meeting,social,competition',
        ]);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? $validated['start_date'],
            'location' => $validated['location'] ?? '',
            'type' => $validated['type'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    public function editPermission(Request $request, Event $event)
    {
        $canEdit = $event->organizer_id === $request->user()->id || 
                   $request->user()->hasRole('admin');

        return response()->json([
            'canEdit' => $canEdit
        ]);
    }

    public function register(Request $request, Event $event)
    {
        // Check if registration is required
        if (! $event->registration_required) {
            return response()->json([
                'error' => 'Registration is not required for this event',
            ], 400);
        }

        // Check if already registered
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            return response()->json([
                'error' => 'You are already registered for this event',
            ], 409);
        }

        // Check if event is full
        if ($event->max_participants) {
            $registrationCount = EventRegistration::where('event_id', $event->id)
                ->where('status', '!=', 'cancelled')
                ->count();

            if ($registrationCount >= $event->max_participants) {
                return response()->json([
                    'error' => 'This event is full',
                ], 400);
            }
        }

        // Register user
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
            'registered_at' => now(),
            'status' => 'registered',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered for the event',
            'registration' => $registration,
        ]);
    }
}
