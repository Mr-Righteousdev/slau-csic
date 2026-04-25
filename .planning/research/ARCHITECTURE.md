# Architecture Patterns: Event System (RSVP, Recurring, Calendar)

**Domain:** Laravel/Livewire Event Management
**Researched:** 2026-04-25
**Overall confidence:** HIGH

## Executive Summary

The existing event system follows standard Laravel 12 patterns with Livewire v3 for interactivity. The Event model has established relationships for RSVP (EventRegistration), recurring events (EventRecurrence), and calendar display (EventCalendar). RSVP is fully implemented with status tracking, while recurring events have a foundation but lack UI. Calendar integration exists via EventCalendar Livewire component. All components follow consistent patterns: service-free business logic in models, Livewire for UI, Filament for admin forms.

## Current Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ROUTE LAYER                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│  Public:     /events/{slug}          → EventDetails (Livewire)           │
│  Auth:       /events/{slug}/register  → EventRegistration (Livewire)      │
│  Auth:       /my-events               → MyEvents (Livewire)             │
│  Admin:      /admin/events            → EventsManagement (Livewire)     │
│  Admin:      /admin/events/{id}/attendance → EventAttendanceController   │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                      LIVEWIRE COMPONENTS                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│  EventCalendar          → Display/create calendar events                  │
│  EventDetails          → Public event page                               │
│  EventRegistration    → User RSVP form                                   │
│  MyEvents            → User's registered events list                     │
│  Admin/EventsManagement → CRUD with FilamentActions                      │
│  CreateEvent         → Event creation form (stub)                        │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        MODEL LAYER                                       │
├───────────────────────────���─────────────────────────────────────────────────┤
│  Event                  → Core event entity                               │
│  EventRegistration    → RSVP records with status                       │
│  EventRecurrence      → Recurring event patterns                         │
│  EventFeedback      → Post-event feedback                               │
│  EventResource     → Event materials/resources                          │
│  User               → Member, organizer, instructor roles             │
└─────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│  events                      → Core event data                          │
│  event_registrations         → RSVP with status enum                     │
│  event_recurrence            → Pattern, interval, ends_at              │
│  event_instructors           → Many-to-many                             │
│  event_resources            → Event materials                           │
│  event_feedback             → Post-event ratings                        │
└─────────────────────────────────────────────────────────────────────────────┘
```

## Component Boundaries

### Event Component

** Responsibility:** Core event entity with scheduling, capacity, visibility
** Communicates With:** EventRegistration (RSVP), EventRecurrence (recurrence), User (organizer)
** Responsibilities:**
- Title, description, type, dates, location, capacity
- Registration settings and deadline
- Status workflow (draft → published → ongoing → completed)
- Slug generation on create
- Computed: registered_count, attended_count, is_full, remaining_spots

### EventRegistration Component

** Responsibility:** RSVP management with attendance tracking
** Communicates With:** Event, User
** Key Methods:**
- `register()` - Create registration with status=registered
- `unregister()` - Cancel (set status=cancelled)
- `hasAttended()` - Check if attended
- `isWaitlisted()` - Check waitlist status
** Status Values:** registered, waitlist, attended, cancelled, no_show
** Unique Constraint:** event_id + user_id (prevents duplicate RSVP)

### EventRecurrence Component

** Responsibility:** Recurring event pattern definition
** Communicates With:** Event (parent)
** Patterns Supported:** weekly, biweekly, monthly
** Fields:** pattern, interval, ends_at
** Current State:** Model exists, but no scheduled job to generate occurrences

### EventCalendar Component

** Responsibility:** Calendar display and creation
** Communicates With:** Event (reads), dispatch for UI events
** Features:**
- Displays published/scheduled events
- Color coding by type
- Click to view details
- Authenticated creation

## Data Flow

### RSVP Registration Flow

```
1. User visits /events/{slug}/register (authenticated)
2. EventRegistration mount() checks:
   - Existing registration for this user+event
   - Remaining spots (if capacity limit)
   - Registration deadline passed
3. User clicks "Register":
   - EventRegistration::create()
   - status='registered', registered_at=now()
   - Set registered=true, decrement remainingSpots
   - Dispatch notification
4. User clicks "Unregister":
   - Update status='cancelled'
   - Set registered=false, increment remainingSpots
