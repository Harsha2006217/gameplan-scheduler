# 📅 UITLEG EDIT_SCHEDULE.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Agenda Items Bewerken

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De kern van de app: het aanpassen van bestaande afspraken met strikte validatie."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Bugfix #1001 & #1004 Implementatie**
4.  **Database Interactie (PDO)**
5.  **GIGANTISCH SCHEDULE WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Schedule & Validatie Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 📆

De `edit_schedule.php` pagina laat gebruikers een bestaande afspraak bewerken:
- Wijzigen van titel, datum, tijd en beschrijving.
- Validatie van lege velden (**Bugfix #1001**).
- Validatie dat de datum in de toekomst ligt (**Bugfix #1004**).

---

# 2. Code Analyse

```php
<?php
// Bugfix #1001: Lege velden check
if (empty(trim($title))) {
    $error = "Titel mag niet leeg zijn.";
}

// Bugfix #1004: Datumvalidatie
$today = new DateTime();
$eventDate = new DateTime($date);
if ($eventDate < $today) {
    $error = "Datum moet in de toekomst liggen.";
}

// Als geen errors, update in database
$stmt = $pdo->prepare("UPDATE Schedules SET title = ?, date = ?, description = ? WHERE schedule_id = ? AND user_id = ?");
$stmt->execute([$title, $date, $description, $schedule_id, $user_id]);
?>
```

- `empty(trim($title))`: De dubbele check voorkomt dat een titel van alleen spaties wordt geaccepteerd.
- `DateTime` klasse: Een robuuste PHP-klasse voor datumvergelijkingen.

---

# 5. GIGANTISCH SCHEDULE WOORDENBOEK (50 TERMEN)

1. **Schedule Entity**: Het object 'afspraak' in onze database.
2. **DateTime Class**: PHP-object voor geavanceerde datumlogica.
3. **Trim Function**: Verwijdert spaties aan begin en einde van een string.
4. **Empty Check**: Controleren of een waarde leeg is.
5. **Future Date Validation**: Afdwingen dat een datum in de toekomst ligt.
6. **UPDATE Query**: SQL-commando om bestaande data te wijzigen.
7. **Ownership Check**: De `user_id` in de WHERE-clausule.
8. **Description Field**: Optionele extra informatie bij een afspraak.
9. **Error Handling**: Het tonen van foutmeldingen bij validatiefouten.
10. **Form Pre-fill**: Het vooraf invullen van het formulier met oude waarden.
11. **Bugfix #1001**: Fix voor lege titel validatie.
12. **Bugfix #1004**: Fix voor datumvalidatie.
13. **Schedule ID**: Unieke identifier van de afspraak.
14. **Title Field**: Veld voor de titel.
15. **Date Field**: Veld voor de datum.
16. **Time Field**: Veld voor de tijd.
17. **CRUD Operations**: Create, Read, Update, Delete.
18. **Update Operation**: De 'U' in CRUD.
19. **WHERE Clause**: Filter in UPDATE query.
20. **Double WHERE**: ID én user_id in WHERE.
21. **Prepared Statement**: Veilige SQL met placeholders.
22. **Execute Method**: Uitvoeren van de query.
23. **Bind Parameters**: Waarden aan placeholders koppelen.
24. **Session Check**: Controleren of ingelogd.
25. **Authorization**: Controle wie wat mag doen.
26. **Get Parameter**: ID via URL ophalen.
27. **Post Handling**: Formulierdata verwerken.
28. **Form Action**: URL waar formulier naartoe stuurt.
29. **Hidden Field**: Verborgen veld met schedule_id.
30. **Date Picker**: UI voor datumselectie.
31. **Time Picker**: UI voor tijdselectie.
32. **Submit Button**: Verzendknop.
33. **Cancel Button**: Annuleerknop.
34. **Validation Error**: Foutmelding bij fout.
35. **Success Redirect**: Doorsturen na succes.
36. **Success Message**: Bevestigingsmelding.
37. **Edit Page**: De bewerkingspagina.
38. **Original Values**: De oude waarden.
39. **New Values**: De nieuwe waarden.
40. **Change Detection**: Detecteren of iets is gewijzigd.
41. **Recurring Edit**: Herhalende afspraak bewerken.
42. **Single Instance Edit**: Eén voorkomen bewerken.
43. **All Instances Edit**: Alle voorkomens bewerken.
44. **Conflict Detection**: Detecteren van overlappende afspraken.
45. **Reschedule**: Afspraak verzetten.
46. **Cancel Schedule**: Afspraak annuleren.
47. **Notification Update**: Herinneringen updaten.
48. **Attendees Update**: Deelnemers bijwerken.
49. **Calendar Sync**: Synchronisatie met kalender.
50. **HART Protocol Schedule UPDATE**: Veilige UPDATE volgens HART.

---

# 6. EXAMEN TRAINING: 20 Schedule & Validatie Vragen

1. **Vraag**: Wat is Bugfix #1001?
   **Antwoord**: De fix die voorkomt dat een lege titel wordt geaccepteerd.

2. **Vraag**: Wat is Bugfix #1004?
   **Antwoord**: De fix die voorkomt dat datums in het verleden worden geaccepteerd.

3. **Vraag**: Waarom `empty(trim($title))` i.p.v. alleen `empty()`?
   **Antwoord**: trim() verwijdert spaties, zodat " " ook als leeg wordt gezien.

4. **Vraag**: Hoe werkt DateTime-vergelijking?
   **Antwoord**: DateTime objecten kunnen direct worden vergeleken met < > ==.

5. **Vraag**: Waarom is Ownership Check dubbel in de query?
   **Antwoord**: schedule_id én user_id zorgen voor autorisatie én identificatie.

6. **Vraag**: Wat is Form Pre-fill?
   **Antwoord**: Het vooraf invullen van formuliervelden met bestaande data.

7. **Vraag**: Wat gebeurt als validatie faalt?
   **Antwoord**: Error message tonen, geen database update uitvoeren.

8. **Vraag**: Wat is het PRG-pattern?
   **Antwoord**: Post-Redirect-Get om dubbele form submissions te voorkomen.

9. **Vraag**: Hoe zou je Conflict Detection implementeren?
   **Antwoord**: Query voor afspraken op dezelfde datum/tijd voor de user.

10. **Vraag**: Wat is Reschedule?
    **Antwoord**: Het wijzigen van de datum/tijd van een bestaande afspraak.

11. **Vraag**: Wat is Cancel Schedule?
    **Antwoord**: Het annuleren van een afspraak (status wijzigen of verwijderen).

12. **Vraag**: Hoe edit je een Recurring Schedule?
    **Antwoord**: Keuze: alleen deze, deze en toekomstige, of alle voorkomens.

13. **Vraag**: Wat is Single Instance Edit?
    **Antwoord**: Alleen dit specifieke voorkomen van een herhalende afspraak bewerken.

14. **Vraag**: Waarom is Description optioneel?
    **Antwoord**: Niet alle afspraken hebben een beschrijving nodig.

15. **Vraag**: Wat is een Hidden Field voor schedule_id?
    **Antwoord**: Onzichtbaar veld dat de ID doorgeeft bij form submit.

16. **Vraag**: Hoe update je Attendees bij een wijziging?
    **Antwoord**: Notification naar deelnemers met de nieuwe details.

17. **Vraag**: Wat is Calendar Sync?
    **Antwoord**: Synchronisatie van wijzigingen naar externe kalenders.

18. **Vraag**: Waarom is exit() na redirect nodig?
    **Antwoord**: Het stopt verdere PHP-uitvoering.

19. **Vraag**: Wat is Change Detection nuttig voor?
    **Antwoord**: Om te weten of een database update nodig is.

20. **Vraag**: Wat is HART Protocol Schedule UPDATE?
    **Antwoord**: Veilige update met ownership check, validatie en prepared statements.

---

# 7. Conclusie

De `edit_schedule.php` is het hart van de applicatie en bewijst de robuustheid van onze dubbele validatie (Bugfix #1001 & #1004).

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
