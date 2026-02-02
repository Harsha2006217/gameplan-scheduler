# UITLEG edit_schedule.php (Regel voor Regel)
## GamePlan Scheduler - Planning Bewerken

**Bestand**: `edit_schedule.php`
**Doel**: Een gemaakte afspraak verzetten (andere datum/tijd) of details wijzigen.

---

### Regel 30: Planning Ophalen
```php
$schedule = getSchedule(getUserId(), $scheduleId);
```
**Uitleg**:
*   Haalt de specifieke afspraak op uit de database.
*   Checkt of die afspraak wel van jou is.

### Regel 48: Datum Validatie (Opnieuw)
```php
$error = validateDate($date);
if (!$error) {
    $error = editSchedule($scheduleId, getUserId(), ...);
}
```
**Uitleg**:
*   Zelfs bij het *bewerken* moet de datum in de toekomst liggen.
*   Stel je verplaatst een afspraak naar gisteren -> De code zal dit weigeren (`validateDate`).

### Regel 85: Dropdown Selectie (Spel)
```php
<option value="<?php echo $game['game_id']; ?>"
    <?php if ($schedule['game_id'] == $game['game_id']) echo 'selected'; ?>>
    <?php echo safeEcho($game['titel']); ?>
</option>
```
**Uitleg**:
*   Dit ziet er ingewikkeld uit, maar het is logisch.
*   We maken een lijst van alle spellen.
*   We controleren: "Is DIT spel hetzelfde als het spel in de afspraak?"
*   Ja? Voeg `selected` toe.
*   Hierdoor staat het juiste spel al geselecteerd als je de pagina opent.

### Regel 111: Datum Vullen
```html
<input type="date" value="<?php echo $schedule['date']; ?>" ...>
```
**Uitleg**:
*   Haalt de datum (`2026-05-20`) uit de database en zet die in het datum-prikker veld.

---
**Samenvatting**: Stelt de gebruiker in staat om foutjes in de agenda te corrigeren of plannen te wijzigen.
