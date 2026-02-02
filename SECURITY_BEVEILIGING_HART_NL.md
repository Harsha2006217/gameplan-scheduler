# 🔒 BEVEILIGINGS-HART (ELITE MASTER GIDS)
## GamePlan Scheduler - Defense-in-Depth & Data-Integriteit

---

> **Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Project**: GamePlan Scheduler
> 
> "Veiligheid is verweven in het DNA van dit project. In dit document leg ik uit hoe we de OWASP Top 10 beveiligingsrisico's hebben gemitigeerd door middel van moderne PHP-beveiligingstechnieken."

---

# 1. Beveiligings-Architectuur (De 5 Lagen)

De applicatie beschermt de gebruiker via vijf onafhankelijke beveiligingslagen. Zelfs als één laag faalt, houden de anderen de software veilig.

```
┌─────────────────────────────────────────────────────────────┐
│                    BEVEILIGINGSLAGEN                         │
├─────────────────────────────────────────────────────────────┤
│  Laag 1: INPUT VALIDATIE (Client-side & Server-side)        │
│  Laag 2: AUTHENTICATIE (Sessie-beheer & Timeouts)           │
│  Laag 3: AUTORISATIE (Eigendoms-checks / Ownership)         │
│  Laag 4: DATA BESCHERMING (Hash-encryptie & XSS filtering)  │
│  Laag 5: DATABASE VEILIGHEID (Prepared statements)          │
└─────────────────────────────────────────────────────────────┘
```

---

# 2. Wachtwoord Beveiliging: Het "Bcrypt" Shield

In de GamePlan Scheduler worden wachtwoorden **nooit** als tekst opgeslagen. Wij maken gebruik van de industriestandaard **BCRYPT**.

## 2.1 Hoe het werkt
Wanneer een gebruiker zich registreert, wordt het wachtwoord door de `password_hash()` functie gehaald. Dit creëert een 60-tekens lange, onleesbare string.

```php
function registerUser($username, $email, $password) {
    // We gebruiken PASSWORD_BCRYPT voor automatische salting.
    // De 'cost' is standaard 10, wat een goede balans biedt tussen snelheid en veiligheid.
    $hash = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hash]);
}
```

## 2.2 Waarom BCRYPT?
Wij hebben specifiek voor Bcrypt gekozen boven andere methoden vanwege de resistentie tegen brute-force aanvallen.
- **Salting**: Bcrypt voegt automatisch een unieke 'salt' toe aan elk wachtwoord. Hierdoor hebben twee gebruikers met hetzelfde wachtwoord ("123456") toch een compleet andere hash in de database.
- **Timing Attacks**: Bcrypt is resistent tegen timing-aanvallen dankzij de consistente verwerkingstijd.

---

# 3. SQL Injection Preventie: Prepared Statements (PDO)

SQL Injection is de meest voorkomende hack bij web-apps. In dit project maken we dit onmogelijk door het gebruik van **PDO (PHP Data Objects)**.

```php
// De VEILIGE manier (gebruikt in dit project):
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->execute([$userEmail]); // De data wordt apart verstuurd naar de MySQL server.
```

Analyse: De server ontvangt eerst de query (`SELECT...`). Hij weet nu precies wat hij moet doen. Daarna pas wordt de data gestuurd. Het is voor een hacker onmogelijk om de query nog aan te passen met deze data.

---

# 4. Cross-Site Scripting (XSS) Verdediging

XSS gebeurt wanneer een hacker probeert om JavaScript-code in jouw website te injecteren.

**De `safeEcho()` wrapper**:
```php
function safeEcho($text) {
    // htmlspecialchars converts special chars to HTML entities.
    // ENT_QUOTES zorgt dat ook single en double quotes veilig zijn.
    // UTF-8 garandeert dat we alle karakters correct verwerken.
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
```

---

# 5. Sessie Beveiliging & Privacy

## 5.1 De "Inactiviteits-Wekker" (30 min Timeout)
Privacy is ook beveiliging. Als een gebruiker zijn computer open laat staan, wil je niet dat anderen in zijn agenda kunnen kijken.
- Na 30 minuten inactiviteit wordt de sessie vernietigd via `session_destroy()`.

## 5.2 Session Hijacking Voorkomen
Bij elke succesvolle login voeren we `session_regenerate_id(true)` uit. Dit zorgt ervoor dat een hacker een gestolen (oude) sessie-ID niet kan gebruiken om zich voor te doen als de eigenaar.

---

# 6. Autorisatie: De "Eigendoms-Check"

Toegang hebben tot de app is één ding, maar je mag alleen je **eigen** data bewerken.

```php
function checkOwnership($pdo, $table, $id, $userId) {
    // We checken ALTIJD of de user_id van de record matcht met de sessie ID.
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ? AND user_id = ? AND deleted_at IS NULL");
    $stmt->execute([$id, $userId]);
    return $stmt->fetch() !== false; // Retourneert true als het record bestaat én van de gebruiker is.
}
```

---

# 7. Soft Delete: Veilig Verwijderen

Hard-deletes (het echt wissen van rijen) is gevaarlijk voor data-consistentie.
- Onze tabellen hebben een `deleted_at` kolom.
- Bij verwijderen vullen we deze in.
- Alle `SELECT` queries bevatten een `WHERE deleted_at IS NULL` clausule.

---

# Conclusie

De beveiliging van de GamePlan Scheduler is gebaseerd op de **OWASP Top 10** richtlijnen. We hebben niet op één paard gewed, maar een systeem gebouwd met meerdere vangnetten. Van sterke encryptie tot slimme sessie-beheer; de data van de gebruiker is bij ons in veilige handen.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - 2026*
