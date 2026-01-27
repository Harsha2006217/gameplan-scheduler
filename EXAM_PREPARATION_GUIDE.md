# MBO-4 EXAMINATION PREPARATION GUIDE
## GamePlan Scheduler - Checklist for Your Exam

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# 📋 PRE-EXAM CHECKLIST

## ✅ Files to Bring/Have Ready

- [ ] All 18 documentation files (printed or digital)
- [ ] Demo video: `K1-W3-DEMO VIDEO.mp4`
- [ ] Project log PDF
- [ ] Realisatie verslag PDF
- [ ] Screenshot folder: `Demo Fotos`
- [ ] This checklist!

---

# 🎯 QUICK ANSWERS FOR COMMON QUESTIONS

## "What is your project about?"

**Answer**: I built a web application called GamePlan Scheduler that helps gamers plan and organize their gaming sessions. Users can:
- Schedule gaming sessions with friends
- Create gaming events with reminders
- Manage a friends list
- Track favorite games

---

## "What technologies did you use?"

| Technology | Purpose |
|------------|---------|
| **PHP** | Backend/server-side logic |
| **MySQL** | Database storage |
| **JavaScript** | Client-side validation |
| **HTML5/CSS3** | Page structure and styling |
| **Bootstrap 5** | Responsive design framework |
| **PDO** | Secure database connection |

---

## "How do you prevent security attacks?"

| Attack | Prevention | File |
|--------|------------|------|
| **SQL Injection** | PDO prepared statements | db.php |
| **XSS** | htmlspecialchars() via safeEcho() | functions.php |
| **Session Hijacking** | session_regenerate_id() | functions.php |
| **Brute Force** | Session timeout (30 min) | functions.php |
| **Unauthorized Access** | Ownership checks | functions.php |

---

## "How do you validate user input?"

**3-Layer Validation**:
1. **HTML5** - `required`, `type="email"`, `maxlength`
2. **JavaScript** - Client-side before submit
3. **PHP** - Server-side before database

---

## "Explain a bug you fixed"

**BUG FIX #1001 - Spaces-Only Input**:

*Problem*: Users could submit forms with only spaces "   "
*Solution*: Added regex check `/^\s*$/` after trim()
*Files Changed*: functions.php, script.js

```php
// Before (broken):
if (empty($value)) { ... }

// After (fixed):
$value = trim($value);
if (empty($value) || preg_match('/^\s*$/', $value)) { ... }
```

---

## "Explain your database design"

**6 Tables**:
| Table | Purpose |
|-------|---------|
| Users | User accounts |
| Games | Available games |
| UserGames | User's favorite games (junction table) |
| Friends | User's friends list |
| Schedules | Gaming schedules |
| Events | Gaming events |

**Key Relationships**:
- 1 User → Many Schedules (1:N)
- 1 User → Many Events (1:N)
- Users ↔ Games via UserGames (N:M)

---

## "What is CRUD?"

**C**reate = Add new records (INSERT)
**R**ead = View records (SELECT)
**U**pdate = Modify records (UPDATE)
**D**elete = Remove records (DELETE)

*My app implements CRUD for: Schedules, Events, Friends, Favorites*

---

## "What is a session?"

A **session** is server-side storage that remembers who is logged in.

```php
// Start session
session_start();

// Store user ID when logged in
$_SESSION['user_id'] = 123;

// Check if logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in
}
```

---

## "How does password hashing work?"

```php
// Registration - hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
// Result: $2y$10$ABC123...xyz (60 characters, unreadable)

// Login - verify the password
if (password_verify($inputPassword, $storedHash)) {
    // Password is correct
}
```

*Why bcrypt?* It's slow (protects against brute force) and salted (each hash is unique).

---

# 📊 KEY NUMBERS TO REMEMBER

| Metric | Value |
|--------|-------|
| Total PHP files | 18 |
| Total functions | 35+ |
| Total validations | 25+ |
| Total test cases | 99 |
| Session timeout | 30 minutes |
| Password min length | 8 characters |
| bcrypt cost factor | 10 (default) |
| Documentation files | 18 |

---

# 🗂️ WHERE TO FIND THINGS

| If asked about... | Show this file |
|-------------------|----------------|
| All validations | VALIDATION_DOCUMENTATION.md |
| Algorithms | ALGORITHMS_SUBMISSION.md |
| Visual diagrams | CODE_FLOW_DIAGRAMS.md |
| Database schema | DATABASE_DOCUMENTATION.md |
| Security | SECURITY_DOCUMENTATION.md |
| Test cases | VALIDATION_TEST_CASES.md |
| How to use | USER_MANUAL.md |
| File structure | FILE_DEPENDENCIES.md |
| Technical terms | GLOSSARY.md |

---

# 🎤 DEMO SCRIPT

## 1. Login Demo (2 min)
1. Show login page
2. Try empty form → show validation
3. Try wrong password → show error
4. Login successfully → show dashboard

## 2. Schedule Demo (2 min)
1. Click "Add Schedule"
2. Show date validation (try past date)
3. Add a valid schedule
4. Edit the schedule
5. Delete the schedule

## 3. Security Demo (1 min)
1. Show password is hashed in database
2. Show XSS protection (try `<script>`)
3. Show session timeout setting

---

# ✨ FINAL TIPS

1. **Stay calm** - You know this project!
2. **Use documentation** - Point to specific files
3. **Show don't tell** - Demo the application
4. **Explain simply** - Pretend they don't know code
5. **Mention bug fixes** - Shows problem-solving skills

---

**GOOD LUCK WITH YOUR EXAM! / SUCCES MET JE EXAMEN!** 🎮

---

**END OF PREPARATION GUIDE**
