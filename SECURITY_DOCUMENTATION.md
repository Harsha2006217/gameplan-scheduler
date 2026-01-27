# SECURITY DOCUMENTATION
## GamePlan Scheduler - Security Measures & Implementation

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# 1. Security Overview / Beveiligingsoverzicht

This document details all security measures implemented in GamePlan Scheduler.

## Security Layers / Beveiligingslagen

```
┌─────────────────────────────────────────────────────────────┐
│                    SECURITY LAYERS                           │
├─────────────────────────────────────────────────────────────┤
│  Layer 1: INPUT VALIDATION (Client-side + Server-side)      │
│  Layer 2: AUTHENTICATION (Session management)               │
│  Layer 3: AUTHORIZATION (Ownership checks)                  │
│  Layer 4: DATA PROTECTION (Encryption, XSS prevention)      │
│  Layer 5: DATABASE SECURITY (Prepared statements)           │
└─────────────────────────────────────────────────────────────┘
```

---

# 2. Password Security / Wachtwoord Beveiliging

## 2.1 Password Hashing with bcrypt

**File**: `functions.php`, Line 278
**Method**: `password_hash()` with `PASSWORD_BCRYPT`

```php
// How passwords are stored:
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// How passwords are verified:
password_verify($input_password, $stored_hash);
```

| Feature | Description |
|---------|-------------|
| **Algorithm** | bcrypt (one-way hashing) |
| **Salt** | Automatically generated (unique per password) |
| **Cost Factor** | Default 10 (2^10 iterations) |
| **Output Length** | 60 characters |
| **Reversible** | NO - cannot decrypt, only verify |

## 2.2 Why bcrypt?

| Method | Security | Why (NOT) |
|--------|----------|-----------|
| ~~Plain text~~ | ❌ TERRIBLE | Anyone can read passwords! |
| ~~MD5~~ | ❌ WEAK | Can be cracked in seconds |
| ~~SHA1~~ | ❌ WEAK | Vulnerable to rainbow tables |
| ~~SHA256~~ | ⚠️ MEDIUM | Fast = easy to brute force |
| **bcrypt** | ✅ STRONG | Slow by design, uses salt |

---

# 3. Session Security / Sessie Beveiliging

## 3.1 Session Configuration

**File**: `functions.php`, Lines 32-37

| Setting | Value | Purpose |
|---------|-------|---------|
| **Session Name** | `GAMEPLAN_SESSION` | Custom name (not default PHPSESSID) |
| **Cookie HttpOnly** | `true` | JavaScript cannot read cookie |
| **Cookie SameSite** | `Strict` | Prevents CSRF attacks |
| **Timeout** | 30 minutes | Auto-logout after inactivity |

## 3.2 Session Regeneration

**When**: After successful login
**File**: `functions.php`, Line 313
**Why**: Prevents session fixation attacks

```php
session_regenerate_id(true); // true = delete old session
```

## 3.3 Session Timeout Flow

```
User logs in → Session created
      ↓
Every page: Check last_activity
      ↓
If (now - last_activity) > 30 minutes
      ↓
session_destroy() → Redirect to login
```

---

# 4. XSS Protection / XSS Bescherming

## 4.1 What is XSS?

**Cross-Site Scripting (XSS)**: Attacker injects malicious JavaScript into your website.

**Example Attack**:
```html
<!-- If user input is displayed without escaping: -->
Username: <script>stealCookies()</script>

<!-- This JavaScript would execute on your page! -->
```

## 4.2 Prevention: safeEcho() Function

**File**: `functions.php`, Lines 50-55

```php
function safeEcho($text) {
    // htmlspecialchars converts special chars to HTML entities
    // < becomes &lt; (won't execute as HTML)
    // > becomes &gt;
    // " becomes &quot;
    // & becomes &amp;
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
```

## 4.3 Usage Example

```php
// ❌ UNSAFE - XSS vulnerable
echo $username;

// ✅ SAFE - XSS protected
echo safeEcho($username);
```

---

# 5. SQL Injection Protection

## 5.1 What is SQL Injection?

**SQL Injection**: Attacker manipulates database queries by inserting malicious SQL.

**Vulnerable Code Example**:
```php
// ❌ DANGEROUS - SQL Injection possible!
$query = "SELECT * FROM Users WHERE email = '" . $_POST['email'] . "'";

// Attacker enters: ' OR '1'='1
// Query becomes: SELECT * FROM Users WHERE email = '' OR '1'='1'
// Returns ALL users!
```

## 5.2 Prevention: Prepared Statements (PDO)

**File**: `db.php` and all query functions in `functions.php`

```php
// ✅ SAFE - Using PDO prepared statements
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
$stmt->execute(['email' => $email]);
```

| Method | Security | Reason |
|--------|----------|--------|
| String concatenation | ❌ UNSAFE | User input in query |
| Prepared statements | ✅ SAFE | User input separate from query |

