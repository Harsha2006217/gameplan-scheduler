# 🎓 MOCK EXAMENVRAGEN (NEDERLANDS)
## Bereid je voor op de commissie!

Harsha, hier zijn 10 vragen die de examinatoren waarschijnlijk gaan stellen, met de perfecte antwoorden erbij.

---

### 1. "Hoe heb je de veiligheid van je database gewaarborgd?"
**Antwoord**: "Ik gebruik overal **PDO Prepared Statements**. Dit scheidt de SQL-logica van de gebruikersinvoer, waardoor SQL-injectie onmogelijk is. Daarnaast gebruik ik **Bcrypt** voor het hashen van wachtwoorden, wat de huidige standaard is."

### 2. "Wat doet die `safeEcho()` functie precies?"
**Antwoord**: "Dat is mijn verdediging tegen **Cross-Site Scripting (XSS)**. Het haalt alle data door `htmlspecialchars()`. Als een hacker probeert een script in een tekstveld te typen, wordt dat door mijn functie omgezet in gewone tekst voordat het op het scherm verschijnt."

### 3. "Waarom heb je gekozen voor PHP en niet voor een framework?"
**Antwoord**: "Door 'Vanilla' PHP te gebruiken, laat ik zien dat ik de kern-concepten van webontwikkeling beheers, zoals sessie-beheer, database-relaties en formulier-verwerking. Dit geeft een solide basis voor mijn verdere carrière."

### 4. "Leg eens uit hoe je die bug met de datums hebt gefikst (Bug #1004)."
**Antwoord**: "Ik gebruikte eerst een simpele check, maar die was niet strikt genoeg. Nu gebruik ik de PHP `DateTime` klasse. Ik controleer niet alleen het formaat, maar ook of de datum logisch is (bijvoorbeeld vandaag of in de toekomst) en of de datum echt bestaat."

### 5. "Wat gebeurt er als twee gebruikers hetzelfde spel toevoegen?"
**Antwoord**: "Mijn functie `getOrCreateGameId()` controleert eerst of de titel al in de `Games` tabel staat. Als dat zo is, hergebruikt hij het bestaande ID. Zo houden we de database schoon en genormaliseerd."

### 6. "Hoe voorkom je dat een gebruiker de data van een ander verwijdert via de URL?"
**Antwoord**: "Daarvoor heb ik de functie `checkOwnership()`. Voordat een delete of edit wordt uitgevoerd, controleert PHP in de database of het records wel gekoppeld is aan de `user_id` van de ingelogde persoon."

### 7. "Leg het principe van 'Soft Delete' uit."
**Antwoord**: "Ik verwijder geen rijen fysiek uit de database. In plaats daarvan zet ik een tijdstempel in de kolom `deleted_at`. De applicatie filtert deze rijen eruit. Dit is veiliger omdat je per ongeluk verwijderde data kunt herstellen en je relaties in de database intact blijven."

### 8. "Hoe heb je je code gestructureerd?"
**Antwoord**: "Ik heb een duidelijke scheiding gemaakt tussen de **logica** (`functions.php` en `db.php`) en de **presentatie** (de overige PHP bestanden met HTML). Dit maakt de code makkelijker te onderhouden."

### 9. "Wat is Glassmorphism en waarom heb je daarvoor gekozen?"
**Antwoord**: "Glassmorphism is een modern design-trend met transparante lagen en onscherpe achtergronden. Ik heb hiervoor gekozen omdat het de applicatie een moderne, premium 'gaming' uitstraling geeft."

### 10. "Wat zou je in een volgende versie verbeteren?"
**Antwoord**: "Ik zou een wachtwoord-reset functie toevoegen via email en wellicht een real-time chatfunctie implementeren met WebSockets."

---
**Tip**: Lees deze vragen een paar keer door vlak voor je presentatie! Succes! 🏆
