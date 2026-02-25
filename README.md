# GamePlan Scheduler - Volledige Projectdocumentatie

**Auteur:** Harsha Kanaparthi
**Studentnummer:** 2195344
**Datum:** 30-09-2025
**Project:** GamePlan Scheduler
**Opleiding:** MBO-4 Software Development
**Kerntaak:** K1-W3 Realisatie

---

## Inhoudsopgave

1. [Projectbeschrijving](#1-projectbeschrijving)
2. [Mapstructuur](#2-mapstructuur)
3. [Technische Specificaties](#3-technische-specificaties)
4. [Database Structuur](#4-database-structuur)
5. [Alle Validaties met Algoritmen](#5-alle-validaties-met-algoritmen)
6. [Alle Functionele Flows](#6-alle-functionele-flows)
7. [Code Flow Diagrammen](#7-code-flow-diagrammen)
8. [Beveiligingsmaatregelen](#8-beveiligingsmaatregelen)
9. [Foutafhandeling](#9-foutafhandeling)
10. [Volledige Functiereferentie](#10-volledige-functiereferentie)
11. [Installatie-instructies](#11-installatie-instructies)

---

## 1. Projectbeschrijving

### Wat is GamePlan Scheduler?

GamePlan Scheduler is een webapplicatie waarmee gamers hun gaming-activiteiten kunnen organiseren. De applicatie biedt de volgende kernfuncties:

- **Registreren en inloggen** met een beveiligd account
- **Vrienden beheren** (toevoegen, bewerken, verwijderen)
- **Favoriete spellen beheren** (toevoegen, bewerken, verwijderen)
- **Gaming-schema's plannen** met datum, tijd en vrienden
- **Evenementen aanmaken** (toernooien, streams) met herinneringen
- **Kalenderoverzicht** met alle geplande activiteiten
- **Herinneringen** die automatisch verschijnen voor evenementen

### Voor wie is het bedoeld?

De applicatie is bedoeld voor gamers die hun speelsessies willen plannen en coördineren met vrienden. Het lost het probleem op dat gamers geen centraal overzicht hebben van wanneer ze met wie gaan spelen.

### Gebruikte technologieën

| Technologie            | Doel                                                |
| ---------------------- | --------------------------------------------------- |
| PHP 7.4+               | Server-side logica en formulierverwerking           |
| MySQL (InnoDB)         | Database voor opslag van alle gegevens              |
| PDO                    | Veilige database-verbinding met prepared statements |
| HTML5                  | Structuur van de webpagina's                        |
| CSS3 + Bootstrap 5.3.3 | Styling en responsief ontwerp                       |
| JavaScript (Vanilla)   | Client-side validatie en interactieve functies      |
| XAMPP                  | Lokale ontwikkelomgeving (Apache + MySQL)           |
| Git                    | Versiebeheer                                        |

---

## 2. Mapstructuur

```
gameplan-scheduler/
|
|-- db.php                  -> Database verbinding (PDO Singleton)
|-- functions.php           -> Alle validatie-, authenticatie- en CRUD-functies
|-- header.php              -> Navigatiebalk (herbruikbaar component)
|-- footer.php              -> Voettekst (herbruikbaar component)
|-- style.css               -> Alle CSS-styling (donker gaming-thema)
|-- script.js               -> Client-side JavaScript validatie
|-- database.sql            -> SQL-schema om de database aan te maken
|
|-- login.php               -> Inlogpagina
|-- register.php            -> Registratiepagina
|-- logout.php              -> Uitlogscript (sessie vernietigen)
|-- index.php               -> Dashboard (hoofdpagina na inloggen)
|-- profile.php             -> Favoriete spellen beheren
|-- add_friend.php          -> Vriend toevoegen
|-- edit_friend.php         -> Vriend bewerken
|-- add_schedule.php        -> Gaming-schema toevoegen
|-- edit_schedule.php       -> Gaming-schema bewerken
|-- add_event.php           -> Evenement toevoegen
|-- edit_event.php          -> Evenement bewerken
|-- edit_favorite.php       -> Favoriet spel bewerken
|-- delete.php              -> Universele verwijder-handler
|-- privacy.php             -> Privacybeleid pagina
|-- contact.php             -> Contactpagina
|
|-- Demo Fotos/             -> Screenshots van de applicatie
|-- K1-W3-DEMO VIDEO.mp4   -> Demo-video van de applicatie
|-- *.pdf                   -> Examendocumenten (planning, ontwerp, testen, etc.)
```

### Scheiding van Verantwoordelijkheden (Separation of Concerns)

| Laag        | Bestanden                               | Verantwoordelijkheid                     |
| ----------- | --------------------------------------- | ---------------------------------------- |
| Database    | `db.php`, `database.sql`                | Verbinding en schema                     |
| Logica      | `functions.php`                         | Validatie, authenticatie, CRUD-operaties |
| Presentatie | `header.php`, `footer.php`, `style.css` | Visuele weergave                         |
| Pagina's    | `login.php`, `index.php`, etc.          | Formulieren en paginastructuur           |
| Client-side | `script.js`                             | Formuliervalidatie in de browser         |

---

## 3. Technische Specificaties

### Database

- **Engine:** InnoDB (ondersteunt foreign keys en transacties)
- **Tekenset:** utf8mb4 (ondersteunt alle Unicode-tekens)
- **Vergelijking:** utf8mb4_unicode_ci (hoofdletterongevoelig)

### Server

- **Webserver:** Apache via XAMPP
- **PHP-versie:** 7.4 of hoger
- **Database:** MySQL via XAMPP

### Front-end

- **CSS-framework:** Bootstrap 5.3.3
- **Thema:** Donker gaming-thema met glassmorphism-effect
- **Responsief:** Aangepast voor desktop, tablet en mobiel
- **Minimale knophoogte:** 40px (voor mobiele bruikbaarheid)

---

## 4. Database Structuur

De database `gameplan_db` bevat 6 tabellen:

### 4.1 Tabel: Users (Gebruikers)

De hoofdtabel. Alle andere tabellen verwijzen hiernaar.

| Kolom           | Type                         | Beschrijving                          |
| --------------- | ---------------------------- | ------------------------------------- |
| `user_id`       | INT AUTO_INCREMENT           | Primaire sleutel, uniek per gebruiker |
| `username`      | VARCHAR(50) NOT NULL         | Weergavenaam van de gebruiker         |
| `email`         | VARCHAR(100) UNIQUE NOT NULL | E-mailadres, gebruikt voor inloggen   |
| `password_hash` | VARCHAR(255) NOT NULL        | Bcrypt-versleuteld wachtwoord         |
| `last_activity` | TIMESTAMP                    | Wanneer gebruiker laatst actief was   |
| `deleted_at`    | TIMESTAMP NULL               | Soft delete: NULL = actief            |

### 4.2 Tabel: Games (Spellen)

Slaat alle spellen op die gebruikers als favoriet kunnen toevoegen.

| Kolom         | Type                  | Beschrijving              |
| ------------- | --------------------- | ------------------------- |
| `game_id`     | INT AUTO_INCREMENT    | Primaire sleutel          |
| `titel`       | VARCHAR(100) NOT NULL | Naam van het spel         |
| `description` | TEXT                  | Beschrijving van het spel |
| `deleted_at`  | TIMESTAMP NULL        | Soft delete               |

### 4.3 Tabel: UserGames (Koppeltabel - Favorieten)

Verbindt gebruikers met hun favoriete spellen (veel-op-veel relatie).

| Kolom     | Type         | Beschrijving                       |
| --------- | ------------ | ---------------------------------- |
| `user_id` | INT NOT NULL | Verwijst naar Users                |
| `game_id` | INT NOT NULL | Verwijst naar Games                |
| `note`    | TEXT         | Persoonlijke notitie over het spel |

**Primaire sleutel:** Samengesteld uit `user_id` + `game_id` (voorkomt duplicaten).
**Foreign keys:** Beide met ON DELETE CASCADE.

### 4.4 Tabel: Friends (Vrienden)

Slaat gaming-vrienden op per gebruiker.

| Kolom             | Type                          | Beschrijving           |
| ----------------- | ----------------------------- | ---------------------- |
| `friend_id`       | INT AUTO_INCREMENT            | Primaire sleutel       |
| `user_id`         | INT NOT NULL                  | Verwijst naar Users    |
| `friend_username` | VARCHAR(50) NOT NULL          | Gamertag van de vriend |
| `note`            | TEXT                          | Persoonlijke notitie   |
| `status`          | VARCHAR(50) DEFAULT 'Offline' | Online-status          |
| `deleted_at`      | TIMESTAMP NULL                | Soft delete            |

### 4.5 Tabel: Schedules (Gaming-schema's)

Slaat geplande speelsessies op.

| Kolom         | Type               | Beschrijving                         |
| ------------- | ------------------ | ------------------------------------ |
| `schedule_id` | INT AUTO_INCREMENT | Primaire sleutel                     |
| `user_id`     | INT NOT NULL       | Verwijst naar Users                  |
| `game_id`     | INT NOT NULL       | Verwijst naar Games                  |
| `date`        | DATE NOT NULL      | Datum van de sessie                  |
| `time`        | TIME NOT NULL      | Tijdstip van de sessie               |
| `friends`     | TEXT               | Kommagescheiden lijst van vrienden   |
| `shared_with` | TEXT               | Kommagescheiden lijst van gebruikers |
| `deleted_at`  | TIMESTAMP NULL     | Soft delete                          |

### 4.6 Tabel: Events (Evenementen)

Slaat gaming-evenementen op (toernooien, streams, etc.).

| Kolom           | Type                  | Beschrijving                           |
| --------------- | --------------------- | -------------------------------------- |
| `event_id`      | INT AUTO_INCREMENT    | Primaire sleutel                       |
| `user_id`       | INT NOT NULL          | Verwijst naar Users                    |
| `title`         | VARCHAR(100) NOT NULL | Naam van het evenement                 |
| `date`          | DATE NOT NULL         | Datum                                  |
| `time`          | TIME NOT NULL         | Tijdstip                               |
| `description`   | TEXT                  | Details over het evenement             |
| `reminder`      | VARCHAR(50)           | Herinnering: 'none', '1_hour', '1_day' |
| `external_link` | VARCHAR(255)          | URL naar evenementpagina               |
| `shared_with`   | TEXT                  | Kommagescheiden gebruikerslijst        |
| `deleted_at`    | TIMESTAMP NULL        | Soft delete                            |

### Database-indexen

| Index                     | Tabel     | Kolom(men)    | Doel                                 |
| ------------------------- | --------- | ------------- | ------------------------------------ |
| `idx_users_email`         | Users     | email         | Versnelt inloggen (zoeken op e-mail) |
| `idx_schedules_user_date` | Schedules | user_id, date | Versnelt ophalen van schema's        |
| `idx_events_user_date`    | Events    | user_id, date | Versnelt ophalen van evenementen     |

### Relaties tussen tabellen

```
Users (1) ----< (N) Friends          (een gebruiker heeft veel vrienden)
Users (1) ----< (N) UserGames        (een gebruiker heeft veel favorieten)
Users (1) ----< (N) Schedules        (een gebruiker heeft veel schema's)
Users (1) ----< (N) Events           (een gebruiker heeft veel evenementen)
Games (1) ----< (N) UserGames        (een spel kan door veel gebruikers gefavoriet zijn)
Games (1) ----< (N) Schedules        (een spel kan in veel schema's voorkomen)
```

---

## 5. Alle Validaties met Algoritmen

### 5.1 Overzicht van alle validaties in de applicatie

| Nr  | Validatie                    | Waar (server)                 | Waar (client)          | Formulieren              |
| --- | ---------------------------- | ----------------------------- | ---------------------- | ------------------------ |
| V1  | Verplicht veld niet leeg     | `validateRequired()`          | JS `!field` check      | Alle formulieren         |
| V2  | Geen alleen-spaties invoer   | `validateRequired()` regex    | JS `^\s*$` regex       | Alle formulieren         |
| V3  | Maximum lengte controle      | `validateRequired()` strlen   | JS `.length`           | Gebruikersnaam, titel    |
| V4  | E-mail formaat geldig        | `validateEmail()` filter_var  | JS email regex         | Login, registratie       |
| V5  | Wachtwoord minimaal 8 tekens | `registerUser()` strlen       | JS `.length < 8`       | Registratie              |
| V6  | Datum formaat JJJJ-MM-DD     | `validateDate()` DateTime     | JS `new Date()`        | Schema, evenement        |
| V7  | Datum in de toekomst         | `validateDate()` vergelijking | JS datumvergelijking   | Schema, evenement        |
| V8  | Tijd formaat UU:MM           | `validateTime()` regex        | JS tijd regex          | Schema, evenement        |
| V9  | URL formaat geldig           | `validateUrl()` filter_var    | JS URL regex           | Evenement                |
| V10 | Kommagescheiden lijst geldig | `validateCommaSeparated()`    | JS `^[a-zA-Z0-9,\s]*$` | Schema, evenement        |
| V11 | E-mail niet al geregistreerd | `registerUser()` SELECT       | -                      | Registratie              |
| V12 | Spel niet al in favorieten   | `addFavoriteGame()` SELECT    | -                      | Favoriet toevoegen       |
| V13 | Vriend niet al toegevoegd    | `addFriend()` SELECT          | -                      | Vriend toevoegen         |
| V14 | Eigenaarschap controleren    | `checkOwnership()`            | -                      | Bewerken, verwijderen    |
| V15 | Beschrijving max 500 tekens  | `addEvent()` strlen           | JS `.length > 500`     | Evenement                |
| V16 | Herinnering geldig           | `addEvent()` in_array         | -                      | Evenement                |
| V17 | Sessie timeout (30 min)      | `checkSessionTimeout()`       | -                      | Alle beveiligde pagina's |
| V18 | Inlogstatus controleren      | `isLoggedIn()`                | -                      | Alle beveiligde pagina's |

### 5.2 Algoritme per validatie

#### V1 + V2 + V3: Verplicht veld validatie (`validateRequired`)

**Bestand:** `functions.php` regel 68-86

```
ALGORITME: valideerVerplichtVeld(waarde, veldnaam, maxLengte)

1. VERWIJDER witruimte aan begin en einde van waarde (trim)
2. ALS waarde leeg is OF waarde alleen uit spaties bestaat:
   -> RETOURNEER foutmelding: "[veldnaam] mag niet leeg zijn"
3. ALS maxLengte groter dan 0 is EN lengte van waarde groter dan maxLengte:
   -> RETOURNEER foutmelding: "[veldnaam] overschrijdt maximale lengte"
4. RETOURNEER null (geen fout, validatie geslaagd)
```

#### V4: E-mail formaat validatie (`validateEmail`)

**Bestand:** `functions.php` regel 136-142

```
ALGORITME: valideerEmail(email)

1. CONTROLEER email met PHP filter_var(FILTER_VALIDATE_EMAIL)
   Dit controleert of de email het formaat "naam@domein.extensie" heeft
2. ALS filter ONGELDIG retourneert:
   -> RETOURNEER foutmelding: "Ongeldig e-mail formaat"
3. RETOURNEER null (geen fout)
```

#### V5: Wachtwoord lengte validatie

**Bestand:** `functions.php` regel 265-266

```
ALGORITME: valideerWachtwoord(wachtwoord)

1. ALS lengte van wachtwoord kleiner dan 8 tekens:
   -> RETOURNEER foutmelding: "Wachtwoord moet minimaal 8 tekens zijn"
2. RETOURNEER null (geen fout)
```

#### V6 + V7: Datum validatie (`validateDate`)

**Bestand:** `functions.php` regel 97-117

```
ALGORITME: valideerDatum(datumString)

1. PROBEER datumString te ontleden als datum met formaat JJJJ-MM-DD
   Gebruik DateTime::createFromFormat('Y-m-d', datumString)
2. ALS ontleden mislukt OF geformatteerde datum niet exact overeenkomt met invoer:
   -> RETOURNEER foutmelding: "Ongeldig datum formaat"
   (Dit vangt ongeldige datums als 2025-13-45 of 2025-02-30)
3. MAAK een nieuw DateTime-object voor vandaag
4. ALS ingevoerde datum eerder is dan vandaag:
   -> RETOURNEER foutmelding: "Datum moet vandaag of in de toekomst zijn"
5. RETOURNEER null (geen fout)
```

#### V8: Tijd validatie (`validateTime`)

**Bestand:** `functions.php` regel 123-130

```
ALGORITME: valideerTijd(tijdString)

1. CONTROLEER tijdString met reguliere expressie: ^([01]?[0-9]|2[0-3]):[0-5][0-9]$
   Dit betekent:
   - Uren: 00-09, 10-19, of 20-23
   - Dubbele punt als scheidingsteken
   - Minuten: 00-59
2. ALS patroon NIET overeenkomt:
   -> RETOURNEER foutmelding: "Ongeldig tijd formaat (UU:MM)"
3. RETOURNEER null (geen fout)
```

#### V9: URL validatie (`validateUrl`)

**Bestand:** `functions.php` regel 148-154

```
ALGORITME: valideerUrl(url)

1. ALS url NIET leeg is:
   1a. CONTROLEER url met PHP filter_var(FILTER_VALIDATE_URL)
   1b. ALS filter ONGELDIG retourneert:
       -> RETOURNEER foutmelding: "Ongeldig URL formaat"
2. RETOURNEER null (geen fout, URL is optioneel)
```

#### V10: Kommagescheiden lijst validatie (`validateCommaSeparated`)

**Bestand:** `functions.php` regel 160-171

```
ALGORITME: valideerKommaGescheiden(waarde, veldnaam)

1. ALS waarde leeg is:
   -> RETOURNEER null (veld is optioneel)
2. SPLITS waarde op komma's in een lijst van items
3. VOOR ELK item in de lijst:
   3a. VERWIJDER witruimte van het item (trim)
   3b. ALS het item leeg is na trimmen:
       -> RETOURNEER foutmelding: "[veldnaam] bevat lege items"
4. RETOURNEER null (geen fout)
```

#### V11: E-mail uniciteit validatie

**Bestand:** `functions.php` regel 269-272

```
ALGORITME: controleerEmailBestaat(email)

1. VOER database-query uit: tel gebruikers met dit e-mailadres
   WHERE email = :email AND deleted_at IS NULL
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "E-mail al geregistreerd"
3. RETOURNEER null (e-mail is beschikbaar)
```

#### V12: Spel al in favorieten validatie

**Bestand:** `functions.php` regel 369-372

```
ALGORITME: controleerAlFavoriet(userId, gameId)

1. VOER database-query uit: tel records in UserGames
   WHERE user_id = :userId AND game_id = :gameId
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "Spel al in favorieten"
3. RETOURNEER null (nog niet als favoriet)
```

#### V13: Vriend al toegevoegd validatie

**Bestand:** `functions.php` regel 451-454

```
ALGORITME: controleerAlVrienden(userId, vriendNaam)

1. VOER database-query uit: tel records in Friends
   WHERE user_id = :userId
   AND LOWER(friend_username) = LOWER(:vriendNaam)
   AND deleted_at IS NULL
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "Al vrienden"
3. RETOURNEER null (nog niet als vriend)
```

#### V14: Eigenaarschap validatie (`checkOwnership`)

**Bestand:** `functions.php` regel 640-645

```
ALGORITME: controleerEigenaarschap(tabel, idKolom, id, userId)

1. VOER database-query uit:
   SELECT COUNT(*) FROM [tabel]
   WHERE [idKolom] = :id AND user_id = :userId AND deleted_at IS NULL
2. ALS telling groter dan 0:
   -> RETOURNEER true (gebruiker is eigenaar)
3. ANDERS:
   -> RETOURNEER false (geen eigenaar)
```

#### V15: Beschrijving lengte validatie

**Bestand:** `functions.php` regel 577-578

```
ALGORITME: valideerBeschrijving(beschrijving)

1. ALS beschrijving NIET leeg is EN lengte groter dan 500:
   -> RETOURNEER foutmelding: "Beschrijving te lang (max 500)"
2. RETOURNEER null (geen fout)
```

#### V16: Herinnering waarde validatie

**Bestand:** `functions.php` regel 579-580

```
ALGORITME: valideerHerinnering(herinnering)

1. ALS herinnering NIET in de lijst ['none', '1_hour', '1_day']:
   -> RETOURNEER foutmelding: "Ongeldige herinnering"
2. RETOURNEER null (geen fout)
```

#### V17: Sessie timeout validatie (`checkSessionTimeout`)

**Bestand:** `functions.php` regel 239-248

```
ALGORITME: controleerSessieTimeout()

1. ALS gebruiker ingelogd is EN laatste activiteit is bekend:
   1a. BEREKEN verschil: huidige tijd - laatste activiteit
   1b. ALS verschil groter dan 1800 seconden (30 minuten):
       -> VERNIETIG de sessie
       -> STUUR gebruiker naar login.php met bericht "sessie verlopen"
       -> STOP script
2. WERK laatste activiteit bij naar huidige tijd
```

#### V18: Inlogstatus validatie (`isLoggedIn`)

**Bestand:** `functions.php` regel 211-214

```
ALGORITME: isIngelogd()

1. CONTROLEER of $_SESSION['user_id'] bestaat
2. ALS het bestaat:
   -> RETOURNEER true (ingelogd)
3. ANDERS:
   -> RETOURNEER false (niet ingelogd)
```

### 5.3 JavaScript client-side validaties

#### Login formulier validatie (`validateLoginForm`)

**Bestand:** `script.js` regel 38-68

```
ALGORITME: valideerLoginFormulier()

1. HAAL e-mail waarde op en verwijder witruimte
2. HAAL wachtwoord waarde op en verwijder witruimte
3. ALS e-mail OF wachtwoord leeg is:
   -> TOON melding: "E-mail en wachtwoord zijn verplicht"
   -> RETOURNEER false (blokkeer verzending)
4. ALS e-mail NIET voldoet aan regex ^[^\s@]+@[^\s@]+\.[^\s@]+$:
   -> TOON melding: "Ongeldig e-mail formaat"
   -> RETOURNEER false
5. RETOURNEER true (sta verzending toe)
```

#### Registratie formulier validatie (`validateRegisterForm`)

**Bestand:** `script.js` regel 93-136

```
ALGORITME: valideerRegistratieFormulier()

1. HAAL gebruikersnaam, e-mail en wachtwoord op (met trim)
2. ALS een van de velden leeg is:
   -> TOON melding: "Alle velden zijn verplicht"
   -> RETOURNEER false
3. ALS gebruikersnaam alleen uit spaties bestaat (regex ^\s*$):
   -> TOON melding: "Gebruikersnaam kan niet alleen spaties zijn"
   -> RETOURNEER false
4. ALS gebruikersnaam langer dan 50 tekens:
   -> TOON melding: "Gebruikersnaam te lang"
   -> RETOURNEER false
5. ALS e-mail ongeldig formaat:
   -> TOON melding: "Ongeldig e-mail formaat"
   -> RETOURNEER false
6. ALS wachtwoord korter dan 8 tekens:
   -> TOON melding: "Wachtwoord moet minimaal 8 tekens zijn"
   -> RETOURNEER false
7. RETOURNEER true
```

#### Schema formulier validatie (`validateScheduleForm`)

**Bestand:** `script.js` regel 163-224

```
ALGORITME: valideerSchemaFormulier()

1. HAAL alle velden op: speltitel, datum, tijd, vrienden, gedeeld-met
2. ALS speltitel leeg is OF alleen spaties:
   -> TOON melding: "Speltitel is verplicht"
   -> RETOURNEER false
3. ALS datum leeg is:
   -> TOON melding: "Datum is verplicht"
   -> RETOURNEER false
4. MAAK Date-object van ingevoerde datum
5. MAAK Date-object van vandaag (uren op 0 gezet)
6. ALS datum ongeldig is (NaN):
   -> TOON melding: "Ongeldig datum formaat"
   -> RETOURNEER false
7. ALS datum in het verleden:
   -> TOON melding: "Datum moet vandaag of in de toekomst zijn"
   -> RETOURNEER false
8. ALS tijd niet voldoet aan regex ^([01]?[0-9]|2[0-3]):[0-5][0-9]$:
   -> TOON melding: "Ongeldig tijd formaat"
   -> RETOURNEER false
9. ALS vrienden-veld ongeldige tekens bevat:
   -> TOON melding: "Ongeldige tekens in vrienden veld"
   -> RETOURNEER false
10. ALS gedeeld-met veld ongeldige tekens bevat:
    -> TOON melding: "Ongeldige tekens in gedeeld-met veld"
    -> RETOURNEER false
11. RETOURNEER true
```

#### Evenement formulier validatie (`validateEventForm`)

**Bestand:** `script.js` regel 253-327

```
ALGORITME: valideerEvenementFormulier()

1. HAAL alle velden op: titel, datum, tijd, beschrijving, link, gedeeld-met
2. ALS titel leeg is OF alleen spaties:
   -> TOON melding: "Titel is verplicht"
   -> RETOURNEER false
3. ALS titel langer dan 100 tekens:
   -> TOON melding: "Titel te lang (max 100)"
   -> RETOURNEER false
4. ALS datum leeg is:
   -> TOON melding: "Datum is verplicht"
   -> RETOURNEER false
5. VALIDEER datum (zelfde als schema: geldig formaat + toekomst)
6. ALS datum ongeldig OF in verleden:
   -> TOON melding
   -> RETOURNEER false
7. ALS tijd ongeldig formaat:
   -> TOON melding: "Ongeldig tijd formaat"
   -> RETOURNEER false
8. ALS beschrijving langer dan 500 tekens:
   -> TOON melding: "Beschrijving te lang"
   -> RETOURNEER false
9. ALS externe link ingevuld EN niet geldig URL-formaat:
   -> TOON melding: "Ongeldig link formaat"
   -> RETOURNEER false
10. ALS gedeeld-met ongeldige tekens:
    -> TOON melding
    -> RETOURNEER false
11. RETOURNEER true
```

---

## 6. Alle Functionele Flows

### 6.1 Flow: Gebruiker Registreren

```
STAP 1: Gebruiker opent register.php in de browser
STAP 2: Systeem controleert of gebruiker al ingelogd is
        -> ALS ja: redirect naar index.php
        -> ALS nee: toon registratieformulier
STAP 3: Gebruiker vult in: gebruikersnaam, e-mail, wachtwoord
STAP 4: Gebruiker klikt op "Account Aanmaken"
STAP 5: JavaScript validateRegisterForm() draait:
        -> Controleert alle velden (zie algoritme hierboven)
        -> ALS ongeldig: toon foutmelding, blokkeer verzending
STAP 6: Formulier wordt verzonden naar server (POST)
STAP 7: Server roept registerUser() aan:
        -> Valideer gebruikersnaam (niet leeg, max 50 tekens)
        -> Valideer e-mail formaat
        -> Valideer wachtwoord (niet leeg, min 8 tekens)
        -> Controleer of e-mail al bestaat in database
        -> Versleutel wachtwoord met bcrypt
        -> Sla op in Users-tabel
STAP 8: ALS registratie gelukt:
        -> Zet succesbericht in sessie
        -> Redirect naar login.php
STAP 9: ALS registratie mislukt:
        -> Toon foutmelding op registratiepagina
```

### 6.2 Flow: Gebruiker Inloggen

```
STAP 1: Gebruiker opent login.php
STAP 2: Systeem controleert of gebruiker al ingelogd is
        -> ALS ja: redirect naar index.php
STAP 3: Gebruiker vult in: e-mail en wachtwoord
STAP 4: Gebruiker klikt op "Login"
STAP 5: JavaScript validateLoginForm() draait:
        -> Controleert e-mail en wachtwoord niet leeg
        -> Controleert e-mail formaat
STAP 6: Formulier wordt verzonden naar server (POST)
STAP 7: Server roept loginUser() aan:
        -> Valideer dat e-mail en wachtwoord niet leeg zijn
        -> Zoek gebruiker op e-mail in database (WHERE deleted_at IS NULL)
        -> Vergelijk wachtwoord met bcrypt hash (password_verify)
        -> ALS onjuist: retourneer foutmelding
        -> ALS correct: maak sessie aan
STAP 8: Sessie wordt aangemaakt:
        -> $_SESSION['user_id'] = gebruiker ID
        -> $_SESSION['username'] = gebruikersnaam
        -> session_regenerate_id(true) voor veiligheid
        -> Update last_activity in database
STAP 9: Redirect naar index.php (dashboard)
```

### 6.3 Flow: Dashboard Laden (index.php)

```
STAP 1: Gebruiker navigeert naar index.php
STAP 2: Server laadt functions.php (inclusief db.php en sessiestart)
STAP 3: checkSessionTimeout() controleert:
        -> Is gebruiker langer dan 30 minuten inactief?
        -> ALS ja: vernietig sessie, redirect naar login.php
        -> ALS nee: update last_activity
STAP 4: isLoggedIn() controleert of sessie actief is
        -> ALS niet ingelogd: redirect naar login.php
STAP 5: Haal gebruiker-ID op uit sessie
STAP 6: Update last_activity in database
STAP 7: Haal sorteerparameters op uit URL (?sort_schedules=, ?sort_events=)
STAP 8: Haal alle gegevens op uit database:
        -> getFriends(userId)           -> alle vrienden
        -> getFavoriteGames(userId)     -> alle favoriete spellen
        -> getSchedules(userId, sort)   -> alle schema's (gesorteerd)
        -> getEvents(userId, sort)      -> alle evenementen (gesorteerd)
        -> getCalendarItems(userId)     -> gecombineerd kalenderoverzicht
        -> getReminders(userId)         -> actieve herinneringen
STAP 9: Render HTML met alle gegevens:
        -> header.php (navigatiebalk)
        -> Sessiebericht (succes/fout)
        -> Vriendenlijst tabel
        -> Favoriete spellen tabel
        -> Schema's tabel (met sorteerknoppen)
        -> Evenementen tabel (met sorteerknoppen)
        -> Kalender overzicht (kaarten)
        -> footer.php (voettekst)
STAP 10: JavaScript laadt:
         -> Toon herinnering pop-ups als er actieve herinneringen zijn
         -> initializeFeatures() voor interactieve elementen
```

### 6.4 Flow: Vriend Toevoegen

```
STAP 1: Gebruiker klikt op "Vriend Toevoegen" -> add_friend.php
STAP 2: Beveiligingscontroles (sessie, inlogstatus)
STAP 3: Gebruiker vult in: gebruikersnaam vriend, notitie, status
STAP 4: Formulier wordt verzonden (POST)
STAP 5: Server roept addFriend() aan:
        -> Valideer gebruikersnaam (niet leeg, max 50 tekens)
        -> Valideer status (niet leeg, max 50 tekens)
        -> Controleer of al vrienden (hoofdletterongevoelig)
        -> INSERT in Friends-tabel
STAP 6: ALS gelukt: succesbericht, redirect naar add_friend.php
STAP 7: ALS mislukt: toon foutmelding op pagina
```

### 6.5 Flow: Favoriet Spel Toevoegen

```
STAP 1: Gebruiker klikt op "Favoriet Toevoegen" -> profile.php
STAP 2: Beveiligingscontroles
STAP 3: Gebruiker vult in: speltitel, beschrijving, notitie
STAP 4: Formulier wordt verzonden (POST)
STAP 5: Server roept addFavoriteGame() aan:
        -> Valideer speltitel (niet leeg, max 100 tekens)
        -> getOrCreateGameId(): zoek of spel al bestaat
           -> ALS bestaat: gebruik bestaand game_id
           -> ALS niet bestaat: maak nieuw spel in Games-tabel
        -> Controleer of spel al in favorieten
        -> INSERT in UserGames-tabel
STAP 6: Redirect met succesbericht of toon fout
```

### 6.6 Flow: Gaming-schema Toevoegen

```
STAP 1: Gebruiker klikt op "Schema Toevoegen" -> add_schedule.php
STAP 2: Beveiligingscontroles
STAP 3: Gebruiker vult in: speltitel, datum, tijd, vrienden, gedeeld-met
STAP 4: Client-side: validateScheduleForm() controleert alle velden
STAP 5: Formulier wordt verzonden (POST)
STAP 6: Server roept addSchedule() aan:
        -> Valideer speltitel (verplicht, max 100 tekens)
        -> Valideer datum (geldig formaat, toekomst)
        -> Valideer tijd (UU:MM formaat)
        -> Valideer vrienden (kommagescheiden)
        -> Valideer gedeeld-met (kommagescheiden)
        -> getOrCreateGameId() voor het spel
        -> INSERT in Schedules-tabel
STAP 7: ALS gelukt: redirect naar index.php met succesbericht
STAP 8: ALS mislukt: toon foutmelding op pagina
```

### 6.7 Flow: Evenement Toevoegen

```
STAP 1: Gebruiker klikt op "Evenement Toevoegen" -> add_event.php
STAP 2: Beveiligingscontroles
STAP 3: Gebruiker vult in: titel, datum, tijd, beschrijving,
        herinnering, externe link, gedeeld-met
STAP 4: Client-side: validateEventForm() controleert alle velden
STAP 5: Formulier wordt verzonden (POST)
STAP 6: Server roept addEvent() aan:
        -> Valideer titel (verplicht, max 100 tekens)
        -> Valideer datum (geldig, toekomst)
        -> Valideer tijd (UU:MM)
        -> Valideer beschrijving (max 500 tekens)
        -> Valideer herinnering (moet 'none', '1_hour' of '1_day' zijn)
        -> Valideer externe link (optioneel, geldig URL-formaat)
        -> Valideer gedeeld-met (kommagescheiden)
        -> INSERT in Events-tabel
STAP 7: ALS gelukt: redirect naar index.php met succesbericht
STAP 8: ALS mislukt: toon foutmelding
```

### 6.8 Flow: Item Bewerken (schema, evenement, vriend, favoriet)

```
STAP 1: Gebruiker klikt op bewerkknop (potlood-icoon)
STAP 2: Redirect naar bewerkpagina met ?id=<item_id> in URL
STAP 3: Beveiligingscontroles (sessie, inlogstatus)
STAP 4: Haal item op uit database met het meegegeven ID
STAP 5: Controleer eigenaarschap (checkOwnership)
        -> ALS geen eigenaar: foutmelding, redirect
STAP 6: Vul formulier met huidige waarden
STAP 7: Gebruiker past waarden aan
STAP 8: Client-side validatie (als van toepassing)
STAP 9: Formulier wordt verzonden (POST)
STAP 10: Server valideert alle velden opnieuw
STAP 11: Server voert UPDATE query uit op database
STAP 12: Redirect met succesbericht
```

### 6.9 Flow: Item Verwijderen

```
STAP 1: Gebruiker klikt op verwijderknop (prullenbak-icoon)
STAP 2: JavaScript confirm() vraagt bevestiging:
        "Weet je zeker dat je wilt verwijderen?"
        -> ALS "Annuleer": niets doen
        -> ALS "OK": ga door
STAP 3: Redirect naar delete.php?type=<type>&id=<id>
STAP 4: Beveiligingscontroles (sessie, inlogstatus)
STAP 5: Bepaal type item (schedule, event, favorite, friend)
STAP 6: Roep juiste verwijderfunctie aan
STAP 7: Verwijderfunctie controleert eigenaarschap
STAP 8: SOFT DELETE: UPDATE tabel SET deleted_at = NOW()
        (Data wordt NIET echt verwijderd, alleen gemarkeerd)
STAP 9: Zet succes- of foutbericht in sessie
STAP 10: Redirect naar juiste pagina:
         -> schedule/event: index.php
         -> favorite: profile.php
         -> friend: add_friend.php
```

### 6.10 Flow: Uitloggen

```
STAP 1: Gebruiker klikt op "Uitloggen" in navigatie
STAP 2: Redirect naar logout.php
STAP 3: Server leegt alle sessievariabelen: $_SESSION = []
STAP 4: Server vernietigt de sessiecookie
STAP 5: Server roept session_destroy() aan
STAP 6: Redirect naar login.php
```

---

## 7. Code Flow Diagrammen

### 7.1 Code Flow: Login Pagina Laden

```
BROWSER                          SERVER
  |                                |
  |-- GET /login.php ------------->|
  |                                |-- Laad functions.php
  |                                |   |-- Laad db.php
  |                                |   |-- Start sessie (session_start)
  |                                |
  |                                |-- isLoggedIn()
  |                                |   |-- Controleer $_SESSION['user_id']
  |                                |   |-- ALS ingelogd: header("Location: index.php")
  |                                |
  |                                |-- Initialiseer $error = ''
  |                                |
  |                                |-- Render HTML:
  |                                |   |-- login.php regel 72-220
  |                                |   |-- <head>: Bootstrap CSS + style.css
  |                                |   |-- <body>: Formulier met e-mail + wachtwoord
  |                                |   |-- <script>: Bootstrap JS + script.js
  |                                |
  |<-- HTML response --------------|
  |                                |
  |-- Gebruiker vult formulier in  |
  |-- Klikt "Login"                |
  |                                |
  |-- validateLoginForm() [JS] --->| (client-side)
  |   |-- Controleer e-mail        |
  |   |-- Controleer wachtwoord    |
  |   |-- ALS geldig: return true  |
  |                                |
  |-- POST /login.php ------------>|
  |   (email + password)           |
  |                                |-- $_SERVER['REQUEST_METHOD'] == 'POST'
  |                                |-- Haal $email en $password uit $_POST
  |                                |-- loginUser($email, $password)
  |                                |   |-- getDBConnection() [db.php]
  |                                |   |   |-- Maak PDO-verbinding (Singleton)
  |                                |   |-- validateRequired($email)
  |                                |   |-- validateRequired($password)
  |                                |   |-- SELECT user WHERE email = :email
  |                                |   |-- password_verify($password, $hash)
  |                                |   |-- ALS onjuist: return foutmelding
  |                                |   |-- ALS correct:
  |                                |   |   |-- $_SESSION['user_id'] = user_id
  |                                |   |   |-- $_SESSION['username'] = username
  |                                |   |   |-- session_regenerate_id(true)
  |                                |   |   |-- updateLastActivity()
  |                                |   |   |-- return null
  |                                |
  |                                |-- ALS geen fout: header("Location: index.php")
  |<-- 302 Redirect naar index.php |
```

**Kritieke bestanden en functies:**

| Bestand         | Functie                | Regel   | Taak                       |
| --------------- | ---------------------- | ------- | -------------------------- |
| `login.php`     | -                      | 26      | Laadt functions.php        |
| `login.php`     | -                      | 35-38   | Controleert of al ingelogd |
| `login.php`     | -                      | 51-70   | Verwerkt POST-formulier    |
| `functions.php` | `loginUser()`          | 292-317 | Authenticatie logica       |
| `functions.php` | `validateRequired()`   | 68-86   | Veldvalidatie              |
| `functions.php` | `isLoggedIn()`         | 211-214 | Sessiecontrole             |
| `functions.php` | `updateLastActivity()` | 229-233 | Activiteit bijwerken       |
| `db.php`        | `getDBConnection()`    | 96-299  | Database verbinding        |
| `script.js`     | `validateLoginForm()`  | 38-68   | Client-side validatie      |

### 7.2 Code Flow: Dashboard (Home) Pagina Laden

```
BROWSER                          SERVER
  |                                |
  |-- GET /index.php ------------->|
  |                                |-- Laad functions.php
  |                                |   |-- Laad db.php (databaseverbinding)
  |                                |   |-- Start sessie
  |                                |
  |                                |-- checkSessionTimeout() [functions.php:239]
  |                                |   |-- Controleer of > 30 min inactief
  |                                |   |-- ALS timeout: session_destroy()
  |                                |   |-- Update $_SESSION['last_activity']
  |                                |
  |                                |-- isLoggedIn() [functions.php:211]
  |                                |   |-- ALS niet ingelogd: redirect login.php
  |                                |
  |                                |-- getUserId() [functions.php:220]
  |                                |   |-- Haal user_id uit sessie
  |                                |
  |                                |-- updateLastActivity() [functions.php:229]
  |                                |   |-- UPDATE Users SET last_activity
  |                                |
  |                                |-- Haal sorteerparameters uit $_GET
  |                                |
  |                                |-- getFriends($userId) [functions.php:488]
  |                                |   |-- SELECT FROM Friends WHERE user_id
  |                                |   |   AND deleted_at IS NULL
  |                                |
  |                                |-- getFavoriteGames($userId) [functions.php:419]
  |                                |   |-- SELECT FROM UserGames JOIN Games
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |
  |                                |-- getSchedules($userId, $sort) [functions.php:521]
  |                                |   |-- Valideer sorteerparameter (whitelist)
  |                                |   |-- SELECT FROM Schedules JOIN Games
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |   |   ORDER BY $sort LIMIT 50
  |                                |
  |                                |-- getEvents($userId, $sort) [functions.php:591]
  |                                |   |-- Valideer sorteerparameter
  |                                |   |-- SELECT FROM Events
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |   |   ORDER BY $sort LIMIT 50
  |                                |
  |                                |-- getCalendarItems($userId) [functions.php:647]
  |                                |   |-- Combineer schema's + evenementen
  |                                |   |-- Sorteer op datum+tijd (usort)
  |                                |
  |                                |-- getReminders($userId) [functions.php:658]
  |                                |   |-- Filter evenementen met herinnering
  |                                |   |-- Controleer of herinneringstijd nu is
  |                                |
  |                                |-- Render HTML:
  |                                |   |-- include 'header.php' (navigatie)
  |                                |   |-- getMessage() (sessiebericht)
  |                                |   |-- Vriendenlijst tabel
  |                                |   |   |-- LOOP: safeEcho() per veld
  |                                |   |-- Favorieten tabel
  |                                |   |-- Schema's tabel (met sorteerknoppen)
  |                                |   |-- Evenementen tabel
  |                                |   |-- Kalenderkaarten
  |                                |   |-- include 'footer.php' (voettekst)
  |                                |   |-- <script>: herinnering pop-ups
  |                                |
  |<-- HTML response --------------|
  |                                |
  |-- Browser toont dashboard      |
  |-- script.js laadt              |
  |   |-- DOMContentLoaded event   |
  |   |-- initializeFeatures()     |
  |   |   |-- Smooth scroll links  |
  |   |   |-- Bevestiging bij delete|
  |   |   |-- Auto-dismiss alerts  |
  |   |-- Toon reminder pop-ups    |
```

**Kritieke bestanden en functies:**

| Bestand         | Functie                 | Regel   | Taak                   |
| --------------- | ----------------------- | ------- | ---------------------- |
| `index.php`     | -                       | 26      | Laadt functions.php    |
| `index.php`     | -                       | 29      | checkSessionTimeout()  |
| `index.php`     | -                       | 32-35   | Inlogcontrole          |
| `index.php`     | -                       | 48-53   | Alle data ophalen      |
| `index.php`     | -                       | 71-292  | HTML rendering         |
| `functions.php` | `checkSessionTimeout()` | 239-248 | Sessie-expiratie       |
| `functions.php` | `getFriends()`          | 488-494 | Vrienden ophalen       |
| `functions.php` | `getFavoriteGames()`    | 419-425 | Favorieten ophalen     |
| `functions.php` | `getSchedules()`        | 521-528 | Schema's ophalen       |
| `functions.php` | `getEvents()`           | 591-598 | Evenementen ophalen    |
| `functions.php` | `getCalendarItems()`    | 647-656 | Kalender samenvoegen   |
| `functions.php` | `getReminders()`        | 658-672 | Herinneringen filteren |
| `functions.php` | `safeEcho()`            | 50-55   | XSS-bescherming        |
| `header.php`    | -                       | 1-158   | Navigatiebalk          |
| `footer.php`    | -                       | 1-90    | Voettekst              |
| `script.js`     | `initializeFeatures()`  | 365-398 | Pagina-initialisatie   |

### 7.3 Code Flow: Item Verwijderen

```
BROWSER                          SERVER
  |                                |
  |-- Gebruiker klikt "Verwijderen"|
  |-- confirm() pop-up ----------->| (client-side)
  |   |-- "Weet je het zeker?"     |
  |   |-- ALS Annuleer: stop      |
  |   |-- ALS OK: ga door         |
  |                                |
  |-- GET /delete.php?type=schedule&id=5 -->|
  |                                |-- Laad functions.php
  |                                |-- checkSessionTimeout()
  |                                |-- isLoggedIn() check
  |                                |-- Haal $type en $id uit $_GET
  |                                |-- Haal $userId uit sessie
  |                                |
  |                                |-- ALS type == 'schedule':
  |                                |   |-- deleteSchedule($userId, $id)
  |                                |   |   |-- checkOwnership() controle
  |                                |   |   |-- UPDATE Schedules
  |                                |   |   |   SET deleted_at = NOW()
  |                                |   |-- $redirect = 'index.php'
  |                                |
  |                                |-- setMessage('success', 'Verwijderd!')
  |                                |-- header("Location: index.php")
  |<-- 302 Redirect --------------|
```

---

## 8. Beveiligingsmaatregelen

### 8.1 Overzicht van alle beveiligingen

| Nr  | Beveiliging            | Implementatie                     | Beschermt tegen          |
| --- | ---------------------- | --------------------------------- | ------------------------ |
| B1  | Wachtwoord hashing     | `password_hash(PASSWORD_BCRYPT)`  | Wachtwoord diefstal      |
| B2  | Prepared statements    | PDO met `:named` parameters       | SQL-injectie             |
| B3  | Output escaping        | `safeEcho()` met htmlspecialchars | XSS-aanvallen            |
| B4  | Sessie regeneratie     | `session_regenerate_id(true)`     | Sessie-hijacking         |
| B5  | Sessie timeout         | 30 minuten inactiviteit           | Onbeheerde sessies       |
| B6  | Eigenaarschap controle | `checkOwnership()`                | Ongeautoriseerde toegang |
| B7  | Inputvalidatie         | Server-side + client-side         | Ongeldige data           |
| B8  | Foutmasking            | Generieke berichten aan gebruiker | Informatielekken         |
| B9  | Soft delete            | `deleted_at` timestamp            | Dataverlies              |
| B10 | Inlogcontrole          | `isLoggedIn()` op elke pagina     | Ongeautoriseerde toegang |

### 8.2 Uitleg per beveiliging

**B1 - Wachtwoord hashing (bcrypt)**
Wachtwoorden worden NOOIT als platte tekst opgeslagen. De functie `password_hash()` met `PASSWORD_BCRYPT` versleutelt het wachtwoord met het Blowfish-algoritme. Bij inloggen wordt `password_verify()` gebruikt om het ingevoerde wachtwoord te vergelijken met de hash. Zelfs als de database wordt gestolen, zijn de wachtwoorden onleesbaar.

**B2 - Prepared statements (SQL-injectie preventie)**
Alle database-queries gebruiken PDO prepared statements met `:named` parameters. De database-engine verwerkt de parameters apart van de query, waardoor kwaadaardige SQL-code niet kan worden uitgevoerd. Voorbeeld:

```php
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
$stmt->execute(['email' => $email]);
```

**B3 - Output escaping (XSS-preventie)**
De functie `safeEcho()` converteert speciale HTML-tekens naar veilige entiteiten:

- `<` wordt `&lt;`
- `>` wordt `&gt;`
- `"` wordt `&quot;`
- `'` wordt `&#039;`

Dit voorkomt dat kwaadaardige scripts in de browser worden uitgevoerd.

**B4 - Sessie regeneratie**
Na succesvol inloggen wordt `session_regenerate_id(true)` aangeroepen. Dit maakt een nieuw sessie-ID aan en vernietigt het oude. Dit beschermt tegen sessie-fixatie aanvallen.

**B5 - Sessie timeout**
Na 30 minuten inactiviteit wordt de sessie automatisch vernietigd. Dit beschermt tegen het scenario dat iemand een computer onbeheerd achterlaat.

**B6 - Eigenaarschap controle**
Voor elke bewerk- of verwijderactie wordt gecontroleerd of de ingelogde gebruiker daadwerkelijk eigenaar is van het item. Dit voorkomt dat gebruiker A de data van gebruiker B kan wijzigen.

**B8 - Foutmasking**
Technische foutmeldingen (met databasepad, queries, etc.) worden NOOIT aan de gebruiker getoond. Ze worden gelogd met `error_log()` voor ontwikkelaars. Gebruikers zien alleen generieke berichten zoals "Er is een fout opgetreden."

**B9 - Soft delete**
Data wordt nooit echt verwijderd. In plaats daarvan wordt het veld `deleted_at` gezet op de huidige datum/tijd. Alle queries filteren op `WHERE deleted_at IS NULL`. Dit biedt mogelijkheid tot herstel.

---

## 9. Foutafhandeling

### 9.1 Patroon: Functie retourwaarden

Alle functies in de applicatie volgen hetzelfde patroon:

- **Succes:** retourneer `null`
- **Fout:** retourneer een foutmelding als string

```php
$error = addSchedule($userId, $title, $date, $time, $friends, $shared);
if ($error) {
    // Toon foutmelding
} else {
    // Actie gelukt, redirect
}
```

### 9.2 Patroon: Database foutafhandeling

```php
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Database fout: " . $e->getMessage());  // Log voor ontwikkelaar
    die("Sorry, er was een probleem.");                // Generiek bericht voor gebruiker
}
```

### 9.3 Patroon: Sessiebericht systeem

Berichten worden opgeslagen in de sessie en op de volgende pagina getoond:

```php
// Op pagina 1: Sla bericht op
setMessage('success', 'Item succesvol toegevoegd!');
header("Location: index.php");

// Op pagina 2 (index.php): Toon en verwijder bericht
echo getMessage();  // Toont Bootstrap alert, verwijdert daarna uit sessie
```

### 9.4 Alle foutmeldingen in de applicatie

| Situatie                  | Foutmelding                                                |
| ------------------------- | ---------------------------------------------------------- |
| Leeg verplicht veld       | "[Veldnaam] mag niet leeg zijn of alleen spaties bevatten" |
| Te lange invoer           | "[Veldnaam] overschrijdt maximale lengte van X tekens"     |
| Ongeldige e-mail          | "Ongeldig e-mail formaat"                                  |
| Ongeldig datum formaat    | "Ongeldig datum formaat. Gebruik JJJJ-MM-DD"               |
| Datum in verleden         | "Datum moet vandaag of in de toekomst zijn"                |
| Ongeldig tijd formaat     | "Ongeldig tijd formaat (UU:MM)"                            |
| Ongeldige URL             | "Ongeldig URL formaat"                                     |
| Lege items in lijst       | "[Veldnaam] bevat lege items"                              |
| Wachtwoord te kort        | "Wachtwoord moet minimaal 8 tekens zijn"                   |
| E-mail al geregistreerd   | "E-mail al geregistreerd"                                  |
| Ongeldige inlog           | "Ongeldige e-mail of wachtwoord"                           |
| Spel al favoriet          | "Spel al in favorieten"                                    |
| Al vrienden               | "Al vrienden"                                              |
| Geen toestemming          | "Geen toestemming om te bewerken/verwijderen"              |
| Beschrijving te lang      | "Beschrijving te lang (max 500)"                           |
| Ongeldige herinnering     | "Ongeldige herinnering"                                    |
| Database fout             | "Registratie/actie mislukt. Probeer opnieuw"               |
| Ongeldig type verwijderen | "Ongeldig type"                                            |

---

## 10. Volledige Functiereferentie

### 10.1 Database functies (`db.php`)

| Functie             | Parameters | Retourneert | Beschrijving                                        |
| ------------------- | ---------- | ----------- | --------------------------------------------------- |
| `getDBConnection()` | geen       | PDO object  | Maakt of hergebruikt databaseverbinding (Singleton) |

### 10.2 Helper functies (`functions.php`)

| Functie                                            | Parameters          | Retourneert         | Beschrijving                       |
| -------------------------------------------------- | ------------------- | ------------------- | ---------------------------------- |
| `safeEcho($string)`                                | string              | string              | Escapet HTML-tekens tegen XSS      |
| `validateRequired($value, $fieldName, $maxLength)` | string, string, int | null of foutmelding | Valideert verplicht veld           |
| `validateDate($date)`                              | string              | null of foutmelding | Valideert datumformaat en toekomst |
| `validateTime($time)`                              | string              | null of foutmelding | Valideert tijdformaat UU:MM        |
| `validateEmail($email)`                            | string              | null of foutmelding | Valideert e-mailformaat            |
| `validateUrl($url)`                                | string              | null of foutmelding | Valideert URL-formaat (optioneel)  |
| `validateCommaSeparated($value, $fieldName)`       | string, string      | null of foutmelding | Valideert kommagescheiden lijst    |

### 10.3 Sessie- en berichtfuncties (`functions.php`)

| Functie                   | Parameters     | Retourneert | Beschrijving                              |
| ------------------------- | -------------- | ----------- | ----------------------------------------- |
| `setMessage($type, $msg)` | string, string | void        | Slaat bericht op in sessie                |
| `getMessage()`            | geen           | HTML string | Haalt bericht op en verwijdert uit sessie |

### 10.4 Authenticatie functies (`functions.php`)

| Functie                                      | Parameters | Retourneert         | Beschrijving                                                      |
| -------------------------------------------- | ---------- | ------------------- | ----------------------------------------------------------------- |
| `isLoggedIn()`                               | geen       | boolean             | Controleert of gebruiker ingelogd is                              |
| `getUserId()`                                | geen       | int                 | Haalt gebruiker-ID uit sessie (0 als niet ingelogd)               |
| `updateLastActivity($pdo, $userId)`          | PDO, int   | void                | Werkt laatste activiteit bij in database                          |
| `checkSessionTimeout()`                      | geen       | void                | Controleert 30-minuten timeout, vernietigt sessie indien verlopen |
| `registerUser($username, $email, $password)` | 3x string  | null of foutmelding | Registreert nieuw account                                         |
| `loginUser($email, $password)`               | 2x string  | null of foutmelding | Authenticeert gebruiker                                           |
| `logout()`                                   | geen       | void                | Vernietigt sessie, redirect naar login                            |

### 10.5 Spel functies (`functions.php`)

| Functie                                                             | Parameters          | Retourneert         | Beschrijving                          |
| ------------------------------------------------------------------- | ------------------- | ------------------- | ------------------------------------- |
| `getOrCreateGameId($pdo, $title, $description)`                     | PDO, string, string | int (game_id)       | Haalt bestaand spel op of maakt nieuw |
| `addFavoriteGame($userId, $title, $description, $note)`             | int, 3x string      | null of foutmelding | Voegt spel toe aan favorieten         |
| `updateFavoriteGame($userId, $gameId, $title, $description, $note)` | int, int, 3x string | null of foutmelding | Bewerkt favoriet spel                 |
| `deleteFavoriteGame($userId, $gameId)`                              | int, int            | null                | Verwijdert spel uit favorieten        |
| `getFavoriteGames($userId)`                                         | int                 | array               | Haalt alle favoriete spellen op       |
| `getGames()`                                                        | geen                | array               | Haalt alle spellen op                 |

### 10.6 Vrienden functies (`functions.php`)

| Functie                                                             | Parameters          | Retourneert         | Beschrijving                    |
| ------------------------------------------------------------------- | ------------------- | ------------------- | ------------------------------- |
| `addFriend($userId, $friendUsername, $note, $status)`               | int, 3x string      | null of foutmelding | Voegt vriend toe                |
| `updateFriend($userId, $friendId, $friendUsername, $note, $status)` | int, int, 3x string | null of foutmelding | Bewerkt vriend                  |
| `deleteFriend($userId, $friendId)`                                  | int, int            | null                | Verwijdert vriend (soft delete) |
| `getFriends($userId)`                                               | int                 | array               | Haalt alle vrienden op          |

### 10.7 Schema functies (`functions.php`)

| Functie                                                                                     | Parameters          | Retourneert         | Beschrijving                    |
| ------------------------------------------------------------------------------------------- | ------------------- | ------------------- | ------------------------------- |
| `addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr)`               | int, 5x string      | null of foutmelding | Maakt gaming-schema             |
| `getSchedules($userId, $sort)`                                                              | int, string         | array               | Haalt schema's op (gesorteerd)  |
| `editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr)` | int, int, 5x string | null of foutmelding | Bewerkt schema                  |
| `deleteSchedule($userId, $scheduleId)`                                                      | int, int            | null of foutmelding | Verwijdert schema (soft delete) |

### 10.8 Evenement functies (`functions.php`)

| Functie                                                                                                      | Parameters          | Retourneert         | Beschrijving                       |
| ------------------------------------------------------------------------------------------------------------ | ------------------- | ------------------- | ---------------------------------- |
| `addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink, $sharedWithStr)`            | int, 7x string      | null of foutmelding | Maakt evenement                    |
| `getEvents($userId, $sort)`                                                                                  | int, string         | array               | Haalt evenementen op (gesorteerd)  |
| `editEvent($userId, $eventId, $title, $date, $time, $description, $reminder, $externalLink, $sharedWithStr)` | int, int, 7x string | null of foutmelding | Bewerkt evenement                  |
| `deleteEvent($userId, $eventId)`                                                                             | int, int            | null of foutmelding | Verwijdert evenement (soft delete) |

### 10.9 Hulpfuncties (`functions.php`)

| Functie                                                 | Parameters          | Retourneert | Beschrijving                                          |
| ------------------------------------------------------- | ------------------- | ----------- | ----------------------------------------------------- |
| `checkOwnership($pdo, $table, $idColumn, $id, $userId)` | PDO, 3x string, int | boolean     | Controleert of gebruiker eigenaar is                  |
| `getCalendarItems($userId)`                             | int                 | array       | Combineert schema's en evenementen, sorteert op datum |
| `getReminders($userId)`                                 | int                 | array       | Filtert evenementen met actieve herinneringen         |

### 10.10 JavaScript functies (`script.js`)

| Functie                           | Parameters     | Retourneert | Beschrijving                                |
| --------------------------------- | -------------- | ----------- | ------------------------------------------- |
| `validateLoginForm()`             | geen           | boolean     | Valideert login formulier                   |
| `validateRegisterForm()`          | geen           | boolean     | Valideert registratie formulier             |
| `validateScheduleForm()`          | geen           | boolean     | Valideert schema formulier                  |
| `validateEventForm()`             | geen           | boolean     | Valideert evenement formulier               |
| `initializeFeatures()`            | geen           | void        | Initialiseert interactieve pagina-elementen |
| `showNotification(message, type)` | string, string | void        | Toont toast-notificatie                     |

---

## 11. Installatie-instructies

### Stap 1: Omgeving opzetten

1. Installeer XAMPP (bevat Apache en MySQL)
2. Start Apache en MySQL via XAMPP Control Panel

### Stap 2: Database aanmaken

1. Open phpMyAdmin via `http://localhost/phpmyadmin`
2. Importeer het bestand `database.sql`
3. Dit maakt de database `gameplan_db` aan met alle tabellen en voorbeelddata

### Stap 3: Applicatie plaatsen

1. Kopieer de map `gameplan-scheduler` naar `C:\xampp\htdocs\`
2. De mapstructuur wordt: `C:\xampp\htdocs\gameplan-scheduler\`

### Stap 4: Applicatie openen

1. Open een browser
2. Ga naar `http://localhost/gameplan-scheduler/`
3. Je ziet de loginpagina
4. Klik op "Registreer hier" om een account aan te maken
5. Log in met je nieuwe account
6. Je komt op het dashboard waar je vrienden, spellen, schema's en evenementen kunt beheren

---

## Bugfixes

### Bug #1001: Alleen-spaties validatie

**Probleem:** Gebruikers konden velden opslaan met alleen spaties (bijv. " ").
**Oorzaak:** De oude validatie controleerde alleen of het veld leeg was, niet of het alleen spaties bevatte.
**Oplossing:** Regex controle `/^\s*$/` toegevoegd aan `validateRequired()` en alle JavaScript validatiefuncties.
**Toegepast in:** functions.php, script.js (alle formuliervalidaties).

### Bug #1004: Strenge datumvalidatie

**Probleem:** Ongeldige datums zoals 2025-13-45 werden geaccepteerd.
**Oorzaak:** De oude validatie controleerde alleen het formaat, niet of de datum daadwerkelijk bestaat.
**Oplossing:** `DateTime::createFromFormat()` gebruikt met strikte vergelijking (`$dateObj->format('Y-m-d') !== $date`).
**Toegepast in:** functions.php (`validateDate()`), script.js (schema en evenement formulier).

---

_Dit document beschrijft de volledige GamePlan Scheduler applicatie van A tot Z. Alle code, validaties, flows en beveiligingen zijn hierin gedocumenteerd._

_Auteur: Harsha Kanaparthi | Studentnummer: 2195344_
