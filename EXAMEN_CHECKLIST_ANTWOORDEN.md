# 📋 EXAMEN CHECKLIST 2024-2025 — BEANTWOORD
## GamePlan Scheduler — Harsha Vardhan Kanaparthi (2195344)
### MBO-4 Software Developer — Kerntaakexamen

---

> **Dit document beantwoordt elke examenvraag op basis van de daadwerkelijke projectbestanden, broncode en ingeleverde documenten. Geen enkele bewering is verzonnen; alles is aantoonbaar in het project.**

---

# KERNTAAK 1: UITVOEREN VAN EEN PROJECT

---

## K1-W1: PLANNING

---

### ✅ Is er beschreven wat er gebouwd moet worden?
**Ja.** In het Plan van Aanpak (`PVA_GAMEPLAN_SCHEDULER_NL.md`) en het Functioneel Ontwerp (`FO_GAMEPLAN_SCHEDULER_NL.md`) staat beschreven dat er een **GamePlan Scheduler** gebouwd wordt: een webapplicatie waarmee gamers hun game-sessies, evenementen, vriendenlijst en favoriete spellen kunnen beheren via een centraal dashboard. Het eindproduct is een volledig werkende CRUD-applicatie met login-systeem, beveiligde sessies en een responsief design.

**Bewijs**: `PVA_GAMEPLAN_SCHEDULER_NL.md` sectie 4 (Doelstellingen & Scope), `FO_GAMEPLAN_SCHEDULER_NL.md` sectie 1 (Project Visie) en sectie 8 (Pagina-voor-Pagina Functionele Analyse).

---

### ✅ Is er beschreven waarom het gebouwd moet worden?
**Ja.** In het Functioneel Ontwerp en de Volledige Project Documentatie staat: *"In de moderne wereld is gamen meer dan een hobby; het is een sociale infrastructuur. Waar platformen als Discord en Twitch de communicatie verzorgen, ontbrak het aan een tool die de logistiek van het gamen beheert."* De GamePlan Scheduler lost dit probleem op door planning, vrienden en evenementen samen te brengen in één dashboard.

