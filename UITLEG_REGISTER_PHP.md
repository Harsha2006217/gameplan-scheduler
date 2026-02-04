# 📝 UITLEG REGISTER.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Nieuwe Accounts Aanmaken

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De eerste stap in elk gebruikersavontuur: veilige registratie met Bcrypt hashing."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Bcrypt Password Hashing**
4.  **Duplicate Email Prevention**
5.  **GIGANTISCH REGISTER WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Registratie & Hashing Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🔐

De `register.php` pagina laat nieuwe gebruikers een account aanmaken. Dit omvat:
- Invoer van naam, email en wachtwoord.
- Validatie dat alle velden gevuld zijn.
- Controle dat het email-adres nog niet in gebruik is.
- Veilige opslag van het wachtwoord via **Bcrypt hashing**.

---

# 2. Code Analyse

```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validatie
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Alle velden zijn verplicht.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Ongeldig email-adres.";
    } else {
        // Check duplicate
        $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Dit email is al in gebruik.";
        } else {
            // Bcrypt hashing (HART Protocol - H)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);
            header('Location: login.php?registered=1');
            exit();
        }
    }
}
?>
```

- `password_hash(..., PASSWORD_BCRYPT)`: De gouden standaard voor wachtwoord-hashing.
- `filter_var(..., FILTER_VALIDATE_EMAIL)`: Controleert email-formaat.

---

# 5. GIGANTISCH REGISTER WOORDENBOEK (50 TERMEN)

1. **Registration**: Het proces van account-aanmaak.
2. **Bcrypt**: Een veilig, traag hashing-algoritme.
3. **Password Hash**: Een onleesbare versie van het wachtwoord.
4. **Salt**: Willekeurige data toegevoegd aan de hash voor uniciteit.
5. **Duplicate Check**: Controleren of de email al bestaat.
6. **filter_var()**: PHP-functie voor validatie.
7. **FILTER_VALIDATE_EMAIL**: Filter voor email-validatie.
8. **Trim**: Verwijdert spaties aan begin en einde.
9. **INSERT Query**: SQL-commando voor nieuwe data.
10. **Prepared Statement**: Veilige SQL met placeholders.
11. **Cost Factor**: De traagheid van Bcrypt (standaard 10).
12. **One-Way Hashing**: Hashing kan niet worden teruggedraaid.
13. **Rainbow Table**: Een tabel met voorberekende hashes voor aanvallen.
14. **Brute Force Attack**: Alle mogelijke wachtwoorden proberen.
15. **Dictionary Attack**: Veelgebruikte wachtwoorden proberen.
16. **POST Request**: HTTP-methode voor formulier-verzending.
17. **Form Action**: De URL waar het formulier naartoe stuurt.
18. **Input Sanitization**: Invoer schoonmaken tegen aanvallen.
19. **Validation**: Controleren of invoer aan regels voldoet.
20. **Server-Side Validation**: Validatie op de PHP-server.
21. **Client-Side Validation**: Validatie in de browser (JavaScript).
22. **UNIQUE Constraint**: Database-regel die duplicaten voorkomt.
23. **Email Verification**: Controleren of een email echt bestaat (optioneel).
24. **Confirmation Password**: Wachtwoord-herhalingsveld.
25. **Password Strength**: Hoe sterk een wachtwoord is.
26. **Minimum Length**: Minimale lengte van een wachtwoord.
27. **Special Characters**: Speciale tekens in een wachtwoord.
28. **Session Creation**: Het aanmaken van een sessie na registratie.
29. **Redirect**: Doorsturen naar een andere pagina.
30. **Exit Statement**: Stoppen van PHP-uitvoering na redirect.
31. **Success Message**: Feedback bij succesvolle registratie.
32. **Error Handling**: Het afhandelen van fouten.
33. **User Entity**: Het object 'gebruiker' in de database.
34. **Created At**: Het moment van accountcreatie.
35. **Auto Increment**: Automatisch doortelend ID.
36. **Primary Key**: De unieke sleutel van een rij.
37. **VARCHAR**: Tekst met variabele lengte.
38. **NOT NULL**: Kolom mag niet leeg zijn.
39. **Database Normalization**: Het optimaliseren van de database-structuur.
40. **3NF**: Derde Normaalvorm.
41. **Data Integrity**: De betrouwbaarheid van data.
42. **Referential Integrity**: Consistentie tussen tabellen.
43. **PDO**: PHP Data Objects voor database-interactie.
44. **DSN**: Data Source Name.
45. **Execute Method**: Het uitvoeren van een prepared statement.
46. **Fetch Method**: Het ophalen van resultaten.
47. **ERRMODE_EXCEPTION**: PDO gooit exceptions bij fouten.
48. **Try-Catch Block**: Error handling structuur.
49. **EMULATE_PREPARES**: Moet uitstaan voor echte prepared statements.
50. **HART Protocol**: Hashing, Authentication, Resistance, Transport.

