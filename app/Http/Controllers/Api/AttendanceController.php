<?php

// ============================================
// API Attendance Controller
// app/Http/Controllers/Api/AttendanceController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Meeting;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'meeting_code' => 'required|string',
            'location' => 'nullable|string',
        ]);

        // Find meeting by code
        $meeting = Meeting::where('meeting_code', $validated['meeting_code'])->first();

        if (! $meeting) {
            return response()->json([
                'error' => 'Invalid meeting code',
            ], 404);
        }

        // Check if attendance is open
        if (! $meeting->attendance_open) {
            return response()->json([
                'error' => 'Attendance is not open for this meeting',
            ], 403);
        }

        // Check if user already checked in
        $existingAttendance = Attendance::where('meeting_id', $meeting->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'error' => 'You have already checked in to this meeting',
                'checked_in_at' => $existingAttendance->checked_in_at,
            ], 409);
        }

        // Record attendance
        $attendance = Attendance::create([
            'meeting_id' => $meeting->id,
            'user_id' => $request->user()->id,
            'checked_in_at' => now(),
            'check_in_method' => 'qr_code',
            'location' => $validated['location'] ?? null,
            'device_info' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);

        // Update user's attendance count
        $request->user()->updateAttendanceCount();

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'meeting' => [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'location' => $meeting->location,
                'scheduled_at' => $meeting->scheduled_at,
            ],
            'attendance' => $attendance,
        ]);
    }

    public function myHistory(Request $request)
    {
        $attendance = Attendance::with(['meeting'])
            ->where('user_id', $request->user()->id)
            ->orderBy('checked_in_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'checked_in_at' => $item->checked_in_at,
                    'check_in_method' => $item->check_in_method,
                    'check_in_method_display' => $item->check_in_method_display,
                    'meeting' => [
                        'id' => $item->meeting->id,
                        'title' => $item->meeting->title,
                        'type' => $item->meeting->type,
                        'type_display' => $item->meeting->type_display,
                        'location' => $item->meeting->location,
                        'scheduled_at' => $item->meeting->scheduled_at,
                    ],
                ];
            });

        return response()->json([
            'data' => $attendance,
            'total' => $attendance->count(),
        ]);
    }
}
