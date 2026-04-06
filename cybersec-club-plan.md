# 🛡️ Cybersecurity Club Attendance System — Claude Code Master Plan
This document is your complete implementation guide. Read it fully before writing any code. Follow phases in order. Assume `users` and `members` tables and their relationship already exist. Do not recreate them.

---

## 🧱 Tech Stack

| Layer | Tool |
|---|---|
| Backend | Laravel 11 |
| Frontend | Livewire v3 + Alpine.js |
| Styling | Tailwind CSS |
| UI Components | Filament Components (no full panel) |
| Roles & Permissions | Spatie Laravel Permission |
| Activity Logging | Spatie Laravel Activity Log |
| QR Generation | `simplesoftwareio/simple-qrcode` |
| Scheduling | Laravel Scheduler (for auto-session close) |
| Testing | Pest PHP |

---

## 📐 Assumptions (Do Not Recreate These)

- `users` table exists with standard Laravel auth fields
- `members` table exists and has a `user_id` foreign key linking to `users`
- Auth scaffolding is in place
- Spatie Permission package is installed

---

## 🗂️ Database Schema

### Table: `sessions`

```php
Schema::create('teachingsessions', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->date('session_date');
    $table->time('start_time');
    $table->time('end_time');
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->enum('status', ['scheduled', 'ongoing', 'completed'])->default('scheduled');
    $table->string('attendance_token', 64)->nullable()->unique();
    $table->timestamp('token_expires_at')->nullable();
    $table->integer('late_threshold_minutes')->default(15); // Per-session late threshold
    $table->timestamps();
});
```

### Table: `attendances`

```php
Schema::create('attendances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
    $table->foreignId('session_id')->constrained('sessions')->cascadeOnDelete();
    $table->enum('status', ['present', 'late', 'absent'])->default('absent');
    $table->timestamp('check_in_time')->nullable();
    $table->enum('check_in_method', ['qr', 'manual'])->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->text('device_info')->nullable();
    $table->unique(['member_id', 'session_id']); // One attendance per member per session
    $table->timestamps();
});
```

### Table: `member_stats`

```php
Schema::create('member_stats', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
    $table->integer('total_sessions_attended')->default(0);
    $table->integer('current_streak')->default(0);
    $table->integer('longest_streak')->default(0);
    $table->integer('bonus_points')->default(0);
    $table->decimal('score', 8, 2)->default(0);
    $table->timestamps();
    $table->unique('member_id');
});
```

### Table: `eligibility_snapshots`

```php
Schema::create('eligibility_snapshots', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
    $table->string('semester'); // e.g. "2024-S1"
    $table->decimal('attendance_rate', 5, 2);
    $table->integer('sessions_attended');
    $table->boolean('is_eligible');
    $table->timestamp('snapshot_at');
    $table->timestamps();
    $table->unique(['member_id', 'semester']);
});
```

---

## 🔐 Roles & Permissions

### Roles
already exist

### Permissions

```php
$permissions = [
    'create session',
    'edit session',
    'delete session',
    'start session',
    'end session',
    'mark attendance',
    'view reports',
    'manage members',
    'view leaderboard',
    'checkin via qr',
    'take eligibility snapshot',
];
```

### Assignment

| Permission | Admin | Trainer | Member |
|---|---|---|---|
| create session | ✅ | ✅ | ❌ |
| edit session | ✅ | ✅ | ❌ |
| delete session | ✅ | ❌ | ❌ |
| start session | ✅ | ✅ | ❌ |
| end session | ✅ | ✅ | ❌ |
| mark attendance | ✅ | ✅ | ❌ |
| view reports | ✅ | ✅ | ❌ |
| manage members | ✅ | ❌ | ❌ |
| view leaderboard | ✅ | ✅ | ✅ |
| checkin via qr | ❌ | ❌ | ✅ |
| take eligibility snapshot | ✅ | ❌ | ❌ |

---

## 📁 Project Structure

