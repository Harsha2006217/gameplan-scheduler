# FILE DEPENDENCIES & ARCHITECTURE
## GamePlan Scheduler - How Files Connect

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# 1. Architecture Overview / Architectuur Overzicht

```
┌─────────────────────────────────────────────────────────────────────┐
│                        BROWSER (CLIENT)                              │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                  │
│  │   HTML      │  │  CSS        │  │  JavaScript │                  │
│  │  (Pages)    │  │  (style.css)│  │  (script.js)│                  │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘                  │
└─────────┼────────────────┼────────────────┼─────────────────────────┘
          │                │                │
          ▼                ▼                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        WEB SERVER (PHP)                              │
│  ┌─────────────────────────────────────────────────────────────┐    │
│  │                    PAGE FILES                                │    │
│  │  login.php  register.php  index.php  profile.php            │    │
│  │  add_schedule.php  add_event.php  add_friend.php            │    │
│  │  edit_*.php  delete.php  contact.php  privacy.php           │    │
│  └────────────────────────┬────────────────────────────────────┘    │
│                           │                                          │
│                           ▼                                          │
│  ┌─────────────────────────────────────────────────────────────┐    │
│  │                   SHARED FILES                               │    │
│  │  header.php (navigation)  footer.php  functions.php         │    │
│  └────────────────────────┬────────────────────────────────────┘    │
│                           │                                          │
│                           ▼                                          │
│  ┌─────────────────────────────────────────────────────────────┐    │
│  │                   DATABASE LAYER                             │    │
│  │                      db.php                                  │    │
│  └────────────────────────┬────────────────────────────────────┘    │
└───────────────────────────┼─────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        DATABASE (MySQL)                              │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐  ┌───────────┐        │
│  │   Users   │  │  Friends  │  │ Schedules │  │  Events   │        │
│  └───────────┘  └───────────┘  └───────────┘  └───────────┘        │
│  ┌───────────┐  ┌───────────┐                                       │
│  │   Games   │  │ UserGames │                                       │
│  └───────────┘  └───────────┘                                       │
└─────────────────────────────────────────────────────────────────────┘
```

---

# 2. File Dependency Map / Bestandsafhankelijkheden

## 2.1 Core Dependencies

```
Every PHP page requires:
├── db.php (database connection)
├── functions.php (all logic)
├── header.php (navigation + session start)
└── footer.php (scripts + closing tags)
```

## 2.2 Include Chain

```
1. User visits login.php
   ├── include 'db.php'          ← Database connection
   ├── include 'functions.php'    ← All functions
   ├── include 'header.php'       ← Navigation bar
   │   └── (starts session via initSession())
   ├── [PAGE CONTENT]             ← Login form
   └── include 'footer.php'       ← Scripts, Bootstrap
       └── references script.js   ← Validation
```

---

# 3. Detailed File Map

## 3.1 Public Pages (No Login Required)

| File | Includes | Purpose |
|------|----------|---------|
| `login.php` | db, functions, header, footer | User login |
| `register.php` | db, functions, header, footer | User registration |
| `contact.php` | header, footer | Contact information |
| `privacy.php` | header, footer | Privacy policy |

## 3.2 Protected Pages (Login Required)

| File | Includes | Purpose |
|------|----------|---------|
| `index.php` | db, functions, header, footer | Dashboard |
| `profile.php` | db, functions, header, footer | User profile |
| `add_schedule.php` | db, functions, header, footer | Add schedule |
| `edit_schedule.php` | db, functions, header, footer | Edit schedule |
| `add_event.php` | db, functions, header, footer | Add event |
| `edit_event.php` | db, functions, header, footer | Edit event |
| `add_friend.php` | db, functions, header, footer | Add friend |
| `edit_friend.php` | db, functions, header, footer | Edit friend |
| `edit_favorite.php` | db, functions, header, footer | Edit favorite |
| `delete.php` | db, functions | Delete handler |

