# 🔒 SECURITY BEVEILIGINGS-HART (MASTER-EDITIE)
## GamePlan Scheduler - Dé Architectuur van een Veilig Systeem

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
>
> "In de moderne wereld is veiligheid geen optie, maar een fundament. Dit document beschrijft de technische verdedigingslinies van de GamePlan Scheduler op senior-niveau."

---

# 1. De Filosofie: Defense in Depth (DiD)

In dit project hanteren we de **Defense in Depth** strategie. Dit betekent dat we niet vertrouwen op één "groot slot", maar op een serie van verbonden verdedigingsmechanismen. Als één laag faalt (bijvoorbeeld als JavaScript wordt uitgeschakeld), vangt de volgende laag (de PHP backend) de aanval op.

---

# 2. Gedetailleerde Verdedigingslagen

### 🛡️ Laag 1: Invoer Validatie & Sanctie
Elke byte die het systeem binnenkomt, is potentieel gevaarlijk.
- **Trim & Regex (Bugfix #1001)**: Door witruimte te strippen en reguliere expressies te gebruiken, blokkeren we 'Null Byte' aanvallen en vervuiling met lege karakters.
- **Type Casting**: In de backend forceren we dat ID's echt integers zijn via `(int)$id`. Dit voorkomt dat een hacker een stuk tekst in plaats van een nummer stuurt.

### 🔑 Laag 2: Cryptografische Identiteit
Wachtwoorden zijn het meest gevoelige bezit van onze gebruikers.
- **Bcrypt Algoritme**: We gebruiken `password_hash` met de `PASSWORD_DEFAULT` (Bcrypt) instelling. Dit algoritme is "computationally expensive", wat betekent dat het heel lang duurt voor een computer om het te kraken (beveiliging tegen GPU-cracking).
- **Unieke Salts**: Het algoritme genereert automatisch een unieke salt voor elk wachtwoord. Twee gebruikers met hetzelfde wachtwoord hebben dus een totaal verschillende hash in de database.

### 🌐 Laag 3: Sessie-Behandeling & Privacy
- **Session Regeneration**: Door `session_regenerate_id(true)` aan te roepen bij elke inlog, vernietigen we de oude sessie-cookie. Dit maakt "Session Fixation" aanvallen onmogelijk.
- **Automatic Timeout**: In `checkSessionTimeout()` hebben we een algoritme dat de tijd sinds de laatste klik meet. Na 30 minuten word de sessie hard afgesloten.

---

# 3. OWASP Top 10 Bestrijding

De GamePlan Scheduler is getoetst aan de **OWASP Top 10**, de wereldwijde standaard voor veilige webapplicaties.

### ⚠️ A03:2021 - Injectie (SQLi)
Dit is de klassieke hack waarbij iemand SQL-commando's typt in een tekstveld.
- **Onze Verdediging**: **100% Prepared Statements via PDO**. De SQL-code en de data worden gescheiden verstuurd. De database weet: "Alles wat nu komt is platte tekst, nooit een commando."

### ⚠️ A01:2021 - Gebrekkige Toegangscontrole (Broken Access Control)
Kan gebruiker A de data van gebruiker B bewerken?
- **Onze Verdediging**: De `checkOwnership()` functie. Elke query die data bewerkt of inziet, bevat een verplichte check op `user_id`. Het is technisch onmogelijk om andermans data te beïnvloeden, zelfs niet door het ID in de URL aan te passen.

### ⚠️ A03:2021 - Cross-Site Scripting (XSS)
- **Onze Verdediging**: De `safeEcho()` wrapper. Alle data die van de database naar het scherm gaat, wordt door `htmlspecialchars` gehaald. Eventuele scripts worden omgezet in onschadelijke tekst die de browser niet uitvoert.

---

# 4. Data-Integriteit & Soft Deletes

Beveiliging gaat ook over de **beschikbaarheid** en **integriteit** van data. Daarom verwijderen we nooit harde data.
- **Logica**: Een `DELETE` actie is in werkelijkheid een `UPDATE` waarbij de kolom `deleted_at` wordt gevuld.
- **Voordeel**: Bij een foutieve actie of een hackpoging waarbij data wordt gewist, kan de systeembeheerder de data binnen enkele seconden herstellen. Dit is een professionele eis bij grote IT-bedrijven.

---

# 5. Conclusie: Een Onverwoestbaar Fundament

De beveiliging van de GamePlan Scheduler is geen "pleister" die achteraf is geplakt. Het is verweven in het hart (het DNA) van de code. Van de database-architectuur in `db.php` tot de validatie in `functions.php`: overal is nagedacht over risico's en mitigaties. Dit project is daarmee niet alleen functioneel, maar ook **veilig naar industriestandaarden**.

---
**REVISIE STATUS**: LEGENDARY QUALITY ACHIEVED
*Harsha Kanaparthi - 2026*
