# FILE DOCUMENTATION: functions.php (A-Z Deep Dive)
## GamePlan Scheduler - The Brain of the Application

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `functions.php` | **Total Lines**: 672

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\functions.php`
**Purpose**: This is the **Core Function Library**. It contains:
1.  **Session Management**: Login status, timeouts.
2.  **Validation**: Strict checking of all inputs (Dates, Emails, Text).
3.  **CRUD Operations**: Create, Read, Update, Delete logic for all entities.
4.  **Security**: XSS prevention, SQL Injection prevention.

**Architecture Note**: This file isolates the "Business Logic" from the "Presentation Logic" (HTML files like index.php). This is a professional "Separation of Concerns".

---

# 2. Key Logical Sections

## SECTION 1: System & Security Helpers (Lines 17-171)

### `safeEcho($string)` (Line 50)
*   **Purpose**: Prevents Cross-Site Scripting (XSS).
*   **How**: Converts `<script>` to `&lt;script&gt;`.
*   **Usage**: Wraps EVERY variable output in HTML files.

### `validateRequired($value, ...)` (Line 68)
*   **BUG FIX #1001**: Uses `preg_match('/^\s*$/', ...)` to block inputs that are just spaces.
*   **Why**: A user named "   " broke the layout in early testing.

### `validateDate($date)` (Line 97)
*   **BUG FIX #1004**: Uses `DateTime::createFromFormat` for strict validation.
*   **Logic**:
    1.  Parse format YYYY-MM-DD.
    2.  Check for logic errors (e.g., 2025-02-30 -> Feb 30th doesn't exist).
    3.  Check if date is in the **future**.

---

## SECTION 2: Authentication (Lines 207-328)

### `checkSessionTimeout()` (Line 239)
*   **Rule**: If user is inactive for 30 minutes (1800 seconds), log them out.
*   **Security**: Prevents "forgotten" sessions on public computers.

### `registerUser(...)` (Line 254)
*   **Hashing**: `password_hash($password, PASSWORD_BCRYPT)`.
*   **Security Check**: Verified that the email doesn't already exist before inserting.

---

## SECTION 3: Game Management (Lines 338-435)

### `getOrCreateGameId(...)` (Line 338)
*   **Smart Logic**:
    1.  User types "Minecraft".
    2.  Script checks: Does "Minecraft" exist in `Games` table?
    3.  **Yes**: Return ID `5`.
    4.  **No**: Insert "Minecraft" -> Return new ID `6`.
*   **Benefit**: Prevents duplicate game entries ("Minecraft" and "minecraft").

---

## SECTION 4: CRUD Operations (Lines 441-634)

Common Pattern for all `add/edit/delete` functions:
1.  **DB Connection**: `$pdo = getDBConnection()`.
2.  **Validation**: Call `validateRequired`, `validateDate`, etc. If any fail, return error string immediately.
3.  **Ownership (Edit/Delete)**: `$stmt = ... WHERE user_id = :userId`. Checks if you own the item.
4.  **Execution**: Run the `INSERT/UPDATE` query.

### `getCalendarItems($userId)` (Line 647)
*   **Purpose**: Merges `Schedules` + `Events` into one chronological list for the Dashboard.
*   **Logic**:
    1.  Get Schedules.
    2.  Get Events.
    3.  `array_merge`.
    4.  `usort`: Sort by Date/Time string.

---

# 3. Security Checklist (Exam Prep)

1.  **SQL Injection**: Prevented by using **Prepared Statements** (`:value`) everywhere.
    *   *Bad*: `SELECT * FROM Users WHERE name = '$name'`
    *   *Good*: `SELECT * FROM Users WHERE name = :name` (Used in `functions.php`).
2.  **XSS**: Prevented by `safeEcho()`.
3.  **Session Hijacking**: Prevented by `session_regenerate_id(true)` (Line 36 & 314).
4.  **CSRF**: (Basic protection via Session checks, though explicit CSRF tokens are a future improvement).

---

**END OF FILE DOCUMENTATION**
