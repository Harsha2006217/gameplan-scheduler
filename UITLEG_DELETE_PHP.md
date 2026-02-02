# UITLEG delete.php (Regel voor Regel)
## GamePlan Scheduler - Verwijder Logica

**Bestand**: `delete.php`
**Doel**: Het veilig verwijderen van data (Vrienden, Planning, Games, Events).

---

### Regel 22-26: Context Laden
```php
require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) { header("Location: login.php"); ... }
```
**Uitleg**: Zoals altijd:
1.  Laad de functies.
2.  Check of de sessie verlopen is.
3.  Check of de gebruiker ingelogd is. **Niet ingelogd = Niks verwijderen**.

### Regel 28: Parameters Ophalen (GET)
```php
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
```
**Uitleg**:
*   De link ziet er zo uit: `delete.php?type=friend&id=5`.
*   `$type`: Wat willen we verwijderen? ('friend', 'schedule', etc.).
*   `$id`: Welk nummer? (Nummer 5).

### Regel 31: Het Grote Schakelblok (Switch)
```php
switch ($type) {
    case 'friend': ... break;
    case 'schedule': ... break;
    // ...
}
```
**Uitleg**:
*   `switch`: Dit is een "verkeersregelaar".
*   Als `$type` gelijk is aan 'friend', ga naar regel 32.
*   Als `$type` gelijk is aan 'schedule', ga naar regel 36.

### Regel 32-35: Vriend Verwijderen
```php
case 'friend':
    $error = deleteFriend(getUserId(), $id);
    $redirect = 'index.php';
    break;
```
**Uitleg**:
*   `deleteFriend`: Roept de functie aan in `functions.php`. Geef mijn ID mee én het ID van de vriend die weg moet.
*   `$redirect`: Waar moeten we naartoe na het verwijderen? Terug naar het dashboard (`index.php`).

### Regel 36-39: Planning Verwijderen
```php
case 'schedule':
    $error = deleteSchedule(getUserId(), $id);
    $redirect = 'index.php';
    break;
```
**Uitleg**: Zelfde logica, maar nu roepen we `deleteSchedule` aan.

### Regel 52-55: Onbekend Type (Default)
```php
default:
    $error = "Invalid delete type. / Ongeldig verwijder type.";
    $redirect = 'index.php';
```
**Uitleg**:
*   **Veiligheid**: Wat als een hacker typt: `delete.php?type=HACK&id=1`?
*   Dan komt hij in `default` (standaard).
*   We geven een foutmelding en verwijderen NIETS.

### Regel 58-64: Afhandeling & Redirect
```php
if ($error) {
    setMessage('error', $error);
} else {
    setMessage('success', ucfirst($type) . ' deleted successfully! ...');
}
header("Location: " . $redirect);
```
**Uitleg**:
*   Als `$error` gevuld is (er ging iets mis, bijv. je probeerde een vriend van iemand anders te verwijderen), tonen we een RODE melding.
*   Als het gelukt is (`else`), tonen we een GROENE melding.
*   `ucfirst($type)`: Maakt van 'friend' -> 'Friend' (Hoofdletter).
*   `header`: Stuurt de gebruiker terug naar de juiste pagina.

---
**Samenvatting**: `delete.php` is een universele prullenbak. Je gooit er een `type` en een `id` in, en het script zorgt dat het veilig wordt opgeruimd.
