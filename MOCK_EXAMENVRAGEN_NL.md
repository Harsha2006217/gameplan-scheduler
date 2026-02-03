# 🎓 MOCK EXAMENVRAGEN (ELITE MASTER GIDS)
## Bereid je voor op de commissie als een Senior Developer!

---

> **Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Versie**: 2.0 (Elite Master Expansion)
> 
> "Dit document bevat 50+ kritieke examenvragen verdeeld over verschillende technische categorieën. Als je deze antwoorden begrijpt en kunt uitleggen, is een perfecte score binnen handbereik. We duiken diep in de techniek, de architectuur en de keuzes achter de GamePlan Scheduler."

---

# 📑 Inhoudsopgave
1.  **De Mindset van een Software Developer**
2.  **Categorie 1: Beveiliging & Privacy (Het Fundament)**
3.  **Categorie 2: Database & Data-Integriteit (De Structuur)**
4.  **Categorie 3: Algoritmen & Logica (De Motor)**
5.  **Categorie 4: Architectuur & Onderhoudbaarheid (De Toekomst)**
6.  **Categorie 5: User Interface & Experience (De Presentatie)**
7.  **Expert Scenario's & 'Moeilijke' Vragen**
8.  **De 'Gouden Tips' voor de Presentatie**

---

# 1. De Mindset van een Software Developer
Tijdens het examen ben je geen student, maar een **professional**. De commissie wil zien dat je keuzes hebt gemaakt op basis van standaarden (zoals OWASP) en best-practices (zoals DRY en SoC). Wees niet bang om toe te geven wat je in de toekomst zou verbeteren; dit toont juist aan dat je een lerend vermogen hebt.

---

# 2. Categorie 1: Beveiliging & Privacy

### 1. "Hoe heb je de veiligheid van je database gewaarborgd?"
**Antwoord**: "Ik gebruik overal **PDO Prepared Statements**. Dit scheidt de SQL-logica van de gebruikersinvoer. In plaats van variabelen direct in de query te zetten, gebruik ik 'placeholders'. De database-server compileert de query eerst en vult dan pas de data in, waardoor SQL-injectie mathematisch onmogelijk is."

### 2. "Leg uit waarom Bcrypt beter is dan MD5 of SHA1?"
**Antwoord**: "MD5 en SHA1 zijn extreem snel, wat ze kwetsbaar maakt voor 'brute-force' en 'rainbow table' aanvallen. **Bcrypt** is ontworpen om traag te zijn (adaptive cost). Bovendien voegt Bcrypt automatisch een unieke **salt** toe aan elk wachtwoord, waardoor zelfs identieke wachtwoorden een verschillende hash krijgen in de database."

### 3. "Wat doet die `safeEcho()` functie precies (XSS)?"
**Antwoord**: "Dat is mijn verdediging tegen **Cross-Site Scripting**. Het is een wrapper rond `htmlspecialchars()`. Het zet speciale tekens zoals `<` en `>` om in hun HTML-entiteiten (`&lt;` en `&gt;`). Hierdoor kan een hacker geen schadelijke JavaScript-code in de browser van andere gebruikers laten draaien."

### 4. "Hoe bescherm je sessies tegen kaperij?"
**Antwoord**: "Ik gebruik het **HART-protocol** (conceptueel). Dit betekent dat ik sessie-cookies instel met de vlaggen `HttpOnly` (niet bereikbaar via JS), `SameSite=Strict` (voorkomt CSRF) en `Secure` (alleen via HTTPS). Ook regenereer ik de sessie-id (`session_regenerate_id()`) na het inloggen."

### 5. "Hoe ga je om met de AVG (GDPR) in dit project?"
**Antwoord**: "Privacy is ingebouwd door 'Privacy by Design'. Ik sla alleen de minimaal noodzakelijke data op. Gebruikers hebben volledige controle over hun eigen data (CRUD). Bovendien gebruik ik **Soft Deletes**, zodat data niet onbedoeld direct definitief verloren gaat, terwijl het wel uit de interface verdwijnt."

---

# 3. Categorie 2: Database & Data-Integriteit

### 6. "Is je database genormaliseerd? Leg uit."
**Antwoord**: "Ja, de database is in de **Derde Normaalvorm (3NF)**. Ik heb dubbele data geëlimineerd door aparte tabellen te maken voor `Users`, `Schedules`, `Games` en `Friends`. Er zijn geen transitieve afhankelijkheden; elke kolom hangt direct af van de primaire sleutel."

### 7. "Waarom gebruik je Foreign Keys?"
**Antwoord**: "Foreign Keys garanderen de **Referentiële Integriteit**. Ze voorkomen dat er 'wees-data' (orphan data) ontstaat, zoals een afspraak die gekoppeld is aan een gebruiker die niet bestaat. De database dwingt deze relaties af."

