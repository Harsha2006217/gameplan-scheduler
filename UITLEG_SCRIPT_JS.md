# ⚡ UITLEG SCRIPT.JS (ELITE MASTER EDITIE)
## GamePlan Scheduler - JavaScript Interactie & Validatie

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De ziel van de gebruikerservaring: client-side validatie en dynamische interacties."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Functie voor Functie)**
3.  **Client-Side vs Server-Side Validatie**
4.  **UX Enhancement Patterns**
5.  **GIGANTISCH JAVASCRIPT WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 JavaScript & UX Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🎭

De `script.js` bevat alle client-side logica voor de GamePlan Scheduler:
- **Real-time Formuliervalidatie**: Directe feedback bij ongeldige invoer.
- **Dynamische UI Updates**: Hover-effecten, actieve status-styling.
- **UX Enhancement**: Soepele transities en visuele aanwijzingen.

---

# 2. Code Analyse

```javascript
// Real-time lege veld check (Bugfix #1001 ondersteuning)
document.querySelectorAll('form input, form textarea').forEach(field => {
    field.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });
});

// Dynamic greeting based on time of day
function setGreeting() {
    const hour = new Date().getHours();
    let greeting = 'Welkom';
    if (hour < 12) greeting = 'Goedemorgen';
    else if (hour < 18) greeting = 'Goedemiddag';
    else greeting = 'Goedenavond';
    document.getElementById('greeting').textContent = greeting;
}
window.onload = setGreeting;
```

- `querySelectorAll`: Selecteert alle invoervelden.
- `addEventListener('blur', ...)`: Triggert wanneer de gebruiker het veld verlaat.
- `trim()`: Verwijdert spaties om lege invoer te detecteren.
- `classList.add/remove`: Voegt visuele feedback toe via CSS-klassen.

---

# 5. GIGANTISCH JAVASCRIPT WOORDENBOEK (50 TERMEN)

1. **DOM (Document Object Model)**: De boomstructuur van de webpagina.
2. **Event Listener**: Een digitale oor die luistert naar gebruikersacties.
3. **Blur Event**: Triggered wanneer een element focus verliest.
4. **Click Event**: Triggered bij een muisklik.
5. **Submit Event**: Triggered bij het verzenden van een formulier.
6. **Form Validation**: Controleren of invoer aan regels voldoet.
7. **Client-Side**: Logica die in de browser draait.
8. **Server-Side**: Logica die op de server draait (PHP).
9. **Callback Function**: Een functie die later wordt uitgevoerd.
10. **Arrow Function**: Moderne JS-syntax voor functies (`() => {}`).
11. **querySelector**: Selecteert één element via CSS-selector.
12. **querySelectorAll**: Selecteert alle matchende elementen.
13. **getElementById**: Selecteert element via ID.
14. **getElementsByClassName**: Selecteert elementen via class.
15. **innerHTML**: De HTML-inhoud van een element.
16. **textContent**: De tekstinhoud van een element.
17. **classList**: Interface voor class-management.
18. **add()**: Voegt een class toe.
19. **remove()**: Verwijdert een class.
20. **toggle()**: Wisselt een class.
21. **contains()**: Checkt of class bestaat.
22. **trim()**: Verwijdert witruimte van string.
23. **forEach**: Loop over array-elementen.
24. **Array Methods**: map, filter, reduce, etc.
25. **let/const/var**: Variabele declaraties.
26. **Template Literals**: Backtick strings met interpolation.
27. **Ternary Operator**: Korte if-else (`? :`).
28. **Fetch API**: Moderne HTTP requests.
29. **Promise**: Async waarde die later beschikbaar is.
30. **async/await**: Moderne async syntax.
31. **Try-Catch**: Error handling.
32. **JSON**: JavaScript Object Notation.
33. **JSON.parse()**: String naar object.
34. **JSON.stringify()**: Object naar string.
35. **localStorage**: Persistente browser-opslag.
36. **sessionStorage**: Sessie-gebaseerde opslag.
37. **setTimeout**: Vertraagde uitvoering.
38. **setInterval**: Herhaalde uitvoering.
39. **window Object**: Het globale browser-object.
40. **document Object**: De pagina-representatie.
41. **Event Object**: Info over een event (e).
42. **preventDefault()**: Voorkomt standaard gedrag.
43. **stopPropagation()**: Stopt event bubbling.
44. **Event Bubbling**: Events stijgen naar parent.
45. **Event Capturing**: Events dalen naar child.
46. **window.onload**: Triggered bij pagina-load.
47. **DOMContentLoaded**: Triggered bij DOM-ready.
48. **Script Defer**: Script na DOM laden.
49. **Script Async**: Script asynchroon laden.
50. **ES6+ Features**: Moderne JavaScript.