---

# 6. Authorization / Autorisatie

## 6.1 Ownership Check

**File**: `functions.php`, Lines 640-645
**Purpose**: Users can only access their own data

```php
function checkOwnership($userId, $recordUserId) {
    if ($userId !== $recordUserId) {
        return false; // Access denied
    }
    return true; // Access granted
}
```

## 6.2 Where Ownership is Checked

| Operation | Check Location |
|-----------|----------------|
| Edit Schedule | edit_schedule.php |
| Delete Schedule | delete.php |
| Edit Event | edit_event.php |
| Delete Event | delete.php |
| Edit Friend | edit_friend.php |
| Delete Friend | delete.php |

---

# 7. Input Validation Summary

## 7.1 Three-Layer Validation

```
┌─────────────────────────────────────────────────────────────┐
│ Layer 1: HTML5 Validation                                    │
│ - required attribute                                         │
│ - type="email", type="date", type="url"                     │
│ - maxlength, minlength                                       │
├─────────────────────────────────────────────────────────────┤
│ Layer 2: JavaScript Validation (script.js)                   │
│ - validateLoginForm()                                        │
│ - validateRegisterForm()                                     │
│ - validateScheduleForm()                                     │
│ - validateEventForm()                                        │
├─────────────────────────────────────────────────────────────┤
│ Layer 3: PHP Server Validation (functions.php)              │
│ - validateRequired() + BUG FIX #1001                        │
│ - validateDate() + BUG FIX #1004                            │
│ - validateTime()                                             │
│ - validateEmail()                                            │
│ - validateUrl()                                              │
│ - validateCommaSeparated()                                   │
└─────────────────────────────────────────────────────────────┘
```

## 7.2 Why Three Layers?

| Layer | Can be bypassed? | Purpose |
|-------|------------------|---------|
| HTML5 | Yes (disable JavaScript, use curl) | User convenience |
| JavaScript | Yes (disable JavaScript, use curl) | Immediate feedback |
| PHP | **NO** - runs on server | **Final security gate** |

> **IMPORTANT**: Never trust client-side validation alone! Always validate on server.

---

# 8. Error Handling / Foutafhandeling

## 8.1 Hide Error Details from Users

**File**: `db.php`, Lines 280-290

```php
try {
    // Database operation
} catch (PDOException $e) {
    // ❌ DON'T: Show error to user (reveals system info)
    // echo $e->getMessage();
    
    // ✅ DO: Log error, show generic message
    error_log($e->getMessage());
    return "An error occurred. Please try again.";
}
```

## 8.2 Why Hide Errors?

| Shown to User | Security Risk |
|---------------|---------------|
| Database error with query | Reveals table/column names |
| File path in error | Reveals server structure |
| PHP version in error | Reveals potential vulnerabilities |
| **Generic message** | **Safe - no information leaked** |

---

# 9. HTTPS Recommendation

While the application works on HTTP, production deployment should use HTTPS.

| Protocol | Security |
|----------|----------|
| HTTP | ❌ Data sent in plain text |
| HTTPS | ✅ Data encrypted in transit |

---

# 10. Security Checklist / Beveiligings Checklist

| # | Security Measure | Implemented | Location |
|---|------------------|-------------|----------|
| 1 | Password hashing (bcrypt) | ✅ | functions.php |
| 2 | Session management | ✅ | functions.php |
| 3 | Session timeout (30 min) | ✅ | functions.php |
| 4 | Session regeneration | ✅ | functions.php |
| 5 | XSS protection (safeEcho) | ✅ | functions.php |
| 6 | SQL injection prevention (PDO) | ✅ | db.php, functions.php |
| 7 | Ownership checks | ✅ | edit/delete pages |
| 8 | Input validation (3 layers) | ✅ | HTML, JS, PHP |
| 9 | Error message hiding | ✅ | db.php |
| 10 | Soft delete (data protection) | ✅ | All tables |

---

# 11. Summary for Examiner

## Security Features Implemented:

1. ✅ **Authentication** - Secure login with bcrypt password hashing
2. ✅ **Session Management** - Custom session with timeout and regeneration
3. ✅ **XSS Prevention** - All output escaped with htmlspecialchars
4. ✅ **SQL Injection Prevention** - PDO prepared statements everywhere
5. ✅ **Authorization** - Ownership checks for all CRUD operations
6. ✅ **Input Validation** - 3-layer validation (HTML5, JavaScript, PHP)
7. ✅ **Error Handling** - Generic messages to users, detailed logs for dev
8. ✅ **Bug Fixes** - #1001 (spaces) and #1004 (dates) implemented

---

**END OF SECURITY DOCUMENTATION**

This document provides complete security documentation for the GamePlan Scheduler application.
Ready for MBO-4 examination!
