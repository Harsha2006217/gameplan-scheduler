# FILE DOCUMENTATION: add_event.php (A-Z Deep Dive)
## GamePlan Scheduler - Add Event Page Logic

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `add_event.php` | **Total Lines**: 147

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\add_event.php`
**Purpose**: Allows logged-in users to create new gaming events (tournaments, streams, LAN parties).
**Key Dependencies**:
*   `functions.php` (Logic)
*   `db.php` (Database)
*   `header.php` / `footer.php` (UI)
*   `script.js` (Client Validation)

**Key Features**:
1.  **Session Protection**: Only logged-in users can access.
2.  **Double Validation**: Client-side (JS) AND Server-side (PHP).
3.  **Correct Bug Fixes**: Implements strict checks for empty spaces (#1001) and dates (#1004).

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Header & Authentication (Lines 1-30)

```php
require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
```

**Logic Explained**:
1.  **Dependency**: Loads `functions.php` which brings in `db.php` and helper functions.
2.  **Timeout**: `checkSessionTimeout()` ensures the user hasn't been idle (security).
3.  **Access Control**: If `!isLoggedIn()`, instantly redirect to login page. This prevents unauthorized access.
4.  **User ID**: `$userId = getUserId();` grabs the ID from the session to link the new event to the correct user.

---

## SECTION 2: Form Processing (Lines 34-50)

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get inputs...
    $error = addEvent($userId, $title, $date, ...);
    
    if (!$error) {
        // Success!
        header("Location: index.php");
    }
}
```

**Logic Explained**:
1.  **Input Gathering**: Collects all POST data (`title`, `date`, `time`, etc.).
2.  **Core Function Call**: Calls `addEvent()` in `functions.php`.
    *   *This function inside `functions.php` validates everything again (Server-Side Layer).*
3.  **Error Handling**: If `addEvent` returns a string, it's an error message. If it returns `null` or `false` (empty error), it succeeded.
4.  **Redirect**: On success, sends user to Dashboard (`index.php`).

---

## SECTION 3: HTML Structure & Meta (Lines 52-61)

**Logic Explained**:
*   Sets up standard HTML5 structure.
*   Includes **Bootstrap 5 CDN** for styling.
*   Includes local `style.css` for custom gaming theme.

---

## SECTION 4: The Form (Lines 76-136)

**Validation Attribute**: `onsubmit="return validateEventForm();"`
*   *Before* the data goes to PHP, the JavaScript function `validateEventForm()` runs.
*   If JS returns `false`, the form **never submits**. (First line of defense).

### Form Fields Breakdown:

#### A. Title Input (Lines 79-84)
```html
<input type="text" id="title" required maxlength="100">
<small>Max 100 characters, cannot be empty (BUG FIX #1001)</small>
```
*   **HTML Validation**: `required` and `maxlength="100"`.
*   **Bug #1001**: JS/PHP will check if it's just spaces.

#### B. Date Input (Lines 87-92)
```html
<input type="date" id="date" required min="<?php echo date('Y-m-d'); ?>">
```
*   **HTML Validation**: `min="<?php echo date('Y-m-d'); ?>"`
    *   This dynamic PHP code effectively sets the minimum selectable date to **TODAY**.
    *   Browsers will grey out past dates.
*   **Bug #1004**: JS/PHP will double-check this for strictness.

#### C. Time Input (Lines 95-98)
*   Uses `type="time"`. Browsers show a clock picker.

#### D. Description (Lines 101-106)
*   `textarea` for longer text.
*   Restricted to 500 chars.

#### E. External Link (Lines 119-124)
*   `type="url"`. Browsers will demand `http://` protocol.

#### F. Buttons (Lines 133-134)
*   **Submit**: Green (`btn-success`), triggers logic.
*   **Cancel**: Grey (`btn-secondary`), links back to `index.php`.

---

# 3. Validation Logic Map

This page uses a **3-Layer Validation Strategy**:

| Layer | Where? | What it checks |
|-------|--------|----------------|
| **1. HTML5** | `add_event.php` (Lines 80-130) | `required`, `type="date"`, `min="2026-..."`, length |
| **2. JavaScript** | `script.js` (called at Line 76) | Regex for spaces (#1001), exact date math (#1004), URL strictness |
| **3. PHP** | `functions.php` (called at Line 43) | Final security check before SQL INSERT. Prevents bad data from tools like Postman. |

---

# 4. Security Measures in this File

1.  **Auth Check (`isLoggedIn`)**: Prevents "URL hacking" where users type `add_event.php` directly without logging in.
2.  **`checkSessionTimeout()`**: Logs out idle users.
3.  **`safeEcho($error)` (Line 69)**:
    *   If an error comes back (e.g., "Invalid title <script>..."), `safeEcho` runs `htmlspecialchars()`.
    *   **Prevents XSS attacks** (Cross-Site Scripting).
4.  **Default Values**: `$_POST['reminder'] ?? 'none'` prevents warnings if fields are missing.

---

**END OF FILE DOCUMENTATION**
