# CODE FLOW DIAGRAMS
## GamePlan Scheduler - Visual Flow Charts

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 2026-01-19

---

# 1. LOGIN PAGE LOADING FLOW

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        LOGIN PAGE LOADING FLOW                               │
│                        (login.php → index.php)                               │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────────┐
    │  Browser opens   │
    │   login.php      │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ require_once     │
    │ 'functions.php'  │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ functions.php    │
    │ loads db.php     │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ session_start()  │
    │ Session begins   │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐      ┌──────────────────┐
    │  isLoggedIn()?   │─YES─▶│ Redirect to      │
    │  Check session   │      │ index.php        │
    └────────┬─────────┘      └──────────────────┘
             │ NO
             ▼
    ┌──────────────────┐
    │ Render login     │
    │ form HTML        │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ Load script.js   │
    │ (JavaScript)     │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ DOMContentLoaded │
    │ Initialize       │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ PAGE READY       │
    │ Waiting for user │
    └──────────────────┘
```

---

# 2. LOGIN FORM SUBMISSION FLOW

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     LOGIN FORM SUBMISSION FLOW                               │
│              (User click → Validation → Authentication)                      │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────────┐
    │ User enters      │
    │ email + password │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ Click "Login"    │
    │ button           │
    └────────┬─────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ CLIENT-SIDE VALIDATION (script.js)                                          │
├─────────────────────────────────────────────────────────────────────────────┤
             │
             ▼
    ┌──────────────────┐      ┌──────────────────┐
    │ Email filled?    │─NO──▶│ Alert: "Email    │───┐
    │                  │      │ required"        │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ YES                                    │
             ▼                                        │
    ┌──────────────────┐      ┌──────────────────┐   │
    │ Password filled? │─NO──▶│ Alert: "Password │───┤
    │                  │      │ required"        │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ YES                                    │
             ▼                                        │
    ┌──────────────────┐      ┌──────────────────┐   │
    │ Valid email      │─NO──▶│ Alert: "Invalid  │───┤
    │ format? (regex)  │      │ email format"    │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ YES                                    │
             ▼                                        │
    ┌──────────────────┐      ┌──────────────────┐   │
    │ return true      │      │ return false     │◀──┘
    │ (submit form)    │      │ (block submit)   │
    └────────┬─────────┘      └──────────────────┘
             │
├─────────────────────────────────────────────────────────────────────────────┤
│ SERVER-SIDE VALIDATION (functions.php)                                       │
├─────────────────────────────────────────────────────────────────────────────┤
             │
             ▼
    ┌──────────────────┐
    │ login.php        │
    │ receives POST    │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ Call loginUser() │
    │ in functions.php │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐      ┌──────────────────┐
    │ validateRequired │─ERR─▶│ Return error     │───┐
    │ (email)          │      │ message          │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ OK                                     │
             ▼                                        │
    ┌──────────────────┐      ┌──────────────────┐   │
    │ validateRequired │─ERR─▶│ Return error     │───┤
    │ (password)       │      │ message          │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ OK                                     │
             ▼                                        │
    ┌──────────────────┐      ┌──────────────────┐   │
    │ Query database   │─NO──▶│ Return: "Invalid│───┤
    │ User found?      │      │ credentials"     │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ YES                                    │
             ▼                                        │
    ┌──────────────────┐      ┌──────────────────┐   │
    │ password_verify  │─FAIL▶│ Return: "Invalid │───┤
    │ Check hash       │      │ credentials"     │   │
    └────────┬─────────┘      └──────────────────┘   │
             │ OK                                     │
             ▼                                        ▼
    ┌──────────────────┐      ┌──────────────────┐
    │ Create session   │      │ Display error    │
    │ Set user_id      │      │ in form          │
    └────────┬─────────┘      └──────────────────┘
             │
             ▼
    ┌──────────────────┐
    │ Redirect to      │
    │ index.php        │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ DASHBOARD        │
    │ LOGIN SUCCESS!   │
    └──────────────────┘
```

---

# 3. HOME PAGE (DASHBOARD) LOADING FLOW

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     HOME PAGE LOADING FLOW                                   │
│                        (index.php)                                           │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────────┐
    │  Browser opens   │
    │   index.php      │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ require_once     │
    │ 'functions.php'  │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐      ┌──────────────────┐
    │ checkSession     │─EXP─▶│ session_destroy  │
    │ Timeout()        │      │ → login.php      │
    └────────┬─────────┘      └──────────────────┘
             │ OK
             ▼
    ┌──────────────────┐      ┌──────────────────┐
    │  isLoggedIn()?   │─NO──▶│ Redirect to      │
    │                  │      │ login.php        │
    └────────┬─────────┘      └──────────────────┘
             │ YES
             ▼
    ┌──────────────────┐
    │ getUserId()      │
    │ Get user ID      │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ updateLast       │
    │ Activity()       │
    └────────┬─────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ FETCH ALL DATA FROM DATABASE                                                 │
