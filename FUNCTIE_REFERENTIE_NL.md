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

### `validateRequired($value, $fieldName, $maxLength)`
- **Doel**: **Bugfix #1001**. Zorgt dat verplichte velden echt gevuld zijn.
- **Logica**: Gebruikt `trim()` en regex `/^\s*$/` om velden met alleen spaties te blokkeren.

### `validateDate($date)`
- **Doel**: **Bugfix #1004**. Strikte datum-validatie.
- **Werking**: Checkt of een datum logisch bestaat (geen 32 januari) en of deze in de toekomst ligt.

### `validateEmail($email)`
- **Techniek**: PHP native `filter_var($email, FILTER_VALIDATE_EMAIL)`.

---

# 2. Authenticatie & Sessie Management

### `registerUser($username, $email, $password)`
- **Actie**: Checkt eerst op dubbele emails. Hasht daarna het wachtwoord met `PASSWORD_BCRYPT`.
- **Veiligheid**: Gebruikt Prepared Statements voor de INSERT actie.

### `loginUser($email, $password)`
- **Proces**: 
    1. Haalt de `password_hash` op uit de DB.
    2. Verifieert de invoer via `password_verify()`.
    3. Start de sessie en roept `session_regenerate_id(true)` aan tegen hijacking.

### `checkSessionTimeout()`
- **Logica**: Checkt of er meer dan 1800 seconden (30 min) zijn verstreken sinds de laatste activiteit.
- **Actie**: Vernietigt de sessie en stuurt de gebruiker naar de loginpagina bij timeout.

---

# 3. Game & Agenda Logica (CRUD)

### `getSchedules($userId, $sortOrder)`
- **Techniek**: SQL `JOIN` tussen `Schedules` en `Games`. 
- **Doel**: Toont de namen van spellen in plaats van alleen ID's.

### `addSchedule($data)`
- **Stroom**: Roept eerst `getOrCreateGameId()` aan. Slaat daarna de afspraak op.
- **Validatie**: Voert alle 5 server-side checks uit voor opslag.

### `getOrCreateGameId($gameTitle)`
- **Slimme Logica**: Checkt of een spel al bestaat in de database (`Games` tabel).
- **Resultaat**: Indien ja -> haal ID op. Indien nee -> maak nieuw spel aan en haal NIEUW ID op.

### `editSchedule($data, $id)`
- **Beveiliging**: Roept ALTIJD `checkOwnership()` aan voordat de query wordt uitgevoerd.

---

# 4. Sociale Functies (Friends)

### `getFriends($userId)`
- **SQL**: `SELECT * FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL`.
- **Beveiliging**: Voorkomt dat je vriendenlijsten van anderen kunt inzien door te filteren op Sessie ID.

### `addFriend($userId, $friendName)`
- **Validatie**: `validateRequired` controleert op lege namen.

---

# 5. Gegevensbescherming (Soft Delete)

### `deleteItem($id, $table)`
- **Logica**: Voert een **Soft Delete** uit. 
- **Query**: `UPDATE $table SET deleted_at = CURRENT_TIMESTAMP WHERE id = :id`.
- **Impact**: De data blijft behouden voor de administrator, maar is onzichtbaar voor de frontend. Dit verhoogt de data-integriteit.

---

# 6. Administratieve Functies

### `checkOwnership($id, $table, $userId)`
- **CRUCIAAL**: Dit is de poortwachter tegen ID-manipulatie. Checkt of een record daadwerkelijk 'eigendom' is van de gebruiker die de actie probeert uit te voeren.

---

# Conclusie

Dit document toont aan dat de backend van de GamePlan Scheduler niet alleen functioneel is, maar ook is gebouwd volgens professionele normen van **Separation of Concerns** en **Secure Coding**.

---
**DOCUMENT STATUS**: Geverifieerd voor Examenportefeuille
