# ❤️ UITLEG EDIT_FAVORITE.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Favoriete Spellen Beheren

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Een gamer zonder favoriete spellen is als een developer zonder koffie. Deze pagina laat gebruikers hun passies beheren."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Validatie & Beveiliging**
4.  **Database Interactie (PDO)**
5.  **GIGANTISCH FAVORIETEN WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Favorieten & CRUD Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🎮

De `edit_favorite.php` pagina stelt een ingelogde gebruiker in staat om een bestaand favoriet spel te wijzigen. Dit omvat:
- Het ophalen van de huidige spelnaam uit de database.
- Het tonen van een voorgevuld formulier.
- Het valideren en updaten van de nieuwe naam.

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

$game_id = (int)$_GET['id'];
// Ownership check: Is dit spel van de ingelogde gebruiker?
// ... Update query met PDO Prepared Statement ...
?>
```

- `session_start()`: Start de sessie om te controleren of de gebruiker is ingelogd.
- `(int)$_GET['id']`: **Type Casting**. Dwingt af dat de ID een integer is, wat SQL Injection via dit veld onmogelijk maakt.
- **Ownership Check**: Essentieel! Voorkomt dat gebruiker A het spel van gebruiker B kan bewerken.

---

# 5. GIGANTISCH FAVORIETEN WOORDENBOEK (50 TERMEN)

1. **CRUD**: Create, Read, Update, Delete.
2. **Update**: Het wijzigen van bestaande data in de database.
3. **PDO**: PHP Data Objects voor veilige database-interactie.
4. **Prepared Statement**: Een SQL-query met placeholders voor veiligheid.
5. **Ownership Check**: Controleren of de gebruiker rechten heeft op de data.
6. **Type Casting**: Omzetten van een variabele naar een specifiek type (bijv. `(int)`).
7. **Session**: Een manier om gebruikersdata te bewaren tussen pagina's.
8. **Redirect**: Doorsturen naar een andere pagina via `header('Location: ...')`.
9. **Pre-filled Form**: Een formulier dat al bestaande waarden toont.
10. **Validation**: Controleren of de invoer geldig is.
11. **Favorite Game**: Favoriete spel van de gebruiker.
12. **Favorites Table**: Database-tabel voor favorieten.
13. **Game ID**: Unieke identifier van het spel.
14. **Game Name**: Naam van het spel.
15. **Game Genre**: Genre van het spel.
16. **Game Platform**: Platform waarop het spel speelt.
17. **Game Rating**: Beoordeling van het spel.
18. **User Preference**: Voorkeuren van de gebruiker.
19. **Gaming Profile**: Profiel met speelvoorkeuren.
20. **Foreign Key**: Koppeling naar Users tabel.
21. **One-to-Many**: Eén user kan meerdere favorites hebben.
22. **Form Handling**: Verwerking van formulierinvoer.
23. **Input Validation**: Controleren van invoer.
24. **Sanitization**: Invoer schoonmaken.
25. **Execute Method**: Uitvoeren van prepared query.
26. **Bind Parameters**: Parameters aan query koppelen.
27. **Success Message**: Bevestiging na succesvolle actie.
28. **Error Message**: Melding bij validatiefout.
29. **Session Check**: Controleren of ingelogd.
30. **Authorization**: Controle of actie is toegestaan.
31. **Form Action**: URL waar formulier naartoe stuurt.
32. **Form Method**: GET of POST.
33. **Input Name**: Naam-attribuut van formulierveld.
34. **Text Input**: Tekstinvoerveld.
35. **Submit Button**: Verzendknop.
36. **Cancel Button**: Annuleerknop.
37. **Form Reset**: Formulier leegmaken.
38. **Required Field**: Verplicht veld.
39. **Delete Favorite**: Favoriet verwijderen.
40. **Add Favorite**: Favoriet toevoegen.
41. **Favorite List**: Lijst van favorieten.
42. **Sort Order**: Sorteervolgorde.
43. **Display Order**: Weergavevolgorde.
44. **Favorite Count**: Aantal favorieten.
45. **Top Games**: Meest gekozen spellen.
46. **Recent Games**: Recent toegevoegde spellen.
47. **Game Search**: Zoeken naar spellen.
48. **Game Cover**: Afbeelding van het spel.
49. **Game Description**: Beschrijving van het spel.
50. **HART Protocol Favorites**: Veilige favorites volgens HART.

---

# 6. EXAMEN TRAINING: 20 Favorieten & CRUD Vragen

1. **Vraag**: Wat is CRUD?
   **Antwoord**: Create, Read, Update, Delete - de vier basis database-operaties.

2. **Vraag**: Waarom is Ownership Check belangrijk bij edit?
   **Antwoord**: Het voorkomt dat gebruikers elkaars favorieten kunnen bewerken.

3. **Vraag**: Wat doet `(int)$_GET['id']`?
   **Antwoord**: Type casting naar integer om SQL Injection te voorkomen.

4. **Vraag**: Wat is een Pre-filled Form?
   **Antwoord**: Een formulier dat al de huidige waarden van het item toont.

5. **Vraag**: Hoe haal je de huidige game-naam op?
   **Antwoord**: SELECT query met game_id en user_id.

6. **Vraag**: Wat is het verschil tussen Favorites en Friends tabellen?
   **Antwoord**: Favorites slaat spellen op; Friends slaat contacten op.

7. **Vraag**: Waarom staat user_id in de WHERE-clausule?
   **Antwoord**: Extra beveiliging: alleen eigen favorieten kunnen worden bewerkt.

8. **Vraag**: Wat gebeurt na een succesvolle update?
   **Antwoord**: Redirect naar de favorieten-pagina met success parameter.

9. **Vraag**: Wat is Input Validation bij games?
   **Antwoord**: Controleren dat de game-naam niet leeg is en geldig formaat heeft.

10. **Vraag**: Wat is het verschil tussen edit en delete favorite?
    **Antwoord**: Edit wijzigt de naam; delete verwijdert het hele item.

11. **Vraag**: Hoe zou je Game Genre implementeren?
    **Antwoord**: Extra kolom in Favorites of aparte Genres tabel.

12. **Vraag**: Wat is Sort Order bij favorieten?
    **Antwoord**: De volgorde waarin favorieten worden weergegeven.

13. **Vraag**: Wat is een Foreign Key bij Favorites?
    **Antwoord**: De user_id kolom die refereert naar Users.user_id.

14. **Vraag**: Hoe zou je Game Cover implementeren?
    **Antwoord**: Extra kolom voor image URL of path.

15. **Vraag**: Wat is een One-to-Many relatie bij favorites?
    **Antwoord**: Eén user kan meerdere favorites hebben.

16. **Vraag**: Waarom is exit() na redirect belangrijk?
    **Antwoord**: Het stopt verdere PHP-uitvoering om data-lekken te voorkomen.

17. **Vraag**: Wat is het PRG-pattern?
    **Antwoord**: Post-Redirect-Get: redirect na POST om dubbele submits te voorkomen.

18. **Vraag**: Hoe zou je Top Games berekenen?
    **Antwoord**: COUNT query op game_name, GROUP BY, ORDER BY DESC.

19. **Vraag**: Wat is Game Search?
    **Antwoord**: Zoekfunctionaliteit om spellen te vinden (bijv. via LIKE query).

20. **Vraag**: Wat is HART Protocol Favorites?
    **Antwoord**: Veilige favorites-operaties met ownership checks en prepared statements.

---

# 7. Conclusie

De `edit_favorite.php` toont hoe we CRUD-logica combineren met strikte beveiligingsmaatregelen (sessiecontrole, ownership check, PDO).

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
