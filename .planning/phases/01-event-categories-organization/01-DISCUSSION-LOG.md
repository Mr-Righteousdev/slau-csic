# Phase 1: Event Categories & Organization - Discussion Log

> **Audit trail only.** Do not use as input to planning, research, or execution agents.
> Decisions are captured in CONTEXT.md — this log preserves the alternatives considered.

**Date:** 2026-04-25
**Phase:** 1-Event Categories & Organization
**Areas discussed:** Category storage, Filter UX, Search approach

---

## Category Storage

| Option | Description | Selected |
|--------|-------------|----------|
| Use existing 'type' field | Already has check constraint with workshop, competition, ctf, bootcamp, awareness_campaign, talk, social, hackathon | ✓ |
| Add new category field | Separate categories table for more flexibility | |
| Extend existing enum | Add more types and rename to category in UI | |

**User's choice:** Use existing 'type' field (Recommended)
**Notes:** The type field already exists with valid categories - no need to add new database columns

---

## Filter UX

| Option | Description | Selected |
|--------|-------------|----------|
| Dropdown filter | Single dropdown in event list header | |
| Sidebar filters | Sidebar with checkboxes for multiple categories | |
| Pill toggles | Clickable pill buttons to toggle filters | ✓ |

**User's choice:** Pill toggles
**Notes:** Visual and intuitive - users can see all category options at a glance

---

## Search Approach

| Option | Description | Selected |
|--------|-------------|----------|
| Full-text search | Search across title and description | ✓ |
| Quick filter only | Filter results in list only | |
| Dedicated search page | Open a separate search page | |

**User's choice:** Full-text search (Recommended)
**Notes:** Standard approach - users expect to search by title/description

---

## Agent's Discretion

- Specific color palette for each category type
- Whether search is debounced
- Exact placement of filter toggles in UI

## Deferred Ideas

None - discussion stayed within phase scope