# UITLEG edit_event.php (Regel voor Regel)
## GamePlan Scheduler - Evenement Bewerken

**Bestand**: `edit_event.php`
**Doel**: Een bestaand evenement aanpassen.

---

### Regel 28: Welk Evenement? (ID)
```php
$eventId = $_GET['id'] ?? 0;
```
**Uitleg**:
*   De pagina zoekt in de adresbalk naar `?id=5`.
*   Zonder ID weten we niet wat we moeten aanpassen.

### Regel 30: Data Ophalen & Checken
```php
$event = getEvent($eventId);
```
**Uitleg**:
*   We halen de evenement-gegevens uit de database.
*   **Beveiliging in `functions.php`**: De functie `getEvent` checkt automatisch of JIJ de eigenaar bent.
*   Als het evenement niet bestaat of niet van jou is, krijgen we niks terug.

### Regel 32-35: Reddingsboei
```php
if (!$event) {
    header("Location: index.php");
    exit;
}
```
**Uitleg**:
*   Als we geen data vonden (bijv. hacker probeerde ID 9999 te gokken), sturen we ze direct weg.

### Regel 40-42: Formulier Invullen
```php
// Als formulier nog NIET verstuurd is (eerste keer laden):
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $title = $event['title'];
    $date = $event['date'];
    ...
}
```
**Uitleg**:
*   De gebruiker komt op de pagina. We willen natuurlijk dat de oude gegevens alvast in de vakjes staan.
*   We kopiëren de database-gegevens naar de variabelen `$title`, `$date`, etc.
*   In de HTML (regel 88) gebruiken we dit: `value="<?php echo $title; ?>"` om het vakje voor te vullen.

### Regel 50-52: Update Uitvoeren
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = editEvent($eventId, getUserId(), ...);
}
```
**Uitleg**:
*   Als de gebruiker op "Opslaan" klikt, sturen we de *nieuwe* gegevens naar de database via `editEvent`.
*   We sturen `getUserId()` mee als extra veiligheidscheck.

---
**Samenvatting**: Bijna hetzelfde als `add_event.php`, maar met één extra stap: Eerst de oude gegevens ophalen en tonen.
