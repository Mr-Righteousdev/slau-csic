# Technology Stack: Event RSVP, Calendar & Recurring Events

**Project:** Club Management Event System Enhancements  
**Researched:** 2026-04-25  
**Domain:** RSVP systems, calendar integrations, recurring events in Laravel

---

## Executive Summary

This club management system already has a solid foundation with Event and EventRegistration models. For enhancing RSVP functionality, calendar integrations, and recurring events, the ecosystem offers mature, well-maintained packages that integrate seamlessly with Laravel 12 and Livewire 3.

**Recommended Stack:**

| Capability | Primary Choice | Alternative |
|------------|---------------|-------------|
| RSVP Management | Custom (EventRegistration model exists) | `offload-project/laravel-invite-only` for advanced invitation handling |
| Calendar UI | `asantibanez/livewire-calendar` (existing) | Custom FullCalendar.js integration |
| iCal Export | `spatie/icalendar-generator` | Built-in custom implementation |
| Google Calendar | `spatie/laravel-google-calendar` | `youngfolks/laravel-gcal-sync` for complex sync |
| Recurring Events | `starfolksoftware/redo` | `simshaun/recurr` for RRULE parsing |

**Confidence:** HIGH

The existing codebase already uses `asantibanez/livewire-calendar` successfully. Recommended additions build on this foundation without disrupting existing functionality.

---

## Core Technology Recommendations

### 1. RSVP Management

**Recommendation:** Enhance existing `EventRegistration` model rather than adding packages

| Aspect | Recommendation | Why |
|--------|--------------|-----|
|Status tracking|Use existing `EventRegistration::class` with status enum|Boilerplate for club events: registered, waiting, cancelled |
|Waitlist|Add `waitlist_position` column to existing table|Prevents need for separate model |
|Bulk RSVP|Build on existing `EventRegistration` relationship|Leverages existing Eloquent relationships |
|Reminders|Use Laravel notifications + scheduled commands|Custom implementation gives full control |

**Existing Infrastructure (already in codebase):**

```php
// app/Models/EventRegistration.php exists
// Event -> registrations() relationship exists

// Recommended extensions:
$fillable = [
    'event_id',
    'user_id', 
    'status', // 'registered', 'waiting', 'cancelled', 'attended'
    'registered_at',
    'attended_at',
    'waitlist_position',
    'notes',
];
```

**RSVP Status Flow:**

```
Registered → Attended (on check-in)
          → Cancelled (user or admin)
          → Waiting (if event full, auto-promoted)
```

**Alternative Package — Only if Invitations Required:**

If the system needs token-based invitation RSVPs (not member-based), consider:

```bash
composer require offload-project/laravel-invite-only
```

- Polymorphic invitations (attach to any model)
- Bulk invitations with partial failure
- Status tracking: pending, accepted, declined, expired
- Scheduled reminders via Artisan command
- Events fired for all lifecycle changes
- Requires: PHP 8.2+, Laravel 11/12

**Not recommended for typical club RSVP use —** The existing `EventRegistration` model with user associations is more appropriate.

---

### 2. Calendar UI / Display

**Recommendation:** Continue using existing `asantibanez/livewire-calendar`

| Package | Version | Purpose | Status |
|---------|---------|---------|--------|
| `asantibanez/livewire-calendar` | Latest | Calendar grid view with event display | Already installed |
| `fullcalendar/fullcalendar` | ^6.x | FullCalendar.js for advanced views | Optional enhancement |

**Current Implementation:**

The codebase already uses `asantibanez/livewire-calendar`:

```php
// app/Livewire/EventCalendar.php - functional
// Loads public events, handles clicks
// Custom event creation/update
```

**Recommended Enhancements:**

| Enhancement | Package | When to Use |
|-------------|---------|------------|
| Multiple calendars | FullCalendar.js | Need week/month/day/agenda views |
| Drag-and-drop | FullCalendar.js | Need event rescheduling UI |
| Resource scheduling | FullCalendar.js | Multiple rooms/instructors |

**FullCalendar.js Integration (if needed):**

```bash
npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction
```

```php
// Livewire component with FullCalendar
public function render()
{
    return view('livewire.calendar-full', [
        'events' => $this->events->map(fn($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'start' => $e->start_date,
            'end' => $e->end_date,
            'color' => $this->getEventColor($e->type),
        ]),
    ]);
}
```

