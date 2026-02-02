# 🎯 EXAMEN SPIEKBRIEFJE (DE "10" EDITIE)
## GamePlan Scheduler - Technische Killer-Antwoorden

---

# 1. Beveiliging (De Commissie-Favoriet)

### "Waarom is jouw app veiliger dan die van anderen?"
> **Killer Antwoord**: "Omdat ik werk met de **OWASP principes**. Ik vertrouw nooit wat de gebruiker invoert (*Never trust user input*). Daarom pas ik zowel op de client-side (JS) als op de server-side (PHP) strikte validatie toe. Tegen SQL-injectie gebruik ik **Prepared Statements** via PDO, wat zorgt dat data en commando's strikt gescheiden blijven."

### "Wat doe je tegen XSS aanvallen?"
> **Killer Antwoord**: "Ik gebruik een 'Output Escaping' strategie. Via mijn `safeEcho()` functie worden alle speciale karakters omgezet in HTML-entiteiten. Zo worden kwaadaardige scripts van hackers onschadelijk gemaakt voordat de browser ze kan uitvoeren."

# 2. Architectuur & Keuzes

### "Waarom gebruik je Soft Deletes in plaats van DELETE?"
> **Killer Antwoord**: "Data is kostbaar. Bij een **Soft Delete** zetten we een tijdstempel in de kolom `deleted_at`. Dit zorgt voor betere data-integriteit. Als een gebruiker per ongeluk iets verwijdert, kunnen we het herstellen, en we behouden onze waardevolle historische data voor statistieken."

### "Welk design patroon heb je gevolgd?"
> **Killer Antwoord**: "Ik heb geprobeerd een duidelijke **Separation of Concerns** (SoC) aan te houden. De database-configuratie (`db.php`), de business-logica (`functions.php`) en de gebruikersinterface (style.css/index.php) zijn strikt gescheiden. Dit maakt de applicatie schaalbaar en makkelijk te onderhouden."

# 3. Technische Termen (Gooi ze erin!)

| Term | Wanneer gebruiken? | Wat betekent het? |
|---|---|---|
| **Data Normalisatie** | Vragen over database. | Het slim verdelen van data over meerdere tabellen om dubbele data te voorkomen. |
| **Integriteit** | Vragen over validatie. | Zorgen dat de data in de database correct en logisch is (geen datums in het verleden). |
| **Responsive** | Vragen over CSS. | Dat het ontwerp 'meebeweegt' met verschillende schermgroottes. |
| **Sanitization** | Vragen over security. | Het 'schoonmaken' van gebruikersinvoer voordat het verwerkt wordt. |

---
**GEHEIM VAN DE SMID**: Als je vastloopt, zeg dan: *"Dat is een interessante vraag, in mijn documentatie (bijv. TECHNISCH_ONTWERP_NL) heb ik dit detail verder uitgewerkt."* Dat toont aan dat je voorbereid bent! 🥇
