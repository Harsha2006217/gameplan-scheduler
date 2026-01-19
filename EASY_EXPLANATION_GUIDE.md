# EASY EXPLANATION GUIDE
## GamePlan Scheduler - Simple Beginner's Guide
## Eenvoudige Uitleg Handleiding voor Beginners

**Author/Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Date/Datum**: 19-01-2026

---

# What is GamePlan Scheduler? / Wat is GamePlan Scheduler?

**English**: GamePlan Scheduler is a website where gamers can:
- Create an account to login
- Add their gaming friends
- Schedule when they will play games
- Add gaming events like tournaments
- Save their favorite games

**Nederlands**: GamePlan Scheduler is een website waar gamers kunnen:
- Een account maken om in te loggen
- Hun gaming vrienden toevoegen
- Plannen wanneer ze games gaan spelen
- Gaming evenementen toevoegen zoals toernooien
- Hun favoriete games opslaan

---

# What is Validation? / Wat is Validatie?

**English**: 
Validation is like a security guard checking if data is correct before saving it.

**Example**: If someone types "abc" in a date field, validation says "No, that's not a date!" and stops the data from being saved.

**Nederlands**:
Validatie is als een beveiliger die controleert of gegevens correct zijn voordat ze worden opgeslagen.

**Voorbeeld**: Als iemand "abc" typt in een datum veld, zegt validatie "Nee, dat is geen datum!" en voorkomt dat de gegevens worden opgeslagen.

---

# The 3 Layers of Validation / De 3 Lagen van Validatie

Think of it like 3 security gates:

```
🚪 GATE 1: HTML5 (Browser)
   ↓ If passes...
🚪 GATE 2: JavaScript (Before sending)
   ↓ If passes...
🚪 GATE 3: PHP (Server checks again)
   ↓ If passes...
✅ DATA IS SAVED
```

## Gate 1: HTML5 Validation / Poort 1: HTML5 Validatie
- Built into the browser
- Shows red outline if field is wrong
- Example: `required` attribute prevents empty fields

## Gate 2: JavaScript Validation / Poort 2: JavaScript Validatie  
- Runs when you click Submit
- Shows alert boxes with error messages
- Checks more complex rules (like email format)

## Gate 3: PHP Validation / Poort 3: PHP Validatie
- Runs on the server
- Final security check
- Even if someone bypasses JavaScript, PHP will catch it

---

# All Validations Explained Simply / Alle Validaties Eenvoudig Uitgelegd

## 1. Required Field Check / Verplicht Veld Controle
**What it does**: Makes sure the field is not empty and not just spaces.

```
✗ Empty: ""           → ERROR
✗ Just spaces: "   "  → ERROR  (BUG FIX #1001!)
✓ Real text: "Hello"  → OK
```

## 2. Date Check / Datum Controle
**What it does**: Makes sure the date is real and not in the past.

```
✗ Invalid: "2025-13-45" → ERROR (month 13 doesn't exist!)  (BUG FIX #1004!)
✗ Past: "2020-01-01"    → ERROR (already happened)
✓ Valid: "2026-02-15"   → OK
```

## 3. Time Check / Tijd Controle
**What it does**: Makes sure time is in correct format.

```
✗ Invalid: "25:99"  → ERROR (no 25 hours!)
✓ Valid: "14:30"    → OK
```

## 4. Email Check / E-mail Controle
**What it does**: Makes sure email has correct format.

```
✗ Invalid: "hello"        → ERROR (no @)
✗ Invalid: "hello@"       → ERROR (no domain)
✓ Valid: "user@email.com" → OK
```

## 5. URL Check / URL Controle
**What it does**: Makes sure URL has correct format.

```
✗ Invalid: "hello"              → ERROR
✓ Valid: "https://example.com"  → OK
```

## 6. Comma-Separated Check / Komma-Gescheiden Controle
**What it does**: Makes sure lists don't have empty items.

```
✗ Invalid: "a, , b"    → ERROR (empty item in middle)
✓ Valid: "a, b, c"     → OK
```

---

# Login Flow - Step by Step / Login Stroom - Stap voor Stap

