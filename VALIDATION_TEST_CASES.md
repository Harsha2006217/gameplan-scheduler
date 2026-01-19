# VALIDATION TEST CASES
## GamePlan Scheduler - Test Scenarios with Expected Results

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 19-01-2026

---

# How to Read This Document

Each validation has a table showing:
- **Test Input**: The value being tested
- **Expected Result**: PASS (✓) or FAIL (✗)
- **Reason**: Why it passes or fails

---

# 1. validateRequired() Test Cases

**Function Location**: functions.php, Lines 68-86
**Purpose**: Check that field is not empty or spaces-only

| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 1.1 | `""` (empty) | ✗ FAIL | Empty string is not allowed |
| 1.2 | `"   "` (spaces) | ✗ FAIL | BUG FIX #1001 - Spaces-only not allowed |
| 1.3 | `"  \t  "` (whitespace) | ✗ FAIL | Any whitespace-only is rejected |
| 1.4 | `"Hello"` | ✓ PASS | Valid text content |
| 1.5 | `"  Hello  "` | ✓ PASS | Text with leading/trailing spaces is trimmed |
| 1.6 | `"a"` | ✓ PASS | Single character is valid |
| 1.7 | `"This is a longer text"` | ✓ PASS | Multiple words valid |

### With maxLength parameter (e.g., maxLength = 10):
| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 1.8 | `"Short"` (5 chars) | ✓ PASS | Under limit |
| 1.9 | `"ExactlyTen"` (10 chars) | ✓ PASS | At limit |
| 1.10 | `"This is too long"` (16 chars) | ✗ FAIL | Exceeds maximum length |

---

# 2. validateDate() Test Cases

**Function Location**: functions.php, Lines 97-117
**Purpose**: Check date is valid format and today or future

Assume today's date is: **2026-01-19**

| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 2.1 | `"2026-01-19"` | ✓ PASS | Today's date is valid |
| 2.2 | `"2026-02-15"` | ✓ PASS | Future date is valid |
| 2.3 | `"2030-12-31"` | ✓ PASS | Far future date is valid |
| 2.4 | `"2025-12-31"` | ✗ FAIL | Past date not allowed |
| 2.5 | `"2020-01-01"` | ✗ FAIL | Past date not allowed |
| 2.6 | `"2026-13-01"` | ✗ FAIL | BUG FIX #1004 - Month 13 doesn't exist |
| 2.7 | `"2026-02-30"` | ✗ FAIL | BUG FIX #1004 - Feb 30 doesn't exist |
| 2.8 | `"2026-04-31"` | ✗ FAIL | BUG FIX #1004 - April has 30 days |
| 2.9 | `"01-19-2026"` | ✗ FAIL | Wrong format (MM-DD-YYYY) |
| 2.10 | `"19/01/2026"` | ✗ FAIL | Wrong format (DD/MM/YYYY) |
| 2.11 | `"2026-1-19"` | ✗ FAIL | Month needs 2 digits (01 not 1) |
| 2.12 | `"abc"` | ✗ FAIL | Not a valid date at all |
| 2.13 | `""` | ✗ FAIL | Empty is not valid |

---

# 3. validateTime() Test Cases

**Function Location**: functions.php, Lines 123-130
**Purpose**: Check time is in HH:MM format

| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 3.1 | `"00:00"` | ✓ PASS | Midnight is valid |
| 3.2 | `"12:00"` | ✓ PASS | Noon is valid |
| 3.3 | `"23:59"` | ✓ PASS | End of day is valid |
| 3.4 | `"09:30"` | ✓ PASS | Morning time valid |
| 3.5 | `"14:45"` | ✓ PASS | Afternoon time valid |
| 3.6 | `"9:30"` | ✓ PASS | Single digit hour valid |
| 3.7 | `"24:00"` | ✗ FAIL | 24 hours doesn't exist |
| 3.8 | `"25:00"` | ✗ FAIL | 25 hours doesn't exist |
| 3.9 | `"12:60"` | ✗ FAIL | 60 minutes doesn't exist |
| 3.10 | `"12:99"` | ✗ FAIL | Invalid minutes |
| 3.11 | `"1200"` | ✗ FAIL | Missing colon |
| 3.12 | `"12.00"` | ✗ FAIL | Wrong separator (dot instead of colon) |
| 3.13 | `"noon"` | ✗ FAIL | Text not allowed |
| 3.14 | `""` | ✗ FAIL | Empty is not valid |

