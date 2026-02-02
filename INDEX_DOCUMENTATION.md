# FILE DOCUMENTATION: index.php (A-Z Deep Dive)
## GamePlan Scheduler - Main Dashboard

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `index.php` | **Total Lines**: 305

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\index.php`
**Purpose**: The central hub (Dashboard) where the user sees everything: Friends, Favorite Games, Schedules, and Events.
**Key Feature**: Aggregates data from 4 different database tables into one cohesive view.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Initialization (Lines 26-58)

1.  **Auth Check**: Redirects to `login.php` if not logged in.
2.  **Activity Tracking**: Calls `updateLastActivity()` to keep the session alive.
3.  **Data Fetching**:
    ```php
    $friends = getFriends($userId);
    $favorites = getFavoriteGames($userId);
    $schedules = getSchedules($userId, $sortSchedules);
    $events = getEvents($userId, $sortEvents);
    ```
4.  **Logout Handling**: If URL has `?logout=1`, call `logout()`.

## SECTION 2: Sorting Logic (Lines 44-51)

```php
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
```
*   **Mechanism**: The user clicks a link like `index.php?sort_schedules=date DESC`.
*   **Result**: The `$sortSchedules` variable captures this, and passes it to `getSchedules()`, which adjusts the SQL `ORDER BY` clause.

## SECTION 3: The 5 Display Sections

### 1. Friends List (Lines 80-116)
*   Displays table of friends with status badges.
*   "Green" badge for Online, "Grey" for Offline.

### 2. Favorite Games (Lines 121-153)
*   Table showing games the user plays.
*   Includes "Edit" and "Delete" buttons for quick management.

### 3. Schedules (Lines 158-198)
*   Shows planned gaming sessions.
*   **Sorting Controls**: Up/Down arrows (`↑` `↓`) allow sorting by Date.

### 4. Events (Lines 203-249)
*   Shows tournaments/streams.
*   **External Links**: Checks `if (!empty($external_link))` before showing the "🔗 Open" button.

### 5. Calendar Overview (Lines 254-288) (CRITICAL)
*   **Unique Feature**: Merges Schedules and Events into chronological cards.
*   **Visuals**: Uses Bootstrap `card` components for a modern look.
*   **Logic**: Iterates over `$calendarItems` (prepared in `functions.php`).

---

# 3. JavaScript Integration (Lines 298-303)

```javascript
const reminders = <?php echo json_encode($reminders); ?>;
reminders.forEach(reminder => {
    alert(...);
});
```
*   **Purpose**: Immediate Browser Alert if an event is starting soon.
*   **Mechanism**: PHP passes the reminder array to JavaScript using `json_encode`.
*   **UX**: This ensures the user sees important notifications upon logging in or refreshing.

---

**END OF FILE DOCUMENTATION**
