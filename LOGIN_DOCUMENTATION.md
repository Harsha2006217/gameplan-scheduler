# FILE DOCUMENTATION: login.php (A-Z Deep Dive)
## GamePlan Scheduler - Authentication Entry Point

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `login.php` | **Total Lines**: 227

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\login.php`
**Purpose**: The gateway to the application. It authenticates users by checking credentials against the database.
**Distinct Features**:
*   Uses `POSt` method for security.
*   Includes a "Glassmorphism" UI container (`.auth-container`).
*   Implements Javascript Pre-validation (`validateLoginForm`).

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Pre-Checks (Lines 24-38)

```php
require_once 'functions.php';
if (isLoggedIn()) { header("Location: index.php"); exit; }
```
**Optimized UX**:
*   If I am already logged in and I type "login.php", I shouldn't see the login form.
*   The script detects my active session and immediately bounces me to the Dashboard.

## SECTION 2: Form Processing (Lines 51-70)

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    // ...
    $error = loginUser($email, $password);
}
```
*   **Encapsulation**: The actual logic (SQL query, password verification) is hidden inside `loginUser()` in `functions.php`.
*   **Benefit**: This keeps `login.php` clean and focused on *Presentation* (HTML), while `functions.php` handles *Data*.

## SECTION 3: HTML Structure (Lines 109-208)

### The Container
```html
<div class="auth-container">
```
*   This CSS class (defined in `style.css`) adds the semi-transparent box, shadow, and rounded corners which gives the "Gaming/Modern" vibe.

### Input Fields
*   **Email**: `type="email"`. Browser automatically warns if you type text without an '@'.
*   **Password**: `type="password"`. Masks characters (`••••••`).

### JavaScript Hook (Line 140)
```html
<form method="POST" onsubmit="return validateLoginForm();">
```
*   **Purpose**: Prevents the form from even being sent to the server if fields are empty. Saves server resources.

---

# 3. Security Highlights

1.  **POST Method**: Credentials are sent in the HTTP Request Body, not in the URL bar (unlike GET).
    *   *GET would show: `login.php?password=secret` (TERRIBLE!)*.
2.  **Error Messages**: Stores errors in `$error` variable and displays them in a red alert box (`alert-danger`) right above the form.

---

**END OF FILE DOCUMENTATION**
