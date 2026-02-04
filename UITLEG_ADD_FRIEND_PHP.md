# ➕ UITLEG ADD_FRIEND.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Nieuwe Vrienden Toevoegen

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De eerste stap naar een bloeiende gamingcommunity: het toevoegen van vrienden."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **ENUM Status & Default Values**
4.  **Database Interactie (INSERT met PDO)**
5.  **GIGANTISCH ADD_FRIEND WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 CREATE & INSERT Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 👤

De `add_friend.php` pagina stelt een ingelogde gebruiker in staat om een nieuwe vriend toe te voegen aan zijn lijst. Dit omvat:
- Het invoeren van de naam van de vriend.
- Het selecteren van een status (Offline, Online, In Game).
- Het koppelen van de vriend aan de ingelogde `user_id`.

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
    $friend_name = trim($_POST['friend_name']);
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];

    if (empty($friend_name)) {
        $error = "Naam mag niet leeg zijn.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_name, status) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $friend_name, $status]);
        header('Location: index.php?success=friend_added');
        exit();
    }
}
?>
```

- `$_SERVER['REQUEST_METHOD'] === 'POST'`: Controleert of het formulier is verzonden.
- `trim($_POST['friend_name'])`: Verwijdert spaties om lege invoer te voorkomen.
- **INSERT Query**: Voegt een nieuwe rij toe aan de `Friends` tabel.

---

# 5. GIGANTISCH ADD_FRIEND WOORDENBOEK (50 TERMEN)

1. **INSERT Query**: SQL-commando om nieuwe data toe te voegen.
2. **POST Method**: HTTP-methode voor het verzenden van formulierdata.
3. **Session**: Data die bewaard blijft tussen pagina's.
4. **User ID**: De unieke identificatie van de ingelogde gebruiker.
5. **ENUM**: Een beperkte set toegestane waarden voor een kolom.
6. **Default Value**: De waarde die automatisch wordt ingevuld als er niets wordt meegegeven.
7. **Trim Function**: Verwijdert witruimte aan begin en einde.
8. **Empty Check**: Controleert of een waarde leeg is.
9. **Redirect**: Doorsturen naar een andere pagina na een succesvolle actie.
10. **Exit**: Stopt verdere PHP-uitvoering na een redirect.
11. **Friend Entity**: Het object 'vriend' in de database.
12. **Friend Name**: De naam van de toegevoegde vriend.
13. **Friend Status**: Online/Offline/In Game status.
14. **Friends Table**: Database-tabel voor vriendenlijsten.
15. **Foreign Key**: Koppeling naar Users tabel.
16. **Relationship**: Relatie tussen entiteiten.
17. **One-to-Many**: Eén user kan meerdere friends hebben.
18. **Form Handling**: Verwerking van formulierinvoer.
19. **Input Validation**: Controleren van invoer.
20. **Sanitization**: Invoer schoonmaken.
21. **Prepared Statement**: Veilige SQL met placeholders.
22. **Execute Method**: Uitvoeren van prepared query.
23. **Bind Parameters**: Parameters aan query koppelen.
24. **Success Message**: Bevestiging na succesvolle actie.
25. **Error Message**: Melding bij validatiefout.
26. **Session Check**: Controleren of ingelogd.
27. **Authorization**: Controle of actie is toegestaan.
28. **Form Action**: URL waar formulier naartoe stuurt.
29. **Form Method**: GET of POST.
30. **Input Name**: Naam-attribuut van formulierveld.
31. **Select Dropdown**: Keuzelijst voor status.
32. **Option Element**: Keuzeoptie in dropdown.
33. **Selected Attribute**: Standaard geselecteerde optie.
34. **Label Element**: Label bij formulierveld.
35. **Submit Button**: Verzendknop.
36. **Cancel Button**: Annuleerknop.
37. **Form Reset**: Formulier leegmaken.
38. **Required Field**: Verplicht veld.
39. **Optional Field**: Optioneel veld.
40. **Friend Request**: Vriendschapsverzoek (toekomstig).
41. **Accept/Decline**: Verzoek accepteren/weigeren.
42. **Block User**: Gebruiker blokkeren.
43. **Unfriend**: Vriendschap beëindigen.
44. **Mutual Friends**: Gemeenschappelijke vrienden.
45. **Friend Suggestions**: Vriendschapsssuggesties.
46. **Gaming Buddy**: Speelmaatje.
47. **Last Seen**: Laatst gezien.
48. **Online Status**: Online indicator.
49. **Notification**: Melding bij nieuwe vriend.
50. **HART Protocol CREATE**: Veilige CREATE volgens HART.

---

# 6. EXAMEN TRAINING: 20 CREATE & INSERT Vragen

1. **Vraag**: Waarom gebruiken we `trim()` op de friend_name?
   **Antwoord**: Om spaties aan begin en einde te verwijderen en lege namen te detecteren.

2. **Vraag**: Wat is een ENUM in database-context?
   **Antwoord**: Een kolom met een beperkte set toegestane waarden (bijv. Online, Offline).

3. **Vraag**: Waarom koppelen we user_id aan de friend?
   **Antwoord**: Zodat elke gebruiker zijn eigen vriendenlijst heeft.

4. **Vraag**: Wat is het voordeel van een prepared statement?
   **Antwoord**: Het voorkomt SQL Injection aanvallen.

5. **Vraag**: Wat doet de session check aan het begin?
   **Antwoord**: Het zorgt dat alleen ingelogde gebruikers vrienden kunnen toevoegen.

6. **Vraag**: Wat gebeurt er na een succesvolle INSERT?
   **Antwoord**: De gebruiker wordt doorgestuurd naar index.php met een succesmelding.

7. **Vraag**: Waarom is `exit()` na redirect belangrijk?
   **Antwoord**: Het stopt verdere PHP-uitvoering om data-lekken te voorkomen.

8. **Vraag**: Wat is het verschil tussen POST en GET voor forms?
   **Antwoord**: POST is veiliger voor data; GET toont data in de URL.

9. **Vraag**: Wat is een Foreign Key?
   **Antwoord**: Een kolom die refereert naar de primary key van een andere tabel.

10. **Vraag**: Wat is One-to-Many relatie?
    **Antwoord**: Eén user kan meerdere friends hebben.

11. **Vraag**: Wat is een Select Dropdown?
    **Antwoord**: Een formulier-element voor het kiezen uit een lijst opties.

12. **Vraag**: Wat is een Default Value?
    **Antwoord**: De waarde die automatisch wordt gebruikt als geen waarde wordt opgegeven.

13. **Vraag**: Hoe zou je Friend Suggestions implementeren?
    **Antwoord**: Query voor friends van friends die je nog niet hebt toegevoegd.

14. **Vraag**: Wat is het verschil tussen Unfriend en Block?
    **Antwoord**: Unfriend verwijdert alleen; Block voorkomt ook toekomstige interactie.

15. **Vraag**: Wat is Mutual Friends?
    **Antwoord**: Vrienden die twee gebruikers gemeenschappelijk hebben.

16. **Vraag**: Waarom is input validation belangrijk?
    **Antwoord**: Het voorkomt lege, ongeldige of kwaadaardige invoer.

17. **Vraag**: Wat is de rol van het Label element?
    **Antwoord**: Het verbetert toegankelijkheid door veld en label te koppelen.

18. **Vraag**: Hoe zou je Friend Request implementeren?
    **Antwoord**: Extra tabel met pending status, accept/decline functionaliteit.

19. **Vraag**: Wat is Last Seen?
    **Antwoord**: De timestamp wanneer een gebruiker laatst actief was.

20. **Vraag**: Wat is HART Protocol CREATE?
    **Antwoord**: Veilige data-creatie met validatie, prepared statements en session checks.

---

# 7. Conclusie

De `add_friend.php` is een schoolvoorbeeld van veilige CREATE-logica met invoervalidatie en sessie-koppeling.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
