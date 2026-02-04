# 👤 UITLEG EDIT_FRIEND.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Vrienden Beheren

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Vriendschappen veranderen, en zo ook hun online status. Deze pagina laat je die relaties updaten."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Validatie & ENUM Status**
4.  **Database Interactie (PDO)**
5.  **GIGANTISCH VRIENDEN WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Vrienden & Relatie Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 👥

De `edit_friend.php` pagina stelt een ingelogde gebruiker in staat om een bestaande vriend te wijzigen. Dit omvat:
- Het wijzigen van de naam van de vriend.
- Het updaten van de status (Offline, Online, In Game).
- Het valideren dat de invoer niet leeg is.

---

# 2. Code Analyse

```php
<?php
$friend_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Haal huidige data op
$stmt = $pdo->prepare("SELECT * FROM Friends WHERE friend_id = ? AND user_id = ?");
$stmt->execute([$friend_id, $user_id]);
$friend = $stmt->fetch();

if (!$friend) {
    header('Location: index.php?error=unauthorized');
    exit();
}
// Update query na POST ...
?>
```

- **Double Check**: De query controleert zowel `friend_id` ALS `user_id`. Zo kan een gebruiker nooit de vriend van iemand anders bewerken.
- `$stmt->fetch()`: Haalt één rij op. Als deze `false` is, bestaat de vriend niet of is hij niet van de gebruiker.

---

# 5. GIGANTISCH VRIENDEN WOORDENBOEK (50 TERMEN)

1. **Friend Entity**: Het object 'vriend' in onze database.
2. **Status ENUM**: De beperkte keuzemogelijkheden (Offline/Online/In Game).
3. **Ownership Validation**: Controleren of de vriend bij de ingelogde user hoort.
4. **Fetch**: Het ophalen van data uit een query-resultaat.
5. **Header Redirect**: Doorsturen naar een andere pagina.
6. **POST Request**: Het versturen van data via een formulier.
7. **GET Parameter**: Data meegeven via de URL (`?id=5`).
8. **Pre-Fill**: Het vooraf invullen van een formulier met bestaande data.
9. **Binding**: Het koppelen van waarden aan placeholders in PDO.
10. **Exit**: Stoppen van de PHP-uitvoering na een redirect.
11. **UPDATE Query**: SQL-commando om data te wijzigen.
12. **Friend ID**: Unieke identifier van de vriend.
13. **Friend Name**: Naam van de vriend.
14. **Friend Status**: Online/Offline/In Game status.
15. **Friends Table**: Database-tabel voor vrienden.
16. **User ID**: Koppeling naar de eigenaar.
17. **Foreign Key**: Relatie tussen tabellen.
18. **One-to-Many**: Eén user heeft meerdere friends.
19. **CRUD Operations**: Create, Read, Update, Delete.
20. **Update Operation**: De 'U' in CRUD.
21. **Form Handling**: Verwerking van formulierinvoer.
22. **Input Validation**: Controleren van invoer.
23. **Sanitization**: Invoer schoonmaken.
24. **Prepared Statement**: Veilige SQL met placeholders.
25. **Execute Method**: Uitvoeren van prepared query.
26. **Success Message**: Bevestiging na succesvolle actie.
27. **Error Message**: Melding bij validatiefout.
28. **Session Check**: Controleren of ingelogd.
29. **Authorization**: Controle of actie is toegestaan.
30. **Form Action**: URL waar formulier naartoe stuurt.
31. **Form Method**: GET of POST.
32. **Input Name**: Naam-attribuut van formulierveld.
33. **Select Dropdown**: Keuzelijst voor status.
34. **Option Element**: Keuzeoptie in dropdown.
35. **Selected Attribute**: Standaard geselecteerde optie.
36. **Label Element**: Label bij formulierveld.
37. **Submit Button**: Verzendknop.
38. **Cancel Button**: Annuleerknop.
39. **Delete Friend**: Vriend verwijderen.
40. **Block Friend**: Vriend blokkeren.
41. **Online Indicator**: Visuele status-indicator.
42. **Last Seen**: Laatst actief timestamp.
43. **Gaming Together**: Samenspelen.
44. **Invite to Game**: Uitnodiging om samen te spelen.
45. **Friend Notes**: Notities bij een vriend.
46. **Nickname**: Bijnaam voor de vriend.
47. **Friend Groups**: Groepen van vrienden.
48. **Friend Categories**: Categorieën voor vrienden.
49. **Mutual Games**: Spellen die beide hebben.
50. **HART Protocol Friends**: Veilige friends-operaties volgens HART.