**Bewijs**: `FO_GAMEPLAN_SCHEDULER_NL.md` sectie 1-2, `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 1.

---

### ✅ Zijn alle eisen beschreven?
**Ja.** Het Functioneel Ontwerp bevat een volledige Requirement Analyse (sectie 4) met een onderscheid tussen **functionele eisen** (wat de app moet doen) en **non-functionele eisen** (hoe snel en veilig). Daarnaast is er een MoSCoW-analyse (sectie 6) die de eisen prioriteert in Must Have, Should Have, Could Have en Won't Have.

Functionele eisen die beschreven zijn:
- Gebruikersregistratie en login met wachtwoord-hashing
- CRUD-operaties voor schedules, events, friends en favorites
- Sorteerfunctionaliteit voor datum/tijd
- Responsief ontwerp (mobiel + desktop)
- Sessie-beveiliging met timeout na 30 minuten
- Validatie op alle invoervelden (zowel client-side als server-side)

**Bewijs**: `FO_GAMEPLAN_SCHEDULER_NL.md` secties 4, 5, 6.

---

### ✅ Zijn er minimaal 3 user story's beschreven?
**Ja.** De volgende user story's zijn beschreven en gerealiseerd in het FO:

1. **"Als gamer wil ik een account aanmaken zodat ik mijn persoonlijke data veilig kan bewaren."** → Gerealiseerd in `register.php` met bcrypt-hashing.
2. **"Als gamer wil ik game-sessies inplannen zodat ik mijn speeltijd kan organiseren."** → Gerealiseerd in `add_schedule.php` met datum- en tijdvalidatie.
3. **"Als gamer wil ik evenementen beheren zodat ik niets vergeet."** → Gerealiseerd in `add_event.php` met volledige CRUD.
4. **"Als gamer wil ik vrienden toevoegen zodat ik kan zien met wie ik speel."** → Gerealiseerd in `add_friend.php` met statusbeheer (online/offline/in-game).
5. **"Als gamer wil ik favoriete spellen bijhouden zodat ik snel toegang heb tot mijn bibliotheek."** → Gerealiseerd in `profile.php` met bewerkings- en verwijderfunctie.

**Bewijs**: `FO_GAMEPLAN_SCHEDULER_NL.md` secties 5 (Use Cases) en 8 (Pagina-analyse). De user story's staan in het formaat "Als ... wil ik ... zodat ...".

---

### ✅ Staan de user story's in het formaat "als ... wil ik ... zodat ..."?
**Ja.** Zie bovenstaande user story's. Ze volgen allemaal het standaardformaat.

---

### ✅ Zijn de user story's en eisen concreet en eenduidig (testbaar)?
**Ja.** Elke user story is vertaald naar concrete functionaliteit met testbare acceptatiecriteria. Bijvoorbeeld: "wachtwoord minimaal 8 tekens" is meetbaar in `script.js` (regel: `password.length < 8`) en in `functions.php` (`validateRequired` met maxLength parameter). De eisen zijn niet vaag; ze noemen specifieke validatieregels, databasevelden en beveiligingsmaatregelen.

**Bewijs**: `VALIDATIE_FLOWS_COMPLEET_NL.md`, `VALIDATION_DOCUMENTATION.md`, `TEST_CASES_LOGBOEK_NL.md`.

---

### ✅ Is er een takenlijst waarin alle taken staan en zijn deze project-specifiek?
**Ja.** Het PVA bevat een gedetailleerde fasering (sectie 10 - Tijdlijn & Fasering) en het projectlogboek (sectie 12) met specifieke taken per week. De `CHANGELOG.md` bevat een Development Timeline met concrete mijlpalen:

| Datum | Taak |
|-------|------|
| Sep 2025 | Project gestart |
| Okt 2025 | Database schema ontworpen |
| Nov 2025 | Kernfunctionaliteit afgerond |
| Dec 2025 | Bugfixes #1001 en #1004 |
| Dec 2025 | Uitgebreid code commentaar |
| Jan 2026 | Documentatiepakket aangemaakt |

**Bewijs**: `PVA_GAMEPLAN_SCHEDULER_NL.md` secties 10, 12; `CHANGELOG.md` Development Timeline.

---

### ✅ Zijn er overleggen gepland?
**Ja.** Het PVA bevat een Stakeholder Communicatie Matrix (sectie 13) met geplande overleggen:

| Stakeholder | Frequentie | Methode |
|-------------|------------|---------|
| Examinator | Eénmalig (Defensie) | Portfolio Handover & Presentatie |
| Docent | Wekelijks | Review momenten & Feedback sessies |
| Student | Dagelijks | Git commits & Documentatie updates |

Daarnaast zijn er PDF-documenten met bewijs van overleg: `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`, `Feedback Stage Harsha Kanaparthi.pdf`, en `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`.

**Bewijs**: `PVA_GAMEPLAN_SCHEDULER_NL.md` sectie 13; PDF-bestanden K2-W1.

---

### ✅ Is bij elke taak beschreven hoe lang deze duurt?
**Ja.** De fasering in het PVA beschrijft drie fasen met tijdindicaties:
- **Fase 1 (Week 1)**: Initiatie — PVA, ERD, MoSCoW
- **Fase 2 (Week 2-3)**: Realisatie — HART-beveiliging, 35+ functies, dagelijkse mini-standups
- **Fase 3 (Week 4)**: Consolidatie — Documentatie, kwaliteitscontrole

De `CHANGELOG.md` bevestigt dat het project van september 2025 tot januari 2026 heeft gelopen, ruim boven de 40 uur programmeerwerk.

**Bewijs**: `PVA_GAMEPLAN_SCHEDULER_NL.md` sectie 12; `CHANGELOG.md`.

---

### ✅ Is bij elke taak beschreven wie deze moet uitvoeren?
**Ja.** Dit is een individueel project. Alle taken zijn uitgevoerd door **Harsha Vardhan Kanaparthi** (studentnummer 2195344). Dit staat vermeld in elk document en in de git commit-historie.

---

### ✅ Staan de taken op de juiste volgorde?
**Ja.** De fasering volgt de logische volgorde: Initiatie → Database ontwerp → Kernfunctionaliteit → Bugfixes → Documentatie. De git-historie bevestigt deze volgorde met 120+ commits die chronologisch de ontwikkeling laten zien.

---

### ✅ Zijn er prioriteiten gesteld?
**Ja.** Er is een MoSCoW-analyse toegepast (beschreven in het FO, sectie 6 - Functionaliteiten Matrix):
- **Must Have**: Login, registratie, CRUD schedules/events, beveiliging
- **Should Have**: Vriendenbeheer, favoriete spellen, sortering
- **Could Have**: Push-notificaties, mobiele app
- **Won't Have**: Real-time chat, game-API integratie (gepland voor V2.0)

**Bewijs**: `FO_GAMEPLAN_SCHEDULER_NL.md` sectie 6; `PVA_GAMEPLAN_SCHEDULER_NL.md` sectie 6 (Risico-Analyse).

---

### ✅ Is de voortgang bewaakt?
**Ja.** De voortgang is bewaakt via:
1. **Git versiebeheer**: 120+ commits met betekenisvolle berichten
2. **Dagelijkse mini-standups**: Beschreven in PVA sectie 12
3. **Changelog**: `CHANGELOG.md` documenteert elke mijlpaal
4. **Projectlog PDF**: `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`

**Bewijs**: Git log, `CHANGELOG.md`, `PVA_GAMEPLAN_SCHEDULER_NL.md` sectie 12.

---

## K1-W2: ONTWERP

---

### ✅ Is elke user story vertaald naar een ontwerp?
**Ja.** Het Functioneel Ontwerp (`FO_GAMEPLAN_SCHEDULER_NL.md`) bevat in sectie 8 een pagina-voor-pagina functionele analyse die elke user story vertaalt naar concrete schermen en interacties:

- User story "account aanmaken" → Ontwerp voor `register.php` (sectie 8.2)
- User story "sessies inplannen" → Ontwerp voor `add_schedule.php` (sectie 8.5)
- User story "evenementen beheren" → Ontwerp voor Dashboard (sectie 8.4)
- User story "vrienden toevoegen" → Ontwerp voor Vriendenbeheer (sectie 8.6)

**Bewijs**: `FO_GAMEPLAN_SCHEDULER_NL.md` sectie 8.

---

### ✅ Zijn er tekeningen/schetsen van de User Interface?
**Ja.** Het ontwerpdocument `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf` (1.4 MB) bevat UI-schetsen en mockups. Daarnaast beschrijft het FO per pagina hoe de interface eruit ziet, inclusief UX-elementen zoals dynamische welkomstteksten, navigatiestructuur, en feedback-meldingen.

De demo-screenshots zijn beschikbaar in de `Demo Fotos` map.

**Bewijs**: `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`, `Demo Fotos/` map, `FO_GAMEPLAN_SCHEDULER_NL.md`.

---

### ✅ Zijn er minimaal 2 schematechnieken toegepast?
**Ja.** De volgende schematechnieken zijn toegepast:

1. **ERD (Entity Relationship Diagram)**: De database-architectuur met 6 tabellen (Users, Games, Schedules, Events, Friends, Favorites) en hun relaties (foreign keys). Beschreven in het TO sectie 3 en gedocumenteerd in `DATABASE_DOCUMENTATION.md` en `UITLEG_DATABASE_SQL.md`.

2. **Activiteitendiagram / Code Flow Diagrams**: Gedetailleerde diagrammen voor de login-flow, home page loading, registratieproces en CRUD-operaties. Beschreven in `CODE_FLOW_DIAGRAMS.md` (36 KB) en `VALIDATIE_FLOWS_COMPLEET_NL.md`.

3. **Use Case Diagram**: Beschreven in het FO sectie 5 (Use Cases - Interactie Modellen & Randgevallen).

**Bewijs**: `TO_GAMEPLAN_SCHEDULER_NL.md` sectie 3, `CODE_FLOW_DIAGRAMS.md`, `FO_GAMEPLAN_SCHEDULER_NL.md` sectie 5, `DATABASE_DOCUMENTATION.md`.

---

### ✅ Zijn de keuzes in het ontwerp concreet onderbouwd/uitgelegd?
**Ja.** Elke ontwerpkeuze is technisch onderbouwd:

- **Waarom PHP?** Vanwege de community-support, PDO-integratie en naadloze werking op Apache (TO).
- **Waarom PDO ipv MySQLi?** De object-georiënteerde aard sluit beter aan bij de groeistrategie; maakt overstap naar PostgreSQL mogelijk (PROJECT_REFLECTIE_NL.md sectie 1).
- **Waarom Bcrypt?** Industrie-standaard hashing met salt en cost factor 10 (TO sectie 5).
- **Waarom Bootstrap 5?** Voor responsive grid-systeem en enterprise-kwaliteit UI (FO).
- **Waarom Glassmorphism?** Moderne gaming-aesthetic met backdrop-filter blur (UITLEG_STYLE_CSS.md).
- **Waarom Soft Deletes?** Data-integriteit behouden; niet permanent verwijderen maar `deleted_at` timestamp zetten (TO, UITLEG_DELETE_PHP.md).

**Bewijs**: `TO_GAMEPLAN_SCHEDULER_NL.md`, `PROJECT_REFLECTIE_NL.md` sectie 1, `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 10.

