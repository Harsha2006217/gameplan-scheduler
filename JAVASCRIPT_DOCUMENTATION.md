# JAVASCRIPT DOCUMENTATION (A-Z Deep Dive)
## GamePlan Scheduler - Client-Side Logic Explained

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `script.js` | **Total Lines**: 433

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\script.js`
**Purpose**: Handles all client-side validation, interactivity, DOM manipulation, and dynamic user feedback.
**Language**: Vanilla JavaScript (ES6+)

**Key Responsibilities**:
1.  **Form Validation**: Prevents invalid data from reaching the server (saves resources).
2.  **Bug Fixes Implementation**: Directly implements checks for Bug #1001 (spaces) and #1004 (dates).
3.  **User Experience (UX)**: Smooth scrolling, auto-dismissing alerts, delete confirmations.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Login Validation (`validateLoginForm`)

**Lines**: 38-68
**Trigger**: On Log in form submit (`<form onsubmit="return validateLoginForm()">`)

### Logic Explained:
1.  **Get Values**: `document.getElementById('...').value` gets the input.
2.  **Trim Method**: `.trim()` removes whitespace from start/end.
    *   *Example*: `"  user  "` becomes `"user"`.
3.  **Empty Check**: `if (!email || !password)` checks if strings are empty strings `""`.
4.  **Regex Check**: Validates email format.

### Code Snippet:
```javascript
// Validates an email address
if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    alert('Invalid email format.');
    return false; // Stopt formulier
}
```

---

## SECTION 2: Registration Validation (`validateRegisterForm`)

**Lines**: 93-136
**Trigger**: On Register form submit

### Logic Explained:
1.  **Required Fields**: Checks Username, Email, Password.
2.  **BUG FIX #1001 (Spaces Check)**:
    *   Uses Regex `/^\s*$/`.
    *   **Meaning**: if string contains *only* whitespace characters from start (`^`) to end (`$`).
    *   Prevents users from registering as " " (invisible name).
3.  **Length Checks**:
    *   Username > 50 chars -> Block.
    *   Password < 8 chars -> Block.
4.  **Email**: Standard regex test.

### Code Snippet (Bug Fix #1001):
```javascript
// BUG FIX #1001: Check for spaces-only input
if (/^\s*$/.test(username)) {
    alert('Username cannot be only spaces.');
    return false;
}
```

---

## SECTION 3: Schedule Validation (`validateScheduleForm`)

**Lines**: 163-224
**Trigger**: On Add Schedule form submit

### Logic Explained:
1.  **Game Title**: Standard required + spaces check (#1001).
2.  **BUG FIX #1004 (Date Validation)**:
    *   Converts input string to `new Date()`.
    *   Compares with `new Date()` (Today).
    *   **Rule**: `selectedDate < today` is INVALID.
3.  **Time Validation**:
    *   Uses Regex `/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/`.
    *   Ensures HH is 00-23 and MM is 00-59.
4.  **Whitelist Validation**:
    *   For "Friends" and "Shared With" fields.
    *   Regex: `/^[a-zA-Z0-9,\s]*$/`.
    *   Allows only letters, numbers, commas, and spaces. Prevents XSS/Issues.

### Code Snippet (Bug Fix #1004):
```javascript
// BUG FIX #1004: Strict Future Date
const selectedDate = new Date(date);
const today = new Date();
today.setHours(0, 0, 0, 0); // Ignore time part

if (selectedDate < today) {
    alert('Date must be today or in the future.');
    return false;
}
```

---

## SECTION 4: Event Validation (`validateEventForm`)

**Lines**: 253-327
**Trigger**: On Add Event form submit

### Logic Explained:
1.  **Title Checks**: Max 100 chars, not empty/spaces.
2.  **Description Checks**: Max 500 chars.
3.  **External URL**:
    *   Uses complex regex to validate "http://" or "www." structures.
    *   Prevents broken links in database.
4.  **Date/Time**: Same strict logic as Schedule form.

### Code Snippet (URL Regex):
```javascript
// Validates URL like https://example.com or www.test.com
if (externalLink && !/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(externalLink)) {
    // Error handling
}
```

---

## SECTION 5 & 6: Initialization (`initializeFeatures`)

**Lines**: 346-398
**Trigger**: `DOMContentLoaded` (When page loads)

### Logic Explained:
1.  **Smooth Scrolling**:
    *   Finds all `href="#target"`.
    *   Intercepts click (`e.preventDefault()`).
    *   Uses `target.scrollIntoView({ behavior: 'smooth' })`.
2.  **Delete Confirmation**:
    *   Finds all `.btn-danger`.
    *   Adds click listener.
    *   Shows `confirm("Are you sure?")`.
    *   If user clicks Cancel, `e.preventDefault()` stops the link/form.
3.  **Auto-Dismiss Alerts**:
    *   Finds `.alert-dismissible`.
    *   Sets a timer (`setTimeout`) for 5000ms (5 seconds).
    *   Clicks the close button programmatically.

---

## SECTION 7: Utilities (`showNotification`)

**Lines**: 412-428

### Logic Explained:
*   Creates a dynamic `div` element via JS.
*   Adds Bootstrap classes (`alert`, `alert-success`, etc.).
*   Injects into `document.body`.
*   Self-destructs after 5 seconds (`notification.remove()`).

---

# 3. Complete Regex (Regular Expressions) Guide / Uitleg

This reference explains every cryptic symbol used in the validation logic.

## A. Email Regex
`/^[^\s@]+@[^\s@]+\.[^\s@]+$/`

| Symbol | Meaning / Betekenis |
|--------|---------------------|
| `^` | Start of string / Begin van tekst |
| `[^\s@]+`| 1+ characters that are NOT space (`\s`) OR `@` |
| `@` | Literal "@" symbol |
| `\.` | Literal "." dot |
| `$` | End of string / Einde van tekst |

## B. Spaces-Only Regex (Bug Fix #1001)
`/^\s*$/`

| Symbol | Meaning / Betekenis |
|--------|---------------------|
| `^` | Start |
| `\s` | Whitespace character (space, tab, newline) |
| `*` | Zero or more times (entire string is allowed to be spaces) |
| `$` | End |
| **Logic** | If this matches, the input is BAD (empty/invisible). |

## C. Time Regex
`/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/`

| Symbol | Meaning / Betekenis |
|--------|---------------------|
| `[01]?` | Optional 0 or 1 (e.g., 0, 1, or nothing) |
| `[0-9]` | Followed by any digit (0-9) -> Matches 00-19 |
| `|` | OR / OF |
| `2[0-3]`| Starts with 2, followed by 0-3 -> Matches 20-23 |
| `:` | Literal colon |
| `[0-5][0-9]` | First digit 0-5, second 0-9 -> Matches 00-59 (Minutes) |

## D. URL Regex
`/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/`

| Symbol | Meaning / Betekenis |
|--------|---------------------|
| `https?` | Matches "http" or "https" (`s` is optional `?`) |
| `[\w\-]+` | Word characters (letters/numbers/_) or dashes |
| `(\.[\w\-]+)+` | Dot followed by word/dashes, repeating (e.g., .com, .co.uk) |

---

# 4. Event Listeners Reference

| Event | Target | Purpose |
|-------|--------|---------|
| `DOMContentLoaded` | `document` | Initializes scripts when page loads |
| `submit` | `<form>` | Triggers validation functions |
| `click` | `a[href^="#"]` | Smooth scrolling navigation |
| `click` | `.btn-danger` | Confirm before deleting |

---

**END OF JAVASCRIPT DEEP DIVE**
