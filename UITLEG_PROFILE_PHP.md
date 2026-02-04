# 👤 UITLEG PROFILE.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Gebruikersprofiel

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De persoonlijke ruimte van elke gamer: profielinformatie en statistieken."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Sessie-Afhankelijke Content**
4.  **Statistieken & Samenvattingen**
5.  **GIGANTISCH PROFILE WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Profile & Session Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🎭

De `profile.php` pagina toont informatie over de ingelogde gebruiker:
- Naam en email-adres (opgehaald uit de sessie/database).
- Statistieken zoals aantal vrienden, favoriete spellen en geplande events.
- Links naar het bewerken van het profiel (toekomstige feature).

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
$stmt = $pdo->prepare("SELECT username, email, created_at FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Tel statistieken
$friendCount = $pdo->query("SELECT COUNT(*) FROM Friends WHERE user_id = $user_id")->fetchColumn();
$eventCount = $pdo->query("SELECT COUNT(*) FROM Schedules WHERE user_id = $user_id")->fetchColumn();
?>
```

- **Sessie Check**: Zonder sessie geen profiel.
- `fetch()`: Haalt de gebruikersdata op.
- `COUNT(*)`: SQL-aggregatiefunctie om aantallen te tellen.

---

# 5. GIGANTISCH PROFILE WOORDENBOEK (50 TERMEN)

1. **Profile Page**: De persoonlijke pagina van de gebruiker.
2. **Session**: Server-side opslag van gebruikersdata.
3. **User ID**: De unieke identifier van de gebruiker.
4. **Aggregation**: Het combineren van data voor statistieken.
5. **COUNT()**: SQL-functie om rijen te tellen.
6. **Fetch**: Het ophalen van data uit een query.
7. **FetchColumn()**: Het ophalen van een enkele waarde.
8. **Created At**: Het moment van accountcreatie.
9. **Statistics**: Samenvattende cijfers over de gebruiker.
10. **Avatar**: Een profielafbeelding (toekomstige feature).
11. **Username Display**: Het tonen van de gebruikersnaam.
12. **Email Display**: Het tonen van het emailadres.
13. **Member Since**: Hoe lang iemand lid is.
14. **Account Age**: De leeftijd van het account.
15. **Friend Statistics**: Aantal vrienden van de gebruiker.
16. **Event Statistics**: Aantal events van de gebruiker.
17. **Game Statistics**: Aantal favoriete spellen.
18. **Edit Profile Link**: Knop om profiel te bewerken.
19. **Change Password**: Wachtwoord wijzigen functionaliteit.
20. **Privacy Settings**: Instellingen voor privacy.
21. **Notification Preferences**: Voorkeuren voor meldingen.
22. **Account Deletion**: Mogelijkheid om account te verwijderen.
23. **Data Export**: GDPR-compliant data export.
24. **Bio/Description**: Persoonlijke beschrijving.
25. **Social Links**: Links naar sociale media.
26. **Gaming Preferences**: Voorkeuren voor game-types.
27. **Availability Status**: Online/offline status.
28. **Timezone Setting**: Tijdzone van de gebruiker.
29. **Language Preference**: Taalvoorkeur.
30. **Profile Visibility**: Publiek/privé profiel.
31. **Dashboard Widget**: Profielwidget op dashboard.
32. **Prepared Statement**: Veilige database query.
33. **SELECT Query**: SQL voor data ophalen.
34. **WHERE Clause**: Filter in SQL queries.
35. **Session Authentication**: Controle of gebruiker ingelogd is.
36. **Redirect on Fail**: Doorsturen bij geen sessie.
37. **Header Include**: Navigatiebalk invoegen.
38. **Footer Include**: Voettekst invoegen.
39. **Glassmorphism Card**: Profielkaart met glaseffect.
40. **Responsive Layout**: Aanpasbare layout.
41. **Mobile View**: Mobiele weergave.
42. **Desktop View**: Desktop weergave.
43. **User Info Section**: Sectie met gebruikersinfo.
44. **Stats Dashboard**: Overzicht van statistieken.
45. **Quick Actions**: Snelle acties op profiel.
46. **Last Login**: Laatste inlogtijd.
47. **Account Status**: Actief/inactief account.
48. **Email Verification**: Verificatie van emailadres.
49. **Two-Factor Auth**: Extra beveiligingslaag.
50. **HART Protocol**: Beveiligingsstandaard.

---

# 6. EXAMEN TRAINING: 20 Profile & Session Vragen

1. **Vraag**: Waarom controleren we `isset($_SESSION['user_id'])` op de profielpagina?
   **Antwoord**: Omdat alleen ingelogde gebruikers hun profiel mogen zien.

2. **Vraag**: Wat is het verschil tussen `fetch()` en `fetchColumn()`?
   **Antwoord**: fetch() haalt een hele rij op, fetchColumn() haalt één waarde op.

3. **Vraag**: Waarom gebruiken we COUNT(*) voor statistieken?
   **Antwoord**: Het is een efficiënte SQL-aggregatiefunctie die rijen telt zonder alle data op te halen.

4. **Vraag**: Waarom is een profielpagina belangrijk voor UX?
   **Antwoord**: Het geeft gebruikers controle over hun data en een gevoel van eigenaarschap.

5. **Vraag**: Hoe zou je een "Change Password" functie implementeren?
   **Antwoord**: Verificatie van huidig wachtwoord, nieuw wachtwoord hashen, UPDATE in database.

6. **Vraag**: Wat is GDPR-compliant data export?
   **Antwoord**: De mogelijkheid voor gebruikers om al hun data te downloaden.

7. **Vraag**: Waarom tonen we `created_at` op het profiel?
   **Antwoord**: Het toont hoe lang iemand lid is, wat vertrouwen kan wekken.

8. **Vraag**: Hoe zou je een avatar-upload implementeren?
   **Antwoord**: Bestandsupload, validatie van type/grootte, opslaan in uploads-map.

9. **Vraag**: Wat is Profile Visibility?
   **Antwoord**: De instelling of anderen je profiel kunnen zien.

10. **Vraag**: Waarom is account deletion een GDPR-vereiste?
    **Antwoord**: Gebruikers hebben het recht om vergeten te worden (Right to be Forgotten).

11. **Vraag**: Hoe zou je email verificatie implementeren?
    **Antwoord**: Token genereren, email sturen, verificatie-link, database update.

12. **Vraag**: Wat is Two-Factor Authentication?
    **Antwoord**: Extra beveiligingslaag met code via telefoon of app.

13. **Vraag**: Waarom is een prepared statement belangrijk voor de profile query?
    **Antwoord**: Het voorkomt SQL Injection, ook al komt user_id uit de sessie.

14. **Vraag**: Hoe zou je een "Last Login" timestamp implementeren?
    **Antwoord**: UPDATE query in de login.php na succesvolle authenticatie.

15. **Vraag**: Wat is de rol van statistieken op het profiel?
    **Antwoord**: Ze geven gebruikers inzicht in hun activiteit en betrokkenheid.

16. **Vraag**: Waarom is Responsive Layout belangrijk voor profielen?
    **Antwoord**: Gebruikers bekijken hun profiel op verschillende apparaten.

17. **Vraag**: Wat is een Social Link op een profiel?
    **Antwoord**: Een link naar de sociale media van de gebruiker.

18. **Vraag**: Hoe bescherm je tegen unauthorized profile access?
    **Antwoord**: Session check en ownership validation.

19. **Vraag**: Wat is Account Status?
    **Antwoord**: Indicator of een account actief, inactief, of verbannen is.

20. **Vraag**: Waarom is Timezone Setting belangrijk?
    **Antwoord**: Voor correcte weergave van datums en tijden aan de gebruiker.

---

# 7. Conclusie

De `profile.php` biedt een overzichtelijke weergave van de gebruikersstatus en statistieken.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
