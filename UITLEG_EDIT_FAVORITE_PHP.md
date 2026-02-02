# UITLEG edit_favorite.php (Regel voor Regel)
## GamePlan Scheduler - Favoriet Bewerken

**Bestand**: `edit_favorite.php`
**Doel**: Notities of beschrijvingen van een favoriet spel aanpassen.

---

### Regel 28: ID Ophalen
```php
$gameId = $_GET['id'] ?? 0;
```
**Uitleg**: Welk spel wil je bewerken?

### Regel 30: Game Data + Ownership Check
```php
$favorite = getFavoriteGame($gameId, getUserId());
```
**Uitleg**:
*   Deze functie haalt het spel op, MAAR ALLEEN als het gekoppeld is aan jouw `user_id`.
*   Als ik stiekem ID 5 (van iemand anders) probeer, krijg ik hier niets terug.

### Regel 48: De Dubbele Update
```php
$error = updateFavoriteGame(getUserId(), $gameId, $title, $description, $note);
```
**Uitleg**:
*   Dit is interessant! We updaten eigenlijk twee tabellen in de database:
    1.  `Games` tabel: Als je de titel verandert van "Minecraft" naar "Minecraft PC".
    2.  `UserGames` tabel: Als je jouw persoonlijke notitie verandert.
*   De functie `updateFavoriteGame` regelt dit allemaal veilig achter de schermen.

### Regel 82: Beschrijving Textarea
```html
<textarea name="description" ...><?php echo safeEcho($description); ?></textarea>
```
**Uitleg**:
*   Let op: Bij een `<input>` gebruik je `value="..."`.
*   Bij een `<textarea>` zet je de tekst **tussen** de tags `>hier<`.
*   `safeEcho` zorgt dat vreemde tekens (zoals `"` of `<`) de pagina niet breken.

---
**Samenvatting**: Stelt de gebruiker in staat om zijn/haar mening over een spel (de notitie) of de details van het spel zelf bij te werken.
