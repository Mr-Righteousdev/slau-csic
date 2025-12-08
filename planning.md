ROLES & PERMISSIONS STRUCTURE
Roles (Using Spatie)

1. Super Admin
   - Full system access
   - Manage all settings
   - Assign/remove officers

2. President
   - All officer permissions
   - Approve major decisions
   - Financial oversight
   - Manage cabinet

3. Vice President (Technical)
   - Manage technical events
   - CTF competitions
   - Projects oversight
   - Learning resources

4. Vice President (Operations)
   - General events
   - Meeting scheduling
   - Member communications

5. Secretary
   - Meeting minutes
   - Attendance tracking
   - Document management
   - Announcements

6. Treasurer
   - Financial management
   - Budget tracking
   - Fine management
   - Expense approvals

7. Technical Lead
   - CTF challenges creation
   - Technical workshops
   - Mentor members

8. Event Coordinator
   - Create/manage events
   - Venue booking
   - Resource allocation

9. Member
   - Basic access
   - Register for events
   - Join projects
   - View resources

10. Alumni
    - Limited access
    - View-only
    - Cannot register for events

11. Faculty Advisor
    - Oversight access
    - Approve major activities
    - Financial review
    - Reports access

12. Guest/Prospective
    - Very limited access
    - Public events only
    - Cannot register

Permission Groups
php

// Members Management
- view_members
- create_members
- edit_members
- delete_members
- approve_members
- suspend_members

// Events Management
- view_events
- create_events
- edit_events
- delete_events
- approve_events
- register_for_events
- mark_attendance

// Financial Management
- view_finances
- create_transactions
- approve_expenses
- issue_fines
- waive_fines
- view_reports

// Meetings Management
- view_meetings
- create_meetings
- edit_meetings
- record_minutes
- mark_attendance

// Projects Management
- view_projects
- create_projects
- edit_projects
- join_projects
- approve_projects

// CTF Management
- view_ctf
- create_ctf
- manage_challenges
- submit_flags
- view_leaderboard

// Resources Management
- view_resources
- upload_resources
- edit_resources
- delete_resources

// Administrative
- manage_roles
- manage_settings
- view_analytics
- export_data
- manage_announcements
```

---

## **IMPLEMENTATION ORDER (16 PHASES)**

---

## **PHASE 1: Foundation & Authentication (Week 1)**

### **What to Build**
1. Fresh Laravel installation with TALL stack
2. Filament admin panel setup
3. Basic authentication system
4. User registration with email verification
5. Profile management

### **Deliverables**
- ✅ User registration form (Filament form)
- ✅ Login/Logout functionality
- ✅ Email verification
- ✅ Profile page (Livewire component)
- ✅ Password reset

### **Filament Components**
```
- Filament Form: User Registration
  * First Name, Last Name
  * Email (with domain validation @stlawu.edu)
  * Student ID
  * Major
  * Graduation Year
  * Password with confirmation

- Filament Form: Profile Edit
  * Bio (textarea)
  * Profile picture upload (Spatie Media Library)
  * Social links (GitHub, LinkedIn)
  * Skills (tags input)

- Filament Notification: 
  * Registration success
  * Email verification sent
  * Profile updated

Database Tables
sql

- users (extended with club fields)
- roles (Spatie)
- permissions (Spatie)
- model_has_roles (Spatie)
- model_has_permissions (Spatie)
- role_has_permissions (Spatie)
```

### **Watch Out For**
⚠️ **Email Domain Validation**: Only allow @stlawu.edu emails
⚠️ **Student ID Uniqueness**: Prevent duplicate registrations
⚠️ **Default Role Assignment**: Auto-assign "Member" role on registration
⚠️ **Email Queue Setup**: Configure queue driver for verification emails

---

## **PHASE 2: Roles & Permissions System (Week 1)**

### **What to Build**
1. Install Spatie Laravel Permission
2. Create all roles and permissions
3. Role assignment interface for admins
4. Permission gates and policies

### **Deliverables**
- ✅ Roles seeder with all 12 roles
- ✅ Permissions seeder with all permission groups
- ✅ Filament resource for role management
- ✅ Filament resource for user role assignment
- ✅ Middleware for role checking

### **Filament Components**
```
- Filament Table: Roles Management
  * Name
  * Permissions count
  * Users count
  * Actions (Edit, Delete)

- Filament Form: Assign Role to User
  * Select user (searchable select)
  * Select role (checkbox list with descriptions)
  * Effective date
  * Notes

- Filament Table: Users with Roles
  * Name, Email, Student ID
  * Current Role(s) badges
  * Member since
  * Status (active/suspended)
  * Quick actions (Edit role, Suspend)

- Filament Widget: Roles Overview
  * Pie chart of members by role
  * Total active members
  * Pending approvals
```

### **Watch Out For**
⚠️ **Role Hierarchy**: President can't remove Super Admin
⚠️ **Multiple Roles**: Some users might have multiple roles (e.g., Member + Technical Lead)
⚠️ **Permission Caching**: Remember to clear cache after permission changes
⚠️ **Seeder Order**: Run permissions before roles, roles before assignments

---

## **PHASE 3: Member Management System (Week 2)**

### **What to Build**
1. Member directory
2. Member approval workflow
3. Member status management (active, suspended, alumni)
4. Member statistics dashboard

### **Deliverables**
- ✅ Filament resource for members
- ✅ Member approval system
- ✅ Member directory (public-facing Livewire component)
- ✅ Member profile pages
- ✅ Activity tracking with Spatie Activity Log

### **Filament Components**
```
- Filament Table: Members Management
  * Columns: Photo, Name, Email, Student ID, Major, Year, Role, Status, Join Date
  * Filters: Role, Status, Graduation Year, Major
  * Bulk Actions: Approve, Suspend, Export
  * Global Search: Name, Email, Student ID
  * Custom Action: View Activity Log

- Filament Form: Member Approval
  * Member details (read-only)
  * Role assignment (select)
  * Approval notes (textarea)
  * Actions: Approve, Reject, Request More Info

- Filament Form: Suspend Member
  * Reason (select: Violation, Inactivity, Graduated, Other)
  * Suspension duration (date range or permanent)
  * Notes (textarea)
  * Send notification (toggle)

- Filament Widget: Member Statistics
  * Total members (stat)
  * Active members (stat)
  * Pending approvals (stat with action)
  * Growth chart (line chart - last 6 months)
  * Members by year (bar chart)
  * Members by major (donut chart)

- Filament Widget: Recent Activity
  * Last 10 member activities (list)
  * New registrations
  * Role changes
  * Suspensions
```

### **Livewire Components**
```
- MemberDirectory (public view)
  * Search and filter
  * Grid/List view toggle
  * Member cards with contact buttons
  * Pagination

- MemberProfile (public view)
  * Member info
  * Skills badges
  * Projects involved
  * Events attended
  * Achievements
  * Contact form (if allowed)
```

### **Watch Out For**
⚠️ **Privacy Settings**: Members should control what's visible in directory
⚠️ **Auto-suspension**: Alumni status after graduation year
⚠️ **Approval Workflow**: Email notifications at each stage
⚠️ **Activity Logging**: Log all member status changes for auditing
⚠️ **Bulk Operations**: Confirm before bulk suspend/delete

---

## **PHASE 4: Financial System - Core (Week 2-3)**

### **What to Build**
1. Budget tracking
2. Income/Expense management
3. Financial reports
4. Treasurer dashboard

### **Deliverables**
- ✅ Filament resource for transactions
- ✅ Budget categories
- ✅ Financial reports
- ✅ Treasurer dashboard

### **Filament Components**
```
- Filament Table: Transactions
  * Columns: Date, Type (Income/Expense), Category, Amount, Description, Receipt, Status, Created By
  * Filters: Date range, Type, Category, Status
  * Summary: Total income, Total expenses, Balance
  * Actions: Edit, Delete, View Receipt, Mark as Approved

- Filament Form: Create Transaction
  * Type (select: Income/Expense)
  * Category (select: depends on type)
    - Income: Membership dues, Donations, Sponsorships, Fundraising, Other
    - Expense: Events, Equipment, Prizes, Refreshments, Printing, Travel, Other
  * Amount (currency input)
  * Date (date picker)
  * Description (textarea)
  * Receipt (file upload - Spatie Media Library)
  * Paid to/from (text input)
  * Payment method (select: Cash, Check, Card, Transfer, Other)
  * Requires approval (toggle - auto-checked for amounts > $100)

- Filament Form: Budget Category
  * Name
  * Type (Income/Expense)
  * Allocated amount (for current semester)
  * Description

- Filament Widget: Financial Overview (Treasurer Dashboard)
  * Current balance (stat - prominent)
  * Income this month (stat with trend)
  * Expenses this month (stat with trend)
  * Pending approvals (stat with count badge)
  * Budget vs Actual chart (bar chart by category)
  * Recent transactions (table - last 10)
  * Spending trend (line chart - last 6 months)

- Filament Widget: Budget Status
  * Table showing each category with:
    - Allocated amount
    - Spent amount
    - Remaining amount
    - Progress bar
    - Alert if over-budget

- Filament Report: Financial Statement
  * Date range selector
  * Income breakdown by category
  * Expense breakdown by category
  * Net income/loss
  * Export to PDF/Excel

Database Tables
sql

- transactions
  * id, type, category, amount, date, description
  * receipt_path, paid_to_from, payment_method
  * status (pending, approved, rejected)
  * requires_approval, approved_by, approved_at
  * created_by, timestamps

- budget_categories
  * id, name, type, allocated_amount, semester
  * description, timestamps

- budget_allocations
  * id, category_id, amount, semester, academic_year
  * timestamps
```

### **Watch Out For**
⚠️ **Approval Workflow**: Expenses > $100 require President/Treasurer approval
⚠️ **Receipt Management**: Store receipts securely with Spatie Media Library
⚠️ **Budget Alerts**: Notify treasurer when category exceeds 80% of budget
⚠️ **Audit Trail**: Log all financial actions with Spatie Activity Log
⚠️ **Semester Rollover**: Archive old budgets when new semester starts
⚠️ **Currency Formatting**: Always display with $ and 2 decimals
⚠️ **Negative Balances**: Alert when club balance goes negative

---

## **PHASE 5: Fines & Penalties System (Week 3)**

### **What to Build**
1. Fine types and amounts
2. Issue fines to members
3. Fine payment tracking
4. Fine waiver/appeal system
5. Automated fine generation

### **Deliverables**
- ✅ Filament resource for fines
- ✅ Fine types management
- ✅ Payment tracking
- ✅ Member fine history
- ✅ Automated fine triggers

### **Filament Components**
```
- Filament Table: Fines Management
  * Columns: Member, Fine Type, Amount, Reason, Issue Date, Due Date, Status, Balance
  * Filters: Status (Pending, Paid, Waived, Overdue), Fine Type, Date range
  * Bulk Actions: Send reminder, Waive selected
  * Row Colors: Red for overdue, Yellow for due soon, Green for paid
  * Actions: Record payment, Waive, View history

- Filament Form: Issue Fine
  * Member (searchable select)
  * Fine type (select with predefined types or "Custom")
  * Amount (currency - auto-filled based on type)
  * Reason (textarea)
  * Due date (date picker - default 14 days)
  * Send notification (toggle - default ON)
  * Notes (textarea - internal only)

- Filament Form: Fine Types
  * Name (e.g., "Missed Meeting", "Late Project Submission")
  * Default amount
  * Description
  * Auto-apply rules (optional)
    - Trigger event (select)
    - Threshold (number)
  * Active (toggle)

- Filament Form: Record Payment
  * Amount paid (currency - can be partial)
  * Payment date (date picker)
  * Payment method (select)
  * Receipt number (text)
  * Notes (textarea)

- Filament Form: Waive Fine
  * Waiver reason (select: First offense, Special circumstances, Error, Other)
  * Explanation (textarea)
  * Partial waiver amount (optional)
  * Approved by (auto-filled)

- Filament Widget: Fines Overview
  * Total outstanding fines (stat)
  * Overdue fines count (stat - red badge)
  * Collected this month (stat)
  * Collection rate (stat - percentage)
  * Recent fines (table)

- Filament Table: Member Fine History (Relation Manager)
  * On Member resource
  * Shows all fines for that member
  * Total fines issued
  * Total paid
  * Outstanding balance
```

### **Livewire Component**
```
- MemberFinesDashboard
  * Shows member their own fines
  * Outstanding balance (prominent)
  * List of fines with status
  * Payment history
  * Appeal button for each fine

Database Tables
sql

- fine_types
  * id, name, default_amount, description
  * auto_apply_trigger, auto_apply_threshold
  * is_active, timestamps

- fines
  * id, user_id, fine_type_id, amount
  * reason, issue_date, due_date
  * status (pending, paid, partially_paid, waived, overdue)
  * amount_paid, balance
  * issued_by, waived_by, waived_reason
  * timestamps

- fine_payments
  * id, fine_id, amount, payment_date
  * payment_method, receipt_number
  * recorded_by, notes, timestamps

Automated Fine Rules
php

// Examples of auto-generated fines
1. Missed 3 consecutive meetings → $5 fine
2. No-show for registered event → $3 fine
3. Late project submission (if deadline set) → $2 fine
4. Destructive behavior in lab → $20 fine (manual)
5. Equipment damage → Variable (manual)
```

### **Watch Out For**
⚠️ **Grace Period**: Give 24-hour grace before marking event no-show
⚠️ **Notification System**: 
  - Send fine issued notification immediately
  - Reminder 3 days before due date
  - Overdue notification on due date
  - Weekly reminders for overdue fines
⚠️ **Partial Payments**: Track payment history, allow installments
⚠️ **Appeal Process**: Members can appeal within 7 days of issuance
⚠️ **Waiver Limits**: Treasurer can waive up to $10, President approval for higher
⚠️ **Collection Policy**: Member suspended if fines exceed $50 unpaid for 30+ days
⚠️ **Financial Integration**: Fines paid should create income transaction automatically
⚠️ **Graduation Clearance**: Must clear all fines before getting participation certificate

---

## **PHASE 6: Events Management System (Week 4)**

### **What to Build**
1. Event creation and management
2. Event registration system
3. Attendance tracking
4. Event calendar
5. Event feedback and ratings

### **Deliverables**
- ✅ Filament resource for events
- ✅ Event registration Livewire component
- ✅ Attendance tracking system
- ✅ Event calendar view
- ✅ Post-event feedback forms

### **Filament Components**
```
- Filament Table: Events Management
  * Columns: Title, Type, Date, Time, Location, Capacity, Registered, Status, Creator
  * Filters: Type, Date range, Status, Skill level
  * Actions: View, Edit, Delete, Duplicate, Mark Complete, Export Attendees
  * Bulk Actions: Cancel selected, Export to Calendar
  * Badges: "Full" (red), "Few spots" (yellow), "Open" (green)

- Filament Form: Create/Edit Event
  * Section 1: Basic Info
    - Title (text - required)
    - Description (rich text editor)
    - Event type (select: Workshop, Competition, Social, Meeting, Guest Speaker, Hackathon)
    - Cover image (file upload)
  
  * Section 2: Schedule
    - Start date & time (datetime picker)
    - End date & time (datetime picker)
    - Is recurring (toggle)
      - If yes: Recurrence pattern (select: Weekly, Biweekly, Monthly)
      - Recurrence end date
  
  * Section 3: Location & Capacity
    - Location (text or select from saved venues)
    - Room number (text)
    - Virtual link (URL - for hybrid events)
    - Capacity (number)
    - Allow waitlist (toggle)
  
  * Section 4: Requirements
    - Skill level (select: All, Beginner, Intermediate, Advanced)
    - Prerequisites (textarea)
    - Required equipment (textarea)
  
  * Section 5: Instructor/Presenter
    - Instructor (searchable select - members or external)
    - Co-instructors (multi-select)
    - Guest speaker details (if external)
  
  * Section 6: Resources
    - Materials/slides link (URL)
    - Additional resources (file upload - multiple)
    - Related resources (relation select)
  
  * Section 7: Registration Settings
    - Registration opens (datetime)
    - Registration closes (datetime)
    - Requires approval (toggle)
    - Allow guest registration (toggle)
    - No-show fine amount (currency)
  
  * Section 8: Points & Recognition
    - Experience points awarded (number)
    - Certificate provided (toggle)
    - Counts toward requirements (checkbox: Required workshops, CTF participation, etc.)
  
  * Section 9: Visibility
    - Is public (toggle - visible to non-members)
    - Featured event (toggle - shows on homepage)
    - Send announcement (toggle)

- Filament Widget: Events Dashboard
  * Upcoming events this week (stat with count)
  * Total registrations this month (stat)
  * Average attendance rate (stat)
  * Upcoming events calendar (mini calendar)
  * Today's events (list)
  * Events requiring attention (low registration, missing details)

- Filament Table: Event Registrations (Relation Manager)
  * On Event resource
  * Columns: Name, Email, Registration Date, Attended, Feedback Rating, Check-in Time
  * Actions: Mark attended, Send reminder, Remove registration
  * Bulk Actions: Mark all attended, Export list, Send bulk email
  * Quick Stats: Registered, Attended, No-shows, Attendance rate

- Filament Form: Check-in Interface
  * Simple interface for day-of-event
  * Search by name, email, or student ID
  * Quick scan QR code (optional)
  * Bulk check-in (select all present)
  * Add walk-in (if capacity allows)
  * Notes field for special cases

- Filament Form: Post-Event Management
  * Mark event as completed
  * Upload session recording (video link)
  * Upload materials/slides
  * Summary notes (textarea)
  * Award experience points (auto or manual adjust)
  * Issue certificates (bulk action)
  * Issue no-show fines (auto-suggested list)
```

### **Livewire Components**
```
- EventCalendar
  * Full calendar view (use Wire UI Calendar or Livewire Calendar)
  * Month, Week, Day views
  * Click to see event details
  * Filter by event type
  * Export to Google Calendar, iCal

- EventsList (Public)
  * Grid/List toggle
  * Upcoming events
  * Filter by type, skill level
  * Search events
  * Quick register button

- EventDetails (Public)
  * Full event information
  * Instructor bio
  * Registered members count (if public)
  * Related events
  * Register button
  * Share buttons
  * Add to calendar button

- EventRegistration
  * Registration form
  * Show remaining spots
  * Terms agreement
  * Emergency contact (optional)
  * Dietary restrictions (if food provided)
  * Submit button

- EventFeedback (Post-Event)
  * Star rating (1-5)
  * Multiple choice questions:
    - Content quality
    - Instructor effectiveness
    - Pace
    - Relevance
  * Open feedback (textarea)
  * Suggestions for improvement
  * Anonymous option (toggle)

