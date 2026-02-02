# UITLEG contact.php (Regel voor Regel)
## GamePlan Scheduler - Contact Pagina

**Bestand**: `contact.php`
**Doel**: Gebruikers een manier geven om hulp te vragen.

---

### Regel 20-21: Sessie Check
```php
require_once 'functions.php';
checkSessionTimeout();
```
**Uitleg**:
*   Ook al is dit een simpele "tekst" pagina, we laden toch `functions.php`.
*   **Waarom?**: Zodat de header (navigatiebalk) weet of je ingelogd bent of niet ("Login" vs "Logout" knop).

### Regel 35: Header Insluiten
```php
<?php include 'header.php'; ?>
```
**Uitleg**:
*   We typen de navigatiebalk niet opnieuw.
*   We "plakken" de code van `header.php` hier in.
*   Als we de header ooit willen veranderen (bijv. nieuw logo), hoeven we dat maar op 1 plek te doen.

### Regel 41: Contact Informatie
```html
<p>Heb je vragen of problemen? ...</p>
```
**Uitleg**:
*   Simpele HTML tekst.
*   Hier staat het emailadres waar gebruikers naartoe kunnen mailen.

### Regel 45: Mail Link (`mailto`)
```html
<a href="mailto:harsha.kanaparthi20062@gmail.com" class="text-info">
```
**Uitleg**:
*   `mailto:`: Dit is een speciaal soort link.
*   Als je hierop klikt, opent je computer direct je mailprogramma (zoals Outlook of Gmail) met het adres al ingevuld.
*   `class="text-info"`: Maakt de link lichtblauw (Bootstrap kleur), zodat hij goed leesbaar is op de donkere achtergrond.

### Regel 50-53: Terug Knop
```html
<a href="index.php" class="btn btn-primary mt-3">
    ↩️ Terug naar Dashboard
</a>
```
**Uitleg**:
*   Altijd belangrijk voor UX (User Experience): Zorg dat de gebruiker makkelijk terug kan naar waar hij vandaan kwam.

---
**Samenvatting**: Een statische pagina die dient als wegwijzer voor hulp. Simpel, maar essentieel voor een professionele uitstraling.