```
app/
├── Models/
│   ├── Session.php
│   ├── Attendance.php
│   ├── MemberStat.php
│   └── EligibilitySnapshot.php
│
├── Livewire/
│   ├── Sessions/
│   │   ├── SessionList.php
│   │   ├── CreateSession.php
│   │   └── SessionDetail.php
│   ├── Members/
│   │   ├── MemberTable.php
│   │   └── MemberForm.php
│   └── Attendance/
│       ├── AttendanceMarker.php
│       ├── QrGenerator.php
│       └── CheckInHandler.php
│
├── Services/
│   ├── AttendanceService.php
│   ├── LeaderboardService.php
│   ├── EligibilityService.php
│   └── TokenService.php
│
├── Http/Controllers/
│   └── CheckInController.php
│
├── Console/Commands/
│   ├── AutoCompleteExpiredSessions.php
│   └── TakeEligibilitySnapshot.php
│
└── Policies/
    ├── SessionPolicy.php
    └── AttendancePolicy.php

resources/views/
├── livewire/
│   ├── sessions/
│   │   ├── session-list.blade.php
│   │   ├── create-session.blade.php
│   │   └── session-detail.blade.php
│   ├── members/
│   │   ├── member-table.blade.php
│   │   └── member-form.blade.php
│   └── attendance/
│       ├── attendance-marker.blade.php
│       ├── qr-generator.blade.php
│       └── check-in-handler.blade.php
├── pages/
│   ├── dashboard.blade.php
│   ├── sessions.blade.php
│   ├── members.blade.php
│   ├── leaderboard.blade.php
│   └── check-in.blade.php
└── layouts/
    └── app.blade.php

database/
├── migrations/
└── seeders/
    ├── RolesAndPermissionsSeeder.php
    └── DatabaseSeeder.php
```

---

## ⚙️ Service Layer Specifications

### `TokenService.php`

Responsible for all QR token logic.

```php
class TokenService
{
    // Generate a cryptographically secure token
    public function generate(): string
    // Set token on session + set expiry 5 minutes from now
    public function issue(Session $session): string
    // Rotate: generate new token, update session, return new token
    public function rotate(Session $session): string
    // Check: token exists, session is ongoing, not expired
    public function validate(string $token): Session|false
}
```

**Token Rotation:** The `QrGenerator` Livewire component should poll every 60 seconds and call `TokenService::rotate()`. This means shared screenshots of the QR expire quickly.

### `AttendanceService.php`

All attendance recording logic lives here. No raw Attendance model writes outside this service.

```php
class AttendanceService
{
    // QR check-in — validates token, checks duplicate, records status (present/late)
    public function checkInViaQr(string $token, Member $member): array
    // Manual mark by admin/trainer — can set present/absent/late
    public function markManually(Session $session, Member $member, string $status, User $markedBy): Attendance
    // Determine status: compare check_in_time to session start_time + late_threshold_minutes
    private function resolveStatus(Session $session, Carbon $checkInTime): string
    // After session ends, mark all non-checked-in members as absent
    public function finalizeAbsences(Session $session): void
}
```

### `LeaderboardService.php`

Recalculates scores after every session is completed.

```php
class LeaderboardService
{
    // Recalculate a single member's score
    public function recalculate(Member $member): void
    // Recalculate all members (called when a session is closed)
    public function recalculateAll(): void
    // Get ranked members, optionally filtered by month or semester
    public function getRanked(string $filter = 'all'): Collection
}
```

**Score Formula:**

```
attendance_rate = (sessions_attended / total_sessions) * 100
consistency_score = current_streak * 10  (capped at 100)
score = (attendance_rate * 0.7) + (consistency_score * 0.2) + (bonus_points * 0.1)
```

**Streak logic:**
- After each session closes, check if member was present/late
- If yes: `current_streak++`, update `longest_streak` if exceeded
- If absent: `current_streak = 0`
- Store both values in `member_stats`

### `EligibilityService.php`

```php
class EligibilityService
{
    // Check eligibility for a single member right now (live calculation)
    public function isEligible(Member $member): bool
    // Called by artisan command — snapshot all members for a given semester
    public function snapshotAll(string $semester): void
}

// Eligibility criteria:
// - attendance_rate >= 75%
// - sessions_attended >= 5
```

---

## 🔄 Session Lifecycle & Flows

### Start Session Flow

```
Admin clicks "Start Session"
    → SessionDetail Livewire component calls Session::start()
    → Session status → 'ongoing'
    → TokenService::issue() generates token + sets expiry
    → QR code rendered via simplesoftwareio/simple-qrcode
    → QrGenerator component begins polling every 60s to rotate token
```

### QR Check-In Flow

```
Member scans QR → GET /check-in/{token}
    → CheckInController resolves token via TokenService::validate()
    → If invalid/expired → return error view
    → If valid → get auth member
    → AttendanceService::checkInViaQr()
        → Check duplicate (unique constraint + soft check)
        → Resolve status (present/late) based on threshold
        → Record attendance with ip_address + device_info
        → Trigger LeaderboardService::recalculate(member)
    → Return success view
```