- MyEvents (Member Dashboard)
  * Upcoming events I'm registered for
  * Past events I attended
  * Events I created/instructed
  * Pending feedback

Database Tables
sql

- events
  * id, title, description, event_type
  * start_time, end_time, location, room_number
  * virtual_link, capacity, allow_waitlist
  * skill_level, prerequisites, required_equipment
  * instructor_id, cover_image_path
  * materials_url, registration_opens, registration_closes
  * requires_approval, allow_guests, no_show_fine
  * experience_points, provides_certificate
  * is_public, is_featured
  * status (draft, scheduled, ongoing, completed, cancelled)
  * attendance_marked, session_recording_url
  * created_by, timestamps

- event_recurrence
  * id, event_id, pattern, interval
  * ends_at, timestamps

- event_registrations
  * id, event_id, user_id
  * registration_date, status (registered, waitlist, cancelled)
  * attended, check_in_time
  * emergency_contact, dietary_restrictions, notes
  * timestamps

- event_feedback
  * id, event_id, user_id
  * rating, content_quality, instructor_rating, pace_rating
  * feedback_text, suggestions, is_anonymous
  * timestamps

- event_instructors (for co-instructors)
  * id, event_id, user_id, role
  * timestamps

- event_resources
  * id, event_id, title, file_path, url
  * type (slide, code, document, video)
  * timestamps

Automated Processes
php

// Jobs/Scheduled Tasks
1. SendEventReminders
   - 24 hours before: "Don't forget!"
   - 1 hour before: "Starting soon!"
   - For recurring events: remind weekly

2. CheckNoShows
   - Run 1 hour after event ends
   - Mark no-shows
   - Optionally issue fines

3. RequestEventFeedback
   - Send 2 hours after event ends
   - Reminder after 3 days if not submitted

4. CloseEventRegistration
   - Auto-close when capacity reached
   - Auto-close at registration_closes time

5. ArchiveOldEvents
   - Mark as archived after 6 months
   - Keep for historical data
```

### **Watch Out For**
⚠️ **Timezone Handling**: Store all times in UTC, display in local
⚠️ **Capacity Management**: 
  - Don't allow over-registration
  - Waitlist moves up automatically when cancellation
  - Officers can override capacity (with reason)
⚠️ **QR Code Check-in**: Generate unique QR for each registration (optional feature)
⚠️ **No-show Policy**:
  - Cancel 24+ hours before: no penalty
  - Cancel < 24 hours: warning
  - No-show: fine + warning
  - 3 no-shows: temporary registration suspension
⚠️ **Recurring Events**: Handle with care - canceling one shouldn't cancel all
⚠️ **Guest Registration**: Collect more info (organization, purpose) for non-members
⚠️ **Hybrid Events**: Track both in-person and virtual attendees separately
⚠️ **Experience Points**: Double-check auto-award doesn't happen twice
⚠️ **Event Conflicts**: Warn when creating event that conflicts with another
⚠️ **Attendance Verification**: Require officers to confirm attendance marking
⚠️ **Privacy**: Don't show full attendee list publicly without permission

---

## **PHASE 7: Meetings Management System (Week 5)**

### **What to Build**
1. General meetings (all members)
2. Cabinet meetings (officers only)
3. Committee meetings (specific groups)
4. Meeting minutes/notes
5. Attendance tracking
6. Action items tracking

### **Deliverables**
- ✅ Filament resource for meetings
- ✅ Meeting types and agendas
- ✅ Minutes recording system
- ✅ Action items tracker
- ✅ Meeting attendance integration

### **Filament Components**
```
- Filament Table: Meetings Management
  * Columns: Title, Type, Date, Time, Location, Attendees, Minutes Status, Actions Due
  * Filters: Type, Date range, Minutes status
  * Actions: View, Edit, Record Minutes, Export Minutes, Mark Complete
  * Badges: "Minutes Pending" (red), "Actions Pending" (yellow), "Complete" (green)

- Filament Form: Create/Edit Meeting
  * Section 1: Basic Info
    - Meeting type (select: General, Cabinet, Committee, Emergency, Planning)
    - Title (text)
    - Description/Purpose (textarea)
  
  * Section 2: Schedule
    - Date (date picker)
    - Start time (time picker)
    - End time (time picker)
    - Is recurring (toggle)
      - Pattern (if recurring): Weekly, Biweekly, Monthly
      - Ends after (number of occurrences or date)
  
  * Section 3: Location & Access
    - Location type (select: In-person, Virtual, Hybrid)
    - Room/Venue (text)
    - Virtual link (URL - Zoom, Teams, etc.)
    - Virtual password (text)
  
  * Section 4: Participants
    - Required attendees (multi-select users by role)
      - All members (for general meetings)
      - Officers only (for cabinet)
      - Specific members
      - Specific committees
    - Optional attendees (multi-select)
    - Guest attendees (repeater: name, email, organization)
  
  * Section 5: Agenda
    - Agenda items (repeater)
      - Item title
      - Presenter
      - Duration (minutes)
      - Type (Discussion, Decision, Update, Other)
      - Attachments
    - Pre-reading materials (file upload)
  
  * Section 6: Settings
    - Attendance mandatory (toggle)
    - Absence fine amount (if mandatory)
    - RSVP required (toggle)
    - RSVP deadline (datetime)
    - Send calendar invite (toggle)
    - Record meeting (toggle)

- Filament Form: Record Minutes
  * Meeting info (read-only display at top)
  * Attendees present (checklist)
  * Attendees absent (with reasons)
  * Late arrivals/Early departures
  
  * For each agenda item:
    - Discussion summary (rich text)
    - Decisions made (repeater)
    - Action items created (repeater)
      - Task description
      - Assigned to
      - Due date
      - Priority
  
  * Additional notes (rich text)
  * Next meeting date
  * Adjournment time
  
  * Recorded by (auto-filled)
  * Approved by (select - typically President/Secretary)
  * Minutes status (Draft, Under Review, Approved, Published)

- Filament Form: Quick Attendance Check-in
  * Simplified interface for meeting day
  * List of expected attendees
  * Checkboxes for present
  * Quick add for unexpected attendees
  * Time of arrival tracking
  * Bulk actions (Mark all present)

- Filament Table: Action Items Tracker
  * Global table accessible from sidebar
  * Columns: Task, Meeting, Assigned To, Due Date, Priority, Status, Created Date
  * Filters: Status, Priority, Assigned to, Meeting, Due date
  * Bulk Actions: Mark complete, Reassign, Extend deadline
  * Row colors: Overdue (red), Due soon (yellow), Completed (green)
  * Actions: Edit, Mark complete, Add follow-up note

- Filament Widget: Meetings Dashboard
  * Next meeting countdown (stat)
  * Meetings this month (stat)
  * Action items pending (stat - clickable)
  * Average attendance rate (stat)
  * Upcoming meetings (list with RSVP status)
  * Overdue action items (list)
  * Minutes pending approval (list)

- Filament Widget: Meeting Analytics
  * Attendance trends (line chart)
  * Meeting frequency by type (pie chart)
  * Action item completion rate (stat)
  * Average meeting duration (stat)
  * Top contributors (members with most actions completed)

- Filament Table: Meeting Minutes (Relation Manager)
  * On Meeting resource
  * Shows formatted minutes
  * Downloadable as PDF
  * Approval workflow tracking
  * Version history (if edited)
  * Comments from reviewers
```

### **Livewire Components**
```
- MeetingCalendar
  * Shows all scheduled meetings
  * Filter by meeting type
  * RSVP directly from calendar
  * Sync to personal calendar

- UpcomingMeetings (Member View)
  * Meetings I'm invited to
  * RSVP status
  * Agenda preview
  * Add to calendar button
  * Virtual meeting link (if applicable)

- MeetingMinutesViewer (Member View)
  * Browse past meeting minutes
  * Search minutes
  * Filter by date/type
  * Download minutes
  * Comment on published minutes (if allowed)

- MyActionItems (Member Dashboard)
  * Tasks assigned to me from meetings
  * Grouped by: Overdue, This Week, Later
  * Progress tracking
  * Mark complete
  * Request extension

- MeetingRSVP
  * Simple RSVP form
  * Attending/Not Attending/Maybe
  * Reason if not attending
  * Add to calendar option

Database Tables
sql

- meetings
  * id, meeting_type, title, description
  * date, start_time, end_time
  * location_type, venue, virtual_link, virtual_password
  * attendance_mandatory, absence_fine
  * rsvp_required, rsvp_deadline
  * send_calendar_invite, record_meeting
  * is_recurring, recurrence_pattern, recurrence_ends
  * status (scheduled, in_progress, completed, cancelled)
  * recording_url, created_by, timestamps

- meeting_participants
  * id, meeting_id, user_id
  * participant_type (required, optional, guest)
  * role_type (for role-based invites: all_members, officers, committee_name)

Continue
sql

- meeting_attendance
  * id, meeting_id, user_id
  * status (present, absent, late, excused)
  * arrival_time, departure_time
  * absence_reason, fine_issued, fine_id
  * timestamps

- meeting_agenda_items
  * id, meeting_id
  * title, presenter_id, duration_minutes
  * item_type (discussion, decision, update, other)
  * order_number, attachments_path
  * timestamps

- meeting_minutes
  * id, meeting_id
  * attendees_present (json), attendees_absent (json)
  * late_arrivals (json), early_departures (json)
  * discussion_notes (json - per agenda item)
  * decisions_made (json)
  * additional_notes (text)
  * next_meeting_date, adjournment_time
  * recorded_by, approved_by
  * status (draft, under_review, approved, published)
  * version_number, timestamps

- meeting_action_items
  * id, meeting_id, meeting_minutes_id
  * task_description, assigned_to_user_id
  * due_date, priority (low, medium, high, urgent)
  * status (pending, in_progress, completed, overdue, cancelled)
  * completion_date, completion_notes
  * follow_up_notes (json)
  * created_by, timestamps

- meeting_rsvps
  * id, meeting_id, user_id
  * response (attending, not_attending, maybe)
  * reason, responded_at, timestamps

- meeting_guests
  * id, meeting_id
  * name, email, organization, purpose
  * timestamps

Automated Processes
php

// Jobs/Scheduled Tasks

1. SendMeetingInvitations
   - Send 1 week before meeting
   - Include agenda, virtual links
   - Request RSVP if required

2. SendMeetingReminders
   - 48 hours before: First reminder
   - 24 hours before: Second reminder + RSVP deadline warning
   - 1 hour before: Final reminder with virtual link
   - 15 minutes before: "Meeting starting soon"

3. CheckMeetingAttendance
   - Run after meeting ends
   - Issue fines for mandatory meetings
   - Send "Sorry we missed you" to absent members
   - Update attendance statistics

4. RemindActionItems
   - 3 days before due: First reminder
   - 1 day before due: Urgent reminder
   - On due date: "Due today" notification
   - 1 day after: Overdue notification to assignee + meeting chair

5. MinutesApprovalReminder
   - Remind Secretary to submit minutes (48 hours after meeting)
   - Remind approver to review (72 hours after submission)

6. CreateRecurringMeetings
   - Generate next occurrence 1 week before
   - Copy agenda template
   - Send invitations
```

### **Meeting Types Explained**

**1. General Meetings**
- All members invited
- Monthly or as needed
- Club-wide updates, elections, major decisions
- Minutes published to all members
- Mandatory attendance (with exceptions)

**2. Cabinet Meetings**
- Officers only
- Biweekly or weekly
- Strategic planning, policy decisions
- Minutes shared with officers, summary to members
- Mandatory for cabinet members

**3. Committee Meetings**
- Specific teams (Events Committee, Technical Committee, etc.)
- As needed
- Project planning, task coordination
- Minutes to committee members
- Optional attendance unless specified

**4. Emergency Meetings**
- Called by President for urgent matters
- All relevant parties
- Short notice
- Decision-focused

**5. Planning Meetings**
- Semester planning, event planning
- Relevant coordinators and volunteers
- Brainstorming sessions
- Informal minutes

### **Watch Out For**
⚠️ **Meeting Scheduling Conflicts**: 
  - Check for conflicts with other meetings
  - Check for conflicts with major campus events
  - Warn if scheduling during exam periods

⚠️ **Quorum Requirements**:
  - Define minimum attendance for valid decisions
  - Cabinet: 2/3 of officers
  - General: 1/2 of active members
  - Warn if RSVP count below quorum

⚠️ **Minutes Approval Workflow**:
  - Secretary records → Draft
  - President reviews → Under Review
  - President approves → Approved
  - Auto-publish after approval → Published
  - Version control if edited after publishing

⚠️ **Absence Management**:
  - Allow members to mark absence in advance (excused)
  - Require reason for excused absence
  - Officers review absence requests
  - Only fine unexcused absences
  - Maximum 2 excused absences per semester

⚠️ **Action Item Tracking**:
  - Don't let action items fall through cracks
  - Escalate to President if overdue > 1 week
  - Review incomplete items in next meeting
  - Track completion rate per person (performance metric)

⚠️ **Virtual Meeting Management**:
  - Auto-generate unique Zoom/Teams links
  - Store passwords securely
  - Only share links with invited participants
  - Track virtual attendance separately

⚠️ **Guest Management**:
  - Collect guest info in advance
  - Send limited agenda (exclude confidential items)
  - Mark certain agenda items as "Executive Session" (no guests)
  - Follow up with guest afterward

⚠️ **Recording Consent**:
  - Get consent before recording
  - Store recordings securely
  - Auto-delete after semester (unless historical importance)
  - Limit access to attendees only

⚠️ **Time Management**:
  - Set time limits per agenda item
  - Alert when time limit exceeded
  - Automatically suggest extending or tabling discussion

---

## **PHASE 8: Projects Management System (Week 6)**

### **What to Build**
1. Project proposals and approval
2. Project team formation
3. Project progress tracking
4. Project milestones and deliverables
5. Project showcase/portfolio

### **Deliverables**
- ✅ Filament resource for projects
- ✅ Project proposal workflow
- ✅ Team management system
- ✅ Progress tracking tools
- ✅ Project showcase page

### **Filament Components**
```
- Filament Table: Projects Management
  * Columns: Title, Status, Team Size, Progress %, Tech Stack, Started, Last Updated
  * Filters: Status, Looking for members, Tech stack, Project type
  * Actions: View, Edit, Approve (if pending), Archive, Clone
  * Badges: Status colors (Planning-blue, Active-green, Completed-purple, Archived-gray)
  * Custom column: "Health score" (based on activity, progress)
  * Bulk Actions: Archive selected, Export project list

- Filament Form: Project Proposal
  * Section 1: Project Overview
    - Project title (text - required)
    - Tagline (short description - 100 chars)
    - Detailed description (rich text)
    - Project type (select: Web App, Mobile App, Tool, Research, Hardware, Other)
    - Category (select: Security Tool, Educational, CTF Practice, Club Management, Other)
    - Cover image (file upload)
  
  * Section 2: Technical Details
    - Tech stack (tags input: Python, JavaScript, React, Laravel, etc.)
    - Required skills (tags input)
    - Difficulty level (select: Beginner-friendly, Intermediate, Advanced)
    - Estimated duration (select: < 1 month, 1-3 months, 3-6 months, 6+ months)
    - Prerequisites (textarea)
  
  * Section 3: Team Structure
    - Minimum team size (number)
    - Maximum team size (number)
    - Current team size (auto-calculated)
    - Looking for roles (repeater)
      - Role name (e.g., Backend Dev, UI Designer, Security Analyst)
      - Number needed
      - Required skills
      - Description
    - Is recruiting (toggle)
  
  * Section 4: Goals & Objectives
    - Project goals (repeater: goal description)
    - Expected outcomes (textarea)
    - Learning objectives (textarea)
    - Success criteria (repeater)
  
  * Section 5: Timeline & Milestones
    - Start date (date picker)
    - Expected end date (date picker)
    - Milestones (repeater)
      - Milestone title
      - Description
      - Due date
      - Deliverables
      - Status (Not started, In progress, Completed)
  
  * Section 6: Resources & Repository
    - GitHub repository URL (URL)
    - Project documentation link (URL)
    - Figma/Design files (URL)
    - Communication channel (select: Slack, Discord, Teams)
    - Channel link/invite (URL)
    - Additional resources (file upload - multiple)
  
  * Section 7: Collaboration
    - Meeting schedule (text: e.g., "Every Wednesday 7 PM")
    - Communication expectations (textarea)
    - Contribution guidelines (textarea)
  
  * Section 8: Approval & Recognition
    - Requires faculty advisor approval (toggle)
    - Faculty advisor (select user with role 'advisor')
    - Credits for resume/portfolio (toggle)
    - Certificate upon completion (toggle)
    - Experience points awarded (number)
  
  * Status (hidden field: auto-set based on workflow)
    - Pending Approval
    - Approved
    - Active
    - Completed
    - On Hold
    - Cancelled
    - Archived

- Filament Form: Join Project Team
  * Project details (read-only summary)
  * Apply for role (select from available roles)
  * Why do you want to join? (textarea)
  * Relevant skills (tags input)
  * Relevant experience (textarea)
  * Availability (select: 2-5 hrs/week, 5-10 hrs/week, 10+ hrs/week)
  * GitHub profile (URL)
  * Portfolio link (URL - optional)

- Filament Table: Project Team Members (Relation Manager)
  * On Project resource
  * Columns: Member, Role, Join Date, Contribution Level, Status
  * Actions: Change role, Remove from team, View contributions
  * Custom column: Activity score (based on commits, updates, attendance)
  * Add member button (for project lead)

- Filament Form: Update Project Progress
  * Current progress percentage (slider 0-100%)
  * Recent accomplishments (textarea)
  * Challenges faced (textarea)
  * Next steps (textarea)
  * Screenshots/demos (file upload)
  * Update milestones status
  * Team notes (visible to team only)

- Filament Form: Complete Milestone
  * Milestone name (read-only)
  * Completion date (date picker - default today)
  * Deliverables submitted (file upload)
  * Summary of work (textarea)
  * Lessons learned (textarea)
  * Next milestone preview (read-only)

- Filament Widget: Projects Dashboard
  * Active projects (stat)
  * Projects seeking members (stat - clickable)
  * Completed this semester (stat)
  * Average completion rate (stat)
  * Project health overview (list with traffic light indicators)
  * Recently updated projects (list)
  * Projects needing attention (stale, behind schedule)

- Filament Widget: My Projects Widget (Member Dashboard)
  * Projects I'm leading
  * Projects I'm contributing to
  * Pending project applications
  * Available projects matching my skills

