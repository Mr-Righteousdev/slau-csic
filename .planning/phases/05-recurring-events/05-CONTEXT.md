# Phase 5: Recurring Events - Context

**Gathered:** 2026-04-25
**Status:** Ready for planning

<domain>
## Phase Boundary

Events can recur weekly and auto-generate future instances. This phase covers:
- REC-01: Events support weekly recurrence
- REC-02: System generates future instances
- REC-03: Series edits propagate to occurrences

</domain>

<decisions>
## Implementation Decisions

### Instance Generation Strategy (REC-02)
- **D-01:** Hybrid approach — Generate 3 months ahead at event creation, regenerate as dates approach
- **D-02:** Use scheduled Laravel command for ongoing generation

### Instance Editing Strategy (REC-03)
- **D-03:** Series-wide only — Edits to the master event propagate to all future occurrences
- **D-04:** No per-instance overrides — Keep it simple for v1

### Deletion Handling
- **D-05:** Skip date — When individual occurrence deleted, mark as skipped in series

### Display in Event UI
- **D-06:** Series indicator — Show "Recurring weekly" badge on event cards
- **D-07:** Link to series — Badge links to master event details

### Agent's Discretion
- Exact number of months to generate ahead (3 is default)
- Scheduling frequency for regeneration command
- Badge styling details

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Codebase
- `app/Models/Event.php` — Has recurrence() relationship
- `app/Models/EventRecurrence.php` — Existing recurrence model
- `app/Livewire/EventCalendar.php` — Calendar display

### No external specs — requirements fully captured in decisions above

</canonical_refs>

<a name="code_context"></a>
## Existing Code Insights

### Reusable Assets
- EventRecurrence model already exists with pattern fields
- EventCalendar can display generated instances
- Laravel scheduler available for cron jobs

### Established Patterns
- Event model with relationships
- Livewire for dynamic UI
- Laravel scheduled commands

### Integration Points
- Event creation flow needs recurrence option
- Calendar displays occurrences
- Series management via EventRecurrence

</code_context>

<specifics>
## Specific Ideas

No specific requirements — open to standard approaches using existing codebase patterns.

</specifics>

<deferred>
## Deferred Ideas

- Per-instance overrides — complex, defer to v2
- Monthly/yearly recurrence patterns — defer to v2
- iCal export of recurring events — defer to v2

</deferred>

---

*Phase: 05-recurring-events*
*Context gathered: 2026-04-25*