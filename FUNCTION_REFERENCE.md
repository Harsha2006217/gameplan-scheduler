# FUNCTION REFERENCE
## GamePlan Scheduler - Complete Function Documentation

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# 1. Core Functions Overview / Kern Functies Overzicht

**Total Functions**: 35+
**Files with Functions**: functions.php, script.js, db.php

---

# 2. PHP Functions (functions.php)

## 2.1 Session Management Functions

### `initSession()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 32-37 |
| **Purpose** | Start PHP session with custom settings |
| **Parameters** | None |
| **Returns** | void |
| **Called By** | Every page (via include) |

```php
function initSession() {
    session_name('GAMEPLAN_SESSION');
    session_start();
}
```

---

### `isLoggedIn()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 211-214 |
| **Purpose** | Check if user is currently logged in |
| **Parameters** | None |
| **Returns** | `bool` - true if logged in, false otherwise |
| **Used For** | Page access control |

```php
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}
```

---

### `getUserId()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 220-223 |
| **Purpose** | Get current user's ID from session |
| **Parameters** | None |
| **Returns** | `int|null` - User ID or null if not logged in |

---

### `getUsername()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 229-232 |
| **Purpose** | Get current user's username from session |
| **Parameters** | None |
| **Returns** | `string|null` - Username or null |

---

### `checkSessionTimeout()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 238-255 |
| **Purpose** | Auto-logout after 30 minutes inactivity |
| **Parameters** | None |
| **Returns** | void (redirects if expired) |
| **Timeout** | 1800 seconds (30 minutes) |

---

### `updateLastActivity()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 261-264 |
| **Purpose** | Update session last activity timestamp |
| **Parameters** | None |
| **Returns** | void |

---

## 2.2 Validation Functions

### `validateRequired($value, $fieldName, $maxLength = 0)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 68-86 |
| **Purpose** | Validate required field is not empty/spaces |
| **Bug Fix** | #1001 - Prevents spaces-only input |
| **Parameters** | `$value` (string), `$fieldName` (string), `$maxLength` (int, optional) |
| **Returns** | `string|null` - Error message or null if valid |

---

### `validateDate($date)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 97-117 |
| **Purpose** | Validate date format and ensure future date |
| **Bug Fix** | #1004 - Strict date validation |
| **Parameters** | `$date` (string in YYYY-MM-DD format) |
| **Returns** | `string|null` - Error message or null if valid |

---

### `validateTime($time)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 123-130 |
| **Purpose** | Validate time format (HH:MM) |
| **Parameters** | `$time` (string) |
| **Returns** | `string|null` - Error message or null if valid |
| **Regex** | `/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/` |

---

### `validateEmail($email)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 136-142 |
| **Purpose** | Validate email format |
| **Parameters** | `$email` (string) |
| **Returns** | `string|null` - Error message or null if valid |
| **Uses** | `filter_var` with `FILTER_VALIDATE_EMAIL` |

---

### `validateUrl($url)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 148-154 |
| **Purpose** | Validate URL format (optional field) |
| **Parameters** | `$url` (string) |
| **Returns** | `string|null` - Error message or null if valid |
| **Uses** | `filter_var` with `FILTER_VALIDATE_URL` |

---

### `validateCommaSeparated($value)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 160-171 |
| **Purpose** | Validate comma-separated list has no empty items |
| **Parameters** | `$value` (string) |
| **Returns** | `string|null` - Error message or null if valid |

---

## 2.3 Authentication Functions

### `registerUser($username, $email, $password)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 269-290 |
| **Purpose** | Create new user account |
| **Parameters** | `$username`, `$email`, `$password` (strings) |
| **Returns** | `string|null` - Error message or null if success |
| **Security** | Uses `password_hash` with `PASSWORD_BCRYPT` |

---

### `loginUser($email, $password)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 296-320 |
| **Purpose** | Authenticate user and create session |
| **Parameters** | `$email`, `$password` (strings) |
| **Returns** | `string|null` - Error message or null if success |
| **Security** | Uses `password_verify`, regenerates session ID |

---

### `logout()`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 326-335 |
| **Purpose** | End user session and redirect to login |
| **Parameters** | None |
| **Returns** | void (redirects) |

---

## 2.4 CRUD Functions - Schedules

### `addSchedule($userId, $gameTitle, $date, $time, $friends, $sharedWith)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 345-380 |
| **Purpose** | Create new gaming schedule |
| **Returns** | `string|null` - Error or null if success |

---

### `getSchedules($userId, $sortBy = 'date', $sortOrder = 'ASC')`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 386-410 |
| **Purpose** | Get all schedules for a user |
| **Returns** | `array` - List of schedule records |

---

### `editSchedule($scheduleId, $userId, ...)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 416-450 |
| **Purpose** | Update existing schedule |
| **Returns** | `string|null` - Error or null if success |

---

### `deleteSchedule($scheduleId, $userId)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 456-475 |
| **Purpose** | Soft delete a schedule |
| **Returns** | `bool` - true if deleted, false otherwise |

---

## 2.5 CRUD Functions - Events

### `addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink, $sharedWith)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 481-520 |
| **Purpose** | Create new gaming event |
| **Returns** | `string|null` - Error or null if success |

---

### `getEvents($userId, $sortBy = 'date', $sortOrder = 'ASC')`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 526-550 |
| **Purpose** | Get all events for a user |
| **Returns** | `array` - List of event records |

---

