# GamePlan Scheduler - Complete Validation & Flow Documentation
## A-Z Guide from Begin to End / Complete Handleiding van A tot Z

---

> **Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 2026-01-19
> 
> This document contains ALL validations, algorithms, functional flows, and code flow diagrams for the GamePlan Scheduler application.

---

# Table of Contents

1. [Application Overview](#1-application-overview)
2. [Complete List of All Validations](#2-complete-list-of-all-validations)
3. [Validation Algorithms](#3-validation-algorithms-for-each-validation-flow)
4. [Login Flow with Algorithm](#4-login-flow-with-complete-algorithm)
5. [All Functional Flows](#5-complete-list-of-all-functional-flows)
6. [Code Flow Diagrams](#6-code-flow-diagrams)
7. [Login Page Loading Flow](#7-login-page-loading-flow-diagram)
8. [Home Page (Dashboard) Loading Flow](#8-home-page-dashboard-loading-flow-diagram)

---

# 1. Application Overview

**GamePlan Scheduler** is a web application for managing gaming schedules, events, and friends.

## Key Files Structure
| File | Purpose |
|------|---------|
| login.php | User login page |
| register.php | User registration page |
| index.php | Dashboard/Home page |
| functions.php | Core validation & business logic |
| script.js | Client-side validation |
| db.php | Database connection |
| add_schedule.php | Add gaming schedule |
| add_event.php | Add gaming events |
| add_friend.php | Add friends |

---

# 2. Complete List of All Validations

## 2.1 Server-Side Validations (PHP - functions.php)

| # | Validation | Function | Line | Description EN | Description NL |
|---|------------|----------|------|----------------|----------------|
| 1 | **Required Field** | `validateRequired()` | 68-86 | Checks if field is empty or contains only spaces (BUG FIX #1001) | Controleert of veld leeg is of alleen spaties bevat |
| 2 | **Date Format** | `validateDate()` | 97-117 | Validates YYYY-MM-DD format and ensures date is today or future (BUG FIX #1004) | Valideert JJJJ-MM-DD formaat en zorgt dat datum vandaag of toekomst is |
| 3 | **Time Format** | `validateTime()` | 123-130 | Validates HH:MM format (00-23 hours, 00-59 minutes) | Valideert UU:MM formaat (00-23 uren, 00-59 minuten) |
| 4 | **Email Format** | `validateEmail()` | 136-142 | Validates proper email format using PHP filter | Valideert correct e-mail formaat met PHP filter |
| 5 | **URL Format** | `validateUrl()` | 148-154 | Validates URL format (optional field) | Valideert URL formaat (optioneel veld) |
| 6 | **Comma-Separated** | `validateCommaSeparated()` | 160-171 | Validates comma-separated values have no empty items | Valideert dat komma-gescheiden waarden geen lege items bevatten |
| 7 | **XSS Protection** | `safeEcho()` | 50-55 | Escapes HTML to prevent XSS attacks | Escapet HTML om XSS aanvallen te voorkomen |
| 8 | **Ownership Check** | `checkOwnership()` | 640-645 | Verifies user owns the record before edit/delete | Verifieert dat gebruiker eigenaar is van record voor bewerken/verwijderen |

## 2.2 Client-Side Validations (JavaScript - script.js)

| # | Validation | Function | Line | Description EN | Description NL |
|---|------------|----------|------|----------------|----------------|
| 1 | **Login Form** | `validateLoginForm()` | 38-68 | Validates email and password before login | Valideert e-mail en wachtwoord voor login |
| 2 | **Register Form** | `validateRegisterForm()` | 93-136 | Validates username, email, password for registration | Valideert gebruikersnaam, e-mail, wachtwoord voor registratie |
| 3 | **Schedule Form** | `validateScheduleForm()` | 163-224 | Validates game title, date, time, friends fields | Valideert speltitel, datum, tijd, vrienden velden |
| 4 | **Event Form** | `validateEventForm()` | 253-327 | Validates event title, date, time, description, URL | Valideert evenement titel, datum, tijd, beschrijving, URL |
| 5 | **Delete Confirm** | `initializeFeatures()` | 380-388 | Confirms before delete actions | Bevestigt voor verwijder acties |

## 2.3 Authentication Validations

| # | Validation | Function | File | Description EN | Description NL |
|---|------------|----------|------|----------------|----------------|
| 1 | **Login Check** | `isLoggedIn()` | functions.php:211 | Checks if user session exists | Controleert of gebruikerssessie bestaat |
| 2 | **Session Timeout** | `checkSessionTimeout()` | functions.php:239 | Auto-logout after 30 minutes inactivity | Auto-uitloggen na 30 minuten inactiviteit |
| 3 | **Password Verify** | `loginUser()` | functions.php:307 | Verifies password hash matches | Verifieert dat wachtwoord hash overeenkomt |
| 4 | **Email Unique** | `registerUser()` | functions.php:269-272 | Checks email not already registered | Controleert dat e-mail niet al geregistreerd is |

## 2.4 HTML5 Built-in Validations

| # | Attribute | Used In | Description |
|---|-----------|---------|-------------|
| 1 | `required` | All form fields | Browser prevents empty submission |
| 2 | `type="email"` | Email fields | Browser validates email format |
| 3 | `type="date"` | Date fields | Browser shows date picker |
| 4 | `type="time"` | Time fields | Browser shows time picker |
| 5 | `type="url"` | URL fields | Browser validates URL format |
| 6 | `maxlength` | Text fields | Limits character input |
| 7 | `minlength` | Password field | Requires minimum characters |
| 8 | `min` | Date fields | Sets minimum date (today) |

---

# 3. Validation Algorithms for Each Validation Flow

## 3.1 Algorithm: `validateRequired()` (BUG FIX #1001)

**Location**: functions.php:68-86

```
ALGORITHM: validateRequired(value, fieldName, maxLength)
============================================================
INPUT:  value (string), fieldName (string), maxLength (integer)
OUTPUT: error message (string) OR null (if valid)

BEGIN
    STEP 1: Remove whitespace from beginning and end
            value = trim(value)
    
    STEP 2: Check if empty OR contains only spaces (BUG FIX #1001)
            IF value is empty OR matches regex /^\s*$/
                RETURN error: "fieldName may not be empty or contain only spaces"
            ENDIF
    
    STEP 3: Check maximum length (if specified)
            IF maxLength > 0 AND length(value) > maxLength
                RETURN error: "fieldName exceeds maximum length"
            ENDIF
    
    STEP 4: All validations passed
            RETURN null (indicates valid)
END
```

## 3.2 Algorithm: `validateDate()` (BUG FIX #1004)

**Location**: functions.php:97-117

```
ALGORITHM: validateDate(date)
============================================================
INPUT:  date (string in format YYYY-MM-DD)
OUTPUT: error message (string) OR null (if valid)

BEGIN
    STEP 1: Parse date using DateTime::createFromFormat
            dateObj = DateTime::createFromFormat('Y-m-d', date)
    
    STEP 2: Verify date was parsed AND matches input exactly (BUG FIX #1004)
            IF dateObj is false OR dateObj.format('Y-m-d') != date
                RETURN error: "Invalid date format. Use YYYY-MM-DD."
            ENDIF
    
    STEP 3: Check if date is today or in future
            today = new DateTime('today')
            IF dateObj < today
                RETURN error: "Date must be today or in the future."
            ENDIF
    
    STEP 4: All validations passed
            RETURN null (indicates valid)
END
```

## 3.3 Algorithm: `validateTime()`

**Location**: functions.php:123-130

```
ALGORITHM: validateTime(time)
============================================================
INPUT:  time (string in format HH:MM)
OUTPUT: error message (string) OR null (if valid)

BEGIN
    STEP 1: Check time format with regex
            regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
            IF time does NOT match regex
                RETURN error: "Invalid time format (HH:MM)"
            ENDIF
    
    STEP 2: Validation passed
            RETURN null (indicates valid)
END
```

## 3.4 Algorithm: `validateEmail()`

**Location**: functions.php:136-142

```
ALGORITHM: validateEmail(email)
============================================================
INPUT:  email (string)
OUTPUT: error message (string) OR null (if valid)

BEGIN
    STEP 1: Use PHP filter_var with FILTER_VALIDATE_EMAIL
            IF filter_var(email, FILTER_VALIDATE_EMAIL) is false
                RETURN error: "Invalid email format"
            ENDIF
    
    STEP 2: Validation passed
            RETURN null (indicates valid)
END
```

## 3.5 Algorithm: `validateUrl()`

**Location**: functions.php:148-154

```
ALGORITHM: validateUrl(url)
============================================================
INPUT:  url (string, optional)
OUTPUT: error message (string) OR null (if valid)

BEGIN
    STEP 1: Check if URL is provided and validate format
            IF url is NOT empty AND filter_var(url, FILTER_VALIDATE_URL) is false
                RETURN error: "Invalid URL format"
            ENDIF
    
    STEP 2: Validation passed (including empty = valid for optional)
            RETURN null (indicates valid)
END
```

## 3.6 Algorithm: `validateCommaSeparated()`

**Location**: functions.php:160-171

```
ALGORITHM: validateCommaSeparated(value, fieldName)
============================================================
INPUT:  value (string), fieldName (string)
OUTPUT: error message (string) OR null (if valid)

BEGIN
    STEP 1: If value is empty, return valid (optional field)
            IF value is empty
                RETURN null
            ENDIF
    
    STEP 2: Split value by comma into array
            items = explode(',', value)
    
    STEP 3: Check each item for empty values
            FOR EACH item IN items
                IF trim(item) is empty
                    RETURN error: "fieldName contains empty items"
                ENDIF
            ENDFOR
    
    STEP 4: Validation passed
            RETURN null (indicates valid)
END
```

## 3.7 Algorithm: `validateLoginForm()` (JavaScript)

**Location**: script.js:38-68

```
ALGORITHM: validateLoginForm()
============================================================
INPUT:  Form fields (email, password) from DOM
OUTPUT: boolean (true = allow submit, false = block submit)

BEGIN
    STEP 1: Get and trim email value from DOM
            email = document.getElementById('email').value.trim()
    
    STEP 2: Get and trim password value from DOM
            password = document.getElementById('password').value.trim()
    
    STEP 3: Check if both fields are filled
            IF email is empty OR password is empty
                DISPLAY alert: "Email and password are required"
                RETURN false (block submission)
            ENDIF
    
    STEP 4: Validate email format with regex
            regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            IF email does NOT match regex
                DISPLAY alert: "Invalid email format"
                RETURN false (block submission)
            ENDIF
    
    STEP 5: All validations passed
            RETURN true (allow submission)
END
```

## 3.8 Algorithm: `validateRegisterForm()` (JavaScript)

**Location**: script.js:93-136

```
ALGORITHM: validateRegisterForm()
============================================================
INPUT:  Form fields (username, email, password) from DOM
OUTPUT: boolean (true = allow submit, false = block submit)

BEGIN
    STEP 1: Get and trim all form values from DOM
            username = document.getElementById('username').value.trim()
            email = document.getElementById('email').value.trim()
            password = document.getElementById('password').value.trim()
    
    STEP 2: Check all required fields
            IF username is empty OR email is empty OR password is empty
                DISPLAY alert: "All fields are required"
                RETURN false
            ENDIF
    
    STEP 3: Check for spaces-only username (BUG FIX #1001)
            IF username matches /^\s*$/
                DISPLAY alert: "Username cannot be only spaces"
                RETURN false
            ENDIF
    
    STEP 4: Check username length (max 50)
            IF username.length > 50
                DISPLAY alert: "Username too long (max 50 characters)"
                RETURN false
            ENDIF
    
    STEP 5: Validate email format
            IF email does NOT match /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                DISPLAY alert: "Invalid email format"
                RETURN false
            ENDIF
    
    STEP 6: Check password minimum length (8 characters)
            IF password.length < 8
                DISPLAY alert: "Password must be at least 8 characters"
                RETURN false
            ENDIF
    
    STEP 7: All validations passed
            RETURN true
END
```

## 3.9 Algorithm: `validateScheduleForm()` (JavaScript)

**Location**: script.js:163-224

```
ALGORITHM: validateScheduleForm()
============================================================
INPUT:  Form fields from DOM
OUTPUT: boolean (true = allow submit, false = block submit)

BEGIN
    STEP 1: Get all form values
            gameTitle = getElementById('game_title').value.trim()
            date = getElementById('date').value
            time = getElementById('time').value
            friendsStr = getElementById('friends_str').value.trim()
            sharedWithStr = getElementById('shared_with_str').value.trim()
    
    STEP 2: Validate game title (BUG FIX #1001)
            IF gameTitle is empty OR matches /^\s*$/
                DISPLAY alert: "Game title is required and cannot be only spaces"
                RETURN false
            ENDIF
    
    STEP 3: Validate date is provided
            IF date is empty
                DISPLAY alert: "Date is required"
                RETURN false
            ENDIF
    
    STEP 4: Validate date format and future date (BUG FIX #1004)
            selectedDate = new Date(date)
            today = new Date()
            today.setHours(0, 0, 0, 0)
            
            IF selectedDate is invalid (isNaN)
                DISPLAY alert: "Invalid date format"
                RETURN false
            ENDIF
            
            IF selectedDate < today
                DISPLAY alert: "Date must be today or in the future"
                RETURN false
            ENDIF
    
    STEP 5: Validate time format
            IF time does NOT match /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
                DISPLAY alert: "Invalid time format. Use HH:MM"
                RETURN false
            ENDIF
    
    STEP 6: Validate friends field (if provided)
            IF friendsStr AND does NOT match /^[a-zA-Z0-9,\s]*$/
                DISPLAY alert: "Friends field contains invalid characters"
                RETURN false
            ENDIF
    
    STEP 7: Validate shared with field (if provided)
            IF sharedWithStr AND does NOT match /^[a-zA-Z0-9,\s]*$/
                DISPLAY alert: "Shared with field contains invalid characters"
                RETURN false
            ENDIF
    
    STEP 8: All validations passed
            RETURN true
END
```

## 3.10 Algorithm: `validateEventForm()` (JavaScript)

**Location**: script.js:253-327

```
ALGORITHM: validateEventForm()
============================================================
INPUT:  Form fields from DOM
OUTPUT: boolean (true = allow submit, false = block submit)

BEGIN
    STEP 1: Get all form values
            title = getElementById('title').value.trim()
            date = getElementById('date').value
            time = getElementById('time').value
            description = getElementById('description').value
            externalLink = getElementById('external_link').value
            sharedWithStr = getElementById('shared_with_str').value.trim()
    
    STEP 2: Validate title (BUG FIX #1001)
            IF title is empty OR matches /^\s*$/
                DISPLAY alert: "Title is required and cannot be only spaces"
                RETURN false
            ENDIF
    
    STEP 3: Check title maximum length
            IF title.length > 100
                DISPLAY alert: "Title too long (max 100 characters)"
                RETURN false
            ENDIF
    
    STEP 4: Validate date (BUG FIX #1004)
            IF date is empty
                DISPLAY alert: "Date is required"
                RETURN false
            ENDIF
            
            selectedDate = new Date(date)
            today = new Date()
            today.setHours(0, 0, 0, 0)
            
            IF selectedDate is invalid
                DISPLAY alert: "Invalid date format. Use YYYY-MM-DD"
                RETURN false
            ENDIF
            
            IF selectedDate < today
                DISPLAY alert: "Date must be today or in the future"
                RETURN false
            ENDIF
    
    STEP 5: Validate time format
            IF time does NOT match /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
                DISPLAY alert: "Invalid time format. Use HH:MM"
                RETURN false
            ENDIF
    
    STEP 6: Check description length
            IF description.length > 500
                DISPLAY alert: "Description too long (max 500 characters)"
                RETURN false
            ENDIF
    
    STEP 7: Validate external URL (if provided)
            urlRegex = /^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/
            IF externalLink AND does NOT match urlRegex
                DISPLAY alert: "Invalid external link format"
                RETURN false
            ENDIF
    
    STEP 8: Validate shared with field
            IF sharedWithStr AND does NOT match /^[a-zA-Z0-9,\s]*$/
                DISPLAY alert: "Shared with field contains invalid characters"
                RETURN false
            ENDIF
    
    STEP 9: All validations passed
            RETURN true
END
```

---

# 4. Login Flow with Complete Algorithm

## 4.1 Complete Login Flow Algorithm

```
ALGORITHM: Complete Login Flow
============================================================
FILES INVOLVED: login.php, functions.php, script.js, db.php

PHASE 1: PAGE LOAD
═══════════════════
    1.1 Browser requests login.php
    1.2 login.php includes functions.php
    1.3 functions.php starts session (line 32-37)
    1.4 isLoggedIn() checks if already logged in (line 211-214)
        IF logged in → redirect to index.php
    1.5 Initialize $error = '' (line 42)
    1.6 Render HTML form with JavaScript validation

PHASE 2: CLIENT-SIDE VALIDATION (When user clicks Submit)
═════════════════════════════════════════════════════════
    2.1 onsubmit triggers validateLoginForm() in script.js
    2.2 Get email and password values with trim()
    2.3 Check if both fields are filled
        IF empty → show alert, RETURN false (block submission)
    2.4 Validate email format with regex
        IF invalid → show alert, RETURN false
    2.5 All client validations passed → RETURN true (allow submit)

PHASE 3: SERVER-SIDE PROCESSING
═══════════════════════════════
    3.1 login.php receives POST request (line 51)
    3.2 Get email and password from $_POST (line 56-57)
    3.3 Call loginUser(email, password) in functions.php (line 61)

PHASE 4: loginUser() FUNCTION (functions.php line 292-317)
══════════════════════════════════════════════════════════
    4.1 Get database connection
    4.2 Validate email is required: validateRequired(email, "Email")
        IF error → return error message
    4.3 Validate password is required: validateRequired(password, "Password")
        IF error → return error message
    4.4 Query database for user by email (line 302-304)
        SELECT user_id, username, password_hash 
        FROM Users WHERE email = :email AND deleted_at IS NULL
    4.5 Verify password with bcrypt (line 307)
        IF user not found OR password_verify fails
            RETURN "Invalid email or password"
    4.6 Create session (line 312-315)
        - Set $_SESSION['user_id']
        - Set $_SESSION['username']
        - Regenerate session ID for security
        - Update last activity timestamp
    4.7 RETURN null (success, no error)

PHASE 5: POST-LOGIN REDIRECT
════════════════════════════
    5.1 Check if loginUser() returned error (line 65)
        IF no error ($error is falsy)
            Redirect to index.php → EXIT
        ELSE
            Display error in form
```

## 4.2 Login Flow Diagram (Text Representation)

```
[User opens login.php]
        ↓
[Include functions.php]
        ↓
[Session starts]
        ↓
[Check: User logged in?] ──Yes──→ [Redirect to index.php]
        ↓ No
[Display login form]
        ↓
[User enters email/password]
        ↓
[Click Submit]
        ↓
[validateLoginForm() in script.js]
        ↓
[Check: Email filled?] ──No──→ [Alert: Email required] → [Back to form]
        ↓ Yes
[Check: Password filled?] ──No──→ [Alert: Password required] → [Back to form]
        ↓ Yes
[Check: Valid email format?] ──No──→ [Alert: Invalid email] → [Back to form]
        ↓ Yes
[Submit form to server]
        ↓
[login.php receives POST]
        ↓
[Call loginUser()]
        ↓
[Check: Email required?] ──No──→ [Return error] → [Display in form]
        ↓ Yes
[Check: Password required?] ──No──→ [Return error] → [Display in form]
        ↓ Yes
[Query database for user]
        ↓
[Check: User found?] ──No──→ [Return: Invalid credentials] → [Display in form]
        ↓ Yes
[Check: Password correct?] ──No──→ [Return: Invalid credentials]
        ↓ Yes
[Create session]
        ↓
[Regenerate session ID]
        ↓
[Update last activity]
        ↓
[Return success]
        ↓
[Redirect to index.php]
        ↓
[Dashboard loaded]
```

---

# 5. Complete List of All Functional Flows

## 5.1 Authentication Flows

| # | Flow Name | Files | Description |
|---|-----------|-------|-------------|
| 1 | **User Registration** | register.php → functions.php → db.php | New user creates account with username, email, password |
| 2 | **User Login** | login.php → functions.php → db.php | User authenticates and creates session |
| 3 | **User Logout** | header.php → functions.php | Destroys session and redirects to login |
| 4 | **Session Timeout** | Any protected page → functions.php | Auto-logout after 30 minutes inactivity |

## 5.2 Schedule Management Flows (CRUD)

| # | Flow Name | Files | Description |
|---|-----------|-------|-------------|
| 1 | **Add Schedule** | add_schedule.php → functions.php → db.php | Create new gaming schedule |
| 2 | **View Schedules** | index.php → functions.php → db.php | Display all user's schedules |
| 3 | **Edit Schedule** | edit_schedule.php → functions.php → db.php | Modify existing schedule |
| 4 | **Delete Schedule** | delete.php → functions.php → db.php | Remove schedule (soft delete) |

## 5.3 Event Management Flows (CRUD)

| # | Flow Name | Files | Description |
|---|-----------|-------|-------------|
| 1 | **Add Event** | add_event.php → functions.php → db.php | Create gaming event (tournament, stream) |
| 2 | **View Events** | index.php → functions.php → db.php | Display all user's events |
| 3 | **Edit Event** | edit_event.php → functions.php → db.php | Modify existing event |
| 4 | **Delete Event** | delete.php → functions.php → db.php | Remove event (soft delete) |

## 5.4 Friends Management Flows (CRUD)

| # | Flow Name | Files | Description |
|---|-----------|-------|-------------|
| 1 | **Add Friend** | add_friend.php → functions.php → db.php | Add gaming friend by username |
| 2 | **View Friends** | index.php, add_friend.php → functions.php | Display friends list |
| 3 | **Edit Friend** | edit_friend.php → functions.php → db.php | Update friend info/status |
| 4 | **Delete Friend** | delete.php → functions.php → db.php | Remove friend (soft delete) |

## 5.5 Favorite Games Flows (CRUD)

| # | Flow Name | Files | Description |
|---|-----------|-------|-------------|
| 1 | **Add Favorite** | profile.php → functions.php → db.php | Add game to favorites |
| 2 | **View Favorites** | index.php → functions.php → db.php | Display favorite games |
| 3 | **Edit Favorite** | edit_favorite.php → functions.php → db.php | Update favorite details |
| 4 | **Delete Favorite** | delete.php → functions.php → db.php | Remove from favorites |

## 5.6 Support Flows

| # | Flow Name | Files | Description |
|---|-----------|-------|-------------|
| 1 | **Contact Form** | contact.php | User sends support message |
| 2 | **Privacy Policy** | privacy.php | Display privacy information |
| 3 | **User Profile** | profile.php | View/edit user profile |

---

# 6. Code Flow Diagrams

## 6.1 Registration Flow Diagram (Text)

```
[User opens register.php]
        ↓
[Display registration form]
        ↓
[User fills: username, email, password]
        ↓
[Click Register button]
        ↓
[validateRegisterForm() in script.js]
        ↓
[Check: All fields filled?] ──No──→ [Alert] → [Back to form]
        ↓ Yes
[Check: Username only spaces?] ──Yes──→ [Alert] → [Back to form]
        ↓ No
[Check: Username <= 50 chars?] ──No──→ [Alert] → [Back to form]
        ↓ Yes
[Check: Valid email format?] ──No──→ [Alert] → [Back to form]
        ↓ Yes
[Check: Password >= 8 chars?] ──No──→ [Alert] → [Back to form]
        ↓ Yes
[Submit to server]
        ↓
[register.php receives POST]
        ↓
[Call registerUser()]
        ↓
[Validate username]
        ↓
[Validate email]
        ↓
[Validate password]
        ↓
[Check: Password >= 8 chars?] ──No──→ [Return error]
        ↓ Yes
[Check email uniqueness in DB]
        ↓
[Check: Email exists?] ──Yes──→ [Return: Email already registered]
        ↓ No
[Hash password with bcrypt]
        ↓
[INSERT into Users table]
        ↓
[Return success]
        ↓
[Set success message]
        ↓
[Redirect to login.php]
```

## 6.2 Add Schedule Flow Diagram (Text)

```
[User opens add_schedule.php]
        ↓
[Include functions.php]
        ↓
[checkSessionTimeout()]
        ↓
[Check: Session valid?] ──No──→ [Redirect to login.php]
        ↓ Yes
[Check: User logged in?] ──No──→ [Redirect to login.php]
        ↓ Yes
[Get userId from session]
        ↓
[Display schedule form]
        ↓
[User fills form fields]
        ↓
[Submit triggers validateScheduleForm()]
        ↓
[Check: Game title valid?] ──No──→ [Alert error] → [Back to form]
        ↓ Yes
[Check: Date valid and future?] ──No──→ [Alert error] → [Back to form]
        ↓ Yes
[Check: Time format valid?] ──No──→ [Alert error] → [Back to form]
        ↓ Yes
[Check: Friends field valid?] ──No──→ [Alert error] → [Back to form]
        ↓ Yes
[Submit to server]
        ↓
[Call addSchedule()]
        ↓
[validateRequired for gameTitle]
        ↓
[validateDate for date]
        ↓
[validateTime for time]
        ↓
[validateCommaSeparated for friends]
        ↓
[validateCommaSeparated for sharedWith]
        ↓
[All validations passed?] ──No──→ [Return error] → [Display in form]
        ↓ Yes
[getOrCreateGameId()]
        ↓
[INSERT into Schedules table]
        ↓
[Return success]
        ↓
[Set success message]
        ↓
[Redirect to index.php]
```

## 6.3 Delete Flow Diagram (Text)

```
[User clicks Delete button]
        ↓
[JavaScript confirm dialog]
        ↓
[User confirms?] ──No──→ [Cancel, stay on page]
        ↓ Yes
[Navigate to delete.php]
        ↓
[Include functions.php]
        ↓
[checkSessionTimeout()]
        ↓
[User logged in?] ──No──→ [Redirect to login.php]
        ↓ Yes
[Get type and id from URL]
        ↓
[Type = schedule?] ──Yes──→ [Call deleteSchedule()] ──→ [checkOwnership]
        ↓ No                                                    ↓
[Type = event?] ──Yes──→ [Call deleteEvent()] ──────→ [User owns record?]
        ↓ No                                                    ↓ No
[Type = friend?] ──Yes──→ [Call deleteFriend()] ───→ [Return: No permission]
        ↓ No                                                    ↓ Yes
[Type = favorite?] ──Yes──→ [Call deleteFavoriteGame()] ──→ [Soft delete]
        ↓ No                                                    ↓
[Invalid type error]                                    [Set success message]
        ↓                                                       ↓
[Set error message] ←─────────────────────────────────────────────┘
        ↓
[Redirect to referrer page]
```

---

# 7. Login Page Loading Flow Diagram

```
STEP 1: HTTP REQUEST
════════════════════
Browser opens URL: http://localhost/gameplan-scheduler/login.php
        ↓
Apache/XAMPP receives request
        ↓
PHP interpreter starts processing login.php

STEP 2: PHP INITIALIZATION
══════════════════════════
Line 26: require_once 'functions.php'
        ↓
functions.php loads
        ↓
Line 19: ob_start() - Start output buffering
        ↓
Line 22: require_once 'db.php'
        ↓
db.php loads with database constants

STEP 3: SESSION MANAGEMENT
══════════════════════════
Line 32-37: Session check
        ↓
Session already started? ──No──→ session_start()
        ↓ Yes                           ↓
        └───────────────────────→ session_regenerate_id(true)

STEP 4: AUTHENTICATION CHECK
════════════════════════════
Line 35: isLoggedIn() check
        ↓
$_SESSION['user_id'] exists? ──Yes──→ header('Location: index.php')
        ↓ No                                  ↓
Continue to render login form              exit - Stop processing

STEP 5: INITIALIZE VARIABLES
════════════════════════════
Line 42: $error = ''
        ↓
Line 51: Check REQUEST_METHOD
        ↓
Method = POST? ──Yes──→ Process form submission
        ↓ No
Skip form processing

STEP 6: RENDER HTML
═══════════════════
Line 72: Output HTML DOCTYPE
        ↓
Line 73-94: HEAD section
        ↓
Load Bootstrap CSS from CDN
        ↓
Load style.css
        ↓
Line 103: BODY starts
        ↓
Line 109: Container div
        ↓
Line 112: Auth container with glassmorphism
        ↓
Line 118: H1 title "🎮 Login"
        ↓
Line 127-131: Error display (if any)
        ↓
Line 140: Form element with onsubmit="validateLoginForm()"
        ↓
Line 153-159: Email input field
        ↓
Line 172-178: Password input field
        ↓
Line 187-189: Submit button
        ↓
Line 200-205: Register link
        ↓
Line 215: Bootstrap JS from CDN
        ↓
Line 217: Load script.js
        ↓
Line 219-220: Close body/html

STEP 7: JAVASCRIPT READY
════════════════════════
DOMContentLoaded event fires
        ↓
initializeFeatures() runs
        ↓
Setup smooth scroll
        ↓
Setup delete confirmations
        ↓
Setup alert auto-dismiss
        ↓
Page fully loaded and interactive
```

### Critical Files and Functions for Login Page Loading

| Order | File | Function/Line | Purpose |
|-------|------|---------------|---------|
| 1 | login.php | Line 1 | Entry point |
| 2 | functions.php | Line 26 (require) | Load all functions |
| 3 | db.php | Line 22 (require) | Database config |
| 4 | functions.php | Line 32-37 | Session start |
| 5 | functions.php | Line 211-214 (`isLoggedIn()`) | Check login status |
| 6 | login.php | Line 72-220 | HTML render |
| 7 | script.js | Line 346-354 (DOMContentLoaded) | Initialize features |
| 8 | script.js | Line 38-68 (`validateLoginForm()`) | Ready for validation |

---

# 8. Home Page (Dashboard) Loading Flow Diagram

```
STEP 1: HTTP REQUEST
════════════════════
Browser opens URL: http://localhost/gameplan-scheduler/index.php
        ↓
Apache/XAMPP routes to index.php

STEP 2: PHP INITIALIZATION
══════════════════════════
Line 20: require_once 'functions.php'
        ↓
functions.php → db.php loads
        ↓
Session initialized

STEP 3: AUTHENTICATION
══════════════════════
Line 23: checkSessionTimeout()
        ↓
Session expired? (30min) ──Yes──→ session_destroy() → Redirect to login.php
        ↓ No
Update $_SESSION['last_activity']
        ↓
Line 28: isLoggedIn() check
        ↓
User logged in? ──No──→ Redirect to login.php
        ↓ Yes
Line 35: getUserId()

STEP 4: DATA FETCHING
═════════════════════
Line 38: updateLastActivity()
        ↓
Line 44-45: Get sort parameters from URL
        ↓
Line 48: getFriends(userId)
        ↓
Line 49: getFavoriteGames(userId)
        ↓
Line 50: getSchedules(userId, sort)
        ↓
Line 51: getEvents(userId, sort)

STEP 5: RENDER HTML
═══════════════════
Line 56: DOCTYPE and HEAD
        ↓
Load Bootstrap CSS
        ↓
Load style.css
        ↓
Line 70-71: Include header.php
        ↓
Display navigation bar
        ↓
Line 73-75: Main container
        ↓
Line 76: getMessage() - Session messages

STEP 6: RENDER SECTIONS
═══════════════════════
Section 1: Friends List Table
        ↓
Loop through $friends array
        ↓
Display each friend with Edit/Delete
        ↓
Section 2: Favorite Games Table
        ↓
Loop through $favorites array
        ↓
Display each game with Edit/Delete
        ↓
Section 3: Schedules Table with Sort
        ↓
Loop through $schedules array
        ↓
Display each schedule
        ↓
Section 4: Events Table with Sort
        ↓
Loop through $events array
        ↓
Display each event

STEP 7: FINALIZE
════════════════
Include footer.php
        ↓
Load Bootstrap JS
        ↓
Load script.js
        ↓
DOMContentLoaded fires
        ↓
initializeFeatures()
        ↓
Dashboard fully loaded
```

### Critical Files and Functions for Home Page Loading

| Order | File | Function/Line | Purpose |
|-------|------|---------------|---------|
| 1 | index.php | Line 1 | Entry point |
| 2 | functions.php | require | Load functions |
| 3 | functions.php | Line 239-248 (`checkSessionTimeout()`) | Verify session |
| 4 | functions.php | Line 211-214 (`isLoggedIn()`) | Auth check |
| 5 | functions.php | Line 220-223 (`getUserId()`) | Get user ID |
| 6 | functions.php | Line 488-494 (`getFriends()`) | Fetch friends |
| 7 | functions.php | Line 419-425 (`getFavoriteGames()`) | Fetch favorites |
| 8 | functions.php | Line 521-528 (`getSchedules()`) | Fetch schedules |
| 9 | functions.php | Line 591-598 (`getEvents()`) | Fetch events |
| 10 | header.php | include | Navigation |
| 11 | footer.php | include | Footer |
| 12 | script.js | Line 361-398 (`initializeFeatures()`) | Interactive features |

---

# 9. Summary Tables

## 9.1 All Validation Functions Summary

| Category | Function | Location | Bug Fix | Description |
|----------|----------|----------|---------|-------------|
| **Server-Side** | `validateRequired()` | functions.php:68 | #1001 | Checks empty/spaces-only |
| **Server-Side** | `validateDate()` | functions.php:97 | #1004 | Validates date format and future |
| **Server-Side** | `validateTime()` | functions.php:123 | - | Validates HH:MM format |
| **Server-Side** | `validateEmail()` | functions.php:136 | - | Validates email format |
| **Server-Side** | `validateUrl()` | functions.php:148 | - | Validates URL format |
| **Server-Side** | `validateCommaSeparated()` | functions.php:160 | - | Validates comma-separated |
| **Server-Side** | `safeEcho()` | functions.php:50 | - | XSS protection |
| **Server-Side** | `checkOwnership()` | functions.php:640 | - | Permission check |
| **Client-Side** | `validateLoginForm()` | script.js:38 | - | Login validation |
| **Client-Side** | `validateRegisterForm()` | script.js:93 | #1001 | Registration validation |
| **Client-Side** | `validateScheduleForm()` | script.js:163 | #1001, #1004 | Schedule validation |
| **Client-Side** | `validateEventForm()` | script.js:253 | #1001, #1004 | Event validation |

## 9.2 All CRUD Operations Summary

| Entity | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| **User** | `registerUser()` | `isLoggedIn()` | - | - |
| **Session** | `loginUser()` | `getUserId()` | `updateLastActivity()` | `logout()` |
| **Schedule** | `addSchedule()` | `getSchedules()` | `editSchedule()` | `deleteSchedule()` |
| **Event** | `addEvent()` | `getEvents()` | `editEvent()` | `deleteEvent()` |
| **Friend** | `addFriend()` | `getFriends()` | `updateFriend()` | `deleteFriend()` |
| **Favorite** | `addFavoriteGame()` | `getFavoriteGames()` | `updateFavoriteGame()` | `deleteFavoriteGame()` |
| **Game** | `getOrCreateGameId()` | `getGames()` | - | - |

---

**For MBO-4 Examination**: This document provides complete A-Z coverage of all validations, algorithms, functional flows, and code flow diagrams for the GamePlan Scheduler application.

---

**Document End / Einde Document**
