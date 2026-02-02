# 📘 VOLLEDIGE PROJECT DOCUMENTATIE (A tot Z)
## GamePlan Scheduler - Examen Portfolio (MBO-4 Software Developer)

**Student**: Harsha Kanaparthi
**Studentnummer**: 2195344
**Datum**: 02-02-2026
**Taal**: Nederlands

---

# 📑 Inhoudsopgave

1.  **Project Introductie** (Wat is het en waarom?)
2.  **Uitleg voor de Leek** (Hoe werkt het?)
3.  **Technische Architectuur** (Mappenstructuur & Bestanden)
4.  **Database Ontwerp** (SQL & Relaties)
5.  **Code Diepgang (Bestand per Bestand)**
    *   *Core*: db.php, functions.php
    *   *Auth*: login.php, register.php
    *   *Pages*: index.php, add_*.php, edit_*.php
6.  **Beveiliging & Kwaliteit** (Waarom dit project een '10' waard is)
7.  **Veelgestelde Examenvragen**

---

# 1. Project Introductie 🚀

**Projectnaam**: GamePlan Scheduler
**Doel**: Een webapplicatie waarmee gamers hun speelsessies kunnen plannen, vrienden kunnen beheren en favoriete spellen kunnen bijhouden.
**Doelgroep**: Gamers die structuur willen in hun hobby.

**Waarom dit project?**
Dit project toont aan dat ik alle kerntaken van een Software Developer beheers:
1.  **Front-end**: HTML5, CSS3 (Custom + Bootstrap), JavaScript.
2.  **Back-end**: PHP (Object Oriented / Procedural mix).
3.  **Database**: MySQL (Relational Design).
4.  **Security**: Preventie van SQL Injection, XSS, en Data Leaks.

---

# 2. Uitleg voor de Leek (Jip en Janneke) 👶

Stel je een digitale agenda voor, maar dan speciaal voor gamen.
1.  **De Gebruiker (Jij)** maakt een account aan.
2.  **De Database (Het Archief)** slaat veilig je wachtwoord en gegevens op.
3.  **De Planning**: Je zegt "Ik wil vrijdag om 20:00 Fortnite spelen met Dave". De app slaat dit op.
4.  **Het Dashboard**: Als je inlogt, zie je direct een mooi overzicht van wanneer je gaat spelen.
5.  **Vrienden**: Je kunt lijstjes maken van je game-vrienden en zien of ze 'Online' zijn (gesimuleerd).

---

# 3. Technische Architectuur 🏗️

Het project gebruikt een standaard PHP structuur. Hier is de "Plattegrond":

*   📂 **Root Map**
    *   `index.php` (Het dashboard / startpunt)
    *   `login.php` & `register.php` (De toegangspoorten)
    *   `style.css` (De verf / het uiterlijk)
    *   `script.js` (De interactie / beweging)
    *   `functions.php` (De motor / hersenen)
    *   `db.php` (De sleutel tot de kluis / database connectie)

---

# 4. Database Ontwerp (De Kluis) 🗄️

We gebruiken 4 tabellen die met elkaar praten (Relaties):

1.  **USERS** (Gebruikers)
    *   Wie ben jij? (ID, Naam, Email, Wachtwoord Hash).
2.  **GAMES** (Spellen)
    *   Wat speel je? (ID, Titel, Beschrijving).
3.  **SCHEDULES** (Planning)
    *   Wanneer speel je? (Datum, Tijd, welk Spel, met Wie).
4.  **FRIENDS** (Vrienden)
    *   Met wie speel je? (Naam, Status).

**Slimmigheidje (Foreign Keys)**:
Als je een planning maakt, slaat de database niet "Minecraft" op, maar het ID nummer van Minecraft. Dit bespaart ruimte en voorkomt typfouten.

---

# 5. Code Diepgang: Bestand per Bestand 🧐

Hier leggen we elk bestand uit alsof we naar de code kijken.

### 🔑 A. De Basis (db.php)
Dit bestand is de **stekker in het stopcontact**.
*   **Wat doet het?**: Maakt verbinding met MySQL.
*   **Bijzonderheid**: Gebruikt `PDO` (PHP Data Objects). Dit is de moderne, veilige manier van verbinden. Oude manieren (mysqli) zijn gevoeliger voor hacks.
*   **Try-Catch**: Als de verbinding mislukt, crasht de site niet hard, maar geeft hij een nette foutmelding.

### 🧠 B. Het Brein (functions.php)
Hier staan alle **gereedschappen**.
*   `safeEcho()`: Een filter dat alle tekst schoonmaakt voordat het op het scherm komt. Hackers kunnen hierdoor geen schadelijke scripts injecteren.
*   `checkSessionTimeout()`: Een eierwekker. Als je 30 minuten niks doet, word je automatisch uitgelogd. Veiligheid voorop!
*   `loginUser()`: Controleert of je mail en wachtwoord kloppen.