---

# 6. EXAMEN TRAINING: 20 JavaScript & UX Vragen

1. **Vraag**: Wat is het DOM?
   **Antwoord**: Document Object Model - de boomstructuur-representatie van de HTML-pagina.

2. **Vraag**: Wat is het verschil tussen client-side en server-side validatie?
   **Antwoord**: Client-side is sneller maar kan worden omzeild; server-side is de definitieve check.

3. **Vraag**: Wat doet `trim()` bij formuliervalidatie?
   **Antwoord**: Het verwijdert spaties aan begin en einde om lege invoer correct te detecteren.

4. **Vraag**: Wat is een Event Listener?
   **Antwoord**: Een functie die reageert op gebruikersacties zoals clicks of toetsaanslagen.

5. **Vraag**: Wat is het verschil tussen `blur` en `focus` events?
   **Antwoord**: focus triggered bij het activeren van een element; blur bij het verlaten.

6. **Vraag**: Wat doet `classList.add()`?
   **Antwoord**: Het voegt een CSS-class toe aan een element.

7. **Vraag**: Waarom gebruiken we Arrow Functions?
   **Antwoord**: Kortere syntax en behoud van `this` context.

8. **Vraag**: Wat is `querySelectorAll`?
   **Antwoord**: Een methode die alle matchende elementen selecteert als NodeList.

9. **Vraag**: Wat is localStorage?
   **Antwoord**: Persistente browser-opslag die blijft bestaan na het sluiten van het tabblad.

10. **Vraag**: Wat is het verschil tussen let en const?
    **Antwoord**: let kan worden gewijzigd; const is constant na declaratie.

11. **Vraag**: Wat is Event Bubbling?
    **Antwoord**: Events stijgen van child naar parent elementen.

12. **Vraag**: Wat doet `preventDefault()`?
    **Antwoord**: Het voorkomt het standaard gedrag van een event (bijv. form submit).

13. **Vraag**: Wat is een Template Literal?
    **Antwoord**: Een string met backticks die variabelen kan bevatten via ${}.

14. **Vraag**: Wat is het verschil tussen innerHTML en textContent?
    **Antwoord**: innerHTML bevat HTML-tags; textContent alleen tekst.

15. **Vraag**: Wat is Fetch API?
    **Antwoord**: Moderne JavaScript-interface voor HTTP requests.

16. **Vraag**: Wat is een Promise?
    **Antwoord**: Een object dat een waarde vertegenwoordigt die later beschikbaar is.

17. **Vraag**: Wat is async/await?
    **Antwoord**: Moderne syntax om met Promises te werken als synchrone code.

18. **Vraag**: Wat doet `window.onload`?
    **Antwoord**: Het triggered een functie wanneer de hele pagina is geladen.

19. **Vraag**: Wat is het voordeel van script defer?
    **Antwoord**: Het script laadt parallel maar voert uit na DOM-parsing.

20. **Vraag**: Waarom is client-side validatie goed voor UX?
    **Antwoord**: Het geeft directe feedback zonder pagina-reload.

---

# 7. Conclusie

De `script.js` versterkt de UX door directe feedback en dynamische interacties, terwijl de server de definitieve validatie handhaaft.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
