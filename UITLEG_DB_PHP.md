# UITLEG db.php (Regel voor Regel)
## GamePlan Scheduler - Database Verbinding

**Bestand**: `db.php`
**Doel**: Het maken van een veilige verbinding met de database.

---

### Regel 1-13: Introductie DocBlock
```php
/**
 * ============================================================================
 * DB.PHP - DATABASE CONNECTION / DATABASE VERBINDING
 * ...
 */
```
**Uitleg**: Dit is commentaar. De computer negeert dit. Het vertelt ons (de mensen) wat het bestand doet. Hier staat de auteur, datum en het doel (Singleton Pattern).

### Regel 15-20: Constanten Definiëren (Instellingen)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gameplan_scheduler');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```
**Uitleg**:
*   `define(...)`: Dit maakt een "Constante". Een waarde die nooit mag veranderen tijdens het script.
*   `DB_HOST`: Waar staat de database? (localhost = deze computer).
*   `DB_NAME`: De naam van de kluis (database).
*   `DB_USER`: De gebruikersnaam (standaard XAMPP is 'root').
*   `DB_PASS`: Het wachtwoord (standaard XAMPP is leeg).
*   `DB_CHARSET`: De taalset. `utf8mb4` ondersteunt alle karakters, inclusief Emoji's 🎮.

### Regel 22-54: De Functie `getDBConnection()`
```php
function getDBConnection() {
    static $pdo = null;
```
**Uitleg**:
*   `function`: Hier start een blok code dat we vaker kunnen hergebruiken.
*   `static $pdo = null`: Dit is het **Singleton Pattern**.
    *   *Normaal*: Elke keer als je de functie roept, maak je een nieuwe verbinding. (Slecht voor prestaties).
    *   *Static*: De eerste keer is `$pdo` leeg (`null`). We maken verbinding. De tweede keer onthoudt hij de verbinding! Hij maakt dus maar **1 keer** verbinding per paginalading.

### Regel 29-39: De Verbinding Maken (Try-Catch)
```php
if ($pdo === null) {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
```
**Uitleg**:
*   `if ($pdo === null)`: Alleen als we nog geen verbinding hebben...
*   `try { ... }`: "Probeer dit". Dit is foutafhandeling. Als er iets misgaat (bijv. server plat), crasht de site niet, maar springt hij naar `catch`.
*   `$dsn`: Data Source Name. Een string die vertelt waar we naartoe moeten verbinden. (MySQL, op localhost, database naam, tekenset).

### Regel 33-38: PDO Opties (Veiligheid)
```php
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
```
**Uitleg**:
1.  **ERRMODE_EXCEPTION**: Als er een SQL fout is, geef een harde foutmelding (Exception) in plaats van stil te falen.
2.  **FETCH_ASSOC**: Haal data op als een nette lijst met namen (`['naam' => 'Harsha']`) in plaats van nummers (`[0] => 'Harsha'`).
3.  **EMULATE_PREPARES => false**: **Heel Belangrijk voor Beveiliging!** Dit dwingt de database om *echte* Prepared Statements te gebruiken. Dit stopt SQL Injection hackers.

### Regel 40: De Connectie
```php
$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
```
**Uitleg**: Hier gebeurt de magie. `new PDO(...)` klopt aan bij MySQL. Als de inloggegevens kloppen, zit de verbinding nu in `$pdo`.

### Regel 41-47: Foutafhandeling (Catch)
```php
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed. Please try again later. / Database verbinding mislukt.");
}
```
**Uitleg**:
*   `catch`: Als de verbinding in de `try` mislukte...
*   `error_log`: Schrijf de ECHTE fout (bijv. "Wachtwoord verkeerd") in een verborgen logbestand op de server. Laat dit NOOIT aan de gebruiker zien (hackers houden van foutmeldingen).
*   `die(...)`: Stop het script en toon een veilige, vriendelijke boodschap aan de bezoeker.

### Regel 50-53: Return
```php
    }
    return $pdo;
}
```
**Uitleg**: Geef de verbinding terug aan wie erom vroeg. Nu kan de rest van de website de database gebruiken.
