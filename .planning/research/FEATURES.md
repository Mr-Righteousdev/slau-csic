# Feature Landscape: Event Enhancements

**Domain:** Club Management — Event RSVP, Calendar, Recurring Events
**Researched:** 2026-04-25
**Confidence:** HIGH (multiple current sources reviewed)

---

## Executive Summary

RSVP systems, calendar views, and recurring events are foundational in event management software. Users expect RSVP tracking with capacity limits and attendee lists as baseline. Differentiators include waitlists, automated reminders, QR check-in, and flexible pattern support. Calendar views beyond basic month/week are table stakes in modern apps — year and agenda views are standard. Recurring events require careful schema design to handle exceptions (modified/canceled instances) effectively.

---

## RSVP Systems

### Table Stakes

Users expect these features. Missing = product feels incomplete.

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| RSVP yes/no response | Core attendance tracking | Low | Two states: attending / not attending |
| Capacity limits | Prevent overcrowding | Low | Hard stop on attendee count |
| Attendee count display | See space availability | Low | Show X/Y spots filled |
| Attendee list view | See who's coming | Low | Basic table of names |
| RSVP confirmation feedback | User needs reassurance | Low | "You're in!" or similar |

**Reference:** GatherGrove, Who's In? both lead with RSVP + capacity limits as core. RSVPify, Eventbrite similarly.

### Differentiators

Features that set products apart. Not expected universally, but valued by power users.

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| Waitlists | Manage overflow gracefully | Medium | Auto-add when capacity reached |
| Plus-one support | Allow guests | Medium | Configurable per-event |
| Custom RSVP forms | Collect needed info | Medium | Dietary, accessibility, etc. |
| QR code check-in | Fast door entry | Medium | Scan → verify attendance |
| Automated reminders | Reduce no-shows | Medium | 7-day, night-before, day-of |
| Response deadline | Urgency for RSVPs | Low | Late RSVPs auto-managed |
| Email with calendar attachment | Convenience | Low | ICS for native calendar |
| Virtual event join button | Clear CTA for online events | Low | Prominent link in confirm email |

**Reference:** Who's In? sends 3-step reminder chain. RSVPify offers QR check-in. GatherGrove supports custom fields. Best practice sources emphasize "confirmation email with ICS" and "response deadlines" as modern expectations.

### Anti-Features

Features to explicitly NOT build now.

| Anti-Feature | Why Avoid | What to Do Instead |
|--------------|-----------|-------------------|
| Multi-attendee bulk RSVP | Complexity, edge cases | Individual RSVPs per member |
| Payment with RSVP | Out of scope | Defer to v2 |
| Group packages / discounts | Out of scope | Defer to v2 |
| Team roles (co-organizer, etc.) | Out of scope | Defer to v2 |

---

## Calendar Views

### Table Stakes

Standard in every modern calendar app.

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| Month view | Primary overview | Low | Standard calendar grid |
| Week view | Operational scheduling | Low | Hourly slots |
| Day view | Detailed single-day | Low | Hourly slots |
| Event display | Basic visualization | Low | Title + time on calendar |
| Navigation | Move between periods | Low | Prev/next month/week |
| Today indicator | Quick orientation | Low | Highlight current day |
| Basic responsiveness | Mobile-friendly | Low | Scale grid on smaller screens |

**Reference:** All Shadcn Calendar, MUI X Scheduler, Mantine Schedule — all provide month/week/day views as baseline. FullCalendar (existing in project) similarly.

### Differentiators

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| Year view | Long-range planning | Medium | Mini-calendars per month |
| Agenda / list view | Quick scan upcoming | Low | Chronological list |
| Drag-and-drop reschedule | Intuitive interface | Medium | Move events visually |
| Color coding | Visual categorization | Low | Events by category/club |
| Filtering by category | Focused view | Low | Show only certain types |
| Time format toggle 12/24 | User preference | Low | Settings-toggleable |
| Multi-day event display | Longer events | Medium | Events spanning days |
| All-day events | Without specific time | Low | Non-timed events |
| Double-click to navigate | Faster navigation | Low | e.g., double-click week → day |
| Today button | Quick return | Low | One-click to now |

**Reference:** Full-calendar v6+ supports year + agenda views. Shadcn Calendar offers multiple views. "Filter by color" is documented in Shadcn docs. Time toggle 12/24 appears in multiple implementations.

### Anti-Features

| Anti-Feature | Why Avoid | What to Do Instead |
|--------------|-----------|-------------------|
| Advanced resource views | Complex, likely unnecessary | Standard views sufficient |
| Complex overlapping algorithms | Performance edge cases | Basic stacking is fine |
| Heavy keyboard navigation | Accessibility complexity | Defer to v2 if needed |

---

## Recurring Events

