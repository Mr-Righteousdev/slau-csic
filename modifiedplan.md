# 🛡️ Cybersecurity Club Attendance System — Modified Plan
## Leveraging Existing Meeting Infrastructure

**Goal:** Record attendance for teaching sessions of the club by extending the existing Meeting/Attendance system instead of creating parallel tables.

---

## 📐 Key Decision: Reuse Instead of Recreate

| Original Plan | Modified Approach |
|---------------|-------------------|
| New `sessions` table | Use `meetings` table with `type = 'teaching_session'` |
| New `Session` model | Extend `Meeting` model |
| New `attendances` table (separate) | Extend existing `attendance` table |
| New attendance status enum | Add `status` column to existing attendance |
| New `member_stats` table | Add columns to existing `attendance` pivot or User model |

---

## 🗂️ Database Schema (Minimal Changes)

### Option A: Extend Existing Attendance Table (Recommended)

```php
Schema::table('attendance', function (Blueprint $table) {
    // Add status column - present/late/absent (default 'absent' for new records)
    $table->enum('status', ['present', 'late', 'absent'])->default('absent')->after('check_in_method');

    // Late threshold for this specific attendance record (minutes)
    $table->integer('late_threshold_minutes')->default(15)->after('status');

    // Whether absence was auto-marked by system
    $table->boolean('is_auto_absent')->default(false)->after('status');
});
```

### Option B: Add Member Stats to Users Table (Optional - for leaderboard)

```php
Schema::table('users', function (Blueprint $table) {
    $table->integer('total_sessions_attended')->default(0)->after('attendance_count');
    $table->integer('current_streak')->default(0)->after('total_sessions_attended');
    $table->integer('longest_streak')->default(0)->after('current_streak');
    $table->integer('bonus_points')->default(0)->after('longest_streak');
    $table->decimal('score', 8, 2)->default(0)->after('bonus_points');
});
```

---

## 📁 Project Structure (Modified)

### Models (Extend Existing)

```
app/Models/
├── Meeting.php          ← Add teaching session methods
├── Attendance.php        ← Add status, late logic
└── User.php              ← Add leaderboard stats (optional)
```

### New Services

```
app/Services/
├── AttendanceService.php    ← QR check-in + manual marking + late logic
├── LeaderboardService.php   ← Score calculation + streaks
├── EligibilityService.php   ← Eligibility checks
└── TokenService.php         ← Meeting code validation
```

### Livewire Components (Reuse & Extend)

```
app/Livewire/
├── Teaching/
│   ├── TeachingSessionList.php    ← Filter meetings by type=teaching_session
│   ├── CreateTeachingSession.php ← Create meeting with type=teaching_session
│   ├── SessionDetail.php         ← Shows session + attendance + QR
│   ├── AttendanceMarker.php      ← Manual attendance marking
│   └── QrGenerator.php           ← Display meeting QR code
└── (Existing Members/ components can be reused)
```

### New Artisan Commands

```
app/Console/Commands/
├── AutoCloseTeachingSessions.php   ← Mark sessions as completed
└── TakeEligibilitySnapshot.php     ← Snapshot eligibility
```

---

## 🔄 Session Lifecycle (Using Existing Meeting)

### Start Session (Reuse Meeting)

```
Admin clicks "Start Attendance"
    → Meeting.update(['attendance_open' => true, 'started_at' => now()])
    → Existing meeting_code serves as check-in token
    → QR code displayed using existing QrCodeGenerator
```

### QR Check-In Flow

```
Member scans QR → GET /attendance/verify/{meeting_code}
    → Validate meeting_code exists + meeting.attendance_open = true
    → Get authenticated user
    → AttendanceService::checkInViaQr()
        → Check duplicate
        → Resolve status (present/late) based on late_threshold
        → Record attendance with ip + device_info
        → Trigger LeaderboardService::recalculate(user)
    → Return success view
```

### End Session (Reuse Meeting)

```
Admin clicks "End Attendance" OR scheduled auto-close
    → Meeting.update(['attendance_open' => false, 'ended_at' => now()])
    → AttendanceService::finalizeAbsences() marks non-checked-in as absent
    → LeaderboardService::recalculateAll()
```

---

## 🏆 Leaderboard (Score Formula Retained)

```
attendance_rate = (sessions_attended / total_sessions) * 100
consistency_score = current_streak * 10 (capped at 100)
score = (attendance_rate * 0.7) + (consistency_score * 0.2) + (bonus_points * 0.1)
```

**Streak Logic:**
- After session closes: if member was present/late → increment streak
- If absent → reset streak to 0

---

## 📊 Eligibility Criteria (Retained)

- attendance_rate >= 75%
- sessions_attended >= 5

---

## 🛣️ Routes (Reuse Existing + Add)