```

### Recurring Event Flow (Proposed)

```
1. Admin creates Event with recurrence pattern
2. System stores: Event + EventRecurrence (parent)
3. Scheduled job (daily):
   a. Find events with recurrence ending future
   b. For each occurrence_date in range:
      - Check if occurrence exists
      - Create if not (with parent reference)
4. Each occurrence:
   - Independent registration limit
   - Can be modified independently
   - Linked to parent via recurrence_id
```

### Calendar Flow

```
1. EventCalendar mount():
   - Query events: is_public=true, status=scheduled
   - Map to calendar format (ISO8601 dates)
2. User clicks event:
   - dispatch('eventClick', eventId)
   - handleEventClick() loads full event
   - Open modal with details
3. User clicks date (authenticated):
   - dispatch('dateClick', date)
   - open-create-modal with pre-filled date
```

## Patterns to Follow

### Pattern 1: Livewire Component Organization

**What:** Each feature area has dedicated Livewire component in App\Livewire (not App\Http\Livewire)
**When:** All new event features
**Example:**
```php
namespace App\Livewire;

use Livewire\Component;

class EventRSVPList extends Component
{
    #[Route]
    public function render()
    {
        return view('livewire.event-rsvp-list');
    }
}
```

### Pattern 2: Filament Actions in Admin

**What:** Use Filament Actions for CRUD in admin components
**When:** Admin event management UI
**Example:** See EventsManagement::getEventFormSchema() using Filament form components

### Pattern 3: Notification Dispatch

**What:** Use Livewire dispatch for UI feedback
**When:** Action completion feedback
**Example:**
```php
$this->dispatch('show-notification', message: 'Registration successful', type: 'success');
```

### Pattern 4: Model-Scoped Business Logic

**What:** Business logic in model methods, not controllers
**When:** Event capacity, registration checks
**Example:**
```php
// In Event model
public function getIsFullAttribute(): bool
{
    return $this->max_participants && $this->registered_count >= $this->max_participants;
}
```

### Pattern 5: Route Model Binding

**What:** Route using event:slug for automatic model resolution
**When:** Event routes
**Example:**
```php
Route::get('/events/{event:slug}', EventDetails::class);
```

## Anti-Patterns to Avoid

### Anti-Pattern 1: Service Layer Bloat

**What:** Creating EventService classes for simple CRUD
**Why:** Laravel models handle this well; adds unnecessary abstraction
**Instead:** Use model scopes, methods, relationships

### Anti-Pattern 2: Duplicate Registration Logic

**What:** Checking capacity in both controller and model
**Why:** Inconsistency, maintenance burden
** Instead:** Single source in model (getIsFullAttribute)

### Anti-Pattern 3: Hardcoded Status Values

**What:** Using string literals for status checks
**Why:** Error-prone, inconsistent
**Instead:** Use model methods: `hasAttended()`, `isCancelled()`

### Anti-Pattern 4: Missing Parent Reference

**What:** Creating recurrence occurrences without linking to parent
**Why:** Can't update all occurrences, can't track cancellation
**Instead:** Add recurrence_id, include inOccurrence() check

## Scalability Considerations

| Concern | At 100 Events | At 10K Events | At 1M Events |
|---------|---------------|--------------|-------------|
| Query | Eager load organizer+registrations | Add status/index | Partition by year |
| Calendar | Load all published | Filter by date range | Paginate + infinite scroll |
| RSVP | Simple unique constraint | Add composite index | Queue registration |
| Recurrence | Daily job | Batch by chunk | Scheduled worker pool |

## Integration Points

### With Spatia Permissions

```php
// Routes use role middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/events', EventsManagement::class);
});
```

### With Filament Actions

```php
// Admin uses Filament form components
EditAction::make()
    ->form($this->getEventFormSchema());
```

### With Notifications

```php
// Uses Filament/Livewire notifications
Notification::make()
    ->title('Success')
    ->success()
    ->send();
```

## Sources

- `app/Models/Event.php` - Core event model with relationships
- `app/Models/EventRegistration.php` - RSVP model
- `app/Models/EventRecurrence.php` - Recurrence pattern model
- `app/Livewire/EventCalendar.php` - Calendar component
- `app/Livewire/EventRegistration.php` - Registration component
- `app/Livewire/Admin/EventsManagement.php` - Admin CRUD with Filament
- `routes/web.php` - Route definitions