### End Session Flow

```
Admin clicks "End Session" OR scheduler auto-closes
    → Session status → 'completed'
    → attendance_token → null, token_expires_at → null
    → AttendanceService::finalizeAbsences() marks remaining as absent
    → LeaderboardService::recalculateAll()
```

### Auto-Close (Scheduler)

```php
// AutoCompleteExpiredSessions command — runs every 5 minutes
Session::where('status', 'ongoing')
    ->whereDate('session_date', today())
    ->whereTime('end_time', '<', now()->toTimeString())
    ->each(fn($session) => $session->close());
```

Register in `Console/Kernel.php`:
```php
$schedule->command('sessions:auto-close')->everyFiveMinutes();
```

---

## 🧩 Livewire Components Detail

### `SessionList`
- Lists all sessions with status badges
- Filter by status (scheduled/ongoing/completed)
- "Create Session" button → opens `CreateSession` modal
- Each row links to `SessionDetail`

### `CreateSession`
- Form: title, description, date, start_time, end_time, late_threshold_minutes
- Validates end_time > start_time
- On save: creates session, fires event to refresh `SessionList`

### `SessionDetail`
- Shows session info + status
- If ongoing: renders `QrGenerator` + `AttendanceMarker` side by side
- Actions: Start Session, End Session, Show QR
- Guards actions with permission checks

### `QrGenerator`
- Displays QR code image from `/check-in/{token}`
- `wire:poll.60000ms="rotateToken"` to auto-rotate every 60s
- Shows token expiry countdown (Alpine.js timer)
- Only visible when session is ongoing

### `AttendanceMarker`
- Lists all members for the session
- Shows check-in status per member
- Admin/Trainer can click to manually set: present / late / absent
- Calls `AttendanceService::markManually()`
- Logs action via Spatie Activity Log

### `MemberTable`
- Paginated member list
- Search by name, student_id, course
- Edit / Delete actions (admin only)
- Role assignment dropdown

---

## 🏆 Leaderboard Page

### Display
- Top 10 members ranked by score
- Columns: Rank, Name, Attendance Rate, Streak, Bonus, Score
- Badge tiers shown as icons:
  - 🥉 Bronze: ≥ 50%
  - 🥈 Silver: ≥ 75%
  - 🥇 Gold: ≥ 90%

### Filters
- All time / This month / This semester
- Filter passed to `LeaderboardService::getRanked($filter)`

### Bonus Points
Awarded manually or via rules:
- +5 for early check-in (before session start_time)
- +10 for perfect attendance in a calendar month
- Stored in `member_stats.bonus_points`

---

## 🛣️ Routes

```php
// Public
Route::get('/check-in/{token}', [CheckInController::class, 'handle'])
    ->middleware('auth')
    ->name('checkin');

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
    Route::get('/sessions', SessionsPage::class)->name('sessions');
    Route::get('/sessions/{session}', SessionDetailPage::class)->name('sessions.detail');
    Route::get('/members', MembersPage::class)->name('members');
    Route::get('/leaderboard', LeaderboardPage::class)->name('leaderboard');
});
```

---

## 📊 Dashboard Widgets

| Widget | Data Source |
|---|---|
| Total Members | `Member::count()` |
| Total Sessions | `Session::count()` |
| Average Attendance Rate | Calculated across all completed sessions |
| Leaderboard Preview | Top 5 from `LeaderboardService` |
| Upcoming Sessions | `Session::scheduled()->upcoming()->take(3)` |

---

## 🔒 Security Checklist

- [ ] QR token is 64-char random string (`Str::random(64)`)
- [ ] Token expiry enforced server-side, not just frontend
- [ ] Token rotates every 60s when session is active
- [ ] Duplicate check-in blocked by unique constraint + service check
- [ ] IP address + device info logged per check-in
- [ ] All attendance manual changes logged via Spatie Activity Log
- [ ] Gates/Policies enforce permissions on every action
- [ ] Check-in route requires auth — anonymous check-in not allowed
- [ ] `late_threshold_minutes` is per-session, not hardcoded

---

## 📝 Audit / Activity Logging

Install Spatie Activity Log from Phase 1. Log these events:

| Event | Actor | Subject |
|---|---|---|
| Session created | Admin/Trainer | Session |
| Session started | Admin/Trainer | Session |
| Session ended | Admin/Trainer/System | Session |
| Attendance manually marked | Admin/Trainer | Attendance |
| Member role changed | Admin | Member |
| Eligibility snapshot taken | Admin/System | EligibilitySnapshot |

