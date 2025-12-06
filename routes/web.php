<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\UserProfile;
use App\Livewire\Super\ManageUsers;
// use App\Http\Controllers\Admin\MeetingController;
use App\Livewire\Admin\Meetings;
use App\Livewire\Admin\MeetingDetails;
use App\Livewire\Admin\MeetingList;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
});

// ADMIN ROUTES - Dashboard & Management

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // dashboard pages
    // Auth::user()->assignRole('secretary');
    Route::get('/', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

    // Route::get('/user-profile', UserProfile::class)->name('admin.users');
    Route::get('/users', ManageUsers::class)->name('admin.users');
    Route::get('/meetings', Meetings::class)->name('admin.meetings');
    Route::get('/meeting-details/{meeting}', MeetingDetails::class)->name('admin.meeting.details');

});



// FRONTEND ROUTES - Cybersecurity Club Website

Route::get('/', function () {
    return view('frontend.home', ['title' => 'Cybersecurity & Innovations Club - SLAU']);
})->name('home');

Route::get('/about', function () {
    return view('frontend.about', ['title' => 'About Us - Cybersecurity & Innovations Club']);
})->name('about');

Route::get('/events', function () {
    return view('frontend.events', ['title' => 'Events - Cybersecurity & Innovations Club']);
})->name('events');

Route::get('/team', function () {
    return view('frontend.team', ['title' => 'Our Team - Cybersecurity & Innovations Club']);
})->name('team');

Route::get('/contact', function () {
    return view('frontend.contact', ['title' => 'Contact Us - Cybersecurity & Innovations Club']);
})->name('contact');

Route::impersonate();
require __DIR__.'/auth.php';