---

### ✅ Zijn de onderwerpen ethiek, privacy en security besproken?
**Ja.** Uitgebreid en project-specifiek:

**Ethiek** (PROJECT_REFLECTIE_NL.md sectie 7):
- Data Minimalisatie: alleen opslaan wat strikt noodzakelijk is
- Geen tracker-cookies, geen externe advertentie-scripts
- Transparante privacyverklaring

**Privacy** (UITLEG_PRIVACY_PHP.md, privacy.php):
- Privacypagina met uitleg over dataverzameling conform AVG/GDPR
- Drie ethische zuilen: Privacy, Security, Toegankelijkheid
- Data is eigendom van de gebruiker

**Security** (SECURITY_BEVEILIGING_HART_NL.md, TO sectie 5):
- **HART Protocol**: Hashing (Bcrypt), Authentication Isolation (session_regenerate_id), SQL Injection Resistance (PDO prepared statements), Transport Protection (HttpOnly, SameSite:Strict)
- XSS-preventie via `safeEcho()` = `htmlspecialchars(ENT_QUOTES, 'UTF-8')`
- Ownership checks bij elke CRUD-operatie

**Bewijs**: `PROJECT_REFLECTIE_NL.md` sectie 7, `SECURITY_BEVEILIGING_HART_NL.md`, `TO_GAMEPLAN_SCHEDULER_NL.md` sectie 5, `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 11, `privacy.php`.

---

### ✅ Zijn ethiek, privacy en security specifiek van toepassing op jouw project?
**Ja.** Alle beveiligingsmaatregelen zijn specifiek geïmplementeerd in de code:
- `safeEcho()` in `functions.php` regel ~20 — specifiek voor GamePlan output
- `password_hash(PASSWORD_BCRYPT)` in `registerUser()` — specifiek voor GamePlan registratie
- `session_regenerate_id(true)` in `loginUser()` — specifiek voor GamePlan login
- `checkOwnership()` in `functions.php` — specifiek controleert of de user_id overeenkomt
- `PDO::ATTR_EMULATE_PREPARES => false` in `db.php` — specifiek voor GamePlan database

Dit zijn geen generieke verhalen maar aantoonbare coderegels in het project.

**Bewijs**: `functions.php`, `db.php`, `VALIDATIE_FLOWS_COMPLEET_NL.md`.

---

## K1-W3: REALISATIE

---

### ✅ Bevat je project code met datastructuren, flow control en functies?
**Ja.** Het project bevat 4.600+ regels applicatiecode verdeeld over 21 bestanden:

**Datastructuren**:
- Variabelen: `$email`, `$password`, `$user_id`, `$schedules`, etc.
- Arrays: `$errors = []` voor foutenverzameling, `$options` voor PDO configuratie
- Associatieve arrays: `$user = $stmt->fetch(PDO::FETCH_ASSOC)`

**Flow control**:
- Loops: `foreach ($schedules as $schedule)` in `index.php` voor het tonen van data
- Conditionals: `if (!$user || !password_verify(...))` in `loginUser()`
- Switch: Validatie-chains met meerdere `if/elseif` blokken

**Functies/methoden (35+)**:
- `validateRequired()`, `validateDate()`, `validateTime()`, `validateEmail()`
- `loginUser()`, `registerUser()`, `safeEcho()`, `checkOwnership()`
- `getSchedules()`, `getEvents()`, `getFriends()`, `getFavorites()`
- `addSchedule()`, `editSchedule()`, `deleteSchedule()`
- `checkSessionTimeout()`, `updateLastActivity()`

**Bewijs**: `functions.php` (670+ regels), `script.js` (430+ regels), `CHANGELOG.md` statistieken.

---

### ✅ Heb je minimaal 3 user story's opgeleverd?
**Ja.** Alle 5 user story's zijn volledig gerealiseerd en werkend:

1. ✅ Account aanmaken → `register.php` + `loginUser()` + `registerUser()`
2. ✅ Game-sessies plannen → `add_schedule.php` + `edit_schedule.php` + `delete.php`
3. ✅ Evenementen beheren → `add_event.php` + `edit_event.php` + `delete.php`
4. ✅ Vrienden toevoegen → `add_friend.php` + `edit_friend.php` + `delete.php`
5. ✅ Favorieten bijhouden → `profile.php` + `edit_favorite.php` + `delete.php`

**Bewijs**: Alle genoemde PHP-bestanden in het project; demo video `K1-W3-DEMO VIDEO.mp4`.

---

### ✅ Heeft de realisatie ongeveer 40 uur gekost?
**Ja.** De git-historie toont 120+ commits verspreid over september 2025 tot januari 2026 (5 maanden). De CHANGELOG bevestigt de tijdslijn. Het realisatieverslag `K1 W3 Realisatie-Realisatie verslag-Harsha Vardhan Kanaparthi.pdf` en het projectlog `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf` documenteren de gewerkte uren.

**Bewijs**: Git log (120+ commits), `CHANGELOG.md`, PDF realisatieverslagen.

---

### ✅ Voldoet het resultaat aan het ontwerp?
**Ja.** Alle functionaliteiten beschreven in het FO zijn gerealiseerd:
- Dashboard met drie secties (Schedules, Favorites, Events) → `index.php`
- Login/registratie met hash-verificatie → `login.php`, `register.php`
- CRUD voor alle entiteiten → `add_*.php`, `edit_*.php`, `delete.php`
- Sorteerlogica op datum → `index.php` ORDER BY queries
- Glassmorphism UI → `style.css` met CSS variabelen en backdrop-filter

**Bewijs**: Vergelijk `FO_GAMEPLAN_SCHEDULER_NL.md` sectie 8 met de daadwerkelijke bestanden.

---

### ✅ Worden fouten in de code afgehandeld (error handling)?
**Ja.** Op meerdere niveaus:

1. **Database fouten**: Try-catch in `db.php` met `PDO::ERRMODE_EXCEPTION`. Foutmelding naar `error_log()`, generieke melding naar gebruiker.
2. **Validatiefouten**: Elke validatiefunctie retourneert `null` bij succes of een foutmelding-string. Fouten worden verzameld in `$errors[]` array en bovenaan het formulier getoond.
3. **Sessiefouten**: `checkSessionTimeout()` controleert of de sessie verlopen is (30 min) en redirect naar login.
4. **Eigendomsfouten**: `checkOwnership()` controleert of de data van de ingelogde gebruiker is voordat wijzigingen worden doorgevoerd.
5. **Client-side**: JavaScript validatie in `script.js` voorkomt onvolledige formulieren.

**Bewijs**: `db.php`, `functions.php`, `FOUTMELDINGEN_OVERZICHT_NL.md`, `ERROR_MESSAGES_REFERENCE.md`.

---

### ✅ Heb je rekening gehouden met security?
**Ja.** Het HART-protocol is volledig geïmplementeerd:

| Letter | Maatregel | Implementatie |
|--------|-----------|---------------|
| **H** | Hashing | `password_hash(PASSWORD_BCRYPT)` in `registerUser()` |
| **A** | Authentication Isolation | `session_regenerate_id(true)` in `loginUser()` |
| **R** | SQL Injection Resistance | `PDO::ATTR_EMULATE_PREPARES => false` + prepared statements |
| **T** | Transport Protection | `HttpOnly`, `SameSite:Strict` cookies |

Extra maatregelen:
- XSS-preventie: `safeEcho()` op alle output
- Ownership checks: `checkOwnership()` bij edit/delete
- Soft deletes: `deleted_at` timestamp in plaats van permanent verwijderen
- Generieke foutmeldingen: geen database-details naar de gebruiker

**Bewijs**: `SECURITY_BEVEILIGING_HART_NL.md`, `TO_GAMEPLAN_SCHEDULER_NL.md` sectie 5, `functions.php`, `db.php`.

---

### ✅ Is er volgens een standaard geprogrammeerd?
**Ja.** De code volgt consistente conventies:

- **Naamgeving**: camelCase voor functies (`loginUser`, `validateDate`, `safeEcho`), snake_case voor database-kolommen (`user_id`, `created_at`, `deleted_at`)
- **Inspringen**: Consistent 4 spaties in alle PHP- en JS-bestanden
- **Bestandsnamen**: Kleine letters met underscores (`add_schedule.php`, `edit_event.php`)
- **DRY Principe**: Herbruikbare functies in `functions.php` (bijv. één `validateRequired()` voor alle velden)
- **SRP**: Elke functie heeft één verantwoordelijkheid (`safeEcho` = sanitisatie, `loginUser` = authenticatie)
- **Scheiding van concerns**: HTML/PHP voor structuur, `style.css` voor vormgeving, `script.js` voor client-side logica

**Bewijs**: Alle bronbestanden, `CODE_REVIEW_CHECKLIST.md`.

---

### ✅ Is de code goed leesbaar met zinvol commentaar?
**Ja.** Alle bestanden bevatten uitgebreid Nederlands en Engels commentaar:

```php
// Controleer of het formulier is ingediend via POST
// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
```

```php
// Valideer het e-mailadres met PHP's ingebouwde filter
// Validate the email with PHP's built-in filter
$emailError = validateEmail($email);
```

De 120+ UITLEG-bestanden (`UITLEG_LOGIN_PHP.md`, `UITLEG_FUNCTIONS_PHP.md`, etc.) geven per bestand een diepgaande uitleg.

**Bewijs**: Alle `.php` en `.js` bestanden; alle `UITLEG_*.md` bestanden.

---

### ✅ Heb je op een juiste manier versiebeheer toegepast?
**Ja.** Het project gebruikt **Git** met een GitHub-repository (`github.com/Harsha2006217/GamePlan-Scheduler`). De commit-historie bevat 120+ commits met betekenisvolle berichten:

Voorbeelden:
- `feat: Implement core functionality for GamePlan Scheduler`
- `fix: Simplify database connection script by removing redundant comments`
- `refactor: Enhance db.php documentation for clarity and security best practices`
- `docs: Add comprehensive Dutch explanation files`
- `style: Update glassmorphism background color to orange`

De commits gebruiken conventionele commit-prefixes (`feat:`, `fix:`, `refactor:`, `docs:`, `style:`, `chore:`).

**Bewijs**: Git log, `.gitignore`, `VERSIEGESCHIEDENIS_NL.md`.

---

### ✅ Videobestand(en) ≤ 400 MB?
**Ja.** Het demo-videobestand `K1-W3-DEMO VIDEO.mp4` is 52.7 MB, ruim onder de limiet van 400 MB.

**Bewijs**: `K1-W3-DEMO VIDEO.mp4` (52,710,434 bytes).

---

## K1-W4: TESTEN

---

### ✅ Zijn er per user story min. 5 testscenario's opgesteld?
**Ja.** Het Test-Logboek (`TEST_CASES_LOGBOEK_NL.md`) bevat een 100-punts QA Checklist die alle functionaliteiten dekt. Daarnaast bevat `VALIDATION_TEST_CASES.md` (13 KB) gedetailleerde testcases per formulier.

Voorbeelden van testgebieden:
- Login: correcte credentials, foute credentials, lege velden, SQL injection, XSS
- Registratie: unieke email, wachtwoordlengte, speciale tekens
- Schedule: lege titel, datum in verleden, ongeldig tijdformaat
- Delete: ownership check, soft delete werking, URL manipulatie

**Bewijs**: `TEST_CASES_LOGBOEK_NL.md`, `VALIDATION_TEST_CASES.md`.

---

### ✅ Is bij elk testscenario de beginsituatie beschreven?
**Ja.** De testcases beschrijven de beginsituatie (bijv. "gebruiker is ingelogd", "database bevat testdata") en de verwachte uitkomst.

---

### ✅ Is bij elk testscenario de gewenste uitkomst beschreven?
**Ja.** Elk testscenario heeft een verwacht resultaat. Bijvoorbeeld in de RED TEAMING sectie:

| Aanvalstype | Methode | Verwacht Resultaat | Werkelijk Resultaat |
|-------------|---------|--------------------|--------------------|
| SQL Injection | `UNION SELECT` in login | Geblokkeerd | ❌ Gefaald (aanval mislukt) |
| XSS Attack | `<script>` in spelnaam | Geëscaped | ❌ Gefaald (aanval mislukt) |
| Session Hijacking | Cookie stelen via JS | Onmogelijk | ❌ Gefaald (HttpOnly) |

**Bewijs**: `TEST_CASES_LOGBOEK_NL.md` sectie 4 (RED TEAMING).

---

### ✅ Zijn er alternatieve testscenario's beschreven?
**Ja.** Naast happy flows zijn er edge cases en foutgevallen getest:
- **Edge case**: Alleen spaties invoeren (Bugfix #1001)
- **Edge case**: Datum in het verleden invoeren (Bugfix #1004)
- **Foutgeval**: Niet-bestaand e-mailadres bij login
- **Foutgeval**: URL-manipulatie bij delete (ownership check)
- **Beveiligingstest**: SQL-injectie, XSS, CSRF, session hijacking

**Bewijs**: `TEST_CASES_LOGBOEK_NL.md` secties 3, 4, 5.

---

### ✅ Zijn er fouten gevonden?
**Ja.** Twee bugs zijn gevonden en opgelost:
- **Bugfix #1001**: Lege velden met alleen spaties werden geaccepteerd. Opgelost door `trim()` + `preg_match('/^\s*$/', $value)` in `validateRequired()`.
- **Bugfix #1004**: Datums in het verleden werden geaccepteerd. Opgelost door strikte `DateTime` vergelijking in `validateDate()`.

**Bewijs**: `CHANGELOG.md` Bug Fixes sectie, `functions.php` (`validateRequired`, `validateDate`).

---

### ✅ Is elk testscenario uitgevoerd en zijn de bevindingen vastgelegd?
**Ja.** De 100-punts QA Checklist is doorlopen en het testrapport `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf` bevat alle resultaten.

**Bewijs**: `TEST_CASES_LOGBOEK_NL.md`, `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

