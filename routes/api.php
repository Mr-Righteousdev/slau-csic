<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MeetingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/events', [EventController::class, 'index']); // Public endpoint

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user/profile', [AuthController::class, 'profile']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Meetings
    Route::get('/meetings/upcoming', [MeetingController::class, 'upcoming']);
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show']);

    // Attendance
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::get('/user/attendance', [AttendanceController::class, 'myHistory']);

    // Events (protected)
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::get('/events/{event}/edit-permission', [EventController::class, 'editPermission']);
    Route::post('/events/{event}/register', [EventController::class, 'register']);
    Route::get('/user/events', [EventController::class, 'myEvents']);

    // Notifications (optional, implement later)
    // Route::get('/notifications', [NotificationController::class, 'index']);
    // Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
