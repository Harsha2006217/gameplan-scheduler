# UITLEG footer.php (Regel voor Regel)
## GamePlan Scheduler - Voettekst

**Bestand**: `footer.php`
**Doel**: Copyright info en scripts laden aan de onderkant van elke pagina.

---

### Regel 17: Footer Tag
```html
<footer class="text-center text-lg-start bg-dark text-muted fixed-bottom">
```
**Uitleg**:
*   `fixed-bottom`: Dit is een belangrijke Bootstrap class.
*   Het zorgt dat de balk ALTIJD vastgeplakt zit aan de onderkant van het scherm, zelfs als de pagina kort is.
*   `text-muted`: Maakt de tekst grijs (niet te opvallend).

### Regel 22: Copyright Tekst (Dynamisch Jaartal)
```php
© <?php echo date("Y"); ?> GamePlan Scheduler
```
**Uitleg**:
*   We typen niet "2026".
*   We gebruiken PHP `date("Y")`.
*   Waarom? Als het volgend jaar is, verandert het jaartal automatisch. Je hoeft de code nooit meer aan te passen.

### Regel 28: Links
```html
<a href="privacy.php" class="text-reset fw-bold">Privacy Policy</a>
```
**Uitleg**:
*   Wettelijk verplichte links naar Privacy Beleid en Contact.
*   `text-reset`: Neemt de kleur van de ouder over (grijs/muted).

### Regel 48: Einde van de HTML
```html
</body>
</html>
```
**Uitleg**:
*   **Wacht even!** Waarom zit dit in de footer?
*   In `header.php` hebben we `<html>` en `<body>` geopend.
*   Hier in `footer.php` sluiten we ze netjes af.
*   Elke pagina begint met `include 'header.php'` en eindigt met `include 'footer.php'`. Zo is de cirkel rond.

---
**Samenvatting**: De afsluiter van elke pagina. Zorgt voor juridische info en laadt, indien nodig, JavaScript bestanden.
