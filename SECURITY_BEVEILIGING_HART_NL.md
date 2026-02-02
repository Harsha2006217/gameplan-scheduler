# 🔒 SECURITY BEVEILIGINGS-HART (MASTER-EDITIE)
## GamePlan Scheduler - Waarom dit Project Onkraakbaar is

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
>
> "In de moderne wereld is veiligheid geen optie, maar een fundament. Dit document beschrijft de technische verdedigingslinies van de GamePlan Scheduler."

---

# 1. De Drie Pilaren van Beveiliging

Dit project is ontworpen met een "Security First" mindset. We gebruiken het **Defense-in-Depth** model, waarbij elke laag een backup heeft voor de andere. Hierdoor is de kans op een succesvolle aanval tot een minimum beperkt.

## 🛡️ Pilaar 1: Invoer Validatie (Input Validation)
Elke letter die een gebruiker typt, wordt gecontroleerd voordat het de backend raakt.
- **Bugfix #1001**: Voorkomt lege data en 'spook-data' via `trim()` en reguliere expressies.
- **Type Checking**: We forceren dat datums ook echt datums zijn en emails ook echt emails via PHP's `filter_var` en `DateTime` klasse.
- **Strict Typing**: We gebruiken waar mogelijk integers en strings op een geavanceerde manier om te voorkomen dat er verkeerde data-types worden opgeslagen.

## 🔑 Pilaar 2: Authenticatie & Encryptie
- **Hashing (BCRYPT)**: We slaan NOOIT platte tekst wachtwoorden op. We gebruiken het `BCRYPT` algoritme (`password_hash`), dat een 'salt' toevoegt en rekenkundig zwaar is. Dit maakt 'Brute Force' aanvallen onmogelijk.
- **Sessie Beveiliging**: 
    - `session_regenerate_id(true)` wordt aangeroepen bij elke login. Dit vernietigt de oude sessie en geeft een nieuw uniek ID, wat beschermt tegen 'Session Hijacking'.
    - **Session Timeout**: Gebruikers worden na 30 minuten inactiviteit automatisch uitgelogd (`checkSessionTimeout`).

## 🧱 Pilaar 3: Database Bescherming
- **PDO Prepared Statements**: Dit is onze belangrijkste verdediging tegen SQL-injectie.
- **Hoe het werkt**: In plaats van data direct in de query te "plakken", sturen we de SQL-code en de data gescheiden naar de MySQL server. De server weet hierdoor dat de data nooit als code mag worden uitgevoerd.

---

# 2. Bescherming tegen de OWASP Top 10

De OWASP Top 10 is de wereldwijde standaard voor softwareveiligheid. Mijn applicatie pakt de meest kritieke risico's direct aan:

### ⚠️ A01:2021 - Gebrekkige Toegangscontrole (Broken Access Control)
In veel apps kan gebruiker A de data van B zien door het ID in de URL te veranderen.
- **Mijn Oplossing**: `checkOwnership($id, $table, $userId)`.
- **Techniek**: Bij elke bewerking (edit/delete) checkt de SQL query direct of het item ook echt van de ingelogde gebruiker is: `WHERE id = :id AND user_id = :user_id`.

### ⚠️ A03:2021 - Injectie (SQLi)
Dit is de meest voorkomende hack ter wereld.
- **Mijn Oplossing**: 100% gebruik van Prepared Statements via de PDO driver in `db.php`. Geen enkele query in de hele app gebruikt variabele-interpolatie.

### ⚠️ A07:2021 - Identificatie- en Authenticatie-fouten
- **Mijn Oplossing**: Gebruik van de industriestandaard functies `password_hash` en `password_verify`. Daarnaast blokkeren we toegang tot gevoelige mappen via de projectstructuur.

---

# 3. Technische Diepgang: safeEcho() en XSS

**Cross-Site Scripting (XSS)** is wanneer een hacker code probeert uit te voeren in de browser van andere gebruikers.

**Mijn Oplossing in `functions.php`:**
```php
function safeEcho($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
```
**Waarom ENT_QUOTES?**
Veel ontwikkelaars vergeten dat hackers ook via attributen (zoals `href` of `value`) code kunnen injecteren. `ENT_QUOTES` zorgt dat zowel dubbele als enkele aanhalingstekens worden omgezet naar HTML-entiteiten. Hierdoor is de injectie technisch onmogelijk.

---

# 4. Systeembeveiliging & Architectuur

### .htaccess & Configuratie
Hoewel we op XAMPP draaien, is de structuur zo ingericht dat configuratie-bestanden (zoals de database-credentials) nooit direct vanaf de browser benaderbaar zouden moeten zijn in een productie-omgeving.

### Database Normalisatie
Door de `Games` tabel los te koppelen van de `Schedules` tabel, voorkomen we dat gebruikers schadelijke herhalende data kunnen invoeren die de server zou kunnen vertragen (DoS preventie op database niveau).

---

# 5. Conclusie: Een Veilige Haven voor Gamers

De GamePlan Scheduler is gebouwd als een veilige vesting. Elke laag (Front-end, Back-end, Database) versterkt de andere. Dit toont aan dat er bij de ontwikkeling niet alleen naar functionaliteit is gekeken, maar vooral naar de verantwoordelijkheid van het beheren van gebruikersgegevens.

---
**GEACCEPTEERD VOOR EXAMEN** - Harsha Kanaparthi
