# 🏠 UITLEG INDEX.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Het Dashboard

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De thuisbasis van elke gamer: een overzicht van alle afspraken, vrienden en games."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Dashboard Componenten**
4.  **Data Ophalen met JOINs**
5.  **GIGANTISCH INDEX WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Dashboard & Query Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 📊

De `index.php` is het dashboard - de eerste pagina die een ingelogde gebruiker ziet. Het toont:
- Welkomstboodschap (met naam van de gebruiker).
- Overzicht van komende afspraken (schedules/events).
- Snelle statistieken (aantal vrienden, favoriete spellen).
- Navigatie naar alle functionaliteiten.

---

# 2. Code Analyse

```php
<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Haal komende afspraken op
$stmt = $pdo->prepare("SELECT * FROM Schedules WHERE user_id = ? AND date >= CURDATE() ORDER BY date ASC LIMIT 5");
$stmt->execute([$user_id]);
$schedules = $stmt->fetchAll();

// Haal statistieken op
$friendCount = $pdo->query("SELECT COUNT(*) FROM Friends WHERE user_id = $user_id")->fetchColumn();
?>

<?php include 'header.php'; ?>
<main class="glass-card">
    <h1>Welkom, <?= safeEcho($_SESSION['username']) ?></h1>
    <!-- Dashboard content -->
</main>
<?php include 'footer.php'; ?>
```

- `CURDATE()`: SQL-functie voor de huidige datum.
- `ORDER BY date ASC`: Sorteren op datum (eerstkomende eerst).
- `LIMIT 5`: Maximaal 5 afspraken tonen.
- `safeEcho()`: XSS-preventie bij het tonen van de naam.

---

# 5. GIGANTISCH INDEX WOORDENBOEK (50 TERMEN)

1. **Dashboard**: De centrale overzichtspagina.
2. **Session Start**: Het activeren van de sessie.
3. **Authentication Check**: Controleren of de gebruiker is ingelogd.
4. **FetchAll**: Ophalen van alle rijen uit een query.
5. **CURDATE()**: SQL-functie voor de huidige datum.
6. **ORDER BY**: SQL-clausule voor sorteren.
7. **LIMIT**: SQL-clausule om resultaten te beperken.
8. **Include**: PHP-functie om header/footer in te voegen.
9. **safeEcho()**: Functie die output escaped.
10. **Statistics**: Samenvattende cijfers op het dashboard.
11. **Widget**: Een klein UI-component op het dashboard.
12. **Card Layout**: Kaartgebaseerde visuele structuur.
13. **Glassmorphism**: Het transparante glaseffect in de UI.
14. **Welcome Message**: Gepersonaliseerde begroeting.
15. **User Context**: Data over de huidige gebruiker.
16. **Upcoming Events**: Toekomstige afspraken.
17. **Friend Count**: Aantal vrienden van de gebruiker.
18. **Game Count**: Aantal favoriete spellen.
19. **Schedule Count**: Aantal geplande afspraken.
20. **COUNT()**: SQL-aggregatiefunctie.
21. **fetchColumn()**: Ophalen van één waarde.
22. **Prepared Statement**: Veilige query met placeholders.
23. **ASC (Ascending)**: Oplopend sorteren.
24. **DESC (Descending)**: Aflopend sorteren.
25. **Date Comparison**: Vergelijken van datums in SQL.
26. **Header Include**: Het invoegen van de navigatiebalk.
27. **Footer Include**: Het invoegen van de voettekst.
28. **Main Content Area**: Het centrale deel van de pagina.
29. **Responsive Grid**: Layout die zich aanpast aan schermgrootte.
30. **Quick Actions**: Snelle links naar veelgebruikte functies.
31. **Notification Area**: Plek voor meldingen en updates.
32. **Activity Feed**: Tijdlijn van recente activiteiten.
33. **Empty State**: Weergave als er geen data is.
34. **Loading State**: Weergave tijdens laden.
35. **Error State**: Weergave bij fouten.
36. **Pagination**: Verdeling over meerdere pagina's.
37. **Lazy Loading**: Data laden wanneer nodig.
38. **Real-Time Updates**: Live data-updates (geavanceerd).
39. **Caching**: Opslaan van data voor snelheid.
40. **Session Variable**: Data opgeslagen in de sessie.
41. **User ID**: De unieke identifier van de gebruiker.
42. **Redirect Logic**: Doorsturen bij onbevoegde toegang.
43. **Exit Statement**: Stoppen na redirect.
44. **Template Pattern**: Herbruikbare pagina-structuur.
45. **MVC Pattern**: Model-View-Controller architectuur.
46. **View Layer**: De presentatielaag van de app.
47. **Data Binding**: Het koppelen van data aan de UI.
48. **XSS Prevention**: Bescherming tegen script-injectie.
49. **htmlspecialchars()**: PHP-functie voor escaping.
50. **User Experience (UX)**: De gebruikerservaring.

