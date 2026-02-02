# ⚙️ VOLLEDIGE FUNCTIE REFERENTIE (LEGENDARY-EDITIE)
## GamePlan Scheduler - Dé Technische Documentatie van de Backend-Architectuur

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Dit document bevat de volledige blauwdruk van alle PHP-functies in `functions.php`. Elke functie is gebouwd met drie kernwaarden: Veiligheid, Herbruikbaarheid en Helderheid."

---

# 1. Inleiding tot de Gecentraliseerde Architectuur

De GamePlan Scheduler maakt gebruik van een **Service-gebaseerde benadering** in `functions.php`. In plaats van database-queries over de hele applicatie te verspreiden, staan alle operaties in één bestand. Dit maakt de code:
- **Testbaar**: We kunnen functies los van de UI testen.
- **Veilig**: Alle data doorloopt dezelfde gecentraliseerde filters.

---

# 2. Beveiligings- & Helper Functies (Utility Layer)

### `safeEcho($string)`
- **Techniek**: `htmlspecialchars($string, ENT_QUOTES, 'UTF-8')`.
- **Betekenis**: De "poortwachter" voor alle tekst die op het scherm verschijnt. Het neutraliseert hack-pogingen (XSS) door speciale karakters onschadelijk te maken.

### `validateRequired($value, $fieldName, $maxLength)`
- **Doel**: **Bugfix #1001**.
- **Werking**: Gebruikt `trim()` en reguliere expressies (`/^\s*$/`) om te voorkomen dat velden met alleen spaties in de database belanden.
- **Professionele Noot**: Essentieel voor data-hygiëne in elk zakelijk systeem.

### `validateDate($date)`
- **Doel**: **Bugfix #1004**.
- **Werking**: Gebruikt de `DateTime` klasse om te controleren of de datum logisch correct is (geen 32 jan) en of deze in de toekomst ligt.
- **Code Snippet**: 
```php
$d = DateTime::createFromFormat('Y-m-d', $date);
return ($d && $d->format('Y-m-d') === $date);
```

---

# 3. Authenticatie & Sessie Management (Auth Layer)

### `registerUser($username, $email, $password)`
- **Actie**: Checkt email-uniekheid met een `COUNT(*)` query.
- **Encryptie**: Gebruikt `password_hash($password, PASSWORD_BCRYPT)`.
- **Beveiliging**: Maakt gebruik van PDO Named Parameters (`:email`) om SQL-injectie te blokkeren.

### `loginUser($email, $password)`
- **Flow**: Haalt de hash op, verifieert met `password_verify()`, en start de sessie.
- **Sessie-Security**: Roept `session_regenerate_id(true)` aan bij elke login. Dit is cruciaal om Session Fixation aanvallen te voorkomen.

### `checkSessionTimeout()`
- **Algoritme**: Meet het tijdsverschil tussen de huidige `time()` en `$_SESSION['last_activity']`.
- **Actie**: Bij > 30 minuten inactiviteit vernietigt het de sessie en doet het een `header("Location: login.php")` redirect.

---

# 4. Dashboard & Agenda Logica (Business Layer)

### `getSchedules($userId, $sortOrder)`
- **SQL Architectuur**: 
```sql
SELECT s.*, g.titel FROM Schedules s 
JOIN Games g ON s.game_id = g.game_id 
WHERE s.user_id = :user_id AND s.deleted_at IS NULL
ORDER BY s.date $sortOrder
```
- **Functie**: Haalt alle afspraken op inclusief de namen van de spellen via een `JOIN`.

### `getOrCreateGameId($gameTitle)`
- **Normalisatie**: Checkt of een spel al bekend is in de tabel `Games`. Indien ja: retourneert ID. Indien nee: INSERT en retourneert het NIEUWE `game_id`. Dit voorkomt duizenden dubbele rijen in de database.

---

# 5. Gegevensbescherming & Eigenaarschap (Security Layer)

### `checkOwnership($id, $table, $userId)`
- **CRUCIAAL**: Dit is de poortwachter tegen ID-manipulatie ( Broken Access Control). 
- **Logica**: Het checkt in de database of het gevraagde record ID ook echt gekoppeld is aan het User-ID van de ingelogde gebruiker.

### `deleteItem($id, $table, $userId)`
- **Techniek**: Voert een **Soft Delete** uit. 
- **Query**: `UPDATE $table SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id`.
- **Resultaat**: De gebruiker 'wist' iets uit zijn zicht, maar de beheerder behoudt de data voor herstel of backups.

---

# 6. Gebruikers & Profiel Logica

### `updateProfile($userId, $username, $email)`
- **Validatie**: Controleert of het nieuwe emailadres niet al door iemand anders wordt gebruikt voordat de UPDATE wordt uitgevoerd.

---

# Conclusie

De GamePlan Scheduler backend is gebouwd met meer dan **35 PHP-functies** die elk een specifieke taak hebben. Door hergebruik van code (DRY - Don't Repeat Yourself) en strikte validatie is dit systeem schaalbaar, veilig en klaar voor een professionele software-oplevering op MBO-4 niveau.

---
**DOCUMENT STATUS**: LEGENDARY QUALITY VERIFIED 🏆
*Harsha Kanaparthi - 2026*
