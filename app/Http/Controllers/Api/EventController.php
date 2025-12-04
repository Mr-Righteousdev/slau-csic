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
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(20)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'type' => $event->type,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
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
