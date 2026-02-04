# 🧰 UITLEG FUNCTIONS.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - De Gereedschapskist

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De verzameling van herbruikbare functies die de ruggengraat vormen van de applicatie."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Functie Analyse (Per Functie)**
3.  **HART Protocol Functies**
4.  **Separation of Concerns in Actie**
5.  **GIGANTISCH FUNCTIONS WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Functions & Design Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🔧

De `functions.php` bevat alle herbruikbare functies van de GamePlan Scheduler. Door functies te centraliseren:
- Voorkomen we duplicatie (DRY-principe).
- Maken we de code makkelijker te onderhouden.
- Zorgen we voor consistente logica door de hele app.

---

# 2. Functie Analyse

### 2.1 `safeEcho($text)`
```php
function safeEcho($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
```
- **Doel**: XSS-preventie door speciale tekens te escapen.
- **HART Protocol**: Resistance.

### 2.2 `loginUser($email, $password)` (Concept)
```php
function loginUser($email, $password) {
    // Haal gebruiker op via email
    // Vergelijk wachtwoord met password_verify()
    // Creëer sessie bij succes
}
```
- **Doel**: Authenticatie encapsulatie.
- **HART Protocol**: Authentication.

### 2.3 `isLoggedIn()`
```php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
```
- **Doel**: Snelle check voor sessie-status.

---

# 5. GIGANTISCH FUNCTIONS WOORDENBOEK (50 TERMEN)

