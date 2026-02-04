# 🔝 UITLEG HEADER.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Navigatie & Huisstijl

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De eerste indruk: een consistente header zorgt voor herkenning en vertrouwen."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Sessie-Afhankelijke Navigatie**
4.  **Responsive Design Integratie**
5.  **GIGANTISCH HEADER WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Header & Nav Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 🧭

De `header.php` is het bovenste deel van elke pagina en bevat:
- Het logo van de GamePlan Scheduler.
- De navigatiebalk met links naar alle hoofdpagina's.
- Sessie-afhankelijke content (Login/Logout knoppen).

---

# 2. Code Analyse

```php
<header class="glass-header">
    <div class="logo">GamePlan Scheduler</div>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="profile.php">Profiel</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Uitloggen</a>
        <?php else: ?>
            <a href="login.php">Inloggen</a>
        <?php endif; ?>
    </nav>
</header>
```

- `include`: Door de header als een apart bestand te hebben, is consistentie gegarandeerd.
- `$_SESSION['user_id']`: Controleert of de gebruiker is ingelogd om de juiste knoppen te tonen.
- **Conditional Rendering**: PHP `if` binnen HTML voor dynamische content.

---

# 5. GIGANTISCH HEADER WOORDENBOEK (50 TERMEN)

1. **Header**: Het bovenste deel van een webpagina.
2. **Navigation Bar (Nav)**: De lijst met links naar andere pagina's.
3. **Logo**: Het merk-icoon van de applicatie.
4. **Include**: PHP-functie om een bestand in te voegen.
5. **Session Check**: Controleren of een gebruiker is ingelogd.
6. **Conditional Rendering**: Content tonen op basis van een conditie.
7. **Active Link**: De link die overeenkomt met de huidige pagina.
8. **Styling**: De visuele aankleding via CSS.
9. **Responsive Nav**: Een navigatie die zich aanpast aan mobiel.
10. **Hamburger Menu**: Een iconisch mobiel menu-icoon.
11. **Semantic HTML**: Betekenisvolle HTML-tags (`<header>`, `<nav>`).
12. **Accessibility**: Toegankelijkheid voor alle gebruikers.
13. **ARIA Labels**: Labels voor screenreaders.
14. **Skip Navigation Link**: Link om navigatie over te slaan.
15. **Sticky Header**: Header die blijft staan bij scrollen.
16. **Fixed Header**: Header met vaste positie.
17. **Transparent Header**: Doorzichtige header.
18. **Glassmorphism Header**: Header met glaseffect.
19. **Dropdown Menu**: Uitklapbaar submenu.
20. **Mega Menu**: Groot uitgebreid menu.
21. **Breadcrumb**: Navigatiespoor (Home > Pagina).
22. **Search Bar**: Zoekbalk in header.
23. **User Avatar**: Profielafbeelding in header.
24. **Notification Bell**: Meldingen-icoon.
25. **Language Selector**: Taalkeuze in header.
26. **Theme Toggle**: Donker/licht modus schakelaar.
27. **Login Button**: Inlogknap voor gasten.
28. **Logout Button**: Uitlogknop voor ingelogde gebruikers.
29. **Profile Link**: Link naar profielpagina.
30. **Settings Link**: Link naar instellingen.
31. **Brand Consistency**: Consistente huisstijl.
32. **Color Scheme**: Kleurenpalet van de header.
33. **Typography**: Lettertype gebruik.
34. **Spacing**: Witruimte en margins.
35. **Border**: Randen en scheidingslijnen.
36. **Shadow**: Schaduw-effect.
37. **Hover Effect**: Effect bij mouse-over.
38. **Active State**: Stijl van actieve link.
39. **Focus State**: Stijl bij keyboard-focus.
40. **Transition**: Animatie bij state-verandering.
41. **Z-Index**: Stapelvolgorde van elementen.
42. **Viewport Width**: Breedte van het scherm.
43. **Media Query**: CSS voor responsive design.
44. **Mobile First**: Eerst ontwerpen voor mobiel.
45. **Breakpoint**: Punt waar layout verandert.
46. **Flexbox**: CSS voor layout.
47. **Grid**: CSS voor complexe layouts.
48. **DRY Principle**: Don't Repeat Yourself via include.
49. **Template Partial**: Herbruikbaar template-deel.
50. **Component Architecture**: Modulaire opbouw.

---

# 6. EXAMEN TRAINING: 20 Header & Nav Vragen

1. **Vraag**: Waarom gebruiken we `include` voor de header?
   **Antwoord**: Voor consistentie en DRY - de header is op elke pagina hetzelfde.

2. **Vraag**: Wat is Conditional Rendering?
   **Antwoord**: Verschillende content tonen afhankelijk van een conditie (ingelogd/niet).

3. **Vraag**: Waarom tonen we Login OF Logout, nooit beide?
   **Antwoord**: Omdat de gebruiker óf ingelogd is óf niet; nooit beide tegelijk.

4. **Vraag**: Wat is een Hamburger Menu?
   **Antwoord**: Het icoon met drie horizontale lijnen voor mobiele navigatie.

5. **Vraag**: Waarom is Semantic HTML belangrijk?
   **Antwoord**: Het verbetert toegankelijkheid en SEO.

6. **Vraag**: Wat is een Sticky Header?
   **Antwoord**: Een header die bovenaan blijft staan tijdens scrollen.

7. **Vraag**: Hoe maak je een Responsive Navigation?
   **Antwoord**: Met media queries en CSS of JavaScript voor mobile menu.

8. **Vraag**: Wat zijn ARIA Labels?
   **Antwoord**: Attributen die screenreaders helpen de pagina te begrijpen.

9. **Vraag**: Wat is het DRY Principle?
   **Antwoord**: Don't Repeat Yourself - geen duplicatie van code.

10. **Vraag**: Hoe zou je een Active Link stylen?
    **Antwoord**: Check huidige pagina in PHP, voeg klasse toe als match.

11. **Vraag**: Wat is Glassmorphism in CSS?
    **Antwoord**: Semi-transparante achtergrond met blur-effect.

12. **Vraag**: Waarom is Accessibility belangrijk?
    **Antwoord**: Zodat iedereen, inclusief mensen met beperkingen, de site kan gebruiken.

13. **Vraag**: Wat is een Skip Navigation Link?
    **Antwoord**: Een link om direct naar de content te gaan, voor keyboard-gebruikers.

14. **Vraag**: Hoe implementeer je een Theme Toggle?
    **Antwoord**: JavaScript om CSS-klasse te wisselen, localStorage voor persistentie.

15. **Vraag**: Wat is Mobile First Design?
    **Antwoord**: Eerst ontwerpen voor mobiel, dan uitbreiden voor desktop.

16. **Vraag**: Wat is een Breakpoint?
    **Antwoord**: De schermgrootte waar de layout verandert.

17. **Vraag**: Waarom is Z-Index belangrijk voor headers?
    **Antwoord**: Om te zorgen dat de header boven andere content blijft.

18. **Vraag**: Wat is het verschil tussen Fixed en Sticky?
    **Antwoord**: Fixed is altijd vast; sticky wordt vast na een bepaald scrollpunt.

19. **Vraag**: Hoe zou je een Dropdown Menu maken?
    **Antwoord**: CSS :hover of JavaScript voor toggle, nested list-items.

20. **Vraag**: Wat is Component Architecture?
    **Antwoord**: Het bouwen van UI uit herbruikbare, onafhankelijke onderdelen.

---

# 7. Conclusie

De `header.php` combineert hergebruik met dynamische sessie-logica voor een consistente en veilige navigatie.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