## 3.3 Included Files

| File | Included By | Purpose |
|------|-------------|---------|
| `db.php` | All PHP pages | Database connection |
| `functions.php` | All PHP pages | Business logic |
| `header.php` | All PHP pages | Navigation bar |
| `footer.php` | All PHP pages | Footer & scripts |

## 3.4 Static Files

| File | Used By | Purpose |
|------|---------|---------|
| `style.css` | header.php | All styling |
| `script.js` | footer.php | Client validation |
| `database.sql` | (Setup only) | Schema creation |

---

# 4. Function Dependencies

## 4.1 functions.php Internal Dependencies

```
loginUser()
├── calls: getConnection()    [from db.php]
├── calls: password_verify()  [PHP built-in]
└── sets: $_SESSION variables

registerUser()
├── calls: getConnection()
├── calls: validateEmail()    [same file]
├── calls: validateRequired() [same file]
└── calls: password_hash()    [PHP built-in]

addSchedule()
├── calls: getConnection()
├── calls: validateRequired()
├── calls: validateDate()
├── calls: validateTime()
└── calls: validateCommaSeparated()
```

## 4.2 Cross-File Dependencies

```
login.php
└── uses: loginUser()         [functions.php]
    └── uses: getConnection() [db.php]

script.js
└── validateLoginForm()       [standalone, no dependencies]
```

---

# 5. Data Flow Diagrams

## 5.1 Login Flow

```
[Browser]                    [Server]                    [Database]
    │                            │                            │
    │ 1. Submit form             │                            │
    │ ─────────────────────────► │                            │
    │                            │ 2. Query user              │
    │                            │ ─────────────────────────► │
    │                            │ 3. Return user data        │
    │                            │ ◄───────────────────────── │
    │                            │ 4. Verify password         │
    │                            │ 5. Create session          │
    │ 6. Redirect to dashboard   │                            │
    │ ◄───────────────────────── │                            │
```

## 5.2 CRUD Flow

```
[User Action]
      │
      ▼
[JavaScript Validation] ─── FAIL ──► [Show Error]
      │
      │ PASS
      ▼
[Form Submit to PHP]
      │
      ▼
[PHP Validation] ─── FAIL ──► [Show Error Message]
      │
      │ PASS
      ▼
[Database Operation]
      │
      ▼
[Redirect with Success Message]
```

---

# 6. Session Flow

```
1. User visits any page
      │
      ▼
2. header.php includes functions.php
      │
      ▼
3. initSession() starts session
      │
      ▼
4. checkSessionTimeout() runs
      │
      ├── If > 30 min inactive ──► logout() ──► login.php
      │
      └── If active ──► updateLastActivity()
```

---

# 7. File Size Summary

| Category | Files | Total Size |
|----------|-------|------------|
| PHP Core | 4 files | ~54 KB |
| PHP Pages | 15 files | ~85 KB |
| CSS | 1 file | ~17 KB |
| JavaScript | 1 file | ~17 KB |
| SQL | 1 file | ~20 KB |
| **Total Code** | **22 files** | **~193 KB** |
| Documentation | 17 files | ~225 KB |
| **Grand Total** | **39 files** | **~418 KB** |

---

# 8. Quick Reference

## What calls what?

| When you... | These files are used |
|-------------|---------------------|
| Login | login.php → functions.php → db.php |
| View dashboard | index.php → functions.php → db.php |
| Add schedule | add_schedule.php → functions.php → db.php |
| Delete item | delete.php → functions.php → db.php |

## What validates what?

| Form | JS Validator | PHP Validator |
|------|--------------|---------------|
| Login | validateLoginForm() | loginUser() |
| Register | validateRegisterForm() | registerUser() |
| Schedule | validateScheduleForm() | addSchedule() |
| Event | validateEventForm() | addEvent() |

---

**END OF FILE DEPENDENCIES & ARCHITECTURE**
