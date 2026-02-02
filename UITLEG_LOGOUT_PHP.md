# UITLEG logout.php (Regel voor Regel)
## GamePlan Scheduler - Uitloggen

**Bestand**: `logout.php`
**Doel**: De sessie veilig beëindigen zodat niemand anders op jouw account kan.

---

### Regel 19-21: Sessie Hervatten
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```
**Uitleg**:
*   Je kunt een sessie pas weggooien als je hem "vast" hebt.
*   We starten dus de sessie (of hervatten hem) om toegang te krijgen tot de gegevens.

### Regel 25: Variabelen Leegmaken
```php
$_SESSION = [];
```
**Uitleg**:
*   `$_SESSION` is een array (lijstje) met jouw gegevens (Naam, ID).
*   `[]`: We maken dit lijstje helemaal leeg. Nu weet de server niet meer wie je bent.

### Regel 29-35: Cookie Vernietigen
```php
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, ...);
}
```
**Uitleg**:
*   De server herkent jou aan een "Cookie" (een kruimeltje) op je computer.
*   We overschrijven deze cookie met een lege waarde en zetten de houdbaarheidsdatum in het verleden (`time() - 42000`).
*   De browser gooit de cookie dan direct weg.

### Regel 39: Sessie Vernietigen
```php
session_destroy();
```
**Uitleg**:
*   Dit is de genadeklap. De server gooit het dossier van de sessie in de papierversnipperaar.

### Regel 43: Redirect
```php
header("Location: login.php?msg=logged_out");
```
**Uitleg**:
*   We sturen de gebruiker terug naar de login pagina.
*   `?msg=logged_out`: We geven een seintje mee zodat de login pagina kan zeggen: "Je bent succesvol uitgelogd".

---
**Samenvatting**: Dit script poetst alles schoon. Geheugen op de server, cookies op de PC. Veiligheid tot het einde.