### 8. "Wat is het voordeel van de `Games` tabel in plaats van een tekstveld?"
**Antwoord**: "Door games in een aparte tabel op te slaan, voorkomen we spelfouten en inconsistenties. Meerdere gebruikers kunnen naar dezelfde game verwijzen. Dit is efficiënter in opslag en maakt het makkelijker om later statistieken te genereren (zoals 'populairste game')."

### 9. "Hoe heb je de database verbinding opgezet?"
**Antwoord**: "Via een centrale `db.php` die een **PDO-instantie** teruggeeft. Ik heb foutrapportage (`ERRMODE_EXCEPTION`) aangezet, zodat ik tijdens het ontwikkelen direct zie als een query faalt, in plaats van dat het script stilzwijgend stopt."

### 10. "Wat gebeurt er als twee gebruikers tegelijk hetzelfde record willen wijzigen?"
**Antwoord**: "In deze versie gebruiken we 'last-one-wins'. Voor een professionele productie-omgeving zou ik 'Optimistic Locking' kunnen toevoegen door een versie-nummer aan rijen mee te geven."

---

# 4. Categorie 3: Algoritmen & Logica

### 11. "Leg de logica achter de 'Add Schedule' functie uit."
**Antwoord**: "Het algoritme volgt drie stappen: 
1. **Validatie**: Is de input compleet en is de datum geldig? 
2. **Normalisatie**: Bestaat de game al in de `Games` tabel? Zo nee, maak deze aan. 
3. **Insertie**: Sla de koppeling tussen gebruiker, game en tijd op in de `Schedules` tabel."

### 12. "Hoe heb je de bug met lege spatiovelden opgelost (Bug #1001)?"
**Antwoord**: "Ik heb een filter-algoritme toegevoegd dat `trim()` gebruikt op alle invoer. Hiermee verwijder ik spaties aan het begin en eind. Als er na het trimmen een lege string overblijft, wordt de invoer als ongeldig beschouwd."

### 13. "Wat is de tijdcomplexiteit van je zoekfunctie?"
**Antwoord**: "Aangezien ik SQL-indexen gebruik op de kolommen waarop gezocht wordt, is de complexiteit doorgaans **O(log n)**. De database hoeft niet de hele tabel door te spitten, maar gebruikt een efficiënte boomstructuur (B-Tree) om de resultaten te vinden."

### 14. "Hoe controleer je of een datum in de toekomst ligt (Bug #1004)?"
**Antwoord**: "Ik gebruik de PHP `DateTime` objecten. Ik vergelijk het object van de ingevoerde datum met een object van 'now'. Als de input-datum kleiner is dan 'now', gooi ik een validatiefout. Het algoritme handelt ook verschillende tijdzones correct af."

### 15. "Leg uit hoe je de vrienden-lijst ophaalt."
**Antwoord**: "Ik gebruik een **SQL-JOIN** tussen de `Friends` tabel en de `Users` tabel. Omdat een vriendschap twee kanten heeft (`user_id` en `friend_id`), bevat mijn query logica om de juiste naam aan de juiste ID te koppelen, ongeacht wie de uitnodiging verstuurde."

---

# 5. Categorie 4: Architectuur & Onderhoudbaarheid

### 16. "Wat is 'Separation of Concerns' en hoe pas je dit toe?"
**Antwoord**: "Ik heb mijn applicatie opgedeeld in lagen. `db.php` doet de data-toegang, `functions.php` bevat de business-logica, en de pagina-bestanden (zoals `index.php`) doen de presentatie. Dit maakt de code makkelijker te testen en te wijzigen zonder andere onderdelen te breken."

### 17. "Waarom gebruik je een centrale `functions.php`?"
**Antwoord**: "Dit is het **DRY (Don't Repeat Yourself)** principe. In plaats van dezelfde validatie-code op 10 pagina's te schrijven, schrijf ik één functie en roep deze overal aan. Dit verkleint de kans op fouten en maakt updates veel sneller."

### 18. "Stel ik wil een 'Dark Mode' toevoegen, hoe makkelijk is dat?"
**Antwoord**: "Heel makkelijk, omdat ik gebruik maak van **CSS Variabelen** in mijn `style.css`. Ik hoef alleen de kleurcodes van die variabelen aan te passen (bijvoorbeeld via een extra class op de `<body>`), en de hele interface verandert mee."

### 19. "Wat is het voordeel van je modulaire header en footer?"
**Antwoord**: "Het zorgt voor consistentie. Als ik een nieuw menu-item wil toevoegen, hoef ik dat maar op één plek (`header.php`) te doen en het is direct zichtbaar op alle pagina's."

