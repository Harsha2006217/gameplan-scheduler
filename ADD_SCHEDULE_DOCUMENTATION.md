# FILE DOCUMENTATION: add_schedule.php (A-Z Deep Dive)
## GamePlan Scheduler - Add Schedule Page Logic

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `add_schedule.php` | **Total Lines**: 132

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\add_schedule.php`
**Purpose**: Allows logged-in users to create a new gaming session (schedule).
**Primary Function**: Links a specific game to a specific date and time, and optionally lists friends who are joining.

**Key Dependencies**:
*   `functions.php` (contains `addSchedule` logic)
*   `script.js` (contains `validateScheduleForm`)
*   `db.php` (Database connection via functions)

**User Story**: "As a user, I want to schedule a gaming session so my friends know when I am playing."

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Authentication & Setup (Lines 1-31)

```php
require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) { header("Location: login.php"); ... }
$userId = getUserId();
```

**Logic Explained**:
1.  **Security First**: Identical to `add_event.php`, this blocks non-logged-in users immediately.
2.  **Session Management**: Ensures the session is valid and active (`checkSessionTimeout`).

---

## SECTION 2: Form Handling (Lines 34-48)

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect 5 inputs
    $error = addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);
    
    if (!$error) {
        setMessage('success', 'Schedule added!');
        header("Location: index.php");
    }
}
```

**Function Call Breakdown**:
*   `addSchedule(...)` is the workhorse here.
*   **Parameters**:
    1.  `$userId`: Who created this?
    2.  `$gameTitle`: "Fortnite", "Minecraft", etc.
    3.  `$date`: YYYY-MM-DD string.
    4.  `$time`: HH:MM string.
    5.  `$friendsStr`: Comma-separated string ("Tom, Jerry").
    6.  `$sharedWithStr`: Visibility settings.

---

## SECTION 3: HTML Form Structure (Lines 74-121)

**Validation Hook**: `onsubmit="return validateScheduleForm();"`
*   Calls the specific JavaScript function designed for schedules.

### Field Analysis:

#### A. Game Title (Lines 77-83)
```html
<label>🎮 Game Title *</label>
<input type="text" id="game_title" required maxlength="100">
<small>BUG FIX #1001</small>
```
*   **Purpose**: The name of the game.
*   **Validation**: `required`, `maxlength`.
*   **Bug #1001**: Prevents users from entering "   " (spaces) as a game name.

#### B. Date Input (Lines 86-92)
```html
<label>📆 Date *</label>
<input type="date" id="date" required min="<?php echo date('Y-m-d'); ?>">
<small>BUG FIX #1004</small>
```
*   **Dynamic Restriction**: `min="<?php echo date('Y-m-d'); ?>"`
*   **Logic**: PHP calculates today's date (e.g., "2026-02-02") and puts it in the HTML `min` attribute.
*   **Result**: The calendar picker in Chrome/Edge disallows selecting yesterday.

#### C. Time Input (Lines 95-98)
*   `type="time"`: Provides a native clock interface.
*   **Format**: Returns "14:30" (24-hour format) to the server.

#### D. Friends Joining (Lines 101-108)
```html
<input type="text" id="friends_str" placeholder="player1, player2">
```
*   **Free Text**: Users type names manually.
*   **Validation**: Checked by `validateCommaSeparated` in PHP and regex in JS.

---

# 3. Validation Logic Map

This page uses a **3-Layer Validation Strategy**:

| Layer | Where? | What it checks |
|-------|--------|----------------|
| **1. HTML5** | `add_schedule.php` | `required`, `min="today"`, `maxlength` |
| **2. JavaScript** | `script.js` (`validateScheduleForm`) | Spaces check (#1001), Future Date (#1004), Time Format |
| **3. PHP** | `functions.php` (`addSchedule`) | Final integrity check before `INSERT`. |

---

# 4. Security Measures

1.  **Auth Check**: `isLoggedIn()` protects the page.
2.  **`safeEcho($error)`**: Line 67 prevents XSS if the error message contains user input.
3.  **Prepared Statements**: The underlying `addSchedule` function uses PDO prepared statements to prevent SQL Injection when inserting the game title and friend names.

---

# 5. Difference vs. Events

*   **Schedules** are for *playing games* ("I am playing FIFA tonight").
*   **Events** are for *happenings* ("FIFA Tournament is watching stream").
*   `add_schedule.php` is simpler; it doesn't have a "Description" or "External Link" field, but it has a "Friends Joining" field which Events do not have directly in the same way.

---

**END OF FILE DOCUMENTATION**
