# ROADMAP.md — v1.1 Question Bank Module

## Overview

**Milestone:** v1.1 Question Bank Module
**Goal:** Integrate standalone Question Bank module into SLAU CSIC app for creating, managing, and exporting questions
**Granularity:** Standard
**Phases:** 1

---

## Phases

- [ ] **Phase 6: Question Bank Module** - Complete question management with CRUD, types, and JSON export

---

## Phase Details

### Phase 6: Question Bank Module

**Goal:** Admin users can fully manage a question bank with multiple question types and JSON export capability

**Depends on:** Phase 5 (v1.0 last phase)

**Requirements:** QB-01, QB-02, QB-03, QB-04, QB-05, QT-01, QT-02, QT-03, QT-04, QT-05, EXP-01, EXP-02, EXP-03, PERM-01, PERM-02

**Success Criteria** (what must be TRUE):
1. Admin can view paginated list of all questions with type and marks display
2. Admin can create questions selecting from MCQ, True/False, Short Answer, Code Snippet types
3. Admin can edit any field of existing questions including type, options, correct answer, marks
4. Admin can soft-delete questions (moves to trash, can restore)
5. Admin can search questions by text content (title, body, options)
6. MCQ questions support 2-6 options with multiple correct answer selection
7. True/False questions have fixed 2 options (True, False)
8. Short Answer questions have no options (manual grading)
9. Code Snippet questions include code block textarea and language dropdown
10. Each question has editable marks/points field (integer)
11. Only admin role users can access question bank pages and operations
12. Non-admin users receive 403 error attempting to access any question bank route
13. Export generates valid JSON file with all question data and options
14. Exported JSON follows ExamShield import format schema

**Plans:** TBD

**UI hint:** yes

---

## Progress

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 6. Question Bank Module | 0/1 | Not started | - |

---

*Generated: 2026-04-27*
*Next: `/gsd-plan-phase 6`*