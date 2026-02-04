# 🚪 UITLEG LOGOUT.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Veilig Uitloggen

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De beveiligde uitgang: een correct geïmplementeerde logout is net zo belangrijk als een veilige login."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Session Destruction Best Practices**
4.  **Security Implications**
5.  **GIGANTISCH LOGOUT WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Session & Security Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🔒

De `logout.php` pagina beëindigt de sessie van een gebruiker en stuurt hem door naar de loginpagina. Het is essentieel dat dit correct gebeurt om sessie-gerelateerde aanvallen te voorkomen.

---

# 2. Code Analyse

```php
<?php
session_start();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header('Location: login.php?logout=success');
exit();
?>
```

- `session_start()`: De sessie moet gestart zijn voordat we hem kunnen vernietigen.
- `$_SESSION = array()`: Wist alle sessie-variabelen.
- `setcookie(...)`: Verwijdert de sessiecookie door de vervaldatum in het verleden te zetten.
- `session_destroy()`: Vernietigt de server-side sessie-data.
- `header('Location: ...')`: Stuurt de gebruiker door naar de loginpagina.

---

# 5. GIGANTISCH LOGOUT WOORDENBOEK (50 TERMEN)

1. **Session**: Een server-side opslag voor gebruikersdata.
2. **Session Cookie**: De cookie die de sessie-ID bevat.
3. **Session Destroy**: Het wissen van alle server-side sessie-data.
4. **Session Unset**: Het leegmaken van de `$_SESSION` array.
5. **Session Hijacking**: Een aanval waarbij een sessie-ID wordt gestolen.
6. **Session Fixation**: Een aanval waarbij de sessie-ID vooraf wordt bepaald.
7. **Session Regeneration**: Het vervangen van de sessie-ID voor betere veiligheid.
8. **Cookie Expiration**: Het moment waarop een cookie ongeldig wordt.
9. **HttpOnly**: Een vlag die JavaScript toegang tot cookies blokkeert.
10. **Secure Flag**: Een vlag die cookies alleen over HTTPS verstuurt.
11. **SameSite Flag**: Bescherming tegen CSRF.
12. **Cookie Path**: Het pad waarvoor de cookie geldig is.
13. **Cookie Domain**: Het domein waarvoor de cookie geldig is.
14. **Session ID**: De unieke identifier van de sessie.
15. **PHPSESSID**: De standaard naam van de sessie-cookie.
16. **session_start()**: PHP-functie om sessie te starten.
17. **session_destroy()**: PHP-functie om sessie te vernietigen.
18. **session_unset()**: PHP-functie om variabelen te wissen.
19. **setcookie()**: PHP-functie om cookie te manipuleren.
20. **time()**: PHP-functie voor huidige timestamp.
21. **Cookie Deletion**: Cookie verwijderen via expiratie in verleden.
22. **ini_get()**: Haalt PHP-configuratie op.
23. **session.use_cookies**: PHP-setting voor cookie-gebruik.
24. **session_get_cookie_params()**: Haalt cookie-parameters op.
25. **Logout Flow**: Het proces van uitloggen.
26. **Redirect After Logout**: Doorsturen na uitloggen.
27. **Success Parameter**: `?logout=success` in URL.
28. **Header Function**: PHP-functie voor HTTP-headers.
29. **Exit Statement**: Stoppen van verdere uitvoering.
30. **Complete Session Cleanup**: Alle sessie-sporen wissen.
31. **Server-Side Cleanup**: Data wissen op de server.
32. **Client-Side Cleanup**: Cookie wissen in de browser.
33. **Browser Cache**: Opgeslagen data in de browser.
34. **Clear Cache on Logout**: Cache wissen bij uitloggen.
35. **Single Sign-Out**: Uitloggen uit alle gekoppelde systemen.
36. **Logout Confirmation**: Bevestiging dat je wilt uitloggen.
37. **Forced Logout**: Uitloggen door admin of timeout.
38. **Session Timeout**: Automatisch uitloggen na inactiviteit.
39. **Idle Timeout**: Timeout bij geen activiteit.
40. **Absolute Timeout**: Maximum sessieduur.
41. **Token Invalidation**: Alle tokens ongeldig maken.
42. **Remember Me Revocation**: 'Onthoud mij' cookie wissen.
43. **Audit Log Entry**: Logout registreren in logbestand.
44. **Security Event**: Logout als beveiligingsgebeurtenis.
45. **Multi-Device Logout**: Uitloggen op alle apparaten.
46. **Current Device Only**: Alleen huidig apparaat uitloggen.
47. **Logout Link**: De link naar logout.php.
48. **Logout Button**: Knop om uit te loggen.
49. **Conditional Rendering**: Login/logout tonen afhankelijk van status.
50. **HART Protocol Logout**: Veilige logout volgens HART.