---

# 6. EXAMEN TRAINING: 20 Vrienden & Relatie Vragen

1. **Vraag**: Wat is een ENUM in database-context?
   **Antwoord**: Een kolom met een beperkte set toegestane waarden.

2. **Vraag**: Waarom is de Double Check in de query belangrijk?
   **Antwoord**: Het controleert zowel friend_id als user_id voor beveiliging.

3. **Vraag**: Wat doet `(int)$_GET['id']`?
   **Antwoord**: Type casting naar integer om SQL Injection te voorkomen.

4. **Vraag**: Wat is Pre-Fill in een edit-formulier?
   **Antwoord**: Het vooraf invullen van velden met de huidige database-waarden.

5. **Vraag**: Hoe toon je de huidige status in een dropdown?
   **Antwoord**: Met `selected` attribuut op de optie die matcht met $friend['status'].

6. **Vraag**: Wat is het verschil tussen edit en delete friend?
   **Antwoord**: Edit wijzigt naam/status; delete verwijdert de hele relatie.

7. **Vraag**: Waarom is Ownership Validation cruciaal?
   **Antwoord**: Het voorkomt dat je elkaars vrienden kunt bewerken.

8. **Vraag**: Wat gebeurt als de friend niet bestaat?
   **Antwoord**: Redirect naar index.php met error=unauthorized.

9. **Vraag**: Hoe zou je Friend Groups implementeren?
   **Antwoord**: Extra tabel Groups en friend_group_id in Friends.

10. **Vraag**: Wat is Last Seen?
    **Antwoord**: Timestamp wanneer de vriend laatst actief was.

11. **Vraag**: Wat is het verschil tussen POST en GET bij edit?
    **Antwoord**: GET haalt het item op; POST verwerkt de wijzigingen.

12. **Vraag**: Hoe implementeer je een Online Indicator?
    **Antwoord**: Visuele styling gebaseerd op de status waarde.

13. **Vraag**: Wat is Nickname functionaliteit?
    **Antwoord**: Mogelijkheid om een persoonlijke bijnaam aan een vriend te geven.

14. **Vraag**: Waarom is exit() na redirect nodig?
    **Antwoord**: Het stopt verdere PHP-uitvoering.

15. **Vraag**: Wat is Invite to Game?
    **Antwoord**: Functionaliteit om een vriend uit te nodigen voor een game-sessie.

16. **Vraag**: Hoe zou je Friend Notes implementeren?
    **Antwoord**: Extra tekstkolom 'notes' in de Friends tabel.

17. **Vraag**: Wat is het PRG-pattern?
    **Antwoord**: Post-Redirect-Get voor het voorkomen van dubbele submits.

18. **Vraag**: Hoe zou je Mutual Games tonen?
    **Antwoord**: Query die favorites van beide users vergelijkt.

19. **Vraag**: Wat is Block Friend functionaliteit?
    **Antwoord**: Status wijzigen naar 'blocked' of vlagkolom toevoegen.

20. **Vraag**: Wat is HART Protocol Friends?
    **Antwoord**: Veilige friends-operaties met ownership checks en prepared statements.

---

# 7. Conclusie

De `edit_friend.php` is een schoolvoorbeeld van veilige UPDATE-logica met ownership checks en ENUM-validatie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
