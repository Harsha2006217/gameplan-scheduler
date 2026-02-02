# ⚙️ VOLLEDIGE FUNCTIE REFERENTIE (MASTER-EDITIE)
## GamePlan Scheduler - Dé Technische Handleiding van de Backend

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Dit document bevat de volledige blauwdruk van alle PHP-functies in `functions.php`. Elke functie is gebouwd met drie kernwaarden: Veiligheid, Herbruikbaarheid en Helderheid."

---

# 1. Beveiligings- & Helper Functies

### `safeEcho($string)`
- **Doel**: Voorkomt Cross-Site Scripting (XSS).
- **Werking**: Zet gevaarlijke karakters om in HTML-entiteiten. 
- **Techniek**: `htmlspecialchars($string, ENT_QUOTES, 'UTF-8')`.
- **Waarom?**: Zonder deze functie kan een hacker JavaScript uitvoeren in de browser van andere gebruikers via ingevoerde namen of titels.

### `validateRequired($value, $fieldName, $maxLength)`
- **Doel**: **Bugfix #1001**. Zorgt dat verplichte velden echt gevuld zijn.
- **Logica**: Gebruikt `trim()` om witruimte te verwijderen en checkt daarna op lege waarden of alleen spaties via de regex `/^\s*$/`.
- **Parameter**: `$maxLength` zorgt dat de database niet overstroomt met te lange teksten (DoS preventie).

### `validateDate($date)`
- **Doel**: **Bugfix #1004**. Strikte datum-validatie.
- **Werking**: Gebruikt de `DateTime` klasse om te controleren of de datum syntactisch en logisch correct is (geen 32 januari).
- **Chronologie**: Blokkeert datums die in het verleden liggen voor nieuwe afspraken.

### `validateTime($time)`
- **Doel**: Controleert of de ingevoerde tijd voldoet aan het 24-uurs formaat (HH:MM).

### `validateEmail($email)`
- **Techniek**: Gebruikt PHP's native `FILTER_VALIDATE_EMAIL`.

---

# 2. Authenticatie & Sessie Management

### `registerUser($username, $email, $password)`
- **Flow**: 
    1. Checkt via `SQL SELECT` of het mailadres al bestaat.
    2. Indien uniek: Hasht het wachtwoord met `PASSWORD_BCRYPT`.
    3. Voert een `Prepared INSERT` uit.
- **Resultaat**: Een veilige opslag van een nieuwe gebruiker.

### `loginUser($email, $password)`
- **Proces**: 
    1. Haalt de `password_hash` op uit de DB op basis van email.
    2. Verifieert de invoer via `password_verify()`.
    3. Bij succes: Slaat de `user_id` en `username` op in de `$_SESSION` array.
    4. **Critical**: Roept `session_regenerate_id(true)` aan om Session Hijacking te voorkomen.

### `checkSessionTimeout()`
- **Logica**: Wordt bovenaan elke beveiligde pagina aangeroepen. 
- **Werking**: Vergelijkt `time()` met de opgeslagen `last_activity`. Als het verschil > 1800 seconden is, volgt een automatische uitlog.
- **Doel**: Sessiebeveiliging op openbare computers.

---

# 3. Game & Agenda Logica (CRUD)

### `getSchedules($userId, $sortOrder)`
- **Techniek**: Gebruikt een `INNER JOIN` tussen de tabellen `Schedules` en `Games`. 
- **Input**: `$userId` (beveiliging) en `$sortOrder` (ASC voor oud-naar-nieuw, DESC voor nieuw-naar-oud).
- **Output**: Een gesorteerde lijst van gaming afspraken inclusief speltitels.

### `addSchedule($data)`
- **Stroom**: 
    1. Valideert alle invoer (game_id, date, time, friends).
    2. Controleert eigenaarschap.
    3. Voert de INSERT query uit met bindParams.

### `getOrCreateGameId($gameTitle)`
- **Algoritmische Winst**: Checkt of een speltitel al aanwezig is in de `Games` tabel.
- **Impact**: Voorkomt database-vervuiling en zorgt voor data-normalisatie.

### `editSchedule($data, $id)`
- **Veiligheidscontrole**: Roept `checkOwnership()` aan voordat er ook maar één letter in de database wordt veranderd.

---

# 4. Sociale Functies & Vrienden

### `getFriends($userId)`
- **Query**: `SELECT * FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL`.
- **Soft Delete**: De `deleted_at IS NULL` clausule zorgt dat "verwijderde" vrienden niet meer getoond worden, maar nog wel in de backup staan.

### `addFriend($userId, $friendName, $note)`
- **Validatie**: Gebruikt `validateRequired` om te zorgen voor een geldige naam.

---

# 5. Gegevensbeheer (De Motor)

### `checkOwnership($id, $table, $userId)`
- **De Poortwachter**: Dit is de belangrijkste functie voor privacy. 
- **Logica**: `SELECT count(*) FROM $table WHERE id = :id AND user_id = :user_id`.
- **Resultaat**: Als dit 0 teruggeeft, mag de gebruiker het item niet zien of bewerken. Dit voorkomt dat een gebruiker door het veranderen van een ID in de URL andermans data kan inzien.

### `deleteItem($id, $table, $userId)`
- **Techniek**: Voert een **Soft Delete** uit. 
- **Query**: `UPDATE $table SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id`.
- **Waarom?**: Het is een professionele standaard om data nooit echt te verwijderen voor het geval van een foutieve actie of voor audit-doeleinden.

---

# 6. Uitgebreide Functie-Lijst (Index)

Hieronder volgt een kort overzicht van de overige utiliteitsfuncties:
- `getEvents()`: Haalt toernooien en streams op.
- `addEvent()`: Voegt externe links en herinneringen toe (Bugfix #1002).
- `updateProfile()`: Staat toe om email en gebruikersnaam te wijzigen met uniekheidscheck.
- `isLoggedIn()`: Simpele boolean check voor toegangsbeheer op pagina-niveau.

---

# Conclusie

De `functions.php` van de GamePlan Scheduler bevat meer dan **35 functies** die naadloos samenwerken. Door het gebruik van gecentraliseerde logica is de applicatie robuust, veilig en klaar voor elke inspectie door een examencommissie.

---
**DOCUMENT STATUS**: GEVERIFIEERD VOOR EXAMEN
*Harsha Kanaparthi*
