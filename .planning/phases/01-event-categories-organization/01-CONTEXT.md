# Phase 1: Event Categories & Organization - Context

**Gathered:** 2026-04-25
**Status:** Ready for planning

<domain>
## Phase Boundary

Events organized with categories enabling filtering and search. This phase covers:
- CAT-01: Events organized into categories
- CAT-02: Category color coding in calendar
- CAT-03: Events can be filtered by category
- CAT-04: Events can be searched

</domain>

<decisions>
## Implementation Decisions

### Category Storage (CAT-01)
- **D-01:** Use existing `type` field in events table — already has check constraint with values: workshop, competition, ctf, bootcamp, awareness_campaign, talk, social, hackathon

### Category Display (CAT-02)
- **D-02:** Display category as badge/pill on event cards
- **D-03:** Color code events in calendar based on category type

### Filter UX (CAT-03)
- **D-04:** Use pill toggles for category filtering — clickable buttons to show/hide categories
- **D-05:** Multiple categories can be selected simultaneously (OR logic)

### Search Approach (CAT-04)
- **D-06:** Full-text search across event title and description
- **D-07:** Search bar in event list header (not dedicated page)

### Agent's Discretion
- Specific color palette for each category type — agent can decide based on Tailwind conventions
- Whether search is debounced (recommended for performance)
- Exact placement of filter toggles in UI

</decisions>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Codebase
- `app/Models/Event.php` — Event model with type field and existing relationships
- `app/Livewire/EventCards.php` — Event display component
- `app/Livewire/EventCalendar.php` — Calendar component

### Database
- `database/migrations/` — Events table schema with type check constraint

### No external specs — requirements fully captured in decisions above

</canonical_refs>

<code_context>
## Existing Code Insights

### Reusable Assets
- Event model already has `type` field with check constraint
- EventCards component for displaying events
- EventCalendar component for calendar view

### Established Patterns
- Event `type` uses PHP enum-like check constraint (workshop, competition, ctf, etc.)
- Livewire components handle filtering and state
- Tailwind CSS for styling

### Integration Points
- Event model `type` field connects to all event display views
- Calendar component receives event data via Livewire
- Filtering will modify EventCards/EventCalendar queries

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

*Phase: 01-event-categories-organization*
*Context gathered: 2026-04-25*