Use the `LogsActivity` trait on models. For manual actions in services, call `activity()->causedBy($user)->performedOn($model)->log(...)` explicitly.

---

## 🧪 Testing Plan (Pest PHP)

### Unit Tests

```
tests/Unit/
├── AttendanceServiceTest.php     — late threshold, duplicate, status resolution
├── LeaderboardServiceTest.php    — score formula, streak logic
├── EligibilityServiceTest.php    — eligibility criteria boundary cases
└── TokenServiceTest.php          — generate, validate, expiry, rotation
```

### Feature Tests

```
tests/Feature/
├── QrCheckInTest.php             — full check-in flow, expired token, duplicate
├── SessionLifecycleTest.php      — create, start, end, auto-close
├── ManualAttendanceTest.php      — permission gates, mark flow
├── LeaderboardTest.php           — ranking, filters
└── EligibilitySnapshotTest.php   — snapshot command, criteria
```

---

## 🚀 Development Phases

### Phase 1 — Foundation
> Goal: Working role system + member management

- [ ] Run migrations for `sessions`, `attendances`, `member_stats`, `eligibility_snapshots`
- [ ] Seed `RolesAndPermissionsSeeder` with all roles and permissions
- [ ] Create Models with relationships and scopes
- [ ] Install + configure Spatie Activity Log
- [ ] Build `MemberTable` + `MemberForm` Livewire components
- [ ] Build Members page
- [ ] Write unit test stubs

### Phase 2 — Session & Manual Attendance
> Goal: Full session lifecycle with manual attendance

- [ ] Build `SessionList` + `CreateSession` + `SessionDetail` Livewire components
- [ ] Implement `AttendanceService` (manual marking only)
- [ ] Build `AttendanceMarker` Livewire component
- [ ] Register `AutoCompleteExpiredSessions` scheduler command
- [ ] Build Sessions and Session Detail pages
- [ ] Write feature tests for session lifecycle + manual attendance

### Phase 3 — QR Check-In
> Goal: Members can self-check-in via QR

- [ ] Install `simplesoftwareio/simple-qrcode`
- [ ] Implement `TokenService` (generate, issue, rotate, validate)
- [ ] Build `QrGenerator` Livewire component with 60s polling
- [ ] Build `CheckInController` + check-in page view
- [ ] Update `AttendanceService::checkInViaQr()`
- [ ] Write QR check-in feature tests

### Phase 4 — Leaderboard & Eligibility
> Goal: Scoring, ranking, badge system, eligibility snapshots

- [ ] Implement `LeaderboardService` (score formula + streak logic)
- [ ] Build `LeaderboardPage` with filters and badge display
- [ ] Implement `EligibilityService`
- [ ] Register `TakeEligibilitySnapshot` artisan command
- [ ] Wire bonus points system
- [ ] Write leaderboard + eligibility tests

### Phase 5 — Dashboard & Polish
> Goal: Full dashboard, audit log viewer, UI polish

- [ ] Build Dashboard with all widgets
- [ ] Add activity log viewer for admins
- [ ] Responsive UI audit
- [ ] Performance: add indexes on `attendances.session_id`, `attendances.member_id`, `member_stats.score`
- [ ] Final security review against checklist

---

## 🧠 Key Business Rules (Reference During Implementation)

1. A member can only have one attendance record per session (enforced at DB + service level)
2. Late is determined by: `check_in_time > session.start_time + late_threshold_minutes`
3. A session must be `ongoing` for QR check-in to work
4. When a session closes, all members without a record are marked `absent`
5. Leaderboard scores recalculate after every session closes
6. Streak resets to 0 on any `absent` record
7. Eligibility is live-calculated but snapshotted at semester end
8. Token rotation every 60s — QrGenerator Livewire component handles this via `wire:poll`
9. All manual attendance changes must be audit-logged with the acting user
10. Early check-in bonus (+5) applies if `check_in_time < session.start_time`

---

## 📦 Composer / NPM Dependencies to Install

```bash
# QR Code generation
composer require simplesoftwareio/simple-qrcode

# Activity logging (install from Phase 1)
composer require spatie/laravel-activitylog
php artisan activitylog:install
php artisan migrate

# Spatie Permission (should already be installed)
# composer require spatie/laravel-permission
```

---

*Last updated: Phase planning complete. Begin with Phase 1.*
