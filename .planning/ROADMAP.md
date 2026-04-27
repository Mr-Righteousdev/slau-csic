# ROADMAP.md — v1.1 Question Bank Module

## Overview

**Milestone:** v1.1 Question Bank Module
**Goal:** Integrate standalone Question Bank module into SLAU CSIC app for creating, managing, and exporting questions
**Granularity:** Standard
**Phases:** 1

---

## Phases

- [x] **Phase 6: Question Bank Module** - Complete question management with CRUD, types, and JSON export

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

**Plans:** 1 plan (COMPLETED)

Plans:
- [x] 06-01-PLAN.md — Full CRUD, multiple question types, export ✓

**UI hint:** yes

---

## Progress

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 6. Question Bank Module | 1/1 | Complete | 2026-04-27 |

### Phase 7: Gamification - points, badges, ranks, leaderboard

**Goal:** Members earn points from activities, badges unlock automatically on milestones, ranks auto-upgrade, leaderboard ranks members by total points. Admins can manually award points and manage badges.

**Depends on:** Phase 6

**Requirements:** GAM-01, GAM-02, GAM-03, GAM-04, GAM-05, GAM-06, GAM-07, BADGE-01, BADGE-02, BADGE-03, BADGE-04, RANK-01, RANK-02, RANK-03, LEADER-01, LEADER-02, LEADER-03

**Success Criteria** (what must be TRUE):
1. Users earn points from activities (events, CTF completions, teaching sessions)
2. Points transactions are logged in a ledger with reason and reference
3. Badges unlock automatically when criteria thresholds are met
4. Each badge has criteria_type, criteria_value, and points_bonus
5. Ranks upgrade automatically when point thresholds are reached (Silver: 200, Gold: 500, Platinum: 1000)
6. Leaderboard displays top 100 users sorted by total points
7. Leaderboard is cached for 5 minutes to avoid expensive queries
8. Admins can manually award points from the admin panel
9. Admins can view, create, edit, and delete badge definitions
10. Admins can configure rank thresholds
11. Members can view the public leaderboard page
12. Members can see their earned badges on their profile

**Plans:** 1 plan

Plans:
- [ ] 07-01-PLAN.md — Points ledger, badges, ranks, leaderboard foundation

### Phase 8: CTF Management - competitions, challenges, submissions, writeups, scoreboard

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 7
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 8 to break down)

### Phase 9: Exams & Assessments - exam creation, question bank, timed tests, AI grading, results

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 8
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 9 to break down)

### Phase 10: Learning & Training - learning paths, modules, lessons, progress tracking, resource library

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 9
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 10 to break down)

### Phase 11: Certificate System - auto-generated certificates, PDF generation, QR verification, public verification page

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 10
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 11 to break down)

### Phase 12: Executive Dashboard - club stats, member management, application queue, settings for points, ranks, badges, notifications

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 11
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 12 to break down)

### Phase 13: Research & Projects - members browse join projects, executives create manage, project timeline updates, public portfolio

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 12
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 13 to break down)

### Phase 14: Notifications & Announcements - in-app notifications, announcements with type and target, email SMTP, SMS Africa Talking

**Goal:** [To be planned]
**Requirements**: TBD
**Depends on:** Phase 13
**Plans:** 0 plans

Plans:
- [ ] TBD (run /gsd-plan-phase 14 to break down)

---

*Generated: 2026-04-27*
*Next: `/gsd-complete-milestone` — milestone complete*