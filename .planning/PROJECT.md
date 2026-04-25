# PROJECT.md — SLAU CSIC Event Enhancements

## What This Is

Enhance the existing SLAU CSIC club management system's event module with: RSVP management, improved calendar views, recurring events, and event organization with categories/filtering/search.

## Why This Matters

- Current event system lacks RSVP tracking — can't manage attendee capacity
- No calendar view for members to see upcoming events 
- Manual event creation for recurring club meetings
- Events not organized — no way to filter by type/club

## Expected Outcomes

1. Members can RSVP to events with capacity limits
2. Interactive calendar showing all events
3. Recurring events auto-generate future instances
4. Events organized by category with filtering

---

## Context

### Brownfield Project

Existing codebase already has:
- Event creation/editing (CreateEvent, EditEvent)
- Event display (EventDetails, EventCards, EventCalendar, MyEvents)
- Event registration for events

Existing features:
- Event model with title, description, date, location
- Public event browsing
- Basic attendee tracking

### Tech Stack

- Laravel 12, Livewire 3
- PHP 8.4
- MySQL database
- Tailwind CSS

---

## Requirements

### Validated

- ✓ Event creation and editing — existing
- ✓ Event display pages — existing
- ✓ Basic attendee tracking — existing
- ✓ Calendar component exists — existing

### Active

- [ ] RSVP system with capacity limits
- [ ] Attendee management tools
- [ ] Recurring event support
- [ ] Event categories
- [ ] Event filtering and search

### Out of Scope

- Event notifications (email/push) — defer to v2
- Event RSVP waitlists — defer to v2
- Event collaboration/co-hosting — defer to v2
- Event check-in system — defer to v2

---

## Key Decisions

| Decision | Rationale | Outcome |
|----------|---------|---------|
| RSVP vs registration | Events need RSVP for capacity control | RSVP with yes/no |
| Calendar library | Already using FullCalendar | Continue with FullCalendar |
| Recurring pattern | Weekly club meetings common | Weekly/monthly patterns |

---

## Technical Notes

- Event model already has `max_attendees` potential field
- Need recurring_events table for pattern storage
- Categories can use existing approach (model + enum)
- Calendar needs API endpoint for event data

---

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---

*Last updated: 2026-04-25 after initialization*