---

# 6. EXAMEN TRAINING: 20 Dashboard & Query Vragen

1. **Vraag**: Waarom controleren we `isset($_SESSION['user_id'])` als eerste?
   **Antwoord**: Om onbevoegde toegang te voorkomen voordat data wordt opgehaald.

2. **Vraag**: Wat doet `CURDATE()` in de SQL-query?
   **Antwoord**: Het retourneert de huidige datum in YYYY-MM-DD formaat.

3. **Vraag**: Waarom gebruiken we `ORDER BY date ASC`?
   **Antwoord**: Om de eerstkomende afspraken bovenaan te tonen.

4. **Vraag**: Wat is het doel van `LIMIT 5`?
   **Antwoord**: Om alleen de 5 meest relevante afspraken te tonen voor overzicht.

5. **Vraag**: Waarom is `safeEcho()` belangrijk bij het tonen van de username?
   **Antwoord**: Om XSS-aanvallen te voorkomen als de username kwaadaardige scripts bevat.

6. **Vraag**: Wat is het verschil tussen `fetch()` en `fetchAll()`?
   **Antwoord**: fetch() haalt één rij op, fetchAll() haalt alle rijen in een array op.

7. **Vraag**: Waarom includeer we header.php en footer.php?
   **Antwoord**: Voor consistente navigatie en styling op alle pagina's.

8. **Vraag**: Wat is een Dashboard in software-terminologie?
   **Antwoord**: Een centrale pagina met een overzicht van de belangrijkste informatie.

9. **Vraag**: Hoe zou je de dashboard-performance kunnen verbeteren?
   **Antwoord**: Door caching, indexen op de database, en lazy loading van componenten.

10. **Vraag**: Wat is een Widget?
    **Antwoord**: Een klein, zelfstandig UI-component dat specifieke informatie toont.

11. **Vraag**: Waarom staat er `exit()` na de redirect?
    **Antwoord**: Om te voorkomen dat PHP doorgaat en mogelijk data lekt.

12. **Vraag**: Wat is Glassmorphism?
    **Antwoord**: Een designtrend met semi-transparante, wazige achtergronden.

13. **Vraag**: Hoe zou je een "empty state" implementeren?
    **Antwoord**: Door te controleren of `$schedules` leeg is en een alternatieve boodschap te tonen.

14. **Vraag**: Wat is het risico van de directe `$user_id` in de COUNT-query?
    **Antwoord**: Potentieel risico, beter is een prepared statement te gebruiken.

15. **Vraag**: Waarom is gepersonaliseerde content belangrijk?
    **Antwoord**: Het verhoogt engagement en laat de gebruiker zich welkom voelen.

16. **Vraag**: Wat is de MVC-laag waar index.php toe behoort?
    **Antwoord**: De View-laag met controller-elementen (hybride in deze eenvoudige app).

17. **Vraag**: Hoe zou je real-time updates implementeren?
    **Antwoord**: Via WebSockets, Server-Sent Events, of polling.

18. **Vraag**: Wat is Responsive Design?
    **Antwoord**: Een layout die zich aanpast aan verschillende schermformaten.

19. **Vraag**: Waarom is session_start() de eerste regel?
    **Antwoord**: Sessie moet gestart zijn voordat we $_SESSION kunnen lezen.

20. **Vraag**: Wat is de rol van statistieken op het dashboard?
    **Antwoord**: Ze geven de gebruiker snel inzicht in zijn activiteit en content.

---

# 7. Conclusie

De `index.php` is het fysieke commando-centrum van de GamePlan Scheduler, met een naadloze mix van sessie-logica en data-ophaling.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
