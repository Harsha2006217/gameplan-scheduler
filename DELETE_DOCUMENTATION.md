# FILE DOCUMENTATION: delete.php (A-Z Deep Dive)
## GamePlan Scheduler - Deletion Handler Logic

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `delete.php` | **Total Lines**: 99

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\delete.php`
**Purpose**: A centralized handler script for deleting items (`schedule`, `event`, `favorite`, `friend`).
**Key Concept**: **Dynamic Routing**. Instead of having `delete_schedule.php`, `delete_event.php`, etc., one file handles all deletions based on URL parameters.

**Does it actually delete?**
*   **No**: It uses **SOFT DELETE**.
*   **Mechanism**: It updates the record to set `deleted_at = NOW()`.
*   **Result**: The user *sees* it as gone, but the database keeps the record for recovery/audit.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Access Control (Lines 1-34)

```php
require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) { header("Location: login.php"); ... }
```
**Logic Explained**:
1.  **Strict Security**: Deleting data is sensitive. No guest access allowed.
2.  **Dependencies**: Pulls in `functions.php` where the actual SQL `UPDATE` logic resides.

---

## SECTION 2: Request Parsing (Lines 36-42)

```php
$type = $_GET['type'] ?? ''; 
$id   = $_GET['id']   ?? 0;
$userId = getUserId();
```

**How it works**:
*   The script expects a URL like: `delete.php?type=schedule&id=42`
*   `$_GET['type']` captures "schedule".
*   `$_GET['id']` captures "42".
*   `$_GET['...'] ?? ''` handles cases where parameters are missing (avoids PHP warnings).

---

## SECTION 3: The Switch Logic (Lines 51-75)

This helper determines **which** function to call and **where** to send the user afterwards.

### A. Schedules
```php
if ($type == 'schedule') {
    $error = deleteSchedule($userId, $id);
    $redirect = 'index.php'; // Back to Dashboard
}
```

### B. Events
```php
elseif ($type == 'event') {
    $error = deleteEvent($userId, $id);
    $redirect = 'index.php';
}
```

### C. Favorites
```php
elseif ($type == 'favorite') {
    $error = deleteFavoriteGame($userId, $id);
    $redirect = 'profile.php'; // Back to Profile
}
```

### D. Friends
```php
elseif ($type == 'friend') {
    $error = deleteFriend($userId, $id);
    $redirect = 'add_friend.php'; // Back to Friend List
}
```

### E. Invalid (Security Fallback)
```php
else {
    $error = 'Invalid type.';
    $redirect = 'index.php';
}
```
*   **Defense**: If a hacker tries `delete.php?type=admin`, they hit this block and nothing happens.

---

## SECTION 4: Feedback & Redirect (Lines 83-92)

```php
if ($error) {
    setMessage('danger', $error);
} else {
    // e.g. "Schedule deleted successfully!"
    setMessage('success', ucfirst($type) . ' deleted successfully!');
}
header("Location: " . $redirect);
exit;
```

**Logic Explained**:
1.  **Success/Error**: Checks if the delete function returned an error string.
2.  **`ucfirst($type)`**: Makes the message look professional ("Schedule" vs "schedule").
3.  **`header(...)`**: Sends the browser to the `$redirect` page decided above.

---

# 3. Security Analysis (CRITICAL)

**Q: Can I delete someone else's schedule by changing the ID?**
*   **Attack**: `delete.php?type=schedule&id=999` (where 999 belongs to User B).
*   **Defense**: Inside `deleteSchedule($userId, $id)` (in functions.php), there is a SQL check:
    ```sql
    WHERE schedule_id = :id AND user_id = :userId
    ```
*   **Result**: The logic **forces** the `user_id` to match the logged-in user. If you try to delete ID 999, the query finds nothing (because ID implies User B, but constraint demands User A). The operation fails silently or returns "Not found/Owned".

---

# 4. Data Flow Description

1.  **User** clicks "Delete" button on Dashboard.
2.  **JS** shows "Are you sure?" (client-side).
3.  **User** clicks OK.
4.  **Browser** goes to `delete.php?type=schedule&id=10`.
5.  **PHP**:
    *   Checks login.
    *   Calls `deleteSchedule(10)`.
    *   `deleteSchedule` updates row 10 to `deleted_at = NOW()`.
6.  **PHP** redirects to `index.php`.
7.  **Dashboard** reloads. The query `WHERE deleted_at IS NULL` now hides row 10.
8.  **User** sees green banner: "Schedule deleted successfully!".

---

**END OF FILE DOCUMENTATION**