- Filament Form: Project Review (Upon Completion)
  * Final project presentation (file upload or link)
  * Project report (file upload)
  * Final demo (video link)
  * Achievements vs. goals (comparison)
  * Team performance evaluation (internal)
  * Publish to showcase (toggle)
  * Award certificates (toggle)
  * Calculate experience points (auto or manual)

- Filament Table: Project Applications (Relation Manager)
  * For each project
  * Columns: Applicant, Role Applied For, Date, Skills, Status
  * Actions: Approve, Reject, Request Interview
  * Filters: Status, Role
```

### **Livewire Components**
```
- ProjectShowcase (Public)
  * Grid of featured/completed projects
  * Filter by tech stack, category
  * Search projects
  * Project cards with images, tech stack badges
  * View details link

- ProjectDetails (Public)
  * Full project information
  * Team members
  * Tech stack and skills used
  * Screenshots/demos
  * GitHub stats (stars, forks, commits)
  * Related projects
  * Join button (if recruiting)

- ProjectBoard (Project Team View)
  * Kanban-style task board
  * Columns: To Do, In Progress, Review, Done
  * Drag-and-drop tasks
  * Assign tasks to team members
  * Due dates and priorities
  * Activity feed

- ProjectApplicationForm
  * User-friendly application
  * Show project requirements
  * Upload portfolio samples
  * Commitment acknowledgment

- MyProjects (Member Dashboard)
  * Projects I'm part of
  * My role and responsibilities
  * Upcoming project meetings
  * My tasks due soon
  * Quick access to project resources

- ProjectTimeline
  * Visual timeline of milestones
  * Completed milestones (checkmarks)
  * Current milestone (highlighted)
  * Upcoming milestones
  * Overall progress bar

Database Tables
sql

- projects
  * id, title, tagline, description
  * project_type, category, cover_image_path
  * tech_stack (json), required_skills (json)
  * difficulty_level, estimated_duration
  * prerequisites, goals (json), expected_outcomes
  * learning_objectives, success_criteria (json)
  * min_team_size, max_team_size
  * is_recruiting, start_date, expected_end_date
  * actual_end_date, progress_percentage
  * github_url, documentation_url, design_files_url
  * communication_channel, channel_link
  * meeting_schedule, contribution_guidelines
  * requires_advisor_approval, faculty_advisor_id
  * provides_certificate, experience_points
  * status (pending, approved, active, on_hold, completed, cancelled, archived)
  * health_score (calculated field)
  * created_by, approved_by, approved_at
  * timestamps, completed_at

- project_team_members
  * id, project_id, user_id
  * role, join_date, status (active, inactive, left)
  * contribution_level (calculated)
  * activity_score (calculated)
  * responsibilities (text)
  * timestamps

- project_roles
  * id, project_id
  * role_name, number_needed, required_skills (json)
  * description, is_filled
  * timestamps

- project_milestones
  * id, project_id
  * title, description, due_date
  * deliverables (json)
  * status (not_started, in_progress, completed, delayed)
  * completed_date, order_number
  * timestamps

- project_applications
  * id, project_id, user_id, role_id
  * motivation (text), relevant_skills (json)
  * relevant_experience (text)
  * availability, github_profile, portfolio_url
  * status (pending, approved, rejected, withdrawn)
  * reviewed_by, reviewed_at, review_notes
  * timestamps

- project_updates
  * id, project_id, user_id
  * progress_percentage, accomplishments (text)
  * challenges (text), next_steps (text)
  * update_type (progress, milestone, issue, demo)
  * attachments_path, is_public
  * timestamps

- project_tasks
  * id, project_id, milestone_id
  * title, description, assigned_to_user_id
  * status (todo, in_progress, review, done)
  * priority (low, medium, high)
  * due_date, completed_date
  * order_number, column (for kanban)
  * timestamps

- project_resources
  * id, project_id
  * title, description, file_path, url
  * resource_type (document, code, design, other)
  * uploaded_by, timestamps

Project Health Score Calculation
php

// Auto-calculated score (0-100)
Health Score = (
    Recent Activity (30%) +
    Progress vs Timeline (30%) +
    Team Engagement (20%) +
    Milestone Completion (20%)
)

Recent Activity:
  - Last update within 7 days: 30 points
  - Last update 7-14 days: 20 points
  - Last update 14-30 days: 10 points
  - No update 30+ days: 0 points

Progress vs Timeline:
  - On track or ahead: 30 points
  - Slightly behind (<10%): 20 points
  - Behind (10-25%): 10 points
  - Significantly behind (>25%): 0 points

Team Engagement:
  - All members active: 20 points
  - Most members active (>75%): 15 points
  - Some members active (50-75%): 10 points
  - Low engagement (<50%): 5 points

Milestone Completion:
  - On schedule: 20 points
  - 1 milestone delayed: 15 points
  - 2+ milestones delayed: 5 points

Health Indicators:
  - 80-100: Healthy (Green)
  - 60-79: Needs Attention (Yellow)
  - 0-59: At Risk (Red)

Automated Processes
php

// Jobs/Scheduled Tasks

1. CalculateProjectHealth
   - Run daily
   - Update health scores
   - Alert project leads if health drops
   - Notify VP Technical for at-risk projects

2. SendProjectReminders
   - Weekly summary to project teams
   - Milestone deadline reminders (3 days before)
   - Overdue milestone alerts
   - Inactive project warnings (no update in 14 days)

3. ReviewProjectApplications
   - Remind project lead to review applications (48 hours)
   - Auto-notify applicants of decision
   - Suggest similar projects if rejected

4. UpdateGitHubStats
   - Fetch commit counts, stars, forks
   - Update project activity scores
   - Weekly for active projects
   - Monthly for completed projects

5. ArchiveInactiveProjects
   - Mark as inactive if no activity for 60 days
   - Send warning at 45 days
   - Allow project lead to reactivate

6. AwardProjectCompletion
   - Generate certificates for team members
   - Award experience points
   - Add to member portfolio
   - Send completion survey
```

### **Watch Out For**
⚠️ **Project Approval Workflow**:
  - Pending → Review by VP Technical → Approved/Rejected
  - Complex projects need faculty advisor approval
  - Rejection should include feedback and improvement suggestions

⚠️ **Team Dynamics**:
  - Require project lead (creator is default)
  - Lead can reassign roles
  - Handle member removal gracefully (give notice)
  - Track contribution to prevent freeloading

⚠️ **Intellectual Property**:
  - Clarify ownership in contribution guidelines
  - Club projects = open source by default
  - Member can retain IP with prior approval

⚠️ **Resource Conflicts**:
  - Don't let too many projects compete for same members
  - Limit active projects per member (max 3)
  - Warn if applying to competing projects

⚠️ **Progress Tracking**:
  - Require weekly updates for active projects
  - Send reminder if no update in 10 days
  - Auto-calculate progress from milestone completion
  - Manual override allowed for leads

⚠️ **Milestone Management**:
  - Make milestones SMART (Specific, Measurable, Achievable, Relevant, Time-bound)
  - Allow milestone date adjustments (with reason)
  - Celebrate milestone completions (announce in club)

⚠️ **Project Completion Criteria**:
  - All milestones complete
  - Final demo/presentation given
  - Documentation complete
  - Code pushed to GitHub
  - Team signs off on completion

⚠️ **Recruiting**:
  - Clear skill requirements
  - Set application deadlines
  - Interview process for competitive projects
  - Trial period (2 weeks) before full commitment

⚠️ **GitHub Integration**:
  - Use GitHub webhooks to track commits
  - Validate GitHub URLs on save
  - Display contribution graphs
  - Link commits to project updates

⚠️ **Privacy & Showcase**:
  - Projects are internal by default
  - Opt-in for public showcase
  - Completed projects reviewed before publishing
  - Showcase best work only (quality over quantity)

---

## **PHASE 9: CTF Competition System (Week 7)**

### **What to Build**
1. CTF competition creation and management
2. Challenge creation with categories
3. Team formation and management
4. Flag submission system
5. Live leaderboard
6. Hints and scoring system

### **Deliverables**
- ✅ Filament resource for CTF competitions
- ✅ Challenge management system
- ✅ Team creation and registration
- ✅ Flag submission Livewire component
- ✅ Real-time leaderboard
- ✅ CTF analytics and write-ups

### **Filament Components**
```
- Filament Table: CTF Competitions
  * Columns: Name, Status, Start Date, End Date, Teams, Challenges, Top Team
  * Filters: Status, Date range, Competition type
  * Actions: View, Edit, Clone, Export Results, Generate Report
  * Badges: Status (Upcoming-blue, Live-green, Completed-gray)
  * Custom column: "Participation rate"

- Filament Form: Create CTF Competition
  * Section 1: Basic Information
    - Competition name (text - required)
    - Description (rich text)
    - Competition type (select: Jeopardy, Attack-Defense, King of the Hill, Mixed)
    - Cover image/banner (file upload)
    - Difficulty level (select: Beginner, Intermediate, Advanced, Mixed)
  
  * Section 2: Schedule
    - Start date & time (datetime picker)
    - End date & time (datetime picker)
    - Duration (auto-calculated, or custom for special formats)
    - Timezone (select - default to school timezone)
  
  * Section 3: Team Settings
    - Is team-based (toggle - if off, individual competition)
    - Minimum team size (number - default 1)
    - Maximum team size (number - default 4)
    - Allow team changes after start (toggle)
    - Allow solo participants in team competition (toggle)
  
  * Section 4: Registration
    - Registration opens (datetime)
    - Registration closes (datetime)
    - Maximum teams (number - null for unlimited)
    - Requires approval (toggle)
    - Allow guest participants (toggle - external competitors)
    - Registration fee (currency - optional for major competitions)
  
  * Section 5: Scoring & Rules
    - Scoring type (select: Static, Dynamic, Custom)
    - First blood bonus (number - extra points for first solve)
    - Decay function (for dynamic scoring - formula)
    - Tie-breaker (select: First submission time, Total time, Manual review)
    - Hints system (toggle)
    - Hint penalty (number - points deducted per hint)
    - Max hints per challenge (number)
  
  * Section 6: Challenges
    - Import challenges from previous CTF (toggle)
    - Challenge categories enabled (multi-select: Web, Crypto, Forensics, Pwn, Reverse, OSINT, Misc)
    - Total challenges planned (number)
    - Total points available (auto-calculated or manual)
  
  * Section 7: Prizes & Recognition
    - Prizes (repeater)
      - Place (1st, 2nd, 3rd)
      - Prize description
      - Value (if monetary)
    - Experience points awarded (number)
    - Certificates (toggle)
    - Write-up submission (toggle - encourage learning)
  
  * Section 8: Visibility & Access
    - Is public (toggle - visible to non-members)
    - Allow spectators (toggle - can view but not participate)
    - Live leaderboard visibility (select: Public, Participants only, Hidden until end)
    - Challenge visibility (select: All visible, Progressive unlock, Hidden)
  
  * Section 9: Platform Settings
    - Challenge infrastructure (text - e.g., AWS instances, Docker containers)
    - Flag format (text - regex, e.g., SLAU{.*})
    - Case sensitive flags (toggle)
    - Rate limiting (number - submissions per minute)
    - Collaboration rules (textarea)
  
  * Status (auto-set based on dates)

- Filament Form: Create/Edit Challenge
  * Section 1: Challenge Details
    - Title (text - required)
    - Description (rich text - the challenge prompt)
    - Category (select: Web, Crypto, Forensics, Pwn, Reverse, OSINT, Misc)
    - Difficulty (select: Easy, Medium, Hard)
    - Points (number - static or starting value for dynamic)
    - Author (select user - default to creator)
  
  * Section 2: Challenge Files & Resources
    - Challenge files (file upload - multiple)
      - Downloadable for participants
    - Deployment files (file upload - multiple)
      - For infrastructure setup (not visible to participants)
    - Challenge URL (text - if challenge is hosted)
    - Connection info (textarea - e.g., nc server.com 1337)
  
  * Section 3: Flag & Validation
    - Flag (text - encrypted in database)
    - Flag format (text - e.g., SLAU{...}, regex validation)
    - Flag is case-sensitive (toggle)
    - Multiple valid flags (repeater - for variations)
    - Custom validation script (code editor - advanced)
  
  * Section 4: Hints System
    - Hints enabled (toggle)
    - Hints (repeater)
      - Hint text
      - Cost (points deducted)
      - Unlock condition (optional - e.g., after 30 mins, after X solves)
      - Order (which hint comes first)
  
  * Section 5: Scoring Configuration
    - Minimum points (for dynamic scoring)
    - Decay formula (for dynamic - adjust based on solves)
    - First blood bonus (points)
  
  * Section 6: Challenge Metadata
    - Tags (tags input - for categorization)
    - Recommended tools (tags input)
    - Learning objectives (textarea)
    - Intended solve time (number - minutes)
    - Prerequisites (textarea - what participants should know)
  
  * Section 7: Visibility & Unlocking
    - Visible from start (toggle)
    - Unlock after (number of other challenges solved)
    - Unlock at (specific time)
    - Max solves (optional - for limited flag challenges)
  
  * Section 8: Write-up & Solution
    - Official solution (rich text - hidden from participants)
    - Solution files (file upload)
    - Intended solution steps (textarea)
    - Alternative solutions (textarea)
    - Common mistakes (textarea)
  
  * Status (Draft, Testing, Active, Solved, Archived)

- Filament Table: Challenges (Relation Manager on CTF)
  * Columns: Title, Category, Difficulty, Points, Solves, First Blood, Status
  * Actions: Edit, Test, Clone, View Submissions, Disable
  * Custom columns:
    - Solve rate (percentage)
    - Average solve time
    - Health indicator (if broken, too easy/hard)
  * Filters: Category, Difficulty, Status, Solve count
  * Bulk Actions: Activate, Deactivate, Adjust points

- Filament Form: Create/Join CTF Team
  * Team name (text - unique per competition)
  * Team description/motto (textarea)
  * Team avatar (file upload)
  * Captain (auto-set to creator, can transfer later)
  * Team members (repeater or relation)
    - Search and add members
    - Each member accepts invite
  * Preferred categories (multi-select - for matchmaking)
  * Experience level (select: Beginner, Intermediate, Advanced, Mixed)

- Filament Table: CTF Teams (Relation Manager on CTF)
  * Columns: Rank, Team Name, Members Count, Score, Challenges Solved, Last Submission
  * Filters: By score range, team size, status
  * Actions: View details, Disqualify (if cheating), Export team data
  * Real-time updates (using Livewire polling or Reverb)

- Filament Table: Flag Submissions (Relation Manager on Challenge)
  * Columns: Team/User, Flag Submitted, Timestamp, Result, Points Awarded, IP Address
  * Filters: Result (Correct, Incorrect), Date range, Team
  * Actions: Review submission, Manual override (for disputes)
  * Statistics:
    - Total attempts
    - Unique teams attempted
    - Success rate
    - Average attempts before solve

- Filament Widget: CTF Dashboard (Admin View)
  * Active CTF info (card with countdown)
  * Live statistics:
    - Total participants
    - Challenges solved
    - Current leader
    - Active submissions (realtime)
  * Challenge health (list)
    - Challenges with no solves (may be broken or too hard)
    - Challenges with 100% solve (may be too easy)
    - Challenges with unusual submission patterns
  * System status
    - Infrastructure health
    - Rate limiting status
    - Flag validation errors

- Filament Widget: CTF Analytics (Post-Competition)
  * Participation metrics
  * Challenge difficulty analysis (actual vs intended)
  * Solve timeline (chart showing when each challenge was solved)
  * Category popularity
  * Top performers
  * Write-up submissions

- Filament Form: CTF Competition Report
  * Executive summary (auto-generated + editable)
  * Participation statistics
  * Challenge breakdown
  * Winners and prizes
  * Notable performances
  * Lessons learned
  * Recommendations for next CTF
  * Export as PDF
```

### **Livewire Components**
```
- CTFList (Public)
  * Upcoming CTFs
  * Registration status
  * Quick stats (teams, challenges, prizes)
  * Register button

- CTFCompetitionPage (Participant View)
  * Competition info and countdown
  * Team management (if team-based)
  * Challenges list with statuses
    - Solved (green check)
    - Attempted (yellow dot)
    - Unsolved (gray)
  * My team's score and rank
  * Leaderboard (if visible)
  * Announcements