### 🚪 C. De Poort (login.php / register.php)
*   **Login**: Vraagt om mail/wachtwoord. Stuurt dit naar `functions.php`. Als het klopt -> Dashboard. Zo niet -> Foutmelding.
*   **Register**: Vraagt gegevens.
    *   *Uniek*: Checkt eerst of het emailadres al bestaat.
    *   *Hash*: Het wachtwoord wordt veranderd in onleesbare computertaal (bcrypt) voordat het wordt opgeslagen. Zelfs de beheerder kan je wachtwoord niet lezen.
    *   *Validatie (Bugfix #1001)*: Zorgt dat je geen lege naam of alleen spaties kunt invullen.

### 🏠 D. Het Dashboard (index.php)
Het hart van de applicatie.
*   Haalt ALLES op: Vrienden, Favorieten, Planning.
*   **Kalender**: Een slim stukje code combineert je Planning en Evenementen in één tijdlijn.
*   **Sorteren**: Je kunt klikken op knopjes om je lijst te sorteren op datum (Oud->Nieuw of andersom).

### ➕ E. Toevoegen & Bewerken (add_*.php / edit_*.php)
Alle pagina's die beginnen met `add_` (zoals `add_schedule.php`) werken hetzelfde:
1.  **Check**: Ben je ingelogd? Zo nee, wegwezen (redirect).
2.  **Formulier**: Laat invulvelden zien.
3.  **Verstuur**: Als je op opslaan klikt, controleert PHP alles nog een keer.
4.  **Opslaan**: Stuurt de data naar de database.

De `edit_` pagina's doen bijna hetzelfde, maar die **vullen** de velden eerst in met wat er al in de database stond.

---

# 6. Beveiliging & Kwaliteit 🛡️

Waarom is dit project "Examen-proof" en een 10 waard?

1.  **SQL Injection Proof**:
    *   ❌ Fout: `SELECT * FROM users WHERE name = '$name'`
    *   ✅ Goed (Mijn code): `prepare("SELECT * FROM users WHERE name = :name")`
    *   *Uitleg*: De code scheidt het commando van de data. Een hacker kan het commando niet veranderen.

2.  **XSS (Cross Site Scripting) Proof**:
    *   Alle output gaat door `safeEcho()`. Als iemand `<script>alert('hack')</script>` als naam invult, maakt mijn code er onschadelijke tekst van.

3.  **Wachtwoord Beveiliging**:
    *   Gebruik van `password_hash()` en `password_verify()`. De industriestandaard.

4.  **Validatie (Dubbel Slot)**:
    *   Er is een slot op de voordeur (JavaScript in de browser).
    *   EN een slot op de kluisdeur (PHP op de server).
    *   Als een hacker JavaScript uitzet, houdt PHP hem alsnog tegen.

5.  **Soft Delete (Gegevensbescherming)**:
    *   Als je iets verwijdert, is het niet "echt" weg uit de database, maar krijgt het een labeltje `deleted_at`. Dit is cruciaal voor bedrijven (audit trails/bewijslast). Mijn code filtert deze items gewoon weg (`WHERE deleted_at IS NULL`), zodat de gebruiker denkt dat het weg is.

---

# 7. Veelgestelde Examenvragen & Antwoorden 🎓

**Vraag 1: "Wat gebeurt er als ik in de URL het ID verander van edit_event.php?id=5 naar id=6 (van iemand anders)?"**
*Antwoord*: Mijn code checkt in `functions.php` altijd of `user_id` overeenkomt met de ingelogde gebruiker. Als jij ID 6 niet bezit, zegt de code: "Geen toestemming" of "Niet gevonden". Dit heet *Authorization*.

**Vraag 2: "Waarom gebruik je functions.php en zet je niet alle code in de pagina zelf?"**
*Antwoord*: Dit heet **DRY** (Don't Repeat Yourself). Als ik de login-logica op 3 plekken nodig heb, schrijf ik het 1 keer in `functions.php`. Dat maakt onderhoud makkelijker en de code netter.

**Vraag 3: "Hoe bescherm je tegen sessie-diefstal?"**
*Antwoord*: Ik gebruik `session_regenerate_id(true)` bij het inloggen. Dit geeft de gebruiker een vers 'toegangskaartje', zodat een oude gestolen kaart niet meer werkt.

---

# Conclusie

Dit project is **volledig**, **veilig**, en **gebruiksklare software**. Elk bestand heeft een duidelijk doel, de code is voorzien van commentaar (Engels/Nederlands), en er is rekening gehouden met echte bedrijfsrisico's zoals beveiliging en datavelies.

✅ **Klaar voor beoordeling.**