```php
// Existing routes to verify/reuse
Route::get('/attendance/verify/{code}', [AttendanceController::class, 'verify'])
    ->middleware('auth')
    ->name('attendance.verify');

// New routes for teaching sessions
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/teaching-sessions', TeachingSessionsPage::class)->name('teaching-sessions');
    Route::get('/teaching-sessions/{meeting}', SessionDetailPage::class)->name('teaching-sessions.detail');
    Route::get('/leaderboard', LeaderboardPage::class)->name('leaderboard');
});
```

---

## ⚙️ Service Specifications

### AttendanceService.php

```php
class AttendanceService
{
    // QR check-in — validates code, checks duplicate, records status
    public function checkInViaQr(string $meetingCode, User $user): array

    // Manual mark by admin/trainer
    public function markManually(Meeting $session, User $user, string $status, User $markedBy): Attendance

    // Determine status: present vs late
    private function resolveStatus(Meeting $session, Carbon $checkInTime): string

    // Mark all non-checked-in members as absent
    public function finalizeAbsences(Meeting $session): void
}
```

### LeaderboardService.php

```php
class LeaderboardService
{
    public function recalculate(User $user): void
    public function recalculateAll(): void
    public function getRanked(string $filter = 'all'): Collection
}
```

### EligibilityService.php

```php
class EligibilityService
{
    public function isEligible(User $user): bool
    public function snapshotAll(string $semester): void
}
```

---

## 🔐 Permissions (Extend Existing)

Existing roles: admin, trainer, member (from Spatie)

```php
$permissions = [
    'create teaching session',
    'edit teaching session',
    'delete teaching session',
    'start teaching session',
    'end teaching session',
    'mark attendance',
    'view reports',
    'view leaderboard',
    'checkin via qr',
    'take eligibility snapshot',
];
```

| Permission | Admin | Trainer | Member |
|---|---|---|---|
| create teaching session | ✅ | ✅ | ❌ |
| edit teaching session | ✅ | ✅ | ❌ |
| delete teaching session | ✅ | ❌ | ❌ |
| start teaching session | ✅ | ✅ | ❌ |
| end teaching session | ✅ | ✅ | ❌ |
| mark attendance | ✅ | ✅ | ❌ |
| view reports | ✅ | ✅ | ❌ |
| view leaderboard | ✅ | ✅ | ✅ |
| checkin via qr | ❌ | ❌ | ✅ |
| take eligibility snapshot | ✅ | ❌ | ❌ |

---

## 📝 Implementation Phases

### Phase 1 — Database + Models
> Goal: Add status columns to attendance, add stats to User

- [ ] Run migration to add `status`, `late_threshold_minutes`, `is_auto_absent` to attendance
- [ ] Run migration to add leaderboard stats to users table
- [ ] Update Meeting model with teaching session helpers
- [ ] Update Attendance model with status logic
- [ ] Update User model with leaderboard helpers

### Phase 2 — Services
> Goal: Build core logic

- [ ] Build AttendanceService
- [ ] Build LeaderboardService
- [ ] Build EligibilityService

### Phase 3 — Frontend (Teaching Sessions)
> Goal: Session management + manual attendance

- [ ] Create TeachingSessionList Livewire (filter meetings by type=teaching_session)
- [ ] Create CreateTeachingSession Livewire
- [ ] Create SessionDetail Livewire with AttendanceMarker
- [ ] Build Teaching Sessions page

### Phase 4 — QR Check-In
> Goal: Self check-in for members

- [ ] Extend existing AttendanceController for QR check-in
- [ ] Update QrCodeGenerator for teaching sessions
- [ ] Add check-in success/error views

### Phase 5 — Leaderboard + Eligibility
> Goal: Rankings and eligibility snapshots

- [ ] Build LeaderboardService integration
- [ ] Create LeaderboardPage
- [ ] Create TakeEligibilitySnapshot artisan command
- [ ] Build EligibilityPage (admin only)

### Phase 6 — Dashboard + Polish
> Goal: Dashboard widgets, responsive UI

- [ ] Add teaching session stats to dashboard
- [ ] Final polish

---

## ✅ Advantages of This Modified Plan

1. **Less code to maintain** — reuse existing Meeting/Attendance infrastructure
2. **Faster to build** — skip creating new tables, models, migrations
3. **Consistent UX** — members already familiar with meeting attendance
4. **Easier to extend** — future features (events, general meetings) use same system
5. **Single source of truth** — all attendance in one table

---

## ⚠️ Questions to Answer Before Implementation

1. Should we use meetings table directly or add a `type` column to distinguish teaching sessions? - add a type column as u have suggested.
2. Do we want leaderboard stats on User model or a separate pivot table? add them to the users table please .
3. Should existing meeting attendance records be migrated or kept as-is? -  there are no existing records so ignore that.

---

*Ready for implementation once questions above are answered.*