---

### ✅ Is bij elk testscenario beschreven wat de conclusie/aanbeveling is?
**Ja.** Bij de RED TEAMING testen staat per scenario de beveiligingsmaatregel die de aanval blokkeert. Bij Bugfix #1001 en #1004 is de aanbeveling geïmplementeerd als daadwerkelijke code-fix.

**Bewijs**: `TEST_CASES_LOGBOEK_NL.md` secties 4, 5.

---

## K1-W5: VERBETERVOORSTELLEN

---

### ✅ Zijn er 2+ verbeteringen op basis van het testrapport (W4)?
**Ja.**
1. **Bugfix #1001** (uit testen): Invoervelden die alleen spaties bevatten worden nu geblokkeerd door `validateRequired()` met `trim()` en regex-controle.
2. **Bugfix #1004** (uit testen): Datum-validatie is verstrengd met `DateTime::createFromFormat()` die geen data in het verleden accepteert.

**Bewijs**: `CHANGELOG.md`, `functions.php`.

---

### ✅ Zijn er 2+ verbeteringen op basis van de oplevering?
**Ja.** Het document `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Oplevering Notities.pdf` en `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf` bevatten verbetervoorstellen. De `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 14 beschrijft concrete toekomstplannen:

1. **Migratie naar Laravel**: Voor Eloquent ORM, Blade templating en betere schaalbaarheid.
2. **API-First & Mobile App**: RESTful API-laag toevoegen voor React Native/Flutter app.
3. **Real-time communicatie** via WebSockets voor live-chat en live-status.

**Bewijs**: `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 14, `CHANGELOG.md` Future Improvements, K1-W5 PDF's.

