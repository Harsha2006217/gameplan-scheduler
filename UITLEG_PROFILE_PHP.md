# UITLEG profile.php (Regel voor Regel)
## GamePlan Scheduler - Profiel & Favorieten

**Bestand**: `profile.php`
**Doel**: Het beheren van je persoonlijke game-bibliotheek (Favorieten).

---

### Regel 28-29: Data Voorbereiden
```php
$userId = getUserId();
$favorites = getFavoriteGames($userId);
```
**Uitleg**:
*   Voordat we de pagina tonen, moeten we weten WIE je bent (`getUserId`).
*   Daarna halen we al jouw favoriete spellen op uit de database (`getFavoriteGames`).

### Regel 33: Nieuw Spel Toevoegen (POST)
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
```
**Uitleg**:
*   `isset($_POST['add_favorite'])`: We checken niet alleen of er een formulier is verzonden, maar specifiek of er op de knop met naam `add_favorite` is gedrukt.
*   **Waarom?**: Straks wil je misschien 2 formulieren op één pagina (bijv. ook Wachtwoord Wijzigen). Zo weet PHP welk formulier bedoeld wordt.

### Regel 38: Toevoegen via Functie
```php
    $error = addFavoriteGame($userId, $title, $description, $note);
```
**Uitleg**:
*   We sturen de titel ("Minecraft"), beschrijving ("Blokjes") en notitie ("Leuk") naar `functions.php`.
*   **Slimme Logica**: Die functie kijkt eerst of het spel al bestaat in de `Games` tabel. Zo niet, maakt hij het aan. Daarna koppelt hij het aan JOU in de `UserGames` tabel.

### Regel 62-65: Berichtgeving
```php
<?php echo getMessage(); ?>
<?php if ($error): ?> ... <?php endif; ?>
```
**Uitleg**:
*   `getMessage()`: Toont groene succes-balkjes (bijv. "Spel toegevoegd!").
*   `$error`: Toont rode fout-balkjes (bijv. "Spel bestaat al in jouw lijst").

### Regel 75: Input (Titel)
```html
<input type="text" ... list="gameSuggestions">
```
**Uitleg**:
*   Hier typt de gebruiker de naam van het spel.
*   *Tip*: In een toekomstige versie kunnen we hier `datalist` gebruiken voor auto-complete!

### Regel 97-131: De Tabel (Weergave)
```html
<table class="table table-dark ...">
    <thead> ... </thead>
    <tbody>
        <?php foreach ($favorites as $game): ?>
```
**Uitleg**:
*   `table-dark`: Bootstrap stijl voor donkere tabellen.
*   `foreach`: We lopen door elk spel in jouw favorietenlijst.
*   Voor ELK spel maken we een tabelrij (`<tr>`).

### Regel 120-124: Actie Knoppen
```html
<a href="edit_favorite.php?id=..." class="btn-warning">✏️ Edit</a>
<a href="delete.php?type=favorite&id=..." class="btn-danger">🗑️ Delete</a>
```
**Uitleg**:
*   **Edit**: Stuurt je naar een aparte pagina om de notitie aan te passen.
*   **Delete**: Stuurt je naar de universele prullenbak (`delete.php`) met het commando "Gooi FAVORIET nr X weg".

---
**Samenvatting**: De profielpagina is jouw persoonlijke game-kast. Je kunt spellen in de kast zetten (Add), bekijken (Table), stickers opplakken (Edit Note) en weggooien (Delete).
