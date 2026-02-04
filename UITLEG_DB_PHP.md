# 🔌 UITLEG DB.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Database Connectie

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De levensader van de applicatie: een veilige PDO-verbinding naar MariaDB."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **PDO Opties Deep Dive**
4.  **Error Handling Strategie**
5.  **GIGANTISCH DB WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 PDO & Connection Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🔗

De `db.php` is het configuratie-bestand dat de verbinding met de MariaDB database opzet. Het wordt via `require_once` geladen door elke pagina die data nodig heeft.

---

# 2. Code Analyse

```php
<?php
$host = 'localhost';
$db = 'gameplan_scheduler';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Database niet beschikbaar.");
}
?>
```

- **DSN**: Data Source Name - de 'adres' van de database.
- `ERRMODE_EXCEPTION`: Gooi een exception bij database-fouten.
- `FETCH_ASSOC`: Haal data op als associatieve array.
- `EMULATE_PREPARES => false`: **Essentieel voor SQL Injection preventie**.

---

# 5. GIGANTISCH DB WOORDENBOEK (50 TERMEN)

1. **PDO**: PHP Data Objects - de veilige database-abstractie.
2. **DSN**: Data Source Name - de connectie-string.
3. **MySQL Driver**: De link tussen PDO en MySQL/MariaDB.
4. **ERRMODE_EXCEPTION**: Modus die exceptions gooit bij fouten.
5. **FETCH_ASSOC**: Ophalen als associatieve array.
6. **EMULATE_PREPARES**: Emulatie van prepared statements (uit zetten!).
7. **try-catch**: Error handling blok.
8. **PDOException**: Foutmelding bij database-problemen.
9. **Charset (utf8mb4)**: Karakterset voor volledige Unicode-support.
10. **Connection Pooling**: Hergebruik van verbindingen (geavanceerd).
11. **Persistent Connection**: Verbinding open houden tussen requests.
12. **Connection Timeout**: Tijd voordat een verbinding faalt.
13. **Host**: Het adres van de databaseserver.
14. **Database Name**: De naam van de specifieke database.
15. **Username**: De gebruikersnaam voor authenticatie.
16. **Password**: Het wachtwoord voor database-toegang.
17. **Port (3306)**: De standaard MySQL/MariaDB poort.
18. **Socket**: Alternatieve verbindingsmethode op Unix.
19. **SSL/TLS**: Versleutelde database-verbinding.
20. **Read Replica**: Secundaire database voor leesoperaties.
21. **Master Database**: Primaire database voor schrijfoperaties.
22. **Failover**: Automatisch overschakelen bij storing.
23. **Load Balancing**: Verdeling van database-verkeer.
24. **Query Cache**: Opslag van veelgebruikte query-resultaten.
25. **Connection String Variables**: Variabelen in de DSN.
26. **Environment Variables**: Configuratie via .env bestanden.
27. **Config File**: Gescheiden configuratiebestand.
28. **Singleton Pattern**: Eén database-instantie voor hele app.
29. **Dependency Injection**: Database als parameter doorgeven.
30. **Global Variable**: De $pdo variabele als globaal object.
31. **Error Logging**: Fouten loggen in plaats van tonen.
32. **error_log()**: PHP-functie om naar logbestand te schrijven.
33. **die()**: Script stoppen met foutmelding.
34. **Graceful Degradation**: Netjes falen bij problemen.
35. **User-Friendly Error**: Vage foutmelding voor gebruikers.
36. **Debug Mode**: Gedetailleerde fouten tijdens ontwikkeling.
37. **Production Mode**: Minimale fouten op live server.
38. **Prepared Statements**: SQL met placeholders voor veiligheid.
39. **Parameterized Queries**: Queries met parameters.
40. **SQL Injection**: Aanval via kwaadaardige SQL-invoer.
41. **Native Prepared Statements**: Echte voorbereide queries (EMULATE_PREPARES = false).
42. **Emulated Prepared Statements**: PHP-gesimuleerde queries (onveilig).
43. **Fetch Mode**: Hoe resultaten worden opgehaald.
44. **FETCH_OBJ**: Ophalen als object.
45. **FETCH_NUM**: Ophalen als numerieke array.
46. **FETCH_BOTH**: Ophalen als beide (verspilling).
47. **Charset Mismatch**: Problemen bij verkeerde karakterset.
48. **UTF-8**: Universele karaktercodering.
49. **MariaDB Fork**: Open-source versie van MySQL.
50. **InnoDB Engine**: Transactie-veilige storage engine.

