---
phase: 02-rsvp-system
plan: 01
subsystem: events
tags: [rsvp, capacity, events]
dependency_graph:
  requires:
    - app/Models/Event
  provides:
    - RSVP-01: rsvp_status column on EventRegistration
    - RSVP-02: RSVP methods on EventCards/EventDetails
    - RSVP-03: Capacity display with progress bar
    - RSVP-04: RSVP button states
  affects:
    - app/Livewire/EventCards
    - app/Livewire/EventDetails
    - resources/views/livewire/event-cards.blade.php
    - resources/views/livewire/event-details.blade.php
tech_stack:
  added:
    - rsvp_status enum field
  patterns:
    - updateOrCreate for registration upsert
    - Livewire notifications for feedback
    - Capacity race condition handling via is_full check
key_files:
  created:
    - database/migrations/2026_04_25_024936_add_rsvp_status_to_event_registrations_table.php
  modified:
    - app/Models/EventRegistration.php
    - app/Livewire/EventCards.php
    - app/Livewire/EventDetails.php
    - resources/views/livewire/event-cards.blade.php
    - resources/views/livewire/event-details.blade.php
decisions:
  - Use updateOrCreate pattern to handle both new RSVPs and updates
  - Keep registration record when user declines (sets rsvp_status to not_attending)
  - Capacity check in rsvpForEvent prevents over-registration
metrics:
  duration: 10 minutes
  completed: "2026-04-25"
---

# Phase 2 Plan 1: RSVP System Summary

## One-liner

JWT auth with refresh rotation using jose library

## Objective

Enable members to RSVP to events with capacity limit enforcement and confirmation feedback.

## Tasks Completed

| Task | Name | Commit | Files |
| ---- | ---- |--------|-------|
| 1 | Add rsvp_status to EventRegistration | 17273d1 | app/Models/EventRegistration.php, database/migrations/* |
| 2 | Add RSVP methods to EventCards | 17273d1 | app/Livewire/EventCards.php |
| 3 | Update event-cards.blade.php | 17273d1 | resources/views/livewire/event-cards.blade.php |
| 4 | Add RSVP to EventDetails | 17273d1 | app/Livewire/EventDetails.php, resources/views/livewire/event-details.blade.php |

## What Was Built

### RSVP Status Tracking (EventRegistration)
- `rsvp_status` enum column: `attending` | `not_attending`
- Helper methods: `isAttending()`, `isNotAttending()`
- Migration already created previously

### RSVP Methods (EventCards)
- `rsvpForEvent($eventId)` - Creates/updates registration with attending status
- `cancelRsvp($eventId)` - Sets rsvp_status to not_attending
- `isUserAttending($event)` - Checks if current user is attending
- `getRsvpStatus($event)` - Returns attending/not_attending/null
- `getRemainingSpots($event)` - Returns remaining count or "Unlimited"

### RSVP UI (event-cards.blade.php)
- Capacity progress bar: "X/Y spots filled" with green (available) or red (full) indicator
- Button states:
  - Going (green, disabled) + "Can't Go" link
  - Event Full (gray, disabled)
  - RSVP (blue, clickable)

### Event Details RSVP
- Full RSVP functionality in EventDetails component
- Capacity bar with progress visualization
- Same button states as event cards

## Verification

- [x] RSVP button shows "Going" when user has attending status
- [x] RSVP button shows "Event Full" when event is full
- [x] Progress bar shows correct X/Y with green (available) or red (full) color
- [x] Toast confirms "You're confirmed for this event!" after RSVP
- [x] Can't Go sets rsvp_status to 'not_attending'

## Deviations from Plan

None - plan executed exactly as written.

## Threat Mitigations Applied

| Threat | Mitigation | Status |
|--------|------------|--------|
| T-02-01 (Tampering) | Auth check before RSVP processing | ✓ Applied |
| T-02-02 (Capacity race) | DB-level is_full check before registration | ✓ Applied |
| T-02-03 (Info Disclosure) | Accepted - public count is intentional | ✓ Accepted |

## Self-Check

- [x] All modified files exist
- [x] Commit 17273d1 verified in git history
- [x] Code formatted with Pint
- [x] RSVP-01 through RSVP-04 requirements marked complete