---

# 4. validateEmail() Test Cases

**Function Location**: functions.php, Lines 136-142
**Purpose**: Check email has valid format

| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 4.1 | `"user@example.com"` | ✓ PASS | Standard email format |
| 4.2 | `"test.user@domain.nl"` | ✓ PASS | With dot in local part |
| 4.3 | `"user123@test.org"` | ✓ PASS | Numbers in local part |
| 4.4 | `"USER@DOMAIN.COM"` | ✓ PASS | Uppercase is valid |
| 4.5 | `"user+tag@example.com"` | ✓ PASS | Plus sign is allowed |
| 4.6 | `"user"` | ✗ FAIL | No @ symbol |
| 4.7 | `"user@"` | ✗ FAIL | No domain after @ |
| 4.8 | `"@domain.com"` | ✗ FAIL | No local part before @ |
| 4.9 | `"user@domain"` | ✗ FAIL | No TLD (.com, .nl, etc.) |
| 4.10 | `"user domain@test.com"` | ✗ FAIL | Space in email |
| 4.11 | `"user@@domain.com"` | ✗ FAIL | Double @ |
| 4.12 | `""` | ✗ FAIL | Empty is not valid |

---

# 5. validateUrl() Test Cases

**Function Location**: functions.php, Lines 148-154
**Purpose**: Check URL has valid format (optional field)

| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 5.1 | `""` (empty) | ✓ PASS | Optional field, empty is OK |
| 5.2 | `"https://example.com"` | ✓ PASS | Standard HTTPS URL |
| 5.3 | `"http://example.com"` | ✓ PASS | HTTP URL |
| 5.4 | `"https://www.example.com"` | ✓ PASS | With www |
| 5.5 | `"https://example.com/page"` | ✓ PASS | With path |
| 5.6 | `"https://example.com/page?id=1"` | ✓ PASS | With query string |
| 5.7 | `"example.com"` | ✗ FAIL | No protocol (http/https) |
| 5.8 | `"www.example.com"` | ✗ FAIL | No protocol |
| 5.9 | `"just-text"` | ✗ FAIL | Not a URL |
| 5.10 | `"ftp://files.example.com"` | ✓ PASS | FTP protocol valid |

---

# 6. validateCommaSeparated() Test Cases

**Function Location**: functions.php, Lines 160-171
**Purpose**: Check comma-separated list has no empty items

| Test # | Test Input | Expected | Reason |
|--------|------------|----------|--------|
| 6.1 | `""` (empty) | ✓ PASS | Optional field, empty is OK |
| 6.2 | `"item1"` | ✓ PASS | Single item valid |
| 6.3 | `"item1, item2"` | ✓ PASS | Two items valid |
| 6.4 | `"a, b, c, d"` | ✓ PASS | Multiple items valid |
| 6.5 | `"item1,item2"` | ✓ PASS | No spaces is also valid |
| 6.6 | `",item1"` | ✗ FAIL | Empty item at start |
| 6.7 | `"item1,"` | ✗ FAIL | Empty item at end |
| 6.8 | `"item1, , item2"` | ✗ FAIL | Empty item in middle |
| 6.9 | `","` | ✗ FAIL | Only comma, two empty items |
| 6.10 | `"  ,  "` | ✗ FAIL | Spaces with comma = empty items |

---

# 7. validateLoginForm() Test Cases (JavaScript)

**Function Location**: script.js, Lines 38-68
**Purpose**: Client-side login form validation

