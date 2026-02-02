# UITLEG style.css (Regel voor Regel)
## GamePlan Scheduler - Vormgeving

**Bestand**: `style.css`
**Doel**: Het uiterlijk van de website bepalen (Kleuren, Lettertypes, Layout).

---

### Regel 18-50: CSS Variabelen (`:root`)
```css
:root {
    --bg-dark: #0a0a0f;
    --accent-blue: #0d6efd;
    --glass-bg: rgba(22, 22, 35, 0.85);
}
```
**Uitleg**:
*   Dit is het "Kleurenpalet" van de site.
*   We geven kleuren een naam (zoals `--accent-blue`).
*   Als we de hele site roze willen maken, hoeven we dat alleen HIER aan te passen.
*   **Keuze**: We kiezen voor een donker thema (`#0a0a0f`) omdat gamers vaak in de avond spelen.

### Regel 68: Body Styling
```css
body {
    background: linear-gradient(135deg, ...);
    font-family: 'Segoe UI', ...;
}
```
**Uitleg**:
*   `linear-gradient`: De achtergrond is niet saai zwart, maar een verloop van donkerblauw naar zwart.
*   `Segoe UI`: Het standaard lettertype van Windows.

### Regel 239: Glassmorphism (Het Glas Effect)
```css
.card {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
}
```
**Uitleg**:
*   Dit is de moderne "look" van de site.
*   De kaarten (blokken op het scherm) zijn half doorzichtig (`glass-bg`).
*   `backdrop-filter: blur`: Alles *achter* de kaart wordt wazig gemaakt, net als matglas.

### Regel 490: Responsiviteit (Mobiele Weergave)
```css
@media (max-width: 992px) { ... }
```
**Uitleg**:
*   `@media`: Een regel die zegt: "Pas de volgende regels alleen toe als..."
*   "... het scherm kleiner is dan 992 pixels (Tablets/Laptops)".
*   Hier zorgen we dat de tabellen niet van het scherm af vallen op kleine schermen.

### Regel 582: Animaties
```css
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
```
**Uitleg**:
*   Als de pagina laadt, "glijdt" de inhoud zachtjes omhoog en wordt zichtbaar (`opacity: 0` -> `1`).
*   Dit geeft een luxe gevoel aan de app.

---
**Samenvatting**: 600 regels aan styling die zorgen voor de "Dark Gaming Mode" sfeer en zorgen dat de site werkt op mobiel en desktop.
