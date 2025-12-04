<?php

// ============================================
// API Auth Controller
// app/Http/Controllers/Api/AuthController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();

        // Check if user is an active member
        if ($user->membership_status !== 'active') {
            Auth::logout();

            return response()->json([
                'message' => 'Your membership is not active. Please contact admin.',
            ], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'phone' => $user->phone,
                'program' => $user->program,
                'year_of_study' => $user->year_of_study,
                'membership_type' => $user->membership_type,
                'membership_status' => $user->membership_status,
                'attendance_count' => $user->attendance_count,
                'events_attended' => $user->events_attended,
                'discord_username' => $user->discord_username,
                'github_username' => $user->github_username,
                'bio' => $user->bio,
                'joined_at' => $user->joined_at,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'student_id' => $user->student_id,
            'phone' => $user->phone,
            'program' => $user->program,
            'year_of_study' => $user->year_of_study,
            'membership_type' => $user->membership_type,
            'membership_status' => $user->membership_status,
            'attendance_count' => $user->attendance_count,
            'events_attended' => $user->events_attended,
            'discord_username' => $user->discord_username,
            'github_username' => $user->github_username,
            'linkedin_url' => $user->linkedin_url,
            'bio' => $user->bio,
            'joined_at' => $user->joined_at,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'bio' => 'sometimes|string|max:500',
            'github_username' => 'sometimes|string|max:255',
            'linkedin_url' => 'sometimes|url|max:255',
            'discord_username' => 'sometimes|string|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
}