├─────────────────────────────────────────────────────────────────────────────┤
    ┌──────────────────┐
    │ getFriends()     │──▶ Returns friends array
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ getFavoriteGames │──▶ Returns favorites array
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ getSchedules()   │──▶ Returns schedules array
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ getEvents()      │──▶ Returns events array
    └────────┬─────────┘
├─────────────────────────────────────────────────────────────────────────────┤
             │
             ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ RENDER HTML SECTIONS                                                         │
├─────────────────────────────────────────────────────────────────────────────┤
    ┌──────────────────┐
    │ include          │
    │ header.php       │ ──▶ Navigation bar
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ getMessage()     │ ──▶ Session messages
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ Section 1:       │
    │ Friends Table    │ ──▶ Loop $friends
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ Section 2:       │
    │ Favorites Table  │ ──▶ Loop $favorites
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ Section 3:       │
    │ Schedules Table  │ ──▶ Loop $schedules (with sort)
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ Section 4:       │
    │ Events Table     │ ──▶ Loop $events (with sort)
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ include          │
    │ footer.php       │
    └────────┬─────────┘
├─────────────────────────────────────────────────────────────────────────────┤
             │
             ▼
    ┌──────────────────┐
    │ Load script.js   │
    │ initFeatures()   │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ DASHBOARD READY  │
    │ User can interact│
    └──────────────────┘
```

---

# 4. REGISTRATION FLOW

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        REGISTRATION FLOW                                     │
│                        (register.php)                                        │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────────┐
    │ User fills form: │
    │ • Username       │
    │ • Email          │
    │ • Password       │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ Click "Register" │
    └────────┬─────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ CLIENT VALIDATION (validateRegisterForm)                                     │
├─────────────────────────────────────────────────────────────────────────────┤
    ┌──────────────────┐
    │ All fields       │─NO──▶ Alert → Back to form
    │ filled?          │
    └────────┬─────────┘
             │ YES
    ┌──────────────────┐
    │ Username only    │─YES─▶ Alert: "Cannot be        ← BUG #1001
    │ spaces? /^\s*$/  │       only spaces"
    └────────┬─────────┘
             │ NO
    ┌──────────────────┐
    │ Username <= 50   │─NO──▶ Alert: "Too long"
    │ chars?           │
    └────────┬─────────┘
             │ YES
    ┌──────────────────┐
    │ Valid email      │─NO──▶ Alert: "Invalid email"
    │ format?          │
    └────────┬─────────┘
             │ YES
    ┌──────────────────┐
    │ Password >= 8    │─NO──▶ Alert: "Too short"
    │ chars?           │
    └────────┬─────────┘
             │ YES
├─────────────────────────────────────────────────────────────────────────────┤
│ SERVER VALIDATION (registerUser)                                             │
├─────────────────────────────────────────────────────────────────────────────┤
             │
    ┌──────────────────┐
    │ validateRequired │─ERR─▶ Return error
    │ (username)       │
    └────────┬─────────┘
             │ OK
    ┌──────────────────┐
    │ validateEmail()  │─ERR─▶ Return error
    └────────┬─────────┘
             │ OK
    ┌──────────────────┐
    │ validateRequired │─ERR─▶ Return error
    │ (password)       │
    └────────┬─────────┘
             │ OK
    ┌──────────────────┐
    │ Password >= 8    │─NO──▶ Return error
    │ chars?           │
    └────────┬─────────┘
             │ YES
    ┌──────────────────┐
    │ Check email      │─YES─▶ Return: "Email
    │ exists in DB?    │       already registered"
    └────────┬─────────┘
             │ NO
    ┌──────────────────┐
    │ Hash password    │
    │ (bcrypt)         │
    └────────┬─────────┘
             │
    ┌──────────────────┐
    │ INSERT into      │
    │ Users table      │
    └────────┬─────────┘
             │
├─────────────────────────────────────────────────────────────────────────────┤
             │
             ▼
    ┌──────────────────┐
    │ Set success msg  │
    │ Redirect login   │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ REGISTRATION     │
    │ SUCCESS!         │
    └──────────────────┘
```

---

# 5. ADD/EDIT/DELETE CRUD FLOW (Generic)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        CRUD OPERATIONS FLOW                                  │
│              (Schedules, Events, Friends, Favorites)                         │
└─────────────────────────────────────────────────────────────────────────────┘

                          ┌───────────────────┐
                          │    DASHBOARD      │
                          │    (index.php)    │
                          └─────────┬─────────┘
                                    │
          ┌─────────────────────────┼─────────────────────────┐
          │                         │                         │
          ▼                         ▼                         ▼
┌─────────────────┐      ┌─────────────────┐      ┌─────────────────┐
│     CREATE      │      │      READ       │      │     UPDATE      │
│  add_*.php      │      │   index.php     │      │  edit_*.php     │
└────────┬────────┘      └────────┬────────┘      └────────┬────────┘
         │                        │                        │
         ▼                        ▼                        ▼
