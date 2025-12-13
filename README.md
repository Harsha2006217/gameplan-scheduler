# 🎮 GamePlan Scheduler (Legendary Edition)

**Responsive webapplicatie voor jonge gamers om profielen te beheren, vrienden toe te voegen, speelschema's te delen, evenementen te plannen en herinneringen in te stellen.**

---

## 📌 Overzicht

- **Auteur**: Harsha Kanaparthi  
- **Studentnummer**: 2195344  
- **Datum**: 30-09-2025  
- **Versie**: 1.1 (Legendary Update)  
- **Technologieën**: PHP, MySQL, HTML/CSS/Bootstrap 5, JavaScript  
- **Ondersteuning**: Desktop & Mobiel (Volledig Responsief)  

---

## ✨ Nieuwe Functionaliteiten (Versie 1.1)

### 🛠️ Bug Fixes & Verbetervoorstellen
1.  **#1001 Validatie Lege Velden**: Strikt `trim()` check toegevoegd in `functions.php`. Velden met alleen spaties worden nu geweigerd.
2.  **#1004 Datum Validatie**: `checkdate()` toegevoegd. Ongeldige datums (zoals 30 februari of "2025-13-45") worden geweigerd.
3.  **#1002 Notificaties**: `add_event.php` uitgebreid met een dropdown voor herinneringstypes.
4.  **#1003 Navigatieverbetering**: "Evenement toevoegen" knop is nu prominent aanwezig in de header.
5.  **#1006 Sorteren**: Lijsten op het dashboard kunnen nu gesorteerd worden op Datum.

### 🎨 Design Updates ("Legendary" Thema)
- **Glassmorphism**: Semi-transparante kaarten over een geanimeerde achtergrond.
- **Neon Accents**: Blauwe en paarse gloed voor een moderne "gaming" look.
- **Micro-interacties**: Hover-effecten op knoppen en tabellen.
- **Bootstrap 5**: Volledige integratie voor lay-out en modale componenten.

### 📝 Documentatie
- **Code Commentaar**: Elke regel code is voorzien van didactisch commentaar (A-Z uitleg) om de werking uit te leggen aan examinatoren.

---

## ⚙️ Installatie

1. **Omgeving**: Zorg voor XAMPP (Apache + MySQL).
2. **Database**: Importeer `database.sql` via PHPMyAdmin.
3. **Plaatsing**: Kopieer de map `gameplan-scheduler` naar `htdocs`.
4. **Uitvoeren**: Ga naar `http://localhost/gameplan-scheduler`.

---

## 📁 Bestandsstructuur

- **Core**: 
  - `functions.php`: Het hart van de applicatie (logica en validatie).
  - `db.php`: Veilige databaseverbinding.
- **Styling**: `style.css` (Custom CSS).
- **Pagina's**:
  - `index.php`: Dashboard en Kalender.
  - `add_*.php` / `edit_*.php`: Formulieren voor beheer.
  - `profile.php`: Profielbeheer.

---

## 👥 Credits

Ontwikkeld door Harsha Kanaparthi voor het MBO-4 Software Development examen.