---

# 6. EXAMEN TRAINING: 20 Session & Security Vragen

1. **Vraag**: Waarom is `session_start()` nodig voor logout?
   **Antwoord**: De sessie moet gestart zijn voordat je hem kunt vernietigen.

2. **Vraag**: Wat doet `$_SESSION = array()`?
   **Antwoord**: Het wist alle sessie-variabelen door de array leeg te maken.

3. **Vraag**: Waarom wissen we de sessie-cookie apart?
   **Antwoord**: session_destroy() wist alleen de server-kant; de cookie blijft.

4. **Vraag**: Hoe verwijder je een cookie?
   **Antwoord**: Door de expiratie in het verleden te zetten met setcookie().

5. **Vraag**: Wat is Session Hijacking?
   **Antwoord**: Een aanval waarbij de aanvaller de sessie-ID steelt en zich voordoet als de gebruiker.

6. **Vraag**: Waarom is een volledige session cleanup belangrijk?
   **Antwoord**: Om te voorkomen dat oude sessie-data kan worden misbruikt.

7. **Vraag**: Wat doet `ini_get('session.use_cookies')`?
   **Antwoord**: Het checkt of PHP cookies gebruikt voor sessies.

8. **Vraag**: Wat is `session_get_cookie_params()`?
   **Antwoord**: Het haalt de cookie-parameters op (path, domain, secure, httponly).

9. **Vraag**: Waarom is `exit()` na de redirect belangrijk?
   **Antwoord**: Om te voorkomen dat PHP doorgaat en mogelijk data lekt.

10. **Vraag**: Wat is het verschil tussen session_unset en session_destroy?
    **Antwoord**: unset wist variabelen; destroy vernietigt de hele sessie.

11. **Vraag**: Wat is Session Timeout?
    **Antwoord**: Automatisch uitloggen na een periode van inactiviteit.

12. **Vraag**: Waarom zou je een Logout Confirmation implementeren?
    **Antwoord**: Om per ongeluk uitloggen te voorkomen.

13. **Vraag**: Wat is Single Sign-Out?
    **Antwoord**: Uitloggen uit alle gekoppelde applicaties tegelijk.

14. **Vraag**: Wat is de rol van HttpOnly bij logout?
    **Antwoord**: Het voorkomt dat JavaScript de sessie-cookie kan lezen/manipuleren.

15. **Vraag**: Wat is een Audit Log Entry voor logout?
    **Antwoord**: Een registratie van wanneer en wie er uitlogde.

16. **Vraag**: Waarom is `time() - 42000` effectief?
    **Antwoord**: Het zet de cookie-expiratie ver in het verleden.

17. **Vraag**: Wat is het verschil tussen Cookie Path en Domain?
    **Antwoord**: Path is het URL-pad; Domain is het domein waarvoor geldig.

18. **Vraag**: Waarom redirect je naar login.php na logout?
    **Antwoord**: De gebruiker moet opnieuw inloggen om toegang te krijgen.

19. **Vraag**: Wat is Forced Logout?
    **Antwoord**: Logout door de server, bijv. bij verdachte activiteit.

20. **Vraag**: Wat is de HART-rol bij logout?
    **Antwoord**: Complete sessie-cleanup om hergebruik van credentials te voorkomen.

---

# 7. Conclusie

De `logout.php` implementeert een complete sessie-vernietiging volgens de best practices, inclusief het wissen van de cookie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
