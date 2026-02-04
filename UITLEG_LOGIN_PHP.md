# 🔑 UITLEG LOGIN.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Veilige Authenticatie

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De poortwachter van de applicatie: authenticatie via Bcrypt verificatie."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **password_verify() Deep Dive**
4.  **Session Creation & Security**
5.  **GIGANTISCH LOGIN WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Login & Authenticatie Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🔐

De `login.php` pagina handelt de authenticatie van bestaande gebruikers af:
- Invoer van email en wachtwoord.
- Verificatie tegen de database via `password_verify()`.
- Aanmaken van een sessie bij succesvolle login.

---

# 2. Code Analyse

```php
<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT user_id, password_hash FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // HART Protocol - Authentication
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true); // Security: prevent session fixation
        $_SESSION['user_id'] = $user['user_id'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Ongeldige email of wachtwoord.";
    }
}
?>
```

- `password_verify()`: Vergelijkt het ingevoerde wachtwoord met de opgeslagen hash.
- `session_regenerate_id(true)`: HART Protocol - Resistance tegen session fixation.
- **Vage Foutmelding**: "Ongeldige email of wachtwoord" geeft geen aanwijzingen over welk veld fout is.

---

# 5. GIGANTISCH LOGIN WOORDENBOEK (50 TERMEN)

1. **Authentication**: Het verifiëren van de identiteit van een gebruiker.
2. **password_verify()**: PHP-functie om wachtwoord te checken tegen hash.
3. **Session**: Server-side opslag voor gebruikersdata.
4. **Session Regeneration**: Het vervangen van de sessie-ID na login.
5. **Session Fixation**: Een aanval waarbij de sessie-ID vooraf wordt bepaald.
6. **Brute Force Attack**: Aanval waarbij alle wachtwoorden worden geprobeerd.
7. **Rate Limiting**: Het beperken van login-pogingen.
8. **Two-Factor Authentication (2FA)**: Extra beveiligingslaag (toekomstig).
9. **Credential**: Combinatie van email en wachtwoord.
10. **Prepared Statement**: Veilige SQL met placeholders.
11. **Session Hijacking**: Het stelen van een actieve sessie-ID.
12. **Cookie**: Klein bestandje dat de sessie-ID bevat.
13. **HttpOnly Flag**: Voorkomt JavaScript-toegang tot cookies.
14. **Secure Flag**: Cookie alleen via HTTPS.
15. **SameSite Flag**: Voorkomt CSRF via cookies.
16. **CSRF (Cross-Site Request Forgery)**: Aanval via neppe requests.
17. **Token**: Unieke waarde voor authenticatie.
18. **Bcrypt Verification**: Het controleren van de hash.
19. **Timing Attack**: Aanval die meet hoe lang een check duurt.
20. **Constant-Time Comparison**: Vergelijkingen die altijd even lang duren.
21. **Error Message Obfuscation**: Vage foutmeldingen om aanvallers te misleiden.
22. **Login Attempt Logging**: Bijhouden van login-pogingen.
23. **Account Lockout**: Account blokkeren na te veel pogingen.
24. **GeoIP Check**: Locatie-gebaseerde beveiliging.
25. **Device Fingerprinting**: Toestel herkennen voor beveiliging.
26. **Remember Me Token**: Persistente inlog-cookie.
27. **Session Expiration**: Automatisch uitloggen na inactiviteit.
28. **session_start()**: PHP-functie om sessie te starten.
29. **$_SESSION superglobal**: Array voor sessie-data.
30. **session_destroy()**: Sessie volledig vernietigen.
31. **Header Redirect**: Doorsturen via HTTP header.
32. **Exit Statement**: Stoppen van PHP na redirect.
33. **POST Method**: HTTP-methode voor veilige data-overdracht.
34. **Form Submission**: Het verzenden van een formulier.
35. **Input Validation**: Controleren van invoer.
36. **SQL Injection Prevention**: Bescherming via prepared statements.
37. **PDO Execute**: Het uitvoeren van een query.
38. **PDO Fetch**: Het ophalen van resultaten.
39. **User Table**: De database-tabel met gebruikers.
40. **password_hash Column**: Kolom met gehashte wachtwoorden.
41. **email Column**: Kolom met email-adressen.
42. **UNIQUE Constraint**: Voorkomt duplicate emails.
43. **Require Once**: Include bestand slechts éénmaal.
44. **require_once 'db.php'**: Database-connectie laden.
45. **require_once 'functions.php'**: Functies laden.
46. **Boolean Check**: True/false logica.
47. **AND Operator**: Beide condities moeten waar zijn.
48. **Associative Array Fetch**: Data ophalen als key-value pairs.
49. **HART Protocol Integration**: Hashing, Authentication, Resistance, Transport.
50. **Security Best Practices**: Industriestandaard beveiligingsregels.

