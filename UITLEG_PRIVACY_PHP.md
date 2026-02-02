# UITLEG privacy.php (Regel voor Regel)
## GamePlan Scheduler - Privacy Policy

**Bestand**: `privacy.php`
**Doel**: Voldoen aan de AVG (Algemene Verordening Gegevensbescherming) wetgeving.

---

### Regel 42-49: Welke data verzamelen we?
```html
<ul>
    <li>Username - Your display name</li>
    <li>Email - For login only</li>
    ...
</ul>
```
**Uitleg**:
*   **Transparantie**: We liegen niet tegen de gebruiker.
*   We vertellen PRECIES wat we opslaan.
*   **Dataminimalisatie**: We vragen geen telefoonnummer of adres, want dat hebben we niet nodig voor een game-agenda. Dat is een belangrijk AVG-principe.

### Regel 51-57: Hoe beveiligen we het?
```html
<li>Passwords are encrypted with bcrypt</li>
<li>Prepared statements (SQL injection protection)</li>
```
**Uitleg**:
*   Hier leggen we uit dat we zorgvuldig omgaan met de sleutels.
*   We beloven dat we wachtwoorden niet kunnen lezen (omdat ze gehasht zijn).
*   We leggen uit dat de database op slot zit (Prepared Statements).
*   **Doel**: Vertrouwen winnen van de gebruiker (en de examinator!).

### Regel 59-65: Wat doen we NIET?
```html
<li>We never sell your data</li>
<li>No tracking cookies</li>
```
**Uitleg**:
*   Veel "gratis" apps verkopen je data. Wij beloven hier zwart-op-wit dat we dat niet doen.
*   **Geen Cookies**: We plaatsen geen irritante reclame-cookies.

### Regel 67-72: Jouw Rechten
```html
<li>You can view all your data</li>
<li>You can edit or delete</li>
```
**Uitleg**:
*   De wet zegt dat gebruikers "Baas over eigen Data" moeten zijn.
*   Hier leggen we uit dat ze ten alle tijden hun vrienden/planning mogen verwijderen (`delete.php`).

### Regel 79: AVG Disclaimer
```html
<small>This privacy policy complies with AVG/GDPR regulations.</small>
```
**Uitleg**:
*   Een expliciete vermelding dat we de wet kennen en respecteren.

---
**Samenvatting**: Dit bestand is juridisch noodzakelijk. Het is geen "code" die iets berekent, maar "beleid" dat verklaart hoe de code omgaat met mensen.
