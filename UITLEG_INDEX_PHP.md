# UITLEG index.php (Regel voor Regel)
## GamePlan Scheduler - Het Dashboard

**Bestand**: `index.php`
**Doel**: Het centrale punt waar vrienden, spellen en planning samenkomen.

---

### Regel 28-34: Beveiligings Check
```php
checkSessionTimeout();
if (!isLoggedIn()) { header("Location: login.php"); exit; }
```
**Uitleg**:
*   **Timeout**: Is de gebruiker al 30 minuten stil? Log hem uit (`functions.php`).
*   **Login Check**: Is de gebruiker niet ingelogd? Stuur hem per direct terug naar de login pagina. Dit beschermt privégegevens.

### Regel 41: Activiteit Bijwerken
```php
updateLastActivity(getDBConnection(), $userId);
```
**Uitleg**: De gebruiker heeft de pagina geladen! We resetten de timer van 30 minuten.

### Regel 45: Sorteer Parameters
```php
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
```
**Uitleg**:
*   De gebruiker kan klikken op knopjes als "Draai Datum Om".
*   Die knoppen zetten `?sort_schedules=date DESC` in de URL.
*   Deze regel leest die URL. Als er niks staat, gebruiken we standaard `date ASC` (Oudste eerst).

### Regel 48-53: Data Ophalen (Alles!)
```php
$friends = getFriends($userId);
$favorites = getFavoriteGames($userId);
$schedules = getSchedules($userId, $sortSchedules);
...
```
**Uitleg**:
*   Hier roepen we 5 verschillende functies aan uit `functions.php`.
*   We halen in één keer ALLE info op die we nodig hebben voor dit scherm.
*   Dit is efficiënt: We doen het rekenwerk op de server, niet in de browser.

### Regel 83-113: Vrienden Tabel (Loop)
```php
<?php foreach ($friends as $friend): ?>
    <tr>
        <td><?php echo safeEcho($friend['username']); ?></td>
```
**Uitleg**:
*   `foreach`: Voor elke vriend in de lijst... maak een nieuwe rij (`<tr>`) in de tabel.
*   `safeEcho`: Toon de naam veilig.
*   **Status Badge**: We kijken naar `$friend['status']`. Is hij 'Online'? Dan krijgt de badge de kleur GROEN (`bg-success`). Anders GRIJS (`bg-secondary`).

### Regel 144: Delete Knop (Met Bevestiging)
```html
<a href="delete.php?type=favorite&id=..." onclick="return confirm('...');">
```
**Uitleg**:
*   Deze link stuurt de gebruiker naar `delete.php`.
*   **Cruciaal**: `onclick="return confirm(...)"`. Dit toont eerst een pop-up: "Weet je het zeker?".
*   Als de gebruiker "Annuleren" klikt, stopt de link en gebeurt er niets.

### Regel 158: Sorteer Knoppen
```html
<a href="?sort_schedules=date ASC">📆 Date ↑</a>
```
**Uitleg**:
*   Dit is geen link naar een andere pagina, maar naar *zichzelf* (`?sort...`).
*   Het herlaadt de pagina met nieuwe instructies voor regel 45.

### Regel 205: Evenementen (Met Externe Links)
```php
<?php if (!empty($event['external_link'])): ?>
    <a href="..." target="_blank">🔗 Open</a>
```
**Uitleg**:
*   We controleren eerst of er wel een link is (`!empty`).
*   Zo niet, tonen we de knop niet. Dat staat netter.
*   `target="_blank"`: Opent de link in een NIEUW tabblad. Je wilt niet dat mensen je app verlaten.

### Regel 255-288: De Kalender (Kaarten View)
```html
<div class="col-md-4 col-sm-6 mb-3">
```
**Uitleg**:
*   Hier gebruiken we het Bootstrap *Grid Systeem*.
*   `col-md-4`: Op een laptop passen er 3 kaarten naast elkaar (12 / 4 = 3).
*   `col-sm-6`: Op een tablet passen er 2 kaarten naast elkaar.
*   Op mobiel (automatisch) passen ze onder elkaar.
*   **Inhoud**: Dit toont `$calendarItems`, een gecombineerde, gesorteerde lijst van Planning én Evenementen (zie `functions.php` -> `getCalendarItems`).

### Regel 299-302: JavaScript Reminders
```javascript
const reminders = <?php echo json_encode($reminders); ?>;
reminders.forEach(reminder => { alert(...) });
```
**Uitleg**:
*   PHP praat hier tegen JavaScript!
*   `json_encode`: PHP arrays zijn onleesbaar voor JavaScript. JSON is de universele vertaaltaal.
*   Als er evenementen zijn die BINNENKORT beginnen (berekend in `functions.php`), krijgt JavaScript die lijst en toont direct een pop-up (`alert`).

---
**Samenvatting**: Het dashboard is de verkeerstoren. Het haalt data uit 4 verschillende database-tabellen en toont die georganiseerd aan de gebruiker. Het luistert naar sorteercommando's en waarschuwt bij naderende evenementen.
