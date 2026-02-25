# GAMEPLAN SCHEDULER — COMPLETE VALIDATIE-DOCUMENTATIE A TOT Z

**Auteur:** Harsha Kanaparthi | **Studentnummer:** 2195344 | **Datum:** 30-09-2025
**Project:** GamePlan Scheduler | **Opleiding:** MBO-4 Software Developer

---

## INHOUDSOPGAVE

1. [Alle Validaties — Compleet Overzicht](#1-alle-validaties)
2. [Algoritme per Validatie](#2-algoritmen)
3. [Alle Functionele Flows A tot Z](#3-functionele-flows)
4. [Code Flow Diagram — Login Pagina](#4-login-flow)
5. [Code Flow Diagram — Home Pagina Laden](#5-home-flow)

---

## 1. ALLE VALIDATIES — COMPLEET OVERZICHT

Hieronder staat **elke validatie** die in de hele applicatie voorkomt, gegroepeerd per functie. Er zijn twee lagen: JavaScript (client-side, in de browser) en PHP (server-side, op de server). De server-side validatie is de echte beveiliging en kan NIET omzeild worden.

### 1.1 OVERZICHTSTABEL — ALLE 32 VALIDATIES

| Nr | Validatie | Type | Bestand | Regels | Waar gebruikt |
|----|-----------|------|---------|--------|---------------|
| V01 | E-mail niet leeg | Server | functions.php | 296 | Login |
| V02 | Wachtwoord niet leeg | Server | functions.php | 298 | Login |
| V03 | E-mail bestaat in database | Server | functions.php | 302-304 | Login |
| V04 | Wachtwoord klopt (bcrypt verify) | Server | functions.php | 307 | Login |
| V05 | Gebruikersnaam niet leeg, max 50 | Server | functions.php | 259 | Registratie |
| V06 | E-mail geldig formaat | Server | functions.php | 261 | Registratie |
| V07 | Wachtwoord niet leeg | Server | functions.php | 263 | Registratie |
| V08 | Wachtwoord minimaal 8 tekens | Server | functions.php | 265-266 | Registratie |
| V09 | E-mail nog niet geregistreerd | Server | functions.php | 269-272 | Registratie |
| V10 | Speltitel niet leeg, max 100 | Server | functions.php | 504 | Schedule, Favorite |
| V11 | Datum geldig formaat JJJJ-MM-DD | Server | functions.php | 101-106 | Schedule, Event |
| V12 | Datum vandaag of toekomst | Server | functions.php | 111-114 | Schedule, Event |
| V13 | Tijd geldig formaat UU:MM | Server | functions.php | 126-128 | Schedule, Event |
| V14 | Komma-gescheiden niet leeg items | Server | functions.php | 164-170 | Schedule, Event |
| V15 | Eventtitel niet leeg, max 100 | Server | functions.php | 571 | Event |
| V16 | Beschrijving max 500 tekens | Server | functions.php | 577-578 | Event |
| V17 | Herinnering whitelist | Server | functions.php | 579-580 | Event |
| V18 | URL geldig formaat | Server | functions.php | 150-152 | Event |
| V19 | Vriendnaam niet leeg, max 50 | Server | functions.php | 445 | Vriend |
| V20 | Status niet leeg, max 50 | Server | functions.php | 447 | Vriend |
| V21 | Vriend niet al toegevoegd | Server | functions.php | 451-454 | Vriend |
| V22 | Spel niet al in favorieten | Server | functions.php | 369-372 | Favoriet |
| V23 | Eigendomscontrole (ownership) | Server | functions.php | 640-644 | Edit/Delete |
| V24 | Sessie timeout (30 min) | Server | functions.php | 242-247 | Alle pagina's |
| V25 | Gebruiker ingelogd check | Server | functions.php | 211-213 | Alle pagina's |
| V26 | XSS output escaping | Server | functions.php | 50-55 | Alle output |
| V27 | Sorteer whitelist | Server | functions.php | 524, 594 | Dashboard |
| V28 | Login velden leeg (JS) | Client | script.js | 49-53 | Login formulier |
| V29 | E-mail regex (JS) | Client | script.js | 60-63 | Login/Register |
| V30 | Registratie velden check (JS) | Client | script.js | 102-133 | Register formulier |
| V31 | Schedule formulier check (JS) | Client | script.js | 163-224 | Schedule formulier |
| V32 | Event formulier check (JS) | Client | script.js | 253-327 | Event formulier |

---

## 2. ALGORITME PER VALIDATIE

### ALGORITME V01-V04: LOGIN VALIDATIE

**Functie:** `loginUser($email, $password)` in `functions.php` (regel 292-317)

```
ALGORITME: LoginGebruiker(email, wachtwoord)

STAP 1: Controleer of email NIET leeg is
        ALS email leeg is OF email bevat alleen spaties
            RETOURNEER foutmelding "Email mag niet leeg zijn"
        EINDE ALS

STAP 2: Controleer of wachtwoord NIET leeg is
        ALS wachtwoord leeg is OF wachtwoord bevat alleen spaties
            RETOURNEER foutmelding "Wachtwoord mag niet leeg zijn"
        EINDE ALS

STAP 3: Zoek gebruiker in database
        VOER UIT: SELECT user_id, username, password_hash
                  FROM Users
                  WHERE email = [ingevoerde email]
                  AND deleted_at IS NULL
        (Prepared statement — beschermt tegen SQL-injectie)

STAP 4: Controleer wachtwoord met bcrypt
        ALS gebruiker NIET gevonden OF password_verify() is ONWAAR
            RETOURNEER "Ongeldige e-mail of wachtwoord"
            (Generieke melding — onthult NIET of email bestaat)
        EINDE ALS

STAP 5: Maak sessie aan
        Sla user_id op in $_SESSION['user_id']
        Sla username op in $_SESSION['username']
        Genereer nieuw sessie-ID (voorkomt session fixation aanval)
        Update laatste activiteit timestamp

STAP 6: RETOURNEER null (geen fout = succes)
```

**PHP-code:**
```php
function loginUser($email, $password) {
    $pdo = getDBConnection();
    if ($err = validateRequired($email, "Email")) return $err;
    if ($err = validateRequired($password, "Password")) return $err;
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return "Invalid email or password.";
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    session_regenerate_id(true);
    updateLastActivity($pdo, $user['user_id']);
    return null;
}
```

---

### ALGORITME V05-V09: REGISTRATIE VALIDATIE

**Functie:** `registerUser($username, $email, $password)` in `functions.php` (regel 254-286)

```
ALGORITME: RegistreerGebruiker(gebruikersnaam, email, wachtwoord)

STAP 1: Controleer gebruikersnaam
        Verwijder witruimte aan begin en einde (trim)
        ALS gebruikersnaam leeg is OF alleen spaties bevat
            RETOURNEER "Gebruikersnaam mag niet leeg zijn"
        ALS lengte > 50 tekens
            RETOURNEER "Gebruikersnaam te lang (max 50)"
        EINDE ALS

STAP 2: Controleer e-mail formaat
        ALS email NIET voldoet aan FILTER_VALIDATE_EMAIL
            RETOURNEER "Ongeldig e-mail formaat"
        EINDE ALS

STAP 3: Controleer wachtwoord niet leeg
        ALS wachtwoord leeg is OF alleen spaties
            RETOURNEER "Wachtwoord mag niet leeg zijn"
        EINDE ALS

STAP 4: Controleer wachtwoord lengte
        ALS lengte(wachtwoord) < 8
            RETOURNEER "Wachtwoord moet minimaal 8 tekens zijn"
        EINDE ALS

STAP 5: Controleer email uniciteit
        VOER UIT: SELECT COUNT(*) FROM Users WHERE email = [email] AND deleted_at IS NULL
        ALS aantal > 0
            RETOURNEER "E-mail al geregistreerd"
        EINDE ALS

STAP 6: Hash wachtwoord
        hash = password_hash(wachtwoord, PASSWORD_BCRYPT)
        (Bcrypt genereert automatisch een unieke salt)
        (Wachtwoord wordt NOOIT als platte tekst opgeslagen)

STAP 7: Sla gebruiker op
        VOER UIT: INSERT INTO Users (username, email, password_hash)
                  VALUES ([gebruikersnaam], [email], [hash])
        ALS database fout
            Log fout naar server (NIET naar gebruiker)
            RETOURNEER "Registratie mislukt"
        EINDE ALS

STAP 8: RETOURNEER null (succes)
```

---

### ALGORITME V10-V14: SCHEDULE VALIDATIE

**Functie:** `addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr)` in `functions.php` (regel 500-519)

```
ALGORITME: VoegSchemaToe(gebruikerId, spelTitel, datum, tijd, vrienden, gedeeldMet)

STAP 1: Controleer speltitel
        Verwijder witruimte (trim)
        ALS leeg OF alleen spaties → FOUT "Speltitel mag niet leeg zijn"
        ALS lengte > 100 → FOUT "Speltitel te lang"

STAP 2: Controleer datum (BUG FIX #1004)
        Parseer datum met DateTime::createFromFormat('Y-m-d', datum)
        ALS parsing mislukt OF output ≠ input → FOUT "Ongeldig datum formaat"
        ALS datum < vandaag → FOUT "Datum moet vandaag of later zijn"

STAP 3: Controleer tijd
        ALS tijd NIET matcht met regex /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
            FOUT "Ongeldig tijd formaat"

STAP 4: Controleer vrienden (optioneel)
        ALS niet leeg:
            Splits op komma's
            VOOR ELK item: ALS na trim leeg → FOUT "Bevat lege items"

STAP 5: Controleer gedeeld-met (optioneel, zelfde als stap 4)

STAP 6: Zoek of maak spel
        Zoek spel op titel (case-insensitive)
        ALS niet gevonden → maak nieuw spel aan

STAP 7: Sla schema op
        INSERT INTO Schedules met prepared statement
        RETOURNEER null (succes)
```

---

### ALGORITME V15-V18: EVENT VALIDATIE

**Functie:** `addEvent(...)` in `functions.php` (regel 567-589)

```
ALGORITME: VoegEvenementToe(gebruikerId, titel, datum, tijd, beschrijving, herinnering, link, gedeeldMet)

STAP 1: Titel niet leeg, max 100 tekens (zelfde als V10)
STAP 2: Datum geldig + toekomst (zelfde als V11-V12)
STAP 3: Tijd geldig UU:MM (zelfde als V13)

STAP 4: Beschrijving lengte
        ALS niet leeg EN lengte > 500 → FOUT "Beschrijving te lang (max 500)"

STAP 5: Herinnering whitelist
        ALS herinnering NIET in ['none', '1_hour', '1_day']
            FOUT "Ongeldige herinnering"
        (Whitelist = alleen deze 3 waarden zijn toegestaan)

STAP 6: Externe link (optioneel)
        ALS niet leeg EN NIET geldig volgens FILTER_VALIDATE_URL
            FOUT "Ongeldig URL formaat"

STAP 7: Gedeeld-met komma-gescheiden (zelfde als V14)

STAP 8: INSERT INTO Events met prepared statement
        RETOURNEER null (succes)
```

---

### ALGORITME V19-V21: VRIEND VALIDATIE

**Functie:** `addFriend($userId, $friendUsername, $note, $status)` in `functions.php` (regel 441-458)

```
ALGORITME: VoegVriendToe(gebruikerId, vriendNaam, notitie, status)

STAP 1: Vriendnaam niet leeg, max 50 tekens
STAP 2: Status niet leeg, max 50 tekens

STAP 3: Duplicaat check (case-insensitive)
        VOER UIT: SELECT COUNT(*) FROM Friends
                  WHERE user_id = [gebruikerId]
                  AND LOWER(friend_username) = LOWER([vriendNaam])
                  AND deleted_at IS NULL
        ALS aantal > 0 → FOUT "Al vrienden"

STAP 4: INSERT INTO Friends met prepared statement
        RETOURNEER null (succes)
```

---

### ALGORITME V22: FAVORIET DUPLICAAT CHECK

**Functie:** `addFavoriteGame(...)` in `functions.php` (regel 360-377)

```
ALGORITME: VoegFavorietToe(gebruikerId, titel, beschrijving, notitie)

STAP 1: Speltitel niet leeg, max 100
STAP 2: Zoek of maak spel (case-insensitive op titel)

STAP 3: Controleer of al in favorieten
        SELECT COUNT(*) FROM UserGames WHERE user_id = ? AND game_id = ?
        ALS > 0 → FOUT "Spel al in favorieten"

STAP 4: INSERT INTO UserGames
        RETOURNEER null
```

---

### ALGORITME V23: EIGENDOMSCONTROLE (OWNERSHIP CHECK)

**Functie:** `checkOwnership($pdo, $table, $idColumn, $id, $userId)` in `functions.php` (regel 640-645)

```
ALGORITME: ControleerEigendom(tabel, idKolom, id, gebruikerId)

STAP 1: VOER UIT: SELECT COUNT(*) FROM [tabel]
                  WHERE [idKolom] = [id]
                  AND user_id = [gebruikerId]
                  AND deleted_at IS NULL

STAP 2: ALS count > 0 → RETOURNEER WAAR (eigenaar)
        ANDERS → RETOURNEER ONWAAR (geen eigenaar)

Gebruikt bij: editSchedule, deleteSchedule, editEvent, deleteEvent
Doel: Gebruiker A kan NIET de data van Gebruiker B bewerken/verwijderen
```

---

### ALGORITME V24: SESSIE TIMEOUT

**Functie:** `checkSessionTimeout()` in `functions.php` (regel 239-248)

```
ALGORITME: ControleerSessieTimeout()

STAP 1: ALS gebruiker ingelogd EN last_activity bestaat
            Bereken verschil = huidige_tijd - last_activity
            ALS verschil > 1800 seconden (= 30 minuten)
                Vernietig sessie (session_destroy)
                Redirect naar login.php met bericht "sessie verlopen"
                STOP
            EINDE ALS
        EINDE ALS

STAP 2: Update $_SESSION['last_activity'] = huidige_tijd
```

---

### ALGORITME V25: INLOG CHECK

```
ALGORITME: IsIngelogd()
    RETOURNEER: bestaat $_SESSION['user_id']?
    Gebruikt op: index.php, profile.php, delete.php, alle add/edit pagina's
    ALS niet ingelogd → redirect naar login.php
```

---

### ALGORITME V26: XSS BESCHERMING

**Functie:** `safeEcho($string)` in `functions.php` (regel 50-55)

```
ALGORITME: VeiligeOutput(tekst)

STAP 1: ALS tekst is null → gebruik lege string
STAP 2: Converteer speciale HTML-tekens:
        < wordt &lt;
        > wordt &gt;
        " wordt &quot;
        ' wordt &#039;
        & wordt &amp;

STAP 3: RETOURNEER veilige tekst

Voorbeeld: <script>alert('hack')</script>
Wordt:     &lt;script&gt;alert(&#039;hack&#039;)&lt;/script&gt;
→ Browser toont de tekst in plaats van de code uit te voeren
```

---

### ALGORITME V27: SORTEER WHITELIST

```
ALGORITME: ValideerSortering(sorteerwaarde)

    Toegestane waarden = ['date ASC', 'date DESC', 'time ASC', 'time DESC']
    ALS sorteerwaarde NIET in toegestane waarden
        Gebruik standaard 'date ASC'
    EINDE ALS

Doel: Voorkomt SQL-injectie via de sorteerparameter in de URL
```

---

### ALGORITME V28-V32: CLIENT-SIDE (JAVASCRIPT) VALIDATIES

```
ALGORITME: ValideerLoginFormulier() — script.js

    STAP 1: Haal email op, verwijder witruimte (trim)
    STAP 2: Haal wachtwoord op, verwijder witruimte
    STAP 3: ALS email OF wachtwoord leeg → alert → STOP
    STAP 4: ALS email NIET matcht regex /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            → alert "Ongeldig e-mail formaat" → STOP
    STAP 5: RETOURNEER true (formulier mag verzonden worden)
```

```
ALGORITME: ValideerRegistratieFormulier() — script.js

    STAP 1: Haal username, email, password op (met trim)
    STAP 2: ALS een veld leeg → alert → STOP
    STAP 3: ALS username matcht /^\s*$/ (alleen spaties) → alert → STOP (Bug #1001)
    STAP 4: ALS username.length > 50 → alert → STOP
    STAP 5: ALS email niet geldig regex → alert → STOP
    STAP 6: ALS password.length < 8 → alert → STOP
    STAP 7: RETOURNEER true
```

```
ALGORITME: ValideerScheduleFormulier() — script.js

    STAP 1: Haal gameTitle, date, time, friends, sharedWith op
    STAP 2: ALS gameTitle leeg OF alleen spaties → alert → STOP (Bug #1001)
    STAP 3: ALS date leeg → alert → STOP
    STAP 4: Maak Date-object, ALS ongeldig → alert → STOP
    STAP 5: ALS datum < vandaag → alert → STOP (Bug #1004)
    STAP 6: ALS time niet matcht /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/ → alert → STOP
    STAP 7: ALS friends bevat ongeldige tekens → alert → STOP
    STAP 8: ALS sharedWith bevat ongeldige tekens → alert → STOP
    STAP 9: RETOURNEER true
```

```
ALGORITME: ValideerEventFormulier() — script.js

    STAP 1-2: Titel niet leeg, niet alleen spaties (Bug #1001)
    STAP 3: Titel max 100 tekens
    STAP 4-5: Datum verplicht, niet in verleden (Bug #1004)
    STAP 6: Tijd geldig UU:MM formaat
    STAP 7: Beschrijving max 500 tekens
    STAP 8: Externe link geldig URL regex (optioneel)
    STAP 9: Gedeeld-met alleen letters, cijfers, komma's
    STAP 10: RETOURNEER true
```

---

## 3. ALLE FUNCTIONELE FLOWS A TOT Z

### FLOW 1: REGISTRATIE (Nieuw account aanmaken)

```
Gebruiker → register.php → validateRegisterForm() [JS]
    → POST naar register.php → registerUser() [PHP]
    → validateRequired(username) → validateEmail(email)
    → validateRequired(password) → strlen >= 8
    → email uniciteit check → password_hash(bcrypt)
    → INSERT Users → redirect login.php met succesbericht
```

### FLOW 2: INLOGGEN

```
Gebruiker → login.php → validateLoginForm() [JS]
    → POST naar login.php → loginUser() [PHP]
    → validateRequired(email) → validateRequired(password)
    → SELECT Users WHERE email → password_verify(bcrypt)
    → $_SESSION['user_id'] → session_regenerate_id()
    → updateLastActivity() → redirect index.php
```

### FLOW 3: DASHBOARD LADEN (index.php)

```
Browser → index.php → require functions.php → require db.php
    → session_start() → checkSessionTimeout()
    → isLoggedIn()? NEE → redirect login.php
                     JA  → getUserId()
    → updateLastActivity()
    → getFriends() → getFavoriteGames() → getSchedules()
    → getEvents() → getCalendarItems() → getReminders()
    → HTML renderen met safeEcho() op ALLE output
    → JavaScript herinneringen als alert()
```

### FLOW 4: SCHEMA TOEVOEGEN

```
Gebruiker → add_schedule.php → checkSessionTimeout() → isLoggedIn()
    → Formulier invullen → validateScheduleForm() [JS]
    → POST → addSchedule() [PHP]
    → validateRequired(gameTitle) → validateDate(date)
    → validateTime(time) → validateCommaSeparated(friends)
    → getOrCreateGameId() → INSERT Schedules
    → redirect index.php met succesbericht
```

### FLOW 5: EVENEMENT TOEVOEGEN

```
Gebruiker → add_event.php → checkSessionTimeout() → isLoggedIn()
    → Formulier invullen → validateEventForm() [JS]
    → POST → addEvent() [PHP]
    → validateRequired(title) → validateDate(date)
    → validateTime(time) → strlen(description) <= 500
    → in_array(reminder, whitelist) → validateUrl(link)
    → validateCommaSeparated(sharedWith) → INSERT Events
    → redirect index.php
```

### FLOW 6: VRIEND TOEVOEGEN

```
Gebruiker → add_friend.php → checkSessionTimeout() → isLoggedIn()
    → POST → addFriend() [PHP]
    → validateRequired(friendUsername) → validateRequired(status)
    → duplicaat check (case-insensitive) → INSERT Friends
    → redirect index.php
```

### FLOW 7: FAVORIET SPEL TOEVOEGEN

```
Gebruiker → profile.php → checkSessionTimeout() → isLoggedIn()
    → POST → addFavoriteGame() [PHP]
    → validateRequired(title) → getOrCreateGameId()
    → duplicaat check → INSERT UserGames
    → redirect profile.php
```

### FLOW 8: DATA BEWERKEN (Edit)

```
Gebruiker → edit_*.php → checkSessionTimeout() → isLoggedIn()
    → GET id uit URL → checkOwnership(tabel, id, userId)
    → ALS geen eigenaar → FOUT "Geen toestemming"
    → Haal huidige data op → Toon formulier met vooraf ingevulde waarden
    → POST → zelfde validaties als bij toevoegen
    → UPDATE met prepared statement → redirect
```

### FLOW 9: DATA VERWIJDEREN (Delete)

```
Gebruiker → JavaScript confirm("Weet je het zeker?")
    → JA → delete.php?type=schedule&id=5
    → checkSessionTimeout() → isLoggedIn()
    → Bepaal type (schedule/event/favorite/friend)
    → Roep juiste delete-functie aan
    → checkOwnership() → SOFT DELETE (deleted_at = NOW())
    → setMessage(succes/fout) → redirect
```

### FLOW 10: UITLOGGEN

```
Gebruiker → klikt "Uitloggen" → logout() [PHP]
    → session_destroy() → redirect login.php
```

### FLOW 11: DATABASE VERBINDING

```
Elke pagina → functions.php → db.php → getDBConnection()
    → Singleton: ALS $pdo === null → maak nieuwe PDO verbinding
                 ANDERS → hergebruik bestaande verbinding
    → PDO opties: ERRMODE_EXCEPTION, FETCH_ASSOC,
                  EMULATE_PREPARES = false, PERSISTENT = true
    → BIJ FOUT: error_log() (voor developers)
                die() met generieke melding (voor gebruikers)
```

---

## 4. CODE FLOW DIAGRAM — LOGIN PAGINA

```
GEBRUIKER OPENT login.php IN BROWSER
            |
            v
   +------------------+
   | login.php r.26   |----> require_once 'functions.php'
   | Laad functies    |        |
   +------------------+        v
            |         +------------------+
            |         | functions.php    |----> require_once 'db.php'
            |         | r.22            |      (database beschikbaar)
            |         +------------------+
            |                  |
            |                  v
            |         +------------------+
            |         | functions.php    |----> session_start()
            |         | r.32-37         |      session_regenerate_id(true)
            |         +------------------+
            |
            v
   +------------------+      JA
   | login.php r.35   |-----------> header("Location: index.php")
   | isLoggedIn()?    |             exit; (al ingelogd, geen login nodig)
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
   | VALIDATIE         |  1. email = trim(document.getElementById('email'))
   |                  |  2. password = trim(document.getElementById('password'))
   |                  |  3. ALS !email || !password → alert() → return false
   |                  |  4. ALS email niet matcht regex → alert() → return false
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
   |         → trim → empty check → regex spaties check   |
   |         → ALS fout: RETOURNEER foutmelding            |
   |                                                      |
   | STAP 2: validateRequired($password, "Password")      |
   |         → zelfde controles als stap 1                 |
   |                                                      |
   | STAP 3: Database query (prepared statement)           |
   |         SELECT user_id, username, password_hash       |
   |         FROM Users WHERE email = :email               |
   |         AND deleted_at IS NULL                        |
   |                                                      |
   | STAP 4: password_verify($password, $user['hash'])     |
   |         → Vergelijk met bcrypt hash                   |
   |         → ALS mislukt: "Ongeldige e-mail of wachtw." |
   |                                                      |
   | STAP 5: $_SESSION['user_id'] = $user['user_id']      |
   |         $_SESSION['username'] = $user['username']     |
   |         session_regenerate_id(true)                   |
   |         updateLastActivity($pdo, $user['user_id'])    |
   |                                                      |
   | STAP 6: return null (succes, geen fout)               |
   +====================================================+
            |
            v
   +------------------+
   | login.php r.65   |  if (!$error) {
   | SUCCES CHECK     |      header("Location: index.php");
   +------------------+      exit;
            |              }
            v
   +------------------+
   | login.php r.127  |  ALS $error:
   | FOUT TONEN       |  <div class="alert alert-danger">
   |                  |    <?php echo safeEcho($error); ?>
   |                  |  </div>
   +------------------+
```

**Bestanden betrokken bij Login:**
- `login.php` — Formulier en POST-verwerking
- `functions.php` — `loginUser()`, `validateRequired()`, `isLoggedIn()`, `safeEcho()`
- `db.php` — `getDBConnection()` (PDO Singleton)
- `script.js` — `validateLoginForm()` (client-side)
- `style.css` — Glassmorphism styling van het formulier

---

## 5. CODE FLOW DIAGRAM — HOME PAGINA LADEN

```
GEBRUIKER NAVIGEERT NAAR index.php
            |
            v
   +------------------+
   | index.php r.26   |----> require_once 'functions.php'
   | LAAD FUNCTIES    |        → require_once 'db.php'
   |                  |        → session_start()
   |                  |        → session_regenerate_id(true)
   +------------------+
            |
            v
   +------------------+
   | index.php r.29   |----> checkSessionTimeout()
   | SESSIE CHECK     |
   |                  |  ALS ingelogd EN last_activity > 1800s:
   |                  |      session_destroy()
   |                  |      redirect login.php?msg=session_timeout
   |                  |  ANDERS:
   |                  |      $_SESSION['last_activity'] = time()
   +------------------+
            |
            v
   +------------------+      NEE
   | index.php r.32   |-----------> header("Location: login.php")
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
   | UPDATE ACTIVITEIT|  → UPDATE Users SET last_activity = CURRENT_TIMESTAMP
   |                  |    WHERE user_id = :user_id AND deleted_at IS NULL
   +------------------+
            |
            v
   +------------------+
   | index.php r.44   |  $sortSchedules = $_GET['sort_schedules'] ?? 'date ASC'
   | SORTEER PARAMS   |  $sortEvents = $_GET['sort_events'] ?? 'date ASC'
   |                  |  (uit URL parameters)
   +------------------+
            |
            v
   +=====================================================+
   | DATA OPHALEN UIT DATABASE (index.php r.48-53)        |
   |                                                       |
   | 1. $friends = getFriends($userId)                     |
   |    → SELECT friend_id, friend_username, status, note  |
   |      FROM Friends WHERE user_id = :uid                |
   |      AND deleted_at IS NULL                           |
   |                                                       |
   | 2. $favorites = getFavoriteGames($userId)             |
   |    → SELECT g.game_id, g.titel, g.description, ug.note|
   |      FROM UserGames ug JOIN Games g                   |
   |      ON ug.game_id = g.game_id                        |
   |      WHERE ug.user_id = :uid AND g.deleted_at IS NULL |
   |                                                       |
   | 3. $schedules = getSchedules($userId, $sort)          |
   |    → SORTEER WHITELIST validatie:                     |
   |      in_array($sort, ['date ASC', 'date DESC',       |
   |                        'time ASC', 'time DESC'])      |
   |    → SELECT s.*, g.titel FROM Schedules s             |
   |      JOIN Games g ON s.game_id = g.game_id            |
   |      WHERE s.user_id = :uid AND s.deleted_at IS NULL  |
   |      ORDER BY [gevalideerde sort] LIMIT 50            |
   |                                                       |
   | 4. $events = getEvents($userId, $sort)                |
   |    → Zelfde whitelist + SELECT Events                 |
   |                                                       |
   | 5. $calendarItems = getCalendarItems($userId)         |
   |    → Combineer schedules + events                     |
   |    → Sorteer op datum+tijd met usort()                |
   |                                                       |
   | 6. $reminders = getReminders($userId)                 |
   |    → Filter events met reminder ≠ 'none'             |
   |    → Check of herinneringstijd bereikt is             |
   +=====================================================+
            |
            v
   +------------------+
   | index.php r.71   |  include 'header.php'
   | HEADER LADEN     |  → Navigatie: Dashboard, Profiel, Uitloggen
   |                  |  → Sessie-based: toon Uitloggen als ingelogd
   +------------------+
            |
            v
   +------------------+
   | index.php r.75   |  echo getMessage()
   | SESSIE BERICHTEN |  → Toon succes/fout van vorige actie
   |                  |  → Wis bericht uit sessie (eenmalig)
   +------------------+
            |
            v
   +=====================================================+
   | HTML SECTIES RENDEREN (index.php r.80-288)            |
   |                                                       |
   | SECTIE 1: 👥 Vriendenlijst (r.80-116)               |
   |   → Tabel met username, status badge, notitie         |
   |   → Edit/Delete knoppen per vriend                    |
   |   → ALLE output via safeEcho() (XSS bescherming)     |
   |                                                       |
   | SECTIE 2: 🎮 Favoriete Spellen (r.121-153)          |
   |   → Tabel met titel, beschrijving, notitie            |
   |   → Edit/Delete knoppen                               |
   |                                                       |
   | SECTIE 3: 📅 Schema's (r.158-198)                   |
   |   → Sorteerknoppen: Date ↑ / Date ↓                  |
   |   → Tabel met spel, datum, tijd, vrienden, gedeeld    |
   |   → Edit/Delete knoppen                               |
   |                                                       |
   | SECTIE 4: 🎯 Evenementen (r.203-249)                |
   |   → Sorteerknoppen: Date ↑ / Date ↓                  |
   |   → Tabel met titel, datum, tijd, beschrijving,       |
   |     herinnering badge, externe link                   |
   |   → Edit/Delete knoppen                               |
   |                                                       |
   | SECTIE 5: 📆 Kalender Overzicht (r.254-288)         |
   |   → Cards met gecombineerde items                     |
   |   → Gesorteerd op datum+tijd                          |
   |   → Herinnering badges en externe links               |
   +=====================================================+
            |
            v
   +------------------+
   | index.php r.292  |  include 'footer.php'
   | FOOTER           |  → Copyright © 2025 Harsha Kanaparthi
   +------------------+
            |
            v
   +------------------+
   | index.php r.294  |  Bootstrap JS laden
   | SCRIPTS          |  script.js laden
   +------------------+
            |
            v
   +------------------+
   | index.php r.298  |  const reminders = <?php echo json_encode($reminders); ?>;
   | HERINNERINGEN    |  reminders.forEach(r => {
   |                  |      alert(`🔔 Herinnering: ${r.title} om ${r.time}`);
   |                  |  });
   +------------------+
```

**Bestanden betrokken bij Home Pagina:**
- `index.php` — Hoofd dashboard pagina (305 regels)
- `functions.php` — Alle data-ophaal functies, sessie, validatie (672 regels)
- `db.php` — Database verbinding via PDO Singleton (314 regels)
- `header.php` — Navigatie met sessie-based rendering
- `footer.php` — Copyright footer
- `script.js` — Delete bevestiging, alert auto-dismiss (433 regels)
- `style.css` — Glassmorphism dark theme styling

---

## SAMENVATTING — ALLES OP ÉÉN PAGINA

| Onderdeel | Validaties | Beveiliging |
|-----------|-----------|-------------|
| **Login** | Email verplicht, wachtwoord verplicht, email formaat (JS+PHP) | bcrypt verify, session regenerate, generieke foutmelding |
| **Registratie** | Username (max 50), email formaat, wachtwoord (min 8), uniciteit | bcrypt hash, prepared statements |
| **Schedule** | Speltitel (max 100), datum (JJJJ-MM-DD, toekomst), tijd (UU:MM), komma-gescheiden | Session check, ownership, prepared statements |
| **Event** | Titel (max 100), datum, tijd, beschrijving (max 500), herinnering whitelist, URL | Session check, ownership, prepared statements |
| **Vriend** | Naam (max 50), status (max 50), duplicaat check | Session check, prepared statements |
| **Favoriet** | Speltitel (max 100), duplicaat check | Session check, ownership |
| **Verwijderen** | Type whitelist, ownership check | Soft delete, session check |
| **Alle pagina's** | Sessie timeout 30 min, inlog check | safeEcho (XSS), PDO (SQL-injectie) |

**Totaal: 32 validaties | 11 functionele flows | 2 code flow diagrammen**
