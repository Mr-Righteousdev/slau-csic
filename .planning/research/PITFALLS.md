# Domain Pitfalls: Event System, RSVP, and Recurring Events

**Domain:** Laravel Event Systems, Event Registration (RSVP), Calendar/Recurring Events
**Researched:** 2026-04-25
**Confidence:** HIGH

Critical mistakes that cause rewrites, data corruption, or production incidents.

---

## Critical Pitfalls

### 1. Queued Listeners Execute Before Transaction Commits

**What goes wrong:** Queued event listeners run immediately after the event is dispatched, not after the database transaction commits. If the transaction rolls back, the listener still executes against non-existent or reverted data.

**Why it happens:** Laravel queues dispatch jobs immediately. The default behavior does not wait for `DB::commit()`. This is especially problematic with model events (`created`, `updated`, `deleted`) that trigger listeners expecting the data to be persisted.

**Consequences:**
- Listener attempts to process non-existent records
- N+1 queries fail because related models do not exist
- Email notifications sent for orders that were never placed
- Race conditions between listeners and transaction state

**Prevention:**
- Use `ShouldHandleEventsAfterCommit` interface on queued listeners
- Alternatively, wrap dispatch in `DB::afterCommit()` callback
- For critical listeners, dispatch from the controller/service after successful commit

**Detection:**
```php
// WRONG - listener may run before commit
event(new OrderPlaced($order));

// CORRECT - using ShouldHandleEventsAfterCommit
class SendOrderConfirmation implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    // ...
}

// OR wrap in afterCommit
DB::transaction(function () {
    $order = Order::create([...]);
    event(new OrderPlaced($order));
});
```

---

### 2. Race Conditions in RSVP/Limited-Capacity Events

**What goes wrong:** Multiple users RSVP simultaneously for an event with limited capacity. Without proper concurrency control, you accept more RSVPs than capacity allows (overselling) or reject valid RSVPs due to check-then-act patterns.

**Why it happens:**
- Reading capacity, checking in application code, then writing
- Relying on database isolation (`READ_COMMITTED`) to prevent race conditions
- Not using any locking mechanism
- No uniqueness constraint on (event_id, user_id)

**Consequences:**
- Overselling events beyond capacity
- Double RSVPs from same user
- Angry users who cannot attend after confirming
- Data integrity violations

**Prevention:**
- Add database uniqueness constraint: `unique constraint on (event_id, user_id)`
- Use pessimistic locking (`SELECT ... FOR UPDATE`) for capacity checks
- Use atomic database operations: `UPDATE events SET rsvp_count = rsvp_count + 1 WHERE rsvp_count < capacity`
- For high-contention events, consider Redis-based reservation system with Lua scripts

**Detection:**
- Monitor `rsvp_count` vs `capacity` in production
- Log and alert on oversell conditions
- Run load tests simulating concurrent RSVPs

---

### 3. DST and Timezone Mismatches in Recurring Events

**What goes wrong:** Events scheduled as "every Monday at 9 AM" shift by an hour when Daylight Saving Time begins or ends. Events stored as UTC show wrong local times. Events crossing DST boundaries display incorrectly.

**Why it happens:**
- Storing events as UTC without recording the event's intended timezone
- Using fixed offsets (`-05:00`) instead of IANA identifiers (`America/New_York`)
- Using PHP `DateTime` without timezone awareness
- Not expanding recurring events into specific instances for each occurrence

**Consequences:**
- Events display wrong times to users
- DST transition days cause confusion (2 AM does not exist, or 1 AM exists twice)
- Recurring meetings shift unexpectedly
- Calendar export (ICS) shows incorrect times

**Prevention:**
- Always use IANA timezone identifiers (e.g., `America/New_York`, not `EST`)
- Store the event's timezone separately from the UTC timestamp
- Use recurrence rule (RRULE) storage, expand on read using a library like `rrule.js` or `rical/recurrence`
- For ICS export, include proper VTIMEZONE components per RFC 5545
- Test with dates around DST transitions (US: March 10, November 3)

