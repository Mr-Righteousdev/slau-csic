# Research Summary: Club Event System Enhancements

**Project:** Club Management Event System Enhancements  
**Synthesized:** 2026-04-25  
**Domain:** RSVP systems, calendar integrations, recurring events in Laravel

---

## Executive Summary

This club management system already has a solid foundation with Event, EventRegistration, and EventRecurrence models. The codebase uses Laravel 12, Livewire v3, and follows consistent patterns: model-scoped business logic, Livewire for UI interactivity, and Filament for admin forms.

**Research Recommendation:** Enhance existing infrastructure rather than adding new packages. The existing `EventRegistration` model handles RSVP tracking, and `asantibanez/livewire-calendar` provides calendar display. New additions should target `spatie/icalendar-generator` for calendar export and selective recurring event enhancements.

**Key Risk Areas:**
- **RSVP race conditions:** Concurrent RSVPs can oversell events without proper locking
- **Timezone handling:** DST transitions cause recurring event display errors
- **Event payload patterns:** Passing full models to listeners causes serialization issues

**Overall Confidence:** HIGH — Multiple current sources agree, existing codebase provides strong foundation.

---

## Key Findings

### Technology Stack (STACK.md)

| Capability | Recommendation | Why |
|------------|----------------|-----|
| RSVP Management | Enhance existing `EventRegistration` | Model exists, integrates with current relationships |
| Calendar UI | Continue `asantibanez/livewire-calendar` | Already installed and functional |
| iCal Export | `spatie/icalendar-generator` | RFC 5545 compliant, Spatie quality |
| Google Calendar | `spatie/laravel-google-calendar` (optional) | Simple sync if needed |
| Recurring Events | Enhance existing `EventRecurrence` | Model exists, covers 90% of club needs |
| RRULE parsing | `starfolksoftware/redo` (optional) | For complex patterns |

**Existing Infrastructure:**
- `Event` model with relationships to registrations and recurrence
- `EventRegistration` model with status enum (registered, waiting, cancelled, attended)
- `EventRecurrence` model with pattern/interval/ends_at fields
- `Livewire/EventCalendar` component (functional)

**Installation Priority:**
```bash
composer require spatie/icalendar-generator  # Recommended for v1
# Optionally: composer require spatie/laravel-google-calendar
```

---

### Feature Landscape (FEATURES.md)

