# 🎨 DESIGN SYSTEEM & STYLING
## GamePlan Scheduler - Visuele Identiteit

Dit document legt uit hoe de website zijn moderne "gaming" look krijgt en welke keuzes er zijn gemaakt voor de vormgeving.

---

### 1. Kleurenpalet (Neon Gaming)
We gebruiken een donker thema met felle accenten om de sfeer van gaming platforms (zoals Discord of Steam) na te bootsen.

*   **Achtergrond**: Donkerblauw/Zwart (`#0a0e17`) - Rustgevend voor de ogen tijdens lange gamesessies.
*   **Accent 1 (Neon Blauw)**: `#00d2ff` - Gebruikt voor knoppen en actieve links.
*   **Accent 2 (Neon Paars)**: `#9d50bb` - Voor overgangen en hover-effecten.
*   **Succes (Groen)**: `#28a745` - Voor belangrijke acties zoals "Evenement Toevoegen".

---

### 2. Glassmorphism
Het belangrijkste design-element is 'Glassmorphism'. Dit geeft de website een premium, moderne uitstraling.
*   **Hoe**: We gebruiken `rgba(255, 255, 255, 0.1)` voor kaarten.
*   **Effect**: Een semi-transparant "matglas" effect waardoor de achtergrond subtiel zichtbaar blijft.
*   **CSS**: Gebruik van `backdrop-filter: blur(10px)` om de achtergrond te vervagen.

---

### 3. Typografie (Lettertypes)
*   **Google Fonts**: We gebruiken 'Inter' of 'Roboto'.
*   **Waarom**: Deze lettertypes zijn zeer strak en goed leesbaar op zowel mobiel als desktop.
*   **Hierarchie**: Grote dikke koppen voor secties, kleine subtiele letters voor notities.

---

### 4. Responsive Design (Aanpasbaarheid)
De website is gebouwd met **Bootstrap 5**.
*   **Grids**: We gebruiken het 12-koloms systeem.
*   **Mobiel**: Op een telefoon worden de blokken onder elkaar gezet (stacking).
*   **Desktop**: Op een groot scherm staan vrienden en agenda naast elkaar.
*   **Knoppen**: Alle interactieve elementen zijn minimaal 44x44 pixels groot, zodat ze makkelijk met een vinger te bedienen zijn op een touchscreen.

---

### 5. Micro-animaties
*   **Buttons**: Knoppen lichten op of worden iets groter als je eroverheen gaat (Hover).
*   **Transitions**: Overgangen tussen kleuren duren 0.3 seconden voor een vloeiend gevoel.

---
**EINDE DESIGN DOCUMENTATIE**
