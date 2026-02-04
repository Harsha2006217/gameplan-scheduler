# 🎨 UITLEG STYLE.CSS (ELITE MASTER EDITIE)
## GamePlan Scheduler - Glassmorphism Design System

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Design is not just what it looks like; design is how it works. Dit stylesheet is de visuele identiteit van de GamePlan Scheduler."

---

# 📑 Inhoudsopgave

1.  **Design Filosofie: Glassmorphism**
2.  **CSS Variabelen & Design Tokens**
3.  **Component Styling (Cards, Forms, Buttons)**
4.  **Responsive Design & Media Queries**
5.  **GIGANTISCH CSS WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 CSS & Design Vragen**
7.  **Conclusie**

---

# 1. Design Filosofie: Glassmorphism 🪟

De GamePlan Scheduler maakt gebruik van de **Glassmorphism** trend:
- **Blur-effect**: `backdrop-filter: blur(12px);`
- **Semi-transparante achtergronden**: `background: rgba(255, 255, 255, 0.1);`
- **Zachte randen**: `border-radius: 20px;`
- **Subtiele schaduwen**: `box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);`

Dit creëert een moderne, premium uitstraling die past bij de gaming-doelgroep.

---

# 2. CSS Variabelen

```css
:root {
    --primary-color: #00aaff;
    --secondary-color: #ff5588;
    --glass-bg: rgba(255, 255, 255, 0.15);
    --glass-blur: 12px;
    --border-radius: 20px;
    --shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.glass-card {
    background: var(--glass-bg);
    border-radius: var(--border-radius);
    backdrop-filter: blur(var(--glass-blur));
    box-shadow: var(--shadow);
}
```

- **CSS Variables**: Centraal beheer van ontwerpwaarden.
- **Herbruikbaarheid**: Eén aanpassing in `:root` werkt door op de hele site.

---

# 5. GIGANTISCH CSS WOORDENBOEK (50 TERMEN)

1. **CSS**: Cascading Style Sheets.
2. **Selector**: Het element dat je wilt stylen.
3. **Property**: De eigenschap die je wilt aanpassen.
4. **Value**: De waarde van de eigenschap.
5. **Class Selector**: `.class-name`
6. **ID Selector**: `#id-name`
7. **Pseudo-class**: `:hover`, `:focus`, `:active`
8. **Pseudo-element**: `::before`, `::after`
9. **Box Model**: Content, Padding, Border, Margin.
10. **Flexbox**: Moderne layout-techniek.
11. **Grid**: CSS Grid voor 2D layouts.
12. **CSS Variables**: Custom properties (`--name: value`).
13. **Root Selector**: `:root` voor globale variabelen.
14. **var() Function**: CSS-functie om variabelen te gebruiken.
15. **Media Query**: Responsive styling (`@media`).
16. **Breakpoint**: Punt waar layout verandert.
17. **Mobile First**: Eerst ontwerpen voor mobiel.
18. **Desktop First**: Eerst ontwerpen voor desktop.
19. **Viewport**: Het zichtbare deel van de pagina.
20. **rem/em Units**: Relatieve eenheden.
21. **px Unit**: Absolute pixel-eenheid.
22. **Percentage**: Relatieve eenheid (%).
23. **Color**: Kleuren (hex, rgb, hsl).
24. **Gradient**: Kleurovergang.
25. **Background**: Achtergrond-styling.
26. **Border-radius**: Afgeronde hoeken.
27. **Box-shadow**: Schaduw-effect.
28. **Text-shadow**: Schaduw op tekst.
29. **Opacity**: Transparantie (0-1).
30. **RGBA**: Kleur met transparantie.
31. **Backdrop-filter**: Filter op achtergrond (blur).
32. **Blur(): CSS-filter voor wazigheid.
33. **Glassmorphism**: Moderne designtrend.
34. **Transition**: Animatie bij verandering.
35. **Animation**: Keyframe animaties.
36. **Keyframes**: Stappen in een animatie.
37. **Transform**: Transformaties (rotate, scale).
38. **Z-index**: Stapelvolgorde.
39. **Position**: Positionering (relative, absolute).
40. **Display**: Weergave (block, flex, grid).
41. **Overflow**: Gedrag bij overloop.
42. **Cursor**: Muispijl styling.
43. **Font-family**: Lettertype.
44. **Font-weight**: Dikte van het lettertype.
45. **Line-height**: Regelhoogte.
46. **Text-align**: Tekstuitlijning.
47. **Color Scheme**: Kleurenpalet.
48. **Dark Mode**: Donkere kleurvariant.
49. **Light Mode**: Lichte kleurvariant.
50. **Accessibility**: Toegankelijke styling.

