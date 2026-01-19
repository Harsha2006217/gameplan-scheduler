# VALIDATIE DOCUMENTATIE (DUTCH/NEDERLANDS)
## GamePlan Scheduler - Complete A-Z Handleiding

**Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Datum**: 19-01-2026

---

# Inhoudsopgave

1. [Applicatie Overzicht](#1-applicatie-overzicht)
2. [Volledige Lijst van Alle Validaties](#2-volledige-lijst-van-alle-validaties)
3. [Validatie Algoritmes](#3-validatie-algoritmes)
4. [Login Stroom met Algoritme](#4-login-stroom-met-algoritme)
5. [Alle Functionele Stromen](#5-alle-functionele-stromen)
6. [Code Stroom Diagrammen](#6-code-stroom-diagrammen)

---

# 1. Applicatie Overzicht

**GamePlan Scheduler** is een webapplicatie voor het beheren van gaming schema's, evenementen en vrienden.

## Belangrijke Bestanden

| Bestand | Doel |
|---------|------|
| login.php | Gebruiker login pagina |
| register.php | Gebruiker registratie pagina |
| index.php | Dashboard/Home pagina |
| functions.php | Kern validatie & logica |
| script.js | Client-side validatie |
| db.php | Database verbinding |
| add_schedule.php | Schema toevoegen |
| add_event.php | Evenement toevoegen |
| add_friend.php | Vriend toevoegen |

---

# 2. Volledige Lijst van Alle Validaties

## 2.1 Server-Side Validaties (PHP - functions.php)

| # | Validatie | Functie | Regel | Beschrijving |
|---|-----------|---------|-------|--------------|
| 1 | **Verplicht Veld** | `validateRequired()` | 68-86 | Controleert of veld leeg is of alleen spaties bevat (BUG FIX #1001) |
| 2 | **Datum Formaat** | `validateDate()` | 97-117 | Valideert JJJJ-MM-DD formaat en zorgt dat datum vandaag of toekomst is (BUG FIX #1004) |
| 3 | **Tijd Formaat** | `validateTime()` | 123-130 | Valideert UU:MM formaat (00-23 uren, 00-59 minuten) |
| 4 | **E-mail Formaat** | `validateEmail()` | 136-142 | Valideert correct e-mail formaat met PHP filter |
| 5 | **URL Formaat** | `validateUrl()` | 148-154 | Valideert URL formaat (optioneel veld) |
| 6 | **Komma-Gescheiden** | `validateCommaSeparated()` | 160-171 | Valideert dat komma-gescheiden waarden geen lege items bevatten |
| 7 | **XSS Bescherming** | `safeEcho()` | 50-55 | Escapet HTML om XSS aanvallen te voorkomen |
| 8 | **Eigendoms Check** | `checkOwnership()` | 640-645 | Verifieert dat gebruiker eigenaar is van record |

## 2.2 Client-Side Validaties (JavaScript - script.js)

| # | Validatie | Functie | Regel | Beschrijving |
|---|-----------|---------|-------|--------------|
| 1 | **Login Formulier** | `validateLoginForm()` | 38-68 | Valideert e-mail en wachtwoord voor login |
| 2 | **Registratie Formulier** | `validateRegisterForm()` | 93-136 | Valideert gebruikersnaam, e-mail, wachtwoord voor registratie |
| 3 | **Schema Formulier** | `validateScheduleForm()` | 163-224 | Valideert speltitel, datum, tijd, vrienden velden |
| 4 | **Evenement Formulier** | `validateEventForm()` | 253-327 | Valideert evenement titel, datum, tijd, beschrijving, URL |
| 5 | **Verwijder Bevestiging** | `initializeFeatures()` | 380-388 | Bevestigt voor verwijder acties |

## 2.3 Authenticatie Validaties

| # | Validatie | Functie | Bestand | Beschrijving |
|---|-----------|---------|---------|--------------|
| 1 | **Login Check** | `isLoggedIn()` | functions.php:211 | Controleert of gebruikerssessie bestaat |
| 2 | **Sessie Timeout** | `checkSessionTimeout()` | functions.php:239 | Auto-uitloggen na 30 minuten inactiviteit |
| 3 | **Wachtwoord Verificatie** | `loginUser()` | functions.php:307 | Verifieert dat wachtwoord hash overeenkomt |
| 4 | **E-mail Uniek** | `registerUser()` | functions.php:269-272 | Controleert dat e-mail niet al geregistreerd is |

## 2.4 HTML5 Ingebouwde Validaties

| # | Attribuut | Gebruikt In | Beschrijving |
|---|-----------|-------------|--------------|
| 1 | `required` | Alle formulier velden | Browser voorkomt lege verzending |
| 2 | `type="email"` | E-mail velden | Browser valideert e-mail formaat |
| 3 | `type="date"` | Datum velden | Browser toont datum kiezer |
| 4 | `type="time"` | Tijd velden | Browser toont tijd kiezer |
| 5 | `type="url"` | URL velden | Browser valideert URL formaat |
| 6 | `maxlength` | Tekst velden | Beperkt karakter invoer |
| 7 | `minlength` | Wachtwoord veld | Vereist minimum karakters |
| 8 | `min` | Datum velden | Stelt minimum datum in (vandaag) |

---

# 3. Validatie Algoritmes

## 3.1 Algoritme: `validateRequired()` (BUG FIX #1001)

```
ALGORITME: validateRequired(value, fieldName, maxLength)
═════════════════════════════════════════════════════════
INVOER:  value (tekst), fieldName (naam), maxLength (getal)
UITVOER: foutmelding (tekst) OF null (als geldig)

BEGIN
    STAP 1: Verwijder witruimte van begin en einde
            value = trim(value)
    
    STAP 2: Controleer of leeg OF alleen spaties bevat (BUG FIX #1001)
            ALS value leeg is OF overeenkomt met regex /^\s*$/
                RETOURNEER fout: "fieldName mag niet leeg zijn of alleen spaties bevatten"
            EINDE ALS
    
    STAP 3: Controleer maximum lengte (indien opgegeven)
            ALS maxLength > 0 EN lengte(value) > maxLength
                RETOURNEER fout: "fieldName overschrijdt maximum lengte"
            EINDE ALS
    
    STAP 4: Alle validaties geslaagd
            RETOURNEER null (geeft geldig aan)
EINDE
```

## 3.2 Algoritme: `validateDate()` (BUG FIX #1004)

```
ALGORITME: validateDate(date)
═════════════════════════════
INVOER:  date (tekst in formaat JJJJ-MM-DD)
UITVOER: foutmelding (tekst) OF null (als geldig)

BEGIN
    STAP 1: Parseer datum met DateTime::createFromFormat
            dateObj = DateTime::createFromFormat('Y-m-d', date)
    
    STAP 2: Verifieer dat datum geparsed is EN exact overeenkomt (BUG FIX #1004)
            ALS dateObj false is OF dateObj.format('Y-m-d') != date
                RETOURNEER fout: "Ongeldig datum formaat. Gebruik JJJJ-MM-DD."
            EINDE ALS
    
    STAP 3: Controleer of datum vandaag of in de toekomst is
            today = new DateTime('today')
            ALS dateObj < today
                RETOURNEER fout: "Datum moet vandaag of in de toekomst zijn."
            EINDE ALS
    
    STAP 4: Alle validaties geslaagd
            RETOURNEER null (geeft geldig aan)
EINDE
```

## 3.3 Algoritme: `validateTime()`

```
ALGORITME: validateTime(time)
═════════════════════════════
INVOER:  time (tekst in formaat UU:MM)
UITVOER: foutmelding (tekst) OF null (als geldig)

BEGIN
    STAP 1: Controleer tijd formaat met regex
            regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
            ALS time NIET overeenkomt met regex
                RETOURNEER fout: "Ongeldig tijd formaat (UU:MM)"
            EINDE ALS
    
    STAP 2: Validatie geslaagd
            RETOURNEER null (geeft geldig aan)
EINDE
```

## 3.4 Algoritme: `validateEmail()`

```
ALGORITME: validateEmail(email)
═══════════════════════════════
INVOER:  email (tekst)
UITVOER: foutmelding (tekst) OF null (als geldig)

BEGIN
    STAP 1: Gebruik PHP filter_var met FILTER_VALIDATE_EMAIL
            ALS filter_var(email, FILTER_VALIDATE_EMAIL) false is
                RETOURNEER fout: "Ongeldig e-mail formaat"
            EINDE ALS
    
    STAP 2: Validatie geslaagd
            RETOURNEER null (geeft geldig aan)
EINDE
```

## 3.5 Algoritme: `validateUrl()`

```
ALGORITME: validateUrl(url)
═══════════════════════════
INVOER:  url (tekst, optioneel)
UITVOER: foutmelding (tekst) OF null (als geldig)

BEGIN
    STAP 1: Controleer of URL opgegeven is en valideer formaat
            ALS url NIET leeg is EN filter_var(url, FILTER_VALIDATE_URL) false is
                RETOURNEER fout: "Ongeldig URL formaat"
            EINDE ALS
    
    STAP 2: Validatie geslaagd (leeg is ook geldig voor optioneel)
            RETOURNEER null (geeft geldig aan)
EINDE
```

## 3.6 Algoritme: `validateLoginForm()` (JavaScript)

```
ALGORITME: validateLoginForm()
══════════════════════════════
INVOER:  Formulier velden (e-mail, wachtwoord) uit DOM
UITVOER: boolean (true = verzending toestaan, false = blokkeren)

BEGIN
    STAP 1: Haal e-mail waarde op en trim
            email = document.getElementById('email').value.trim()
    
    STAP 2: Haal wachtwoord waarde op en trim
            password = document.getElementById('password').value.trim()
    
    STAP 3: Controleer of beide velden ingevuld zijn
            ALS email leeg is OF password leeg is
                TOON alert: "E-mail en wachtwoord zijn verplicht"
                RETOURNEER false (blokkeer verzending)
            EINDE ALS
    
    STAP 4: Valideer e-mail formaat met regex
            regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            ALS email NIET overeenkomt met regex
                TOON alert: "Ongeldig e-mail formaat"
                RETOURNEER false (blokkeer verzending)
            EINDE ALS
    
    STAP 5: Alle validaties geslaagd
            RETOURNEER true (verzending toestaan)
EINDE
```

## 3.7 Algoritme: `validateRegisterForm()` (JavaScript)

```
ALGORITME: validateRegisterForm()
═════════════════════════════════
INVOER:  Formulier velden (gebruikersnaam, e-mail, wachtwoord) uit DOM
UITVOER: boolean (true = verzending toestaan, false = blokkeren)

BEGIN
    STAP 1: Haal alle formulier waarden op met trim
            username = document.getElementById('username').value.trim()
            email = document.getElementById('email').value.trim()
            password = document.getElementById('password').value.trim()
    
    STAP 2: Controleer alle verplichte velden
            ALS username leeg OF email leeg OF password leeg
                TOON alert: "Alle velden zijn verplicht"
                RETOURNEER false
            EINDE ALS
    
    STAP 3: Controleer op alleen-spaties gebruikersnaam (BUG FIX #1001)
            ALS username overeenkomt met /^\s*$/
                TOON alert: "Gebruikersnaam kan niet alleen spaties zijn"
                RETOURNEER false
            EINDE ALS
    
    STAP 4: Controleer gebruikersnaam lengte (max 50)
            ALS username.length > 50
                TOON alert: "Gebruikersnaam te lang (max 50 tekens)"
                RETOURNEER false
            EINDE ALS
    
    STAP 5: Valideer e-mail formaat
            ALS email NIET overeenkomt met /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                TOON alert: "Ongeldig e-mail formaat"
                RETOURNEER false
            EINDE ALS
    
    STAP 6: Controleer wachtwoord minimum lengte (8 tekens)
            ALS password.length < 8
                TOON alert: "Wachtwoord moet minimaal 8 tekens zijn"
                RETOURNEER false
            EINDE ALS
    
    STAP 7: Alle validaties geslaagd
            RETOURNEER true
EINDE
```

---

# 4. Login Stroom met Algoritme

## 4.1 Complete Login Stroom Algoritme

```
ALGORITME: Complete Login Stroom
════════════════════════════════
BESTANDEN: login.php, functions.php, script.js, db.php

FASE 1: PAGINA LADEN
════════════════════
    1.1 Browser vraagt login.php aan
    1.2 login.php includeert functions.php
    1.3 functions.php start sessie (regel 32-37)
    1.4 isLoggedIn() controleert of al ingelogd (regel 211-214)
        ALS ingelogd → redirect naar index.php
    1.5 Initialiseer $error = '' (regel 42)
    1.6 Render HTML formulier met JavaScript validatie

FASE 2: CLIENT-SIDE VALIDATIE (Als gebruiker op Submit klikt)
═════════════════════════════════════════════════════════════
    2.1 onsubmit triggert validateLoginForm() in script.js
    2.2 Haal e-mail en wachtwoord waarden op met trim()
    2.3 Controleer of beide velden ingevuld zijn
        ALS leeg → toon alert, RETOURNEER false (blokkeer verzending)
    2.4 Valideer e-mail formaat met regex
        ALS ongeldig → toon alert, RETOURNEER false
    2.5 Alle client validaties geslaagd → RETOURNEER true (sta verzending toe)

FASE 3: SERVER-SIDE VERWERKING
══════════════════════════════
    3.1 login.php ontvangt POST verzoek (regel 51)
    3.2 Haal e-mail en wachtwoord uit $_POST (regel 56-57)
    3.3 Roep loginUser(email, password) aan in functions.php (regel 61)

FASE 4: loginUser() FUNCTIE (functions.php regel 292-317)
═════════════════════════════════════════════════════════
    4.1 Verkrijg database verbinding
    4.2 Valideer e-mail is verplicht: validateRequired(email, "Email")
        ALS fout → retourneer foutmelding
    4.3 Valideer wachtwoord is verplicht: validateRequired(password, "Password")
        ALS fout → retourneer foutmelding
    4.4 Query database voor gebruiker op e-mail (regel 302-304)
        SELECT user_id, username, password_hash 
        FROM Users WHERE email = :email AND deleted_at IS NULL
    4.5 Verifieer wachtwoord met bcrypt (regel 307)
        ALS gebruiker niet gevonden OF password_verify faalt
            RETOURNEER "Ongeldige e-mail of wachtwoord"
    4.6 Maak sessie aan (regel 312-315)
        - Zet $_SESSION['user_id']
        - Zet $_SESSION['username']
        - Regenereer sessie ID voor veiligheid
        - Update laatste activiteit timestamp
    4.7 RETOURNEER null (succes, geen fout)

FASE 5: NA-LOGIN REDIRECT
═════════════════════════
    5.1 Controleer of loginUser() fout retourneerde (regel 65)
        ALS geen fout ($error is falsy)
            Redirect naar index.php → EXIT
        ANDERS
            Toon fout in formulier
```

---

# 5. Alle Functionele Stromen

## 5.1 Authenticatie Stromen

| # | Stroom Naam | Bestanden | Beschrijving |
|---|-------------|-----------|--------------|
| 1 | **Gebruiker Registratie** | register.php → functions.php → db.php | Nieuwe gebruiker maakt account aan met gebruikersnaam, e-mail, wachtwoord |
| 2 | **Gebruiker Login** | login.php → functions.php → db.php | Gebruiker authenticeert en maakt sessie aan |
| 3 | **Gebruiker Logout** | header.php → functions.php | Vernietigt sessie en redirect naar login |
| 4 | **Sessie Timeout** | Elke beveiligde pagina → functions.php | Auto-uitloggen na 30 minuten inactiviteit |

## 5.2 Schema Beheer Stromen (CRUD)

| # | Stroom Naam | Bestanden | Beschrijving |
|---|-------------|-----------|--------------|
| 1 | **Schema Toevoegen** | add_schedule.php → functions.php → db.php | Maak nieuw gaming schema |
| 2 | **Schema's Bekijken** | index.php → functions.php → db.php | Toon alle gebruiker's schema's |
| 3 | **Schema Bewerken** | edit_schedule.php → functions.php → db.php | Wijzig bestaand schema |
| 4 | **Schema Verwijderen** | delete.php → functions.php → db.php | Verwijder schema (soft delete) |

## 5.3 Evenement Beheer Stromen (CRUD)

| # | Stroom Naam | Bestanden | Beschrijving |
|---|-------------|-----------|--------------|
| 1 | **Evenement Toevoegen** | add_event.php → functions.php → db.php | Maak gaming evenement (toernooi, stream) |
| 2 | **Evenementen Bekijken** | index.php → functions.php → db.php | Toon alle gebruiker's evenementen |
| 3 | **Evenement Bewerken** | edit_event.php → functions.php → db.php | Wijzig bestaand evenement |
| 4 | **Evenement Verwijderen** | delete.php → functions.php → db.php | Verwijder evenement (soft delete) |

## 5.4 Vrienden Beheer Stromen (CRUD)

| # | Stroom Naam | Bestanden | Beschrijving |
|---|-------------|-----------|--------------|
| 1 | **Vriend Toevoegen** | add_friend.php → functions.php → db.php | Voeg gaming vriend toe op gebruikersnaam |
| 2 | **Vrienden Bekijken** | index.php, add_friend.php → functions.php | Toon vriendenlijst |
| 3 | **Vriend Bewerken** | edit_friend.php → functions.php → db.php | Update vriend info/status |
| 4 | **Vriend Verwijderen** | delete.php → functions.php → db.php | Verwijder vriend (soft delete) |

## 5.5 Favoriete Games Stromen (CRUD)

| # | Stroom Naam | Bestanden | Beschrijving |
|---|-------------|-----------|--------------|
| 1 | **Favoriet Toevoegen** | profile.php → functions.php → db.php | Voeg game toe aan favorieten |
| 2 | **Favorieten Bekijken** | index.php → functions.php → db.php | Toon favoriete games |
| 3 | **Favoriet Bewerken** | edit_favorite.php → functions.php → db.php | Update favoriet details |
| 4 | **Favoriet Verwijderen** | delete.php → functions.php → db.php | Verwijder uit favorieten |

---

# 6. Code Stroom Diagrammen

## 6.1 Login Pagina Laad Stroom

```
[Browser opent login.php]
        ↓
[Includeert functions.php]
        ↓
[Sessie start]
        ↓
[Controle: Gebruiker ingelogd?] ──Ja──→ [Redirect naar index.php]
        ↓ Nee
[Toon login formulier]
        ↓
[Gebruiker vult e-mail/wachtwoord in]
        ↓
[Klik Submit]
        ↓
[validateLoginForm() in script.js]
        ↓
[Controle: E-mail ingevuld?] ──Nee──→ [Alert: E-mail verplicht]
        ↓ Ja
[Controle: Wachtwoord ingevuld?] ──Nee──→ [Alert: Wachtwoord verplicht]
        ↓ Ja
[Controle: Geldig e-mail formaat?] ──Nee──→ [Alert: Ongeldig e-mail]
        ↓ Ja
[Verzend formulier naar server]
        ↓
[login.php ontvangt POST]
        ↓
[Roep loginUser() aan]
        ↓
[Controle: E-mail verplicht?] ──Nee──→ [Retourneer fout]
        ↓ Ja
[Controle: Wachtwoord verplicht?] ──Nee──→ [Retourneer fout]
        ↓ Ja
[Query database voor gebruiker]
        ↓
[Controle: Gebruiker gevonden?] ──Nee──→ [Retourneer: Ongeldige gegevens]
        ↓ Ja
[Controle: Wachtwoord correct?] ──Nee──→ [Retourneer: Ongeldige gegevens]
        ↓ Ja
[Maak sessie aan]
        ↓
[Regenereer sessie ID]
        ↓
[Update laatste activiteit]
        ↓
[Retourneer succes]
        ↓
[Redirect naar index.php]
        ↓
[DASHBOARD GELADEN]
```

## 6.2 Home Pagina (Dashboard) Laad Stroom

```
[Browser opent index.php]
        ↓
[Includeert functions.php]
        ↓
[checkSessionTimeout()]
        ↓
[Sessie verlopen? (30min)] ──Ja──→ [session_destroy() → login.php]
        ↓ Nee
[Update $_SESSION['last_activity']]
        ↓
[isLoggedIn() controle]
        ↓
[Gebruiker ingelogd?] ──Nee──→ [Redirect naar login.php]
        ↓ Ja
[getUserId()]
        ↓
[updateLastActivity()]
        ↓
[Haal sorteer parameters uit URL]
        ↓
[getFriends(userId)] ──→ Retourneert vrienden array
        ↓
[getFavoriteGames(userId)] ──→ Retourneert favorieten array
        ↓
[getSchedules(userId, sort)] ──→ Retourneert schema's array
        ↓
[getEvents(userId, sort)] ──→ Retourneert evenementen array
        ↓
[Include header.php] ──→ Navigatie balk
        ↓
[getMessage()] ──→ Sessie berichten
        ↓
[Sectie 1: Vrienden Tabel]
        ↓
[Sectie 2: Favorieten Tabel]
        ↓
[Sectie 3: Schema's Tabel (met sortering)]
        ↓
[Sectie 4: Evenementen Tabel (met sortering)]
        ↓
[Include footer.php]
        ↓
[Laad script.js]
        ↓
[initializeFeatures()]
        ↓
[DASHBOARD KLAAR]
```

---

# Samenvatting Tabellen

## Alle Validatie Functies Samenvatting

| Categorie | Functie | Locatie | Bug Fix | Beschrijving |
|-----------|---------|---------|---------|--------------|
| **Server-Side** | `validateRequired()` | functions.php:68 | #1001 | Controleert leeg/alleen-spaties |
| **Server-Side** | `validateDate()` | functions.php:97 | #1004 | Valideert datum formaat en toekomst |
| **Server-Side** | `validateTime()` | functions.php:123 | - | Valideert UU:MM formaat |
| **Server-Side** | `validateEmail()` | functions.php:136 | - | Valideert e-mail formaat |
| **Server-Side** | `validateUrl()` | functions.php:148 | - | Valideert URL formaat |
| **Server-Side** | `validateCommaSeparated()` | functions.php:160 | - | Valideert komma-gescheiden |
| **Client-Side** | `validateLoginForm()` | script.js:38 | - | Login validatie |
| **Client-Side** | `validateRegisterForm()` | script.js:93 | #1001 | Registratie validatie |
| **Client-Side** | `validateScheduleForm()` | script.js:163 | #1001, #1004 | Schema validatie |
| **Client-Side** | `validateEventForm()` | script.js:253 | #1001, #1004 | Evenement validatie |

## Alle CRUD Operaties Samenvatting

| Entiteit | Aanmaken | Lezen | Bijwerken | Verwijderen |
|----------|----------|-------|-----------|-------------|
| **Gebruiker** | `registerUser()` | `isLoggedIn()` | - | - |
| **Sessie** | `loginUser()` | `getUserId()` | `updateLastActivity()` | `logout()` |
| **Schema** | `addSchedule()` | `getSchedules()` | `editSchedule()` | `deleteSchedule()` |
| **Evenement** | `addEvent()` | `getEvents()` | `editEvent()` | `deleteEvent()` |
| **Vriend** | `addFriend()` | `getFriends()` | `updateFriend()` | `deleteFriend()` |
| **Favoriet** | `addFavoriteGame()` | `getFavoriteGames()` | `updateFavoriteGame()` | `deleteFavoriteGame()` |

---

**EINDE DOCUMENT**

Dit document bevat de complete A-Z documentatie in het Nederlands voor de GamePlan Scheduler applicatie.
Klaar voor MBO-4 examen inlevering!