**RSVP Systems — Table Stakes:**
- RSVP yes/no response with confirmation feedback
- Capacity limits (hard stop on attendee count)
- Attendee count display (X/Y spots filled)
- Attendee list view (who's coming)

**RSVP Differentiators (defer to v2 unless high priority):**
- Waitlists (auto-promote when capacity opens)
- Plus-one support
- QR code check-in
- Automated reminders (7-day, night-before, day-of)

**Calendar Views — Table Stakes:**
- Month view (primary overview)
- Week view (operational scheduling)
- Day view (detailed single-day)
- Navigation + Today indicator
- Color coding by category

**Calendar Differentiators (defer):**
- Year view, agenda view
- Drag-and-drop rescheduling
- Multi-day event display

**Recurring Events — Table Stakes:**
- Weekly recurrence
- Generate future instances
- Edit series (change all occurrences)
- Basic end conditions

**Recurring Differentiators (defer):**
- Monthly patterns (by day-of-month, week-of-month)
- Exception handling (modify/cancel single instances)
- Full RRULE support

---

### Architecture Patterns (ARCHITECTURE.md)

**Current Components:**
```
Event → Registration + Recurrence + Calendar
         ↓
Livewire: EventRegistration, EventDetails, EventCalendar, MyEvents
Admin:   EventsManagement (Filament)
```

**Patterns to Follow:**
1. **Livewire in `App\Livewire`** namespace
2. **Model-scoped logic** (e.g., `getIsFullAttribute()`)
3. **Filament Actions** for admin CRUD
4. **Route model binding** via slug (`/events/{event:slug}`)
5. **Service-free design** — use model methods, not service classes

**Data Flow — RSVP:**
```
1. User visits /event/{slug}/register (authenticated)
2. Component checks: existing registration, remaining spots, deadline
3. User clicks Register → EventRegistration::create(status='registered')
4. User clicks Unregister → status='cancelled'
```

**Data Flow — Recurring (proposed):**
```
1. Admin creates Event + EventRecurrence (parent)
2. Scheduled job daily: generates occurrences
3. Each occurrence: independent registration, linked to parent
```

---

### Domain Pitfalls (PITFALLS.md)

**Critical Pitfalls (must prevent):**

| # | Pitfall | Prevention |
|---|--------|-----------|
| 1 | Queued listeners execute before transaction commits | Use `ShouldHandleEventsAfterCommit` |
| 2 | RSVP race conditions (overselling) | DB uniqueness constraint + pessimistic locking |
| 3 | DST/timezone mismatches | Use IANA IDs (`America/New_York`), test DST dates |
| 4 | Passing entire models to event payloads | Pass IDs only, fetch fresh in listeners |
| 5 | Model events contain business logic | Use explicit events in service layer |

**Moderate Pitfalls:**

| # | Pitfall | Prevention |
|---|--------|-----------|
| 6 | Silent listener failures | Implement `failed()` method, monitor queue |
| 7 | Circular event dependencies | Document event flow, add loop detection |
| 8 | RRULE implementation complexity | Use library (`starfolksoftware/redo`), not custom |

**Phase-Specific Warnings:**

| Phase | Likely Pitfall | Mitigation |
|-------|---------------|------------|
| RSVP system | Race conditions | DB constraints + atomic ops from start |
| Calendar display | DST/timezone | Use IANA IDs, test DST dates |
| Event notifications | Silent failures | Implement `failed()` method |
| Recurring events | RRULE complexity | Use library, not custom |

---

## Roadmap Implications

### Suggested Phase Structure

Based on combined research, recommend splitting into these phases:

### Phase 1: RSVP System Enhancements

**Rationale:** RSVP is core to events. Existing EventRegistration model provides foundation, but capacity logic needs hardening.

**Deliverables:**
- Capacity limit enforcement with race condition prevention
- Waitlist support (auto-add when full, auto-promote on cancel)
- Attendee count display + attendee list view
- RSVP confirmation feedback

**Pitfalls to Avoid:**
- Race conditions — use `unique constraint (event_id, user_id)` + `SELECT FOR UPDATE`
- Don't add payment processing (defer to v2)

### Phase 2: Calendar Display Enhancements

**Rationale:** Calendar UI already works via livewire-calendar. Add views and filtering.

**Deliverables:**
- Week and day views (in addition to month)
- Today indicator and navigation
- Category color coding
- Filter by category

**Pitfalls to Avoid:**
- DST/timezone issues — use IANA IDs for timezone storage

### Phase 3: Recurring Events

**Rationale:** EventRecurrence model exists but lacks generation logic. Weekly recurrence covers 90% of club needs.

**Deliverables:**
- Weekly recurrence pattern selection
- Scheduled job to generate occurrences
- Edit series (propagate changes)
- Basic end conditions (date or count)

**Pitfalls to Avoid:**
- RRULE complexity — use column-based fields, not full RRULE
- Infinite materialization — generate rolling window ($N$ days ahead)

### Phase 4: Calendar Export & Reminders

**Rationale:** Export and notification are common user requests.

**Deliverables:**
- iCal export (individual + series)
- Automated reminders (configuration: 7-day, day-before)
- Google Calendar sync (optional)

**Pitfalls to Avoid:**
- RFC 5545 violations in ICS — use spatie/icalendar-generator
- Silent notification failures — implement `failed()` method

### Phase 5: Advanced Features (if needed)

**Rationale:** Differentiators that require more complexity.

**Deliverables:**
- Year/agenda calendar views
- Plus-one support
- QR check-in
- Monthly recurrence patterns
- Single-instance exceptions

---

## Confidence Assessment

| Area | Confidence | Notes |
|------|------------|-------|
| Stack | HIGH | Existing codebase + well-maintained packages |
| Features | HIGH | Multiple current sources agree on table stakes |
| Architecture | HIGH | Strong existing foundation, clear patterns |
| Pitfalls | HIGH | Well-documented from production incidents |

**Gaps to Address:**
- Need to validate calendar UI before adding FullCalendar.js (may not need it)
- Recurring events: confirm weekly pattern sufficiency before adding RRULE
- Confirm if Google Calendar sync is needed (optional)

---

## Research Flags

**Needs Research During Planning:**
- Phase 1: Race condition implementation details
- Phase 4: ICS RFC 5545 validation requirements

**Standard Patterns (no research needed):**
- RSVP flow (existing model with enhancements)
- Calendar basic views (livewire-calendar handles)
- Weekly recurrence (simple column-based fields)

---

## Sources

- **Technology:** STACK.md — spatie packages (HIGH), existing codebase (HIGH)
- **Features:** FEATURES.md — competitive analysis, best practices (HIGH)
- **Architecture:** ARCHITECTURE.md — existing code patterns (HIGH)
- **Pitfalls:** PITFALLS.md — production incident analysis (HIGH)