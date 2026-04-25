# Phase 2: RSVP System - Context

**Gathered:** 2026-04-25
**Status:** Ready for planning

<domain>
## Phase Boundary

Members can RSVP to events with capacity limit enforcement. This phase covers:
- RSVP-01: User can RSVP to events with yes/no response
- RSVP-02: System enforces capacity limits (hard stop)
- RSVP-03: User can see attendee count display (X/Y spots)
- RSVP-04: User receives RSVP confirmation feedback

</domain>

<decisions>
## Implementation Decisions

### Capacity Enforcement (RSVP-02)
- **D-01:** Hard stop — RSVP button disabled when event is full, shows "Event Full" message

### Confirmation Feedback (RSVP-04)
- **D-02:** Toast notification — Brief popup confirming RSVP action using existing notification system

### RSVP Data Model
- **D-03:** Extend existing EventRegistration model — Add rsvp_status column (attending/not_attending) to track RSVP state

### Capacity Display (RSVP-03)
- **D-04:** Progress bar with text — "X/Y spots filled" with visual progress bar
- **D-05:** Color-coded indicator — Green when spots available, red when full

### Agent's Discretion
- Exact progress bar styling (colors, size)
- Whether to show "spots remaining" or "spots filled"
- Specific copy for "Event Full" state

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Codebase
- `app/Models/Event.php` — Event model with max_participants, is_full, registered_count
- `app/Models/EventRegistration.php` — Registration tracking
- `app/Livewire/EventCards.php` — Event listing with registration
- `app/Livewire/EventDetails.php` — Event detail page

### No external specs — requirements fully captured in decisions above

</canonical_refs>

<a name="code_context"></a>
## Existing Code Insights

### Reusable Assets
- Event model already has `max_participants`, `is_full`, `registered_count` attributes
- EventCards has registration/unregistration methods
- Toast notification system already in use

### Established Patterns
- Registration handled in EventCards component
- EventRegistration model tracks users per event
- Notifications dispatch via Livewire

### Integration Points
- RSVP buttons on event cards and event detail page
- Capacity check before allowing registration
- Update attendee count after RSVP

</code_context>

<specifics>
## Specific Ideas

No specific requirements — open to standard approaches using existing codebase patterns.

</specifics>

<deferred>
## Deferred Ideas

None — discussion stayed within phase scope

</deferred>

---

*Phase: 02-rsvp-system*
*Context gathered: 2026-04-25*