---

# 6. EXAMEN TRAINING: 20 CSS & Design Vragen

1. **Vraag**: Wat is Glassmorphism?
   **Antwoord**: Een designtrend met semi-transparante elementen en blur-effect, als een glasplaat.

2. **Vraag**: Waarom gebruiken we CSS Variables?
   **Antwoord**: Voor centraal beheer van designwaarden en makkelijke aanpassing.

3. **Vraag**: Wat doet `backdrop-filter: blur(12px)`?
   **Antwoord**: Het creëert een wazig effect op de achtergrond achter het element.

4. **Vraag**: Wat is het Box Model?
   **Antwoord**: De structuur van elementen: content, padding, border, margin.

5. **Vraag**: Wat is het verschil tussen Flexbox en Grid?
   **Antwoord**: Flexbox is voor 1D layout (rij/kolom), Grid voor 2D layout.

6. **Vraag**: Wat is een Media Query?
   **Antwoord**: CSS-regel die styling toepast op basis van schermgrootte.

7. **Vraag**: Wat betekent Mobile First?
   **Antwoord**: Eerst de mobiele versie stylen, dan met media queries uitbreiden.

8. **Vraag**: Wat is een Breakpoint?
   **Antwoord**: De schermgrootte waar de layout verandert.

9. **Vraag**: Wat doet `var(--primary-color)`?
   **Antwoord**: Het haalt de waarde van de CSS-variabele --primary-color op.

10. **Vraag**: Waarom is `:root` belangrijk?
    **Antwoord**: Het is het hoogste niveau voor CSS-variabelen, globaal beschikbaar.

11. **Vraag**: Wat is het verschil tussen `rem` en `em`?
    **Antwoord**: rem is relatief aan root (html), em aan parent element.

12. **Vraag**: Wat doet `transition`?
    **Antwoord**: Het animeert veranderingen van CSS-properties.

13. **Vraag**: Wat is RGBA?
    **Antwoord**: RGB-kleur met een alpha-kanaal voor transparantie.

14. **Vraag**: Hoe maak je afgeronde hoeken?
    **Antwoord**: Met `border-radius: [waarde]`.

15. **Vraag**: Wat is z-index?
    **Antwoord**: De stapelvolgorde van overlappende elementen.

16. **Vraag**: Wat is een Pseudo-class?
    **Antwoord**: Een stijl voor een specifieke staat (bijv. :hover).

17. **Vraag**: Wat is Dark Mode in CSS?
    **Antwoord**: Een alternatief kleurenschema met donkere achtergronden.

18. **Vraag**: Wat is het voordeel van CSS over inline styles?
    **Antwoord**: Scheiding van content en presentatie, herbruikbaarheid.

19. **Vraag**: Wat doet `box-shadow`?
    **Antwoord**: Het voegt een schaduw toe aan een element.

20. **Vraag**: Waarom is Accessibility belangrijk in CSS?
    **Antwoord**: Zodat iedereen de site kan gebruiken, inclusief mensen met beperkingen.

---

# 7. Conclusie

De `style.css` is een meesterwerk van moderne webdesign, waarbij Glassmorphism-technieken worden gecombineerd met herbruikbare CSS-variabelen.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
