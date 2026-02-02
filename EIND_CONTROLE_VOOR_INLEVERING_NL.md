# ✅ FINALE CHECKLIST VOOR INLEVERING
## GamePlan Scheduler - De allerlaatste check

Gebruik deze lijst om er 100% zeker van te zijn dat je portfolio klaar is voor de inlevering.

---

### 1. Technische Checkboxen 🛠️
- [ ] **Database**: Staat de `database.sql` in de root-map? (Zodat de leraar hem kan importeren).
- [ ] **DB Connectie**: Staat in `db.php` het wachtwoord op leeg `''`? (Dit is de standaard voor XAMPP op school).
- [ ] **Logout**: Werkt de Uitlog-knop in de menubalk? (Ik heb dit gefikst, maar test het zelf even!).
- [ ] **Validatie**: Probeer iets toe te voegen met alleen spaties. Krijg je een foutmelding? (Dan werkt bugfix #1001).
- [ ] **Datum**: Probeer een datum in het verleden te plannen. Wordt dit geweigerd? (Dan werkt bugfix #1004).

### 2. Documentatie Checkboxen 📝
- [ ] **Index**: Staat `SUBMISSION_INDEX.md` bovenaan in je map? Dit is je "menukaart".
- [ ] **Uitleg**: Heeft elk PHP bestand een bijbehorend `UITLEG_*.md` bestand?
- [ ] **Gidsen**: Zitten de `INSTALLATIE_HANDLEIDING_NL.md` en `GEBRUIKERSHANDLEIDING_NL.md` erbij?
- [ ] **Examen**: Heb je het `EXAMEN_SPIEKBRIEFJE_NL.md` en de `PRESENTATIE_SLIDES_NL.md` al bekeken?

### 3. De Map-Structuur 📁
Zorg dat de volgende mappen en bestanden aanwezig zijn:
*   `gameplan-scheduler/` (hoofdmap)
    *   `vendor/` (als je die hebt, maar we gebruikten geen externe libs).
    *   `css/` of `style.css` in de root.
    *   `js/` of `script.js` in de root.
    *   Alle `.php` bestanden.
    *   Alle `.md` documentatie bestanden.
    *   `database.sql`.

### 4. Backup 💾
- [ ] Heb je een kopie van de hele map op een USB-stick of OneDrive staan?
- [ ] Werkt de site ook als je de map naar een andere computer kopieert?

---
**GEFELICITEERD!** Als je alles hebt afgevinkt, ben je klaar voor die 10! 🏆
