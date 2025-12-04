<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\UserProfile;
// use Livewire\Livewire;


// ============================================
// FRONTEND ROUTES - Cybersecurity Club Website
// ============================================
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
});

// ============================================
// ADMIN ROUTES - Dashboard & Management
// ============================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // dashboard pages
    Route::get('/', function () {
        // assign admin role to logged in user for testing
        // Auth::user()->assignRole('admin');
        return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
    })->name('admin.dashboard');

    Route::get('/user-profile', UserProfile::class)->name('admin.users');

});


require __DIR__.'/auth.php';
