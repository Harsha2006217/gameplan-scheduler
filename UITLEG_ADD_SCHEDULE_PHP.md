# UITLEG add_schedule.php (Regel voor Regel)
## GamePlan Scheduler - Planning Maken

**Bestand**: `add_schedule.php`
**Doel**: Een toekomstige gamesessie in de agenda zetten.

---

### Regel 29-37: Voorbereiding (Dropdowns Vullen)
```php
$friends = getFriends(getUserId());
$games = getFavoriteGames(getUserId());
```
**Uitleg**:
*   Als je een planning maakt, wil je kiezen *Met Wie* en *Welk Spel*.
*   We halen dus eerst al jouw vrienden en favoriete spellen op uit de database.
*   Deze stoppen we later in een keuzelijst (`<select>`).

### Regel 44: Validatie (Datum)
```php
    $error = validateDate($date);
```
**Uitleg**:
*   Voordat we iets opslaan, checken we (`functions.php` -> `validateDate`) of de datum in de toekomst ligt.
*   Niemand kan terug in de tijd plannen.

### Regel 54: Plannen
```php
    $error = addSchedule($userId, $gameId, $friendId, $date, $time, $notes);
```
**Uitleg**:
*   We koppelen 3 ID's aan elkaar:
    1.  **Gebruiker ID** (Jij).
    2.  **Game ID** (Bijv. Fortnite, ID 4).
    3.  **Vriend ID** (Bijv. Dave, ID 9).
*   En een tijdstip.
*   Dit slaat een rij op in de `Schedules` tabel.

### Regel 82: Keuzelijst Spellen (`<select>`)
```html
<select name="game_id" class="form-select" required>
    <option value="">Choose a game...</option>
    <?php foreach ($games as $game): ?>
        <option value="<?php echo $game['game_id']; ?>">
            <?php echo safeEcho($game['titel']); ?>
        </option>
    <?php endforeach; ?>
</select>
```
**Uitleg**:
*   Dit is een "Loop in HTML".
*   Voor elk spel dat we op regel 37 hebben gevonden, maken we een optie in het menu.
*   **Value**: De computer onthoudt het Nummer (ID).
*   **Tekst**: De gebruiker ziet de Naam (Titel).

### Regel 97: Keuzelijst Vrienden
```html
<select name="friend_id" ...>
```
**Uitleg**: Zelfde principe als bij spellen. Je kiest uit jouw eigen vriendenlijst.

---
**Samenvatting**: Hier knoopt de gebruiker alles aan elkaar: Tijd, Vriend en Spel worden één afspraak in de agenda.