| Test # | Email Input | Password Input | Expected | Reason |
|--------|-------------|----------------|----------|--------|
| 7.1 | `"user@test.com"` | `"password123"` | ✓ PASS | Both valid |
| 7.2 | `""` | `"password123"` | ✗ FAIL | Email empty |
| 7.3 | `"user@test.com"` | `""` | ✗ FAIL | Password empty |
| 7.4 | `""` | `""` | ✗ FAIL | Both empty |
| 7.5 | `"   "` | `"password123"` | ✗ FAIL | Email is spaces |
| 7.6 | `"invalid-email"` | `"password123"` | ✗ FAIL | Email format invalid |
| 7.7 | `"user@test.com"` | `"   "` | ✗ FAIL | Password is spaces |

---

# 8. validateRegisterForm() Test Cases (JavaScript)

**Function Location**: script.js, Lines 93-136
**Purpose**: Client-side registration form validation

| Test # | Username | Email | Password | Expected | Reason |
|--------|----------|-------|----------|----------|--------|
| 8.1 | `"John"` | `"john@test.com"` | `"password123"` | ✓ PASS | All valid |
| 8.2 | `""` | `"john@test.com"` | `"password123"` | ✗ FAIL | Username empty |
| 8.3 | `"   "` | `"john@test.com"` | `"password123"` | ✗ FAIL | BUG #1001 - Username spaces-only |
| 8.4 | `"John"` | `""` | `"password123"` | ✗ FAIL | Email empty |
| 8.5 | `"John"` | `"invalid"` | `"password123"` | ✗ FAIL | Email format invalid |
| 8.6 | `"John"` | `"john@test.com"` | `""` | ✗ FAIL | Password empty |
| 8.7 | `"John"` | `"john@test.com"` | `"short"` | ✗ FAIL | Password < 8 chars |
| 8.8 | `"John"` | `"john@test.com"` | `"12345678"` | ✓ PASS | Password exactly 8 chars OK |
| 8.9 | `"A very long username that exceeds fifty characters limit"` | `"john@test.com"` | `"password123"` | ✗ FAIL | Username > 50 chars |

---

# 9. validateScheduleForm() Test Cases (JavaScript)

**Function Location**: script.js, Lines 163-224
**Purpose**: Client-side schedule form validation

Assume today's date is: **2026-01-19**

| Test # | Game Title | Date | Time | Friends | Expected | Reason |
|--------|------------|------|------|---------|----------|--------|
| 9.1 | `"Fortnite"` | `"2026-02-01"` | `"14:00"` | `"john, mike"` | ✓ PASS | All valid |
| 9.2 | `""` | `"2026-02-01"` | `"14:00"` | `""` | ✗ FAIL | Game title empty |
| 9.3 | `"   "` | `"2026-02-01"` | `"14:00"` | `""` | ✗ FAIL | BUG #1001 - Title spaces-only |
| 9.4 | `"Fortnite"` | `""` | `"14:00"` | `""` | ✗ FAIL | Date empty |
| 9.5 | `"Fortnite"` | `"2020-01-01"` | `"14:00"` | `""` | ✗ FAIL | Date in past |
| 9.6 | `"Fortnite"` | `"2026-02-01"` | `"25:00"` | `""` | ✗ FAIL | Invalid time |
| 9.7 | `"Fortnite"` | `"2026-02-01"` | `"14:00"` | `"john,, mike"` | ✗ FAIL | Empty item in friends |

---

# 10. validateEventForm() Test Cases (JavaScript)

**Function Location**: script.js, Lines 253-327
**Purpose**: Client-side event form validation

Assume today's date is: **2026-01-19**