---

# 6. EXAMEN TRAINING: 20 Login & Authenticatie Vragen

1. **Vraag**: Wat doet `password_verify()` precies?
   **Antwoord**: Het vergelijkt een plat wachtwoord met een Bcrypt hash en retourneert true/false.

2. **Vraag**: Waarom gebruiken we `session_regenerate_id(true)` na login?
   **Antwoord**: Om Session Fixation attacks te voorkomen door een nieuwe sessie-ID te genereren.

3. **Vraag**: Wat is een Session Fixation attack?
   **Antwoord**: Een aanval waarbij de aanvaller het sessie-ID vooraf bepaalt en wacht tot het slachtoffer inlogt.

4. **Vraag**: Waarom tonen we "Ongeldige email of wachtwoord" in plaats van "Email niet gevonden"?
   **Antwoord**: Om aanvallers geen aanwijzingen te geven welke emails bestaan in het systeem.

5. **Vraag**: Wat is het verschil tussen Authentication en Authorization?
   **Antwoord**: Authentication = wie ben je? Authorization = wat mag je doen?

6. **Vraag**: Hoe voorkom je Brute Force attacks?
   **Antwoord**: Via rate limiting, CAPTCHA, account lockout, en/of progressieve vertraging.

7. **Vraag**: Wat doet de HttpOnly flag op een cookie?
   **Antwoord**: Het voorkomt dat JavaScript de cookie kan lezen, wat XSS-aanvallen beperkt.

8. **Vraag**: Waarom is `exit()` na een `header('Location: ...')` belangrijk?
   **Antwoord**: Zonder exit() blijft PHP doorgaan met uitvoeren, wat kan leiden tot informatielekkage.

9. **Vraag**: Wat is Session Hijacking?
   **Antwoord**: Het stelen van een actieve sessie-ID om zich voor te doen als het slachtoffer.

10. **Vraag**: Hoe beschermt HTTPS tegen Session Hijacking?
    **Antwoord**: Het versleutelt alle data, inclusief de sessie-cookie, zodat deze niet kan worden onderschept.

11. **Vraag**: Wat is een Timing Attack?
    **Antwoord**: Een aanval die meet hoe lang een password-check duurt om te raden of een deel klopt.

12. **Vraag**: Hoe werkt `password_verify()` tegen Timing Attacks?
    **Antwoord**: Het gebruikt constant-time comparison, dus de check duurt altijd even lang.

13. **Vraag**: Wat is Two-Factor Authentication (2FA)?
    **Antwoord**: Een extra beveiligingslaag waarbij je naast wachtwoord ook een code van je telefoon nodig hebt.

14. **Vraag**: Waarom is session timeout belangrijk?
    **Antwoord**: Het logt gebruikers automatisch uit na inactiviteit, wat risico's bij gedeelde computers beperkt.

15. **Vraag**: Wat is het verschil tussen session_destroy() en session_unset()?
    **Antwoord**: session_unset() wist de variabelen, session_destroy() vernietigt de hele sessie.

16. **Vraag**: Hoe wordt de sessie-ID normaal opgeslagen?
    **Antwoord**: In een cookie genaamd PHPSESSID (standaard configuratie).

17. **Vraag**: Wat is de SameSite cookie attribute?
    **Antwoord**: Een flag die voorkomt dat de cookie wordt meegestuurd bij cross-site requests (CSRF preventie).

18. **Vraag**: Wanneer zou je "Remember Me" functionaliteit implementeren?
    **Antwoord**: Voor gebruikersgemak, maar met een separaat veilig token, niet door de sessie te verlengen.

19. **Vraag**: Wat is de rol van PDO in de login?
    **Antwoord**: Het biedt een veilige manier om de database te bevragen met prepared statements.

20. **Vraag**: Waarom halen we alleen user_id en password_hash op in de query?
    **Antwoord**: Data minimization: we halen alleen wat we nodig hebben voor de authenticatie.

---

# 7. Conclusie

De `login.php` is een schoolvoorbeeld van veilige authenticatie met password_verify() en session regeneration.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