- ChallengePage (Participant View)
  * Challenge description
  * Downloads section
  * Connection info
  * Hints section (with cost display)
  * Flag submission form
  * Submission history (my team's attempts)
  * Solve statistics (if allowed)

- FlagSubmissionWidget
  * Text input for flag
  * Submit button
  * Real-time validation
  * Success/error feedback
  * Points awarded display
  * Rate limiting indicator

- CTFLeaderboard (Live Updates)
  * Real-time rankings
  * Team scores and progress
  * Last solve timestamp
  * Rank changes indicator (↑↓)
  * Filterable by category
  * Freezeable (lock before competition end)
  * Auto-refresh every 10 seconds

- TeamDashboard (Participant View)
  * Team members and roles
  * Our challenges solved
  * Our rank and score
  * Activity feed (who solved what)
  * Team chat/notes
  * Strategy planner

- ChallengeProgress (Participant View)
  * Visual category breakdown
  * Challenges by difficulty
  * My/Our progress bars
  * Recommended next challenges

- HintModal
  * Hint description
  * Cost in points
  * Confirm/Cancel buttons
  * Show previously unlocked hints
  * Hint content (after unlock)

- CTFWriteUpSubmission (Post-Competition)
  * Challenge selection
  * Write-up editor (markdown)
  * Code snippets
  * Screenshots upload
  * Submit for review
  * Make public option

- CTFResults (Public View)
  * Final leaderboard
  * Winners and prizes
  * Challenge statistics
  * Highlight moments
  * Write-ups (if published)

Database Tables
sql

- ctf_competitions
  * id, name, description, competition_type
  * cover_image_path, difficulty_level
  * start_time, end_time, duration_minutes, timezone
  * is_team_based, min_team_size, max_team_size
  * allow_team_changes, allow_solo_participants
  * registration_opens, registration_closes
  * max_teams, requires_approval, allow_guests
  * registration_fee, scoring_type
  * first_blood_bonus, decay_function
  * tie_breaker, hints_enabled, hint_penalty, max_hints_per_challenge
  * prizes (json), experience_points, provides_certificates
  * write_up

Continue

_submission_enabled

    is_public, allow_spectators, leaderboard_visibility
    challenge_visibility, challenge_infrastructure
    flag_format_regex, flags_case_sensitive
    rate_limit_per_minute, collaboration_rules
    status (draft, upcoming, live, completed, archived)
    created_by, timestamps

    ctf_challenges
        id, ctf_competition_id
        title, description, category, difficulty, points
        min_points (for dynamic), author_id
        flag_encrypted, flag_format, is_case_sensitive
        additional_flags (json - alternative valid flags)
        validation_script (text - custom validation)
        hints_enabled, hints (json array)
        decay_formula, first_blood_bonus
        tags (json), recommended_tools (json)
        learning_objectives, intended_solve_time_minutes
        prerequisites, official_solution, solution_files_path
        intended_steps, alternative_solutions, common_mistakes
        visible_from_start, unlock_after_challenges_count
        unlock_at_time, max_solves
        challenge_url, connection_info
        status (draft, testing, active, solved, archived)
        solve_count, first_blood_team_id, first_blood_time
        average_solve_time, timestamps
    ctf_challenge_files
        id, challenge_id
        filename, file_path, file_type
        is_downloadable (for participants)
        file_size, timestamps
    ctf_teams
        id, ctf_competition_id
        name, description, avatar_path
        captain_id, total_score, challenges_solved_count
        rank, last_submission_time
        preferred_categories (json), experience_level
        status (pending, approved, active, disqualified)
        disqualification_reason
        created_at, timestamps
    ctf_team_members
        id, team_id, user_id
        role (captain, member)
        join_date, invitation_status (pending, accepted, declined)
        contribution_score (calculated)
        timestamps
    ctf_submissions
        id, challenge_id, team_id, user_id
        flag_submitted, is_correct, points_awarded
        submission_time, ip_address
        hint_penalty_applied, total_hints_used
        timestamps
    ctf_hint_unlocks
        id, challenge_id, team_id
        hint_index (which hint: 0, 1, 2...)
        unlocked_at, points_deducted
        timestamps
    ctf_challenge_solves
        id, challenge_id, team_id, user_id
        solve_time, points_awarded
        is_first_blood, solve_duration_minutes
        hints_used_count, timestamps
    ctf_write_ups
        id, ctf_competition_id, challenge_id
        team_id, user_id
        title, content (markdown)
        code_snippets (json), screenshots_path
        status (draft, submitted, approved, published)
        is_public, reviewed_by, reviewed_at
        likes_count, views_count
        timestamps


### **Real-Time Features (Laravel Reverb)**
```php
// Broadcast Events

1. LeaderboardUpdated
   - When a team solves a challenge
   - Updates all participants' leaderboards
   - Shows rank changes animation

2. ChallengeSolved
   - Broadcast to all participants
   - "Team X just solved Challenge Y!"
   - First blood announcements (special animation)

3. HintUnlocked
   - Notify team members when hint is unlocked
   - Update team's hint usage

4. TeamScoreUpdated
   - Real-time score changes
   - Update team dashboard

5. CTFAnnouncementBroadcast
   - Admin sends urgent announcements
   - Infrastructure issues, hint releases, etc.

6. CTFStatusChanged
   - Competition started/ended
   - Leaderboard frozen
   - Extensions announced
```

### **Scoring Systems Explained**

**1. Static Scoring**

Each challenge has fixed points (e.g., 100, 250, 500)
First solve: +first_blood_bonus
Simple, predictable


**2. Dynamic Scoring**

Points decrease as more teams solve
Formula: current_points = max_points - (solves * decay_rate)
But never below min_points
Rewards early solvers
Makes easier challenges less valuable over time


**3. Custom Scoring**

Admin defines custom formula per challenge
Can account for:

    Time taken
    Hints used
    Number of attempts
    Challenge dependencies


### **Automated Processes**
```php
// Jobs/Scheduled Tasks

1. StartCTFCompetition
   - Run at competition start time
   - Set status to 'live'
   - Activate all challenges (or first batch)
   - Send "CTF started!" notifications
   - Start leaderboard updates

2. EndCTFCompetition
   - Run at competition end time
   - Set status to 'completed'
   - Freeze leaderboard
   - Stop accepting submissions
   - Calculate final rankings
   - Award prizes and certificates
   - Send competition report
   - Request write-ups

3. UpdateDynamicScoring
   - Recalculate challenge points after each solve
   - Update leaderboard
   - Broadcast changes

4. CheckChallengeHealth
   - Run every 30 minutes during CTF
   - Alert if challenge has:
     - No solves after 25% of competition time
     - 100% solve rate (too easy)
     - Unusual submission patterns
     - Infrastructure issues

5. ProcessFlagSubmission
   - Validate flag format
   - Check against correct flag(s)
   - Apply hints penalty
   - Award points
   - Update leaderboard
   - Broadcast updates
   - Log submission

6. UnlockProgressiveChallenges
   - Check if unlock conditions met
   - Reveal next tier of challenges
   - Notify eligible teams

7. SendCTFReminders
   - 24 hours before: Registration closing soon
   - 1 hour before start: Get ready!
   - During CTF: Periodic updates on standings
   - 1 hour before end: Final push!

8. GenerateCTFReport
   - Run after competition ends
   - Compile statistics
   - Create charts and graphs
   - Export to PDF
   - Email to organizers and faculty advisor
```

### **Watch Out For**

⚠️ **Security & Cheating Prevention**:
  - Rate limit flag submissions (prevent brute force)
  - Log IP addresses
  - Monitor for flag sharing (same wrong flags from different teams)
  - Randomize flag formats where possible
  - Encrypt flags in database
  - Monitor for coordinated submissions
  - Disallow VPN/proxy if needed

⚠️ **Infrastructure Management**:
  - Use Docker for challenge isolation
  - Auto-restart crashed containers
  - Monitor server resources
  - Have backup instances
  - Test all challenges before competition
  - Prepare for DDoS attempts on challenge servers

⚠️ **Flag Validation**:
  - Trim whitespace from submissions
  - Handle case sensitivity correctly
  - Support multiple valid flags (if intentional)
  - Clear error messages ("Incorrect flag" vs "Invalid format")

⚠️ **Leaderboard Management**:
  - Consider freeze time (last hour hidden to increase suspense)
  - Handle ties correctly
  - Cache leaderboard (don't recalculate on every page load)
  - Show rank changes with animations

⚠️ **Hint System**:
  - Make hints progressively helpful
  - Price hints appropriately (20-30% of challenge points)
  - Track hint usage for difficulty assessment
  - Allow admin to push free hints if challenge is too hard

⚠️ **Team Dynamics**:
  - Allow team captain to transfer role
  - Handle member leaving mid-competition
  - Prevent team-hopping to game the system
  - Track individual contributions within team

⚠️ **Time Management**:
  - All times in UTC, display in local timezone
  - Handle time extensions gracefully
  - Warn participants about time zones
  - Clear countdown timers

⚠️ **Challenge Difficulty Calibration**:
  - Post-competition analysis
  - If 0% solve rate → too hard or broken
  - If 100% solve rate in first 10 mins → too easy
  - Target: 70% solve for easy, 40% for medium, 10% for hard

⚠️ **Write-ups**:
  - Encourage but don't require
  - Review before publishing (no spoilers for future CTFs)
  - Feature best write-ups
  - Use as learning resource

⚠️ **Guest Participants**:
  - Separate leaderboard or marked differently
  - Limited access to club resources
  - Cannot win club-specific prizes
  - Still award participation points

⚠️ **Accessibility**:
  - Provide challenge descriptions in plain text
  - Colorblind-friendly difficulty badges
  - Keyboard navigation for all features
  - Screen reader compatible

---

## **PHASE 10: Learning Resources Library (Week 8)**

### **What to Build**
1. Resource upload and categorization
2. Learning paths/tracks
3. Resource ratings and reviews
4. Bookmarking system
5. Progress tracking
6. Resource recommendations

### **Deliverables**
- ✅ Filament resource for learning materials
- ✅ Category and tag management
- ✅ Learning paths system
- ✅ Resource library (public-facing)
- ✅ Personal learning dashboard

### **Filament Components**

    Filament Table: Learning Resources
        Columns: Title, Type, Category, Skill Level, Rating, Views, Downloads, Uploaded By, Date
        Filters: Type, Category, Skill level, Tags, Upload date
        Actions: View, Edit, Delete, Feature, Generate shareable link
        Bulk Actions: Categorize, Change visibility, Export list
        Custom columns:
            Engagement score (views + downloads + ratings)
            Completion rate (for video courses)
    Filament Form: Upload Resource
        Section 1: Basic Information
            Title (text - required)
            Description (rich text)
            Resource type (select: PDF, Video, Article Link, Tool, Code Repository, Presentation, Course, Book, Cheat Sheet)
            Cover/thumbnail image (file upload)
        Section 2: Content
            For Files (PDF, presentations, etc.):
                File upload (with size limit: 50MB)
                Preview available (toggle)
            For Videos:
                Video URL (YouTube, Vimeo, etc.)
                Or upload video file
                Duration (auto-detected or manual)
                Subtitles file (optional)
            For Links:
                External URL
                Source/Publisher
            For Tools:
                Download link or GitHub repo
                Installation instructions (textarea)
                Supported OS (checkboxes: Windows, Mac, Linux)
        Section 3: Categorization
            Primary category (select: Web Security, Network Security, Cryptography, Forensics, Reverse Engineering, Secure Coding, Tools & Techniques, Certifications, Career Development, General IT)
            Subcategory (dynamic based on primary)
            Tags (tags input - unlimited)
            Topics covered (textarea or tags)
        Section 4: Difficulty & Prerequisites
            Skill level (select: Beginner, Intermediate, Advanced, Expert)
            Prerequisites (textarea or multi-select from existing resources)
            Recommended background (textarea)
            Estimated time to complete (number - minutes/hours)
        Section 5: Learning Objectives
            What will students learn? (repeater: objective text)
            Skills gained (tags input)
            Certifications prepared for (multi-select: CEH, Security+, OSCP, etc.)
        Section 6: Metadata
            Author/Creator (text)
            Publication date (date)
            Last updated (date)
            Version (text - for tools/courses)
            Language (select - default English)
            Is official documentation (toggle)
        Section 7: Access & Visibility
            Visibility (select: Public, Members Only, Officers Only, Specific Roles)
            Requires completion of (select other resources)
            Password protected (toggle + password field)
            Available from (date - for timed releases)
            Available until (date - for temporary resources)
        Section 8: Engagement Features
            Allow comments (toggle)
            Allow ratings (toggle)
            Track progress (toggle - for multi-part resources)
            Award experience points (number - upon completion)
            Certificate upon completion (toggle)
        Section 9: Related Resources
            Related resources (multi-select)
            Next recommended resource (select)
            Alternative resources (multi-select)
            Part of learning path (select path)
        Is featured (toggle - shows on homepage)
        Is official club resource (toggle)
        Status (draft, published, archived)
    Filament Table: Learning Paths
        Columns: Path Name, Resources Count, Difficulty, Enrolled, Completion Rate, Status
        Filters: Difficulty, Status, Category
        Actions: View, Edit, Clone, Publish, Export
        Custom column: Average completion time
    Filament Form: Create Learning Path
        Path name (text)
        Description (rich text)
        Path type (select: Beginner Track, Specialization, Certification Prep, Tool Mastery)
        Cover image (file upload)
        Difficulty level (select)
        Estimated duration (number - hours)
        Prerequisites (textarea)
        Learning outcomes (repeater)
        Resources in path (relation - ordered list)
            Drag to reorder
            Mark as required/optional
            Set unlock conditions
        Badge awarded (file upload - badge image)
        Experience points (number)
        Is featured (toggle)
    Filament Widget: Resources Dashboard
        Total resources (stat)
        Resources added this month (stat)
        Most downloaded (stat with resource name)
        Average rating (stat)
        Resources by category (donut chart)
        Recently added (list)
        Pending reviews (list)
        Popular this week (list)
    Filament Table: Resource Analytics (Relation Manager)
        On Resource
        Views over time (line chart)
        Downloads over time (line chart)
        Ratings distribution (bar chart)
        Completion rate (if trackable)
        Top viewers/users
        Referral sources
    Filament Form: Review Resource Submission
        Resource details (read-only)
        Quality check (checkboxes)
            Content is relevant
            No copyright issues
            Appropriate for audience
            Properly categorized
            Meets quality standards
        Feedback to uploader (textarea)
        Actions: Approve, Request changes, Reject
    Filament Table: User Resource Progress (Relation Manager)
        On User resource
        Columns: Resource, Type, Progress %, Status, Last Accessed, Completed Date
        Filters: Status, Type, Category
        Shows learning activity
    Filament Widget: Resource Recommendations Engine
        Based on:
            User's skill level
            Resources completed
            Bookmarked resources
            Current projects
            Club focus areas
        Displays top 5 recommended resources


### **Livewire Components**

    ResourceLibrary (Public Facing)
        Search bar with filters
        Category sidebar
        Grid/List view toggle
        Sort by: Newest, Popular, Rating, Title
        Resource cards with:
            Thumbnail
            Title
            Type badge
            Skill level badge
            Rating stars
            Quick actions (Bookmark, View, Download)
        Pagination
    ResourceDetailPage
        Full resource information
        Preview/Read online (if applicable)
        Download button
        Related resources sidebar
        Comments section
        Rating interface
        Progress tracker (if applicable)
        Learning path indication
        Share buttons
    ResourceViewer
        PDF viewer (for PDFs)
        Video player (for videos)
        Code viewer (for code snippets)
        Markdown renderer (for articles)
        Progress tracking
        Bookmark points
        Note-taking sidebar
    MyLearning (Member Dashboard)
        Current learning paths
        Resources in progress
        Bookmarked resources
        Completed resources
        Recommended for me
        Learning statistics
            Hours learned
            Resources completed
            Paths completed
            Skills gained
    LearningPathPage
        Path overview
        Progress visualization
        List of resources with status
            Locked (prerequisites not met)
            Available
            In progress
            Completed
        Enroll button
        Completion certificate preview
    ResourceCommentsSection
        Add comment
        Reply to comments
        Like comments
        Sort by: Newest, Popular
        Flag inappropriate comments
    ResourceRatingWidget
        Star rating (1-5)
        Written review (optional)
        Helpful/not helpful votes
        Report review
    ResourceSearch
        Advanced search with filters
        Auto-suggestions
        Search history
        Save searches
    BookmarksManager
        All bookmarked resources
        Create folders/collections
        Tags for organization
        Export bookmarks


### **Database Tables**
```sql
- learning_resources
  * id, title, description, resource_type
  * cover_image_path, file_path, external_url
  * video_url, video_duration_minutes
  * primary_category, subcategory, tags (json)
  * topics_covered (json), skill_level
  * prerequisites (json or relation), recommended_background
  * estimated_time_minutes, learning_objectives (json)
  * skills_gained (json), certifications_prepared (json)
  * author_creator, publication_date, last_updated_date
  * version, language, is_official_documentation
  * visibility, password_hash
  * available_from, available_until
  * allow_comments, allow_ratings, track_progress
  * experience_points, provides_certificate
  * related_resources (json or relation)
  * next_recommended_resource_id
  * learning_path_id
  * is_featured, is_official_club_resource
  * view_count, download_count, rating_avg, rating_count
  * engagement_score (calculated)
  * status (draft, published, archived)
  * uploaded_by, reviewed_by, reviewed_at
  * timestamps

- resource_categories
  * id, name, slug, parent_id
  * description, icon, order_number
  * timestamps

- learning_paths
  * id, name, description, path_type
  * cover_image_path, difficulty_level
  * estimated_duration_hours, prerequisites
  * learning_outcomes (json)
  * badge_image_path, experience_points
  * is_featured, enrollment_count
  * completion_count, average_completion_time_hours
  * status (draft, published, archived)
  * created_by, timestamps

- learning_path_resources
  * id, learning_path_id, resource_id
  * order_number, is_required
  * unlock_condition (text - e.g., "complete previous 2")
  * timestamps

- user_resource_progress
  * id, user_id, resource_id
  * status (not_started, in_progress, completed)
  * progress_percentage
  * time_spent_minutes
  * last_accessed_at
  * completed_at, notes (text)
  * bookmarked_at
  * timestamps

- user_learning_paths
  * id, user_id, learning_path_id
  * enrolled_at, started_at
  * progress_percentage
  * completed_at, certificate_issued
  * time_spent_hours
  * timestamps

- resource_bookmarks
  * id, user_id, resource_id
  * folder_name, tags (json)
  * notes, created_at

- resource_ratings
  * id, resource_id, user_id
  * rating (1-5), review_text
  * helpful_count, not_helpful_count
  * is_verified_completion (completed resource before rating)
  * status (published, flagged, removed)
  * timestamps

- resource_comments
  * id, resource_id, user_id
  * parent_comment_id (for replies)
  * comment_text, likes_count
  * is_flagged, flagged_reason
  * timestamps

- resource_views
  * id, resource_id, user_id
  * viewed_at, ip_address
  * referrer_url
  * timestamps (for analytics)

- resource_downloads
  * id, resource_id, user_id
  * downloaded_at, ip_address
  * timestamps

- resource_completions
  * id, user_id, resource_id
  * completed_at, time_taken_minutes
  * quiz_score (if applicable)
  * certificate_issued
  * timestamps
```

### **Learning Path Examples**

    Beginner Track: "Cybersecurity Fundamentals"
        Introduction to Cybersecurity
        Networking Basics
        Linux Command Line
        Basic Cryptography
        Web Security Basics
    Specialization: "Web Application Pentesting"
        HTTP Protocol Deep Dive
        OWASP Top 10
        SQL Injection Techniques
        XSS and CSRF
        Authentication Bypass
        Web Security Tools (Burp Suite)
    Certification Prep: "CompTIA Security+ Preparation"
        All topics aligned with exam objectives
        Practice questions
        Study guides
    Tool Mastery: "Mastering Metasploit"
        Installation and setup
        Basic exploitation
        Post-exploitation
        Writing custom modules
        Real-world scenarios
    Career Track: "From Student to Security Professional"
        Resume building
        Interview prep
        Networking tips
        Portfolio projects
        First job expectations


### **Automated Processes**
```php
// Jobs/Scheduled Tasks

1. UpdateResourceEngagement
   - Run daily
   - Calculate engagement scores
   - Update trending resources
   - Generate recommendations

2. SendLearningReminders
   - Weekly: "Continue your learning path"
   - For stale in-progress resources (no activity in 14 days)
   - Path completion reminders

3. GenerateLearningCertificates
   - When path/resource completed
   - Create PDF certificate
   - Email to user
   - Add to profile

4. ArchiveOutdatedResources
   - Flag resources not accessed in 12 months
   - Alert uploader
   - Suggest update or archival

5. ProcessResourceRecommendations
   - Run weekly per user
   - ML-based or rule-based
   - Consider: completed resources, skill level, interests
   - Update recommendations cache

6. ModerateComments
   - Flag suspicious comments
   - Check for spam
   - Alert moderators for review

7. GenerateResourceReports
   - Monthly usage reports
   - Popular resources
   - Gaps in library
   - Suggested acquisitions
```

### **Watch Out For**

⚠️ **Copyright & Licensing**:
  - Only upload resources you have rights to share
  - Add license information (CC, MIT, etc.)
  - Link to external resources when possible
  - Respect DMCA takedown requests
  - Clearly mark third-party content

⚠️ **File Size & Storage**:
  - Set maximum file sizes (50MB for PDFs, 500MB for videos)
  - Use cloud storage (S3) for large files
  - Compress videos before upload
  - Consider CDN for popular resources

⚠️ **Quality Control**:
  - Review resources before publishing
  - Check for accuracy
  - Ensure appropriate skill level tagging
  - Remove outdated/deprecated content
  - Encourage community flagging of issues

⚠️ **Progress Tracking Accuracy**:
  - Videos: track watch time
  - PDFs: track page views (if viewer used)
  - External links: manual completion
  - Quizzes: auto-mark complete on passing

⚠️ **Learning Path Design**:
  - Logical progression
  - Not too long (max 15 resources)
  - Mix theory and practice
  - Include assessments
  - Clear completion criteria

⚠️ **Resource Discovery**:
  - Strong search functionality
  - Good categorization
  - Personalized recommendations
  - Featured resources rotation
  - "New arrivals" section

⚠️ **Engagement**:
  - Gamification (points, badges)
  - Social features (share completions)
  - Leaderboards (most resources completed)
  - Recognition for contributors

⚠️ **Accessibility**:
  - Provide transcripts for videos
  - Alt text for images
  - PDF accessibility
  - Mobile-friendly viewers

⚠️ **Privacy**:
  - Progress is private by default
  - Option to share achievements
  - Anonymous ratings allowed
  - Don't expose learning gaps publicly

⚠️ **External Links**:
  - Check links periodically (broken link checker)
  - Archive important external content
  - Warn when leaving site

Would you like me to continue with Phase 11 (Announcements & Communications System)?

yes please
PHASE 11: Announcements & Communications System (Week 9)
What to Build

    Club-wide announcements
    Targeted messaging by role/group
    Email notifications system
    In-app notification center
    Emergency alerts
    Communication preferences

Deliverables

    ✅ Filament resource for announcements
    ✅ Multi-channel notification system
    ✅ Notification preferences management
    ✅ Announcement templates
    ✅ Communication analytics

Filament Components

- Filament Table: Announcements Management
  * Columns: Title, Type, Target Audience, Status, Sent Date, Views, Clicks, Created By
  * Filters: Type, Status, Date range, Audience, Priority
  * Actions: View, Edit, Send now, Schedule, Duplicate, Archive, View analytics
  * Bulk Actions: Archive, Delete drafts
  * Custom columns:
    - Engagement rate (opens/views)
    - Click-through rate (for links)
  * Row colors: Draft (gray), Scheduled (blue), Sent (green), Failed (red)

- Filament Form: Create Announcement
  * Section 1: Announcement Details
    - Title (text - required)
    - Type (select: General, Event, Urgent, Meeting, Achievement, Reminder, Policy Update, Opportunity)
    - Priority (select: Low, Normal, High, Critical)
      - Critical: Red banner, push notification, email immediately
      - High: Yellow highlight, email within 1 hour
      - Normal: Standard notification
      - Low: Digest only
    - Content (rich text editor)
      - Bold, italic, lists
      - Hyperlinks
      - Images (inline)
      - Mention users (@username)
      - Embed videos/links
    - Banner image (file upload - optional)
    - Call-to-action button (optional)
      - Button text
      - Button link
      - Button style (primary, secondary, success, danger)
  
  * Section 2: Target Audience
    - Send to (radio buttons):
      - All members
      - Specific roles (multi-select: Officers, Members, Alumni, Advisors)
      - Specific graduation years (multi-select)
      - Specific majors (multi-select)
      - Custom user selection (searchable multi-select)
      - Event registrants (select event)
      - Project team members (select project)
      - CTF team members (select CTF)
      - Learning path enrollees (select path)
    - Exclude users (multi-select - optional)
    - Estimated recipients (auto-calculated display)
    - Preview audience list (expandable table)
  
  * Section 3: Delivery Channels
    - In-app notification (toggle - default ON)
      - Show banner on login (toggle)
      - Mark as read required (toggle)
    - Email (toggle)
      - Email subject (text)
      - Email preview text (text)
      - Use HTML email template (select template)
    - SMS (toggle - for critical only)
      - SMS message (textarea - 160 char limit)
    - Push notification (toggle - if mobile app exists)
      - Push title
      - Push body
    - Post to club website (toggle)
    - Post to social media (toggle)
      - Platforms (checkboxes: Facebook, Twitter/X, Instagram, LinkedIn)
      - Auto-post or draft (radio)
  
  * Section 4: Scheduling & Delivery
    - Send timing (radio):
      - Send immediately upon publish
      - Schedule for specific date/time (datetime picker)
      - Send as part of daily digest (time picker)
    - Timezone (select - default to school timezone)
    - Repeat announcement (toggle - for recurring reminders)
      - Frequency (select: Daily, Weekly, Monthly)
      - Repeat until (date picker)
    - Expiration date (date picker - optional)
      - Auto-archive after this date
      - Hide from announcement board
  
  * Section 5: Engagement Tracking
    - Track opens/views (toggle - default ON)
    - Track link clicks (toggle - default ON)
    - Require acknowledgment (toggle)
      - "I have read this" checkbox
      - Track who acknowledged
    - Allow comments (toggle)
    - Allow reactions (toggle: 👍 ❤️ 😮 🎉 😕)
  
  * Section 6: Attachments & Resources
    - Attachments (file upload - multiple)
      - Max 10MB per file
      - Common formats: PDF, DOCX, XLSX, images
    - Related resources (multi-select from library)
    - Related events (multi-select)
    - Related projects (multi-select)
  
  * Section 7: Templates & Formatting
    - Use template (select from saved templates)
    - Save as template (toggle + template name)
    - Preview (button to see how it will look)
      - Desktop preview
      - Mobile preview
      - Email preview
  
  * Section 8: Permissions & Review
    - Requires approval (toggle - for certain senders)
      - Approval workflow (select approver)
    - Allow edits after sending (toggle - with versioning)
    - Mark as official (toggle - adds "Official Club Communication" badge)
  
  * Status (auto-managed)
    - Draft
    - Pending Approval
    - Scheduled
    - Sending
    - Sent
    - Failed
    - Archived

- Filament Form: Announcement Templates
  * Template name (text)
  * Template type (select: Event Announcement, Meeting Reminder, Achievement, Policy, Emergency)
  * Subject line template (text with variables)
  * Content template (rich text with variables)
    - Variables: {{member_name}}, {{event_title}}, {{event_date}}, etc.
  * Default channels (checkboxes)
  * Default priority (select)
  * Preview with sample data

- Filament Widget: Communications Dashboard
  * Announcements sent this month (stat)
  * Average open rate (stat)
  * Average click rate (stat)
  * Pending approvals (stat - clickable)
  * Recent announcements (list with engagement)
  * Engagement trends (line chart - last 30 days)
  * Channel effectiveness (bar chart)
    - Email open rate
    - In-app view rate
    - Push notification click rate
  * Best time to send (based on historical engagement)

- Filament Table: Announcement Analytics (Relation Manager)
  * On Announcement resource
  * Delivery statistics:
    - Total recipients
    - Delivered successfully
    - Failed deliveries (with reasons)
  * Engagement metrics:
    - Opens/views (count + percentage)
    - Unique opens
    - Link clicks (per link)
    - Acknowledgments (if required)
    - Comments count
    - Reactions breakdown
  * Timeline chart (opens over time)
  * Geographic distribution (if available)
  * Device breakdown (desktop, mobile, app)
  * Export analytics report

- Filament Form: Bulk Messaging
  * Quick message interface for urgent communications
  * Simplified form:
    - Recipients (role-based quick select)
    - Subject
    - Message
    - Send button
  * Pre-populated with emergency templates

- Filament Table: Notification Logs
  * All notifications sent (system-wide)
  * Columns: Type, Recipient, Channel, Status, Sent At, Opened At, Action Taken
  * Filters: Status, Channel, Date range, Recipient, Type
  * Search by recipient or content
  * Actions: Resend failed, View details
  * Bulk Actions: Retry failed notifications

- Filament Widget: Unread Announcements Alert
  * For officers/admins
  * Shows members with most unread announcements
  * Send reminder button

Livewire Components

- AnnouncementBoard (Member View)
  * List of announcements (newest first)
  * Filter by type
  * Mark as read/unread
  * Archive announcements
  * Search announcements
  * Sticky announcements (pinned at top)
  * Announcement cards with:
    - Priority badge
    - Type icon
    - Date posted
    - Unread indicator (blue dot)
    - Quick actions (React, Comment, Share)

- AnnouncementDetailModal
  * Full announcement content
  * Reactions interface
  * Comments section
  * Attachments download
  * Related content links
  * Mark as read automatically on open
  * Acknowledgment checkbox (if required)

- NotificationCenter
  * Bell icon with unread count badge
  * Dropdown panel with recent notifications
  * Grouped by type:
    - Announcements
    - Events
    - Projects
    - CTF updates
    - Mentions
    - System notifications
  * Mark all as read button
  * Settings link
  * "View all" link to full notification page

- NotificationsList (Full Page)
  * All notifications
  * Filter by type, date, read/unread
  * Bulk mark as read
  * Bulk archive
  * Clear all (with confirmation)
  * Pagination

- NotificationPreferences (User Settings)
  * Channel preferences per notification type:
    - Email (ON/OFF + frequency: Immediate, Digest, Off)
    - In-app (ON/OFF)
    - Push (ON/OFF)
    - SMS (ON/OFF - if available)
  * Notification types matrix:

Type                    Email   In-App  Push    SMS
───────────────────────────────────────────────────
Announcements           Daily   ON      OFF     OFF
Event reminders         ON      ON      ON      OFF
Meeting reminders       ON      ON      ON      OFF
CTF updates            OFF      ON      ON      OFF
Project updates        OFF      ON      OFF     OFF
Mentions               ON      ON      ON      OFF
Direct messages        ON      ON      ON      OFF
System alerts          ON      ON      ON      ON

  * Quiet hours (time range when no push/SMS)
  * Digest schedule (if chosen: daily at specific time)
  * Unsubscribe from all (with confirmation)

- DigestEmail (Automated)
  * Daily/Weekly summary email
  * Sections:
    - Unread announcements
    - Upcoming events (next 7 days)
    - Pending action items
    - Recent project updates
    - CTF leaderboard (if active)
    - Learning progress
  * Personalized based on member's activities
  * "View on web" links for each item

- EmergencyAlertBanner
  * Red banner at top of all pages
  * Critical announcements only
  * Cannot be dismissed until acknowledged
  * Sound alert (optional)
  * Countdown timer (if time-sensitive)

- MentionsNotification
  * When @mentioned in comments, announcements
  * Direct link to context
  * Quote of the mention
  * Reply directly from notification

- AnnouncementComments
  * Threaded comments
  * Mention other members
  * Like comments
  * Reply to comments
  * Officer badge for official responses
  * Moderation tools (for officers)

- AnnouncementReactions
  * Emoji reactions (👍 ❤️ 😮 🎉 😕)
  * Show who reacted
  * Click to add/remove reaction
  * Reaction counts display

- CommunicationHistory (Member Profile)
  * All announcements sent to this member
  * Open rates
  * Engagement level
  * Preferred channels
  * Opt-out status

Database Tables
sql

- announcements
  * id, title, announcement_type, priority
  * content (rich text), banner_image_path
  * cta_button_text, cta_button_link, cta_button_style
  * target_audience_type (all, roles, years, custom)
  * target_roles (json), target_years (json)
  * target_majors (json), target_user_ids (json)
  * excluded_user_ids (json)
  * estimated_recipients_count
  * channels_enabled (json: {email: true, in_app: true, push: false, sms: false})
  * email_subject, email_preview_text, email_template_id
  * sms_message, push_title, push_body
  * post_to_website, post_to_social_media
  * social_platforms (json)
  * send_timing (immediate, scheduled, digest)
  * scheduled_for (datetime), timezone
  * is_recurring, recurrence_frequency, recurrence_ends_at
  * expires_at
  * track_opens, track_clicks, require_acknowledgment, allow_comments, allow_reactions
  * attachments (json - file paths)
  * related_resources (json), related_events (json), related_projects (json)
  * template_id, is_template
  * requires_approval, approval_workflow, approved_by, approved_at
  * allow_edits_after_send, is_official
  * status (draft, pending_approval, scheduled, sending, sent, failed, archived)
  * sent_at, sent_count, failed_count
  * created_by, updated_by
  * timestamps

- announcement_templates
  * id, name, template_type
  * subject_template, content_template
  * variables (json)
  * default_channels (json), default_priority
  * created_by, timestamps

- notification_preferences
  * id, user_id
  * preferences (json)
    - Per notification type: {email: 'immediate', in_app: true, push: false, sms: false}
  * quiet_hours_start, quiet_hours_end
  * digest_frequency (daily, weekly, off)
  * digest_time (time)
  * unsubscribed_from_all (boolean)
  * timestamps

- notifications
  * id, notifiable_type, notifiable_id (polymorphic)
  * type (announcement, event, project, ctf, mention, system)
  * data (json - notification content)
  * read_at, read_via (web, email, app)
  * action_taken (boolean)
  * action_taken_at
  * created_at

- announcement_deliveries
  * id, announcement_id, user_id
  * channel (email, in_app, push, sms)
  * status (queued, sent, delivered, failed, bounced)
  * sent_at, delivered_at, failed_at
  * failure_reason, error_message
  * opened_at, first_opened_at
  * open_count, unique_opens
  * clicked_at, click_count
  * links_clicked (json - which links)
  * device_type (desktop, mobile, app)
  * ip_address, user_agent
  * acknowledged_at
  * timestamps

- announcement_comments
  * id, announcement_id, user_id
  * parent_comment_id (for threading)
  * comment_text
  * mentioned_users (json)
  * likes_count
  * is_official_response (from officer)
  * is_flagged, flagged_reason
  * timestamps, deleted_at (soft delete)

- announcement_reactions
  * id, announcement_id, user_id
  * reaction_type (thumbs_up, heart, surprised, party, confused)
  * created_at

- announcement_acknowledgments
  * id, announcement_id, user_id
  * acknowledged_at
  * ip_address

- communication_logs
  * id, log_type (announcement, email, sms, push)
  * user_id, content_preview
  * channel, status, metadata (json)
  * sent_at, timestamps
```

### **Announcement Types Explained**

**1. General Announcements**
- Routine club updates
- New initiatives
- General information
- Non-urgent

**2. Event Announcements**
- Tied to specific events
- Includes event details
- Registration links
- Countdown timers

**3. Urgent/Critical Announcements**
- Emergency situations
- Last-minute changes
- Security alerts
- Immediate action required

**4. Meeting Announcements**
- Scheduled meetings
- Agenda preview
- RSVP required
- Virtual links

**5. Achievement Announcements**
- Member accomplishments
- CTF wins
- Project completions
- Recognition

**6. Policy Updates**
- Constitution changes
- New rules
- Procedural updates
- Requires acknowledgment

**7. Opportunity Announcements**
- Internships
- Scholarships
- Competitions
- Guest speakers

**8. Reminders**
- Deadline approaching
- Registration closing
- Payment due
- Action items pending

### **Email Templates**

**Template 1: General Announcement**
```
Subject: {{announcement_title}}

Hi {{member_name}},

{{announcement_content}}

{{cta_button}}

Best regards,
{{sender_name}}
{{sender_role}}
St. Lawrence University Cyber Security and Innovations Club

[View on Dashboard] | [Notification Preferences]
```

**Template 2: Event Reminder**
```
Subject: Reminder: {{event_title}} - {{event_date}}

Hey {{member_name}},

Don't forget! {{event_title}} is coming up soon.

📅 Date: {{event_date}}
⏰ Time: {{event_time}}
📍 Location: {{event_location}}

{{event_description}}

{{registration_status}}

See you there!

[Event Details] | [Add to Calendar] | [Can't Make It?]
```

**Template 3: Emergency Alert**
```
Subject: 🚨 URGENT: {{alert_title}}

{{member_name}},

IMMEDIATE ACTION REQUIRED:

{{alert_message}}

What you need to do:
{{action_items}}

Contact: {{contact_person}} - {{contact_info}}

[Acknowledge Receipt]
```

**Template 4: Weekly Digest**
```
Subject: Your Weekly SLAU-CSIC Update - Week of {{week_date}}

Hi {{member_name}},

Here's what's happening this week:

📢 ANNOUNCEMENTS ({{unread_count}})
{{announcements_list}}

📅 UPCOMING EVENTS
{{events_list}}

💼 PROJECT UPDATES
{{projects_list}}

🏆 CTF LEADERBOARD
{{ctf_status}}

📚 NEW RESOURCES
{{resources_list}}

✅ ACTION ITEMS
{{action_items_list}}

[View Full Dashboard]

─────────────────────────────────
Manage your notification preferences: [Settings]

Notification Channels Explained

1. In-App Notifications

    Real-time updates within the platform
    Notification center (bell icon)
    Badge counts
    Desktop notifications (browser permission)
    Immediate delivery
    Mark as read tracking

2. Email Notifications

    Three modes:
        Immediate: Sent right away
        Digest: Batched daily/weekly
        Off: No emails
    HTML formatted
    Mobile responsive
    Unsubscribe link included
    Tracking pixels for opens
    Link tracking for clicks

3. Push Notifications (if mobile app)

    Mobile device alerts
    Lock screen notifications
    Sound/vibration
    Deep linking to content
    Rich notifications (images, actions)

4. SMS Notifications (critical only)

    Text messages
    160 character limit
    Only for:
        Emergency alerts
        Critical security issues
        Immediate action required
    Opt-in required
    Carrier charges may apply
    Character count validation

5. Website Banner

    Persistent banner at top
    Cannot miss announcements
    Dismissible (unless critical)
    Click for full details

6. Social Media Posts

    Auto-post to club pages
    Public announcements only
    Formatted for each platform
    With images and hashtags

Automated Processes
php

// Jobs/Scheduled Tasks

1. SendScheduledAnnouncements
   - Check for announcements scheduled for current time
   - Process delivery queue
   - Send via enabled channels
   - Log deliveries

2. SendDigestEmails
   - Run at user's preferred digest time
   - Compile unread announcements
   - Add upcoming events
   - Add pending actions
   - Send personalized digest
   - Mark items as "in digest"

3. ProcessAnnouncementDeliveries
   - Queue for large recipient lists
   - Batch send emails (avoid rate limits)
   - Retry failed deliveries (3 attempts)
   - Update delivery status

4. TrackEngagement
   - Process email opens (tracking pixel)
   - Process link clicks (redirect tracking)
   - Update analytics in real-time
   - Calculate engagement scores

5. SendEngagementReminders
   - For announcements requiring acknowledgment
   - Remind non-acknowledgers after 48 hours
   - Escalate to officers if needed
   - Final reminder before deadline

6. CleanupExpiredAnnouncements
   - Auto-archive expired announcements
   - Remove from active board
   - Keep in database for records
   - Send summary to creator

7. GenerateCommunicationReports
   - Weekly reports to officers
   - Engagement statistics
   - Unengaged members list
   - Channel effectiveness
   - Recommendations

8. SendInactivityAlerts
   - Members who haven't read announcements in 30 days
   - "We miss you" email
   - Highlight what they've missed
   - Re-engagement incentive

9. ProcessRecurringAnnouncements
   - Check for recurring announcements due
   - Clone and send next occurrence
   - Update recurrence schedule
   - Stop if end date reached

10. ModerateComments
    - Flag inappropriate comments
    - Check for spam
    - Alert moderators
    - Auto-hide offensive content
```

### **Watch Out For**

⚠️ **Email Deliverability**:
  - Use reputable email service (SendGrid, Mailgun, AWS SES)
  - Authenticate domain (SPF, DKIM, DMARC)
  - Monitor bounce rates (remove invalid emails)
  - Don't send too frequently (email fatigue)
  - Respect unsubscribes immediately
  - Test emails before mass send
  - Mobile-responsive templates

⚠️ **Spam Prevention**:
  - Avoid spam trigger words
  - Don't use ALL CAPS excessively
  - Include physical address (required by law)
  - Clear unsubscribe link
  - Send from consistent address
  - Don't buy email lists (ever)

⚠️ **Privacy & Consent**:
  - Get opt-in for SMS (legally required)
  - Respect notification preferences
  - Don't share email lists
  - GDPR/privacy law compliance
  - Right to be forgotten (delete data on request)
  - Transparent about data usage

⚠️ **Notification Fatigue**:
  - Don't over-notify
  - Combine related notifications
  - Respect quiet hours
  - Allow granular control
  - Default to digest for non-urgent
  - Quality over quantity

⚠️ **Critical Announcements**:
  - Have approval workflow
  - Verify emergency contacts
  - Test emergency system regularly
  - Have backup channels
  - Clear escalation path

⚠️ **Targeting Accuracy**:
  - Validate audience selection
  - Preview recipient list before sending
  - Test with small group first
  - Don't accidentally exclude people
  - Handle edge cases (alumni, inactive members)

⚠️ **Content Quality**:
  - Proofread before sending
  - Check links work
  - Test on different devices
  - Get approval for official communications
  - Include alt text for images
  - Clear subject lines

⚠️ **Timing**:
  - Send at optimal times (not 3 AM)
  - Consider time zones
  - Avoid weekends for non-urgent
  - Don't send during exams (unless critical)
  - Space out multiple announcements

⚠️ **Tracking & Analytics**:
  - Respect privacy (opt-out of tracking)
  - Use data ethically
  - Anonymize aggregate data
  - Don't shame low-engagement members
  - Focus on improving communication

⚠️ **Accessibility**:
  - Screen reader compatible
  - Clear hierarchy
  - Sufficient color contrast
  - Alt text for images
  - Plain text alternative

⚠️ **Failure Handling**:
  - Retry failed deliveries
  - Alert sender of failures
  - Provide failure reasons
  - Fallback to alternative channels
  - Manual intervention option

⚠️ **Rate Limiting**:
  - Don't exceed ESP limits
  - Queue large sends
  - Throttle delivery
  - Monitor sending rate
  - Have retry backoff strategy

⚠️ **Versioning & Edits**:
  - Track announcement versions
  - Show "Updated" badge
  - Notify previous recipients of changes
  - Keep edit history
  - Prevent accidental overwrites

---

## **PHASE 12: Dashboard & Analytics System (Week 10)**

### **What to Build**
1. Member dashboard (personalized home)
2. Officer dashboard (administrative overview)
3. Analytics engine
4. Reporting system
5. Data visualization
6. Performance metrics

### **Deliverables**
- ✅ Personalized member dashboards
- ✅ Officer analytics dashboard
- ✅ Custom report builder
- ✅ Real-time widgets
- ✅ Export capabilities

### **Filament Components**
```
- Filament Widget: Club Overview (Super Admin/President)
  * Key metrics (stats with trend indicators)
    - Total members (vs last month)
    - Active members (logged in last 30 days)
    - Events this month (vs last month)
    - Upcoming events (next 7 days)
    - Active projects count
    - Club balance
    - Pending approvals count (red badge if > 0)
  * Quick actions
    - Create announcement
    - Create event
    - Review applications
    - View reports
  * Alerts
    - Low event attendance
    - Budget concerns
    - Inactive projects
    - Member issues

- Filament Widget: Membership Analytics
  * Growth chart (line chart - 12 months)
  * Member distribution (donut charts)
    - By role
    - By graduation year
    - By major
    - By member level
  * Retention rate (stat)
  * Churn rate (stat)
  * New members this month (stat)
  * Engagement score distribution (histogram)

- Filament Widget: Event Analytics
  * Events hosted (stat - this semester)
  * Average attendance (stat)
  * Attendance trend (line chart)
  * Event types breakdown (bar chart)
  * Upcoming events capacity (list with progress bars)
  * Popular event times/days (heatmap)
  * Event feedback summary (average ratings)

- Filament Widget: Financial Summary
  * Current balance (large stat)
  * Income vs Expenses (line chart - 6 months)
  * Budget utilization (progress bars per category)
  * Pending transactions (stat)
  * Recent transactions (table - last 10)
  * Monthly burn rate (stat)
  * Projected balance (stat with forecast)

- Filament Widget: Project Health Dashboard
  * Active projects (stat)
  * Projects by status (donut chart)
  * Health score distribution (traffic light indicators)
  * At-risk projects (list with reasons)
  * Recent project activity (feed)
  * Completion rate (stat)
  * Average project duration (stat)

- Filament Widget: CTF Performance
  * Active competitions (stat)
  * Participation rate (stat)
  * Average team ranking (stat)
  * Challenges solved (stat - all time)
  * Win rate (stat)
  * Recent CTF results (table)
  * Skills breakdown (radar chart - team strengths)

- Filament Widget: Learning Progress
  * Resources in library (stat)
  * Most popular resources (list with view counts)
  * Learning paths completion rate (stat)
  * Average time spent learning (stat)
  * New resources this month (stat)
  * Resource engagement (line chart)

- Filament Widget: Communication Effectiveness
  * Announcements sent (stat - this month)
  * Average open rate (stat)
  * Average click rate (stat)
  * Engagement by channel (bar chart)
  * Recent announcements performance (table)
  * Unengaged members count (stat)

- Filament Widget: Meeting Insights
  * Meetings held (stat - this semester)
  * Average attendance (stat)
  * Attendance by meeting type (bar chart)
  * Action items completed (stat - percentage)
  * Overdue action items (list)
  * Next meeting countdown

- Filament Custom Page: Reports Center
  * Pre-built reports (select from list)
    - Monthly Club Report
    - Semester Summary
    - Financial Report
    - Membership Report
    - Event Report
    - Project Report
    - CTF Performance Report
  * Date range selector
  * Generate button
  * Export options (PDF, Excel, CSV)
  * Schedule reports (email weekly/monthly)
  * Saved custom reports

- Filament Form: Custom Report Builder
  * Report name (text)
  * Report type (select: Tabular, Chart, Dashboard)
  * Data source (select: Members, Events, Projects, etc.)
  * Fields to include (multi-select with drag-to-reorder)
  * Filters (repeater)
    - Field
    - Operator (equals, contains, greater than, etc.)
    - Value
  * Grouping (select field)
  * Sorting (select field + direction)
  * Aggregations (sum, average, count, etc.)
  * Date range (preset or custom)
  * Visualization type (table, line chart, bar chart, pie chart)
  * Schedule (frequency + recipients)
  * Save as template (toggle)

- Filament Widget: Real-Time Activity Feed
  * Live updates (using Livewire polling/Reverb)
  * Recent activities:
    - New member registered
    - Event registration
    - Project update
    - CTF flag submission
    - Resource uploaded
    - Announcement posted
    - Payment received
  * Filter by activity type
  * Time ago display (e.g., "2 minutes ago")
  * Click to view details

- Filament Widget: Upcoming Deadlines
  * All approaching deadlines in one place:
    - Event registrations closing
    - Project milestones due
    - CTF competitions ending
    - Action items due
    - Fine payments due
    - Budget reports due
  * Sorted by urgency
  * Color-coded (red: overdue, yellow: soon, green: upcoming)
  * Quick action links

- Filament Widget: Member Engagement Leaderboard
  * Top members by engagement score
  * Calculated from:
    - Event attendance
    - Project participation
    - CTF performance
    - Resource contributions
    - Meeting attendance
    - Comments/reactions
  * Monthly, Semester, All-time views
  * Avatar, name, score display
  * Gamification elements

- Filament Widget: System Health Monitor
  * Server status (online, offline, degraded)
  * Database size (stat)
  * Storage usage (progress bar)
  * Failed jobs count (stat)
  * Email queue size (stat)
  * Recent errors (list)
  * Last backup (stat with time ago)
  * Performance metrics (response time, etc.)
```

### **Livewire Components (Member Dashboard)**
```
- PersonalDashboard
  * Welcome message with member name
  * Quick stats:
    - My points
    - My rank
    - Events attended
    - Projects involved
  * Widgets:
    - My upcoming events
    - My action items
    - My projects progress
    - My learning progress
    - My fines (if any)
    - Recommended for me

- UpcomingEventsWidget
  * Next 3-5 events I'm registered for
  * Countdown timers
  * Quick access to event details
  * Add to calendar buttons
  * Un-register option

- MyActionItemsWidget
  * Tasks assigned to me
  * Overdue (red)
  * Due soon (yellow)
  * Later (gray)
  * Mark complete checkbox
  * Expand for details

- MyProjectsWidget
  * Projects I'm involved in
  * Role in each project
  * Progress bars
  * Latest updates
  * Quick link to project board

- MyLearningWidget
  * Current learning paths
  * Progress circles
  * Next resource in path
  * Continue learning button
  * Completed this week

- MyFinesWidget
  * Outstanding balance (prominent if > 0)
  * List of unpaid fines
  * Payment history
  * Pay now button (if implemented)

- RecommendationsWidget
  * Based on interests and activity
  * Suggested events
  * Suggested projects to join
  * Suggested resources to read
  * Suggested learning paths

- ActivityFeedWidget (Personal)
  * My recent activity
  * Events attended
  * Resources completed
  * Projects updated
  * CTF solves
  * Achievements earned

- AchievementsBadgesWidget
  * Badges earned
  * Locked badges (with how to unlock)
  * Progress toward next badge
  * Share achievements button

- NotificationsWidget
  * Recent notifications (last 5)
  * Unread count
  * Quick mark as read
  * View all link

Continue

Database Tables
sql

- dashboards
  * id, user_id, dashboard_type (member, officer, admin)
  * layout (json - widget positions)
  * widgets_enabled (json)
  * refresh_interval_seconds
  * is_default, timestamps

- dashboard_widgets
  * id, key, name, description, component_name
  * default_size (width, height)
  * allowed_roles (json)
  * category, is_active, timestamps

- analytics_events
  * id, event_type (page_view, button_click, feature_use)
  * user_id, session_id
  * page_url, event_data (json)
  * ip_address, user_agent
  * created_at

- reports
  * id, name, report_type, data_source
  * configuration (json - fields, filters, etc.)
  * schedule_frequency, schedule_recipients (json)
  * created_by, is_template, is_public
  * last_generated_at, timestamps

- report_generations
  * id, report_id, generated_by
  * file_path, file_format
  * parameters (json - date range, filters used)
  * generation_time_seconds
  * created_at

- engagement_scores
  * id, user_id, score, rank
  * components (json - breakdown by activity type)
  * calculated_at
  * period (monthly, semester, all_time)

Engagement Score Calculation
php

// Auto-calculated member engagement score (0-100)

Engagement Score = weighted_sum([
    Event Attendance (25%):
      - Registered events attended / total registered
      - Bonus for workshops and competitive events
    
    Project Participation (20%):
      - Active projects count
      - Contributions to projects
      - Project completion
    
    CTF Performance (15%):
      - Competitions participated
      - Challenges solved
      - Team contributions
    
    Learning Activity (15%):
      - Resources viewed/completed
      - Learning paths progress
      - Certifications earned
    
    Meeting Attendance (10%):
      - Meeting attendance rate
      - Bonus for required meetings
    
    Community Engagement (10%):
      - Comments and discussions
      - Resource contributions
      - Helping other members
    
    Responsiveness (5%):
      - Announcement read rate
      - RSVP promptness
      - Action item completion rate
])

Recalculated: Daily
Displayed: Member profile, leaderboards, admin analytics
```

### **Pre-Built Reports**

**1. Monthly Club Report**
```
Content:
- Executive summary
- Membership statistics (new, active, churned)
- Events held and attendance
- Financial summary (income, expenses, balance)
- Project updates
- CTF results
- Achievements and highlights
- Upcoming plans
- Issues and concerns

Format: PDF (for faculty advisor, president)
Auto-generated: First day of each month
```

**2. Semester Summary Report**
```
Content:
- Comprehensive semester overview
- All metrics (members, events, projects, CTFs, finances)
- Comparison to previous semester
- Goal achievement analysis
- Member engagement analysis
- Recommendations for next semester

Format: PDF + Excel (detailed data)
Generated: End of semester
```

**3. Financial Report**
```
Content:
- Income statement
- Expense breakdown by category
- Budget vs actual
- Variance analysis
- Outstanding fines
- Cash flow chart
- Projected balance

Format: Excel + PDF
Frequency: Monthly (for treasurer), Quarterly (for advisor)
```

**4. Membership Report**
```
Content:
- Current members list
- New members
- Inactive/churned members
- Demographics (year, major, role)
- Engagement distribution
- Retention analysis

Format: Excel
Frequency: On-demand
```

**5. Event Performance Report**
```
Content:
- All events in period
- Attendance data
- Registration vs attendance
- Feedback ratings
- Popular event types
- Time/day analysis
- Room utilization

Format: PDF + charts
Frequency: Monthly or per event
```

**6. Project Portfolio Report**
```
Content:
- All active projects
- Project health scores
- Team compositions
- Progress tracking
- Milestones status
- Resources allocated
- Completed projects showcase

Format: PDF with visuals
Frequency: Monthly or on-demand
```

**7. CTF Performance Report**
```
Content:
- Competitions participated
- Team rankings
- Individual performance
- Skills analysis
- Win/loss record
- Improvement trends
- Comparison to other schools (if data available)

Format: PDF
Frequency: After each CTF + semester summary

Automated Processes
php

// Jobs/Scheduled Tasks

1. CalculateEngagementScores
   - Run daily at midnight
   - Calculate for all members
   - Update leaderboards
   - Identify disengaged members
   - Notify officers of concerns

2. UpdateDashboardCache
   - Refresh cached dashboard data
   - Run every 5 minutes for real-time widgets
   - Run hourly for heavy computations
   - Per-user cache for personalized dashboards

3. GenerateScheduledReports
   - Check for reports due
   - Generate with current data
   - Export to PDF/Excel
   - Email to recipients
   - Store in reports archive

4. TrackAnalyticsEvents
   - Log user interactions
   - Page views
   - Feature usage
   - Button clicks
   - Time spent per page

5. SendEngagementAlerts
   - Alert officers about:
     - Members with very low engagement (< 20)
     - Sudden drops in engagement
     - Inactive members (no activity in 30 days)
   - Suggest interventions

6. CompileMonthlyMetrics
   - Run on first of month
   - Aggregate all statistics
   - Compare to previous months
   - Identify trends
   - Prepare summary for officers

7. ArchiveOldAnalytics
   - Move old analytics_events to archive table
   - Keep last 90 days in main table
   - Compress older data
   - Run monthly

8. OptimizeDashboardQueries
   - Identify slow queries
   - Create necessary indexes
   - Cache frequently accessed data
   - Alert admin of performance issues

Watch Out For

⚠️ Performance:

    Cache heavily used data
    Use database indexes
    Lazy load widgets
    Paginate large datasets
    Optimize chart queries
    Use queue for heavy computations

⚠️ Data Accuracy:

    Validate calculations
    Handle edge cases (division by zero)
    Test with real data
    Cross-verify metrics
    Document calculation methods

⚠️ Dashboard Usability:

    Don't overwhelm with data
    Prioritize key metrics
    Allow customization
    Mobile-responsive
    Fast loading
    Clear visualizations

⚠️ Privacy:

    Role-based data access
    Anonymize personal data in aggregate reports
    Don't expose individual performance publicly
    Respect data access permissions
    Audit logs for sensitive data access

⚠️ Report Generation:

    Queue long-running reports
    Show progress indicator
    Handle timeouts gracefully
    Validate date ranges
    Test with edge cases (no data periods)

⚠️ Chart Readability:

    Appropriate chart types for data
    Clear labels and legends
    Color-blind friendly colors
    Avoid chartjunk
    Export-friendly formats

⚠️ Real-Time Updates:

    Don't poll too frequently (server load)
    Use Laravel Reverb for real-time when needed
    Fallback to periodic refresh
    Consider WebSocket costs

⚠️ Data Retention:

    Define retention policies
    Archive old data
    GDPR compliance (right to deletion)
    Backup before archiving

⚠️ Export Functionality:

    Respect data permissions on export
    Limit export size (prevent memory issues)
    Include metadata (export date, filters used)
    Secure download links (temporary, signed URLs)

⚠️ Dashboard Customization:

    Allow widget reordering
    Save user preferences
    Reset to default option
    Share dashboard layouts (admins)

Would you like me to continue with Phase 13 (User Profile & Gamification)?


## **PHASE 13: User Profile & Gamification System (Week 11)**

### **What to Build**
1. Comprehensive member profiles
2. Skills and endorsements system
3. Achievement badges and awards
4. Experience points (XP) and leveling
5. Leaderboards and rankings
6. Member portfolios
7. Social features (following, connections)

### **Deliverables**
- ✅ Rich profile pages
- ✅ Badge and achievement system
- ✅ XP and leveling mechanics
- ✅ Public portfolio pages
- ✅ Gamification engine

### **Filament Components**
```
- Filament Resource: User Profiles Management (Admin)
  * Extended user management
  * View full profiles
  * Award manual badges
  * Adjust XP (with reason)
  * View member timeline
  * Export profile data

- Filament Table: Achievement Badges
  * Columns: Badge Name, Type, Rarity, Requirements, Awarded Count, Is Active
  * Filters: Type, Rarity, Auto-awarded vs Manual
  * Actions: Edit, Deactivate, Award manually, View holders
  * Custom column: Award rate (percentage of eligible members)

- Filament Form: Create/Edit Badge
  * Section 1: Badge Details
    - Badge name (text - required)
    - Description (textarea)
    - Badge type (select: Participation, Achievement, Milestone, Special, Leadership)
    - Rarity (select: Common, Uncommon, Rare, Epic, Legendary)
    - Icon/image (file upload - SVG or PNG)
    - Icon color scheme (color pickers for gradient)
  
  * Section 2: Requirements
    - Award type (select: Automatic, Manual, Hybrid)
    - Criteria (rich text - describe what's needed)
    
    If Automatic:
      - Trigger event (select: Event attended, Project completed, CTF win, etc.)
      - Conditions (repeater)
        - Metric (select: Count, Score, Percentage, etc.)
        - Operator (>=, <=, ==, etc.)
        - Value (number)
      - Examples:
        - "Attend 10 events" → event_attendance >= 10
        - "Solve 50 CTF challenges" → ctf_challenges_solved >= 50
        - "Complete a learning path" → learning_paths_completed >= 1
    
    If Manual:
      - Who can award (select roles)
      - Requires approval from (select role)
      - Nomination process (toggle)
  
  * Section 3: Rewards
    - Experience points awarded (number)
    - Unlocks feature (optional)
      - Access to advanced resources
      - Special role/title
      - Priority event registration
    - Displayed on profile (toggle)
    - Announcement when earned (toggle)
  
  * Section 4: Visibility & Status
    - Is active (toggle)
    - Is visible before earning (toggle - "locked" badges)
    - Show progress toward badge (toggle)
    - Available from date (date picker - seasonal badges)
    - Available until date (date picker)
  
  * Section 5: Badge Tiers (Optional)
    - For progressive badges (Bronze, Silver, Gold)
    - Tier requirements (repeater)
    - Different icons per tier

- Filament Form: Award Badge Manually
  * Select member (searchable)
  * Select badge
  * Reason for award (textarea)
  * Award date (date picker - default today)
  * Send notification (toggle)
  * Make announcement (toggle - for special badges)
  * Awarded by (auto-filled)

- Filament Table: User Skills (Admin View)
  * All skills in system
  * Columns: Skill Name, Category, Users with Skill, Avg Proficiency, Endorsement Count
  * Actions: Edit, Merge similar, Delete
  * Add skill to taxonomy

- Filament Form: Skill Management
  * Skill name (text)
  * Category (select: Technical, Soft Skills, Tools, Languages, Certifications)
  * Subcategory (dynamic based on category)
  * Description (textarea)
  * Related skills (multi-select)
  * Difficulty to learn (select: Beginner, Intermediate, Advanced)
  * Resources available (multi-select from learning resources)
  * Is verified skill (toggle - requires proof like certification)

- Filament Widget: Gamification Overview
  * Total XP distributed (stat)
  * Badges awarded (stat)
  * Active leaderboard entries (stat)
  * Average member level (stat)
  * XP distribution (histogram)
  * Badge rarity distribution (pie chart)
  * Top XP earners this month (list)

- Filament Widget: Member Levels Distribution
  * Visual chart showing members per level
  * Level thresholds
  * Progression curve analysis
  * Recommendations for level balancing

- Filament Form: XP Configuration
  * Define XP rewards for actions:
    - Event attendance (number XP)
    - Event hosting (number XP)
    - Workshop completion (number XP)
    - Project contribution (number XP)
    - Project completion (number XP)
    - CTF challenge solved (number XP - varies by difficulty)
    - CTF competition win (number XP)
    - Learning resource completion (number XP)
    - Meeting attendance (number XP)
    - Helping other members (number XP)
    - Resource upload (number XP)
    - Comment/discussion participation (number XP)
  * XP multipliers:
    - First-time actions (1.5x)
    - Consecutive days active (streak bonus)
    - Officer role (1.2x for leadership actions)
  * Level thresholds (repeater)
    - Level number
    - XP required
    - Title/rank name
    - Perks unlocked
    - Badge earned (optional)

- Filament Table: XP Transactions (Like financial ledger)
  * Columns: Member, Action, XP Amount, Multiplier, Date, Awarded By
  * Filters: Date range, Action type, Member
  * Search by member
  * Actions: Void transaction (with reason), View details
  * Show running total

- Filament Form: Award Custom XP
  * Select member(s)
  * XP amount (number - can be negative)
  * Reason (textarea - required)
  * Category (select: Bonus, Penalty, Correction, Special)
  * Notify member (toggle)
  * Awarded by (auto-filled)

- Filament Widget: Leaderboard Management
  * Configure leaderboard types:
    - Overall XP
    - Monthly XP
    - Event attendance
    - CTF performance
    - Project contributions
    - Learning progress
  * Set visibility (public, members-only, officers-only)
  * Freeze leaderboard (toggle - for competitions)
  * Reset schedule (monthly, semester, never)

- Filament Table: Profile Endorsements
  * Columns: From User, To User, Skill Endorsed, Date, Message
  * Filters: Skill, Date range
  * Actions: View profiles, Flag inappropriate
  * Bulk Actions: Approve pending (if moderation enabled)
```

### **Livewire Components (Member-Facing)**
```
- MemberProfile (Public/Semi-Public)
  * Profile Header:
    - Avatar (large, with level badge overlay)
    - Name and pronouns
    - Member title/rank (based on level)
    - Member since date
    - Current level and XP progress bar
    - Location (if shared)
    - Social links (GitHub, LinkedIn, Twitter, Personal site)
    - Contact button (if allowed)
    - Follow/Connect button
  
  * Quick Stats Cards:
    - Total XP
    - Current rank
    - Events attended
    - Projects involved
    - CTF challenges solved
    - Resources contributed
  
  * Badges Showcase:
    - Earned badges (displayed prominently)
    - Locked badges (grayed out with unlock requirements)
    - Rarest badge highlight
    - Toggle between grid and list view
  
  * Skills Section:
    - Skills with proficiency levels (bars)
    - Endorsement counts per skill
    - Endorse button (for other members)
    - Grouped by category
    - Add skill button (own profile only)
  
  * Activity Timeline:
    - Recent achievements
    - Events attended
    - Projects updates
    - CTF wins
    - Resources completed
    - Chronological feed with icons
    - Filterable by type
    - Load more pagination
  
  * Portfolio Section:
    - Featured projects
    - CTF write-ups
    - Presentations given
    - Resources uploaded
    - GitHub contributions (if linked)
    - Certificates earned
  
  * Achievements & Recognition:
    - Special awards
    - Competition wins
    - Leadership roles held
    - Certificates
    - Honors and mentions
  
  * Statistics Tab:
    - Detailed stats and charts
    - Activity heatmap (GitHub-style)
    - XP over time chart
    - Category breakdowns
    - Personal bests
  
  * Privacy Controls (Own Profile):
    - Toggle profile visibility (public, members-only, private)
    - Choose what to display
    - Hide specific badges
    - Control who can endorse
    - Download profile data

- EditProfile
  * All editable fields
  * Avatar upload with crop tool
  * Bio editor (rich text, limited)
  * Skills management (add, remove, set proficiency)
  * Social links
  * Privacy settings
  * Notification preferences
  * Password change
  * Two-factor authentication
  * Account deletion (with confirmation)

- SkillsEditor
  * Add skill (type-ahead search)
  * Set proficiency (slider or select)
  * Drag to reorder (feature skills first)
  * Remove skill
  * Request endorsement
  * Show endorsements received

- BadgeShowcase
  * All earned badges
  * Pinned badges (choose up to 3 to highlight)
  * Badge details modal (on click)
    - How earned
    - Date earned
    - Rarity indicator
    - Others who have it (if allowed)
  * Locked badges view
    - Requirements to unlock
    - Progress toward badge (if trackable)
  * Share badge on social media

- XPProgressWidget
  * Current XP
  * XP to next level
  * Visual progress bar with animation
  * Recent XP gains (last 5 transactions)
  * XP history chart
  * How to earn more XP (tips)

- LeaderboardWidget
  * Multiple leaderboard tabs:
    - Overall XP (all-time)
    - Monthly XP
    - Semester XP
    - Event attendance
    - CTF performance
    - Learning progress
  * User's position highlighted
  * Top 10 displayed, expand for more
  * Avatar, name, score/metric
  * Rank change indicator (↑↓ or new)
  * Filter by: Overall, By year, By major
  * Refresh button

- AchievementNotification
  * Popup when badge earned
  * Animated badge reveal
  * Badge name and description
  * XP awarded
  * Share button
  * View all badges button
  * Confetti animation (for rare badges)

- SkillEndorsementModal
  * Select skill to endorse
  * Add message (optional)
  * Submit endorsement
  * Thank you confirmation

- ProfileComparison (Fun Feature)
  * Compare your profile with another member
  * Side-by-side stats
  * Shared skills
  * Shared badges
  * Who has more XP
  * Friendly competition view

- MemberDirectory (Enhanced)
  * Search and filter members
  * Filter by:
    - Skills
    - Level range
    - Graduation year
    - Major
    - Looking for project/CTF teammates
  * Sort by: Name, Level, XP, Join date
  * Grid or list view
  * Member cards with quick stats
  * Connect/Follow buttons

- FollowersFollowing
  * Lists of followers and following
  * Activity feed from followed members
  * Follow/Unfollow management
  * Suggest people to follow

- PersonalStats
  * Comprehensive statistics dashboard
  * Custom date range selector
  * Charts and graphs:
    - XP over time
    - Activity by type
    - Monthly comparison
    - Streaks (consecutive days active)
  * Milestones reached
  * Personal bests
  * Export stats as PDF
```

### **Database Tables**
```sql
- user_profiles (extends users table)
  * id, user_id
  * bio (text)
  * tagline (text - short description)
  * pronouns (text)
  * location (text)
  * website_url, github_username, linkedin_url, twitter_handle
  * portfolio_url
  * phone_number (encrypted)
  * emergency_contact_name, emergency_contact_phone
  * profile_visibility (public, members_only, private)
  * show_email, show_phone, show_stats
  * looking_for_team (boolean)
  * available_for_mentoring (boolean)
  * interests (json)
  * timezone
  * avatar_path, banner_image_path
  * total_xp, current_level, level_title
  * rank_overall, rank_by_year
  * activity_streak_days, longest_streak_days
  * profile_views_count
  * last_active_at
  * timestamps

- skills
  * id, name, slug, category, subcategory
  * description, difficulty_level
  * related_skills (json)
  * is_verified_skill, verification_required
  * icon, color
  * users_with_skill_count
  * timestamps

- user_skills
  * id, user_id, skill_id
  * proficiency_level (beginner, intermediate, advanced, expert)
  * years_of_experience (number)
  * is_featured (boolean - show prominently)
  * is_verified (boolean - proof provided)
  * verification_document_path
  * endorsement_count
  * order_number (for custom ordering)
  * timestamps

- skill_endorsements
  * id, endorser_user_id, endorsed_user_id, skill_id
  * message (text - optional)
  * is_visible (boolean)
  * created_at

- badges
  * id, name, slug, description
  * badge_type (participation, achievement, milestone, special, leadership)
  * rarity (common, uncommon, rare, epic, legendary)
  * icon_path, icon_color_start, icon_color_end (for gradient)
  * award_type (automatic, manual, hybrid)
  * criteria (text), trigger_event (text)
  * conditions (json - for automatic)
  * xp_reward
  * unlocks_feature (json)
  * is_displayed_on_profile, announce_when_earned
  * is_active, is_visible_before_earning, show_progress
  * available_from, available_until
  * has_tiers, tier_data (json)
  * awarded_count
  * manual_award_roles (json - which roles can award)
  * requires_approval, approval_role
  * created_by, timestamps

- user_badges
  * id, user_id, badge_id
  * tier (if tiered badge)
  * awarded_at, awarded_by_user_id
  * award_type (automatic, manual)
  * reason (text - for manual awards)
  * is_pinned (boolean - show prominently)
  * is_featured (boolean)
  * announcement_sent (boolean)
  * order_number
  * timestamps

- xp_transactions
  * id, user_id
  * action_type (event_attendance, ctf_solve, project_complete, etc.)
  * action_id (polymorphic - references the related event, project, etc.)
  * xp_amount (can be negative for penalties)
  * multiplier (decimal)
  * total_xp_awarded (xp_amount * multiplier)
  * reason (text)
  * category (earned, bonus, penalty, correction, special)
  * awarded_by_user_id
  * transaction_date
  * is_voided (boolean)
  * void_reason (text)
  * timestamps

- levels
  * id, level_number, xp_required
  * title (text - "Novice", "Apprentice", "Expert", etc.)
  * description, perks (json)
  * badge_id (optional - badge awarded at this level)
  * icon_path, color
  * timestamps

- leaderboards
  * id, name, leaderboard_type
  * metric (xp, event_attendance, ctf_score, etc.)
  * period (all_time, monthly, semester, custom)
  * visibility (public, members_only, officers_only)
  * is_frozen (boolean)
  * reset_schedule (monthly, semester, never)
  * last_reset_at
  * config (json)
  * timestamps

- leaderboard_entries
  * id, leaderboard_id, user_id
  * rank, previous_rank
  * score, previous_score
  * rank_change (calculated)
  * metadata (json - additional info)
  * calculated_at

- user_follows
  * id, follower_user_id, following_user_id
  * created_at

- user_connections
  * id, user_id_1, user_id_2
  * status (pending, accepted, blocked)
  * requested_by_user_id
  * created_at, accepted_at

- profile_views
  * id, profile_user_id, viewer_user_id
  * viewed_at, ip_address
  * referrer_url

- achievements_log
  * id, user_id, achievement_type
  * achievement_data (json)
  * description (text)
  * xp_earned, badge_earned_id
  * created_at
```

### **Badge Examples**

**Participation Badges (Common)**
```
1. "First Steps" - Attend first event (10 XP)
2. "Team Player" - Join your first project (15 XP)
3. "CTF Rookie" - Participate in first CTF (20 XP)
4. "Learner" - Complete first learning resource (10 XP)
5. "Engaged" - Attend 5 events (25 XP)
6. "Social Butterfly" - Attend 3 social events (20 XP)
```

**Achievement Badges (Uncommon to Rare)**
```
7. "Workshop Warrior" - Attend 10 workshops (50 XP)
8. "Project Pioneer" - Complete a project (100 XP)
9. "Flag Hunter" - Solve 25 CTF challenges (75 XP)
10. "Knowledge Seeker" - Complete a learning path (100 XP)
11. "Consistent Contributor" - 30-day activity streak (150 XP)
12. "Meeting Maven" - 100% meeting attendance for semester (80 XP)
```

**Milestone Badges (Rare to Epic)**
```
13. "Veteran" - 1 year membership (200 XP)
14. "Expert" - Reach level 10 (250 XP)
15. "Master" - Reach level 20 (500 XP)
16. "Centurion" - Attend 100 events (300 XP)
17. "CTF Champion" - Win 5 CTF competitions (400 XP)
18. "Resource King/Queen" - Upload 50 resources (350 XP)
```

**Special Badges (Epic to Legendary)**
```
19. "Founder" - Charter member (manual award)
20. "All-Star" - Top 5 engagement for semester (500 XP)
21. "Renaissance Person" - Skills in all categories (300 XP)
22. "Mentor" - Mentored 10+ members (250 XP)
23. "Security Guru" - Certified (CEH, Security+, etc.) (400 XP)
24. "Bug Bounty Hunter" - Found vulnerability (500 XP)
```

**Leadership Badges (Manual Award)**
```
25. "Officer" - Elected to officer position
26. "President" - Served as president
27. "Event Organizer" - Organized 5+ successful events
28. "Ambassador" - Represented club externally
```

**Seasonal/Limited Badges**
```
29. "Hacktoberfest 2024" - Participated in October
30. "CTF Month Champion" - Top performer during CTF month
31. "Holiday Helper" - Volunteered during holiday season
```

**Tiered Badges (Progressive)**
```
32. "Attendance" - Bronze (10), Silver (25), Gold (50), Platinum (100)
33. "CTF Solver" - Bronze (10), Silver (50), Gold (100), Platinum (250)
34. "Project Leader" - Bronze (1), Silver (3), Gold (5)
35. "Helper" - Bronze (25 comments), Silver (100), Gold (250)
```

### **Level System**
```php
Level Progression (Exponential Growth):

Level 1: 0 XP (Newbie)
Level 2: 100 XP (Beginner)
Level 3: 250 XP (Apprentice)
Level 4: 450 XP (Learner)
Level 5: 700 XP (Practitioner)
Level 6: 1,000 XP (Skilled)
Level 7: 1,400 XP (Proficient)
Level 8: 1,900 XP (Experienced)
Level 9: 2,500 XP (Advanced)
Level 10: 3,200 XP (Expert)
Level 11: 4,000 XP (Veteran)
Level 12: 5,000 XP (Elite)
Level 13: 6,200 XP (Master)
Level 14: 7,600 XP (Guru)
Level 15: 9,200 XP (Legend)
Level 16: 11,000 XP (Champion)
Level 17: 13,000 XP (Titan)
Level 18: 15,500 XP (Demigod)
Level 19: 18,500 XP (Mythical)
Level 20: 22,000 XP (Grand Master)

Levels 21+: +5,000 XP per level (virtually unlimited)

Perks by Level:
- Level 5: Can endorse skills
- Level 7: Can create projects
- Level 10: Priority event registration
- Level 12: Can upload resources without approval
- Level 15: Can mentor others officially
- Level 18: Access to advanced labs
- Level 20: Lifetime honorary member status
```

### **XP Award Examples**
```
Actions and XP Rewards:

Events:
- Attend workshop: 20 XP
- Attend competition: 30 XP
- Attend social event: 15 XP
- Attend general meeting: 10 XP
- Host workshop: 100 XP
- Organize event: 150 XP

Projects:
- Join project: 25 XP
- Complete milestone: 50 XP
- Complete project: 200 XP
- Lead project: 300 XP (on completion)

CTF:
- Solve easy challenge: 10 XP
- Solve medium challenge: 25 XP
- Solve hard challenge: 50 XP
- First blood: +20 XP bonus
- Win competition (team): 100 XP each
- Top 3 finish: 50 XP

Learning:
- Complete resource: 15 XP
- Complete learning path: 100 XP
- Upload quality resource: 50 XP
- Earn certification: 200 XP

Community:
- Meeting attendance: 10 XP
- Helpful comment: 2 XP
- Endorsed skill: 5 XP (giver and receiver)
- Mentoring session: 20 XP

Special:
- Referral (new member): 50 XP
- Bug report: 30 XP
- Feature suggestion adopted: 40 XP
- Representing club: 100 XP
- Club achievement: 200 XP

Streaks:
- 7-day streak: +50 XP bonus
- 30-day streak: +200 XP bonus
- 90-day streak: +500 XP bonus

Multipliers:
- First time action: 1.5x XP
- Officer performing leadership action: 1.2x XP
- During competitive period: 1.3x XP
- Collaboration (team actions): 1.1x XP each
```

### **Automated Processes**
```php
// Jobs/Scheduled Tasks

1. CheckBadgeEligibility
   - Run hourly
   - Check all members against automatic badge criteria
   - Award badges when conditions met
   - Send notifications
   - Post announcements for rare badges
   - Update badge counters

2. CalculateLevels
   - Recalculate after XP transaction
   - Check if level threshold crossed
   - Update user level
   - Award level badge if applicable
   - Send level-up notification with animation
   - Unlock perks

3. UpdateLeaderboards
   - Run every hour (or real-time for active competitions)
   - Recalculate rankings
   - Detect rank changes
   - Update previous rank field
   - Send notifications for top 10 entries
   - Freeze/unfreeze based on schedule

4. AwardStreakBonuses
   - Run daily at midnight
   - Check activity for previous day
   - Increment streak or reset to 0
   - Award streak bonus XP at milestones
   - Track longest streak

5. UpdateEngagementScores
   - Run daily
   - Recalculate engagement scores
   - Update member rankings
   - Identify disengaged members
   - Recommend actions to increase engagement

6. SendAchievementDigests
   - Weekly summary of achievements
   - "You earned X XP this week"
   - Badges earned
   - Level progress
   - Comparison to last week
   - Suggestions for next badge

7. ResetMonthlyLeaderboards
   - Run first of month
   - Archive current month standings
   - Award top performers
   - Reset for new month
   - Announce winners

8. CleanupProfileViews
   - Delete old profile view logs (90+ days)
   - Keep aggregated counts
   - Maintain privacy

9. SuggestEndorsements
   - Weekly suggestions per member
   - "You worked with X on project Y, endorse their skills"
   - Based on collaboration history
   - Increase endorsement activity

10. BadgeProgressNotifications
    - Weekly reminders of nearly-earned badges
    - "You're 2 events away from Workshop Warrior!"
    - Motivate completion
    - Include tips on how to earn
```

### **Watch Out For**

⚠️ **XP Inflation**:
  - Start conservative with XP rewards
  - Monitor average XP gain rates
  - Adjust rewards if everyone reaches high levels too quickly
  - Make higher levels exponentially harder
  - Cap XP from repetitive actions (diminishing returns)

⚠️ **Gaming the System**:
  - Prevent XP farming (e.g., rapid event register/unregister)
  - Rate limit certain XP-earning actions
  - Manual review for suspicious XP spikes
  - Void fraudulent XP transactions
  - Track IP and patterns for abuse

⚠️ **Badge Design**:
  - Make badges visually appealing and distinct
  - Clear criteria (no ambiguity)
  - Achievable but challenging
  - Balanced across categories (not all CTF-focused)
  - Test badge unlock rates (not too easy or impossible)

⚠️ **Privacy Concerns**:
  - Allow profile privacy settings
  - Don't force leaderboard participation
  - Anonymous options where appropriate
  - Respect FERPA (education privacy laws)
  - Opt-out of gamification features

⚠️ **Inclusivity**:
  - Don't make it too competitive (can discourage)
  - Badges for all skill levels
  - Recognize diverse contributions (not just technical)
  - Participation badges for those who show up
  - Avoid elitism

⚠️ **Performance**:
  - Cache leaderboards (don't recalculate on every page load)
  - Index database properly (user_id, xp_amount, created_at)
  - Pagination for leaderboards
  - Lazy load profile sections
  - Optimize badge checking queries

⚠️ **Endorsement Authenticity**:
  - Limit endorsements per day (prevent spam)
  - Must have collaborated to endorse
  - Moderation for inappropriate messages
  - Report/flag system
  - Consider requiring time overlap or shared activity

⚠️ **Level Balancing**:
  - Track time to reach each level
  - Adjust thresholds if too fast/slow
  - Higher levels should feel meaningful
  - Don't make it impossible to reach top levels in 4 years

⚠️ **Badge Saturation**:
  - Don't create too many badges (quality over quantity)
  - Retire outdated badges
  - Seasonal badges create urgency
  - Make legendary badges truly special

⚠️ **Profile Completeness**:
  - Encourage but don't require complete profiles
  - XP bonus for profile completion
  - Guidance on what makes a good profile
  - Examples of great profiles

⚠️ **Social Features**:
  - Block/report functionality
  - Privacy controls on who can follow
  - Notification preferences for social interactions
  - Handle account deletion gracefully (anonymize or remove follows)

⚠️ **Data Export**:
  - Allow members to export their data
  - Include XP history, badges, stats
  - GDPR compliance
  - Portable format (JSON, CSV)

⚠️ **Fairness**:
  - New members shouldn't feel discouraged by veterans' high levels
  - Separate leaderboards by year or tenure
  - Highlight "Rising Star" new members
  - Monthly resets keep it fresh

---

## **PHASE 14: Settings & Configuration System (Week 12)**

### **What to Build**
1. Club settings and configuration
2. System preferences
3. Customization options
4. Feature toggles
5. Email templates management
6. Backup and maintenance tools

### **Deliverables**
- ✅ Comprehensive settings panel
- ✅ Club branding customization
- ✅ Feature flags system
- ✅ Email template editor
- ✅ System maintenance tools

### **Filament Components**
```
- Filament Custom Page: Settings Hub
  * Navigation sidebar with categories:
    - General Settings
    - Club Information
    - Branding & Appearance
    - Features & Modules
    - Email & Notifications
    - Security & Privacy
    - Integrations
    - Maintenance & Backup
    - Advanced Settings

- Section 1: General Settings
  * Club basics:
    - Club full name (text)
    - Club acronym (text)
    - Tagline (text)
    - Description (rich text)
    - Founded date (date picker)
    - Contact email (email)
    - Contact phone (phone)
    - Office location (text)
    - Office hours (textarea or time range repeater)
  
  * Academic settings:
    - Current academic year (text)
    - Current semester (select: Fall, Spring, Summer)
    - Semester start date (date)
    - Semester end date (date)
    - Exam periods (date ranges)
  
  * Time & locale:
    - Default timezone (select)
    - Date format (select)
    - Time format (12h/24h)
    - Currency (select)
    - Language (select)

- Section 2: Branding & Appearance
  * Logo & images:
    - Club logo (file upload - primary)
    - Club logo (alternate - for dark backgrounds)
    - Favicon (file upload)
    - Banner image (for website header)
    - Default event image
    - Email header image
  
  * Colors (color pickers):
    - Primary color
    - Secondary color
    - Accent color
    - Success color
    - Warning color
    - Danger color
  
  * Theme:
    - Default theme (light/dark/auto)
    - Allow theme switching (toggle)
    - Custom CSS (code editor - advanced)
  
  * Typography:
    - Heading font (select)
    - Body font (select)
    - Code font (select - monospace)

- Section 3: Features & Modules
  * Enable/disable features (toggles for each):
    - Event management (always on - core)
    - Project management
    - CTF competitions
    - Learning resources
    - Meetings management
    - Financial tracking
    - Fines system
    - Gamification (XP, badges, leaderboards)
    - Social features (follows, connections)
    - Announcements
    - Comments & discussions
    - Member directory
    - Public website/landing page
  
  * Feature-specific settings:
    - Event auto-archive after X days
    - Project
    proposal approval required
    - CTF auto-scoring enabled
    - Fines auto-issue enabled
    - Badge auto-award enabled
    - Leaderboard visibility

- Section 4: Membership Settings
  * Registration:
    - Open registration (toggle)
    - Require approval (toggle)
    - Allowed email domains (repeater: @stlawu.edu)
    - Required fields during registration
    - Welcome email (toggle)
    - Probation period (number - days)
  
  * Membership rules:
    - Max absences allowed per semester
    - Fine for unexcused absence (currency)
    - Inactivity threshold (days)
    - Auto-suspend after inactivity (toggle)
    - Alumni conversion (auto after graduation)
  
  * Member levels:
    - Use level system (toggle)
    - Starting level
    - Max level (or unlimited)

- Section 5: Events Settings
  * Event defaults:
    - Default event duration (minutes)
    - Default capacity
    - Registration opens X days before event
    - Registration closes X hours before event
    - Allow waitlist by default
    - No-show fine amount
    - Cancel penalty grace period (hours)
  
  * Attendance:
    - Attendance tracking required
    - QR code check-in enabled
    - Late arrival threshold (minutes)
    - Early departure threshold (minutes)
  
  * Reminders:
    - Send reminder 24h before (toggle)
    - Send reminder 1h before (toggle)
    - Send feedback request after event (toggle)

- Section 6: Financial Settings
  * Budget:
    - Current semester budget (currency)
    - Budget categories (repeater: name, allocated amount)
    - Over-budget alerts (toggle)
    - Alert threshold (percentage)
  
  * Transactions:
    - Approval required for amounts > (currency)
    - Approvers (select roles)
    - Receipt required for amounts > (currency)
    - Fiscal year start month
  
  * Fines:
    - Fines system enabled (toggle)
    - Default fine amounts (repeater by type)
    - Payment grace period (days)
    - Late payment penalty (percentage)
    - Waiver approval roles

- Section 7: CTF Settings
  * Competition defaults:
    - Default team size
    - Default competition duration (hours)
    - Scoring type (static/dynamic)
    - First blood bonus (points)
    - Flag format regex
    - Rate limit (submissions per minute)
  
  * Challenges:
    - Auto-unlock progressive challenges
    - Hints system enabled
    - Hint cost (percentage of challenge points)
    - Write-up submission encouraged

- Section 8: Email & Notifications
  * Email configuration:
    - From name (text)
    - From email (email)
    - Reply-to email (email)
    - Email service (select: SMTP, SendGrid, Mailgun, SES)
    - Service credentials (encrypted fields)
    - Test email button
  
  * Email templates (list with edit):
    - Welcome email
    - Email verification
    - Password reset
    - Event reminder
    - Event confirmation
    - Meeting invitation
    - Announcement
    - Invoice/Receipt
    - Badge earned
    - Level up
    - Custom templates (add more)
  
  * Notification defaults:
    - Default notification channels by type
    - Digest schedule
    - Quiet hours (start/end time)

- Section 9: Security & Privacy
  * Authentication:
    - Require email verification (toggle)
    - Password requirements:
      - Minimum length
      - Require uppercase
      - Require numbers
      - Require special characters
    - Two-factor authentication (toggle available)
    - Session timeout (minutes)
    - Max login attempts before lockout
    - Lockout duration (minutes)
  
  * Privacy:
    - Default profile visibility (public/members/private)
    - GDPR mode (toggle - additional privacy features)
    - Data retention period (days - for logs)
    - Allow data export (toggle)
    - Cookie consent required (toggle)
  
  * Content moderation:
    - Comments require approval (toggle)
    - Profanity filter (toggle)
    - Spam detection (toggle)
    - Flagging enabled (toggle)

- Section 10: Integrations
  * Social media:
    - Facebook page URL
    - Twitter/X handle
    - Instagram handle
    - LinkedIn page URL
    - Auto-post announcements (toggle per platform)
  
  * External services:
    - GitHub organization
    - Slack workspace
    - Discord server
    - Google Calendar integration
    - Zoom API credentials
  
  * Analytics:
    - Google Analytics ID
    - Track anonymous usage (toggle)
  
  * Cloud storage:
    - AWS S3 credentials
    - Storage quota (GB)

- Section 11: Maintenance & Backup
  * Backups:
    - Auto-backup enabled (toggle)
    - Backup frequency (daily/weekly)
    - Backup time (time picker)
    - Backup retention (number of backups)
    - Backup location (local/S3)
    - Manual backup button
    - Restore from backup (select backup + confirm)
  
  * Maintenance mode:
    - Enable maintenance mode (toggle)
    - Maintenance message (textarea)
    - Whitelist IPs (repeater - allow admins)
  
  * System health:
    - Disk usage (display)
    - Database size (display)
    - Failed jobs count (display)
    - Clear cache button
    - Clear logs button (with confirmation)
    - Optimize database button
    - Run migrations button (with confirmation)
  
  * Logs viewer:
    - Application logs
    - Error logs
    - Security logs
    - Filter by date/level
    - Download logs

- Section 12: Advanced Settings
  * API:
    - API enabled (toggle)
    - API rate limiting (requests per minute)
    - API key management
    - Webhook URLs
  
  * Developer mode:
    - Debug mode (toggle - only in non-production)
    - Query logging (toggle)
    - Email testing mode (catch-all)
  
  * Custom fields:
    - Add custom fields to models (user, event, project)
    - Field name, type, validation
  
  * Scheduled tasks:
    - View cron schedule
    - Run task manually
    - Enable/disable specific tasks
  
  * Import/Export:
    - Export all data (JSON/CSV)
    - Import data (from file)
    - Migration tools

- Filament Widget: Settings Quick Actions
  * Common tasks:
    - Run backup now
    - Clear cache
    - Test email
    - Toggle maintenance mode
    - View logs
    - Update system

- Filament Form: Email Template Editor
  * Template name (text)
  * Subject line (text with variables)
  * Body (rich text editor with variables)
  * Available variables (list with descriptions)
  * Preview with sample data
  * Send test email
  * Reset to default
  * Save as custom

- Filament Table: System Logs
  * Columns: Timestamp, Level, Message, User, IP, Context
  * Filters: Level, Date range, User
  * Actions: View details, Download
  * Auto-refresh (for real-time monitoring)
  * Export logs
```

### **Database Tables**
```sql
- settings
  * id, key (unique), value (json or text)
  * type (string, number, boolean, array, json)
  * category (general, branding, features, etc.)
  * is_encrypted (boolean)
  * description (text)
  * timestamps

- email_templates
  * id, name, slug (unique)
  * subject_template, body_template
  * variables (json - available variables)
  * is_system (boolean - can't delete)
  * is_active (boolean)
  * created_by, updated_by
  * timestamps

- feature_flags
  * id, key (unique), is_enabled (boolean)
  * description, category
  * requires_restart (boolean)
  * allowed_roles (json - who can use when enabled)
  * timestamps

- system_logs
  * id, level (debug, info, warning, error, critical)
  * message (text), context (json)
  * user_id, ip_address
  * url, user_agent
  * created_at

- backups
  * id, filename, file_path, file_size
  * backup_type (full, database, files)
  * status (in_progress, completed, failed)
  * created_by, created_at

- api_keys
  * id, name, key_hash
  * permissions (json)
  * last_used_at, expires_at
  * created_by, timestamps
```

### **Feature Flags System**
```php
// Usage in code:
if (Feature::enabled('projects')) {
    // Show project management features
}

// Role-specific:
if (Feature::enabledFor('ctf_admin', Auth::user())) {
    // Show CTF admin features
}

// Gradual rollout:
if (Feature::percentageEnabled('new_dashboard', 10)) {
    // Show to 10% of users
}
```

### **Automated Processes**
```php
// Jobs/Scheduled Tasks

1. AutoBackupDatabase
   - Run at configured time
   - Create database dump
   - Compress and encrypt
   - Upload to S3 or store locally
   - Rotate old backups
   - Send success/failure notification

2. CleanupOldLogs
   - Run daily
   - Delete logs older than retention period
   - Archive important logs
   - Compress old log files

3. MonitorSystemHealth
   - Run every hour
   - Check disk space
   - Check database size
   - Check failed jobs
   - Check email queue
   - Alert if issues detected

4. OptimizeDatabase
   - Run weekly
   - Optimize tables
   - Rebuild indexes
   - Analyze query performance
   - Suggest improvements

5. PruneOldData
   - Run daily
   - Soft-deleted records older than X days
   - Old notifications
   - Expired sessions
   - Old analytics events

6. SyncExternalServices
   - Sync with Google Calendar
   - Update social media profiles
   - Refresh integration tokens
   - Check webhook status

7. GenerateSystemReport
   - Weekly system health report
   - Usage statistics
   - Performance metrics
   - Security alerts
   - Email to admins
```

### **Watch Out For**

⚠️ **Settings Validation**:
  - Validate all inputs before saving
  - Type checking (email format, URLs, numbers)
  - Range validation (min/max)
  - Required field enforcement
  - Test changes before applying globally

⚠️ **Sensitive Data**:
  - Encrypt API keys, passwords, credentials
  - Never log sensitive data
  - Mask in UI (show last 4 characters)
  - Secure storage (Laravel's encryption)
  - Rotate secrets regularly

⚠️ **Settings Cache**:
  - Cache settings for performance
  - Clear cache when settings change
  - Broadcast changes to all servers (if multiple)
  - Handle cache misses gracefully

⚠️ **Email Template Editing**:
  - Validate variables exist before using
  - Preview before saving
  - Test email sending
  - Keep default templates as backup
  - Version control templates

⚠️ **Feature Flags**:
  - Document each flag clearly
  - Remove flags after full rollout
  - Don't nest flags (complexity)
  - Test both enabled and disabled states

⚠️ **Backup Strategy**:
  - Test restoration process regularly
  - Store backups off-site
  - Encrypt backups
  - Monitor backup success
  - Have backup rotation policy

⚠️ **Maintenance Mode**:
  - Graceful degradation
  - Clear message to users
  - Whitelist admin IPs
  - Set expected downtime
  - Notify users in advance

⚠️ **Permission Checks**:
  - Only super admins access settings
  - Audit log all changes
  - Require confirmation for dangerous actions
  - Two-person rule for critical changes

⚠️ **System Updates**:
  - Test in staging first
  - Backup before updates
  - Plan for rollback
  - Monitor after deployment
  - Communicate changes to users

⚠️ **Performance Impact**:
  - Settings changes shouldn't require restart
  - Cache aggressively
  - Lazy load settings when possible
  - Monitor query performance

⚠️ **Default Values**:
  - Sensible defaults for all settings
  - Document recommended values
  - Validation that prevents breaking configurations
  - Reset to defaults option

---

Would you like me to continue with Phase 15 (Search & Advanced Filtering System) and Phase 16 (Mobile App Considerations & API)?
