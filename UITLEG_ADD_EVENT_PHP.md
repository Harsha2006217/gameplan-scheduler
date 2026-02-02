# UITLEG add_event.php (Regel voor Regel)
## GamePlan Scheduler - Evenementen Toevoegen

**Bestand**: `add_event.php`
**Doel**: Het aanmaken van speciale gaming evenementen (zoals toernooien of LAN-parties).

---

### Regel 1-28: Standaard Setup
```php
require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) { ... }
```
**Uitleg**:
*   De heilige drie-eenheid aan het begin van elk beveiligd bestand:
    1.  Laad gereedschap (`functions.php`).
    2.  Check eierwekker (sessie timeout).
    3.  Check toegang (login).

### Regel 33: Formulier Verzonden (POST)
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
```
**Uitleg**: We gaan pas werken als de gebruiker op "Toevoegen" heeft geklikt.

### Regel 34-37: Data Verzamelen
```php
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    // ...
```
**Uitleg**:
*   We halen de titel, datum, tijd, beschrijving en link op uit het formulier.
*   `?? ''`: Als een veld leeg is, voorkomen we een crash door een lege tekst te gebruiken.

### Regel 40: Het Echte Werk
```php
    $error = addEvent(getUserId(), $title, $description, $date, $time, $link);
```
**Uitleg**:
*   We roepen `addEvent` aan in `functions.php`.
*   **Let op**: We geven `getUserId()` mee. Het evenement wordt dus gekoppeld aan JOU. Jij bent de eigenaar.

### Regel 42-46: Succes of Falen
```php
    if (!$error) {
        setMessage('success', 'Event added! ...');
        header("Location: index.php");
        exit;
    }
```
**Uitleg**:
*   Gelukt? Groen bericht ("Succes!") en terug naar dashboard.
*   Mislukt? De foutmelding (`$error`) blijft bestaan en wordt zo dadelijk onderaan getoond.

### Regel 73: Formulier Validatie (Client-side)
```html
<form method="POST" onsubmit="return validateEventForm();">
```
**Uitleg**:
*   We gebruiken JavaScript (`validateEventForm` in `script.js`) om te checken of de datum wel in de toekomst ligt, *voordat* we de server lastig vallen.

### Regel 82-85: Datum Veld (Minimaal Vandaag)
```html
<input type="date" min="<?php echo date('Y-m-d'); ?>" ...>
```
**Uitleg**:
*   **Slimmigheidje**: `min="..."`.
*   We vragen PHP: "Welke dag is het vandaag?" (`date('Y-m-d')`).
*   Die datum zetten we als minimum in de HTML.
*   Resultaat: De gebruiker KAN in de kalender niet eens op gisteren klikken.

### Regel 97: Externe Link (Optioneel)
```html
<input type="url" ... placeholder="https://discord.gg/...">
```
**Uitleg**:
*   `type="url"`: De browser controleert of het echt een link is (begint met http:// etc).
*   Handig voor Discord invites of toernooi pagina's.

---
**Samenvatting**: Een pagina om evenementen aan te maken. Bevat slimme datum-begrenzing zodat je geen evenementen in het verleden kunt plannen.
