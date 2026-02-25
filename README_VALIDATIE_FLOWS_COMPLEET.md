# GAMEPLAN SCHEDULER — COMPLETE VALIDATIE, ALGORITMEN, FUNCTIONELE FLOWS & CODE FLOW DIAGRAMMEN

**Auteur:** Harsha Kanaparthi | **Studentnummer:** 2195344 | **Datum:** 30-09-2025  
**Project:** GamePlan Scheduler | **Opleiding:** MBO-4 Software Developer  
**Kerntaak:** K1-W3 Realisatie

---

## INHOUDSOPGAVE

1. [Overzicht van de Applicatie](#1-overzicht-van-de-applicatie)
2. [Bestandsstructuur & Samenhang](#2-bestandsstructuur--samenhang)
3. [Database Schema](#3-database-schema)
4. [Alle Validaties — Compleet Overzicht (32 stuks)](#4-alle-validaties--compleet-overzicht)
5. [Algoritme per Validatie](#5-algoritme-per-validatie)
6. [Alle Functionele Flows A tot Z (13 flows)](#6-alle-functionele-flows-a-tot-z)
7. [Code Flow Diagram — Login Pagina](#7-code-flow-diagram--login-pagina)
8. [Code Flow Diagram — Home Pagina Laden](#8-code-flow-diagram--home-pagina-laden)
9. [Beveiligingsmaatregelen](#9-beveiligingsmaatregelen)
10. [Samenvatting](#10-samenvatting)

---

## 1. OVERZICHT VAN DE APPLICATIE

**GamePlan Scheduler** is een webapplicatie waarmee gamers hun gaming-schema's, evenementen, vrienden en favoriete spellen kunnen beheren. De applicatie is gebouwd met:

| Technologie | Doel |
|-------------|------|
| **PHP 8+** | Server-side logica, validatie, sessie, database |
| **MySQL/MariaDB** | Database opslag via PDO (prepared statements) |
| **JavaScript** | Client-side formuliervalidatie |
| **Bootstrap 5** | Responsive design, UI componenten |
| **HTML5/CSS3** | Structuur en glassmorphism dark-theme styling |

**Kernfunctionaliteiten:**
- Registreren en inloggen (met bcrypt wachtwoord-hashing)
- Gaming schema's toevoegen, bewerken, verwijderen
- Gaming evenementen beheren (met herinneringen)
- Vriendenlijst bijhouden
- Favoriete spellen profiel
- Kalender overzicht (gecombineerd)
- Sessie-timeout na 30 minuten inactiviteit

---

## 2. BESTANDSSTRUCTUUR & SAMENHANG

```
gameplan-scheduler/
│
├── db.php              ← Database verbinding (PDO Singleton)
├── functions.php       ← ALLE validatie, sessie, CRUD functies (672 regels)
├── script.js           ← Client-side validatie (433 regels)
├── style.css           ← Glassmorphism dark-theme styling
├── database.sql        ← Database schema (6 tabellen)
│
├── login.php           ← Inlog pagina + formulier
├── register.php        ← Registratie pagina + formulier
├── logout.php          ← Sessie vernietigen + redirect
├── index.php           ← Dashboard (homepagina na login)
├── profile.php         ← Profiel + favoriete spellen
├── contact.php         ← Contact informatie
├── privacy.php         ← Privacy beleid
│
├── add_schedule.php    ← Schema toevoegen formulier
├── add_event.php       ← Evenement toevoegen formulier
├── add_friend.php      ← Vriend toevoegen formulier
│
├── edit_schedule.php   ← Schema bewerken formulier
├── edit_event.php      ← Evenement bewerken formulier
├── edit_friend.php     ← Vriend bewerken formulier
├── edit_favorite.php   ← Favoriet bewerken formulier
│
├── delete.php          ← Centraal verwijder-script (soft delete)
├── header.php          ← Navigatie header (sessie-based)
└── footer.php          ← Copyright footer
```

**Bestandssamenhang (hoe ze samenwerken):**
```
Elke pagina → require functions.php → require db.php → getDBConnection()
                  ↓                        ↓
           session_start()           PDO Singleton
           validatiefuncties         prepared statements
           CRUD functies             SQL-injectie bescherming
```

---

## 3. DATABASE SCHEMA

**6 tabellen** in `gameplan_db`:

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Users     │     │   Games     │     │  UserGames  │
│─────────────│     │─────────────│     │─────────────│
│ user_id PK  │←────│             │     │ user_id FK  │──→ Users
│ username    │     │ game_id PK  │←────│ game_id FK  │──→ Games
│ email UNIQ  │     │ titel       │     │ note        │
│ password_hash│    │ description │     └─────────────┘
│ last_activity│    │ deleted_at  │      (koppeltabel)
│ deleted_at  │     └─────────────┘
└─────────────┘
      ↑                    ↑
      │                    │
┌─────────────┐     ┌─────────────┐
│  Friends    │     │ Schedules   │
│─────────────│     │─────────────│
│ friend_id PK│     │ schedule_id │
│ user_id FK  │     │ user_id FK  │──→ Users
│ friend_user │     │ game_id FK  │──→ Games
│ note        │     │ date        │
│ status      │     │ time        │
│ deleted_at  │     │ friends     │
└─────────────┘     │ shared_with │
                    │ deleted_at  │
      ┌─────────────┘─────────────┘
      │
┌─────────────┐
│   Events    │
│─────────────│
│ event_id PK │
│ user_id FK  │──→ Users
│ title       │
│ date, time  │
│ description │
│ reminder    │  ← whitelist: none, 1_hour, 1_day
│ external_link│
│ shared_with │
│ deleted_at  │
└─────────────┘
```

---

## 4. ALLE VALIDATIES — COMPLEET OVERZICHT

Er zijn **32 validaties** in totaal: **27 server-side (PHP)** + **5 client-side (JavaScript)**.

### 4.1 SERVER-SIDE VALIDATIES (PHP — functions.php)

| Nr | Validatie | Functie | Regel | Gebruikt bij |
|----|-----------|---------|-------|-------------|
| V01 | E-mail niet leeg | `validateRequired()` | r.68-86 | Login |
| V02 | Wachtwoord niet leeg | `validateRequired()` | r.68-86 | Login |
| V03 | E-mail bestaat in database | `loginUser()` | r.302-304 | Login |
| V04 | Wachtwoord klopt (bcrypt verify) | `loginUser()` | r.307 | Login |
| V05 | Gebruikersnaam niet leeg, max 50 | `validateRequired()` | r.259 | Registratie |
| V06 | E-mail geldig formaat | `validateEmail()` | r.136-142 | Registratie |
| V07 | Wachtwoord niet leeg | `validateRequired()` | r.263 | Registratie |
| V08 | Wachtwoord minimaal 8 tekens | `registerUser()` | r.265-266 | Registratie |
| V09 | E-mail nog niet geregistreerd | `registerUser()` | r.269-272 | Registratie |
| V10 | Speltitel niet leeg, max 100 | `validateRequired()` | r.504 | Schedule, Favoriet |
| V11 | Datum geldig formaat JJJJ-MM-DD | `validateDate()` | r.97-117 | Schedule, Event |
| V12 | Datum vandaag of toekomst | `validateDate()` | r.111-114 | Schedule, Event |
| V13 | Tijd geldig formaat UU:MM | `validateTime()` | r.123-130 | Schedule, Event |
| V14 | Komma-gescheiden niet leeg | `validateCommaSeparated()` | r.160-171 | Schedule, Event |
| V15 | Eventtitel niet leeg, max 100 | `validateRequired()` | r.571 | Event |
| V16 | Beschrijving max 500 tekens | `addEvent()` | r.577-578 | Event |
| V17 | Herinnering whitelist | `addEvent()` | r.579-580 | Event |
| V18 | URL geldig formaat | `validateUrl()` | r.148-154 | Event |
| V19 | Vriendnaam niet leeg, max 50 | `validateRequired()` | r.445 | Vriend |
| V20 | Status niet leeg, max 50 | `validateRequired()` | r.447 | Vriend |
| V21 | Vriend niet al toegevoegd | `addFriend()` | r.451-454 | Vriend |
| V22 | Spel niet al in favorieten | `addFavoriteGame()` | r.369-372 | Favoriet |
| V23 | Eigendomscontrole (ownership) | `checkOwnership()` | r.640-645 | Edit/Delete |
| V24 | Sessie timeout (30 min) | `checkSessionTimeout()` | r.239-248 | Alle pagina's |
| V25 | Gebruiker ingelogd check | `isLoggedIn()` | r.211-213 | Alle pagina's |
| V26 | XSS output escaping | `safeEcho()` | r.50-55 | Alle output |
| V27 | Sorteer whitelist | `getSchedules()`/`getEvents()` | r.524, r.594 | Dashboard |

### 4.2 CLIENT-SIDE VALIDATIES (JavaScript — script.js)

| Nr | Validatie | Functie | Regel | Gebruikt bij |
|----|-----------|---------|-------|-------------|
| V28 | Login velden leeg check | `validateLoginForm()` | r.38-68 | Login formulier |
| V29 | E-mail regex check | `validateLoginForm()` | r.60-63 | Login/Register |
| V30 | Registratie velden check | `validateRegisterForm()` | r.93-136 | Register formulier |
| V31 | Schedule formulier check | `validateScheduleForm()` | r.163-224 | Schedule formulier |
| V32 | Event formulier check | `validateEventForm()` | r.253-327 | Event formulier |

### 4.3 DUBBELE LAAG VALIDATIE (WAAROM?)

```
Gebruiker vult formulier in
        ↓
┌─────────────────────────────┐
│ LAAG 1: JavaScript (client) │  ← Snelle feedback, UX verbetering
│ Draait in de browser        │  ← KAN OMZEILD WORDEN (DevTools)
└─────────────────────────────┘
        ↓ (als JS validatie slaagt)
┌─────────────────────────────┐
│ LAAG 2: PHP (server)        │  ← ECHTE beveiliging
│ Draait op de server         │  ← KAN NIET OMZEILD WORDEN
│ Prepared statements (SQL)   │  ← Beschermt tegen SQL-injectie
└─────────────────────────────┘
        ↓ (als PHP validatie slaagt)
┌─────────────────────────────┐
│ LAAG 3: Database constraints│  ← Laatste vangnet
│ NOT NULL, UNIQUE, FK        │  ← Voorkomt corrupte data
└─────────────────────────────┘
```

---

## 5. ALGORITME PER VALIDATIE

### ALGORITME V01-V04: LOGIN VALIDATIE

**Functie:** `loginUser($email, $password)` in `functions.php` (regel 292-317)

```
ALGORITME: LoginGebruiker(email, wachtwoord)

STAP 1: Controleer of email NIET leeg is
        Verwijder witruimte (trim)
        ALS email leeg is OF alleen spaties bevat
            RETOURNEER "Email mag niet leeg zijn"
        EINDE ALS

STAP 2: Controleer of wachtwoord NIET leeg is
        ALS wachtwoord leeg is OF alleen spaties bevat
            RETOURNEER "Wachtwoord mag niet leeg zijn"
        EINDE ALS

STAP 3: Zoek gebruiker in database
        VOER UIT: SELECT user_id, username, password_hash
                  FROM Users WHERE email = :email AND deleted_at IS NULL
        (Prepared statement — beschermt tegen SQL-injectie)

STAP 4: Controleer wachtwoord met bcrypt
        ALS gebruiker NIET gevonden OF password_verify() is ONWAAR
            RETOURNEER "Ongeldige e-mail of wachtwoord"
            (Generieke melding — onthult NIET of email bestaat)
        EINDE ALS

STAP 5: Maak sessie aan
        $_SESSION['user_id'] = user_id
        $_SESSION['username'] = username
        session_regenerate_id(true) → voorkomt session fixation
        updateLastActivity() → update timestamp

STAP 6: RETOURNEER null (geen fout = succes)
```

### ALGORITME V05-V09: REGISTRATIE VALIDATIE

**Functie:** `registerUser($username, $email, $password)` in `functions.php` (regel 254-286)

```
ALGORITME: RegistreerGebruiker(gebruikersnaam, email, wachtwoord)

STAP 1: Controleer gebruikersnaam
        trim() → verwijder witruimte
        ALS leeg OF alleen spaties → FOUT "mag niet leeg zijn"
        ALS lengte > 50 → FOUT "te lang (max 50)"

STAP 2: Controleer e-mail formaat
        ALS NIET voldoet aan FILTER_VALIDATE_EMAIL
            RETOURNEER "Ongeldig e-mail formaat"

STAP 3: Controleer wachtwoord niet leeg
        ALS leeg OF alleen spaties → FOUT

STAP 4: Controleer wachtwoord lengte
        ALS strlen(wachtwoord) < 8
            RETOURNEER "Minimaal 8 tekens"

STAP 5: Controleer email uniciteit in database
        SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL
        ALS > 0 → FOUT "E-mail al geregistreerd"

STAP 6: Hash wachtwoord met bcrypt
        password_hash(wachtwoord, PASSWORD_BCRYPT)
        → Bcrypt genereert automatisch unieke salt
        → Wachtwoord NOOIT als platte tekst opgeslagen

STAP 7: Sla gebruiker op in database
        INSERT INTO Users (username, email, password_hash)
        ALS database fout → error_log() + generieke melding

STAP 8: RETOURNEER null (succes)
```

### ALGORITME V10-V14: SCHEDULE VALIDATIE

**Functie:** `addSchedule(...)` in `functions.php` (regel 500-519)

```
ALGORITME: VoegSchemaToe(gebruikerId, spelTitel, datum, tijd, vrienden, gedeeldMet)

STAP 1: Speltitel niet leeg, max 100 tekens
STAP 2: Datum validatie (DateTime::createFromFormat)
        ALS parsing mislukt OF output ≠ input → FOUT
        ALS datum < vandaag → FOUT "moet toekomst zijn"
STAP 3: Tijd validatie regex /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
STAP 4: Vrienden komma-gescheiden check (optioneel)
STAP 5: Gedeeld-met komma-gescheiden check (optioneel)
STAP 6: getOrCreateGameId() → zoek of maak spel (case-insensitive)
STAP 7: INSERT INTO Schedules → RETOURNEER null
```

### ALGORITME V15-V18: EVENT VALIDATIE

**Functie:** `addEvent(...)` in `functions.php` (regel 567-589)

```
ALGORITME: VoegEvenementToe(gebruikerId, titel, datum, tijd, beschrijving, herinnering, link, gedeeldMet)

STAP 1: Titel niet leeg, max 100 tekens
STAP 2: Datum geldig + toekomst
STAP 3: Tijd geldig UU:MM
STAP 4: ALS beschrijving niet leeg EN strlen > 500 → FOUT
STAP 5: Herinnering whitelist: ALS NIET in ['none','1_hour','1_day'] → FOUT
STAP 6: URL validatie (optioneel): FILTER_VALIDATE_URL
STAP 7: Gedeeld-met komma-gescheiden check
STAP 8: INSERT INTO Events → RETOURNEER null
```

### ALGORITME V19-V21: VRIEND VALIDATIE

**Functie:** `addFriend(...)` in `functions.php` (regel 441-458)

```
ALGORITME: VoegVriendToe(gebruikerId, vriendNaam, notitie, status)

STAP 1: Vriendnaam niet leeg, max 50 tekens
STAP 2: Status niet leeg, max 50 tekens
STAP 3: Duplicaat check (case-insensitive)
        SELECT COUNT(*) FROM Friends
        WHERE user_id = :uid AND LOWER(friend_username) = LOWER(:naam)
        AND deleted_at IS NULL
        ALS > 0 → FOUT "Al vrienden"
STAP 4: INSERT INTO Friends → RETOURNEER null
```

### ALGORITME V22: FAVORIET DUPLICAAT CHECK

```
ALGORITME: VoegFavorietToe(gebruikerId, titel, beschrijving, notitie)

STAP 1: Speltitel niet leeg, max 100
STAP 2: getOrCreateGameId() (case-insensitive zoek/maak)
STAP 3: SELECT COUNT(*) FROM UserGames WHERE user_id=? AND game_id=?
        ALS > 0 → FOUT "Spel al in favorieten"
STAP 4: INSERT INTO UserGames → RETOURNEER null
```

### ALGORITME V23: EIGENDOMSCONTROLE

```
ALGORITME: ControleerEigendom(tabel, idKolom, id, gebruikerId)

STAP 1: SELECT COUNT(*) FROM [tabel]
        WHERE [idKolom] = :id AND user_id = :uid AND deleted_at IS NULL
STAP 2: ALS count > 0 → WAAR (eigenaar)
        ANDERS → ONWAAR (geen toestemming)

Gebruikt bij: editSchedule, deleteSchedule, editEvent, deleteEvent, editFavorite
Doel: Gebruiker A kan NIET de data van Gebruiker B bewerken/verwijderen
```

### ALGORITME V24: SESSIE TIMEOUT

```
ALGORITME: ControleerSessieTimeout()

STAP 1: ALS ingelogd EN last_activity bestaat
            verschil = huidige_tijd - last_activity
            ALS verschil > 1800 seconden (30 minuten)
                session_destroy() → redirect login.php
STAP 2: Update last_activity = time()
```

### ALGORITME V25-V27: OVERIGE VALIDATIES

```
V25 — IsIngelogd():
    RETOURNEER isset($_SESSION['user_id'])
    Op elke beveiligde pagina: ALS niet → redirect login.php

V26 — VeiligeOutput(tekst):
    htmlspecialchars(tekst, ENT_QUOTES, 'UTF-8')
    < wordt &lt;  > wordt &gt;  " wordt &quot;
    Voorkomt XSS-aanvallen

V27 — ValideerSortering(sorteerwaarde):
    Whitelist = ['date ASC', 'date DESC', 'time ASC', 'time DESC']
    ALS niet in whitelist → gebruik 'date ASC'
    Voorkomt SQL-injectie via sorteerparameter
```

### ALGORITME V28-V32: CLIENT-SIDE VALIDATIES (JavaScript)

```
V28 — validateLoginForm():
    1. email = trim(getElementById('email'))
    2. password = trim(getElementById('password'))
    3. ALS !email || !password → alert → return false
    4. ALS email niet matcht /^[^\s@]+@[^\s@]+\.[^\s@]+$/ → alert → return false
    5. return true

V29 — E-mail regex (onderdeel van V28 en V30):
    Regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    Betekenis: [niet-spatie-niet-@]+@[niet-spatie-niet-@]+.[niet-spatie-niet-@]+

V30 — validateRegisterForm():
    1. Haal username, email, password (met trim)
    2. ALS een veld leeg → alert → stop
    3. ALS username alleen spaties /^\s*$/ → alert → stop (BUG FIX #1001)
    4. ALS username.length > 50 → alert → stop
    5. ALS email niet geldig → alert → stop
    6. ALS password.length < 8 → alert → stop
    7. return true

V31 — validateScheduleForm():
    1. Haal gameTitle, date, time, friends, sharedWith
    2. ALS gameTitle leeg/alleen spaties → alert → stop
    3. ALS date leeg → alert → stop
    4. ALS Date-object ongeldig → alert → stop
    5. ALS datum < vandaag → alert → stop
    6. ALS time niet matcht UU:MM regex → alert → stop
    7. ALS friends ongeldige tekens → alert → stop
    8. ALS sharedWith ongeldige tekens → alert → stop
    9. return true

V32 — validateEventForm():
    1-2. Titel niet leeg, niet alleen spaties
    3. Titel max 100 tekens
    4-5. Datum verplicht, niet in verleden
    6. Tijd geldig UU:MM
    7. Beschrijving max 500 tekens
    8. Externe link geldig URL regex (optioneel)
    9. Gedeeld-met alleen letters, cijfers, komma's
    10. return true
```

---

## 6. ALLE FUNCTIONELE FLOWS A TOT Z

### FLOW 1: REGISTRATIE — Nieuw account aanmaken

```
Gebruiker opent register.php in browser
    → require functions.php → require db.php → session_start()
    → isLoggedIn()? JA → redirect index.php | NEE → toon formulier
    → Gebruiker vult in: username, email, wachtwoord
    → Klikt "Create Account"
    → validateRegisterForm() [JavaScript V30]
        → Controleert: velden niet leeg, username max 50, email regex, wachtwoord ≥ 8
    → ALS JS validatie slaagt: POST naar register.php
    → registerUser() [PHP V05-V09]
        → validateRequired(username, 50) → validateEmail(email)
        → validateRequired(password) → strlen ≥ 8
        → email uniciteit check in database
        → password_hash(bcrypt) → INSERT Users
    → ALS succes: setMessage('success') → redirect login.php
    → ALS fout: toon foutmelding met safeEcho()
```

### FLOW 2: INLOGGEN

```
Gebruiker opent login.php
    → require functions.php → session_start()
    → isLoggedIn()? JA → redirect index.php | NEE → toon formulier
    → Gebruiker vult in: email, wachtwoord
    → Klikt "Login"
    → validateLoginForm() [JavaScript V28-V29]
    → POST naar login.php
    → loginUser() [PHP V01-V04]
        → validateRequired(email) → validateRequired(password)
        → SELECT Users WHERE email (prepared statement)
        → password_verify(bcrypt)
        → $_SESSION['user_id'] + session_regenerate_id(true)
        → updateLastActivity()
    → ALS succes: redirect index.php
    → ALS fout: toon "Ongeldige e-mail of wachtwoord" met safeEcho()
```

### FLOW 3: DASHBOARD LADEN (index.php — Home Pagina)

```
Browser → index.php → require functions.php → require db.php
    → session_start() → checkSessionTimeout() [V24]
    → isLoggedIn()? NEE → redirect login.php
                    JA  → getUserId() → updateLastActivity()
    → Sorteer parameters uit URL ophalen ($_GET)
    → getFriends() → getFavoriteGames() → getSchedules()
    → getEvents() → getCalendarItems() → getReminders()
    → include header.php → getMessage() (sessie berichten)
    → 5 HTML secties renderen met safeEcho() [V26] op ALLE output
    → include footer.php → JavaScript herinneringen laden
```

### FLOW 4: SCHEMA TOEVOEGEN

```
Gebruiker → add_schedule.php → checkSessionTimeout() → isLoggedIn()
    → Formulier: speltitel, datum, tijd, vrienden, gedeeld-met
    → validateScheduleForm() [JavaScript V31]
    → POST → addSchedule() [PHP V10-V14]
        → validateRequired(gameTitle, 100) → validateDate(date)
        → validateTime(time) → validateCommaSeparated(friends)
        → getOrCreateGameId() → INSERT Schedules
    → ALS succes: setMessage('success') → redirect index.php
```

### FLOW 5: EVENEMENT TOEVOEGEN

```
Gebruiker → add_event.php → checkSessionTimeout() → isLoggedIn()
    → Formulier: titel, datum, tijd, beschrijving, herinnering, link, gedeeld-met
    → validateEventForm() [JavaScript V32]
    → POST → addEvent() [PHP V15-V18]
        → validateRequired(title, 100) → validateDate → validateTime
        → strlen(description) ≤ 500 → in_array(reminder, whitelist)
        → validateUrl(link) → validateCommaSeparated(sharedWith)
        → INSERT Events
    → redirect index.php
```

### FLOW 6: VRIEND TOEVOEGEN

```
Gebruiker → add_friend.php → checkSessionTimeout() → isLoggedIn()
    → Formulier: vriendnaam, notitie, status
    → POST → addFriend() [PHP V19-V21]
        → validateRequired(friendUsername, 50) → validateRequired(status, 50)
        → duplicaat check (case-insensitive LOWER())
        → INSERT Friends
    → redirect index.php
```

### FLOW 7: FAVORIET SPEL TOEVOEGEN

```
Gebruiker → profile.php → checkSessionTimeout() → isLoggedIn()
    → Formulier: speltitel, beschrijving, notitie
    → POST → addFavoriteGame() [PHP V10, V22]
        → validateRequired(title, 100)
        → getOrCreateGameId() → duplicaat check
        → INSERT UserGames
    → redirect profile.php
```

### FLOW 8: DATA BEWERKEN (Edit)

```
Gebruiker → edit_*.php → checkSessionTimeout() → isLoggedIn()
    → GET id uit URL → checkOwnership() [V23]
    → ALS geen eigenaar → FOUT "Geen toestemming"
    → Haal huidige data op → toon formulier met vooraf ingevulde waarden
    → POST → zelfde validaties als bij toevoegen
    → UPDATE met prepared statement → redirect met succesbericht
```

### FLOW 9: DATA VERWIJDEREN (Delete)

```
Gebruiker klikt "Delete" knop → JavaScript confirm() dialoog
    → JA → delete.php?type=schedule&id=5
    → checkSessionTimeout() → isLoggedIn()
    → Bepaal type: schedule / event / favorite / friend
    → Roep juiste delete-functie aan
    → checkOwnership() [V23] → SOFT DELETE (deleted_at = NOW())
    → setMessage(succes/fout) → redirect naar juiste pagina
```

### FLOW 10: UITLOGGEN

```
Gebruiker klikt "Logout" → logout.php
    → $_SESSION = [] (wis alle sessie variabelen)
    → Vernietig sessie cookie
    → session_destroy()
    → redirect login.php?msg=logged_out
```

### FLOW 11: DATABASE VERBINDING

```
Elke pagina → functions.php → db.php → getDBConnection()
    → Singleton Pattern: ALS $pdo === null → maak nieuwe PDO verbinding
                         ANDERS → hergebruik bestaande
    → DSN: "mysql:host=localhost;dbname=gameplan_db;charset=utf8mb4"
    → PDO opties: ERRMODE_EXCEPTION, FETCH_ASSOC,
                  EMULATE_PREPARES=false, PERSISTENT=true
    → BIJ FOUT: error_log() + die() met generieke melding
```

### FLOW 12: SESSIE BERICHTEN (Flash Messages)

```
Actie voltooid (bijv. schema toegevoegd)
    → setMessage('success', 'Schema succesvol toegevoegd!')
        → $_SESSION['message'] = ['type' => 'success', 'msg' => '...']
    → redirect naar volgende pagina
    → getMessage() op volgende pagina
        → Leest $_SESSION['message'] → unset() (eenmalig tonen)
        → Retourneert HTML alert div
    → Auto-dismiss na 5 seconden (JavaScript)
```

### FLOW 13: HERINNERING SYSTEEM

```
index.php laadt → getReminders($userId) [PHP]
    → Haal alle events op met reminder ≠ 'none'
    → VOOR ELK event:
        eventTime = strtotime(datum + tijd)
        reminderTime = eventTime - (1_hour? 3600 : 86400)
        ALS reminderTime ≤ now EN reminderTime > now - 60
            → Voeg toe aan herinneringen array
    → JSON encode → JavaScript
    → reminders.forEach(r => alert('🔔 Herinnering: ...'))
```

---

## 7. CODE FLOW DIAGRAM — LOGIN PAGINA

```
GEBRUIKER OPENT login.php IN BROWSER
            |
            v
   +------------------+
   | login.php r.26   |----→ require_once 'functions.php'
   | Laad functies    |        |
   +------------------+        v
            |         +------------------+
            |         | functions.php    |----→ require_once 'db.php'
            |         | r.22            |      (database beschikbaar)
            |         +------------------+
            |                  |
            |                  v
            |         +------------------+
            |         | functions.php    |----→ session_start()
            |         | r.32-37         |      session_regenerate_id(true)
            |         +------------------+
            |
            v
   +------------------+      JA
   | login.php r.35   |-----------→ header("Location: index.php")
   | isLoggedIn()?    |             exit; (al ingelogd)
   +------------------+
            | NEE
            v
   +------------------+
   | HTML FORMULIER   |  <form method="POST"
   | login.php        |   onsubmit="return validateLoginForm();">
   | r.140-191        |  - input type="email" (required)
   |                  |  - input type="password" (required)
   |                  |  - button type="submit"
   +------------------+
            |
            v (gebruiker klikt Submit)
   +------------------+
   | script.js r.38   |  validateLoginForm() [CLIENT-SIDE]
   | JAVASCRIPT       |
   | VALIDATIE         |  1. email = trim(getElementById('email'))
   |                  |  2. password = trim(getElementById('password'))
   |                  |  3. ALS !email || !password → alert → return false
   |                  |  4. ALS email niet matcht regex → alert → return false
   |                  |  5. return true (formulier wordt verzonden)
   +------------------+
            |
            v (POST request naar server)
   +------------------+
   | login.php r.51   |  if ($_SERVER['REQUEST_METHOD'] == 'POST')
   | POST ONTVANGEN   |
   +------------------+
            |
            v
   +------------------+
   | login.php r.56   |  $email = $_POST['email'] ?? '';
   | WAARDEN OPHALEN  |  $password = $_POST['password'] ?? '';
   +------------------+
            |
            v
   +====================================================+
   | loginUser($email, $password)  [SERVER-SIDE]         |
   | functions.php r.292-317                             |
   |                                                      |
   | STAP 1: validateRequired($email, "Email")            |
   |         → trim → empty check → regex spaties         |
   |                                                      |
   | STAP 2: validateRequired($password, "Password")      |
   |                                                      |
   | STAP 3: Database query (prepared statement)           |
   |         SELECT user_id, username, password_hash       |
   |         FROM Users WHERE email = :email               |
   |         AND deleted_at IS NULL                        |
   |                                                      |
   | STAP 4: password_verify($password, hash)              |
   |         ALS mislukt: "Ongeldige e-mail of wachtw."   |
   |                                                      |
   | STAP 5: $_SESSION['user_id'] = user_id               |
   |         $_SESSION['username'] = username               |
   |         session_regenerate_id(true)                   |
   |         updateLastActivity()                          |
   |                                                      |
   | STAP 6: return null (succes)                          |
   +====================================================+
            |
            v
   +------------------+
   | login.php r.65   |  if (!$error):
   | SUCCES → REDIRECT|      header("Location: index.php"); exit;
   +------------------+
            |
            v (als er een fout is)
   +------------------+
   | login.php r.127  |  <div class="alert alert-danger">
   | FOUT TONEN       |    <?php echo safeEcho($error); ?>
   +------------------+  </div>
```

**Bestanden betrokken:**
- `login.php` — Formulier + POST-verwerking (227 regels)
- `functions.php` — `loginUser()`, `validateRequired()`, `isLoggedIn()`, `safeEcho()` (672 regels)
- `db.php` — `getDBConnection()` PDO Singleton (314 regels)
- `script.js` — `validateLoginForm()` client-side (433 regels)
- `style.css` — Glassmorphism styling

---

## 8. CODE FLOW DIAGRAM — HOME PAGINA LADEN

```
GEBRUIKER NAVIGEERT NAAR index.php
            |
            v
   +------------------+
   | index.php r.26   |----→ require_once 'functions.php'
   | LAAD FUNCTIES    |        → require_once 'db.php'
   |                  |        → session_start() + session_regenerate_id()
   +------------------+
            |
            v
   +------------------+
   | index.php r.29   |----→ checkSessionTimeout()
   | SESSIE CHECK     |  ALS ingelogd EN last_activity > 1800s:
   |                  |      session_destroy() → redirect login.php
   |                  |  ANDERS: $_SESSION['last_activity'] = time()
   +------------------+
            |
            v
   +------------------+      NEE
   | index.php r.32   |-----------→ header("Location: login.php")
   | isLoggedIn()?    |             exit;
   +------------------+
            | JA
            v
   +------------------+
   | index.php r.38   |  $userId = getUserId();
   | GEBRUIKER ID     |  → return (int) $_SESSION['user_id']
   +------------------+
            |
            v
   +------------------+
   | index.php r.41   |  updateLastActivity($pdo, $userId)
   | UPDATE ACTIVITEIT|  → UPDATE Users SET last_activity = NOW()
   +------------------+
            |
            v
   +------------------+
   | index.php r.44   |  $sortSchedules = $_GET['sort_schedules'] ?? 'date ASC'
   | SORTEER PARAMS   |  $sortEvents = $_GET['sort_events'] ?? 'date ASC'
   +------------------+
            |
            v
   +=====================================================+
   | DATA OPHALEN UIT DATABASE (r.48-53)                  |
   |                                                       |
   | 1. $friends    = getFriends($userId)                 |
   | 2. $favorites  = getFavoriteGames($userId)           |
   | 3. $schedules  = getSchedules($userId, $sort)        |
   |    → sorteer whitelist validatie [V27]                |
   | 4. $events     = getEvents($userId, $sort)           |
   |    → sorteer whitelist validatie [V27]                |
   | 5. $calendar   = getCalendarItems($userId)           |
   |    → merge schedules+events, usort op datum+tijd     |
   | 6. $reminders  = getReminders($userId)               |
   |    → filter events met actieve herinnering            |
   +=====================================================+
            |
            v
   +------------------+
   | index.php r.71   |  include 'header.php'
   | HEADER           |  → Navigatie: Dashboard, Profiel, Uitloggen
   +------------------+
            |
            v
   +------------------+
   | index.php r.75   |  echo getMessage()
   | FLASH BERICHTEN  |  → Toon succes/fout van vorige actie (eenmalig)
   +------------------+
            |
            v
   +=====================================================+
   | HTML SECTIES RENDEREN (r.80-288)                      |
   |                                                       |
   | § 1: 👥 Vriendenlijst — tabel + edit/delete knoppen |
   | § 2: 🎮 Favoriete Spellen — tabel + edit/delete     |
   | § 3: 📅 Schema's — sorteer + tabel + edit/delete    |
   | § 4: 🎯 Evenementen — sorteer + tabel + links       |
   | § 5: 📆 Kalender Overzicht — cards, gesorteerd      |
   |                                                       |
   | ALLE output via safeEcho() [V26] = XSS bescherming  |
   +=====================================================+
            |
            v
   +------------------+
   | index.php r.292  |  include 'footer.php'
   | FOOTER + SCRIPTS |  Bootstrap JS + script.js laden
   +------------------+
            |
            v
   +------------------+
   | index.php r.298  |  const reminders = <?php echo json_encode(); ?>;
   | HERINNERINGEN    |  reminders.forEach(r => alert('🔔 ...'));
   +------------------+
```

**Bestanden betrokken:**
- `index.php` — Dashboard pagina (305 regels)
- `functions.php` — Alle data-ophaal, sessie, validatie functies (672 regels)
- `db.php` — Database verbinding PDO Singleton (314 regels)
- `header.php` — Navigatie met sessie-based rendering
- `footer.php` — Copyright footer
- `script.js` — Delete bevestiging, alert auto-dismiss (433 regels)
- `style.css` — Glassmorphism dark theme styling

---

## 9. BEVEILIGINGSMAATREGELEN

| Bedreiging | Maatregel | Implementatie |
|------------|-----------|---------------|
| **SQL-injectie** | Prepared statements (PDO) | Alle queries gebruiken `:parameter` binding |
| **XSS (Cross-Site Scripting)** | `safeEcho()` output escaping | `htmlspecialchars(ENT_QUOTES, 'UTF-8')` op alle output |
| **Session Hijacking** | `session_regenerate_id(true)` | Bij elke login nieuw sessie-ID |
| **Session Fixation** | `session_regenerate_id(true)` | Voorkomt hergebruik van oude sessie-ID |
| **Brute Force** | Generieke foutmelding | "Ongeldige e-mail of wachtwoord" (onthult niet of email bestaat) |
| **Wachtwoord lekken** | bcrypt hashing | `password_hash(PASSWORD_BCRYPT)` met automatische salt |
| **IDOR (Insecure Direct Object Reference)** | `checkOwnership()` | Controleert of user_id overeenkomt bij edit/delete |
| **Sessie timeout** | 30 minuten inactiviteit | `checkSessionTimeout()` op elke pagina |
| **Data verlies** | Soft delete | `deleted_at` timestamp i.p.v. echte DELETE |
| **Onbevoegde toegang** | `isLoggedIn()` check | Redirect naar login.php als niet ingelogd |
| **SQL-injectie via sortering** | Whitelist | `in_array($sort, [...toegestaan...])` |
| **Database fouten lekken** | `error_log()` + `die()` | Technische details alleen in server log, niet naar gebruiker |

---

## 10. SAMENVATTING

| Onderdeel | Aantal | Details |
|-----------|--------|---------|
| **Validaties totaal** | 32 | 27 server-side (PHP) + 5 client-side (JS) |
| **Functionele flows** | 13 | Login, register, CRUD, logout, DB, berichten, herinneringen |
| **Code flow diagrammen** | 2 | Login pagina + Home pagina laden |
| **Beveiligingslagen** | 3 | JavaScript → PHP → Database constraints |
| **PHP bestanden** | 16 | Alle pagina's + functions.php + db.php |
| **JavaScript bestanden** | 1 | script.js (433 regels) |
| **Database tabellen** | 6 | Users, Games, UserGames, Friends, Schedules, Events |
| **Bug fixes** | 2 | #1001 (spaces-only), #1004 (strict date) |

**Techniek samenvatting per pagina:**

| Pagina | Validaties | Beveiliging |
|--------|-----------|-------------|
| **Login** | V01-V04, V28-V29 | bcrypt verify, session regenerate, generieke foutmelding |
| **Registratie** | V05-V09, V30 | bcrypt hash, email uniciteit, prepared statements |
| **Schedule** | V10-V14, V31 | Session check, ownership, prepared statements |
| **Event** | V15-V18, V32 | Session check, ownership, whitelist, prepared statements |
| **Vriend** | V19-V21 | Session check, duplicaat check, prepared statements |
| **Favoriet** | V10, V22 | Session check, duplicaat check, ownership |
| **Verwijderen** | V23 | Soft delete, ownership, type whitelist |
| **Alle pagina's** | V24-V27 | Sessie timeout, inlog check, safeEcho, sorteer whitelist |

---

*Dit document is geschreven op basis van de broncode van het GamePlan Scheduler project.*  
*Alle regelnummers verwijzen naar de werkelijke code in de bestanden.*  
*© 2025 Harsha Kanaparthi — MBO-4 Software Developer — Studentnummer 2195344*