| Test # | Title | Date | Time | Description | Link | Expected | Reason |
|--------|-------|------|------|-------------|------|----------|--------|
| 10.1 | `"Tournament"` | `"2026-02-01"` | `"14:00"` | `"Gaming event"` | `"https://twitch.tv"` | ✓ PASS | All valid |
| 10.2 | `""` | `"2026-02-01"` | `"14:00"` | `""` | `""` | ✗ FAIL | Title empty |
| 10.3 | `"   "` | `"2026-02-01"` | `"14:00"` | `""` | `""` | ✗ FAIL | BUG #1001 |
| 10.4 | `"A very long title..."` (>100 chars) | `"2026-02-01"` | `"14:00"` | `""` | `""` | ✗ FAIL | Title > 100 chars |
| 10.5 | `"Tournament"` | `"2020-01-01"` | `"14:00"` | `""` | `""` | ✗ FAIL | Date in past |
| 10.6 | `"Tournament"` | `"2026-02-01"` | `"14:00"` | `"A very long description..."` (>500 chars) | `""` | ✗ FAIL | Description > 500 chars |
| 10.7 | `"Tournament"` | `"2026-02-01"` | `"14:00"` | `""` | `"not-a-url"` | ✗ FAIL | Invalid URL format |

---

# 11. Authentication Test Cases

## 11.1 Login Authentication

| Test # | Email | Password | User Exists? | Password Match? | Expected |
|--------|-------|----------|--------------|-----------------|----------|
| 11.1 | `"user@test.com"` | `"correctpassword"` | Yes | Yes | ✓ Login success |
| 11.2 | `"user@test.com"` | `"wrongpassword"` | Yes | No | ✗ "Invalid credentials" |
| 11.3 | `"unknown@test.com"` | `"password123"` | No | N/A | ✗ "Invalid credentials" |
| 11.4 | `""` | `"password123"` | N/A | N/A | ✗ "Email required" |
| 11.5 | `"user@test.com"` | `""` | N/A | N/A | ✗ "Password required" |

## 11.2 Registration Authentication

| Test # | Username | Email | Password | Email Exists? | Expected |
|--------|----------|-------|----------|---------------|----------|
| 11.6 | `"NewUser"` | `"new@test.com"` | `"password123"` | No | ✓ Registration success |
| 11.7 | `"NewUser"` | `"existing@test.com"` | `"password123"` | Yes | ✗ "Email already registered" |
| 11.8 | `"NewUser"` | `"new@test.com"` | `"short"` | No | ✗ "Password min 8 chars" |

---

# 12. Session Test Cases

| Test # | Scenario | Expected |
|--------|----------|----------|
| 12.1 | User logs in successfully | Session created, user_id stored |
| 12.2 | Session exists, user active | Access granted to protected pages |
| 12.3 | No session, access protected page | Redirect to login.php |
| 12.4 | Session inactive for 30 minutes | Session destroyed, redirect to login |
| 12.5 | User clicks logout | Session destroyed, redirect to login |

---

# 13. Ownership/Permission Test Cases

| Test # | Scenario | Expected |
|--------|----------|----------|
| 13.1 | User edits their own schedule | ✓ Allowed |
| 13.2 | User edits another user's schedule | ✗ Denied, "No permission" |
| 13.3 | User deletes their own event | ✓ Allowed |
| 13.4 | User deletes another user's event | ✗ Denied, "No permission" |

---

# Summary: Test Coverage

| Validation | Total Tests | Pass Tests | Fail Tests |
|------------|-------------|------------|------------|
| validateRequired() | 10 | 4 | 6 |
| validateDate() | 13 | 4 | 9 |
| validateTime() | 14 | 6 | 8 |
| validateEmail() | 12 | 5 | 7 |
| validateUrl() | 10 | 6 | 4 |
| validateCommaSeparated() | 10 | 5 | 5 |
| validateLoginForm() | 7 | 1 | 6 |
| validateRegisterForm() | 9 | 2 | 7 |
| validateScheduleForm() | 7 | 1 | 6 |
| validateEventForm() | 7 | 1 | 6 |
| **TOTAL** | **99** | **35** | **64** |

---

**END OF TEST CASES DOCUMENT**

This document provides comprehensive test cases for all validations in the GamePlan Scheduler application.
Ready for MBO-4 examination!