Here's what happens when you click Login:

```
STEP 1: You type email and password
        Je typt e-mail en wachtwoord
              ↓
STEP 2: You click "Login" button
        Je klikt op de "Login" knop
              ↓
STEP 3: JavaScript checks if fields are filled
        JavaScript controleert of velden ingevuld zijn
              ↓
        ❌ Empty? → Shows alert "Email required"
        ✅ Filled? → Continue...
              ↓
STEP 4: JavaScript checks email format
        JavaScript controleert e-mail formaat
              ↓
        ❌ Invalid? → Shows alert "Invalid email"
        ✅ Valid? → Form is sent to server...
              ↓
STEP 5: PHP receives the data
        PHP ontvangt de gegevens
              ↓
STEP 6: PHP checks if email exists in database
        PHP controleert of e-mail bestaat in database
              ↓
        ❌ Not found? → Shows "Invalid credentials"
        ✅ Found? → Continue...
              ↓
STEP 7: PHP checks if password matches
        PHP controleert of wachtwoord overeenkomt
              ↓
        ❌ Wrong? → Shows "Invalid credentials"
        ✅ Correct? → Continue...
              ↓
STEP 8: PHP creates a session (like a temporary ID card)
        PHP maakt een sessie (zoals een tijdelijke ID kaart)
              ↓
STEP 9: You are redirected to the Dashboard!
        Je wordt doorgestuurd naar het Dashboard!
              ↓
🎮 SUCCESS! You are logged in!
   SUCCES! Je bent ingelogd!
```

---

# What is a Session? / Wat is een Sessie?

**English**:
A session is like a temporary ID card that the website gives you after you login.

- When you login successfully, the website creates a session
- The session stores your user ID (like: "This is user #5")
- Every page checks: "Does this person have a valid session?"
- If yes → show the page
- If no → send them back to login
- After 30 minutes of no activity, the session expires (for security)

**Nederlands**:
Een sessie is als een tijdelijke ID kaart die de website je geeft na het inloggen.

- Wanneer je succesvol inlogt, maakt de website een sessie
- De sessie bewaart je gebruiker ID (zoals: "Dit is gebruiker #5")
- Elke pagina controleert: "Heeft deze persoon een geldige sessie?"
- Indien ja → toon de pagina
- Indien nee → stuur terug naar login
- Na 30 minuten inactiviteit verloopt de sessie (voor veiligheid)

---

# What is CRUD? / Wat is CRUD?

CRUD stands for the 4 basic operations:

| Letter | English | Nederlands | Example |
|--------|---------|------------|---------|
| **C** | Create | Aanmaken | Add new schedule |
| **R** | Read | Lezen | View your schedules |
| **U** | Update | Bijwerken | Edit a schedule |
| **D** | Delete | Verwijderen | Remove a schedule |

Every feature uses CRUD:
- Schedules: Create, Read, Update, Delete
- Events: Create, Read, Update, Delete
- Friends: Create, Read, Update, Delete
- Favorites: Create, Read, Update, Delete

---

# File Structure - What Does Each File Do? / Wat Doet Elk Bestand?

```
📁 gameplan-scheduler/
│
├── 🔐 AUTHENTICATION (Login/Registration)
│   ├── login.php       → Login page / Login pagina
│   ├── register.php    → Registration page / Registratie pagina
│   └── functions.php   → Login/logout functions
│
├── 📱 MAIN PAGES
│   ├── index.php       → Dashboard (main page after login)
│   ├── profile.php     → User profile / Gebruikersprofiel
│   └── contact.php     → Contact page
│
├── 📅 SCHEDULE MANAGEMENT
│   ├── add_schedule.php    → Add new schedule
│   └── edit_schedule.php   → Edit existing schedule
│
├── 🎯 EVENT MANAGEMENT
│   ├── add_event.php       → Add new event
│   └── edit_event.php      → Edit existing event
│
├── 👥 FRIENDS MANAGEMENT
│   ├── add_friend.php      → Add new friend
│   └── edit_friend.php     → Edit friend
│
├── 🎮 FAVORITES MANAGEMENT
│   └── edit_favorite.php   → Edit favorite game
│
├── 🗑️ DELETE HANDLING
│   └── delete.php          → Handles all delete operations
│
├── ⚙️ CORE FILES
│   ├── db.php          → Database connection
│   ├── functions.php   → All validation & helper functions
│   ├── header.php      → Navigation bar (included in all pages)
│   └── footer.php      → Footer (included in all pages)
│
├── 🎨 STYLING & SCRIPTS
│   ├── style.css       → All CSS styling
│   └── script.js       → JavaScript validations
│
└── 📊 DATABASE
    └── database.sql    → SQL to create database tables
```

