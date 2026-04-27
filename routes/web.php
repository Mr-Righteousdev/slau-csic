<?php

use App\Http\Controllers\Admin\ElectionManagementController;
use App\Http\Controllers\Admin\EventAttendanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClubPortalController;
use App\Http\Controllers\Frontend\ProjectsPageController;
use App\Http\Controllers\Frontend\PublicMemberPageController;
use App\Livewire\Admin\BudgetCategoryManagement;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EventAttendees;
use App\Livewire\Admin\EventsManagement;
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
use App\Livewire\Teaching\CreateTeachingSession;
use App\Livewire\Teaching\ManageContent;
use App\Livewire\Teaching\ManagePortfolios;
use App\Livewire\Teaching\SessionDetail;
use App\Livewire\Teaching\TeacherAnalytics;
use App\Livewire\Teaching\TeacherEvents;
use App\Livewire\Teaching\TeachingSessionCommands;
use App\Livewire\Teaching\TeachingSessionList;
use App\Livewire\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes

Route::get('/events/{event:slug}', EventDetails::class)->name('events.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ClubPortalController::class, 'index'])->name('dashboard');
    Route::get('/club/competitions', [ClubPortalController::class, 'competitions'])->name('portal.competitions');
    Route::get('/club/voting', [ClubPortalController::class, 'voting'])->name('portal.voting');
    Route::get('/club/ctf-arena', [ClubPortalController::class, 'ctfArena'])->name('portal.ctf');
    Route::get('/club/classes', [ClubPortalController::class, 'classes'])->name('portal.classes');
    Route::post('/club/voting/{election}', [ClubPortalController::class, 'castVote'])->name('portal.voting.cast');
    Route::post('/club/resources/{clubResource}/progress', [ClubPortalController::class, 'updateProgress'])->name('portal.progress.update');

    // Attendance Routes
    Route::get('/attendance/verify/{code}', [AttendanceController::class, 'showVerifyPage'])->name('attendance.verify');
    Route::post('/attendance/verify/{code}', [AttendanceController::class, 'processCheckIn']);
});

Route::middleware(['auth', 'verified'])->group(function () {
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

Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->group(function () {
    // dashboard pages
    // Auth::user()->assignRole('secretary');
    Route::get('/', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

    // Route::get('/user-profile', UserProfile::class)->name('admin.users');
    Route::get('/users', ManageUsers::class)->name('admin.users');
    Route::get('/events', EventsManagement::class)->name('admin.events');
    Route::get('/events/{event}/attendees', EventAttendees::class)->name('admin.event-attendees');
    Route::get('/events/{event}/attendance', [EventAttendanceController::class, 'show'])->name('admin.events.attendance');
    Route::post('/events/{event}/attendance/{registration}', [EventAttendanceController::class, 'mark'])->name('admin.events.attendance.mark');
    Route::get('/elections', [ElectionManagementController::class, 'index'])->name('admin.elections');
    Route::post('/elections', [ElectionManagementController::class, 'store'])->name('admin.elections.store');
    Route::get('/elections/{election}', [ElectionManagementController::class, 'show'])->name('admin.elections.show');
    Route::put('/elections/{election}', [ElectionManagementController::class, 'update'])->name('admin.elections.update');
    Route::post('/elections/{election}/candidates', [ElectionManagementController::class, 'storeCandidate'])->name('admin.elections.candidates.store');
    Route::delete('/elections/{election}/candidates/{candidate}', [ElectionManagementController::class, 'destroyCandidate'])->name('admin.elections.candidates.destroy');
    Route::get('/treasurer-dashboard', TreasurerDashboard::class)->name('admin.treasurer-dashboard');
    Route::get('/financial-reports', FinancialReports::class)->name('admin.financial-reports');
    Route::get('/transactions', TransactionManagement::class)->name('admin.transactions');
    Route::get('/budget-categories', BudgetCategoryManagement::class)->name('admin.budget-categories');
    Route::get('/meetings', Meetings::class)->name('admin.meetings');
    Route::get('/meeting-details/{meeting}', MeetingDetails::class)->name('admin.meeting.details');
    Route::get('/teaching-sessions', TeachingSessionList::class)->name('admin.teaching-sessions')->middleware('can:create teaching session');
    Route::get('/teaching-sessions/create', CreateTeachingSession::class)->name('admin.teaching-sessions.create')->middleware('can:create teaching session');
    Route::get('/teaching-sessions/{meeting}', SessionDetail::class)->name('admin.teaching-sessions.detail');
    Route::get('/teaching-sessions/commands', TeachingSessionCommands::class)->name('admin.teaching-sessions.commands')->middleware('can:create teaching session');
    Route::get('/roles-permissions', RolePermissionManager::class)->name('admin.roles-permissions');
    Route::get('/pending-members', \App\Livewire\Admin\PendingMembers::class)->name('admin.pending-members');
    Route::get('/fines', \App\Livewire\Admin\FinesManagement::class)->name('admin.fines');
    Route::get('/fine-types', \App\Livewire\Admin\FineTypesManagement::class)->name('admin.fine-types');
    Route::get('/questions', \App\Livewire\QuestionBank\Index::class)->name('question-bank.index');
    Route::get('/questions/create', \App\Livewire\QuestionBank\Create::class)->name('question-bank.create');
    Route::post('/questions', \App\Livewire\QuestionBank\Create::class)->name('question-bank.store');
    Route::get('/questions/export', \App\Livewire\QuestionBank\Export::class)->name('question-bank.export');
    Route::get('/questions/{question}/edit', \App\Livewire\QuestionBank\Edit::class)->name('question-bank.edit');
    Route::put('/questions/{question}', \App\Livewire\QuestionBank\Edit::class)->name('question-bank.update');
    Route::delete('/questions/{question}', \App\Livewire\QuestionBank\Index::class)->name('question-bank.destroy');
    Route::post('/questions/{question}/restore', \App\Livewire\QuestionBank\Index::class)->name('question-bank.restore');

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

Route::get('/projects', [ProjectsPageController::class, 'index'])->name('projects');
Route::get('/club-members', [PublicMemberPageController::class, 'index'])->name('members.public');
Route::get('/club-members/{user}', [PublicMemberPageController::class, 'show'])->name('members.public.show');

Route::get('/contact', function () {
    return view('frontend.contact', ['title' => 'Contact Us - Cybersecurity & Innovations Club']);
})->name('contact');

Route::get('/events-out', function () {
    return view('frontend.events', ['title' => 'Events - Cybersecurity & Innovations Club']);
})->name('events-out');

Route::impersonate();
require __DIR__.'/auth.php';
