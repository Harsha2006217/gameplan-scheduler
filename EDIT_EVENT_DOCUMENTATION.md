# FILE DOCUMENTATION: edit_event.php (A-Z Deep Dive)
## GamePlan Scheduler - Edit Event Handler

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `edit_event.php` | **Total Lines**: 141

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\edit_event.php`
**Purpose**: Allows users to modify details of an *existing* event.
**Key Difference from Add**:
*   Requires an `id` parameter in the URL.
*   Pre-fills the form with existing database values.
*   Performs an `UPDATE` SQL operation instead of `INSERT`.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Initialization & Retrieval (Lines 1-38)

```php
$id = $_GET['id'] ?? 0; // Get ID from URL
$events = getEvents($userId); // Get all user's events
// Filter to find the specific event
$event = array_filter($events, function ($e) use ($id) { ... });
$event = reset($event); // Get first match
```
**Logic Explained**:
1.  **Security**: We fetch *only* the logged-in user's events first.
2.  **Filtering**: We search that list for the requested ID.
3.  **Ownership Check**: If the ID isn't in the user's list, `$event` becomes null (or empty), triggering the "Event not found" error. This prevents editing someone else's event.

## SECTION 2: Form Pre-filling (Lines 83-127)

**Critical Concept**: The form must show the *current* values so the user can see what they are changing.

### Example: Title Field
```php
value="<?php echo safeEcho($event['title']); ?>"
```
*   **`value="..."`**: Sets the input's content.
*   **`safeEcho`**: Prevents XSS if the title contained malicious code.

### Example: Reminder Dropdown Logic (Lines 106-116)
```php
<option value="none" <?php if ($event['reminder'] === 'none') echo 'selected'; ?>>None</option>
```
*   **Conditional Selection**: Checks if the database value matches the option value.
*   **Result**: The dropdown automatically selects the correct saved setting.

## SECTION 3: Update Processing (Lines 42-58)

```php
$error = editEvent($userId, $id, $title, ...);
```
*   Calls `editEvent()` in `functions.php`.
*   Passes both `$userId` (security) and `$id` (target).
*   If successful, redirects to Dashboard with a success message.

---

# 3. Security Analysis

**Q: Can I change the ID in the hidden field to hijack an event?**
*   **Defense**: The ID is passed to `editEvent($userId, $id, ...)` in `functions.php`. That function executes:
    `UPDATE Events SET ... WHERE event_id = :id AND user_id = :userId`
*   **Result**: Even if a hacker hacks the form, the database query forces the ownership check.

---

**END OF FILE DOCUMENTATION**