### Table Stakes

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| Weekly recurrence | Club meetings | Low | "Every Monday" |
| Generate future instances | Create future events | Medium | Batch creation on save |
| Edit series | Change all occurrences | Medium | Modify pattern, update future |
| Basic end condition | Stop repeating | Low | After N times or forever |

**Reference:** Who's In? advertises "recurring events" as core feature. Calendar schema best practices confirm weekly is baseline pattern.

### Differentiators

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| Multiple frequency options | Flexibility | Medium | Daily, weekly, bi-weekly, monthly, yearly |
| Monthly by day-of-month | Fixed date each month | Medium | "15th of each month" |
| Monthly by week-of-month | e.g., "2nd Tuesday" | Medium | More complex patterns |
| Exception handling | Modify one instance | High | Change single occurrence |
| Cancel single instance | Skip one occurrence | High | Don't cancel entire series |
| Modify single instance | One-off change | High | Different time/location for one |
| Timezone support | Correct timing across DST | Medium | FullCalendar handles via Luxon |
| End date | Stop on specific date | Low | Until date picker |
| Occurrence limit | Stop after N times | Low | After N occurrences |

**Reference:** Database design research (Medium/OneUptime) emphasizes: recurring events require **exception handling** as critical differentiator. Hybrid approach (rule + exceptions) is recommended for production systems. iCalendar RRULE format is standard interchange format. "Sparse overrides" approach stores only changes vs. full materialization.

### Anti-Features

| Anti-Feature | Why Avoid | What to Do Instead |
|--------------|-----------|-------------------|
| Complex RRULE strings | Overengineering | Use simple column-based fields |
| Infinite instance materialization | Performance issue | Generate rolling window or on-demand |
| Calendar synchronization (iCal export) | Out of scope | Defer to v2 |

---

## Event Categories + Filtering

### Table Stakes

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| Category field on events | Basic organization | Low | Enum or related model |
| Filter by category | Focused view | Low | Dropdown/toggle filter |
| Category color coding | Visual identification | Low | Match calendar display |
| Search events by title | Find specific events | Low | Basic text search |

**Reference:** PROJECT.md explicitly calls for "event categories" and "filtering and search" as active requirements.

### Differentiators

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| Multi-category filtering | Complex needs | Medium | Show events matching A OR B |
| Date range filter | Find events in period | Medium | Date picker for start/end |
| Advanced combined filters | Power user feature | Medium | Category + date + search |

---

## Feature Dependencies

```
RSVP Capacity Limits → Attendee List
RSVP → Reminders (reminders require RSVP to know who to remind)
Recurring Events → Instancing (to display)
Recurring Events → Exception Handling (to support modifying single occurrences)
Calendar Views → Event Data (events must exist)
Event Categories → Calendar Color Coding
Event Categories → Filtering
```

---

## MVP Recommendation

Based on research, prioritize in this order:

### Phase 1: RSVP System (Table Stakes)

1. **RSVP yes/no response** — Core attendance tracking
2. **Capacity limits** — Prevent overcrowding
3. **Attendee count display** — Show X/Y spots filled
4. **Attendee list view** — See who's coming

**Defer:** Waitlists, plus-ones, QR check-in (defer to v2)

### Phase 2: Calendar Views (Table Stakes + Key Differentiators)

1. **Month view** — Primary overview
2. **Week view** — Operational scheduling
3. **Day view** — Detailed single-day
4. **Navigation + Today indicator**
5. **Color coding connected to categories**
6. **Filtering by category**

**Defer:** Year view, agenda view, drag-and-drop (defer to v2 unless high priority)

### Phase 3: Recurring Events (Table Stakes + Exception Handling)

1. **Weekly recurrence** — Club meetings common pattern
2. **Generate future instances** — Batch creation
3. **Edit series** — Change pattern
4. **Basic end condition** — Stop repeating

**Defer:** Monthly patterns, complex exceptions (more complex — validate need first)

---

## Sources

- **RSVP Best Practices:** Magnetiq blog (2025), GuestlistOnline (2026), Who's In? product pages (2026)
- **Calendar UI Patterns:** Shadcn Calendar docs, MUI X Scheduler, Mantine Schedule, FullCalendar v6
- **Recurring Events DB Design:** Medium (2024), OneUptime (2026), Thoughtbot (2020), CodeGenes (2025)
- **Club Management:** GatherGrove, Who's In? — competitive analysis

**Confidence:**

| Area | Level | Reason |
|------|-------|--------|
| RSVP features | HIGH | Multiple current sources (2025-2026) agree on table stakes vs differentiators |
| Calendar views | HIGH | Standard in all major libraries (FullCalendar, MUI, Mantine, Shadcn) |
| Recurring design | HIGH | Database schema best practices well-documented across multiple sources |