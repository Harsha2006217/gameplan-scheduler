# 📝 UITLEG EDIT_EVENT.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Gaming Events Bewerken

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De ultieme controle: het aanpassen van je geplande gaming events."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Bugfix #1001 & #1004 Integratie**
4.  **Ownership Check & Database Interactie**
5.  **GIGANTISCH EVENT WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Event & UPDATE Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🎮

De `edit_event.php` pagina stelt gebruikers in staat om een bestaand gaming event te wijzigen. Dit omvat:
- Het aanpassen van de eventnaam, datum en beschrijving.
- Volledige validatie (lege velden, datumcontrole).
- Verificatie dat het event toebehoort aan de ingelogde gebruiker.

---

# 2. Code Analyse

```php
<?php
$event_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Ownership check
$stmt = $pdo->prepare("SELECT * FROM Events WHERE event_id = ? AND user_id = ?");
$stmt->execute([$event_id, $user_id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: index.php?error=unauthorized');
    exit();
}

// POST handling met validatie en UPDATE query
?>
```

- `(int)$_GET['id']`: Type casting voor SQL Injection preventie.
- **Ownership Check**: De query controleert `user_id` om te voorkomen dat een andere gebruiker het event kan bewerken.

---

# 5. GIGANTISCH EVENT WOORDENBOEK (50 TERMEN)

1. **Event Entity**: Het object 'gaming event' in de database.
2. **UPDATE Query**: SQL-commando om bestaande data te wijzigen.
3. **Ownership Validation**: Controleren of het event bij de gebruiker hoort.
4. **Type Casting**: Omzetten van variabele naar integer.
5. **Pre-fill Form**: Het vooraf invullen van velden met bestaande data.
6. **Fetch Method**: Het ophalen van een enkele rij uit het query-resultaat.
7. **Redirect**: Doorsturen bij onbevoegde toegang.
8. **Exit Function**: Stoppen van verdere PHP-uitvoering.
9. **DateTime Validation**: Controleren of de datum geldig is.
10. **Bugfix Integration**: Toepassen van eerder gevonden fixes.
11. **Bugfix #1001**: Fix voor lege velden validatie.
12. **Bugfix #1004**: Fix voor datumvalidatie.
13. **CRUD Operations**: Create, Read, Update, Delete.
14. **Update Operation**: De 'U' in CRUD.
15. **WHERE Clause**: Filter in UPDATE query.
16. **Double WHERE**: ID én user_id in WHERE.
17. **Prepared Statement**: Veilige SQL met placeholders.
18. **Execute Method**: Uitvoeren van de query.
19. **Bind Parameters**: Waarden aan placeholders koppelen.
20. **Session Check**: Controleren of ingelogd.
21. **Authorization**: Controle wie wat mag doen.
22. **Get Parameter**: ID via URL ophalen.
23. **Post Handling**: Formulierdata verwerken.
24. **Form Action**: URL waar formulier naartoe stuurt.
25. **Hidden Field**: Verborgen veld met event_id.
26. **Event Title**: Naam van het event.
27. **Event Date**: Datum van het event.
28. **Event Description**: Beschrijving van het event.
29. **Date Picker**: UI voor datumselectie.
30. **Time Picker**: UI voor tijdselectie.
31. **Submit Button**: Verzendknop.
32. **Cancel Button**: Annuleerknop.
33. **Validation Error**: Foutmelding bij fout.
34. **Success Redirect**: Doorsturen na succes.
35. **Success Message**: Bevestigingsmelding.
36. **Edit Page**: De bewerkingspagina.
37. **Form Pre-population**: Velden vullen met bestaande data.
38. **Original Values**: De oude waarden.
39. **New Values**: De nieuwe waarden.
40. **Change Detection**: Detecteren of iets is gewijzigd.
41. **Audit Trail**: Loggen van wijzigingen.
42. **Version History**: Geschiedenis van versies.
43. **Rollback**: Terug naar vorige versie.
44. **Concurrent Editing**: Gelijktijdig bewerken.
45. **Optimistic Locking**: Conflictdetectie.
46. **Last Modified**: Laatste wijzigingstijdstip.
47. **Modified By**: Wie heeft gewijzigd.
48. **Event Cancellation**: Event annuleren.
49. **Event Rescheduling**: Event verzetten.
50. **HART Protocol UPDATE**: Veilige UPDATE volgens HART.

---

# 6. EXAMEN TRAINING: 20 Event & UPDATE Vragen

1. **Vraag**: Waarom is de Ownership Check essentieel?
   **Antwoord**: Het voorkomt dat gebruiker A het event van gebruiker B kan bewerken.

2. **Vraag**: Wat doet `(int)$_GET['id']`?
   **Antwoord**: Type casting naar integer om SQL Injection te voorkomen.

3. **Vraag**: Wat is het verschil tussen INSERT en UPDATE?
   **Antwoord**: INSERT maakt nieuwe data; UPDATE wijzigt bestaande data.

4. **Vraag**: Waarom staat user_id in de WHERE-clausule?
   **Antwoord**: Extra beveiligingslaag: alleen eigen events kunnen worden bewerkt.

5. **Vraag**: Wat is Form Pre-population?
   **Antwoord**: Het vooraf invullen van het formulier met de huidige waarden.

6. **Vraag**: Hoe haal je de oude event-data op?
   **Antwoord**: SELECT query met event_id en user_id.

7. **Vraag**: Wat gebeurt als het event niet bestaat?
   **Antwoord**: Redirect naar index.php met error parameter.

8. **Vraag**: Wat is een Hidden Field?
   **Antwoord**: Een onzichtbaar formulierveld, vaak voor het doorgeven van IDs.

9. **Vraag**: Waarom is Bugfix #1001 ook bij edit nodig?
   **Antwoord**: Omdat ook bij wijzigen lege titels moeten worden geweigerd.

10. **Vraag**: Wat is Change Detection?
    **Antwoord**: Het detecteren of de gebruiker iets heeft gewijzigd.

11. **Vraag**: Wat is Audit Trail?
    **Antwoord**: Een logboek van alle wijzigingen en wie ze deed.

12. **Vraag**: Wat is Version History?
    **Antwoord**: Het bewaren van eerdere versies van data.

13. **Vraag**: Wat is Rollback?
    **Antwoord**: Het herstellen van een eerdere versie.

14. **Vraag**: Wat is Optimistic Locking?
    **Antwoord**: Conflictdetectie bij gelijktijdig bewerken.

15. **Vraag**: Hoe implementeer je Last Modified?
    **Antwoord**: Extra kolom met timestamp, automatisch updaten bij wijziging.

16. **Vraag**: Wat is het verschil tussen edit en delete pagina?
    **Antwoord**: Edit wijzigt data; delete verwijdert data.

17. **Vraag**: Waarom redirect je na een succesvolle update?
    **Antwoord**: Om dubbele POST-requests te voorkomen (PRG-pattern).

18. **Vraag**: Wat is Event Rescheduling?
    **Antwoord**: Het wijzigen van de datum/tijd van een event.

19. **Vraag**: Waarom is exit() na redirect belangrijk?
    **Antwoord**: Het stopt verdere PHP-uitvoering.

20. **Vraag**: Wat is HART Protocol UPDATE?
    **Antwoord**: Veilige UPDATE met ownership check, validatie en prepared statements.

---

# 7. Conclusie

De `edit_event.php` combineert CRUD-logica met strikte beveiliging en validatie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
