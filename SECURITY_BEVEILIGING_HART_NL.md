# 🔒 SECURITY BEVEILIGINGS-HART (LEGENDARY-EDITIE)
## GamePlan Scheduler - Dé Architectuur van een Onkraakbaar Systeem

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
>
> "In de moderne wereld is veiligheid geen optie, maar een fundament. Dit document beschrijft de technische verdedigingslinies van de GamePlan Scheduler op senior-niveau, getoetst aan de OWASP-standaarden."

---

# 1. De Filosofie: Defense in Depth (DiD)

In dit project hanteren we de **Defense in Depth** strategie. Dit betekent dat we niet vertrouwen op één "groot slot", maar op een serie van verbonden verdedigingsmechanismen. 

### Wat betekent dit in de praktijk?
Als een hacker erin slaagt om de browser-validatie (JavaScript) te omzeilen, wordt hij opgevangen door de server-validatie (PHP). Als hij probeert SQL-commando's in een formulier te typen, wordt dit geblokkeerd door de database-driver (PDO). Als hij de database fysiek weet te stelen, zijn de gegevens onbruikbaar door krachtige encryptie (Bcrypt). Deze lagen maken het project **veilig tegen de meest voorkomende cyberaanvallen**.

---

# 2. Gedetailleerde Verdedigingslagen

### 🛡️ Laag 1: De Voorpost (Sanitatie & Validatie)
Elke byte die het systeem binnenkomt via `$_POST` of `$_GET`, is potentieel gevaarlijk.
- **Trim & Regex (Bugfix #1001)**: Door witruimte te strippen en reguliere expressies te gebruiken, blokkeren we 'Null Byte' aanvallen en vervuiling met lege karakters.
- **Strict Typing**: In de backend forceren we dat ID's echt integers zijn via `(int)$id`. Dit voorkomt "Type Juggling" aanvallen waarbij een hacker tekst stuurt waar de code een getal verwacht.

### 🔑 Laag 2: De Kluis (Cryptografische Identiteit)
Wachtwoorden zijn het meest gevoelige bezit van onze gebruikers.
- **Het Bcrypt Algoritme**: We gebruiken `password_hash()` in PHP. Dit algoritme is "Adaptive", wat betekent dat het met de tijd zwaarder kan worden gemaakt om brute-force aanvallen met snellere computers voor te blijven.
- **Salt & Pepper**: Het systeem voegt automatisch een unieke "salt" toe aan elk wachtwoord, waardoor zelfs identieke wachtwoorden in de database een compleet andere hash-string opleveren.

### 🌐 Laag 3: De Poortwachter (Sessie-Behandeling)
- **Session Hijacking Preventie**: Bij het inloggen roepen we `session_regenerate_id(true)` aan. Dit genereert een compleet nieuw sessie-ID voor de gebruiker, waardoor oude sessie-cookies die een hacker mogelijk heeft gestolen, direct waardeloos worden.
- **Automatic Timeout**: Onze `checkSessionTimeout()` functie werkt als een eierwekker. Na 30 minuten inactiviteit wordt de sessie "ge-killed", wat cruciaal is voor veiligheid op publieke computers (zoals op school of in een bibliotheek).

---

# 3. OWASP Top 10 Bestrijding

Mijn applicatie pakt de **OWASP Top 10** (de bijbel van web-security) direct aan:

### ⚠️ A03:2021 - Injectie (SQLi)
Dit is de klassieke hack waarbij iemand SQL-commando's typt in een tekstveld om de database te wissen of te stelen.
- **Oplossing**: We gebruiken **100% Prepared Statements via PDO**. In de code ziet dit er zo uit:
```php
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->execute([$email]);
```
De data (`$email`) wordt nooit door MySQL als code behandeld, waardoor infectie fysiek onmogelijk is.

### ⚠️ A01:2021 - Gebrekkige Toegangscontrole (Broken Access Control)
Kan gebruiker A de data van gebruiker B bewerken?
- **Oplossing**: De `checkOwnership()` functie. Elke query die data bewerkt of inziet, bevat een verplichte check op `user_id`. Het systeem vraagt bij elke actie: "Is dit item echt van jou?".
```php
$stmt = $pdo->prepare("SELECT count(*) FROM table WHERE id = :id AND user_id = :user_id");
```

### ⚠️ A03:2021 - Cross-Site Scripting (XSS)
- **Oplossing**: De `safeEcho()` wrapper. Alle data die van de database naar het scherm gaat, wordt door `htmlspecialchars` gehaald. Eventuele scripts worden omgezet in onschadelijke tekst die de browser niet uitvoert.
```php
function safeEcho($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
```

---

# 4. Data-Integriteit & Soft Deletes

Beveiliging gaat ook over de **beschikbaarheid** en **integriteit** van data. 
- **Waarom Soft Deletes?**: In professionele systemen verwijder je nooit data direct. In de GamePlan Scheduler gebruiken we de kolom `deleted_at`.
- **Veiligheidsvoordeel**: Als een gebruiker (of een malware script) per ongeluk "alles verwijdert", kan de systeembeheerder de data binnen milliseconden herstellen door de `deleted_at` kolom weer leeg te maken (`NULL`).

---

# 5. Conclusie: Een Onverwoestbaar Fundament

De beveiliging van de GamePlan Scheduler is geen optie, het is de **kern**. Door de combinatie van cryptografische hashing, sessiebeheer en SQL-templates staat dit project op hetzelfde niveau als moderne bedrijfssoftware. Dit toont aan dat ik als Software Developer niet alleen naar "features" kijk, maar vooral naar de verantwoordelijkheid van het beheren van andermans gegevens.

---
**REVISIE STATUS**: LEGENDARY QUALITY ACHIEVED 🏆
*Harsha Kanaparthi - 2026*