---

# 6. EXAMEN TRAINING: 20 Registratie & Hashing Vragen

1. **Vraag**: Waarom gebruiken we Bcrypt in plaats van MD5 of SHA1?
   **Antwoord**: Bcrypt is traag by design, wat brute force aanvallen onpraktisch maakt. MD5/SHA1 zijn te snel en kwetsbaar.

2. **Vraag**: Wat is een Salt en waarom is het belangrijk?
   **Antwoord**: Een Salt is willekeurige data toegevoegd aan het wachtwoord voor hashing, wat Rainbow Table aanvallen voorkomt.

3. **Vraag**: Hoe voorkom je duplicate email-adressen in de database?
   **Antwoord**: Via een UNIQUE constraint op de email-kolom EN een check in de PHP-code voor betere foutafhandeling.

4. **Vraag**: Wat doet `filter_var($email, FILTER_VALIDATE_EMAIL)`?
   **Antwoord**: Het controleert of de email een geldig formaat heeft (bijv. naam@domein.nl).

5. **Vraag**: Waarom is server-side validatie belangrijker dan client-side?
   **Antwoord**: Client-side validatie kan worden omzeild; de server is de definitieve poortwachter.

6. **Vraag**: Wat is het verschil tussen Hashing en Encryptie?
   **Antwoord**: Hashing is one-way (niet terug te draaien), encryptie is two-way (kan worden ontsleuteld).

7. **Vraag**: Wat is de standaard cost factor van Bcrypt?
   **Antwoord**: 10. Dit kan worden verhoogd voor meer veiligheid, maar maakt hashing trager.

8. **Vraag**: Hoe werkt `password_hash()` in PHP?
   **Antwoord**: Het genereert automatisch een salt en slaat deze op samen met de hash.

9. **Vraag**: Waarom gebruiken we `trim()` op invoervelden?
   **Antwoord**: Om spaties aan het begin en einde te verwijderen en lege invoer te detecteren.

10. **Vraag**: Wat is het HART Protocol?
    **Antwoord**: Hashing (Bcrypt), Authentication (password_verify), Resistance (XSS/SQLi preventie), Transport (HTTPS).

11. **Vraag**: Waarom staat er een redirect na succesvolle registratie?
    **Antwoord**: Om de gebruiker naar de loginpagina te sturen en dubbele form submissions te voorkomen.

12. **Vraag**: Wat is een Prepared Statement?
    **Antwoord**: Een SQL-query waarin waarden via placeholders worden ingevoegd, wat SQL Injection voorkomt.

13. **Vraag**: Waarom slaan we nooit het platte wachtwoord op?
    **Antwoord**: Bij een datalek zouden alle gebruikerswachtwoorden worden blootgesteld.

14. **Vraag**: Wat is de output van `password_hash()`?
    **Antwoord**: Een string van ~60 karakters inclusief algoritme-identifier, cost, salt en hash.

15. **Vraag**: Hoe controleer je of een email al bestaat in de database?
    **Antwoord**: Via een SELECT-query met een WHERE-clausule op email, gevolgd door een fetch-check.

16. **Vraag**: Wat is het verschil tussen POST en GET methodes?
    **Antwoord**: POST stuurt data in de request body (veilig voor wachtwoorden), GET stuurt data in de URL.

17. **Vraag**: Wanneer gebruik je `exit()` in PHP?
    **Antwoord**: Na een redirect om te voorkomen dat PHP doorgaat met uitvoeren.

18. **Vraag**: Wat is Input Sanitization?
    **Antwoord**: Het verwijderen of escapen van gevaarlijke tekens uit invoer.

19. **Vraag**: Waarom is een password bevestigingsveld belangrijk?
    **Antwoord**: Om typefouten bij het invoeren van het wachtwoord te voorkomen.

20. **Vraag**: Wat is de rol van de database in het HART Protocol?
    **Antwoord**: Het veilig opslaan van de gehashte wachtwoorden en het afdwingen van data-integriteit via constraints.

---

# 7. Conclusie

De `register.php` is een schoolvoorbeeld van veilige account-aanmaak met Bcrypt hashing en duplicate-preventie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
