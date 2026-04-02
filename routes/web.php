<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\BudgetCategoryManagement;
use App\Livewire\Admin\FinancialReports;
use App\Livewire\Admin\MeetingDetails;
use App\Livewire\Admin\Meetings;
use App\Livewire\Admin\RolePermissionManager;
use App\Livewire\Admin\TransactionManagement;
use App\Livewire\Admin\TreasurerDashboard;
use App\Livewire\EventDetails;
use App\Livewire\EventRegistration;
use App\Livewire\MyEvents;
use App\Livewire\Super\ManageUsers;
use App\Livewire\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes

Route::get('/events/{event:slug}', EventDetails::class)->name('events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
    Route::get('/members', \App\Livewire\MemberDirectory::class)->name('members.directory');
    Route::get('/members/{user}', \App\Livewire\PublicMemberProfile::class)->name('members.profile');
    Route::get('/fines', \App\Livewire\MemberFinesDashboard::class)->name('members.fines');
    Route::get('/events/{event:slug}/register', EventRegistration::class)->name('events.register');
    Route::get('/my-events', MyEvents::class)->name('my-events');

    // Teacher Routes
    Route::middleware(['can:content.view'])->group(function () {
        Route::get('/teacher/content', ManageContent::class)->name('teacher.content');
    });

    Route::middleware(['can:teacher.events.view'])->group(function () {
        Route::get('/teacher/events', TeacherEvents::class)->name('teacher.events');
    });

    Route::middleware(['can:teacher.reports.view'])->group(function () {
        Route::get('/teacher/analytics', TeacherAnalytics::class)->name('teacher.analytics');
    });

    Route::middleware(['can:portfolio.view'])->group(function () {
        Route::get('/teacher/portfolios', ManagePortfolios::class)->name('teacher.portfolios');
    });

});

// ADMIN ROUTES - Dashboard & Management

Route::middleware(['auth'])->prefix('admin')->group(function () {
    // dashboard pages
    // Auth::user()->assignRole('secretary');
    Route::get('/', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

    // Route::get('/user-profile', UserProfile::class)->name('admin.users');
    Route::get('/users', ManageUsers::class)->name('admin.users');
    Route::get('/treasurer-dashboard', TreasurerDashboard::class)->name('admin.treasurer-dashboard');
    Route::get('/financial-reports', FinancialReports::class)->name('admin.financial-reports');
    Route::get('/transactions', TransactionManagement::class)->name('admin.transactions');
    Route::get('/budget-categories', BudgetCategoryManagement::class)->name('admin.budget-categories');
    Route::get('/meetings', Meetings::class)->name('admin.meetings');
    Route::get('/meeting-details/{meeting}', MeetingDetails::class)->name('admin.meeting.details');
    Route::get('/roles-permissions', RolePermissionManager::class)->name('admin.roles-permissions');
    Route::get('/fines', \App\Livewire\Admin\FinesManagement::class)->name('admin.fines');
    Route::get('/fine-types', \App\Livewire\Admin\FineTypesManagement::class)->name('admin.fine-types');

});

// FRONTEND ROUTES - Cybersecurity Club Website

Route::get('/', function () {
    return view('frontend.home', ['title' => 'Cybersecurity & Innovations Club - SLAU']);
})->name('home');

Route::get('/about', function () {
    return view('frontend.about', ['title' => 'About Us - Cybersecurity & Innovations Club']);
})->name('about');

Route::get('/team', function () {
    return view('frontend.team', ['title' => 'Our Team - Cybersecurity & Innovations Club']);
})->name('team');

Route::get('/contact', function () {
    return view('frontend.contact', ['title' => 'Contact Us - Cybersecurity & Innovations Club']);
})->name('contact');

Route::get('/events-out', function () {
    return view('frontend.events', ['title' => 'Events - Cybersecurity & Innovations Club']);
})->name('events-out');

Route::impersonate();
require __DIR__.'/auth.php';