**Confidence:** MEDIUM — FullCalendar.js adds significant JS complexity. Only upgrade if current Livewire calendar insufficient.

---

### 3. Calendar Export (iCal)

**Recommendation:** Use `spatie/icalendar-generator` for RFC 5545 compliant exports

| Package | Version | Purpose | When to Use |
|---------|---------|---------|----------|
| `spatie/icalendar-generator` | ^3.x | Generate .ics files |
| `spatie/laravel-google-calendar` | ^3.x | Google Calendar API |

**Install iCal Generator:**

```bash
composer require spatie/icalendar-generator
```

**Simple Export:**

```php
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

$calendar = Calendar::create('Club Events')
    ->event(Event::create()
        ->startDate($event->start_date)
        ->endDate($event->end_date)
        ->title($event->title)
        ->description($event->description)
        ->address($event->location)
    );

return response($calendar->get())
    ->header('Content-Type', 'text/calendar; charset=utf-8')
    ->header('Content-Disposition', 'attachment; filename="events.ics"');
```

**Recurring Event Export:**

```php
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\RecurrenceRule;

$calendar = Calendar::create('Weekly Meeting')
    ->event(Event::create()
        ->startDate($event->start_date)
        ->endDate($event->end_date)
        ->title($event->title)
        ->recurrenceRule(RecurrenceRule::create()
            ->freq('WEEKLY')
            ->interval(1)
            ->until($event->recurrence->ends_at)
            ->byDay(['MO']) // Monday
        )
    );
```

**Confidence:** HIGH — Spatie packages are well-maintained, integrate cleanly.

---

### 4. Google Calendar Integration

**Recommendation:** Use `spatie/laravel-google-calendar` for basic sync

| Package | Version | Auth | Complexity |
|---------|---------|------|-----------|
| `spatie/laravel-google-calendar` | ^3.x | Service Account or OAuth2 | Simple |

**Install:**

```bash
composer require spatie/laravel-google-calendar
php artisan vendor:publish --provider="Spatie\GoogleCalendar\GoogleCalendarServiceProvider"
```

**Configuration (.env):**

```env
GOOGLE_CALENDAR_SERVICE_ACCOUNT credentials.json path
GOOGLE_CALENDAR_ID=calendar-id@example.com
```

**Basic Usage:**

```php
use Spatie\GoogleCalendar\Event;

$event = Event::create()
    ->name('Club Meeting')
    ->startDate(Carbon::parse('tomorrow 7pm'))
    ->endDate(Carbon::parse('tomorrow 9pm'))
    ->address('Club House')
    ->save();
```

**Limitation (from official docs):**

> Recurring events cannot be managed properly with this package. If you stick to creating events with a name and a date you should be fine.

**For Advanced Sync (per-user OAuth, recurring events, webhooks):**

Use `youngfolks/laravel-gcal-sync` instead:

```bash
composer require youngfolks/laravel-gcal-sync
```

Features:
- Per-user OAuth 2.0 token storage
- Incremental sync via `syncToken`
- Webhook push notifications
- Recurring event expansion with exceptions
- Conflict resolution strategies

**Confidence:** MEDIUM — Basic sync works well. Complex sync (recurring events, two-way) adds significant complexity.

---

### 5. Recurring Events

**Recommendation:** Choose based on complexity needed

| Approach | Package | Complexity | Use Case |
|----------|---------|------------|----------|
| Simple (existing) | Custom model + RRULE | Low | Weekly/monthly club meetings |
| Medium | `starfolksoftware/redo` | Medium | Multiple recurrence patterns |
| Complex (RRULE full) | `simshaun/recurr` | High | RFC 5545 RRULE parsing |

**Current Implementation (already exists):**

The codebase already has `EventRecurrence` model:

```php
// app/Models/EventRecurrence.php
protected $fillable = [
    'event_id',
    'pattern', // 'weekly', 'biweekly', 'monthly'
    'interval',
    'ends_at',
];

// Helper methods:
isWeekly(), isBiweekly(), isMonthly()
```

This covers 90% of club meeting needs.

**Enhanced Approach 1: starfolksoftware/redo (Recommended for better patterns)**

```bash
composer require starfolksoftware/redo
```

```php
use StarfolkSoftware\Redo\Recurs;

class Meeting extends Model
{
    use Recurs;
}

// Create recurrence:
$meeting->makeRecurrable('WEEKLY', 1, now(), $endsAt);

// Query:
$meeting->recurrences(); // All occurrences
$meeting->nextRecurrence(); // Next occurrence
$meeting->recurrenceIsActive(); // Boolean
```

