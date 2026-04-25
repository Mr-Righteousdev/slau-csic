# Phase 3: Attendee Management - Context

**Gathered:** 2026-04-25
**Status:** Ready for planning

<domain>
## Phase Boundary

Admin can view and manage attendees per event. This phase covers:
- ATT-01: Admin can view attendee list per event
- ATT-02: Admin can manage attendee registrations
- ATT-03: User can cancel their own RSVP

</domain>

<decisions>
## Implementation Decisions

### Attendee List Display (ATT-01)
- **D-01:** Table layout with pagination — Show name, email, RSVP status, registered date
- **D-02:** Sortable columns for admin convenience

### Admin Management Actions (ATT-02)
- **D-03:** Cancel registration — Admin can cancel any attendee's registration
- **D-04:** Remove from event — Admin can remove attendees entirely

### User Self-Cancel (ATT-03)
- **D-05:** Button on event detail page — "Cancel RSVP" button visible when user has RSVP'd
- **D-06:** Confirmation dialog — Require confirmation before cancelling

### Cancellation Behavior
- **D-07:** Spot freed immediately — When RSVP cancelled, spots available increases
- **D-08:** No waitlist notifications — Waitlist not in scope per PROJECT.md

### Agent's Discretion
- Exact table column widths
- Pagination size (10, 25, 50 per page)
- Confirmation dialog styling

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Codebase
- `app/Models/EventRegistration.php` — Has rsvp_status, isAttending() methods
- `app/Livewire/EventDetails.php` — Event detail page with RSVP
- `app/Livewire/EventCards.php` — Event cards with RSVP

### No external specs — requirements fully captured in decisions above

</canonical_refs>

<a name="code_context"></a>
## Existing Code Insights

### Reusable Assets
- EventRegistration model with rsvp_status
- EventDetails component for detail page
- Toast notification system

### Established Patterns
- Table layouts in other admin components
- Confirmation dialogs use existing modal patterns

### Integration Points
- Attendee management connects to EventRegistration
- Admin routes for management views

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

*Phase: 03-attendee-management*
*Context gathered: 2026-04-25*