---

# 6. EXAMEN TRAINING: 20 PDO & Connection Vragen

1. **Vraag**: Waarom is `EMULATE_PREPARES => false` essentieel?
   **Antwoord**: Omdat echte prepared statements SQL Injection volledig voorkomen; geëmuleerde doen dat niet.

2. **Vraag**: Wat doet `ERRMODE_EXCEPTION`?
   **Antwoord**: Het zorgt dat PDO een exception gooit bij fouten, wat betere error handling mogelijk maakt.

3. **Vraag**: Waarom gebruiken we utf8mb4 in plaats van utf8?
   **Antwoord**: utf8mb4 ondersteunt alle Unicode-tekens, inclusief emoji's (4 bytes per karakter).

4. **Vraag**: Wat is een DSN?
   **Antwoord**: Data Source Name - de string die de database-locatie en parameters bevat.

5. **Vraag**: Waarom zetten we `error_log()` in de catch-block?
   **Antwoord**: Om de echte foutmelding te loggen voor debugging, zonder deze aan gebruikers te tonen.

6. **Vraag**: Wat is het verschil tussen `die()` en `exit()`?
   **Antwoord**: Functioneel identiek; beide stoppen de script-uitvoering.

7. **Vraag**: Waarom is een try-catch blok belangrijk bij database-connecties?
   **Antwoord**: Omdat connectie-fouten kunnen optreden en netjes moeten worden afgehandeld.

8. **Vraag**: Wat is Connection Pooling?
   **Antwoord**: Het hergebruiken van bestaande database-verbindingen om performance te verbeteren.

9. **Vraag**: Waarom gebruiken we `require_once` voor db.php?
   **Antwoord**: Om te zorgen dat de database-connectie slechts éénmaal wordt gemaakt, ook bij meerdere includes.

10. **Vraag**: Wat is het risico van hardcoded database credentials?
    **Antwoord**: Bij een lek van de broncode zijn de inloggegevens direct zichtbaar.

11. **Vraag**: Hoe zou je credentials veiliger kunnen opslaan?
    **Antwoord**: Via environment variables of een .env bestand buiten de webroot.

12. **Vraag**: Wat is FETCH_ASSOC?
    **Antwoord**: Een fetch mode die resultaten als associatieve array teruggeeft ($row['column_name']).

13. **Vraag**: Waarom is een lege password bij root gevaarlijk?
    **Antwoord**: Elke aanvaller met toegang tot de server kan de database benaderen.

14. **Vraag**: Wat is de standaard MySQL poort?
    **Antwoord**: 3306.

15. **Vraag**: Wat is het verschil tussen MySQL en MariaDB?
    **Antwoord**: MariaDB is een open-source fork van MySQL met verbeterde features en licentie.

16. **Vraag**: Waarom is InnoDB de voorkeurs-engine?
    **Antwoord**: InnoDB ondersteunt transacties, foreign keys en row-level locking.

17. **Vraag**: Wat gebeurt er als de database offline is?
    **Antwoord**: PDO gooit een PDOException die we opvangen in de catch-block.

18. **Vraag**: Wat is Graceful Degradation?
    **Antwoord**: Het netjes afhandelen van fouten zodat de applicatie niet crasht.

19. **Vraag**: Waarom tonen we geen gedetailleerde foutmeldingen aan gebruikers?
    **Antwoord**: Om aanvallers geen informatie te geven over de database-architectuur.

20. **Vraag**: Wat is de rol van db.php in de applicatie-architectuur?
    **Antwoord**: Het is de centrale database-configuratie die door alle pagina's wordt geladen.

---

# 7. Conclusie

De `db.php` is een schoolvoorbeeld van veilige database-configuratie met PDO en security-first opties.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