**Enhanced Approach 2: spatie/icalendar-generator (for iCal export)**

When combined with iCal export, use RRULE:

```php
use Spatie\IcalendarGenerator\Enums\RecurrenceRule;

$rule = RecurrenceRule::create()
    ->freq('WEEKLY')
    ->interval(2) // Biweekly
    ->until($endsAt)
    ->byDay(['MO', 'WE']); // Monday, Wednesday
```

**Approach 3: simshaun/recurr (Full RRULE parsing)**

```bash
composer require simshaun/recurr
```

```php
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;

$rule = new Rule('FREQ=WEEKLY;INTERVAL=2;BYDAY=MO,WE;UNTIL=20260630', $startDate);

$transformer = new ArrayTransformer();
$occurrences = $transformer->transform($rule);

// Contains DateTime objects
foreach ($occurrences as $occurrence) {
    echo $occurrence->getStart()->format('Y-m-d H:i');
}
```

**Database Schema Recommendation:**

Based on existing `EventRecurrence` model, enhance with:

```php
// Schema for recurrence_patterns table
Schema::create('recurrence_patterns', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->string('rrule')->nullable(); // Full RRULE for complex
    $table->string('pattern'); // 'daily', 'weekly', 'monthly', 'yearly'
    $table->integer('interval')->default(1);
    $table->json('by_day')->nullable(); // ['MO', 'WE', 'FR']
    $table->json('by_month_day')->nullable(); // [1, 15]
    $table->date('starts_at');
    $table->date('ends_at')->nullable();
    $table->integer('occurrences')->nullable(); // Limit count
    $table->timestamps();
});
```

**Storage Strategy:**

Use **hybrid approach** (from research on efficient storage):

1. Store RRULE/rrule in `recurrence_patterns` table
2. Precompute next 30-60 days of instances in separate table
3. Query precomputed instances for fast calendar display
4. Generate on-demand for date ranges beyond window

This balances storage with query performance.

---

## Alternative Considerations

| Category | Considered | Why Not (or When) |
|----------|------------|-------------------|
| Full event SaaS | Eventmie Pro | Overkill for club use, costs $69-$999 |
| RSVP SaaS | devwaleh/rsvp-app | Use existing custom implementation |
| Google Sync | spatie (basic) | Use for simple sync only |
| Recurring | laravel-shift/laravel-recurring-models | New package (2025), less battle-tested |
| Calendar UI | epaginator/calendar | Less active than livewire-calendar |

---

## Recommended Installation

**Minimal additions for enhanced events:**

```bash
# For iCal export (recommended)
composer require spatie/icalendar-generator

# Optional: Google Calendar sync (if needed)
composer require spatie/laravel-google-calendar
```

**Optional upgrades (if current calendar insufficient):**

```bash
# FullCalendar.js for advanced UI
npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction
```

---

## Integration with Existing Codebase

**Compatibility Checklist:**

| Existing Component | Integration Point | Status |
|------------------|---------------|--------|
| `Event` model | Use existing, add recurrence | Compatible |
| `EventRecurrence` model | Enhance with RRULE support | Compatible |
| `EventRegistration` model | Add waitlist, status flow | Compatible |
| `Livewire/EventCalendar` | Works with existing | Compatible |
| `asantibanez/livewire-calendar` | Already installed | Compatible |

---

## Sources

- [spatie/laravel-google-calendar](https://github.com/spatie/laravel-google-calendar) — HIGH (official)
- [spatie/icalendar-generator](https://github.com/spatie/icalendar-generator) — HIGH (official)
- [offload-project/laravel-invite-only](https://github.com/offload-project/laravel-invite-only) — MEDIUM (new, 2025)
- [youngfolks/laravel-gcal-sync](https://github.com/youngfolks/laravel-gcal-sync) — MEDIUM (advanced sync)
- [starfolksoftware/redo](https://github.com/starfolksoftware/redo) — MEDIUM (well-maintained)
- [simshaun/recurr](https://github.com/simshaun/recurr) — MEDIUM (RFC 5545)
- [asantibanez/livewire-calendar](https://github.com/asantibanez/livewire-calendar) — HIGH (existing)
- [Calendar Recurring Events database storage](https://www.codegenes.net/blog/calendar-recurring-repeating-events-best-storage-method/) — MEDIUM (pattern reference)