# 🧠 TECHNISCHE PROJECT REFLECTIE (MASTER-EDITIE)
## GamePlan Scheduler - Analyse van Proces, Architectuur & Visie

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Dit document is een diepgaande zelfanalyse van de technische keuzes, de architecturale patronen en de professionele groei die ik tijdens dit project heb doorgemaakt. Het dient als bewijs van mijn vermogen om kritisch te reflecteren op eigen werk."

---

# 1. Architecturale Keuzes & Design Patterns

### 🏗️ De "Separation of Concerns" (SoC) Filosofie
In het begin van de opleiding schreef ik code waar HTML, CSS en PHP allemaal in één bestand stonden. In de GamePlan Scheduler heb ik bewust gekozen voor een **gecentraliseerde architectuur**:
- **Gecentraliseerde Logica**: Alle database-operaties en validaties bevinden zich in `functions.php`. Dit is vergelijkbaar met de "Model" en "Controller" laag in een MVC-patroon.
- **Data Integriteit**: Door één centraal punt (`db.php`) te hebben voor de databaseverbinding, voorkom ik redundante verbindingen en lekken.
- **Geleerd**: Code is niet alleen voor de computer; code is voor de *volgende* programmeur (onderhoudbaarheid).

### 🎨 Design System: Glassmorphism & UX
Het design is niet alleen "mooi". Het is gebaseerd op de **Gaming Esthetiek**:
- **Glassmorphism**: Gebruik van `backdrop-filter: blur(10px)` om diepte te creëren. Dit bootst moderne gaming consoles (zoals PS5/Xbox) na.
- **Responsiviteit**: Door Bootstrap 5 te combineren met custom Media Queries, is de app bruikbaar op elk schermformaat.
- **A11Y (Toegankelijkheid)**: Ondanks de transparante achtergronden heb ik de contrastratio's gecontroleerd via de browser dev-tools om te zorgen dat de tekst leesbaar blijft voor iedereen.

---

# 2. Beveiliging: Het "Defense in Depth" Principe

Mijn visie op beveiliging is simpel: vertrouw nooit op één enkele verdedigingslinie.

1.  **De Eerste Linie (Inputs)**: Gebruik van HTML5 attributen (`required`, `type="email"`) voor directe feedback.
2.  **De Tweede Linie (Frontend)**: JavaScript controles om onnodige server-aanvragen te voorkomen.
3.  **De Derde Linie (Backend)**: De meest kritieke laag. Hier gebruik ik `trim()` en `preg_match` (Bugfix #1001) tegen vervuiling.
4.  **De Vierde Linie (Database)**: Volledige implementatie van **PDO Prepared Statements**. Dit is de "gouden standaard" tegen SQL Injection.
5.  **De Vijfde Linie (Hashing)**: Zelfs als een hacker de database steelt, zien ze alleen Bcrypt-hashes. Wachtwoorden zijn onleesbaar.

---

# 3. Technische Uitdagingen & Problem Solving

### 🐛 De "Spatiewal" (Bug #1001)
Een schijnbaar simpel formulier bleek een risico. Gebruikers konden afspraken maken met de titel `" "` (een spatie). Dit vervuilt de UI.
- **Mijn Oplossing**: Het schrijven van een universele `validateRequired()` functie die niet alleen checkt op lengte, maar ook op whitespace via regex. Dit demonstreert mijn aandacht voor detail.

### 📅 De Datum-Integriteit Puzzel (Bug #1004)
Een datum als "2025-02-30" is technisch een string, maar logisch onmogelijk.
- **Mijn Oplossing**: Ik heb geleerd om niet zelf "het wiel opnieuw uit te vinden" met complexe wiskunde, maar de kracht van PHP's `DateTime` klasse te gebruiken. Door invoer te parsen en te vergelijken met de output, vang ik logische fouten direct af.

---

# 4. Toekomstvisie & Schaalbaarheid

Als ik dit project commercieel zou uitrollen, zou ik de volgende stappen zetten:

1.  **Migratie naar OOP**: De huidige functionele structuur is zeer stabiel, maar een transitie naar **Object Oriented Programming** (Klassen voor `User`, `Schedule`, `Game`) zou de app nog krachtiger maken.
2.  **API-First Approach**: Door de backend om te bouwen naar een RESTful API (met JSON output), zou ik een mobiele applicatie (React Native) kunnen lanceren die dezelfde database gebruikt.
3.  **Unit Testing**: Het implementeren van PHPUnit testen om elke functie automatisch te valideren bij elke wijziging.

---

# 5. Persoonlijke Groei als Software Developer

Dit project heeft mijn passie voor **schone code** aangewakkerd. Ik heb geleerd dat:
- **Consistentie** belangrijker is dan snelheid.
- **Documentatie** de helft van het werk is (zonder documentatie is code waardeloos voor een team).
- **Beveiliging** geen extra optie is, maar de kern van elk systeem.

**Conclusie**: De GamePlan Scheduler is voor mij meer dan een examenproject; het is het fundament van mijn carrière als Software Developer. De code is robuust, de architectuur is doordacht en de gebruiker staat centraal.

---
*Getekend voor akkoord,*

**Harsha Kanaparthi**
*Aankomend Software Developer*