### `editEvent($eventId, $userId, ...)`
| Property | Value |
|----------|-------|
| **Location** | functions.php |
| **Purpose** | Update existing event |
| **Returns** | `string|null` |

---

### `deleteEvent($eventId, $userId)`
| Property | Value |
|----------|-------|
| **Location** | functions.php |
| **Purpose** | Soft delete an event |
| **Returns** | `bool` |

---

## 2.6 CRUD Functions - Friends

### `addFriend($userId, $friendUsername, $note, $status)`
| Returns | `string|null` |

### `getFriends($userId)`
| Returns | `array` |

### `updateFriend($friendId, $userId, ...)`
| Returns | `string|null` |

### `deleteFriend($friendId, $userId)`
| Returns | `bool` |

---

## 2.7 CRUD Functions - Favorites

### `addFavoriteGame($userId, $gameTitle, $note)`
| Returns | `string|null` |

### `getFavoriteGames($userId)`
| Returns | `array` |

### `updateFavoriteGame($userId, $gameId, $note)`
| Returns | `string|null` |

### `deleteFavoriteGame($userId, $gameId)`
| Returns | `bool` |

---

## 2.8 Helper Functions

### `safeEcho($text)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 50-55 |
| **Purpose** | XSS protection - escape HTML |
| **Parameters** | `$text` (string) |
| **Returns** | `string` - Escaped text |
| **Uses** | `htmlspecialchars` with `ENT_QUOTES` |

---

### `checkOwnership($userId, $recordUserId)`
| Property | Value |
|----------|-------|
| **Location** | functions.php, Lines 640-645 |
| **Purpose** | Verify user owns the record |
| **Parameters** | `$userId`, `$recordUserId` (int) |
| **Returns** | `bool` |

---

### `setMessage($message, $type = 'info')`
| Property | Value |
|----------|-------|
| **Purpose** | Store flash message in session |
| **Parameters** | `$message` (string), `$type` (string: info/success/error) |

---

### `getMessage()`
| Property | Value |
|----------|-------|
| **Purpose** | Retrieve and clear flash message |
| **Returns** | `array|null` - Message and type |

---

# 3. JavaScript Functions (script.js)

## 3.1 Validation Functions

### `validateLoginForm()`
| Property | Value |
|----------|-------|
| **Location** | script.js, Lines 38-68 |
| **Purpose** | Validate login form before submit |
| **Called By** | `onsubmit` event on login form |
| **Returns** | `boolean` - true to submit, false to block |

---

### `validateRegisterForm()`
| Property | Value |
|----------|-------|
| **Location** | script.js, Lines 93-136 |
| **Purpose** | Validate registration form |
| **Checks** | Username (not empty, max 50), email format, password (min 8) |
| **Bug Fix** | #1001 - Spaces-only check |

---

### `validateScheduleForm()`
| Property | Value |
|----------|-------|
| **Location** | script.js, Lines 163-224 |
| **Purpose** | Validate schedule form |
| **Checks** | Game title, date (future), time format, comma-separated fields |
| **Bug Fixes** | #1001, #1004 |

---

### `validateEventForm()`
| Property | Value |
|----------|-------|
| **Location** | script.js, Lines 253-327 |
| **Purpose** | Validate event form |
| **Checks** | Title (max 100), date, time, description (max 500), URL format |
| **Bug Fixes** | #1001, #1004 |

---

## 3.2 Utility Functions

### `initializeFeatures()`
| Property | Value |
|----------|-------|
| **Location** | script.js, Lines 360-400 |
| **Purpose** | Initialize page features on DOM load |
| **Features** | Smooth scrolling, dismissible alerts, delete confirmations |

---

# 4. Database Functions (db.php)

### `getConnection()`
| Property | Value |
|----------|-------|
| **Location** | db.php |
| **Purpose** | Get PDO database connection (Singleton pattern) |
| **Returns** | `PDO` object |
| **Error Handling** | Logs errors, shows generic message |

---

# 5. Function Call Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    LOGIN FUNCTION FLOW                       │
└─────────────────────────────────────────────────────────────┘

User clicks Login
       ↓
validateLoginForm() [JS]
       ↓
Form submits to login.php
       ↓
loginUser($email, $password) [PHP]
       ├── validateRequired($email, "Email")
       ├── validateRequired($password, "Password")
       ├── getConnection() [DB]
       ├── Query database
       ├── password_verify()
       └── Create session
       ↓
Redirect to index.php
```

---

# 6. Quick Reference Table

| Function | File | Purpose |
|----------|------|---------|
| `initSession()` | functions.php | Start session |
| `isLoggedIn()` | functions.php | Check login status |
| `validateRequired()` | functions.php | Check not empty (#1001) |
| `validateDate()` | functions.php | Validate date (#1004) |
| `validateTime()` | functions.php | Validate HH:MM |
| `validateEmail()` | functions.php | Validate email format |
| `loginUser()` | functions.php | Authenticate user |
| `registerUser()` | functions.php | Create account |
| `logout()` | functions.php | End session |
| `safeEcho()` | functions.php | XSS protection |
| `checkOwnership()` | functions.php | Authorization check |
| `validateLoginForm()` | script.js | JS login validation |
| `validateRegisterForm()` | script.js | JS register validation |
| `validateScheduleForm()` | script.js | JS schedule validation |
| `validateEventForm()` | script.js | JS event validation |
| `getConnection()` | db.php | Database connection |

---

**END OF FUNCTION REFERENCE**

This document provides complete function documentation for the GamePlan Scheduler application.
Ready for MBO-4 examination!