**Detection:**
- User complaints about wrong event times after DST change
- ICS files fail in Microsoft New Outlook (strict RFC 5545 validation as of 2025)

---

### 4. Passing Entire Models to Event Payloads

**What goes wrong:** Events carry full Eloquent models. When listeners serialize/unserialize (queued listeners), or when models are accessed in loops, you get N+1 queries, stale data, and serialization failures.

**Why it happens:**
- Convenience: `$event = new MyEvent($order)` where `$order` is a full model
- Not considering that listeners may queue, serialize, or eager-load relationships
- Circular serialization references in relationships

**Consequences:**
- N+1 query problems when listeners access relationships
- Serialization errors with unserializable properties (file handles, closures)
- Stale data: listener operates on model state from dispatch time, not when it processes
- Memory bloat: passing large models through event bus

**Prevention:**
- Pass only IDs or data arrays: `$event = new MyEvent(['order_id' => $order->id, 'email' => $order->user->email])`
- Have listeners fetch models fresh: `Order::find($event->orderId)->notify()`
- Use data transfer objects (DTOs) that contain only serializable primitives

**Detection:**
- Query logs showing repeated queries for same model
- Serialization exceptions in queue logs
- Memory spikes when processing events

---

### 5. Model Events Contain Business Logic

**What goes wrong:** Eloquent model events (`created`, `updated`, `deleted`) contain critical business logic. These events fire from anywhere—seeders, API imports, tests, console commands—often unexpectedly.

**Why it happens:**
- Model events are convenient: logic lives right next to the model
- Seem like the "Laravel way" for simple hooks
- Not realizing they fire from all contexts

**Consequences:**
- Unexpected behavior in data migrations
- Logic runs in seeders when you only wanted test data
- Difficult to test: cannot easily fake or suppress model events
- Logic hidden in model, not visible to developers
- Side effects during bulk operations

**Prevention:**
- Use explicit events: `$order->markAsPlaced()` dispatches `OrderPlaced` event
- Keep model events for infrastructure only (logging, caching)
- Use Service Actions or Commands for business workflows
- Document what triggers model events

**Detection:**
- Unexpected emails during data seeding
- Side effects running in console commands unexpectedly

---

## Moderate Pitfalls

### 6. Silent Listener Failures

**What goes wrong:** Queued listeners fail but failures go unnoticed. No failed job handling, no retries, no alerting.

**Why it happens:**
- Not implementing the `failed()` method on queued listeners
- Not monitoring the `failed_jobs` table
- Assuming queue workers always succeed

**Consequences:**
- Users not notified of important events
- Data inconsistencies (e.g., webhook not recorded, but order created)
- No visibility into system health

**Prevention:**
```php
class SendOrderConfirmation implements ShouldQueue
{
    public function failed(OrderPlaced $event, Throwable $exception): void
    {
        // Log, notify, or create retry job
        Log::error('Order confirmation failed', [
            'order_id' => $event->orderId,
            'error' => $exception->getMessage()
        ]);
    }
}
```
- Monitor failed_jobs table with health checks
- Set appropriate retry attempts

---

### 7. Circular Event Dependencies

**What goes wrong:** Listener A handles Event A and dispatches Event B. Listener B handles Event B and dispatches Event A. Infinite loops or stack overflow.

**Why it happens:**
- Not tracking event flow
- Complex event chains without documentation
- Adding listeners without understanding full event graph

**Prevention:**
- Document event dispatch relationships
- Use event naming conventions: `OrderPlaced`, `UserRegistered`
- Add loop detection in listeners if complex chains exist

---

### 8. Event Payload Changes Break Listeners

**What goes wrong:** You modify an event class (add/remove properties). Existing listeners break silently because they expect specific data.

**Why it happens:**
- Events as classes have no contract enforcement
- Versioning not considered
- Listeners tightly coupled to event structure

**Prevention:**
- Treat events as immutable contracts
- Add version field to event payloads
- Document event structure changes
- Use event versioning or migration listeners

---

### 9. RRULE Implementation Complexity

