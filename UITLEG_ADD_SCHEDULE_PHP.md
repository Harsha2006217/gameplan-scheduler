# 📅 UITLEG ADD_SCHEDULE.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Nieuwe Afspraken Toevoegen

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De kern van de planning: het toevoegen van nieuwe gaming sessies aan je agenda."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Bugfix #1001 & #1004 Implementatie**
4.  **Database INSERT met PDO**
5.  **GIGANTISCH SCHEDULE WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 CREATE & INSERT Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🗓️

De `add_schedule.php` pagina stelt een ingelogde gebruiker in staat om een nieuwe gaming afspraak toe te voegen aan zijn persoonlijke agenda. Dit omvat:
- Het invoeren van een titel, datum, tijd en optionele beschrijving.
- Volledige validatie inclusief **Bugfix #1001** (lege velden) en **Bugfix #1004** (datumvalidatie).

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
        $error = "Titel mag niet leeg zijn.";
    }

    // Bugfix #1004
    $today = new DateTime('today');
    $eventDate = new DateTime($date);
    if ($eventDate < $today) {
        $error = "Datum moet vandaag of later zijn.";
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("INSERT INTO Schedules (user_id, title, date, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $date, $description]);
        header('Location: index.php?success=schedule_added');
        exit();
    }
}
?>
```

---

# 5. GIGANTISCH SCHEDULE WOORDENBOEK (50 TERMEN)

1. **Schedule Entity**: Het object 'afspraak' in de database.
2. **INSERT Query**: SQL-commando om nieuwe data toe te voegen.
3. **DateTime Class**: PHP-object voor datumverwerking.
4. **Bugfix**: Een correctie van een eerder gevonden probleem.
5. **Trim Function**: Verwijdert witruimte.
6. **Empty Check**: Controleert op lege invoer.
7. **Future Date Validation**: Afdwingen dat datum in de toekomst ligt.
8. **Session ID**: De unieke identifier van de ingelogde sessie.
9. **User ID**: De unieke identifier van de ingelogde gebruiker.
10. **Success Redirect**: Doorsturen na een succesvolle actie.
11. **Bugfix #1001**: Fix voor lege titel validatie.
12. **Bugfix #1004**: Fix voor datumvalidatie in het verleden.
13. **Schedules Table**: Database-tabel voor afspraken.
14. **Date Field**: Formulierveld voor datum.
15. **Time Field**: Formulierveld voor tijd.
16. **Title Field**: Formulierveld voor titel.
17. **Description Field**: Formulierveld voor beschrijving.
18. **Date Picker**: UI-element voor datumselectie.
19. **Time Picker**: UI-element voor tijdselectie.
20. **Datetime Format**: Formaat voor datum en tijd.
21. **Prepared Statement**: Veilige SQL met placeholders.
22. **Execute Method**: Uitvoeren van prepared query.
23. **Error Handling**: Afhandelen van fouten.
24. **Validation Error**: Foutmelding bij validatie.
25. **Form Validation**: Controleren van formulierinvoer.
26. **Server-Side Validation**: Validatie op de server.
27. **Client-Side Validation**: Validatie in de browser.
28. **Calendar View**: Kalenderweergave van afspraken.
29. **Upcoming Events**: Komende afspraken.
30. **Past Events**: Afgelopen afspraken.
31. **Today Marker**: Markering voor vandaag.
32. **Event Duration**: Lengte van de afspraak.
33. **All-Day Event**: Dagvullende afspraak.
34. **Recurring Schedule**: Herhalende afspraak.
35. **Weekly Schedule**: Wekelijkse afspraak.
36. **Monthly Schedule**: Maandelijkse afspraak.
37. **Gaming Session**: Een game-sessie.
38. **Tournament**: Toernooi-afspraak.
39. **Practice Session**: Oefen-sessie.
40. **Team Meeting**: Teamoverleg.
41. **Reminder**: Herinnering voor afspraak.
42. **Notification**: Melding over afspraak.
43. **Attendees**: Deelnemers aan afspraak.
44. **Invite Friends**: Vrienden uitnodigen.
45. **RSVP**: Aanmelding voor afspraak.
46. **Status**: Status van de afspraak.
47. **Cancelled**: Geannuleerde afspraak.
48. **Completed**: Voltooide afspraak.
49. **Pending**: Lopende afspraak.
50. **HART Protocol Schedule**: Veilige scheduling volgens HART.

---

# 6. EXAMEN TRAINING: 20 Schedule & Calendar Vragen

1. **Vraag**: Wat is Bugfix #1001 in add_schedule.php?
   **Antwoord**: De fix die voorkomt dat een lege titel wordt geaccepteerd.

2. **Vraag**: Wat is Bugfix #1004?
   **Antwoord**: De fix die voorkomt dat afspraken in het verleden worden aangemaakt.

3. **Vraag**: Hoe werkt de DateTime-vergelijking?
   **Antwoord**: DateTime objecten kunnen direct worden vergeleken met < > == operators.

4. **Vraag**: Waarom is `new DateTime('today')` beter dan `new DateTime()`?
   **Antwoord**: 'today' geeft de datum zonder tijd, voor zuivere datumvergelijking.

5. **Vraag**: Wat is het voordeel van server-side validation?
   **Antwoord**: Het kan niet worden omzeild door de gebruiker.

6. **Vraag**: Waarom koppelen we user_id aan de schedule?
   **Antwoord**: Zodat elke gebruiker alleen zijn eigen afspraken ziet.

7. **Vraag**: Wat is een Recurring Schedule?
   **Antwoord**: Een afspraak die zich herhaalt (dagelijks, wekelijks, etc.).

8. **Vraag**: Hoe implementeer je Recurring Schedules?
   **Antwoord**: Extra velden voor frequentie en einddatum, of aparte recurrence tabel.

9. **Vraag**: Wat is een Calendar View?
   **Antwoord**: Een visuele weergave van afspraken in kalenderformaat.

10. **Vraag**: Wat is een Date Picker?
    **Antwoord**: Een UI-element waarmee gebruikers een datum kunnen selecteren.

11. **Vraag**: Waarom is de Description optioneel?
    **Antwoord**: Niet alle afspraken hebben een beschrijving nodig.

12. **Vraag**: Wat is een Reminder?
    **Antwoord**: Een melding die de gebruiker herinnert aan een aankomende afspraak.

13. **Vraag**: Hoe zou je Invite Friends implementeren?
    **Antwoord**: Extra tabel voor deelnemers, email-notificaties.

14. **Vraag**: Wat is RSVP?
    **Antwoord**: Répondez s'il vous plaît - bevestiging of je komt.

15. **Vraag**: Wat is het verschil tussen Pending en Completed?
    **Antwoord**: Pending is nog niet geweest; Completed is afgelopen.

16. **Vraag**: Hoe zou je een Gaming Tournament implementeren?
    **Antwoord**: Speciale event-type met extra velden voor game, brackets, etc.

17. **Vraag**: Wat is een All-Day Event?
    **Antwoord**: Een afspraak die de hele dag duurt, zonder specifieke tijd.

18. **Vraag**: Waarom is trim() belangrijk voor de titel?
    **Antwoord**: Om lege titels met alleen spaties te detecteren.

19. **Vraag**: Wat gebeurt na een succesvolle INSERT?
    **Antwoord**: Redirect naar index.php met success parameter.

20. **Vraag**: Wat is HART Protocol Schedule?
    **Antwoord**: Veilige afspraak-creatie met validatie en session checks.

---

# 7. Conclusie

De `add_schedule.php` is een schoolvoorbeeld van veilige CREATE-logica met volledige validatie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
