# ✅ KWALITEITS-CHECKLIST
## GamePlan Scheduler - Code Review & Kwaliteit

Dit document is gebruikt tijdens het bouwen om te garanderen dat de code aan de hoogste standaarden (MBO-4) voldoet.

---

### 1. Code Structuur (Clean Code)
- [x] **Indentatie**: Is alle code netjes ingesprongen? (Gebruik van 4 spaties/tabs).
- [x] **Naamgeving**: Hebben variabelen en functies duidelijke namen? (Bijv. `$user_id` ipv `$u`).
- [x] **Commentaar**: Is elke complexe functie voorzien van uitleg in het Nederlands en Engels?
- [x] **DRY**: Wordt dezelfde code niet op meerdere plekken herhaald? (Gebruik van `functions.php`).

---

### 2. Veiligheid (Security)
- [x] **SQL Injectie**: Gaan alle queries via `PDO::prepare`?
- [x] **XSS**: Wordt alle gebruikersinvoer geëscaped met `safeEcho` (htmlspecialchars)?
- [x] **Wachtwoorden**: Worden wachtwoorden **nooit** als tekst opgeslagen, maar altijd gehasht?
- [x] **Sessies**: Wordt de sessie id ververst (`session_regenerate_id`) na het inloggen?

---

### 3. Functionaliteit (User Experience)
- [x] **Foutmeldingen**: Krijgt de gebruiker bij een fout een duidelijke melding in plaats van een witte pagina?
- [x] **Responsive**: Werkt de navigatiebalk ook goed op een mobiele telefoon?
- [x] **Feedback**: Wordt een actie (zoals het wijzigen van een vriend) bevestigd met een melding?
- [x] **Validatie**: Wordt zowel aan de voorkant (JS) als achterkant (PHP) de data gecontroleerd?

---

### 4. Database Integriteit
- [x] **Foreign Keys**: Zijn de tabellen logisch aan elkaar gekoppeld?
- [x] **Cascading**: Wordt gerelateerde data netjes opgeruimd als een hoofd-item wordt verwijderd?
- [x] **Datatypes**: Worden de juiste types gebruikt (bijv. `DATETIME` voor datums)?

---
**RESULTAAT**: De applicatie is 100% goedgekeurd volgens deze checklist. Er zijn geen openstaande bugs of veiligheidslekken bekend.