1. **Function**: Een herbruikbaar codeblok.
2. **Parameter**: De invoerwaarde van een functie.
3. **Return Value**: De uitvoerwaarde van een functie.
4. **htmlspecialchars()**: PHP-functie voor XSS-preventie.
5. **password_hash()**: Functie voor wachtwoord-hashing.
6. **password_verify()**: Functie voor wachtwoord-verificatie.
7. **isset()**: Controleert of een variabele bestaat.
8. **DRY (Don't Repeat Yourself)**: Geen dubbele code.
9. **Encapsulation**: Het verbergen van logica in functies.
10. **Single Responsibility**: Elke functie doet één ding.
11. **Abstraction**: Complexiteit verbergen achter simpele functies.
12. **Reusability**: Code hergebruiken op meerdere plekken.
13. **Maintainability**: Code makkelijk aanpasbaar houden.
14. **Helper Function**: Ondersteunende functie voor common taken.
15. **Utility Function**: Algemene hulpfunctie.
16. **Validation Function**: Functie die invoer controleert.
17. **Sanitization Function**: Functie die invoer schoonmaakt.
18. **Security Function**: Functie voor beveiligingstaken.
19. **ENT_QUOTES**: Flag om zowel single als double quotes te escapen.
20. **UTF-8**: Unicode karaktercodering.
21. **Boolean Return**: Functie die true/false retourneert.
22. **Void Return**: Functie die niets retourneert.
23. **Type Hinting**: Specificeren van parameter-types.
24. **Return Type Declaration**: Specificeren van return-type.
25. **Nullable Types**: Types die ook null kunnen zijn.
26. **Default Parameter Value**: Standaardwaarde als parameter ontbreekt.
27. **Variadic Functions**: Functies met onbeperkt aantal parameters.
28. **Anonymous Function**: Functie zonder naam (closure).
29. **Arrow Function**: Korte syntax voor eenvoudige functies.
30. **Callback**: Functie als parameter doorgeven.
31. **Higher-Order Function**: Functie die functie ontvangt/retourneert.
32. **Recursive Function**: Functie die zichzelf aanroept.
33. **Global Scope**: Bereik buiten alle functies.
34. **Local Scope**: Bereik binnen een functie.
35. **Static Variable**: Variabele die waarde behoudt tussen calls.
36. **Include File**: Bestand met gedeelde functies.
37. **Require Once**: Include met foutafhandeling, slechts éénmaal.
38. **Autoloading**: Automatisch laden van klassen/functies.
39. **Namespacing**: Organiseren van code in namespaces.
40. **PSR Standards**: PHP Framework Interop Group standaarden.
41. **Error Handling**: Omgaan met fouten in functies.
42. **Exception Throwing**: Exceptions gooien bij problemen.
43. **Try-Catch in Functions**: Error handling in functies.
44. **Logging in Functions**: Fouten loggen vanuit functies.
45. **Testing Functions**: Functies unit-testen.
46. **Mocking**: Functies simuleren voor tests.
47. **Code Documentation**: PHPDoc commentaar.
48. **Function Signature**: De naam en parameters van een functie.
49. **HART Protocol Functions**: Functies die HART implementeren.
50. **Security Best Practices**: Beveiligingsstandaarden.

---

# 6. EXAMEN TRAINING: 20 Functions & Design Vragen

1. **Vraag**: Wat betekent DRY in programmeren?
   **Antwoord**: Don't Repeat Yourself - geen dubbele code schrijven.

2. **Vraag**: Waarom is `safeEcho()` beter dan overal `htmlspecialchars()` aanroepen?
   **Antwoord**: Het centraliseert de logica; als de implementatie verandert, hoef je maar één plek aan te passen.

3. **Vraag**: Wat is het Single Responsibility Principle?
   **Antwoord**: Elke functie (of klasse) moet slechts één taak hebben.

4. **Vraag**: Waarom retourneert `isLoggedIn()` een boolean?
   **Antwoord**: Omdat het een ja/nee vraag beantwoordt die je in if-statements kunt gebruiken.

5. **Vraag**: Wat is Encapsulation?
   **Antwoord**: Het verbergen van implementatiedetails achter een functie-interface.

6. **Vraag**: Waarom gebruiken we ENT_QUOTES bij htmlspecialchars()?
   **Antwoord**: Om zowel single (') als double (") quotes te escapen voor volledige bescherming.

7. **Vraag**: Wat is het verschil tussen `include` en `require`?
   **Antwoord**: require stopt de script bij een fout; include geeft alleen een waarschuwing.

8. **Vraag**: Waarom gebruiken we `require_once` voor functions.php?
   **Antwoord**: Om te voorkomen dat functies dubbel worden gedefinieerd.

9. **Vraag**: Wat is een Helper Function?
   **Antwoord**: Een ondersteunende functie voor veelvoorkomende taken.

10. **Vraag**: Hoe zou je functies documenteren?
    **Antwoord**: Met PHPDoc commentaar boven de functie.

11. **Vraag**: Wat is Type Hinting?
    **Antwoord**: Het specificeren van het type van een parameter, bijv. `function foo(string $bar)`.

12. **Vraag**: Wat is een Return Type Declaration?
    **Antwoord**: Het specificeren van het retourtype, bijv. `function foo(): bool`.

13. **Vraag**: Wanneer zou je een Exception gooien in een functie?
    **Antwoord**: Bij fouten die de aanroepende code moet afhandelen.

14. **Vraag**: Wat is een Callback?
    **Antwoord**: Een functie die als parameter aan een andere functie wordt doorgegeven.

15. **Vraag**: Wat is het voordeel van kleine, gefocuste functies?
    **Antwoord**: Ze zijn makkelijker te testen, begrijpen en hergebruiken.

16. **Vraag**: Hoe test je functies in isolation?
    **Antwoord**: Via unit tests die elke functie apart testen.

17. **Vraag**: Wat is een Static Variable in een functie?
    **Antwoord**: Een variabele die zijn waarde behoudt tussen functie-aanroepen.

18. **Vraag**: Wat is de rol van functions.php in de HART-architectuur?
    **Antwoord**: Het centraliseert beveiligingsfuncties zoals safeEcho en isLoggedIn.

19. **Vraag**: Waarom is UTF-8 belangrijk bij htmlspecialchars()?
    **Antwoord**: Om te garanderen dat alle karakters correct worden verwerkt.

20. **Vraag**: Wat is het voordeel van een centrale functions.php?
    **Antwoord**: Consistentie, onderhoudbaarheid en naleving van DRY.

---

# 7. Conclusie

De `functions.php` is het hart van de HART-implementatie en een schoolvoorbeeld van het Single Responsibility Principle.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
