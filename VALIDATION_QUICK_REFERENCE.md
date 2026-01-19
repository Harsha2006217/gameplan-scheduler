# VALIDATION QUICK REFERENCE SHEET
## GamePlan Scheduler - All Validations At A Glance

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 2026-01-19

---

## SERVER-SIDE VALIDATIONS (PHP)

| Function | File:Line | Input | Output | Bug Fix |
|----------|-----------|-------|--------|---------|
| `validateRequired()` | functions.php:68 | value, fieldName, maxLength | error or null | #1001 |
| `validateDate()` | functions.php:97 | date (YYYY-MM-DD) | error or null | #1004 |
| `validateTime()` | functions.php:123 | time (HH:MM) | error or null | - |
| `validateEmail()` | functions.php:136 | email | error or null | - |
| `validateUrl()` | functions.php:148 | url | error or null | - |
| `validateCommaSeparated()` | functions.php:160 | value, fieldName | error or null | - |
| `safeEcho()` | functions.php:50 | string | escaped string | XSS |
| `checkOwnership()` | functions.php:640 | pdo, table, id, userId | true/false | - |

---

## CLIENT-SIDE VALIDATIONS (JavaScript)

| Function | File:Line | Validates | Returns |
|----------|-----------|-----------|---------|
| `validateLoginForm()` | script.js:38 | email, password | boolean |
| `validateRegisterForm()` | script.js:93 | username, email, password | boolean |
| `validateScheduleForm()` | script.js:163 | game, date, time, friends | boolean |
| `validateEventForm()` | script.js:253 | title, date, time, desc, url | boolean |

---

## AUTHENTICATION VALIDATIONS

| Check | Function | Location |
|-------|----------|----------|
| User logged in? | `isLoggedIn()` | functions.php:211 |
| Session timeout? | `checkSessionTimeout()` | functions.php:239 |
| Password correct? | `password_verify()` | functions.php:307 |
| Email unique? | SELECT query | functions.php:269 |

---

## HTML5 BUILT-IN VALIDATIONS

| Attribute | Purpose | Example |
|-----------|---------|---------|
| `required` | Cannot be empty | `<input required>` |
| `type="email"` | Valid email | `<input type="email">` |
| `type="date"` | Date picker | `<input type="date">` |
| `type="time"` | Time picker | `<input type="time">` |
| `maxlength="50"` | Max chars | `<input maxlength="50">` |
| `minlength="8"` | Min chars | `<input minlength="8">` |
| `min="2026-01-19"` | Min date | `<input type="date" min="...">` |

---

## ALL FUNCTIONAL FLOWS

### Authentication (4 flows)
1. **REGISTER**: register.php → registerUser() → INSERT Users
2. **LOGIN**: login.php → loginUser() → SELECT + password_verify
3. **LOGOUT**: header.php → logout() → session_destroy()
4. **TIMEOUT**: checkSessionTimeout() → 30 min → login.php

### Schedules CRUD (4 flows)
1. **CREATE**: add_schedule.php → addSchedule() → INSERT Schedules
2. **READ**: index.php → getSchedules() → SELECT Schedules
3. **UPDATE**: edit_schedule.php → editSchedule() → UPDATE Schedules
4. **DELETE**: delete.php → deleteSchedule() → SET deleted_at

### Events CRUD (4 flows)
1. **CREATE**: add_event.php → addEvent() → INSERT Events
2. **READ**: index.php → getEvents() → SELECT Events
3. **UPDATE**: edit_event.php → editEvent() → UPDATE Events
4. **DELETE**: delete.php → deleteEvent() → SET deleted_at

### Friends CRUD (4 flows)
1. **CREATE**: add_friend.php → addFriend() → INSERT Friends
2. **READ**: index.php → getFriends() → SELECT Friends
3. **UPDATE**: edit_friend.php → updateFriend() → UPDATE Friends
4. **DELETE**: delete.php → deleteFriend() → SET deleted_at

### Favorites CRUD (4 flows)
1. **CREATE**: profile.php → addFavoriteGame() → INSERT UserGames
2. **READ**: index.php → getFavoriteGames() → SELECT UserGames
3. **UPDATE**: edit_favorite.php → updateFavoriteGame() → UPDATE UserGames
4. **DELETE**: delete.php → deleteFavoriteGame() → DELETE UserGames

---

## CRITICAL FILE DEPENDENCIES

```
login.php
   └── functions.php
          └── db.php (database connection)
          └── Session management
          └── All validation functions

index.php (Dashboard)
   └── functions.php
   └── header.php (navigation)
   └── footer.php
   └── script.js (client validation)
```

---

## BUG FIXES IMPLEMENTED

| Bug # | Problem | Solution | Files |
|-------|---------|----------|-------|
| #1001 | Fields with only spaces accepted | Added regex `/^\s*$/` check | functions.php, script.js |
| #1004 | Invalid dates like "2025-13-45" accepted | Use DateTime::createFromFormat + exact match | functions.php, script.js |

---

**For Submission**: This quick reference sheet provides an at-a-glance view of all validations in the GamePlan Scheduler application.