---

# The 2 Bug Fixes / De 2 Bug Fixes

## BUG #1001: Spaces-Only Fields / Alleen-Spaties Velden

**The Problem / Het Probleem**:
Before the fix, users could submit forms with only spaces like "     " and it would be accepted as valid input.

**The Solution / De Oplossing**:
Added regex check `/^\s*$/` that detects if input contains only whitespace.

```
BEFORE FIX:
"     " → ✓ Accepted (BAD!)

AFTER FIX:
"     " → ✗ Rejected (GOOD!)
"Hello" → ✓ Accepted (GOOD!)
```

## BUG #1004: Invalid Date Format / Ongeldig Datum Formaat

**The Problem / Het Probleem**:
Before the fix, impossible dates like "2025-13-45" (month 13, day 45) were accepted.

**The Solution / De Oplossing**:
Used `DateTime::createFromFormat` with exact matching to validate dates properly.

```
BEFORE FIX:
"2025-13-45" → ✓ Accepted (BAD! Month 13 doesn't exist!)

AFTER FIX:
"2025-13-45" → ✗ Rejected (GOOD!)
"2025-06-15" → ✓ Accepted (GOOD!)
```

---

# Security Features / Beveiligingsfuncties

| Feature | What It Does | Wat Het Doet |
|---------|--------------|--------------|
| **Password Hashing** | Passwords are never stored as plain text. They are encrypted with bcrypt. | Wachtwoorden worden nooit als platte tekst opgeslagen. Ze worden versleuteld met bcrypt. |
| **Session Regeneration** | Session ID changes after login to prevent hijacking. | Sessie ID verandert na login om kaping te voorkomen. |
| **XSS Protection** | `safeEcho()` prevents malicious code injection. | `safeEcho()` voorkomt kwaadaardige code injectie. |
| **Session Timeout** | Auto-logout after 30 minutes inactivity. | Auto-uitloggen na 30 minuten inactiviteit. |
| **Ownership Check** | Users can only edit/delete their own data. | Gebruikers kunnen alleen hun eigen data bewerken/verwijderen. |
| **Prepared Statements** | SQL injection protection. | SQL injectie bescherming. |

---

# Quick Summary for Examiner / Snelle Samenvatting voor Examinator

## What This Application Does / Wat Deze Applicatie Doet:
✓ User registration and login with secure sessions
✓ CRUD operations for schedules, events, friends, and favorites
✓ 3-layer validation (HTML5, JavaScript, PHP)
✓ 2 bug fixes implemented (#1001, #1004)
✓ Responsive design with Bootstrap
✓ Bilingual support (English/Dutch)

## Documentation Provided / Documentatie Verstrekt:
1. VALIDATION_DOCUMENTATION.md - Complete A-Z guide
2. ALGORITHMS_SUBMISSION.md - All 12 algorithms
3. CODE_FLOW_DIAGRAMS.md - 7 visual flowcharts
4. VALIDATION_QUICK_REFERENCE.md - Quick tables
5. VALIDATIE_DOCUMENTATIE_NL.md - Dutch version
6. EASY_EXPLANATION_GUIDE.md - This beginner's guide

---

**END OF DOCUMENT / EINDE DOCUMENT**

This guide explains the GamePlan Scheduler application in simple terms for easy understanding.
Deze handleiding legt de GamePlan Scheduler applicatie uit in eenvoudige termen voor makkelijk begrip.
