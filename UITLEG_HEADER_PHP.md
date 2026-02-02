# UITLEG header.php (Regel voor Regel)
## GamePlan Scheduler - Navigatiebalk

**Bestand**: `header.php`
**Doel**: Het menu dat op ELKE pagina bovenaan staat.

---

### Regel 1-8: Hulpfuncties
```php
if (!function_exists('isActive')) {
    function isActive($page) { ... }
}
```
**Uitleg**:
*   Dit is een slim trucje voor de "Actieve Knop".
*   PHP kijkt naar de naam van de huidige pagina (`basename`).
*   Als we op `index.php` zijn, krijgt de knop "Dashboard" de class `active`.
*   Resultaat: De gebruiker ziet in het menu waar hij is (de knop licht op).

### Regel 33-40: Title & Logo
```html
<header class="navbar navbar-expand-lg ...">
    <a class="navbar-brand" href="index.php">🎮 GamePlan Scheduler</a>
```
**Uitleg**:
*   `navbar-expand-lg`: Bootstrap code. Zorgt dat het menu inklapt op mobiel.
*   `navbar-brand`: De plek voor het logo/de titel.

### Regel 48: Hamburger Menu (Mobiel)
```html
<button class="navbar-toggler" ...>
    <span class="navbar-toggler-icon"></span>
</button>
```
**Uitleg**:
*   Als het scherm smal is (telefoon), verdwijnt het menu en verschijnt deze knop (de drie streepjes ☰).
*   Interactie wordt geregeld door Bootstrap JS (in `footer.php`).

### Regel 55-65: Linkerkant Menu
```html
<a class="nav-link <?php echo isActive('index.php'); ?>" href="index.php">
    📊 Dashboard
</a>
```
**Uitleg**:
*   Hier gebruiken we onze `isActive` functie!
*   Als we op het dashboard zijn, wordt de HTML: `class="nav-link active"`.

### Regel 82: Rechterkant Menu (Acties)
```html
<ul class="navbar-nav ms-auto">
```
**Uitleg**:
*   `ms-auto`: Margin-Start-Auto. Dit is Bootstrap-taal voor "Duw alles naar rechts".
*   Daarom staan de knoppen "Login" of "Logout" altijd rechtsboven.

### Regel 85-97: Ingelogd of Niet?
```php
<?php if (isLoggedIn()): ?>
    <!-- Ingelogd: Toon Logout knop -->
    <li><a href="logout.php">Logout</a></li>
<?php else: ?>
    <!-- Niet Ingelogd: Toon Login knop -->
    <li><a href="login.php">Login</a></li>
<?php endif; ?>
```
**Uitleg**:
*   De header past zich aan.
*   Dit voorkomt dat je op "Login" kunt klikken als je al binnen bent.

---
**Samenvatting**: Een slim, adaptief menu dat weet waar je bent en of je ingelogd bent.
