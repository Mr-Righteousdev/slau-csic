<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function showVerifyPage(string $code)
    {
        $meeting = Meeting::where('meeting_code', $code)->first();

        if (! $meeting) {
            return view('attendance.error', [
                'message' => 'Invalid meeting code. The session does not exist.',
            ]);
        }

        if (! $meeting->isTeachingSession()) {
            return view('attendance.error', [
                'message' => 'This code is not valid for a teaching session.',
            ]);
        }

        if (! $meeting->attendance_open) {
            return view('attendance.error', [
                'message' => 'Attendance is not open for this session.',
            ]);
        }

        $existingAttendance = $meeting->attendance()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingAttendance) {
            return view('attendance.already-checked', [
                'meeting' => $meeting,
                'attendance' => $existingAttendance,
            ]);
        }

        return view('attendance.verify', [
            'meeting' => $meeting,
        ]);
    }

    public function processCheckIn(Request $request, string $code)
    {
        $meeting = Meeting::where('meeting_code', $code)->first();

        if (! $meeting) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid meeting code.',
            ], 404);
        }

        if (! $meeting->attendance_open) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance is not open for this session.',
            ], 403);
        }

        $attendanceService = app(AttendanceService::class);
        $result = $attendanceService->checkInViaQr(
            $code,
            Auth::user(),
            $request->ip(),
            $request->userAgent()
        );

        if (! $result['success']) {
            return response()->json($result, 409);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'status' => $result['status'],
            'bonus_earned' => $result['bonus_earned'] ?? 0,
        ]);
    }
}