**What goes wrong:** Recurring event rules (RRULE) are deceptively complex. Implementing from scratch leads to edge case bugs: BYDAY handling, INTERVAL combinations, UNTIL vs COUNT, EXDATE exceptions.

**Why it happens:**
- RRULE specification (RFC 5545) is extensive
- "Every second Tuesday" is `BYDAY=2TU`, not obvious
- Edge cases like "skip DST transitions" or "except holidays"
- Not using battle-tested libraries

**Prevention:**
- Use libraries like `rlanvin/php-rrule` or JavaScript `rrule.js`
- Store the RRULE string directly, expand on read
- For ICS export, generate proper VTIMEZONE components
- Test with complex recurrence patterns

---

## Minor Pitfalls

### 10. Over-Emitting Events

**What goes wrong:** Firing events for trivial state changes that no one listens to. Adds overhead without value.

**Prevention:** Only emit events that have actual listeners or planned listeners.

### 11. Event Naming in Wrong Tense

**What goes wrong:** Events named as actions (`CreateUser`, `UpdateOrder`) instead of past tense results (`UserCreated`, `OrderUpdated`). Leads to confusion about when event fires.

**Prevention:** Use past tense: `OrderPlaced`, `PaymentReceived`, `RsvpConfirmed`.

### 12. Not Caching Event Discovery

**What goes wrong:** In production, Laravel scans for event-listener mappings on every request, adding overhead.

**Prevention:** Run `php artisan event:cache` in deployment, `php artisan event:clear` during development.

---

## Phase-Specific Warnings

| Phase Topic | Likely Pitfall | Mitigation |
|-------------|---------------|------------|
| RSVP system implementation | Race conditions | Use DB constraints + pessimistic locking from start |
| Calendar display | DST/timezone | Use IANA IDs, store timezone metadata, test DST dates |
| Event notifications | Silent failures | Implement `failed()` method, monitor queue |
| Model events refactor | Hidden business logic | Replace model events with explicit events in service layer |
| Recurring events | RRULE complexity | Use library, not custom implementation |
| Event testing | No isolation | Use `Event::fake()` in tests |
| ICS export | RFC 5545 violations | Validate with strict parsers, include VTIMEZONE |

---

## Summary

| Pitfall | Severity | Preventable With |
|---------|----------|------------------|
| Queued listeners before commit | Critical | `ShouldHandleEventsAfterCommit` |
| RSVP race conditions | Critical | DB constraints + atomic ops |
| DST/timezone errors | Critical | IANA IDs + recurrence library |
| Model event business logic | Critical | Explicit events from actions |
| Silent listener failures | Moderate | `failed()` method + monitoring |
| Event payload changes | Moderate | Versioning + documentation |
| RRULE complexity | Moderate | Battle-tested library |

---

## Sources

- [Laravel Events Documentation](https://laravel.com/docs/11.x/events) — HIGH confidence, official documentation
- [Laravel Events & Listeners: Clean Patterns](https://maxw3ll.com/blog/laravel-series/laravel-events-listeners-clean-patterns-after-commit-queues-and-monitoring) — HIGH confidence, 2025 expert guide
- [Timezone Bugs in Calendar Integration](https://add-to-calendar-pro.com/articles/timezone-bug-haunts-calendar-integration-453e1126) — MEDIUM confidence, industry analysis
- [Building a Ticketing System: Concurrency](https://blog.devgenius.io/building-a-ticketing-system-concurrency-locks-and-race-conditions-a8e0be4be993) — MEDIUM confidence, technical deep-dive
- [Fix waitlist race conditions - Hi.Events](https://github.com/HiEventsDev/Hi.Events/pull/1072) — HIGH confidence, real production fix
- [Laravel Common Mistakes](https://laravel.io/articles/common-laravel-mistakes-i-see-in-production-and-how-to-avoid-them) — MEDIUM confidence, production experience
- [DST Calendar Bugs](https://add-to-calendar-pro.com/articles/event-promotion-daylight-saving-time-calendar-bug-453e859f) — MEDIUM confidence, industry analysis