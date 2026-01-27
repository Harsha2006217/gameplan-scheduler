# CSS & STYLING DOCUMENTATION
## GamePlan Scheduler - Design System & Styles

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# 1. Design Overview / Ontwerp Overzicht

## Theme / Thema
- **Style**: Dark Gaming Theme
- **Primary Colors**: Dark backgrounds with neon accents
- **Framework**: Bootstrap 5

## Color Palette / Kleurenpalet

| Color | Hex Code | Usage |
|-------|----------|-------|
| **Background Dark** | #1a1a2e | Main page background |
| **Card Background** | #16213e | Card containers |
| **Primary Accent** | #0f3460 | Headers, borders |
| **Neon Blue** | #00d9ff | Links, highlights |
| **Neon Purple** | #e94560 | Buttons, accents |
| **Text Primary** | #ffffff | Main text |
| **Text Secondary** | #b0b0b0 | Muted text |
| **Success** | #28a745 | Success messages |
| **Danger** | #dc3545 | Error messages |
| **Warning** | #ffc107 | Warning messages |

---

# 2. Typography / Typografie

## Fonts / Lettertypen

| Element | Font Family | Size | Weight |
|---------|-------------|------|--------|
| **Body** | 'Segoe UI', system-ui | 16px | 400 |
| **Headings (h1)** | 'Segoe UI', system-ui | 2.5rem | 700 |
| **Headings (h2)** | 'Segoe UI', system-ui | 2rem | 600 |
| **Headings (h3)** | 'Segoe UI', system-ui | 1.5rem | 600 |
| **Navigation** | 'Segoe UI', system-ui | 1rem | 500 |
| **Buttons** | 'Segoe UI', system-ui | 1rem | 600 |

---

# 3. Component Styles / Component Stijlen

## 3.1 Cards / Kaarten

```css
.card {
    background: rgba(22, 33, 62, 0.9);  /* Semi-transparent dark */
    border: 1px solid rgba(0, 217, 255, 0.3);  /* Neon border */
    border-radius: 15px;
    backdrop-filter: blur(10px);  /* Glassmorphism effect */
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.card:hover {
    transform: translateY(-5px);  /* Lift on hover */
    border-color: #00d9ff;  /* Brighter border */
}
```

## 3.2 Buttons / Knoppen

```css
/* Primary Button */
.btn-primary {
    background: linear-gradient(135deg, #e94560, #0f3460);
    border: none;
    border-radius: 25px;
    padding: 10px 25px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(233, 69, 96, 0.4);
}

/* Secondary Button */
.btn-secondary {
    background: transparent;
    border: 2px solid #00d9ff;
    color: #00d9ff;
}
```

## 3.3 Forms / Formulieren

```css
.form-control {
    background: rgba(26, 26, 46, 0.8);
    border: 1px solid rgba(0, 217, 255, 0.3);
    color: #ffffff;
    border-radius: 10px;
}

.form-control:focus {
    background: rgba(26, 26, 46, 0.9);
    border-color: #00d9ff;
    box-shadow: 0 0 10px rgba(0, 217, 255, 0.3);
}

.form-control::placeholder {
    color: #666;
}
```

## 3.4 Tables / Tabellen

```css
.table {
    background: transparent;
    color: #ffffff;
}

.table thead {
    background: rgba(15, 52, 96, 0.8);
}

.table tbody tr:hover {
    background: rgba(0, 217, 255, 0.1);
}

.table td, .table th {
    border-color: rgba(255, 255, 255, 0.1);
    vertical-align: middle;
}
```

## 3.5 Navigation / Navigatie

```css
.navbar {
    background: rgba(22, 33, 62, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
}

.nav-link {
    color: #ffffff;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #00d9ff;
}

.nav-link.active {
    color: #e94560;
}
```

## 3.6 Alerts / Meldingen

```css
.alert {
    border-radius: 10px;
    border: none;
}

.alert-success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border-left: 4px solid #dc3545;
}

.alert-info {
    background: rgba(0, 217, 255, 0.2);
    color: #00d9ff;
    border-left: 4px solid #00d9ff;
}
```

---

# 4. Special Effects / Speciale Effecten

## 4.1 Glassmorphism

```css
.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
```

## 4.2 Neon Glow

```css
.neon-glow {
    text-shadow: 0 0 10px #00d9ff,
                 0 0 20px #00d9ff,
                 0 0 30px #00d9ff;
}

.neon-border {
    box-shadow: 0 0 5px #00d9ff,
                0 0 10px #00d9ff,
                inset 0 0 5px rgba(0, 217, 255, 0.3);
}
```

## 4.3 Animations / Animaties

```css
/* Fade In */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.5s ease forwards;
}

/* Pulse */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.pulse {
    animation: pulse 2s infinite;
}
```

---

# 5. Responsive Design / Responsive Ontwerp

## Breakpoints / Breekpunten

| Breakpoint | Screen Size | Target |
|------------|-------------|--------|
| xs | < 576px | Mobile phones |
| sm | ≥ 576px | Large phones |
| md | ≥ 768px | Tablets |
| lg | ≥ 992px | Laptops |
| xl | ≥ 1200px | Desktops |
| xxl | ≥ 1400px | Large screens |

## Mobile Adjustments / Mobiele Aanpassingen

```css
@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    h1 { font-size: 1.75rem; }
    h2 { font-size: 1.5rem; }
}
```

---

# 6. CSS File Structure / CSS Bestandsstructuur

**File**: `style.css` (~400 lines)

```
style.css
├── 1. Root Variables (CSS Custom Properties)
├── 2. Global Reset & Base Styles
├── 3. Typography
├── 4. Layout & Grid
├── 5. Navigation
├── 6. Cards
├── 7. Buttons
├── 8. Forms
├── 9. Tables
├── 10. Alerts
├── 11. Special Effects
├── 12. Animations
├── 13. Utilities
└── 14. Media Queries
```

---

# 7. Bootstrap 5 Classes Used

| Category | Classes |
|----------|---------|
| **Layout** | container, row, col-*, d-flex, justify-content-*, align-items-* |
| **Spacing** | m-*, p-*, mb-*, mt-*, mx-auto |
| **Typography** | text-center, text-muted, fw-bold, fs-* |
| **Colors** | bg-*, text-*, btn-* |
| **Components** | card, table, form-control, alert, navbar |
| **Utilities** | rounded, shadow, border, w-100, h-100 |

---

# 8. Design Decisions / Ontwerpbeslissingen

| Decision | Rationale |
|----------|-----------|
| **Dark Theme** | Reduces eye strain for gamers, modern look |
| **Neon Accents** | Gaming aesthetic, visual interest |
| **Glassmorphism** | Modern design trend, depth perception |
| **Rounded Corners** | Friendly, approachable feel |
| **Subtle Animations** | Better UX without being distracting |
| **Responsive Design** | Works on all devices |

---

**END OF CSS DOCUMENTATION**
