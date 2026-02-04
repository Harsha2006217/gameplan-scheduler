# 🗑️ UITLEG DELETE.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Veilig Verwijderen van Data

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De DELETE in CRUD: misschien wel de gevaarlijkste operatie, en daarom de beste beveiliging."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Ownership Check & Security**
4.  **Soft Delete vs Hard Delete**
5.  **GIGANTISCH DELETE WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 DELETE & Security Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🗑️

De `delete.php` pagina handelt het verwijderen van data af (schedules, vrienden, favorieten, events). Cruciale features:
- Strikte type casting van de `id` parameter.
- **Ownership Check**: Alleen de eigenaar kan zijn eigen data verwijderen.
- **Optional Soft Delete**: Data markeren als 'verwijderd' zonder fysiek te wissen.

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

$id = (int)$_GET['id'];
$type = $_GET['type']; // 'schedule', 'friend', 'favorite', 'event'
$user_id = $_SESSION['user_id'];

// Ownership check
$table = match($type) {
    'schedule' => 'Schedules',
    'friend' => 'Friends',
    'favorite' => 'Favorites',
    'event' => 'Events',
    default => null
};

if ($table) {
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
}

header('Location: index.php?success=deleted');
exit();
?>
```

- `(int)$_GET['id']`: **Type Casting**. Dwingt af dat ID een integer is.
- `match()`: PHP 8 feature voor elegante branching.
- **Double WHERE**: Voorkomt dat een gebruiker data van anderen verwijdert.

---

# 5. GIGANTISCH DELETE WOORDENBOEK (50 TERMEN)

1. **DELETE Query**: SQL-commando om data te verwijderen.
2. **Hard Delete**: Data fysiek verwijderen uit de database.
3. **Soft Delete**: Data markeren als verwijderd maar niet wissen.
4. **Ownership Check**: Controleren of de data bij de gebruiker hoort.
5. **Type Casting**: Variabele omzetten naar een specifiek type.
6. **SQL Injection**: Aanval via kwaadaardige invoer in queries.
7. **Prepared Statement**: Veilige SQL met placeholders.
8. **Match Expression**: PHP 8 feature voor branching.
9. **Redirect**: Doorsturen na een actie.
10. **Exit**: Stoppen van verdere PHP-uitvoering.
11. **IDOR (Insecure Direct Object Reference)**: Aanval via onbeveiligde ID's.
12. **Authorization Check**: Controleren of actie is toegestaan.
13. **Cascade Delete**: Automatisch verwijderen van gerelateerde data.
14. **Foreign Key Constraint**: Database-regel voor relaties.
15. **ON DELETE CASCADE**: Automatische verwijdering bij parent delete.
16. **Rollback**: Ongedaan maken van een transactie.
17. **Transaction**: Groep van database-operaties die samen slagen of falen.
18. **Confirmation Dialog**: Bevestigingspop-up voor delete.
19. **Undo Functionality**: Mogelijkheid om delete ongedaan te maken.
20. **Recycle Bin**: Tijdelijke opslag voor verwijderde items.
21. **Audit Trail**: Logboek van wie wat heeft verwijderd.
22. **Deleted At Timestamp**: Tijdstempel van verwijdering (soft delete).
23. **Is Deleted Flag**: Boolean voor soft delete status.
24. **Data Retention Policy**: Beleid voor hoelang data wordt bewaard.
25. **GDPR Right to Erasure**: Recht om vergeten te worden.
26. **Bulk Delete**: Meerdere items tegelijk verwijderen.
27. **Single Delete**: Één item verwijderen.
28. **Delete Permission**: Recht om te mogen verwijderen.
29. **Admin Override**: Admin kan alles verwijderen.
30. **User Scope Delete**: Gebruiker kan alleen eigen data verwijderen.
31. **GET Parameter**: Data via URL (?id=5).
32. **POST for Delete**: Veiliger dan GET voor destructieve acties.
33. **CSRF Token**: Bescherming tegen cross-site request forgery.
34. **Idempotency**: Meerdere deletes hebben zelfde effect.
35. **404 Not Found**: Als item niet bestaat.
36. **403 Forbidden**: Als gebruiker geen rechten heeft.
37. **Success Message**: Bevestiging na succesvolle delete.
38. **Error Handling**: Afhandelen van delete-fouten.
39. **Database Trigger**: Automatische actie bij delete.
40. **Referential Integrity**: Consistentie tussen tabellen.
41. **Orphan Records**: Verwezen records zonder parent.
42. **Cleanup Jobs**: Automatische opruimtaken.
43. **Archive vs Delete**: Archiveren versus verwijderen.
44. **Legal Hold**: Data niet verwijderen wegens juridische redenen.
45. **Data Erasure Certificate**: Bewijs van verwijdering.
46. **Physical Deletion**: Daadwerkelijke verwijdering van disk.
47. **Logical Deletion**: Markeren als verwijderd.
48. **Backup Consideration**: Verwijderde data kan in backups zitten.
49. **Privacy Compliance**: Naleving van privacywetten.
50. **HART Protocol Delete**: Veilige delete volgens HART.

---

# 6. EXAMEN TRAINING: 20 DELETE & Security Vragen

1. **Vraag**: Waarom is `(int)$_GET['id']` essentieel?
   **Antwoord**: Het dwingt type casting af, wat SQL Injection via de ID voorkomt.

2. **Vraag**: Wat is een Ownership Check?
   **Antwoord**: Controleren of het te verwijderen item van de ingelogde gebruiker is.

3. **Vraag**: Wat is het verschil tussen Hard Delete en Soft Delete?
   **Antwoord**: Hard delete wist fysiek; soft delete markeert als verwijderd.

4. **Vraag**: Waarom is de `match()` expression handig in delete.php?
   **Antwoord**: Het biedt elegante branching voor meerdere item-types.

5. **Vraag**: Wat is IDOR en hoe voorkom je het?
   **Antwoord**: Insecure Direct Object Reference; voorkom met ownership checks.

6. **Vraag**: Waarom staat er `exit()` na de redirect?
   **Antwoord**: Om te voorkomen dat PHP doorgaat en potentieel data lekt.

7. **Vraag**: Wat is ON DELETE CASCADE?
   **Antwoord**: Automatisch verwijderen van child records bij parent delete.

8. **Vraag**: Waarom zou je POST verkiezen boven GET voor deletes?
   **Antwoord**: POST is veiliger voor destructieve acties en voorkomt accidentele deletes.

9. **Vraag**: Wat is een CSRF Token?
   **Antwoord**: Een unieke token die verifieert dat het request van je eigen site komt.

10. **Vraag**: Hoe implementeer je een confirmation dialog?
    **Antwoord**: JavaScript confirm() of een aparte bevestigingspagina.

11. **Vraag**: Wat is een Audit Trail?
    **Antwoord**: Een logboek van wie wat wanneer heeft gedaan.

12. **Vraag**: Waarom is Soft Delete vaak beter dan Hard Delete?
    **Antwoord**: Het maakt undo mogelijk en behoudt data voor analyse.

13. **Vraag**: Wat is het GDPR Right to Erasure?
    **Antwoord**: Het recht van gebruikers om hun data te laten verwijderen.

14. **Vraag**: Hoe zou je Bulk Delete implementeren?
    **Antwoord**: Array van IDs, loop met DELETE queries, of IN-clause.

15. **Vraag**: Wat is Referential Integrity?
    **Antwoord**: De consistentie van relaties tussen database-tabellen.

16. **Vraag**: Waarom is de double WHERE-clausule belangrijk?
    **Antwoord**: Het combineert ID-check met user_id-check voor security.

17. **Vraag**: Wat zijn Orphan Records?
    **Antwoord**: Child records waarvan de parent is verwijderd.

18. **Vraag**: Hoe voorkom je Orphan Records?
    **Antwoord**: Met Foreign Key constraints en ON DELETE CASCADE.

19. **Vraag**: Wat is een Recycle Bin in database-context?
    **Antwoord**: Een tabel of flag voor soft deleted items.

20. **Vraag**: Waarom moet je voorzichtig zijn met deletes in producti?
    **Antwoord**: Verwijderde data is vaak niet te herstellen zonder backups.

---

# 7. Conclusie

De `delete.php` is een schoolvoorbeeld van veilige DELETE-logica met strikte ownership checks.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
