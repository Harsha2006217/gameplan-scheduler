# FILE DOCUMENTATION: register.php (A-Z Deep Dive)
## GamePlan Scheduler - New User Registration

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `register.php` | **Total Lines**: 148

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\register.php`
**Purpose**: Allows new users to sign up.
**Security Critical**: This is where we must enforce password strength rules and prevent duplicate accounts.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Already Registered? (Lines 26-30)

```php
if (isLoggedIn()) { header("Location: index.php"); exit; }
```
*   **UX Rule**: Don't show a registration form to someone who is already logged in. Redirect them to the Dashboard instead.

## SECTION 2: Processing Logic (Lines 35-49)

```php
$error = registerUser($username, $email, $password);
```
*   **Delegation**: We send the raw inputs to `registerUser` in `functions.php`.
*   **What happens in functions.php?**:
    1.  **Duplicate Check**: `SELECT COUNT(*) WHERE email = ...`
    2.  **Hashing**: `password_hash()` creates a secure bcrypt hash.
    3.  **Insert**: Saves the new user.
*   **On Success**: The user is redirected to `login.php` with a green "Success" message.

## SECTION 3: Frontend Validation (Bug #1001 Fix)

```html
<form method="POST" onsubmit="return validateRegisterForm();">
```
*   **Javascript Connection**: Calls `validateRegisterForm()` in `script.js`.
*   **The Check**: Specifically checks if Username is empty OR contains only spaces (Regex: `^\s*$`).
*   **Why?**: A user named " " (space) is invisible in the Friends list, which is a bad user experience. We block this here.

---

# 3. Security Highlights

**Why Minimum 8 Characters?**
*   Enforced in both HTML (`minlength="8"`, Line 121) AND PHP (`strlen < 8`, functions.php Line 265).
*   **Defense in Depth**: Even if a hacker removes the HTML attribute using DevTools, the PHP check will still block the weak password.

---

**END OF FILE DOCUMENTATION**
