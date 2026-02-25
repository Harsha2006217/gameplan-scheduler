# 🛡️ GAMEPLAN SCHEDULER — COMPLETE VALIDATIE & FLOW DOCUMENTATIE

**Auteur:** Harsha Kanaparthi | **Studentnummer:** 2195344 | **Opleiding:** MBO-4 Software Developer

---

## 📋 INHOUDSOPGAVE

1. [Overzicht Validatie-Architectuur](#1-overzicht)
2. [Alle Validatiefuncties & Algoritmen](#2-validatiefuncties)
3. [Authenticatie Flows (Login & Registratie)](#3-authenticatie)
4. [Sessie Beheer & Beveiliging](#4-sessie)
5. [CRUD Validatie Flows (Create/Read/Update/Delete)](#5-crud)
6. [Client-Side Validatie (JavaScript)](#6-clientside)
7. [XSS & SQL-Injectie Bescherming](#7-beveiliging)
8. [Code Flow Diagram: Login Proces](#8-login-flow)
9. [Code Flow Diagram: Home Pagina Laden](#9-home-flow)
10. [Eigendomscontrole (Ownership Check)](#10-ownership)
11. [Samenvatting per Pagina](#11-samenvatting)

---

## 1. OVERZICHT VALIDATIE-ARCHITECTUUR {#1-overzicht}

GamePlan Scheduler gebruikt een **twee-laags validatie-architectuur**:

| Laag | Technologie | Bestand | Doel |
|------|-------------|---------|------|
| **Laag 1: Client-side** | JavaScript | `script.js` | Snelle feedback aan gebruiker vóór verzending |
| **Laag 2: Server-side** | PHP | `functions.php` | Echte beveiliging — altijd de laatste controle |

**Waarom twee lagen?** Client-side validatie kan omzeild worden (bijv. via DevTools). Server-side validatie is ALTIJD verplicht en kan NIET omzeild worden door de gebruiker.

### Betrokken Bestanden

| Bestand | Rol | Regelnummers |
|---------|-----|-------------|
| `functions.php` | Alle validatiefuncties + authenticatie + CRUD | 672 regels |
| `script.js` | Client-side formuliervalidatie | 433 regels |
| `db.php` | Database verbinding (PDO Singleton) | 314 regels |
| `login.php` | Login formulier + verwerking | 227 regels |
| `register.php` | Registratie formulier + verwerking | 148 regels |
| `delete.php` | Verwijder handler met ownership check | 99 regels |
| `index.php` | Dashboard met alle data + sortering | 305 regels |
| `profile.php` | Profiel + favoriete spellen beheer | 139 regels |

---

## 2. ALLE VALIDATIEFUNCTIES & ALGORITMEN {#2-validatiefuncties}

### 2.1 `validateRequired($value, $fieldName, $maxLength)` — Verplicht Veld Validatie

**Bestand:** `functions.php` regels 68-86 | **Bugfix:** #1001

**Algoritme (stap voor stap):**
1. `trim($value)` — Verwijder witruimte aan begin en einde
2. Controleer of waarde leeg is: `empty($value)`
3. Controleer of waarde ALLEEN spaties bevat: `preg_match('/^\s*$/', $value)` (regex)
4. Als `$maxLength > 0`: controleer of lengte niet overschreden wordt met `strlen($value) > $maxLength`
5. Retourneer foutmelding als ongeldig, `null` als geldig

**Code uit functions.php:**
```php
function validateRequired($value, $fieldName, $maxLength = 0) {
    $value = trim($value);
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        return "$fieldName mag niet leeg zijn of alleen spaties bevatten.";
    }
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName overschrijdt maximale lengte van $maxLength tekens.";
    }
    return null;
}
```

**Regex uitleg:** `^\s*$` — `^` = begin, `\s*` = nul of meer witruimte-tekens, `$` = einde. Matcht dus strings die ALLEEN uit spaties bestaan.

---

### 2.2 `validateDate($date)` — Datum Validatie

**Bestand:** `functions.php` regels 97-117 | **Bugfix:** #1004

**Algoritme:**
1. Parseer datum met `DateTime::createFromFormat('Y-m-d', $date)` — strikt formaat
2. Controleer of parsing gelukt is EN of output exact overeenkomt: `$dateObj->format('Y-m-d') !== $date`
3. Vergelijk met vandaag: `$dateObj < new DateTime('today')` — datum moet vandaag of later zijn
4. Retourneer foutmelding of `null`

**Waarom deze dubbele controle?** PHP's DateTime accepteert soms ongeldige datums zoals "2025-02-30" en corrigeert ze automatisch naar "2025-03-02". Door de output te vergelijken met de input, detecteren we dit.

```php
function validateDate($date) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        return "Ongeldig datum formaat. Gebruik JJJJ-MM-DD.";
    }
    $today = new DateTime('today');
    if ($dateObj < $today) {
        return "Datum moet vandaag of in de toekomst zijn.";
    }
    return null;
}
```

---

### 2.3 `validateTime($time)` — Tijd Validatie

**Bestand:** `functions.php` regels 123-130

**Algoritme:** Regex-patroon `^([01]?[0-9]|2[0-3]):[0-5][0-9]$`

| Deel | Betekenis |
|------|-----------|
| `[01]?[0-9]` | Uren 0-19 (met optionele voorloop-0) |
| `2[0-3]` | Uren 20-23 |
| `[0-5][0-9]` | Minuten 00-59 |

Voorbeelden: `09:30` ✅, `23:59` ✅, `25:00` ❌, `12:60` ❌

---

### 2.4 `validateEmail($email)` — E-mail Validatie

**Bestand:** `functions.php` regels 136-142

Gebruikt PHP's ingebouwde `filter_var($email, FILTER_VALIDATE_EMAIL)`. Dit controleert: aanwezigheid van `@`, geldig domein, geen ongeldige tekens.

---

### 2.5 `validateUrl($url)` — URL Validatie

**Bestand:** `functions.php` regels 148-154

Gebruikt `filter_var($url, FILTER_VALIDATE_URL)`. Alleen gecontroleerd als het veld NIET leeg is (optioneel veld).

---

### 2.6 `validateCommaSeparated($value, $fieldName)` — Komma-gescheiden Validatie

**Bestand:** `functions.php` regels 160-171

**Algoritme:**
1. Als leeg → `null` (optioneel veld)
2. `explode(',', $value)` — splits op komma's
3. Loop door elk item: `trim($item)` → als leeg, retourneer foutmelding
4. Voorkomt invoer zoals `"Jan,,Piet"` of `"Jan, , Piet"`

---

### 2.7 `safeEcho($string)` — XSS Bescherming

**Bestand:** `functions.php` regels 50-55

```php
function safeEcho($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
```

Converteert `<script>` naar `&lt;script&gt;`. Wordt gebruikt bij ELKE output naar HTML.

---

## 3. AUTHENTICATIE FLOWS {#3-authenticatie}

### 3.1 Registratie Flow (`registerUser`)

**Bestand:** `functions.php` regels 254-286 | Aangeroepen vanuit: `register.php` regel 41

**Volledige Algoritme:**

```
STAP 1: validateRequired(username, "Username", 50)
    → Controleer: niet leeg, niet alleen spaties, max 50 tekens
STAP 2: validateEmail(email)
    → Controleer: geldig e-mail formaat
STAP 3: validateRequired(password, "Password")
    → Controleer: niet leeg
STAP 4: strlen(password) < 8?
    → Controleer: minimaal 8 tekens
STAP 5: SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL
    → Controleer: e-mail nog niet geregistreerd (uniciteit)
STAP 6: password_hash($password, PASSWORD_BCRYPT)
    → Hash wachtwoord met bcrypt (cost factor 10, genereert salt automatisch)
STAP 7: INSERT INTO Users (username, email, password_hash)
    → Sla gebruiker op in database met prepared statement
STAP 8: Bij succes → redirect naar login.php met succesbericht
```

**Beveiligingsmaatregelen:**
- Wachtwoord wordt NOOIT als platte tekst opgeslagen
- Bcrypt genereert automatisch een unieke salt per gebruiker
- E-mail uniciteit voorkomt dubbele accounts
- Prepared statements voorkomen SQL-injectie

---

### 3.2 Login Flow (`loginUser`)

**Bestand:** `functions.php` regels 292-317 | Aangeroepen vanuit: `login.php` regel 61

**Volledige Algoritme:**

```
STAP 1: validateRequired(email, "Email")
    → Niet leeg, niet alleen spaties
STAP 2: validateRequired(password, "Password")
    → Niet leeg, niet alleen spaties
STAP 3: SELECT user_id, username, password_hash FROM Users 
        WHERE email = :email AND deleted_at IS NULL
    → Haal gebruiker op via prepared statement
STAP 4: password_verify($password, $user['password_hash'])
    → Vergelijk ingevoerd wachtwoord met opgeslagen bcrypt hash
STAP 5: Als NIET geldig → "Ongeldige e-mail of wachtwoord"
    → Generieke foutmelding (onthult NIET of e-mail bestaat)
STAP 6: $_SESSION['user_id'] = $user['user_id']
    → Sla gebruiker ID op in sessie
STAP 7: session_regenerate_id(true)
    → Genereer nieuw sessie-ID (voorkomt session fixation)
STAP 8: updateLastActivity($pdo, $user['user_id'])
    → Update laatste activiteit timestamp
```

---

## 4. SESSIE BEHEER & BEVEILIGING {#4-sessie}

### 4.1 Sessie Start (`functions.php` regels 32-37)

```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);  // Voorkom session fixation
}
```

### 4.2 Sessie Timeout (`checkSessionTimeout`, regels 239-248)

**Algoritme:**
1. Controleer of gebruiker ingelogd is
2. Controleer of `$_SESSION['last_activity']` bestaat
3. Bereken verschil: `time() - $_SESSION['last_activity']`
4. Als > 1800 seconden (30 minuten): `session_destroy()` → redirect naar login
5. Anders: update `$_SESSION['last_activity'] = time()`

### 4.3 Inlog Check (`isLoggedIn`, regel 211-214)

```php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
```

Gebruikt op ELKE beveiligde pagina: `index.php`, `profile.php`, `delete.php`, alle add/edit pagina's.

---

## 5. CRUD VALIDATIE FLOWS {#5-crud}

### 5.1 Schedule (Schema) Toevoegen — `addSchedule`

**Bestand:** `functions.php` regels 500-519

| Stap | Validatie | Functie |
|------|-----------|---------|
| 1 | Speltitel verplicht, max 100 | `validateRequired($gameTitle, "Game title", 100)` |
| 2 | Datum geldig + toekomst | `validateDate($date)` |
| 3 | Tijd geldig HH:MM | `validateTime($time)` |
| 4 | Vrienden komma-gescheiden | `validateCommaSeparated($friendsStr, "Friends")` |
| 5 | Gedeeld-met komma-gescheiden | `validateCommaSeparated($sharedWithStr, "Shared With")` |
| 6 | Spel ophalen/aanmaken | `getOrCreateGameId($pdo, $gameTitle)` |
| 7 | INSERT met prepared statement | PDO `execute()` |

### 5.2 Event (Evenement) Toevoegen — `addEvent`

**Bestand:** `functions.php` regels 567-589

| Stap | Validatie | Functie |
|------|-----------|---------|
| 1 | Titel verplicht, max 100 | `validateRequired($title, "Title", 100)` |
| 2 | Datum geldig + toekomst | `validateDate($date)` |
| 3 | Tijd geldig HH:MM | `validateTime($time)` |
| 4 | Beschrijving max 500 tekens | `strlen($description) > 500` |
| 5 | Herinnering whitelist | `in_array($reminder, ['none', '1_hour', '1_day'])` |
| 6 | Externe link geldig URL | `validateUrl($externalLink)` |
| 7 | Gedeeld-met komma-gescheiden | `validateCommaSeparated($sharedWithStr)` |
| 8 | INSERT met prepared statement | PDO `execute()` |

### 5.3 Vriend Toevoegen — `addFriend`

**Bestand:** `functions.php` regels 441-458

| Stap | Validatie |
|------|-----------|
| 1 | Gebruikersnaam verplicht, max 50 |
| 2 | Status verplicht, max 50 |
| 3 | Duplicaat check (case-insensitive): `LOWER(friend_username) = LOWER(:friend_username)` |
| 4 | INSERT met prepared statement |

### 5.4 Favoriet Spel Toevoegen — `addFavoriteGame`

**Bestand:** `functions.php` regels 360-377

| Stap | Validatie |
|------|-----------|
| 1 | Speltitel verplicht, max 100 |
| 2 | Spel ophalen of aanmaken (case-insensitive) |
| 3 | Duplicaat check: al in favorieten? |
| 4 | INSERT in UserGames koppeltabel |

### 5.5 Verwijderen — `delete.php`

**Bestand:** `delete.php` regels 1-99

| Stap | Actie |
|------|-------|
| 1 | `checkSessionTimeout()` — sessie geldig? |
| 2 | `isLoggedIn()` — gebruiker ingelogd? |
| 3 | Type bepalen: schedule/event/favorite/friend |
| 4 | Ownership check via `checkOwnership()` |
| 5 | **Soft delete**: `UPDATE SET deleted_at = NOW()` (data blijft bewaard) |
| 6 | Succesbericht via `setMessage()` → redirect |

---

## 6. CLIENT-SIDE VALIDATIE (JAVASCRIPT) {#6-clientside}

### 6.1 `validateLoginForm()` — script.js regels 38-68

| Check | Code | Foutmelding |
|-------|------|-------------|
| Velden leeg? | `!email \|\| !password` | "E-mail en wachtwoord zijn verplicht" |
| E-mail formaat? | Regex: `/^[^\s@]+@[^\s@]+\.[^\s@]+$/` | "Ongeldig e-mail formaat" |

### 6.2 `validateRegisterForm()` — script.js regels 93-136

| Check | Code | Foutmelding |
|-------|------|-------------|
| Alle velden ingevuld? | `!username \|\| !email \|\| !password` | "Alle velden zijn verplicht" |
| Alleen spaties? (Bug #1001) | `/^\s*$/.test(username)` | "Gebruikersnaam kan niet alleen spaties zijn" |
| Naam te lang? | `username.length > 50` | "Gebruikersnaam te lang (max 50)" |
| E-mail formaat? | Regex check | "Ongeldig e-mail formaat" |
| Wachtwoord te kort? | `password.length < 8` | "Wachtwoord moet minimaal 8 tekens zijn" |

### 6.3 `validateScheduleForm()` — script.js regels 163-224

| Check | Foutmelding |
|-------|-------------|
| Speltitel leeg/spaties (Bug #1001) | "Speltitel is verplicht" |
| Datum leeg | "Datum is verplicht" |
| Datum ongeldig | "Ongeldig datum formaat" |
| Datum in verleden (Bug #1004) | "Datum moet vandaag of in de toekomst zijn" |
| Tijd ongeldig | "Ongeldig tijd formaat. Gebruik UU:MM" |
| Vrienden ongeldige tekens | "Vrienden veld bevat ongeldige tekens" |

### 6.4 `validateEventForm()` — script.js regels 253-327

Zelfde checks als Schedule + extra:
- Titel max 100 tekens
- Beschrijving max 500 tekens
- Externe link: URL-formaat regex `/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/`
- Gedeeld-met: alleen letters, cijfers, komma's

---

## 7. XSS & SQL-INJECTIE BESCHERMING {#7-beveiliging}

### 7.1 XSS Preventie

**Functie:** `safeEcho()` — wordt aangeroepen bij ELKE output naar HTML

```
Invoer: <script>alert('hack')</script>
Output: &lt;script&gt;alert(&#039;hack&#039;)&lt;/script&gt;
```

### 7.2 SQL-Injectie Preventie

**Methode:** PDO Prepared Statements met named parameters

```php
// VEILIG — prepared statement met parameter binding
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
$stmt->execute(['email' => $email]);

// ONVEILIG — nooit doen! (string concatenatie)
$stmt = $pdo->query("SELECT * FROM Users WHERE email = '$email'");
```

**PDO Configuratie in db.php:**
- `PDO::ATTR_EMULATE_PREPARES => false` — echte prepared statements (database handelt beveiliging af)
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` — fouten worden als exceptions geworpen
- Foutmelding naar `error_log()`, NIET naar gebruiker (voorkomt informatie-lekken)

---

## 8. CODE FLOW DIAGRAM: LOGIN PROCES {#8-login-flow}

```
GEBRUIKER opent login.php
        │
        ▼
┌─────────────────────┐
│  login.php regel 26  │──→ require_once 'functions.php'
│                      │    (laadt ALLE validatiefuncties)
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  functions.php r.22  │──→ require_once 'db.php'
│                      │    (database verbinding beschikbaar)
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  functions.php r.32  │──→ session_start() + session_regenerate_id()
│  Sessie starten      │    (bescherming tegen session fixation)
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐     JA
│  login.php regel 35  │──→ isLoggedIn()? ──→ Redirect naar index.php
│  Al ingelogd?        │                       (voorkom dubbele login)
└──────────┬──────────┘
           │ NEE
           ▼
┌─────────────────────┐
│  HTML Formulier      │──→ Gebruiker vult e-mail + wachtwoord in
│  login.php r.140     │    type="email" + required (HTML5 validatie)
└──────────┬──────────┘
           │
           ▼ (Submit knop)
┌─────────────────────┐
│  script.js r.38      │──→ validateLoginForm() [CLIENT-SIDE]
│  JavaScript check    │    ├─ Velden leeg? → alert() → STOP
│                      │    └─ E-mail regex? → alert() → STOP
└──────────┬──────────┘
           │ Validatie OK → POST request
           ▼
┌─────────────────────┐
│  login.php regel 51  │──→ $_SERVER['REQUEST_METHOD'] == 'POST'
│  POST ontvangen      │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  login.php regel 61  │──→ loginUser($email, $password) [SERVER-SIDE]
│  functions.php r.292 │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────────────────────┐
│  loginUser() - functions.php r.292-317   │
│                                          │
│  1. validateRequired($email) r.296       │
│     → Leeg? Alleen spaties? → FOUT       │
│                                          │
│  2. validateRequired($password) r.298    │
│     → Leeg? Alleen spaties? → FOUT       │
│                                          │
│  3. SQL: SELECT FROM Users r.302         │
│     WHERE email = :email                 │
│     AND deleted_at IS NULL               │
│     → Prepared statement (veilig)        │
│                                          │
│  4. password_verify() r.307              │
│     → Vergelijk wachtwoord met bcrypt    │
│     → Niet geldig? → "Ongeldige e-mail   │
│       of wachtwoord" (generiek!)         │
│                                          │
│  5. $_SESSION['user_id'] = ... r.312     │
│  6. session_regenerate_id(true) r.314    │
│  7. updateLastActivity() r.315           │
│  8. return null (succes)                 │
└──────────┬──────────────────────────────┘
           │
           ▼
┌─────────────────────┐
│  login.php regel 65  │──→ if (!$error) → header("Location: index.php")
│  Redirect dashboard  │    exit;
└─────────────────────┘
```

---

## 9. CODE FLOW DIAGRAM: HOME PAGINA LADEN {#9-home-flow}

```
GEBRUIKER navigeert naar index.php
        │
        ▼
┌──────────────────────┐
│  index.php regel 26   │──→ require_once 'functions.php'
│                       │    → require_once 'db.php'
│                       │    → session_start()
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  index.php regel 29   │──→ checkSessionTimeout()
│  Sessie timeout check │    ├─ Ingelogd + last_activity > 1800s?
│                       │    │  → session_destroy()
│                       │    │  → Redirect: login.php?msg=session_timeout
│                       │    └─ Anders: update last_activity = time()
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐     NEE
│  index.php regel 32   │──→ isLoggedIn()? ──→ Redirect naar login.php
│  Inlog check          │
└──────────┬───────────┘
           │ JA
           ▼
┌──────────────────────┐
│  index.php regels     │──→ Data ophalen uit database:
│  38-53                │
│                       │    $userId = getUserId()           (r.38)
│                       │    updateLastActivity()            (r.41)
│                       │    $friends = getFriends($userId)  (r.48)
│                       │    $favorites = getFavoriteGames() (r.49)
│                       │    $schedules = getSchedules()     (r.50)
│                       │    $events = getEvents()           (r.51)
│                       │    $calendarItems = getCalendarItems() (r.52)
│                       │    $reminders = getReminders()     (r.53)
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  Sorteer validatie    │──→ getSchedules() / getEvents()
│  functions.php r.524  │    Whitelist check:
│                       │    in_array($sort, ['date ASC', 'date DESC',
│                       │                     'time ASC', 'time DESC'])
│                       │    → Alleen toegestane sorteerwaarden!
│                       │    → Voorkomt SQL-injectie via sorteerparameter
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  HTML Rendering       │──→ 5 secties:
│  index.php r.73-288   │    1. 👥 Vriendenlijst (tabel)
│                       │    2. 🎮 Favoriete Spellen (tabel)
│                       │    3. 📅 Schema's met sorteerknoppen
│                       │    4. 🎯 Evenementen met sorteerknoppen
│                       │    5. 📆 Kalender Overzicht (cards)
│                       │
│  ELKE output via      │──→ safeEcho() voor XSS bescherming
│  safeEcho()           │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│  Herinneringen        │──→ JavaScript alert() voor actieve reminders
│  index.php r.298-303  │    json_encode($reminders) → client-side
└──────────────────────┘
```

---

## 10. EIGENDOMSCONTROLE (OWNERSHIP CHECK) {#10-ownership}

**Bestand:** `functions.php` regels 640-645

```php
function checkOwnership($pdo, $table, $idColumn, $id, $userId) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table 
                           WHERE $idColumn = :id 
                           AND user_id = :user_id 
                           AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}
```

**Gebruikt bij:** `editSchedule`, `deleteSchedule`, `editEvent`, `deleteEvent`, `updateFriend`, `updateFavoriteGame`

**Principe:** Een gebruiker kan ALLEEN zijn eigen data bewerken of verwijderen. De `user_id` uit de sessie wordt altijd vergeleken met de `user_id` in de database.

---

## 11. SAMENVATTING PER PAGINA {#11-samenvatting}

| Pagina | Validaties | Beveiliging |
|--------|-----------|-------------|
| `login.php` | E-mail + wachtwoord verplicht, e-mail formaat | bcrypt verify, session regenerate, generieke foutmelding |
| `register.php` | Gebruikersnaam (max 50), e-mail formaat, wachtwoord (min 8), uniciteit | bcrypt hash, prepared statements |
| `index.php` | Sorteer whitelist | Session timeout, isLoggedIn, safeEcho op alle output |
| `profile.php` | Speltitel verplicht (max 100), duplicaat check | Session check, ownership via UserGames |
| `add_schedule.php` | Speltitel, datum, tijd, komma-gescheiden | Session check, prepared statements |
| `add_event.php` | Titel, datum, tijd, beschrijving (max 500), herinnering whitelist, URL | Session check, prepared statements |
| `add_friend.php` | Gebruikersnaam, status, duplicaat (case-insensitive) | Session check, prepared statements |
| `edit_*.php` | Zelfde validaties als add + ownership check | checkOwnership() + prepared statements |
| `delete.php` | Type whitelist, ownership check | Soft delete, session check |
| `contact.php` | Geen invoer (statische pagina) | Session timeout check |
| `db.php` | — | Singleton pattern, PDO opties, error_log (geen details aan gebruiker) |

---

## BEGRIPPENLIJST VOOR EXAMEN

| Term | Uitleg |
|------|--------|
| **Prepared Statement** | SQL-query waarbij waarden apart worden meegegeven, voorkomt SQL-injectie |
| **Bcrypt** | Wachtwoord hash-algoritme met automatische salt en instelbare cost |
| **Session Fixation** | Aanval waarbij een hacker een sessie-ID forceert; voorkomen door `session_regenerate_id()` |
| **XSS** | Cross-Site Scripting — kwaadaardige scripts in HTML; voorkomen door `htmlspecialchars()` |
| **Soft Delete** | Data markeren als verwijderd (`deleted_at`) in plaats van echt wissen |
| **Singleton Pattern** | Ontwerppatroon waarbij slechts één instantie van een object bestaat (hier: databaseverbinding) |
| **PDO** | PHP Data Objects — veilige database-abstractielaag |
| **Regex** | Reguliere Expressie — patroon om tekst te valideren |
| **CRUD** | Create, Read, Update, Delete — de vier basisbewerkingen op data |
| **Whitelist** | Lijst van toegestane waarden; alles wat NIET op de lijst staat wordt geweigerd |
| **Ownership Check** | Controle of de ingelogde gebruiker eigenaar is van het te bewerken/verwijderen item |
| **Client-side validatie** | Validatie in de browser (JavaScript) — voor snelle feedback |
| **Server-side validatie** | Validatie op de server (PHP) — de echte beveiliging, kan niet omzeild worden |
| **Salt** | Willekeurige tekst die aan wachtwoord wordt toegevoegd vóór hashing |
| **Output Buffering** | `ob_start()` — voorkomt "headers already sent" fouten |