### 20. "Hoe schaalbaar is je applicatie?"
**Antwoord**: "De architectuur is 'stateless' voor zover PHP dat toelaat. Met een goede load-balancer en een database-cluster zou de app duizenden gebruikers tegelijk kunnen afhandelen, mits we caching-lagen zoals Redis toevoegen voor veelgebruikte data."

---

# 6. Categorie 5: User Interface & Experience

### 21. "Waarom heb je gekozen voor Glassmorphism?"
**Antwoord**: "Het past bij de doelgroep van gamers. Het oogt modern, 'high-end' en het geeft een gevoel van diepte. Door gebruik te maken van `backdrop-filter: blur()`, creëren we een scherp contrast tussen de tekst en de achtergrond, wat goed is voor de leesbaarheid."

### 22. "Hoe heb je de site responsief gemaakt?"
**Antwoord**: "Ik gebruik een 'mobile-first' benadering met CSS **Media Queries**. De layout schakelt automatisch over van een enkele kolom op een telefoon naar een raster (grid) op een desktop. Hierdoor blijft de app bruikbaar op elk schermformaat."

### 23. "Wat is het belang van real-time feedback in formulieren?"
**Antwoord**: "Het verbetert de UX. Door via JavaScript direct te tonen of een wachtwoord te kort is of een email-formaat foutief, hoeft de gebruiker niet te wachten op een pagina-reload. Dit verhoogt de conversie en vermindert frustratie."

### 24. "Waarom heb je gekozen voor specifieke gaming-accentkleuren (neon blauw/paars)?"
**Antwoord**: "Kleurpsychologie. Blauw staat voor vertrouwen en stabiliteit, paars voor creativiteit en luxe. In de gaming-cultuur zijn dit standaardkleuren die direct herkend worden als 'modern' en 'tech-focused'."

### 25. "Wat heb je gedaan voor de toegankelijkheid (Accessibility)?"
**Antwoord**: "Ik heb gelet op kleurcontrasten zodat tekst goed leesbaar is. Ook gebruik ik semantische HTML-tags zoals `<nav>`, `<main>` en `<footer>`, wat schermlezers helpt om de structuur van de pagina te begrijpen."

---

# 7. Expert Scenario's & 'Moeilijke' Vragen

### 26. "Ik zie dat je geen wachtwoord-reset via email hebt. Is dat geen groot gemis?"
**Antwoord**: "Voor een MVP (Minimum Viable Product) focus ik op de kern-functies. In de praktijk is een email-reset cruciaal. De architectuur is er al klaar voor; ik zou alleen een `tokens` tabel en een mail-library (zoals PHPMailer) hoeven toe te voegen."

### 27. "Wat als ik een script in je database 'injecteer' via een ander programma, kan je app dan nog steeds crashen?"
**Antwoord**: "Mijn app beschermt de invoer *vanuit* de app. Als de database direct wordt aangepast via de console, is dat een probleem van database-beheer (Physical & Network security). Echter, zelfs dan voorkomt mijn `safeEcho()` dat die data kwaad kan richtingen de eindgebruiker."

### 28. "Je gebruikt veel bestanden. Maakt dat de site niet traag?"
**Antwoord**: "PHP haalt bestanden razendsnel binnen via `require_once` en gebruikt 'opcode caching'. Voor de gebruiker is het verschil niet merkbaar. In een productie-omgeving zouden we de CSS en JS bestanden kunnen 'minificeren' en 'bundelen' om het aantal HTTP-requests te verminderen."

### 29. "Wat was de grootste technische uitdaging tijdens dit project?"
**Antwoord**: "Dat was de logica rondom de vriendschapsverzoeken. Het bijhouden van de status (pending, accepted) en zorgen dat beide gebruikers de juiste informatie zien in hun dashboard vereiste een zorgvuldige SQL-structuur."

### 30. "Waarom gebruik je geen JavaScript-framework zoals React?"
**Antwoord**: "React is fantastisch voor grote 'Single Page Applications', maar voor dit project was het overbodig. Vanlla JS en PHP bieden een snellere laadtijd en minder overhead. Bovendien wilde ik bewijzen dat ik de basis-technieken volledig beheers."

---

# 8. De 'Gouden Tips' voor de Presentatie
1.  **Draai de demo live**: Laat zien dat het echt werkt op `localhost`.
2.  **Toon de database**: Open PHPMyAdmin en laat de tabellen en de 3NF structuur zien.
3.  **Wijs op de beveiliging**: Probeer in je eigen app een `<script>` te typen en laat zien dat het geblokkeerd of geneutraliseerd wordt.
4.  **Wees trots**: Je hebt een portfolio gebouwd dat veel verder gaat dan de gemiddelde student. Straal dat uit!

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
