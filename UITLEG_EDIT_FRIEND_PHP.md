# UITLEG edit_friend.php (Regel voor Regel)
## GamePlan Scheduler - Vriend Bewerken

**Bestand**: `edit_friend.php`
**Doel**: De status of notities van een vriend aanpassen.

---

### Regel 30: Vriend Ophalen
```php
$friend = getFriend(getUserId(), $friendId);
```
**Uitleg**:
*   We halen de vriend op die bij JOU hoort.
*   Als je probeert `getFriend(1, 999)` te doen (vriend van iemand anders), krijg je niks terug. Dit is de **Autorisatie Check**.

### Regel 81: Status Dropdown (`<select>`)
```html
<select name="status" class="form-select">
    <option value="Offline" <?php if ($status == 'Offline') echo 'selected'; ?>>Offline</option>
    <option value="Online" <?php if ($status == 'Online') echo 'selected'; ?>>Online</option>
    <option value="In Game" <?php if ($status == 'In Game') echo 'selected'; ?>>In Game</option>
</select>
```
**Uitleg**:
*   Dit is een slim stukje code.
*   We maken een keuzelijst.
*   Maar we willen dat de *huidige* status alvast geselecteerd is.
*   `if ($status == 'Online') echo 'selected'`: Als de vriend nu Online is, voegt PHP het woordje `selected` toe aan die optie. De browser toont die optie dan als standaard.

### Regel 50: Update
```php
$error = updateFriend(getUserId(), $friendId, $name, $note, $status);
```
**Uitleg**:
*   Alle nieuwe data (Naam, Notitie, Status) wordt naar de database gestuurd.
*   De status 'In Game' zorgt er bijvoorbeeld voor dat de vriend op het dashboard een groene badge krijgt.

---
**Samenvatting**: Hiermee beheer je je contactenlijst en kun je aangeven of vrienden beschikbaar zijn om te gamen.
