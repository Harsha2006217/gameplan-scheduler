# UITLEG functions.php (Regel voor Regel)
## GamePlan Scheduler - De Motor van de Website

**Bestand**: `functions.php`
**Doel**: Alle logica, beveiliging en functies op één plek beheren.

---

### Regel 17-19: Output Buffering
```php
ob_start();
```
**Uitleg**: Dit commando zegt tegen PHP: "Wacht even met alles naar het scherm sturen".
**Waarom?**: Soms moeten we de gebruiker doorsturen naar een andere pagina (`header("Location: ...")`). Dat kan alleen als er nog GEEN HTML naar het scherm is gestuurd. `ob_start()` lost dat probleem op.

### Regel 22: Database Koppelen
```php
require_once 'db.php';
```
**Uitleg**: Haal de code uit `db.php` op. Nu kan dit bestand de functie `getDBConnection()` gebruiken. `require_once` voorkomt dat we het per ongeluk 2x laden (wat een fout zou geven).

### Regel 32-37: Sessie Starten
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}
```
**Uitleg**:
*   **Sessie**: Een manier om de gebruiker te onthouden terwijl hij van pagina naar pagina klikt.
*   `session_start()`: Start het geheugen.
*   `session_regenerate_id(true)`: **Beveiliging**. Geeft de gebruiker elke keer een nieuw digitaal paspoortnummer. Als een hacker het oude nummer steelt, werkt het niet meer.

### Regel 50-55: XSS Beveiliging (`safeEcho`)
```php
function safeEcho($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
```
**Uitleg**: De belangrijkste functie voor beveiliging!
*   **Probleem**: Als een gebruiker `<script>hack()</script>` invult als naam.
*   **Oplossing**: `htmlspecialchars` verandert `<` in `&lt;`. De browser ziet het dan als tekst, niet als code.
*   **Gebruik**: Overal waar we tekst tonen, gebruiken we `safeEcho($tekst)`.

### Regel 68-86: Verplichte Velden Controleren (`validateRequired`)
```php
function validateRequired($value, ..."
   $value = trim($value);
   if (empty($value) || preg_match('/^\s*$/', $value)) ...
```
**Uitleg**:
*   `trim()`: Knipt spaties weg aan begin en eind.
*   **Bugfix #1001**: De regex `/^\s*$/` controleert: "Bestaat deze tekst alleen maar uit spaties?". Zo ja, dan is het ongeldig.
*   **Resultaat**: Als er een fout is, geeft hij een tekst terug. Zo niet, geeft hij `null` (alles OK).

### Regel 97-117: Datum Validatie (`validateDate`)
```php
$dateObj = DateTime::createFromFormat('Y-m-d', $date);
```
**Uitleg**:
*   **Bugfix #1004**: We gebruiken de slimme `DateTime` klasse van PHP.
*   We checken of de datum *echt* bestaat (geen 30 februari).
*   We checken of de datum in de toekomst ligt (`$dateObj < $today`).

### Regel 239-248: Sessie Timeout (`checkSessionTimeout`)
```php
if (isLoggedIn() && ... (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php?msg=session_timeout");
}
```
**Uitleg**:
*   Elke keer als de gebruiker iets doet, slaan we de tijd op (`last_activity`).
*   Als het verschil tussen NU en TOEN groter is dan 1800 seconden (30 minuten)...
*   ... Loggen we de gebruiker uit. Veiligheid!

### Regel 254: Registreren (`registerUser`)
```php
$hash = password_hash($password, PASSWORD_BCRYPT);
```
**Uitleg**:
*   We slaan NOOIT het wachtwoord `Geheim123` op.
*   `password_hash` maakt er een brij van: `$2y$10$f8s9d...`.
*   Dit kan niet terugberekend worden naar het origineel.

### Regel 292: Inloggen (`loginUser`)
```php
if (!$user || !password_verify($password, $user['password_hash']))
```
**Uitleg**:
*   We halen de hash uit de database.
*   `password_verify`: Vergelijkt het ingevoerde wachtwoord met de hash.
*   Klopt het? Sessie variabelen (`user_id`, `username`) worden gevuld.

### Regel 338: Spel ID Ophalen of Maken (`getOrCreateGameId`)
**Doel**: Voorkomen van dubbele spellen.
*   Code zoekt eerst: "Bestaat 'Fortnite' al?"
*   Ja -> Geef ID terug.
*   Nee -> Maak nieuw spel aan en geef NIEUW ID terug.

### Regel 490-561: CRUD Functies (Create, Read, Update, Delete)
Alle functies zoals `editSchedule`, `deleteFriend` werken volgens hetzelfde patroon:
1.  **Eigen** eigendom checken (`user_id = :user_id`). Je kunt alleen je eigen spullen wijzigen.
2.  **Validatie**: Is de datum goed? Is de titel niet leeg?
3.  **Prepared Statement**: De SQL query wordt voorbereid met `:plaatsvervangers`.
4.  **Uitvoeren**: `$stmt->execute([...])` vult de data veilig in.

---
**Samenvatting**:
Dit bestand is het "Zwitsers Zakmes" van het project. Het regelt alles wat niet zichtbaar is, maar wel essentieel is voor de werking en veiligheid.