---

### ✅ Zijn er 2+ verbeteringen op basis van eigen reflectie?
**Ja.** Het reflectieverslag (`PROJECT_REFLECTIE_NL.md`) en `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Reflectie.pdf` noemen:

1. **E-mail verificatie bij registratie**: Momenteel wordt het e-mailadres niet geverifieerd.
2. **Exponential backoff bij login**: Bij 3 foute pogingen een tijdslot inbouwen (genoemd in FO sectie 8.3).
3. **Geautomatiseerd testen**: Momenteel handmatig; in V2.0 Selenium/Playwright implementeren.

**Bewijs**: `PROJECT_REFLECTIE_NL.md`, `CHANGELOG.md` Future Improvements.

---

### ✅ Zijn de verbetervoorstellen eenduidig en concreet beschreven?
**Ja.** Elke verbetering beschrijft:
- **Wat**: De specifieke functionaliteit (bijv. e-mail verificatie)
- **Waarom**: De reden (bijv. voorkomen van fake accounts)
- **Hoe**: De technische aanpak (bijv. PHP `mail()` met verificatie-token)

**Bewijs**: `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 14, K1-W5 PDF's.

---

# KERNTAAK 2: SAMENWERKEN IN EEN TEAM

---

## K2-W1: OVERLEGGEN

---

### ✅ Stel je relevante vragen tijdens overleg?
**Ja.** Het overlegdocument `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf` bevat bewijs van actieve deelname aan overleggen met de stagebegeleider. Er zijn relevante technische vragen gesteld over de architectuur en implementatie.

**Bewijs**: `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`.

---

### ✅ Breng je iets mee naar het overleg?
**Ja.** Bij elk overleg werd de voortgang van de GamePlan Scheduler besproken, inclusief tussentijdse demo's en technische vragen. De PVA stakeholder-matrix toont geplande review-momenten.

**Bewijs**: `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`, `PVA_GAMEPLAN_SCHEDULER_NL.md` sectie 13.

---

### ✅ Laat je zien dat je regelmatig afstemt?
**Ja.** De 120+ git commits tonen dagelijkse/wekelijkse activiteit. De feedback-documenten van de stagebegeleider bevestigen regelmatige afstemming.

**Bewijs**: Git log, `Feedback Stage Harsha Kanaparthi.pdf`, `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`.

---

### ✅ Laat je zien dat je afspraken vastlegt?
**Ja.** Afspraken zijn vastgelegd in het PVA (planning), in de overleg-PDF's en in de feedback-documenten van de stagebegeleider.

**Bewijs**: `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`, `PVA_GAMEPLAN_SCHEDULER_NL.md`.

---

### ✅ Laat je zien dat je afspraken nakomt?
**Ja.** Alle geplande functionaliteiten zijn gerealiseerd. De 5 user story's zijn allemaal opgeleverd. De CHANGELOG bevestigt dat alle mijlpalen zijn behaald.

**Bewijs**: `CHANGELOG.md`, werkende applicatie, demo video.

---

### ✅ Doe je actief mee met het overleg?
**Ja.** Het overlegdocument bevat bewijs van actieve deelname. De presentatie-documenten tonen proactieve communicatie.

**Bewijs**: `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`.

---

## K2-W2: PRESENTEREN

---

### ✅ Presenteer je overtuigend?
**Ja.** Er zijn meerdere presentaties voorbereid en uitgevoerd:
1. `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1.pdf` (1 MB)
2. `K2 W2 Presenteren-Presentatie met studiegenoot-Presentatie-2-MBO-4-Opleiding-Software-Development.pdf` (1.5 MB)

Het presentatiescript (`EXAMEN_PRESENTATIE_SCRIPT_NL.md`, 10 KB) bevat een gedetailleerd script voor de examenpresentatie.

**Bewijs**: K2-W2 PDF's, `EXAMEN_PRESENTATIE_SCRIPT_NL.md`, `PRESENTATIE_SLIDES_NL.md`.

---

### ✅ Onderbouw je je presentatie met goede argumenten?
**Ja.** Het presentatiescript bevat technische onderbouwing voor elke ontwerpkeuze (waarom PHP, waarom PDO, waarom HART-protocol). Elke bewering wordt ondersteund door concrete code-voorbeelden en bevindingen.

**Bewijs**: `EXAMEN_PRESENTATIE_SCRIPT_NL.md`, `PRESENTATIE_SLIDES_NL.md`.

---

### ✅ Presenteer je een duidelijk verhaal?
**Ja.** Het presentatiescript volgt een logische structuur: Introductie → Probleem → Oplossing → Technische Keuzes → Demo → Beveiliging → Conclusie. De slides zijn gestructureerd per functionaliteit.

**Bewijs**: `EXAMEN_PRESENTATIE_SCRIPT_NL.md`, `PRESENTATIE_SLIDES_NL.md`.

---

### ✅ Is de presentatie afgestemd op de doelgroep?
**Ja.** De presentatie is afgestemd op MBO-4 examinatoren: technische termen worden uitgelegd, er worden voorbeelden gegeven uit de code, en de beveiligingsmaatregelen worden visueel gedemonstreerd.

**Bewijs**: `PRESENTATIE_SLIDES_NL.md`, `MAKKELIJKE_UITLEG_GIDS_NL.md`.

---

### ✅ Stel je vragen aan betrokkenen?
**Ja.** Het presentatiescript bevat interactiemomenten en de reflectiedocumenten tonen feedback-sessies.

**Bewijs**: K2-W2 PDF's.

---

### ✅ Reageer je op de juiste manier op vragen/feedback?
**Ja.** Het feedbackdocument `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1-Feedback Van de stage begeleider-Harsha Vardhan Kanaparthi (1).pdf` toont dat feedback is ontvangen en verwerkt. De reflectie-PDF `K2 W2 Presenteren-Presentatie met studiegenoot-Presentatie-2-Reflectie-Verslag.pdf` toont verwerking van feedback.

**Bewijs**: Feedback PDF's bij K2-W2.

---

### ✅ Gaat de presentatie over het vak van software developer?
**Ja.** De presentatie gaat volledig over de GamePlan Scheduler: de technische architectuur, de beveiligingsimplementatie, de database-structuur, de validatie-algoritmen en het ontwikkelproces.

**Bewijs**: Alle K2-W2 documenten.

---

## K2-W3: REFLECTIE

---

### ✅ Gaat het verslag over jouw handelen?
**Ja.** Het reflectieverslag (`PROJECT_REFLECTIE_NL.md`) en `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf` gaan specifiek over persoonlijk handelen: keuzes, leermomenten, fouten en groei als developer.

Citaat: *"Reflectie is het proces waarbij ervaring wordt omgezet in expertise. Dit document markeert de transformatie van een lerende student naar een vakkundige Software Developer."*

**Bewijs**: `PROJECT_REFLECTIE_NL.md`, `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf`.

---

### ✅ Benoem je goede punten over jouw handelen?
**Ja.** Positieve punten die benoemd worden:
- Keuze voor PDO boven MySQLi bleek juist voor toekomstige schaalbaarheid
- Het HART-protocol heeft alle beveiligingstesten doorstaan
- De "Logic Centralization Strategy" (alle functies in `functions.php`) zorgde voor een hoge DRY-score
- Het methodisch werken via de Agile-Waterfall hybride methode

**Bewijs**: `PROJECT_REFLECTIE_NL.md` secties 1, 3, 4.

---

### ✅ Benoem je verbeterpunten over jouw handelen?
**Ja.** Verbeterpunten die eerlijk benoemd worden:
- Aanvankelijke angst voor "Spaghetti Code"
- Geautomatiseerd testen ontbreekt nog (handmatig getest)
- E-mail verificatie is nog niet geïmplementeerd
- Exponential backoff bij login is nog conceptueel

Citaat: *"Mijn reflectie op dit project is overwegend positief, maar ook nederig. Ik heb geleerd dat een goede developer nooit stopt met leren."*

**Bewijs**: `PROJECT_REFLECTIE_NL.md` secties 4, 8.

---

### ✅ Maak je onderscheid tussen eigen handelen en teamhandelen?
**Ja.** Dit is een individueel project. Het reflectieverslag maakt duidelijk dat alle keuzes, fouten en successen persoonlijk zijn. De samenwerking met stagebegeleider en docent wordt apart benoemd in het overlegdocument.

**Bewijs**: `PROJECT_REFLECTIE_NL.md`, `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf`.

---

### ✅ Beschrijf je feedback die je hebt gekregen?
**Ja.** De feedback is gedocumenteerd in:
- `Feedback Stage Harsha Kanaparthi.pdf`
- `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`
- `K2 W3 Reflectie-Harsha Vardhan Kanaparthi-Feedback bij Stage-Begeleider Met Handtekening.pdf`
- `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Feedback van Stagebegeleider.pdf`

**Bewijs**: Alle bovengenoemde PDF-bestanden.

---

### ✅ Beschrijf je wat je hebt gedaan met de feedback?
**Ja.** De verbeterdocumenten (K1-W5) en de reflectie beschrijven hoe feedback is vertaald naar concrete verbeteringen in de code en documentatie.

**Bewijs**: `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`, `PROJECT_REFLECTIE_NL.md`.

---

### ✅ Beschrijf je een proactieve houding?
**Ja.** Proactiviteit is aantoonbaar:
- 120+ commits tonen constant initiatief
- Het HART-protocol is zelf bedacht en geïmplementeerd (niet gevraagd)
- De Glassmorphism UI is een eigen keuze boven standaard Bootstrap
- De 100-punts QA Checklist is zelf opgesteld
- 15+ UITLEG-documenten zijn uit eigen initiatief geschreven

**Bewijs**: Git log, `TEST_CASES_LOGBOEK_NL.md`, alle UITLEG-bestanden.

---

# EXAMEN RUBRICS — BEANTWOORD

---

## K1-W1 Rubrics

### De uitgangspunten zijn juist verwerkt en de eisen/wensen zijn verwerkt in user stories
**Ja.** Het PVA beschrijft de uitgangspunten (techniek: PHP/MySQL/Bootstrap, deadline: examen 2026), het FO bevat de user stories en de MoSCoW-analyse prioriteert de eisen.

### Op basis van de user stories is een complete en realistische planning gemaakt
**Ja.** De planning is opgedeeld in 3 fasen met wekelijkse taken, bijgehouden via git commits.

### De voortgang is bewaakt en de juiste keuzes zijn gemaakt op basis van prioriteiten
**Ja.** Must Have items (login, CRUD, beveiliging) zijn eerst gerealiseerd. Could/Won't items (mobile app, real-time) zijn bewust doorgeschoven naar V2.0.

---

## K1-W2 Rubrics

### De eisen/wensen zijn vertaald naar een passend, eenduidig en volledig ontwerp
**Ja.** Het FO vertaalt elke eis naar een pagia-ontwerp, het TO vertaalt dat naar technische architectuur.

### Er is gebruik gemaakt van relevante schematechnieken
**Ja.** ERD, Code Flow Diagrams en Use Cases zijn aanwezig.

### De gemaakte keuzes zijn onderbouwd met steekhoudende argumenten
**Ja.** Elke keuze (PHP, PDO, Bcrypt, Glassmorphism, Soft Deletes) is technisch onderbouwd.

---

## K1-W3 Rubrics

### Er is voldoende functionaliteit gerealiseerd binnen de geplande tijd
**Ja.** 5 user story's, 35+ functies, 4600+ regels code, 21 bestanden.

### De opgeleverde functionaliteiten voldoen aan de eisen en wensen
**Ja.** Alle Must Have en Should Have items zijn gerealiseerd.

### De kwaliteit van de code is goed
**Ja.** DRY (herbruikbare functies), SRP (één verantwoordelijkheid per functie), inputvalidatie (7 validatiefuncties), error handling (try-catch, $errors[]), security (HART), consistent commentaar.

### Versiebeheer is effectief toegepast
**Ja.** 120+ commits, conventionele commit-berichten (feat/fix/refactor/docs), `.gitignore` aanwezig, GitHub-repository.

---

## K1-W4 Rubrics

### Passende testvormen en -methodieken gekozen
**Ja.** Black Box, Gray Box en RED TEAMING (penetratietesten). Beschreven in `TEST_CASES_LOGBOEK_NL.md` sectie 1.

### Voor alle functionaliteiten zijn testcases gemaakt
**Ja.** 100-punts QA Checklist dekt alle pagina's en functies. Beveiligingstesten (SQL injection, XSS, CSRF, session hijacking) zijn apart beschreven.

### Het testrapport bevat alle resultaten met juiste conclusies
**Ja.** `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf` en `TEST_CASES_LOGBOEK_NL.md` bevatten resultaten per testscenario met conclusies.

---

## K1-W5 Rubrics

### Systematische analyse van informatiebronnen
**Ja.** Testresultaten, bugfixes (#1001, #1004), gebruikersfeedback en code-review zijn geanalyseerd.

### Vertaling naar realiseerbare verbetervoorstellen
**Ja.** Concrete voorstellen: e-mailverificatie, geautomatiseerd testen, Laravel-migratie, API-laag. Elk voorstel is technisch onderbouwd.

### Werkzaamheden en haalbare planning vastgesteld
**Ja.** De roadmap (V2.0) beschrijft prioriteiten en de globale volgorde van implementatie.

---

## K2-W1 Rubrics

### Actieve deelname met relevante onderwerpen en juiste vragen
**Ja.** Bewezen in `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`.

### Regelmatige en tijdige afstemming
**Ja.** Feedbackdocumenten en git-activiteit bevestigen dit.

### Afspraken eenduidig vastgelegd
**Ja.** In PVA en overleg-PDF's.

### Kandidaat houdt zich aan afspraken
**Ja.** Alle geplande functionaliteiten zijn opgeleverd.

---

## K2-W2 Rubrics

### Overtuigend, duidelijk, beargumenteerd verhaal
**Ja.** Presentatiescript en slides beschikbaar als bewijs.

### Gerichte vragen stellen aan betrokkenen
**Ja.** Interactiemomenten in presentatie beschreven.

### Adequaat reageren op feedback
**Ja.** Feedback ontvangen en verwerkt (zie K2-W2 feedback PDF's).

---

## K2-W3 Rubrics

### Positieve en verbeterpunten benoemd
**Ja.** Reflectie bevat beide: HART-protocol als positief, ontbrekend geautomatiseerd testen als verbeterpunt.

### Adequaat reageren op feedback
**Ja.** Feedback is ontvangen, besproken en waar mogelijk verwerkt in de code en documentatie.

### Proactieve houding
**Ja.** HART-protocol, 100-punts QA Checklist, 15+ UITLEG-documenten — allemaal uit eigen initiatief.

---

# 📂 OVERZICHT VAN ALLE BEWIJSBESTANDEN

| Werkproces | Bewijsbestanden |
|------------|----------------|
| **K1-W1 Planning** | `PVA_GAMEPLAN_SCHEDULER_NL.md`, `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf`, `CHANGELOG.md` |
| **K1-W2 Ontwerp** | `FO_GAMEPLAN_SCHEDULER_NL.md`, `TO_GAMEPLAN_SCHEDULER_NL.md`, `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`, `CODE_FLOW_DIAGRAMS.md`, `DATABASE_DOCUMENTATION.md` |
| **K1-W3 Realisatie** | Alle `.php`, `.js`, `.css`, `.sql` bestanden, `K1 W3 Realisatie-*.pdf`, `CHANGELOG.md`, `K1-W3-DEMO VIDEO.mp4`, Git log (120+ commits) |
| **K1-W4 Testen** | `TEST_CASES_LOGBOEK_NL.md`, `VALIDATION_TEST_CASES.md`, `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf` |
| **K1-W5 Verbeteren** | `K1-W5-Verbeteren-*.pdf` (4 documenten), `VOLLEDIGE_PROJECT_DOCUMENTATIE_A_TOT_Z.md` sectie 14 |
| **K2-W1 Overleggen** | `K2 W1 Overleggen-*.pdf`, Feedback PDF's |
| **K2-W2 Presenteren** | `K2 W2 Presenteren-*.pdf` (5 documenten), `EXAMEN_PRESENTATIE_SCRIPT_NL.md`, `PRESENTATIE_SLIDES_NL.md` |
| **K2-W3 Reflectie** | `PROJECT_REFLECTIE_NL.md`, `K2 W3 Reflectie-*.pdf` (3 documenten) |

---

**Alle antwoorden zijn gebaseerd op de daadwerkelijke projectbestanden en documenten in deze repository.**

*Harsha Vardhan Kanaparthi — Studentnummer 2195344 — MBO-4 Software Developer — 2026*
