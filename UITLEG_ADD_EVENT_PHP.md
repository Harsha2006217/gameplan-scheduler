# ➕ UITLEG ADD_EVENT.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Nieuwe Gaming Events Toevoegen

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Het creëren van nieuwe gaming momenten: de CREATE in CRUD."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Bugfix #1001 & #1004 Integratie**
4.  **INSERT met PDO Prepared Statements**
5.  **GIGANTISCH ADD_EVENT WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 CREATE & INSERT Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🎮

De `add_event.php` pagina stelt een ingelogde gebruiker in staat om een nieuw gaming event toe te voegen. Dit omvat:
- Eventnaam, datum, tijd en beschrijving.
- Volledige validatie met Bugfix #1001 (lege velden) en Bugfix #1004 (toekomstige datum).

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $date = $_POST['date'];
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    // Bugfix #1001
    if (empty($title)) {
        $error = "Eventnaam mag niet leeg zijn.";
    }

    // Bugfix #1004
    $today = new DateTime('today');
    $eventDate = new DateTime($date);
    if ($eventDate < $today) {
        $error = "Datum moet vandaag of later zijn.";
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("INSERT INTO Events (user_id, title, date, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $date, $description]);
        header('Location: index.php?success=event_added');
        exit();
    }
}
?>
```

---

# 5. GIGANTISCH ADD_EVENT WOORDENBOEK (50 TERMEN)

1. **Event Entity**: Het object 'gaming event' in de database.
2. **INSERT Query**: SQL-commando om nieuwe data toe te voegen.
3. **Trim Function**: Verwijdert witruimte aan begin en einde.
4. **Empty Check**: Controleert of een waarde leeg is.
5. **DateTime Class**: PHP-object voor datumlogica.
6. **Future Date Validation**: Afdwingen dat datum in de toekomst ligt.
7. **Session Start**: Het starten van de sessie.
8. **Require Once**: Zorgt dat een bestand slechts één keer wordt geladen.
9. **POST Request**: HTTP-methode voor formulierverzending.
10. **Prepared Statement**: Veilige SQL-query met placeholders.
11. **Bugfix #1001**: Fix voor lege velden validatie.
12. **Bugfix #1004**: Fix voor datumvalidatie in het verleden.
13. **Form Validation**: Controleren van formulierinvoer.
14. **Server-Side Validation**: Validatie op de PHP-server.
15. **Client-Side Validation**: Validatie in de browser.
16. **Error Message**: Foutmelding bij validatiefouten.
17. **Success Redirect**: Doorsturen na succesvolle actie.
18. **User ID Binding**: Koppelen van event aan gebruiker.
19. **Title Field**: Veld voor de eventnaam.
20. **Date Field**: Veld voor de eventdatum.
21. **Description Field**: Veld voor de eventbeschrijving.
22. **Optional Field**: Niet-verplicht veld.
23. **Required Field**: Verplicht veld.
24. **Date Picker**: UI-element voor datumselectie.
25. **Time Picker**: UI-element voor tijdselectie.
26. **Datetime Format**: Formaat voor datum en tijd (Y-m-d H:i:s).
27. **Timezone Handling**: Omgaan met tijdzones.
28. **Event Duration**: Lengte van het event.
29. **Recurring Events**: Herhalende events (toekomstig).
30. **Event Categories**: Categorieën voor events.
31. **Event Tags**: Labels voor events.
32. **Event Location**: Locatie van het event.
33. **Online Event**: Virtual/online event.
34. **Attendees**: Deelnemers aan het event.
35. **RSVP Functionality**: Aanmelden voor events.
36. **Calendar Integration**: Koppeling met kalender-apps.
37. **Reminder System**: Herinneringen voor events.
38. **Notification**: Melding over events.
39. **Conflict Detection**: Overlappende events detecteren.
40. **Auto-Fill**: Automatisch invullen van velden.
41. **Form Action**: URL waar formulier naartoe stuurt.
42. **Method Attribute**: GET of POST methode.
43. **Form Name**: Naam van het formulier.
44. **Input Type Date**: HTML5 date input.
45. **Input Type Text**: Tekstinvoerveld.
46. **Textarea**: Meerdere regels tekstinvoer.
47. **Submit Button**: Knop om formulier te verzenden.
48. **Cancel Button**: Knop om actie te annuleren.
49. **Form Reset**: Formulier leegmaken.
50. **HART Protocol CREATE**: Veilige CREATE volgens HART.

---

# 6. EXAMEN TRAINING: 20 CREATE & INSERT Vragen

1. **Vraag**: Wat is Bugfix #1001?
   **Antwoord**: De fix die voorkomt dat lege velden of alleen spaties worden geaccepteerd.

2. **Vraag**: Wat is Bugfix #1004?
   **Antwoord**: De fix die voorkomt dat events in het verleden worden aangemaakt.

3. **Vraag**: Waarom gebruiken we `trim()` op de title?
   **Antwoord**: Om spaties aan begin en einde te verwijderen en lege invoer te detecteren.

4. **Vraag**: Hoe werkt de DateTime-vergelijking in PHP?
   **Antwoord**: DateTime objecten kunnen direct worden vergeleken met < > == operators.

5. **Vraag**: Waarom is server-side validatie belangrijker dan client-side?
   **Antwoord**: Client-side kan worden omzeild; de server is de definitieve check.

6. **Vraag**: Wat betekent `!isset($error)`?
   **Antwoord**: Controleren of er geen foutmelding is voordat we naar de database schrijven.

7. **Vraag**: Waarom koppelen we user_id aan het event?
   **Antwoord**: Zodat elke gebruiker alleen zijn eigen events ziet en beheert.

8. **Vraag**: Wat is een Prepared Statement?
   **Antwoord**: Een SQL-query met placeholders die SQL Injection voorkomt.

9. **Vraag**: Waarom is de Description field optioneel?
   **Antwoord**: Niet alle events hebben een beschrijving nodig.

10. **Vraag**: Hoe zou je recurring events implementeren?
    **Antwoord**: Extra velden voor frequentie en einddatum, meerdere INSERT-statements.

11. **Vraag**: Wat is een Date Picker?
    **Antwoord**: Een UI-element waarmee gebruikers makkelijk een datum kunnen selecteren.

12. **Vraag**: Waarom gebruiken we `new DateTime('today')`?
    **Antwoord**: Om de huidige datum zonder tijd te krijgen voor vergelijking.

13. **Vraag**: Wat is Form Validation?
    **Antwoord**: Het controleren of formulierinvoer aan de regels voldoet.

14. **Vraag**: Hoe zou je conflict detection implementeren?
    **Antwoord**: Query voor events op dezelfde datum/tijd voor de gebruiker.

15. **Vraag**: Wat is een Success Redirect?
    **Antwoord**: Doorsturen naar een andere pagina na succesvolle actie.

16. **Vraag**: Waarom staat er `exit()` na de redirect?
    **Antwoord**: Om te voorkomen dat PHP doorgaat met uitvoeren.

17. **Vraag**: Wat is het verschil tussen GET en POST voor forms?
    **Antwoord**: POST is veilig voor data-versturen, GET toont data in de URL.

18. **Vraag**: Hoe zou je timezone handling implementeren?
    **Antwoord**: Opslaan in UTC, converteren bij weergave naar user's timezone.

19. **Vraag**: Wat is auto-fill in formulieren?
    **Antwoord**: Het automatisch invullen van velden op basis van context of historie.

20. **Vraag**: Waarom is de INSERT-query beveiligd met prepared statements?
    **Antwoord**: Om SQL Injection te voorkomen via de titel of beschrijving.

---

# 7. Conclusie

De `add_event.php` is een schoolvoorbeeld van veilige CREATE-logica met volledige dubbele validatie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
