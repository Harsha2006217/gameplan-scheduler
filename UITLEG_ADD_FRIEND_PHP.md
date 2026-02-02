# UITLEG add_friend.php (Regel voor Regel)
## GamePlan Scheduler - Vrienden Toevoegen

**Bestand**: `add_friend.php`
**Doel**: Vleindenlijst uitbreiden (Niet gekoppeld aan echte gebruikersaccounts, maar als adresboek).

---

### Regel 33: Data Ophalen
```php
    $friendName = $_POST['username'] ?? '';
    $note = $_POST['note'] ?? '';
```
**Uitleg**:
*   Je vult een naam in (bijv. "Dave").
*   Je vult een notitie in (bijv. "Speelt alleen in weekend").
*   *Let op*: In dit systeem zijn vrienden "virtueel". Dave hoeft geen account te hebben. Jij maakt Dave aan in jouw lijstje.

### Regel 37: De Actie
```php
    $error = addFriend(getUserId(), $friendName, $note);
```
**Uitleg**:
*   `addFriend` (`functions.php`) voert de actie uit.
*   Er wordt gecontroleerd of je niet al een vriend "Dave" hebt. (Dubbele vrienden is verwarrend).

### Regel 41: Status Systeem
*   Wanneer je een vriend toevoegt, krijgt hij standaard de status 'Offline'.
*   Op het dashboard kun je later de status aanpassen naar 'Online' of 'In Game'.

### Regel 67: Formulier
```html
<label for="username">👤 Friend's Name / Naam van Vriend *</label>
<input type="text" ... maxlength="50">
```
**Uitleg**:
*   Simpel invulveld.
*   `*` betekent verplicht veld.

### Regel 75: Notitie Veld (Textarea)
```html
<textarea ... rows="3"></textarea>
```
**Uitleg**:
*   `textarea`: Een groter vak waar je meerdere regels tekst in kwijt kunt.
*   Handig om te onthouden welke games deze vriend leuk vindt.

---
**Samenvatting**: Een simpele pagina om je persoonlijke adresboekje van gamers uit te breiden.
