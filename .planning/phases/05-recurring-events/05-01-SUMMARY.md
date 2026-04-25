---
phase: 05-recurring-events
plan: 01
subsystem: events
tags: [recurring-events, scheduling, series-editing]
dependency_graph:
  requires: []
  provides: [REC-01, REC-02, REC-03]
  affects: [EventCards, EventCalendar, CreateEvent]
tech_stack:
  added:
    - app/Services/RecurrenceGenerator.php
    - app/Console/Commands/GenerateRecurringEvents.php
  patterns:
    - Hybrid instance generation (3 months ahead)
    - Master/occurrence event model
    - Series-wide sync on edit
key_files:
  created:
    - app/Services/RecurrenceGenerator.php
    - app/Console/Commands/GenerateRecurringEvents.php
    - database/migrations/2026_04_25_033127_add_recurring_fields_to_events_table.php
  modified:
    - app/Models/Event.php
    - app/Livewire/CreateEvent.php
    - app/Livewire/EventCards.php
    - app/Livewire/EventCalendar.php
    - resources/views/livewire/create-event.blade.php
    - resources/views/livewire/event-cards.blade.php
    - routes/console.php
decisions:
  - D-01: Recurrence enabled on event creation via checkbox in form
  - D-02: Hybrid instance generation - 3 months ahead, max 52/year
  - D-03: Series edits sync to all future occurrences via syncOccurrences()
  - D-05: skipOccurrence() for cancelling specific dates
  - D-06: Recurring badge on EventCards with icon
metrics:
  duration: "~6 minutes"
  completed_date: "2026-04-25T06:32:32+03:00"
---

# Phase 5, Plan 1: Recurring Events Summary

Weekly recurrence for events with automatic instance generation and series-wide editing.

## Completed Tasks

| Task | Name | Commit | Files |
|------|------|--------|-------|
| 1 | Add recurrence support to Event model and creation | 2f6098e | Event.php, CreateEvent.php, create-event.blade.php |
| 2 | Create RecurrenceGenerator service and scheduled command | c0e1525 | RecurrenceGenerator.php, GenerateRecurringEvents.php, console.php |
| 3 | Add series-wide editing and recurring badge | d1a51cc | EventCards.php, EventCalendar.php, event-cards.blade.php |

## Must-Haves Delivered

- [x] Admin can create event with weekly recurrence option
- [x] System generates future event instances from recurrence
- [x] Series edits propagate to all future occurrences
- [x] Event cards display recurring badge linking to series

## Implementation Details

### Event Model Changes
- Added `is_recurring`, `parent_event_id`, `cancelled_at` fields (via migration)
- `isRecurring()` returns true if event has recurrence
- `isOccurrence()` returns true if event is a generated instance
- `isMasterEvent()` returns true if event is the series master
- `syncOccurrences()` updates all future occurrences
- `skipOccurrence(Event $occurrence)` marks specific occurrence as cancelled

### RecurrenceGenerator Service
- `generateInstances(Event $event, int $monthsAhead = 3)` creates future Event instances
- `regenerateUpcoming()` called by scheduler to regenerate approaching dates
- Uses hybrid approach: 3 months ahead, max 52 instances per year
- Pattern support: weekly, biweekly, monthly

### CreateEvent Form
- Added recurrence_enabled checkbox
- Added recurrence_pattern select (weekly/biweekly/monthly)
- Added recurrence_ends_at date picker
- Creates EventRecurrence record on save

### EventCards Display
- Recurring badge shown for is_recurring events
- Badge includes rotating arrows icon
- Badge color: purple
- Links to master event details

### EventCalendar
- Added `is_recurring` and `parent_event_id` to event data
- Calendar can distinguish recurring series

### Scheduled Command
- `events:generate-recurring` runs daily at 1am
- Registered in routes/console.php

## Requirements

- [x] REC-01: Events support weekly recurrence
- [x] REC-02: System generates future instances
- [x] REC-03: Series edits propagate to occurrences

## Threat Mitigation

- T-REC-01 (DoS): Capped at 52 instances per event per run
- T-REC-02 (Info Disclosure): Badge only shows publicly available info