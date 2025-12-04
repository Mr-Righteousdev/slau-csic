<?php

// ============================================
// API Meeting Controller
// app/Http/Controllers/Api/MeetingController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;

class MeetingController extends Controller
{
    public function upcoming()
    {
        $meetings = Meeting::upcoming()
            ->take(10)
            ->get()
            ->map(function ($meeting) {
                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'description' => $meeting->description,
                    'type' => $meeting->type,
                    'type_display' => $meeting->type_display,
                    'scheduled_at' => $meeting->scheduled_at,
                    'location' => $meeting->location,
                    'duration_minutes' => $meeting->duration_minutes,
                    'attendance_open' => $meeting->attendance_open,
                ];
            });

        return response()->json([
            'data' => $meetings,
        ]);
    }

    public function show(Meeting $meeting)
    {
        return response()->json([
            'data' => [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'description' => $meeting->description,
                'type' => $meeting->type,
                'type_display' => $meeting->type_display,
                'scheduled_at' => $meeting->scheduled_at,
                'location' => $meeting->location,
                'duration_minutes' => $meeting->duration_minutes,
                'attendance_open' => $meeting->attendance_open,
                'attendees_count' => $meeting->getAttendanceCount(),
            ],
        ]);
    }
}