┌─────────────────┐      ┌─────────────────┐      ┌─────────────────┐
│ 1. Auth check   │      │ 1. Auth check   │      │ 1. Auth check   │
│ 2. Show form    │      │ 2. Fetch data   │      │ 2. Ownership    │
│ 3. Validate     │      │ 3. Display      │      │ 3. Show form    │
│ 4. INSERT       │      │    tables       │      │ 4. Validate     │
│ 5. Redirect     │      │                 │      │ 5. UPDATE       │
└────────┬────────┘      └─────────────────┘      └────────┬────────┘
         │                                                  │
         └──────────────────────┬───────────────────────────┘
                                │
                                ▼
                      ┌─────────────────┐
                      │     DELETE      │
                      │   delete.php    │
                      └────────┬────────┘
                               │
                               ▼
                      ┌─────────────────┐
                      │ 1. Auth check   │
                      │ 2. Get type/id  │
                      │ 3. Ownership    │
                      │ 4. Soft delete  │
                      │    (deleted_at) │
                      │ 5. Redirect     │
                      └─────────────────┘
```

---

# 6. VALIDATION LAYERS DIAGRAM

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     THREE LAYERS OF VALIDATION                               │
└─────────────────────────────────────────────────────────────────────────────┘

         ┌─────────────────────────────────────────────────────────────┐
LAYER 1: │                    HTML5 VALIDATION                         │
         │  - required attribute     - type="email"                    │
         │  - maxlength="50"         - type="date"                     │
         │  - minlength="8"          - min="today"                     │
         │  • Built into browser     • Immediate feedback              │
         └──────────────────────────────┬──────────────────────────────┘
                                        │ If passes...
                                        ▼
         ┌─────────────────────────────────────────────────────────────┐
LAYER 2: │                  JAVASCRIPT VALIDATION                      │
         │  - validateLoginForm()     - BUG FIX #1001 (spaces)         │
         │  - validateRegisterForm()  - BUG FIX #1004 (dates)          │
         │  - validateScheduleForm()  - Custom regex patterns          │
         │  - validateEventForm()     - Real-time alerts               │
         │  • Runs before submit      • User-friendly messages         │
         └──────────────────────────────┬──────────────────────────────┘
                                        │ If passes...
                                        ▼
         ┌─────────────────────────────────────────────────────────────┐
LAYER 3: │                     PHP VALIDATION                          │
         │  - validateRequired()      - validateDate()                 │
         │  - validateEmail()         - validateTime()                 │
         │  - validateUrl()           - validateCommaSeparated()       │
         │  - checkOwnership()        - Email uniqueness check         │
         │  • Server-side security    • Database constraints           │
         └──────────────────────────────┬──────────────────────────────┘
                                        │ If passes...
                                        ▼
         ┌─────────────────────────────────────────────────────────────┐
         │                    DATABASE OPERATION                       │
         │  - INSERT / UPDATE / DELETE with prepared statements        │
         │  • SQL injection protected  • Data integrity ensured        │
         └─────────────────────────────────────────────────────────────┘
```

---

# 7. FILE DEPENDENCY DIAGRAM

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     FILE DEPENDENCIES                                        │
└─────────────────────────────────────────────────────────────────────────────┘

                        ┌─────────────────┐
                        │    db.php       │
                        │  (Database)     │
                        │  • Connection   │
                        │  • Constants    │
                        └────────┬────────┘
                                 │
                                 ▼
                        ┌─────────────────┐
                        │  functions.php  │
                        │  (Core Logic)   │
                        │  • Validations  │
                        │  • CRUD funcs   │
                        │  • Auth funcs   │
                        │  • Session mgmt │
                        └────────┬────────┘
                                 │
         ┌───────────────────────┼───────────────────────┐
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   login.php     │    │   index.php     │    │   Other Pages   │
│                 │    │  (Dashboard)    │    │                 │
│ • Auth form     │    │ • All data      │    │ • add_*.php     │
│ • Session       │    │ • 4 sections    │    │ • edit_*.php    │
└────────┬────────┘    └────────┬────────┘    │ • delete.php    │
         │                      │              │ • profile.php   │
         │                      │              └─────────────────┘
         │                      │
         │                      ▼
         │             ┌─────────────────┐
         │             │   header.php    │
         │             │ (Navigation)    │
         │             └────────┬────────┘
         │                      │
         │                      ▼
         │             ┌─────────────────┐
         │             │   footer.php    │
         │             └─────────────────┘
         │
         └─────────────────────────────────────────────────────────┐
                                                                   │
                                                                   ▼
                                                          ┌─────────────────┐
                                                          │   script.js     │
                                                          │ (Client-side)   │
                                                          │ • Form validate │
                                                          │ • UI features   │
                                                          └─────────────────┘
```

---

**END OF CODE FLOW DIAGRAMS**

These ASCII diagrams visualize all critical flows in the GamePlan Scheduler application.
Ready for MBO-4 submission.
