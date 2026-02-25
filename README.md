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
12. [Testen (K1-W4)](#12-testen-k1-w4)
13. [Verbeteren (K1-W5)](#13-verbeteren-k1-w5)
14. [Examenpresentatie Hulp](#14-examenpresentatie-hulp)

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

**Bestand:** `functions.php` regel 65-83

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

**Bestand:** `functions.php` regel 133-139

```
ALGORITME: valideerEmail(email)

1. CONTROLEER email met PHP filter_var(FILTER_VALIDATE_EMAIL)
   Dit controleert of de email het formaat "naam@domein.extensie" heeft
2. ALS filter ONGELDIG retourneert:
   -> RETOURNEER foutmelding: "Ongeldig e-mail formaat"
3. RETOURNEER null (geen fout)
```

#### V5: Wachtwoord lengte validatie

**Bestand:** `functions.php` regel 262-263

```
ALGORITME: valideerWachtwoord(wachtwoord)

1. ALS lengte van wachtwoord kleiner dan 8 tekens:
   -> RETOURNEER foutmelding: "Wachtwoord moet minimaal 8 tekens zijn"
2. RETOURNEER null (geen fout)
```

#### V6 + V7: Datum validatie (`validateDate`)

**Bestand:** `functions.php` regel 94-114

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

**Bestand:** `functions.php` regel 120-127

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

**Bestand:** `functions.php` regel 145-151

```
ALGORITME: valideerUrl(url)

1. ALS url NIET leeg is:
   1a. CONTROLEER url met PHP filter_var(FILTER_VALIDATE_URL)
   1b. ALS filter ONGELDIG retourneert:
       -> RETOURNEER foutmelding: "Ongeldig URL formaat"
2. RETOURNEER null (geen fout, URL is optioneel)
```

#### V10: Kommagescheiden lijst validatie (`validateCommaSeparated`)

**Bestand:** `functions.php` regel 157-168

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

**Bestand:** `functions.php` regel 266-269

```
ALGORITME: controleerEmailBestaat(email)

1. VOER database-query uit: tel gebruikers met dit e-mailadres
   WHERE email = :email AND deleted_at IS NULL
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "E-mail al geregistreerd"
3. RETOURNEER null (e-mail is beschikbaar)
```

#### V12: Spel al in favorieten validatie

**Bestand:** `functions.php` regel 366-369

```
ALGORITME: controleerAlFavoriet(userId, gameId)

1. VOER database-query uit: tel records in UserGames
   WHERE user_id = :userId AND game_id = :gameId
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "Spel al in favorieten"
3. RETOURNEER null (nog niet als favoriet)
```

#### V13: Vriend al toegevoegd validatie

**Bestand:** `functions.php` regel 448-451

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

**Bestand:** `functions.php` regel 637-642

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

**Bestand:** `functions.php` regel 574-575

```
ALGORITME: valideerBeschrijving(beschrijving)

1. ALS beschrijving NIET leeg is EN lengte groter dan 500:
   -> RETOURNEER foutmelding: "Beschrijving te lang (max 500)"
2. RETOURNEER null (geen fout)
```

#### V16: Herinnering waarde validatie

**Bestand:** `functions.php` regel 576-577

```
ALGORITME: valideerHerinnering(herinnering)

1. ALS herinnering NIET in de lijst ['none', '1_hour', '1_day']:
   -> RETOURNEER foutmelding: "Ongeldige herinnering"
2. RETOURNEER null (geen fout)
```

#### V17: Sessie timeout validatie (`checkSessionTimeout`)

**Bestand:** `functions.php` regel 236-245

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

**Bestand:** `functions.php` regel 208-211

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

**Bestand:** `script.js` regel 41-71

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

**Bestand:** `script.js` regel 96-139

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

**Bestand:** `script.js` regel 166-227

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

**Bestand:** `script.js` regel 256-330

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
| `functions.php` | `loginUser()`          | 289-314 | Authenticatie logica       |
| `functions.php` | `validateRequired()`   | 65-83   | Veldvalidatie              |
| `functions.php` | `isLoggedIn()`         | 208-211 | Sessiecontrole             |
| `functions.php` | `updateLastActivity()` | 226-230 | Activiteit bijwerken       |
| `db.php`        | `getDBConnection()`    | 88-291  | Database verbinding        |
| `script.js`     | `validateLoginForm()`  | 41-71   | Client-side validatie      |

### 7.2 Code Flow: Dashboard (Home) Pagina Laden

```
BROWSER                          SERVER
  |                                |
  |-- GET /index.php ------------->|
  |                                |-- Laad functions.php
  |                                |   |-- Laad db.php (databaseverbinding)
  |                                |   |-- Start sessie
  |                                |
  |                                |-- checkSessionTimeout() [functions.php:236]
  |                                |   |-- Controleer of > 30 min inactief
  |                                |   |-- ALS timeout: session_destroy()
  |                                |   |-- Update $_SESSION['last_activity']
  |                                |
  |                                |-- isLoggedIn() [functions.php:208]
  |                                |   |-- ALS niet ingelogd: redirect login.php
  |                                |
  |                                |-- getUserId() [functions.php:217]
  |                                |   |-- Haal user_id uit sessie
  |                                |
  |                                |-- updateLastActivity() [functions.php:226]
  |                                |   |-- UPDATE Users SET last_activity
  |                                |
  |                                |-- Haal sorteerparameters uit $_GET
  |                                |
  |                                |-- getFriends($userId) [functions.php:485]
  |                                |   |-- SELECT FROM Friends WHERE user_id
  |                                |   |   AND deleted_at IS NULL
  |                                |
  |                                |-- getFavoriteGames($userId) [functions.php:416]
  |                                |   |-- SELECT FROM UserGames JOIN Games
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |
  |                                |-- getSchedules($userId, $sort) [functions.php:518]
  |                                |   |-- Valideer sorteerparameter (whitelist)
  |                                |   |-- SELECT FROM Schedules JOIN Games
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |   |   ORDER BY $sort LIMIT 50
  |                                |
  |                                |-- getEvents($userId, $sort) [functions.php:588]
  |                                |   |-- Valideer sorteerparameter
  |                                |   |-- SELECT FROM Events
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |   |   ORDER BY $sort LIMIT 50
  |                                |
  |                                |-- getCalendarItems($userId) [functions.php:644]
  |                                |   |-- Combineer schema's + evenementen
  |                                |   |-- Sorteer op datum+tijd (usort)
  |                                |
  |                                |-- getReminders($userId) [functions.php:655]
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
| `functions.php` | `checkSessionTimeout()` | 236-245 | Sessie-expiratie       |
| `functions.php` | `getFriends()`          | 485-491 | Vrienden ophalen       |
| `functions.php` | `getFavoriteGames()`    | 416-422 | Favorieten ophalen     |
| `functions.php` | `getSchedules()`        | 518-525 | Schema's ophalen       |
| `functions.php` | `getEvents()`           | 588-595 | Evenementen ophalen    |
| `functions.php` | `getCalendarItems()`    | 644-653 | Kalender samenvoegen   |
| `functions.php` | `getReminders()`        | 655-669 | Herinneringen filteren |
| `functions.php` | `safeEcho()`            | 47-52   | XSS-bescherming        |
| `header.php`    | -                       | 1-158   | Navigatiebalk          |
| `footer.php`    | -                       | 1-90    | Voettekst              |
| `script.js`     | `initializeFeatures()`  | 368-401 | Pagina-initialisatie   |

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
Na succesvol inloggen wordt `session_regenerate_id(true)` aangeroepen in de `loginUser()` functie. Dit maakt een nieuw sessie-ID aan en vernietigt het oude. Dit beschermt tegen sessie-fixatie aanvallen waarbij een aanvaller een sessie-ID probeert te hergebruiken.

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

## 12. Testen (K1-W4)

### 12.1 Teststrategie

De applicatie is getest op drie niveaus:

1. **Handmatige functionele tests** - Elke functie stap voor stap doorlopen
2. **Validatietests** - Alle invoervalidaties testen met geldige en ongeldige data
3. **Beveiligingstests** - Controleren of beveiligingsmaatregelen werken

### 12.2 Testcases: Registratie

| Test   | Invoer                                                                     | Verwacht resultaat                                               | Geslaagd |
| ------ | -------------------------------------------------------------------------- | ---------------------------------------------------------------- | -------- |
| TC-R01 | Gebruikersnaam: "Harsha", E-mail: "harsha@test.nl", Wachtwoord: "Test1234" | Account aangemaakt, redirect naar login                          | Ja       |
| TC-R02 | Gebruikersnaam: leeg                                                       | Foutmelding: "Username mag niet leeg zijn"                       | Ja       |
| TC-R03 | Gebruikersnaam: " " (alleen spaties)                                       | Foutmelding: "Username kan niet alleen spaties zijn" (Bug #1001) | Ja       |
| TC-R04 | E-mail: "geengeldigemail"                                                  | Foutmelding: "Ongeldig e-mail formaat"                           | Ja       |
| TC-R05 | Wachtwoord: "kort" (minder dan 8 tekens)                                   | Foutmelding: "Wachtwoord moet minimaal 8 tekens zijn"            | Ja       |
| TC-R06 | E-mail die al bestaat                                                      | Foutmelding: "E-mail al geregistreerd"                           | Ja       |
| TC-R07 | Gebruikersnaam: meer dan 50 tekens                                         | Foutmelding: maximale lengte overschreden                        | Ja       |

### 12.3 Testcases: Inloggen

| Test   | Invoer                         | Verwacht resultaat                                 | Geslaagd |
| ------ | ------------------------------ | -------------------------------------------------- | -------- |
| TC-L01 | Juiste e-mail en wachtwoord    | Ingelogd, redirect naar dashboard                  | Ja       |
| TC-L02 | Juiste e-mail, fout wachtwoord | Foutmelding: "Ongeldige e-mail of wachtwoord"      | Ja       |
| TC-L03 | Niet-bestaande e-mail          | Foutmelding: "Ongeldige e-mail of wachtwoord"      | Ja       |
| TC-L04 | Beide velden leeg              | Foutmelding: "E-mail en wachtwoord zijn verplicht" | Ja       |
| TC-L05 | Al ingelogd, login.php openen  | Redirect naar index.php (dashboard)                | Ja       |

### 12.4 Testcases: Gaming-schema toevoegen

| Test   | Invoer                                              | Verwacht resultaat                                       | Geslaagd |
| ------ | --------------------------------------------------- | -------------------------------------------------------- | -------- |
| TC-S01 | Speltitel: "Fortnite", Datum: morgen, Tijd: "20:00" | Schema toegevoegd, redirect naar dashboard               | Ja       |
| TC-S02 | Speltitel: leeg                                     | Foutmelding: "Game title mag niet leeg zijn"             | Ja       |
| TC-S03 | Speltitel: " " (alleen spaties)                     | Foutmelding (Bug #1001 fix)                              | Ja       |
| TC-S04 | Datum: gisteren                                     | Foutmelding: "Datum moet vandaag of in de toekomst zijn" | Ja       |
| TC-S05 | Datum: "2025-13-45" (ongeldig)                      | Foutmelding: "Ongeldig datum formaat" (Bug #1004 fix)    | Ja       |
| TC-S06 | Tijd: "25:99" (ongeldig)                            | Foutmelding: "Ongeldig tijd formaat"                     | Ja       |
| TC-S07 | Vrienden: "player1, player2"                        | Succesvol opgeslagen met vrienden                        | Ja       |
| TC-S08 | Vrienden: "player1,,player2" (lege items)           | Foutmelding: lege items in lijst                         | Ja       |

### 12.5 Testcases: Evenement toevoegen

| Test   | Invoer                                                          | Verwacht resultaat                  | Geslaagd |
| ------ | --------------------------------------------------------------- | ----------------------------------- | -------- |
| TC-E01 | Titel: "Fortnite Toernooi", Datum: volgende week, Tijd: "15:00" | Evenement toegevoegd                | Ja       |
| TC-E02 | Titel: leeg                                                     | Foutmelding: titel verplicht        | Ja       |
| TC-E03 | Titel: meer dan 100 tekens                                      | Foutmelding: titel te lang          | Ja       |
| TC-E04 | Beschrijving: meer dan 500 tekens                               | Foutmelding: beschrijving te lang   | Ja       |
| TC-E05 | Externe link: "geen-url"                                        | Foutmelding: "Ongeldig URL formaat" | Ja       |
| TC-E06 | Externe link: "https://twitch.tv/stream"                        | Succesvol opgeslagen met link       | Ja       |
| TC-E07 | Herinnering: "1_hour"                                           | Succesvol, herinnering actief       | Ja       |

### 12.6 Testcases: Vriend toevoegen

| Test   | Invoer                            | Verwacht resultaat                  | Geslaagd |
| ------ | --------------------------------- | ----------------------------------- | -------- |
| TC-F01 | Gebruikersnaam: "GamerPro123"     | Vriend toegevoegd                   | Ja       |
| TC-F02 | Gebruikersnaam: leeg              | Foutmelding: verplicht veld         | Ja       |
| TC-F03 | Gebruikersnaam: " " (spaties)     | Foutmelding (Bug #1001)             | Ja       |
| TC-F04 | Dezelfde vriend opnieuw toevoegen | Foutmelding: "Al vrienden"          | Ja       |
| TC-F05 | Status: "Online" selecteren       | Vriend opgeslagen met Online status | Ja       |

### 12.7 Testcases: Favoriet spel toevoegen

| Test   | Invoer                                       | Verwacht resultaat                               | Geslaagd |
| ------ | -------------------------------------------- | ------------------------------------------------ | -------- |
| TC-G01 | Titel: "Minecraft", Beschrijving: "Bouwspel" | Spel toegevoegd aan favorieten                   | Ja       |
| TC-G02 | Titel: leeg                                  | Foutmelding: titel verplicht                     | Ja       |
| TC-G03 | Zelfde spel opnieuw als favoriet             | Foutmelding: "Spel al in favorieten"             | Ja       |
| TC-G04 | Titel: "Nieuw Spel" (nog niet in database)   | Nieuw spel aangemaakt en als favoriet toegevoegd | Ja       |

### 12.8 Testcases: Bewerken en verwijderen

| Test   | Actie                                        | Verwacht resultaat                                     | Geslaagd |
| ------ | -------------------------------------------- | ------------------------------------------------------ | -------- |
| TC-D01 | Schema bewerken (eigen item)                 | Formulier met huidige waarden, succesvol bijgewerkt    | Ja       |
| TC-D02 | Evenement bewerken (eigen item)              | Formulier met huidige waarden, succesvol bijgewerkt    | Ja       |
| TC-D03 | Vriend bewerken (eigen item)                 | Succesvol bijgewerkt                                   | Ja       |
| TC-D04 | Favoriet bewerken (eigen item)               | Succesvol bijgewerkt                                   | Ja       |
| TC-D05 | Item verwijderen met bevestiging             | confirm() pop-up, daarna soft delete                   | Ja       |
| TC-D06 | Verwijderen annuleren in confirm()           | Niets gebeurd, item nog aanwezig                       | Ja       |
| TC-D07 | URL manipulatie: ander gebruiker-ID meegeven | Foutmelding: geen toestemming (eigenaarschap controle) | Ja       |

### 12.9 Testcases: Beveiliging

| Test   | Actie                                                      | Verwacht resultaat                              | Geslaagd |
| ------ | ---------------------------------------------------------- | ----------------------------------------------- | -------- |
| TC-B01 | index.php openen zonder inloggen                           | Redirect naar login.php                         | Ja       |
| TC-B02 | 30+ minuten wachten na inloggen                            | Sessie verlopen, redirect naar login.php        | Ja       |
| TC-B03 | SQL-injectie proberen in e-mail veld: `' OR 1=1 --`        | Geen effect, prepared statements blokkeren dit  | Ja       |
| TC-B04 | XSS proberen in naamveld: `<script>alert('hack')</script>` | Script wordt getoond als tekst, niet uitgevoerd | Ja       |
| TC-B05 | delete.php?type=schedule&id=999 (niet-bestaand item)       | Foutmelding: geen toestemming                   | Ja       |

### 12.10 Testcases: Responsief ontwerp

| Test    | Schermgrootte          | Verwacht resultaat                                 | Geslaagd |
| ------- | ---------------------- | -------------------------------------------------- | -------- |
| TC-RD01 | Desktop (> 992px)      | Volledige navigatie zichtbaar, tabellen breed      | Ja       |
| TC-RD02 | Tablet (768px - 992px) | Hamburger menu, tabellen scrollbaar                | Ja       |
| TC-RD03 | Mobiel (< 768px)       | Hamburger menu, knoppen full-width, leesbare tekst | Ja       |
| TC-RD04 | Klein mobiel (< 480px) | Alles past op scherm, footer leesbaar              | Ja       |

### 12.11 Testresultaten samenvatting

| Categorie            | Aantal tests | Geslaagd | Gezakt | Percentage |
| -------------------- | ------------ | -------- | ------ | ---------- |
| Registratie          | 7            | 7        | 0      | 100%       |
| Inloggen             | 5            | 5        | 0      | 100%       |
| Schema toevoegen     | 8            | 8        | 0      | 100%       |
| Evenement toevoegen  | 7            | 7        | 0      | 100%       |
| Vriend toevoegen     | 5            | 5        | 0      | 100%       |
| Favoriet spel        | 4            | 4        | 0      | 100%       |
| Bewerken/verwijderen | 7            | 7        | 0      | 100%       |
| Beveiliging          | 5            | 5        | 0      | 100%       |
| Responsief ontwerp   | 4            | 4        | 0      | 100%       |
| **TOTAAL**           | **52**       | **52**   | **0**  | **100%**   |

---

## 13. Verbeteren (K1-W5)

### 13.1 Gevonden fouten en verbeteringen

Tijdens het testen en reviewen van de code zijn de volgende fouten gevonden en verbeterd:

| Nr        | Fout / Probleem                              | Hoe gevonden                      | Oplossing                                                      | Bestand                  |
| --------- | -------------------------------------------- | --------------------------------- | -------------------------------------------------------------- | ------------------------ |
| Bug #1001 | Velden accepteerden alleen spaties           | Handmatig testen                  | Regex `^\s*$` controle toegevoegd                              | functions.php, script.js |
| Bug #1004 | Ongeldige datums werden geaccepteerd         | Handmatig testen met "2025-13-45" | `DateTime::createFromFormat()` met strikte controle            | functions.php, script.js |
| Bug #1005 | CSS kaarten hadden oranje achtergrond        | Visuele inspectie                 | `--glass-bg` van `orange` naar `rgba(255,255,255,0.05)`        | style.css                |
| Bug #1006 | Sessie-ID werd bij elk verzoek geregenereerd | Code review                       | `session_regenerate_id()` verplaatst naar alleen `loginUser()` | functions.php            |

### 13.2 Verbeterproces per bug

#### Bug #1001: Alleen-spaties validatie

```
STAP 1: PROBLEEM ONTDEKT
   -> Bij het testen van het registratieformulier bleek dat
      een gebruikersnaam met alleen spaties " " werd geaccepteerd.

STAP 2: OORZAAK GEVONDEN
   -> De validateRequired() functie gebruikte alleen empty() controle.
   -> empty(" ") retourneert false in PHP, waardoor spaties doorgelaten werden.

STAP 3: OPLOSSING BEDACHT
   -> Voeg trim() toe om witruimte te verwijderen.
   -> Voeg regex /^\s*$/ toe als extra controle.

STAP 4: OPLOSSING GEIMPLEMENTEERD
   -> functions.php: validateRequired() aangepast met trim() en regex.
   -> script.js: Alle validatiefuncties aangepast met /^\s*$/.test() controle.

STAP 5: OPNIEUW GETEST
   -> Test TC-R03: Gebruikersnaam "   " -> Foutmelding verschijnt. GESLAAGD.
   -> Test TC-S03: Speltitel "   " -> Foutmelding verschijnt. GESLAAGD.
```

#### Bug #1004: Strenge datumvalidatie

```
STAP 1: PROBLEEM ONTDEKT
   -> Bij het testen bleek dat datum "2025-13-45" werd geaccepteerd.
   -> Er bestaan geen 13 maanden en niet 45 dagen.

STAP 2: OORZAAK GEVONDEN
   -> De datum werd alleen gecontroleerd met een regex patroon.
   -> Het regex patroon controleerde alleen het formaat JJJJ-MM-DD.
   -> Het controleerde NIET of de datum daadwerkelijk bestaat.

STAP 3: OPLOSSING BEDACHT
   -> Gebruik DateTime::createFromFormat() om de datum te ontleden.
   -> Vergelijk de geformatteerde datum terug met de invoer.
   -> Als ze niet exact overeenkomen, is de datum ongeldig.

STAP 4: OPLOSSING GEIMPLEMENTEERD
   -> functions.php: validateDate() herschreven met DateTime object.
   -> script.js: Datum validatie versterkt met new Date() + isNaN() controle.

STAP 5: OPNIEUW GETEST
   -> Test TC-S05: Datum "2025-13-45" -> Foutmelding. GESLAAGD.
   -> Test TC-S04: Datum gisteren -> Foutmelding. GESLAAGD.
   -> Test TC-S01: Datum morgen -> Geaccepteerd. GESLAAGD.
```

#### Bug #1005: CSS glassmorphism-achtergrondkleur

```
STAP 1: PROBLEEM ONTDEKT
   -> Bij visuele inspectie bleken alle kaarten en formulieren
      een oranje achtergrondkleur te hebben.
   -> Dit paste niet bij het donkere gaming-thema.

STAP 2: OORZAAK GEVONDEN
   -> In style.css stond de CSS-variabele --glass-bg ingesteld op "orange".
   -> Dit had een semi-transparante waarde moeten zijn voor glassmorphism.

STAP 3: OPLOSSING GEIMPLEMENTEERD
   -> --glass-bg gewijzigd van "orange" naar "rgba(255, 255, 255, 0.05)".
   -> Dit geeft een subtiel semi-transparant wit effect op de donkere achtergrond.

STAP 4: OPNIEUW GETEST
   -> Alle pagina's gecontroleerd: kaarten tonen nu correct glassmorphism-effect.
   -> Login, register, dashboard, profiel -> GESLAAGD.
```

#### Bug #1006: Sessie-ID regeneratie op elke paginalaad

```
STAP 1: PROBLEEM ONTDEKT
   -> Bij code review bleek session_regenerate_id(true) in het
      sessie-startblok te staan, niet alleen bij login.

STAP 2: OORZAAK GEVONDEN
   -> De functie stond op regel 36 in functions.php.
   -> functions.php wordt bij ELKE pagina geladen.
   -> Dus bij elk verzoek kreeg de gebruiker een nieuw sessie-ID.
   -> Dit kon problemen veroorzaken met meerdere gelijktijdige verzoeken.

STAP 3: OPLOSSING GEIMPLEMENTEERD
   -> session_regenerate_id(true) verwijderd uit het sessie-startblok.
   -> Het wordt nu ALLEEN aangeroepen in loginUser() (regel 311).
   -> Dit is de correcte plek: alleen na succesvolle authenticatie.

STAP 4: OPNIEUW GETEST
   -> Inloggen werkt correct. Sessie-ID wordt vernieuwd bij login.
   -> Navigatie tussen pagina's werkt stabiel zonder sessie-verlies.
   -> GESLAAGD.
```

### 13.3 Mogelijke toekomstige verbeteringen

| Nr  | Verbetering                   | Beschrijving                                  | Prioriteit |
| --- | ----------------------------- | --------------------------------------------- | ---------- |
| V1  | Wachtwoord vergeten functie   | E-mail met reset-link sturen                  | Hoog       |
| V2  | Profielfoto uploaden          | Gebruikers kunnen een avatar uploaden         | Gemiddeld  |
| V3  | Echte real-time vriendenlijst | WebSocket verbinding voor live status updates | Laag       |
| V4  | Zoekfunctie                   | Zoeken in schema's, evenementen en vrienden   | Gemiddeld  |
| V5  | Meerdere talen                | Volledig taalwisselaar (NL/EN)                | Laag       |
| V6  | E-mail notificaties           | Herinnering per e-mail versturen              | Gemiddeld  |
| V7  | Exportfunctie                 | Schema's exporteren naar iCal/Google Calendar | Laag       |

---

## 14. Examenpresentatie Hulp

### 14.1 Kerntaak-dekking

Dit overzicht toont hoe dit project alle kerntaken van het examen dekt:

| Kerntaak              | Onderdeel                           | Waar gedocumenteerd    | Bewijsmateriaal                  |
| --------------------- | ----------------------------------- | ---------------------- | -------------------------------- |
| **K1-W1 Planning**    | Projectplanning en aanpak           | PvA document (PDF)     | Tijdsplanning, user stories      |
| **K1-W2 Ontwerp**     | Functioneel en technisch ontwerp    | FO/TO documenten (PDF) | Database ontwerp, wireframes     |
| **K1-W3 Realisatie**  | Code schrijven en implementeren     | README sectie 1-11     | Alle PHP, JS, CSS, SQL bestanden |
| **K1-W4 Testen**      | Testcases uitvoeren en documenteren | README sectie 12       | 52 testcases, 100% geslaagd      |
| **K1-W5 Verbeteren**  | Fouten vinden en oplossen           | README sectie 13       | 4 bugs gevonden en gefixt        |
| **K2-W1 Overleggen**  | Communicatie over het project       | Overlegverslagen (PDF) | Bijeenkomsten, feedback          |
| **K2-W2 Presenteren** | Het project uitleggen               | README + deze sectie   | Demonstratie, uitleg             |
| **K2-W3 Reflectie**   | Terugkijken op het proces           | Reflectieverslag (PDF) | Wat ging goed/fout               |

### 14.2 Belangrijkste punten om uit te leggen aan de examinator

#### Punt 1: Architectuur (hoe de code is opgebouwd)

"Mijn applicatie gebruikt **Separation of Concerns**. Dat betekent dat elke laag een eigen verantwoordelijkheid heeft:

- `db.php` handelt ALLEEN de databaseverbinding af
- `functions.php` bevat ALLE logica (validatie, authenticatie, CRUD)
- De PHP-pagina's (zoals `login.php`) handelen ALLEEN de formulieren en HTML af
- `script.js` doet ALLEEN de client-side validatie
- `style.css` doet ALLEEN de visuele styling"

#### Punt 2: Dubbele validatie (client-side EN server-side)

"Ik valideer invoer op TWEE plekken:

1. **Client-side** (JavaScript): Snelle feedback aan de gebruiker voordat het formulier verzonden wordt
2. **Server-side** (PHP): Echte validatie in `functions.php` die NIET omzeild kan worden

De client-side validatie is voor gebruiksgemak. De server-side validatie is voor veiligheid. Een gebruiker kan JavaScript uitschakelen, maar kan de server-validatie NOOIT omzeilen."

#### Punt 3: Beveiliging (hoe de applicatie beschermd is)

"Mijn applicatie is beschermd tegen de belangrijkste aanvallen:

- **SQL-injectie**: Ik gebruik PDO prepared statements. De database-engine scheidt de data van de query.
- **XSS**: Ik gebruik `safeEcho()` die `htmlspecialchars()` aanroept. Dit converteert `<script>` naar `&lt;script&gt;`.
- **Wachtwoord diefstal**: Ik gebruik `password_hash()` met bcrypt. Zelfs als de database gestolen wordt, zijn wachtwoorden onleesbaar.
- **Sessie-hijacking**: Ik regenereer het sessie-ID na inloggen met `session_regenerate_id(true)`.
- **Onbeheerde sessies**: Na 30 minuten inactiviteit wordt de sessie automatisch vernietigd."

#### Punt 4: Database-ontwerp (hoe de data is gestructureerd)

"De database heeft 6 tabellen:

- `Users` is de hoofdtabel waar alle andere tabellen naar verwijzen
- `UserGames` is een **koppeltabel** (veel-op-veel relatie) tussen Users en Games
- Alle tabellen hebben `deleted_at` voor **soft delete**: data wordt nooit echt verwijderd
- Ik gebruik **foreign keys met CASCADE**: als een gebruiker verwijderd wordt, worden automatisch al hun vrienden, schema's en evenementen ook verwijderd
- Ik heb **indexen** toegevoegd op veelgebruikte kolommen voor snellere queries"

#### Punt 5: Bugfixes (hoe ik fouten heb gevonden en opgelost)

"Ik heb 4 bugs gevonden en opgelost:

1. **Bug #1001**: Velden accepteerden alleen spaties. Opgelost met regex controle.
2. **Bug #1004**: Ongeldige datums werden geaccepteerd. Opgelost met DateTime strikte validatie.
3. **Bug #1005**: Kaarten hadden een oranje achtergrond. Opgelost door CSS-variabele te corrigeren.
4. **Bug #1006**: Sessie-ID werd te vaak vernieuwd. Opgelost door het alleen bij login te doen.

Voor elke bug heb ik het proces gevolgd: **ontdekken -> oorzaak vinden -> oplossen -> opnieuw testen**."

### 14.3 Veelgestelde examenvragen en antwoorden

**V: "Waarom heb je PHP gekozen en niet een ander framework?"**
A: "PHP is de taal die we geleerd hebben in de opleiding. Het werkt goed met XAMPP als lokale omgeving en met MySQL als database. Voor een MBO-project is vanilla PHP geschikt omdat het de basis laat zien zonder afhankelijkheid van frameworks."

**V: "Hoe voorkom je SQL-injectie in jouw applicatie?"**
A: "Ik gebruik PDO prepared statements. In plaats van de gebruikersinvoer direct in de query te plaatsen, gebruik ik `:named` parameters. De database-engine verwerkt de invoer apart van de query, waardoor kwaadaardige SQL-code nooit uitgevoerd wordt. Voorbeeld: `$stmt->execute(['email' => $email]);`"

**V: "Wat is het verschil tussen client-side en server-side validatie?"**
A: "Client-side validatie draait in de browser met JavaScript. Het geeft snelle feedback, maar kan uitgeschakeld worden. Server-side validatie draait op de server met PHP. Dit kan NIET omzeild worden. Ik gebruik BEIDE: JavaScript voor gebruiksgemak, PHP voor veiligheid."

**V: "Wat is soft delete en waarom gebruik je het?"**
A: "Bij soft delete markeer ik een record als verwijderd door `deleted_at` op de huidige datum te zetten. De data blijft in de database staan. Alle queries filteren op `WHERE deleted_at IS NULL` om verwijderde items te verbergen. Het voordeel is dat data hersteld kan worden als iemand per ongeluk iets verwijdert."

**V: "Hoe werkt de sessie timeout?"**
A: "Na inloggen wordt `$_SESSION['last_activity']` opgeslagen met de huidige tijd. Bij elk paginaverzoek controleert `checkSessionTimeout()` of het verschil met de huidige tijd groter is dan 1800 seconden (30 minuten). Als dat zo is, wordt de sessie vernietigd en de gebruiker naar de loginpagina gestuurd."

**V: "Wat is het Singleton-patroon in je database-verbinding?"**
A: "Het Singleton-patroon zorgt ervoor dat er slechts een databaseverbinding wordt aangemaakt, ongeacht hoe vaak `getDBConnection()` wordt aangeroepen. De eerste keer maakt het een PDO-object aan en slaat het op in een statische variabele. Bij volgende aanroepen retourneert het dezelfde verbinding. Dit bespaart geheugen en voorkomt te veel open verbindingen."

**V: "Hoe heb je de applicatie getest?"**
A: "Ik heb 52 handmatige testcases uitgevoerd: 7 voor registratie, 5 voor login, 8 voor schema's, 7 voor evenementen, 5 voor vrienden, 4 voor favorieten, 7 voor bewerken/verwijderen, 5 voor beveiliging, en 4 voor responsief ontwerp. Alle 52 tests zijn geslaagd (100%)."

**V: "Wat zou je anders doen als je opnieuw zou beginnen?"**
A: "Ik zou eerder beginnen met testen en een gestructureerder testplan opzetten. Ook zou ik vanaf het begin een CSS-framework configureren voor het glassmorphism-thema, in plaats van achteraf variabelen te moeten corrigeren. Verder zou ik overwegen om een MVC-structuur te gebruiken voor betere scheiding van logica en presentatie."

---

## 15. Onderlegger C24 – Examen Checklistvragen (Crebo 25998)

> **Student:** Harsha Vardhan Kanaparthi | **Studentnummer:** 2195344
> **Opleiding:** MBO-4 Software Development (Crebo 25998)
> **Project:** GamePlan Scheduler – Gaming Planning Webapp
> **Stagebedrijf:** [zie PDF-documenten]
> **Datum:** 2025

Dit hoofdstuk beantwoordt alle examencriteria per werkproces (K1-W1 t/m K2-W3) met concrete verwijzingen naar de code, README-secties en PDF-documenten. De examinator kan dit hoofdstuk gebruiken als afvink-index om snel alle bewijsstukken terug te vinden.

---

### 15.1 K1-W1: Oriënteren / Planning

#### Criterium 1: De student beschrijft de opdrachtgever, context en aanleiding van het project

**Antwoord:**
GamePlan Scheduler is ontwikkeld als individueel stageproject voor de MBO-4 opleiding Software Development. De aanleiding is dat gamers vaak moeite hebben om gaming-sessies te coördineren met vrienden. Er bestaan wel agenda-apps, maar geen specifieke tool gericht op het plannen van gaming-sessies met functies als vriendenlijsten, favoriete spellen en gedeelde schema's. Het project lost dit probleem op met een webapplicatie speciaal ontworpen voor de gaming-community.

**Bewijs:** Zie README sectie 1 (Projectbeschrijving) en PDF `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student beschrijft het doel van het project en de doelgroep

**Antwoord:**
Het doel is een webapplicatie bouwen waarmee gamers hun gaming-leven kunnen organiseren. De doelgroep bestaat uit gamers van alle leeftijden die regelmatig online spelen en hun sessies willen plannen. Specifieke doelen:

- Gebruikers kunnen een account aanmaken en inloggen (authenticatie)
- Gebruikers kunnen een vriendenlijst beheren met status (Online/Offline/In-game)
- Gebruikers kunnen favoriete spellen bijhouden met notities
- Gebruikers kunnen gaming-schema's aanmaken met datum, tijd en vrienden
- Gebruikers kunnen evenementen plannen met herinneringen en externe links
- Gebruikers krijgen een overzichtelijk dashboard met kalender en herinneringen

**Bewijs:** Zie README sectie 1 en sectie 6 (Functionele flows voor alle 10 operaties).

#### Criterium 3: De student beschrijft de functionele eisen (user stories / features)

**Antwoord:**
De applicatie bevat 10 hoofdfunctionaliteiten, elk volledig CRUD (Create, Read, Update, Delete):

| Nr  | Functionele eis                                      | Status       |
| --- | ---------------------------------------------------- | ------------ |
| F1  | Registreren met gebruikersnaam, e-mail en wachtwoord | Gerealiseerd |
| F2  | Inloggen met e-mail en wachtwoord                    | Gerealiseerd |
| F3  | Uitloggen met sessie-vernietiging                    | Gerealiseerd |
| F4  | Favoriete spellen toevoegen/bewerken/verwijderen     | Gerealiseerd |
| F5  | Vriendenlijst beheren met status                     | Gerealiseerd |
| F6  | Gaming-schema's aanmaken met datum/tijd/vrienden     | Gerealiseerd |
| F7  | Evenementen plannen met herinneringen                | Gerealiseerd |
| F8  | Dashboard met kalenderoverzicht                      | Gerealiseerd |
| F9  | Profiel bekijken met statistieken                    | Gerealiseerd |
| F10 | Alle items sorteren op datum/titel/status            | Gerealiseerd |

**Bewijs:** Zie README sectie 6 (Functionele Flows), PDF `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf` en demovideo `K1-W3-DEMO VIDEO.mp4`.

#### Criterium 4: De student beschrijft de technische keuzes met onderbouwing

**Antwoord:**

| Technologie       | Keuze                  | Onderbouwing                                                                                         |
| ----------------- | ---------------------- | ---------------------------------------------------------------------------------------------------- |
| Backend           | PHP 7.4+ (vanilla)     | Geleerd in de opleiding, geen framework nodig voor dit projectniveau, toont basisvaardigheden        |
| Database          | MySQL 8.0 met InnoDB   | Relationele database geschikt voor gestructureerde data, InnoDB voor foreign keys en transacties     |
| Database-toegang  | PDO met prepared stmts | Veiligste methode tegen SQL-injectie, ondersteunt named parameters, database-onafhankelijk           |
| Frontend CSS      | Bootstrap 5.3.3        | Snel responsive design, grote componentenbibliotheek, goed gedocumenteerd                            |
| Frontend JS       | Vanilla JavaScript     | Geen externe afhankelijkheden nodig, toont basisvaardighed in DOM-manipulatie                        |
| Ontwikkelomgeving | XAMPP                  | Alles-in-één pakket (Apache + MySQL + PHP), eenvoudig op te zetten, industrie-standaard voor leren   |
| Versiebeheer      | Git + GitHub           | Industriestandaard, maakt samenwerking mogelijk, toont professionele werkwijze                       |
| Ontwerp-thema     | Dark + Glassmorphism   | Past bij de gaming-doelgroep, moderne uitstraling, onderscheidt zich van standaard Bootstrap-thema's |

**Bewijs:** Zie README sectie 3 (Technische specificaties).

#### Criterium 5: De student heeft een planning gemaakt met taken, uren en deadlines

**Antwoord:**
Er is een gedetailleerde planning gemaakt voorafgaand aan het project. De planning bevat:

- **Faseverdeling:** Oriëntatie → Ontwerp → Realisatie → Testen → Verbeteren → Opleveren
- **Taken per fase:** Elke fase is opgedeeld in concrete, afrekenbare taken
- **Ureninschatting:** Per taak is een tijdsinschatting gemaakt
- **Deadlines:** Per fase is een einddatum bepaald
- **Projectlog:** Dagelijks bijgehouden met daadwerkelijk bestede uren

De totale geplande en gerealiseerde uren overschrijden de minimale 40 uur ruimschoots, gezien de omvang van de applicatie (22+ bronbestanden, 669 regels in `functions.php`, 437 regels in `script.js`, 665 regels in `style.css`, 503 regels in `database.sql`).

**Bewijs:** PDF `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf` (planning) en PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf` (projectlog met uren).

#### Criterium 6: De student beschrijft wijzigingen op de oorspronkelijke planning

**Antwoord:**
Tijdens het project zijn de volgende wijzigingen doorgevoerd ten opzichte van de oorspronkelijke planning:

1. **Kalenderweergave:** Oorspronkelijk gepland als losse pagina, uiteindelijk geïntegreerd in het dashboard voor een beter overzicht
2. **Herinneringsfunctie:** Niet in de oorspronkelijke planning, later toegevoegd na feedback van de stagebegeleider
3. **Glassmorphism-thema:** Het oorspronkelijke ontwerp gebruikte standaard Bootstrap-kleuren; na de eerste iteratie is een custom gaming-thema ontwikkeld
4. **Soft delete:** Oorspronkelijk was harde delete (fysiek verwijderen) gepland; na overleg met de begeleider is gekozen voor soft delete (logisch verwijderen) voor betere dataveiligheid

Elke wijziging is gedocumenteerd in de projectlog.

**Bewijs:** PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 7: De student heeft overleg gevoerd met opdrachtgever/begeleider

**Antwoord:**
Er zijn meerdere overlegmomenten geweest met de stagebegeleider. Tijdens deze overleggen zijn de volgende onderwerpen besproken:

- Projectopzet en scope-afbakening
- Technische keuzes (PHP vs. framework)
- Databaseontwerp (normalisatie, relaties)
- Voortgangsbesprekingen per fase
- Feedback op code-kwaliteit en beveiliging
- Afronding en oplevering

De overlegverslagen en feedback zijn vastgelegd in aparte PDF-documenten.

**Bewijs:** PDF `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf` en PDF `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`.

---

### 15.2 K1-W2: Ontwerpen

#### Criterium 1: De student heeft een functioneel ontwerp (FO) opgesteld

**Antwoord:**
Het functioneel ontwerp beschrijft WAT de applicatie doet vanuit gebruikersperspectief. Het FO bevat:

- **10 functionele flows** (stap-voor-stap beschrijvingen van elke gebruikersactie):
  1. Registratie → validatie → account aanmaken → redirect naar login
  2. Inloggen → verificatie → sessie aanmaken → redirect naar dashboard
  3. Favoriete spellen beheren → toevoegen/bewerken/verwijderen
  4. Vriendenlijst beheren → toevoegen/bewerken/verwijderen met status
  5. Gaming-schema's beheren → datum/tijd/vrienden selecteren
  6. Evenementen beheren → met herinneringen en externe links
  7. Dashboard → kalenderoverzicht + herinneringen
  8. Profiel → statistieken en accountinformatie
  9. Sorteren → schema's en evenementen op datum/titel
  10. Verwijderen → bevestigingsdialoog + soft delete

- **Alle invoervelden per formulier** met verwachte datatypes en beperkingen
- **Foutscenario's** per functie (wat gebeurt er bij ongeldige invoer?)

**Bewijs:** README sectie 6 (alle 10 Functionele Flows met stap-voor-stap beschrijvingen), PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft een technisch ontwerp (TO) opgesteld

**Antwoord:**
Het technisch ontwerp beschrijft HOE de applicatie werkt vanuit technisch perspectief. Het TO bevat:

- **Database-ontwerp:** 6 genormaliseerde tabellen met relaties (zie sectie 4)
  - `Users` (hoofdtabel, PK: user_id)
  - `Games` (spellencatalogus, PK: game_id)
  - `UserGames` (koppeltabel veel-op-veel, FK naar Users + Games)
  - `Friends` (vriendenlijst, FK naar Users)
  - `Schedules` (gaming-schema's, FK naar Users)
  - `Events` (evenementen, FK naar Users)

- **Architectuur:** Separation of Concerns met 4 lagen:
  - Datalaag (`db.php` – PDO Singleton verbinding)
  - Logicalaag (`functions.php` – alle validatie, authenticatie, CRUD)
  - Presentatielaag (PHP-pagina's + `header.php`/`footer.php`)
  - Client-laag (`script.js` + `style.css`)

- **Beveiligingsarchitectuur:** 10 maatregelen (B1-B10) beschreven in sectie 8
- **Validatiearchitectuur:** 18 validatieregels met pseudocode-algoritmen in sectie 5
- **API/routes:** Overzicht van alle PHP-paginas en hun verantwoordelijkheden

**Bewijs:** README secties 3, 4, 5, 7, 8 en PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 3: De student heeft minimaal 3 schematechnieken toegepast

**Antwoord:**
De volgende schematechnieken zijn toegepast:

**Schematechniek 1: Entity-Relationship Diagram (ERD)**
Het databaseontwerp is gedocumenteerd met een ERD dat de 6 tabellen en hun relaties toont:

- Users `1 ──── N` Friends (een gebruiker heeft meerdere vrienden)
- Users `1 ──── N` Schedules (een gebruiker heeft meerdere schema's)
- Users `1 ──── N` Events (een gebruiker heeft meerdere evenementen)
- Users `N ──── M` Games via UserGames (veel-op-veel koppeltabel)
- Elke tabel met alle kolommen, datatypes, primary keys en foreign keys

**Bewijs:** README sectie 4 (Database Structuur) met volledige tabel-beschrijvingen, kolommen, datatypes en relaties. PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

**Schematechniek 2: Flowcharts / Code Flow Diagrams**
Gedetailleerde sequentiediagrammen die de interactie tussen Browser, Server en Database tonen:

- **Login Code Flow** (README sectie 7.1): Browser → login.php → loginUser() → SELECT/password_verify → sessie aanmaken → redirect
- **Dashboard Code Flow** (README sectie 7.2): Browser → index.php → 5 parallelle queries → HTML renderen
- **Delete Code Flow** (README sectie 7.3): Browser → confirm() → delete.php → checkOwnership() → soft delete → redirect

Elk diagram toont de exacte functieaanroepen, database-queries, beslispunten (ALS/ANDERS) en responsen.

**Bewijs:** README sectie 7 (Code Flow Diagrammen) met drie volledige ASCII-diagrammen.

**Schematechniek 3: Activiteitendiagrammen (Functionele Flows)**
Voor alle 10 hoofdfunctionaliteiten zijn stap-voor-stap activiteitendiagrammen gemaakt die het pad van de gebruiker beschrijven:

- Startpunt → Invoer → Validatie → Beslispunt (geldig/ongeldig) → Database-actie → Resultaat → Eindpunt
- Inclusief de alternatieve paden (foutmeldingen, redirects, edge cases)

**Bewijs:** README sectie 6 (Functionele Flows) met alle 10 flows beschreven in genummerde stappen.

**Schematechniek 4: Validatie-algoritmen in Pseudocode (Nassi-Shneiderman-stijl)**
Alle 18 validatieregels zijn beschreven als pseudocode-algoritmen met duidelijke invoer, stappen en uitvoer:

```
FUNCTIE validateDate(datum):
    INVOER: datum (string)
    STAP 1: Controleer formaat JJJJ-MM-DD met regex
    STAP 2: Ontleed met DateTime::createFromFormat('Y-m-d')
    STAP 3: Vergelijk geformatteerde output met invoer
    STAP 4: Vergelijk met vandaag (moet >= vandaag zijn)
    UITVOER: null (geldig) OF foutmelding (string)
```

**Bewijs:** README sectie 5 (Validaties) met 18 algoritmen (V1-V18) in pseudocode.

#### Criterium 4: De student motiveert zijn ontwerpkeuzes

**Antwoord:**

| Ontwerpkeuze                  | Motivatie                                                                                                                                              |
| ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| PDO Singleton patroon         | Voorkomt meerdere databaseverbindingen per pagina-verzoek. Bespaart geheugen en voorkomt connection-pool uitputting. Één verbinding wordt hergebruikt. |
| Soft delete (deleted_at)      | Data wordt nooit fysiek verwijderd. Dit biedt herstelmogelijkheid bij ongelukken en is compliant met data-retentie best practices.                     |
| Geen PHP-framework            | Voor een MBO-4 project is vanilla PHP geschikter: het toont dat de student de basismechanismen begrijpt zonder framework-"magie" te verbergen.         |
| Dubbele validatie (JS + PHP)  | Client-side voor snelle gebruikerservaring; server-side voor veiligheid. JavaScript kan worden uitgeschakeld, PHP-validatie is onomzeilbaar.           |
| Bootstrap 5.3.3               | Snelle responsive ontwikkeling met uitgebreide componentenbibliotheek. Geen jQuery-afhankelijkheid meer (Bootstrap 5 is jQuery-vrij).                  |
| Glassmorphism CSS-thema       | Past bij de gaming-doelgroep: modern, donker, visueel aantrekkelijk. Onderscheidt het project van standaard portfoliowerk.                             |
| bcrypt wachtwoord-hashing     | Industriestandaard voor wachtwoordopslag. Traag algoritme dat brute-force aanvallen moeilijk maakt. Ingebouwd in PHP via `password_hash()`.            |
| Foreign keys met CASCADE      | Referentiële integriteit op databaseniveau. Bij verwijdering van een gebruiker worden automatisch alle gerelateerde records opgeruimd.                 |
| Sessiebericht-systeem (flash) | Berichten overleven een redirect (POST-Redirect-GET patroon). Voorkomt dubbele formulierinzendingen bij het verversen van de pagina.                   |
| Aparte header.php/footer.php  | DRY-principe: navigatie en footer worden op 1 plek beheerd. Wijziging geldt automatisch voor alle pagina's.                                            |

**Bewijs:** README secties 3, 4, 8 en PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 5: De student heeft wireframes of mockups gemaakt

**Antwoord:**
Er zijn screenshots gemaakt van alle 12 pagina's van de applicatie. Deze tonen de uiteindelijke visuele uitwerking van het ontwerp:

| Screenshot                 | Pagina                         | Locatie                      |
| -------------------------- | ------------------------------ | ---------------------------- |
| InlogPagina.png            | Inlogformulier                 | `Demo Fotos/Software Fotos/` |
| Account Aanmaak Pagina.png | Registratieformulier           | `Demo Fotos/Software Fotos/` |
| HomePagina.png             | Dashboard/overzicht            | `Demo Fotos/Software Fotos/` |
| Profile Add Pagina.png     | Profiel met favoriet toevoegen | `Demo Fotos/Software Fotos/` |
| Profile Edit Pagina.png    | Profiel bewerken               | `Demo Fotos/Software Fotos/` |
| Add Friend Pagina.png      | Vriend toevoegen formulier     | `Demo Fotos/Software Fotos/` |
| Edit Friend Pagina.png     | Vriend bewerken formulier      | `Demo Fotos/Software Fotos/` |
| Schedule Add Pagina.png    | Schema toevoegen formulier     | `Demo Fotos/Software Fotos/` |
| Edit Schedule Pagina.png   | Schema bewerken formulier      | `Demo Fotos/Software Fotos/` |
| Events Add Pagina.png      | Evenement toevoegen formulier  | `Demo Fotos/Software Fotos/` |
| Events Edit Pagina.png     | Evenement bewerken formulier   | `Demo Fotos/Software Fotos/` |
| Delete Button.png          | Verwijderbevestiging           | `Demo Fotos/Software Fotos/` |

**Bewijs:** Map `Demo Fotos/Software Fotos/` (12 PNG-screenshots).

---

### 15.3 K1-W3: Realiseren

#### Criterium 1: De student heeft een werkende applicatie opgeleverd conform het ontwerp

**Antwoord:**
De GamePlan Scheduler is een volledig werkende webapplicatie met alle 10 geplande functionaliteiten. Elke functionaliteit is geïmplementeerd, getest en gedocumenteerd. De applicatie draait op XAMPP (Apache + MySQL) en is bereikbaar via `http://localhost/gameplan-scheduler/`.

**Overzicht van gerealiseerde functionaliteiten:**

| Functionaliteit           | PHP-bestand(en)                                 | Functies in functions.php                                     | Status  |
| ------------------------- | ----------------------------------------------- | ------------------------------------------------------------- | ------- |
| Registreren               | register.php                                    | registerUser()                                                | Werkend |
| Inloggen                  | login.php                                       | loginUser()                                                   | Werkend |
| Uitloggen                 | logout.php                                      | logout()                                                      | Werkend |
| Favoriete spellen beheren | edit_favorite.php, delete.php                   | addFavoriteGame(), updateFavoriteGame(), deleteFavoriteGame() | Werkend |
| Vriendenlijst beheren     | add_friend.php, edit_friend.php, delete.php     | addFriend(), updateFriend(), deleteFriend()                   | Werkend |
| Gaming-schema's beheren   | add_schedule.php, edit_schedule.php, delete.php | addSchedule(), editSchedule(), deleteSchedule()               | Werkend |
| Evenementen beheren       | add_event.php, edit_event.php, delete.php       | addEvent(), editEvent(), deleteEvent()                        | Werkend |
| Dashboard met kalender    | index.php                                       | getCalendarItems(), getReminders()                            | Werkend |
| Profiel met statistieken  | profile.php                                     | getFavoriteGames(), getFriends()                              | Werkend |
| Sorteren                  | index.php                                       | getSchedules($sort), getEvents($sort)                         | Werkend |

**Bewijs:** Alle bronbestanden in de repository, README secties 6-7 (functionele flows + code flows), demovideo `K1-W3-DEMO VIDEO.mp4`, PDF `K1 W3 Realisatie-Realisatie verslag-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft minimaal 40 uur aan het project gewerkt

**Antwoord:**
De totale omvang van het project bevestigt dat er ruimschoots meer dan 40 uur aan is gewerkt:

| Onderdeel       | Omvang                       | Geschatte uren |
| --------------- | ---------------------------- | -------------- |
| functions.php   | 669 regels code              | 15+ uur        |
| script.js       | 437 regels code              | 8+ uur         |
| style.css       | 665 regels CSS               | 10+ uur        |
| database.sql    | 503 regels met comments      | 5+ uur         |
| 15 PHP-pagina's | ±200 regels per pagina gem.  | 12+ uur        |
| README.md       | 1600+ regels documentatie    | 10+ uur        |
| Testen          | 52 testcases uitvoeren       | 5+ uur         |
| Bugfixes        | 4 bugs vinden en oplossen    | 3+ uur         |
| **Totaal**      | **3000+ regels code + docs** | **68+ uur**    |

De exacte uren zijn bijgehouden in de projectlog.

**Bewijs:** PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 3: De student past best practices toe – DRY (Don't Repeat Yourself)

**Antwoord:**
Het DRY-principe is consequent toegepast in de hele applicatie:

| DRY-toepassing                  | Waar                   | Toelichting                                                         |
| ------------------------------- | ---------------------- | ------------------------------------------------------------------- |
| `getDBConnection()` Singleton   | db.php                 | Eén databaseverbinding hergebruikt door alle functies               |
| `safeEcho()` helper             | functions.php          | Eén centrale functie voor XSS-bescherming, gebruikt op ELKE uitvoer |
| `validateRequired()` hergebruik | functions.php          | Alle verplichte-veld-controles gebruiken dezelfde functie           |
| `checkOwnership()` centraal     | functions.php          | Eén functie voor eigenaarschap-controle, niet per entiteit herhaald |
| `header.php` / `footer.php`     | header.php, footer.php | Navigatie en footer op 1 plek, automatisch op alle pagina's         |
| `setMessage()` / `getMessage()` | functions.php          | Eén berichtsysteem voor alle succes- en foutmeldingen               |
| CSS-variabelen                  | style.css              | Kleuren en waarden als variabelen, 1x wijzen geldt overal           |

**Voorbeeld:** In plaats van op elke pagina apart `htmlspecialchars()` aan te roepen:

```php
// FOUT (herhaling):
echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

// GOED (DRY via safeEcho):
echo safeEcho($username);
echo safeEcho($email);
```

#### Criterium 4: De student past best practices toe – SRP (Single Responsibility Principle)

**Antwoord:**
Elk bestand en elke functie heeft precies ÉÉN verantwoordelijkheid:

| Bestand/Functie      | Verantwoordelijkheid                          | Doet NIET                           |
| -------------------- | --------------------------------------------- | ----------------------------------- |
| `db.php`             | Alleen databaseverbinding beheren             | Geen queries, geen validatie        |
| `functions.php`      | Alleen businesslogica (validatie + CRUD)      | Geen HTML, geen routing             |
| `header.php`         | Alleen navigatie-HTML renderen                | Geen logica, geen queries           |
| `footer.php`         | Alleen footer-HTML renderen                   | Geen logica, geen queries           |
| `login.php`          | Alleen loginformulier tonen en afhandelen     | Geen registratie, geen CRUD         |
| `script.js`          | Alleen client-side validatie en UI-interactie | Geen server-communicatie, geen AJAX |
| `style.css`          | Alleen visuele styling                        | Geen logica, geen structuur         |
| `validateRequired()` | Alleen controleren of een veld niet leeg is   | Geen type-specifieke validatie      |
| `loginUser()`        | Alleen authenticatie uitvoeren                | Geen registratie, geen CRUD         |
| `checkOwnership()`   | Alleen controleren of gebruiker eigenaar is   | Geen verwijdering, geen bewerking   |

#### Criterium 5: De student hanteert correcte naamgevingsconventies

**Antwoord:**
De volgende naamgevingsconventies worden consistent aangehouden:

| Type              | Conventie        | Voorbeelden                                                            |
| ----------------- | ---------------- | ---------------------------------------------------------------------- |
| PHP-functies      | camelCase        | `addFriend()`, `getFavoriteGames()`, `checkSessionTimeout()`           |
| PHP-variabelen    | camelCase        | `$friendUsername`, `$sharedWithStr`, `$gameTitle`, `$userId`           |
| Database-tabellen | PascalCase       | `Users`, `Games`, `UserGames`, `Friends`, `Schedules`, `Events`        |
| Database-kolommen | snake_case       | `user_id`, `game_title`, `created_at`, `deleted_at`, `friend_username` |
| CSS-klassen       | kebab-case       | `.glass-card`, `.btn-gaming`, `.nav-link`, `.hero-section`             |
| CSS-variabelen    | kebab met prefix | `--gaming-primary`, `--glass-bg`, `--glass-border`                     |
| JS-functies       | camelCase        | `validateLoginForm()`, `showNotification()`, `initializeFeatures()`    |
| Bestandsnamen     | snake_case       | `add_friend.php`, `edit_schedule.php`, `edit_favorite.php`             |

#### Criterium 6: De student heeft code voorzien van commentaar

**Antwoord:**
De code is voorzien van duidelijk commentaar op meerdere niveaus:

**PHP (functions.php) – Functiebeschrijvingen:**

```php
/**
 * Valideert een verplicht veld
 * Controleert op lege waarde, alleen spaties, en maximale lengte
 */
function validateRequired($value, $fieldName, $maxLength = 255) { ... }
```

**SQL (database.sql) – Tweetalig commentaar:**

```sql
-- Users tabel: Slaat gebruikersinformatie op
-- Users table: Stores user information
CREATE TABLE Users ( ... );
```

**CSS (style.css) – Sectieheaders:**

```css
/* ============================================
   3. GLASSMORPHISME COMPONENTEN
   ============================================ */
```

**JavaScript (script.js) – Inline uitleg:**

```javascript
// Controleer of het veld alleen uit spaties bestaat
if (/^\s*$/.test(username)) { ... }
```

**Bewijs:** Alle bronbestanden bevatten commentaar. Zie `functions.php`, `database.sql` (503 regels met tweetalig commentaar), `style.css` (14 benoemde secties), `script.js`.

#### Criterium 7: De student gebruikt versiebeheer (Git)

**Antwoord:**
Het project wordt beheerd met Git en gehost op GitHub:

- **Repository:** `https://github.com/Harsha2006217/gameplan-scheduler`
- **Branch:** `main`
- **Commits:** Meerdere commits met beschrijvende berichten die de voortgang documenteren
- **Bestanden in versiebeheer:** Alle 22+ bronbestanden, database.sql, README.md, documentatie-PDFs, screenshots

De commit-geschiedenis toont de geleidelijke opbouw van het project: van initiële structuur → databaseontwerp → authenticatie → CRUD-functies → validatie → styling → testen → bugfixes → documentatie.

**Bewijs:** GitHub repository en screenshot `Demo Fotos/VersieBeheer/Versiebeheer.png`.

#### Criterium 8: De student handelt randgevallen (edge cases) correct af

**Antwoord:**
De applicatie handelt de volgende randgevallen af:

| Randgeval                              | Afhandeling                                                             | Validatie-ID |
| -------------------------------------- | ----------------------------------------------------------------------- | ------------ |
| Invoer met alleen spaties " "          | Regex `/^\s*$/` detecteert en weigert (Bug #1001 fix)                   | V1           |
| Onmogelijke datum "2025-13-45"         | `DateTime::createFromFormat()` met stricte vergelijking (Bug #1004 fix) | V3           |
| Datum in het verleden                  | Vergelijking met `date('Y-m-d')` blokkeert verlopen datums              | V3           |
| Kommagescheiden lijst met lege items   | Explode + trim + filter op lege strings                                 | V7           |
| SQL-injectie via invoervelden          | PDO prepared statements met named parameters                            | B2           |
| XSS via invoervelden                   | `safeEcho()` met htmlspecialchars op alle uitvoer                       | B3           |
| Verwijderen van andermans data via URL | `checkOwnership()` controle bij elke bewerk/verwijder-actie             | B6           |
| Sessie-verlopen na inactiviteit        | 30-minuten timeout met automatische redirect naar login                 | B5           |
| Dubbele registratie met zelfde e-mail  | UNIQUE constraint + PHP-controle retourneert foutmelding                | V10          |
| Dubbele favoriet (zelfde spel)         | PHP-controle op bestaande koppeling retourneert "Spel al in favorieten" | -            |
| Dubbele vriend (zelfde gebruikersnaam) | PHP-controle op bestaande vriendschap retourneert "Al vrienden"         | -            |
| Niet-bestaand item verwijderen         | checkOwnership() retourneert false → foutmelding                        | B6           |

**Bewijs:** README sectie 5 (alle validaties), sectie 8 (beveiligingsmaatregelen), sectie 13 (bugfixes).

#### Criterium 9: De student implementeert foutafhandeling

**Antwoord:**
Er zijn drie lagen van foutafhandeling:

**Laag 1: Functie-retourwaarden**
Alle functies retourneren `null` bij succes of een foutmelding-string bij een fout. De aanroepende code controleert dit:

```php
$error = addSchedule($userId, $title, $date, $time, $friends, $shared);
if ($error) {
    setMessage('danger', $error);  // Toon foutmelding
} else {
    setMessage('success', 'Schema toegevoegd!');
    header("Location: index.php");  // Redirect bij succes
}
```

**Laag 2: Database try-catch**
Alle database-operaties staan in try-catch blokken:

```php
try {
    $stmt = $pdo->prepare("INSERT INTO ...");
    $stmt->execute([...]);
} catch (PDOException $e) {
    error_log("Database fout: " . $e->getMessage());  // Technische log
    return "Actie mislukt. Probeer opnieuw.";          // Generieke melding
}
```

**Laag 3: Sessiebericht-systeem**
Berichten (succes én fouten) worden opgeslagen in de sessie en na redirect getoond via Bootstrap alerts. Dit implementeert het PRG-patroon (Post-Redirect-Get).

**Bewijs:** README sectie 9 (Foutafhandeling) met alle patronen en sectie 9.4 met tabel van alle foutmeldingen.

#### Criterium 10: De student implementeert dubbele validatie (client-side + server-side)

**Antwoord:**

**Client-side validatie (JavaScript – script.js):**
6 validatiefuncties die directe feedback geven VOORDAT het formulier wordt verzonden:

- `validateLoginForm()` – e-mail en wachtwoord verplicht
- `validateRegisterForm()` – gebruikersnaam, e-mail, wachtwoord (min. 8 tekens)
- `validateScheduleForm()` – speltitel, datum (toekomst), tijd (formaat)
- `validateEventForm()` – titel, datum, tijd, optionele URL-validatie

Bij een fout wordt `event.preventDefault()` aangeroepen en een visuele foutmelding getoond via Bootstrap toasts.

**Server-side validatie (PHP – functions.php):**
7 validatiefuncties die op de server draaien en NIET omzeild kunnen worden:

- `validateRequired()` – leeg + spaties + maxlengte
- `validateDate()` – formaat + echte datum + toekomst
- `validateTime()` – UU:MM formaat
- `validateEmail()` – e-mailformaat
- `validateUrl()` – URL-formaat (optioneel veld)
- `validateCommaSeparated()` – kommagescheiden lijst zonder lege items

**Waarom beide nodig?**
Een gebruiker kan JavaScript uitschakelen in de browser. Dan werkt client-side validatie niet meer. De server-side validatie vangt dit op en is de echte "poortwachter" van de data. Client-side validatie is puur voor gebruiksgemak (snellere feedback).

**Bewijs:** README sectie 5 (alle 18 validatieregels met algoritmen), `functions.php` (server-side), `script.js` (client-side).

#### Criterium 11: De student implementeert beveiligingsmaatregelen

**Antwoord:**
De applicatie bevat 10 beveiligingsmaatregelen:

| Nr  | Maatregel                | Implementatie                                  | Beschermt tegen                |
| --- | ------------------------ | ---------------------------------------------- | ------------------------------ |
| B1  | Wachtwoord hashing       | `password_hash(PASSWORD_BCRYPT)`               | Wachtwoorddiefstal             |
| B2  | Prepared statements      | PDO met `:named` parameters (30+ queries)      | SQL-injectie                   |
| B3  | Output escaping          | `safeEcho()` op alle gebruikersdata            | XSS (Cross-Site Scripting)     |
| B4  | Sessie regeneratie       | `session_regenerate_id(true)` na login         | Sessiefixatie                  |
| B5  | Sessie timeout           | 30 min inactiviteit → automatisch uitloggen    | Onbeheerde sessies             |
| B6  | Eigenaarschap controle   | `checkOwnership()` bij bewerken/verwijderen    | Ongeautoriseerde toegang       |
| B7  | Inputvalidatie           | Server-side + client-side dubbel               | Ongeldige/kwaadaardige data    |
| B8  | Foutmasking              | `error_log()` + generieke gebruikersmelding    | Informatielekken               |
| B9  | Soft delete              | `deleted_at` timestamp i.p.v. fysiek DELETE    | Dataverlies                    |
| B10 | Inlogcontrole per pagina | `isLoggedIn()` check op elke beveiligde pagina | Ongeautoriseerde paginatoegang |

**Bewijs:** README sectie 8 (Beveiligingsmaatregelen) met uitgebreide uitleg per maatregel.

---

### 15.4 K1-W4: Testen

#### Criterium 1: De student heeft een teststrategie opgesteld

**Antwoord:**
De teststrategie is gebaseerd op drie testniveaus:

1. **Functionele tests:** Elke functionaliteit (registreren, inloggen, CRUD voor alle entiteiten) is stap voor stap doorlopen met geldige invoer om te controleren dat het verwachte resultaat wordt bereikt (happy path).
2. **Validatietests:** Alle 18 validatieregels zijn getest met ongeldige invoer om te verifiëren dat foutmeldingen correct worden getoond (edge cases en foutscenario's).
3. **Beveiligingstests:** De 10 beveiligingsmaatregelen zijn getest door aanvallen te simuleren (SQL-injectie, XSS, URL-manipulatie, sessie-timeout).

Aanvullend is de responsiviteit getest op 4 schermgrootten (desktop, tablet, mobiel, klein mobiel).

**Bewijs:** README sectie 12.1 (Teststrategie) en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft testscenario's opgesteld voor alle features

**Antwoord:**
Er zijn 52 testcases opgesteld en uitgevoerd, verdeeld over 9 categorieën:

| Categorie               | Testcases        | Tests                  |
| ----------------------- | ---------------- | ---------------------- |
| Registratie             | TC-R01 t/m R07   | 7 tests (happy + edge) |
| Inloggen                | TC-L01 t/m L05   | 5 tests                |
| Gaming-schema toevoegen | TC-S01 t/m S08   | 8 tests                |
| Evenement toevoegen     | TC-E01 t/m E07   | 7 tests                |
| Vriend toevoegen        | TC-F01 t/m F05   | 5 tests                |
| Favoriet spel           | TC-G01 t/m G04   | 4 tests                |
| Bewerken/verwijderen    | TC-D01 t/m D07   | 7 tests                |
| Beveiliging             | TC-B01 t/m B05   | 5 tests                |
| Responsief ontwerp      | TC-RD01 t/m RD04 | 4 tests                |
| **Totaal**              |                  | **52 tests**           |

Elk testscenario bevat: invoer, verwacht resultaat en werkelijk resultaat (geslaagd/gezakt).

**Bewijs:** README secties 12.2 t/m 12.10 met alle 52 testcases in tabelvorm.

#### Criterium 3: De student test happy paths, edge cases en foutscenario's

**Antwoord:**

**Happy path tests (alles gaat goed):**

- TC-R01: Correct registreren → account aangemaakt ✓
- TC-L01: Correct inloggen → redirect naar dashboard ✓
- TC-S01: Correct schema toevoegen → opgeslagen ✓
- TC-E01: Correct evenement toevoegen → opgeslagen ✓
- TC-F01: Correct vriend toevoegen → opgeslagen ✓
- TC-G01: Correct favoriet spel → opgeslagen ✓

**Edge case tests (grensgevallen):**

- TC-R03: Gebruikersnaam met alleen spaties " " → foutmelding ✓ (Bug #1001)
- TC-S05: Ongeldige datum "2025-13-45" → foutmelding ✓ (Bug #1004)
- TC-S08: Kommagescheiden lijst met lege items "a,,b" → foutmelding ✓
- TC-R07: Gebruikersnaam > 50 tekens → foutmelding ✓
- TC-E04: Beschrijving > 500 tekens → foutmelding ✓

**Foutscenario tests (intentionele fouten):**

- TC-L02: Fout wachtwoord → "Ongeldige e-mail of wachtwoord" ✓
- TC-R06: Dubbele e-mail → "E-mail al geregistreerd" ✓
- TC-B03: SQL-injectie `' OR 1=1 --` → geen effect ✓
- TC-B04: XSS `<script>alert('hack')</script>` → geëscaped als tekst ✓
- TC-D07: URL-manipulatie (ander user ID) → "Geen toestemming" ✓

**Bewijs:** README secties 12.2-12.9 en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 4: De student heeft een testrapport met resultaten opgesteld

**Antwoord:**
Het testrapport toont dat alle 52 testcases zijn geslaagd:

| Categorie            | Aantal | Geslaagd | Gezakt | Score    |
| -------------------- | ------ | -------- | ------ | -------- |
| Registratie          | 7      | 7        | 0      | 100%     |
| Inloggen             | 5      | 5        | 0      | 100%     |
| Schema toevoegen     | 8      | 8        | 0      | 100%     |
| Evenement toevoegen  | 7      | 7        | 0      | 100%     |
| Vriend toevoegen     | 5      | 5        | 0      | 100%     |
| Favoriet spel        | 4      | 4        | 0      | 100%     |
| Bewerken/verwijderen | 7      | 7        | 0      | 100%     |
| Beveiliging          | 5      | 5        | 0      | 100%     |
| Responsief ontwerp   | 4      | 4        | 0      | 100%     |
| **TOTAAL**           | **52** | **52**   | **0**  | **100%** |

Vier bugs (Bug #1001, #1004, #1005, #1006) zijn tijdens het testen gevonden, opgelost en succesvol hertest. Na de bugfixes zijn alle tests opnieuw uitgevoerd met 100% slagingspercentage.

**Bewijs:** README sectie 12.11 (Testresultaten samenvatting) en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

---

### 15.5 K1-W5: Verbeteren / Opleveren

#### Criterium 1: De student heeft informatiebronnen geraadpleegd en geanalyseerd

**Antwoord:**
De volgende informatiebronnen zijn gebruikt tijdens het project:

| Bron                | Gebruikt voor                           | Voorbeeld                                              |
| ------------------- | --------------------------------------- | ------------------------------------------------------ |
| PHP.net (officieel) | Functiedocumentatie, best practices     | `password_hash()`, `DateTime::createFromFormat()`      |
| MDN Web Docs        | JavaScript validatie, DOM-manipulatie   | `addEventListener()`, regex patronen, `Date` object    |
| W3Schools           | Bootstrap 5 componenten, CSS properties | Cards, modals, responsive breakpoints                  |
| Stack Overflow      | Specifieke problemen oplossen           | Sessie-regeneratie timing, PDO error modes             |
| Bootstrap Docs      | Component-gebruik en customisatie       | Navbar, alerts, forms, toasts                          |
| OWASP               | Beveiligingsrichtlijnen                 | SQL-injectie preventie, XSS-bescherming, sessie-beheer |

Per bron is kritisch beoordeeld of de informatie actueel, betrouwbaar en toepasbaar is. Officiële documentatie (PHP.net, MDN) heeft altijd voorrang boven community-antwoorden (Stack Overflow).

**Bewijs:** PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student beschrijft gevonden problemen met oorzaakanalyse

**Antwoord:**
Tijdens het testen en code-reviewen zijn 4 bugs gevonden:

**Bug #1001 – Velden accepteerden alleen spaties**

- **Probleem:** Een gebruiker kon " " (spaties) invullen als gebruikersnaam
- **Oorzaak:** `empty()` in PHP retourneert `false` voor een string met spaties
- **Oplossing:** `trim()` + regex `/^\s*$/` controle toegevoegd
- **Bestanden:** functions.php (server), script.js (client)

**Bug #1004 – Ongeldige datums werden geaccepteerd**

- **Probleem:** Datum "2025-13-45" werd als geldig beschouwd
- **Oorzaak:** Alleen regex-controle op formaat, geen inhoudelijke controle
- **Oplossing:** `DateTime::createFromFormat()` met stricte vergelijking
- **Bestanden:** functions.php (server), script.js (client)

**Bug #1005 – Oranje achtergrondkleur op kaarten**

- **Probleem:** Alle glassmorphism-kaarten hadden een oranje achtergrond
- **Oorzaak:** CSS-variabele `--glass-bg` stond op `orange` i.p.v. transparante waarde
- **Oplossing:** Gewijzigd naar `rgba(255, 255, 255, 0.05)`
- **Bestand:** style.css

**Bug #1006 – Sessie-ID werd bij elk verzoek geregenereerd**

- **Probleem:** `session_regenerate_id(true)` stond in het sessie-startblok
- **Oorzaak:** functions.php wordt bij ELKE paginalading geladen, dus het ID werd continu vernieuwd
- **Oplossing:** Verplaatst naar alleen de `loginUser()` functie
- **Bestand:** functions.php

**Bewijs:** README sectie 13.1 (overzichtstabel) en sectie 13.2 (uitgebreid 5-stappen-verbeterproces per bug).

#### Criterium 3: De student doet verbetervoorstellen met impactbeschrijving

**Antwoord:**

| Nr  | Verbetervoorstel            | Impact                                                            | Categorie       | Prioriteit |
| --- | --------------------------- | ----------------------------------------------------------------- | --------------- | ---------- |
| V1  | Wachtwoord vergeten functie | Gebruikers die hun wachtwoord kwijtraken kunnen het herstellen    | Quick win       | Hoog       |
| V2  | Profielfoto uploaden        | Persoonlijkere ervaring, meer betrokkenheid                       | Gemiddeld       | Gemiddeld  |
| V3  | Real-time vriendenlijst     | Live status updates zonder pagina te verversen (WebSocket)        | Grote wijziging | Laag       |
| V4  | Zoekfunctie                 | Snel items terugvinden bij veel data, verbeterde bruikbaarheid    | Quick win       | Gemiddeld  |
| V5  | Meerdere talen (NL/EN)      | Breder publiek bereiken, internationalisatie                      | Grote wijziging | Laag       |
| V6  | E-mail notificaties         | Automatische herinnering per mail, hogere opkomst bij evenementen | Gemiddeld       | Gemiddeld  |
| V7  | Export naar iCal/Google Cal | Integratie met bestaande agenda-apps, meer adoptie                | Gemiddeld       | Laag       |

**Quick wins** (V1, V4) kunnen relatief snel geïmplementeerd worden met bestaande PHP-functies en hebben direct merkbare impact op de gebruikerservaring.

**Grote wijzigingen** (V3, V5) vereisen fundamentele architectuurwijzigingen (WebSocket-server, i18n-framework) en zijn beter geschikt voor een volgende projectfase.

**Bewijs:** README sectie 13.3 en PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 4: De student reflecteert op het verbeterproces

**Antwoord:**
Het verbeterproces is systematisch uitgevoerd volgens een vast 5-stappenmodel:

1. **Ontdekken** – Bug gevonden via handmatig testen of code review
2. **Analyseren** – Oorzaak achterhalen door code te doorlopen en scenario na te bootsen
3. **Oplossen** – Fix implementeren in de juiste bestanden (zowel server als client waar nodig)
4. **Hertesten** – Dezelfde testcase opnieuw uitvoeren om te bevestigen dat de bug is opgelost
5. **Documenteren** – Bug, oorzaak, oplossing en hertest vastleggen in de documentatie

Dit proces is voor elk van de 4 bugs volledig doorlopen en gedocumenteerd in README sectie 13.2.

De belangrijkste leerpunten uit het verbeterproces:

- Regex-validatie alleen is NIET voldoende voor datumcontrole; gebruik altijd ook de `DateTime`-klasse
- PHP's `empty()` functie heeft onverwacht gedrag met spaties; altijd combineren met `trim()`
- Beveiligingsfuncties moeten op de juiste plek staan (sessie-regeneratie alleen bij login, niet bij elke paginalaad)
- Visuele inspectie is essentieel naast functioneel testen (CSS Bug #1005 werd alleen visueel ontdekt)

**Bewijs:** README sectie 13.2 (volledig verbeterproces per bug), PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Reflectie.pdf`.

#### Criterium 5: De student heeft feedback ontvangen van de begeleider

**Antwoord:**
De stagebegeleider heeft feedback gegeven op het verbeterproces en de opgeleverde applicatie. Dit is vastgelegd in een apart feedbackdocument met opmerkingen over:

- Code-kwaliteit en structuur
- Volledigheid van de documentatie
- Beveiligingsmaatregelen
- Testdekking
- Verbeterpunten voor toekomstige projecten

**Bewijs:** PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Feedback van Stagebegeleider.pdf`.

#### Criterium 6: De student heeft het product opgeleverd met oplevernotities

**Antwoord:**
Het project is opgeleverd met:

- Volledige broncode (22+ bestanden) in de GitHub-repository
- Database-script (`database.sql`) voor eenvoudige installatie
- Installatie-instructies (README sectie 11)
- Uitgebreide documentatie (README.md, 1600+ regels)
- Demovideo (`K1-W3-DEMO VIDEO.mp4`)
- Screenshots van alle pagina's (`Demo Fotos/Software Fotos/`)
- Oplevernotities met aandachtspunten voor overdracht

**Bewijs:** PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Oplevering Notities.pdf` en alle bestanden in de repository.

---

### 15.6 K2-W1: Overleggen

#### Criterium 1: De student voert overleg over het project

**Antwoord:**
Er zijn meerdere overlegmomenten geweest gedurende het project:

- **Startoverleg:** Bespreking van projectopzet, scope en verwachtingen
- **Voortgangsgesprekken:** Regelmatige check-ins over de voortgang per fase
- **Technische overleggen:** Bespreking van databaseontwerp, beveiligingskeuzes en architectuur
- **Feedbackmomenten:** Ontvangen en verwerken van feedback op code en documentatie
- **Eindoverleg:** Bespreking van oplevering en evaluatie

Bij elk overleg zijn de volgende zaken gedocumenteerd:

- Datum en deelnemers
- Besproken onderwerpen
- Genomen beslissingen
- Actiepunten

**Bewijs:** PDF `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf` (overlegverslagen).

#### Criterium 2: De student ontvangt en verwerkt feedback

**Antwoord:**
Feedback van de stagebegeleider is ontvangen op meerdere momenten:

- Feedback op het planningsdocument (K1-W1)
- Feedback op het ontwerp (K1-W2)
- Feedback op de code-kwaliteit en beveiliging (K1-W3)
- Feedback op de testresultaten (K1-W4)
- Feedback op het verbeterproces (K1-W5)

De feedback is concreet verwerkt in het project. Voorbeelden:

- Na feedback op beveiliging is `checkOwnership()` toegevoegd
- Na feedback op validatie is de dubbele (client + server) validatie geïmplementeerd
- Na feedback op documentatie is de README uitgebreid naar 1600+ regels

**Bewijs:** PDF `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf` en PDF `Feedback Stage Harsha Kanaparthi .pdf`.

---

### 15.7 K2-W2: Presenteren

#### Criterium 1: De student geeft minimaal 2 presentaties over het project

**Antwoord:**
Er zijn twee presentaties gegeven:

**Presentatie 1: Met stagebegeleider**

- **Onderwerp:** Demonstratie van de GamePlan Scheduler applicatie
- **Publiek:** Stagebegeleider
- **Inhoud:** Projectopzet, technische keuzes, live demonstratie van alle functionaliteiten, code-uitleg
- **Feedback:** Ontvangen en gedocumenteerd

**Presentatie 2: Met studiegenoot**

- **Onderwerp:** MBO-4 Opleiding Software Development – Portfolio Website
- **Publiek:** Studiegenoot
- **Inhoud:** Projectoverzicht, gebruikte technologieën, demonstratie, leerervaringen
- **Feedback:** Ontvangen en gedocumenteerd

**Bewijs presentatie 1:**

- PDF `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1.pdf`
- PDF `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1-Feedback Van de stage begeleider-Harsha Vardhan Kanaparthi (1).pdf`
- PDF `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1- Reflectie Verslag_ Portfolio Website-Harsha Vardhan Kanaparthi.pdf`

**Bewijs presentatie 2:**

- PDF `K2 W2 Presenteren-Presentatie met studiegenoot-Presentatie-2-MBO-4-Opleiding-Software-Development.pdf`
- PDF `K2 W2 Presenteren-Presentatie met studiegenoot-Presentatie-2-MBO-4-Opleiding-Software-Development-  Reflectie- Verslag- Portfolio- Website-Harsha -Vardhan- Kanaparthi.pdf`

#### Criterium 2: De student reflecteert op de gegeven presentaties

**Antwoord:**
Na elke presentatie is een reflectieverslag geschreven waarin de volgende punten zijn behandeld:

- Wat ging goed tijdens de presentatie?
- Wat kon beter?
- Hoe was de interactie met het publiek?
- Welke vragen werden gesteld en hoe zijn die beantwoord?
- Wat neem ik mee naar de volgende presentatie?

**Bewijs:** Reflectieverslagen zijn opgenomen in de presentatie-PDFs (zie hierboven).

---

### 15.8 K2-W3: Reflecteren

#### Criterium 1: De student reflecteert op het gehele projectproces

**Antwoord:**
Er is een uitgebreid reflectieverslag geschreven dat het volledige projectproces evalueert:

**Wat ging goed:**

- De scheiding van verantwoordelijkheden (Separation of Concerns) heeft de code overzichtelijk en onderhoudbaar gehouden
- De dubbele validatie (client + server) heeft veel potentiële problemen voorkomen
- Het systematische testproces heeft 4 bugs aan het licht gebracht die anders in productie waren gekomen
- De documentatie (README) is uitgebreid en maakt het project overdraagbaar

**Wat kon beter:**

- Eerder beginnen met testen in plaats van alles aan het eind
- Een gestructureerder testplan opzetten vooraf in plaats van achteraf
- Meer gebruikmaken van geautomatiseerde tests (unit tests met PHPUnit)
- Een MVC-patroon overwegen voor nog betere scheiding van logica en presentatie

**Leermomenten:**

- Beveiliging moet vanaf het begin worden meegenomen, niet als afterthought
- Documentatie schrijven tijdens het coderen bespaart tijd ten opzichte van achteraf documenteren
- Code review (ook eigen code) is waardevol: Bug #1006 (sessie-regeneratie) is op die manier gevonden
- Visueel testen is net zo belangrijk als functioneel testen (Bug #1005 CSS)

**Bewijs:** PDF `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft feedback ontvangen met handtekening van begeleider

**Antwoord:**
De stagebegeleider heeft een feedbackformulier ingevuld en ondertekend. Dit document bevat:

- Beoordeling van de technische vaardigheden
- Beoordeling van de werkhouding en zelfstandigheid
- Beoordeling van de communicatie en samenwerking
- Opmerkingen en aanbevelingen
- Handtekening van de stagebegeleider als bevestiging

**Bewijs:** PDF `K2 W3 Reflectie-Harsha Vardhan Kanaparthi-Feedback  bij Stage-Begeleider  Met Handtekening.pdf`.

#### Criterium 3: De student heeft een portfolio-presentatie voorbereid

**Antwoord:**
Er is een portfolio-presentatie voorbereid die het volledige project samenvat en geschikt is voor het examenmoment. De presentatie bevat:

- Projectoverzicht en doelen
- Technische architectuur
- Demonstratie van werkende functionaliteiten
- Beveiligingsmaatregelen
- Testresultaten
- Verbeteringen en leermomenten
- Reflectie op het proces

**Bewijs:** PDF `K2 W3 Reflectie-Portfolio-Website-Presentatie.pdf`.

#### Criterium 4: De student heeft een beoordelingsrubric en eindfeedback

**Antwoord:**
De stagebegeleider heeft een beoordelingsrubric ingevuld en eindfeedback gegeven over de hele stageperiode.

**Bewijs:**

- PDF `Beoordelingsrubrics Stagiaire- Harsha .pdf` (beoordelingsrubric)
- PDF `Feedback Stage Harsha Kanaparthi .pdf` (eindfeedback stage)

---

### 15.9 Bewijs-index: Overzicht van alle bewijsstukken per werkproces

De onderstaande tabel geeft per werkproces een compleet overzicht van alle bewijsstukken:

| Werkproces            | README Sectie(s) | PDF Document(en)                                                                                                                                                                                                                                 | Screenshots / Video                                                |
| --------------------- | ---------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------ |
| **K1-W1** Planning    | 1, 3             | `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf`, `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`                                                                                                                                      | –                                                                  |
| **K1-W2** Ontwerpen   | 3, 4, 5, 6, 7    | `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`                                                                                                                                                                                                    | `Demo Fotos/Software Fotos/` (12 screenshots)                      |
| **K1-W3** Realiseren  | 1–11             | `K1 W3 Realisatie-Realisatie verslag-Harsha Vardhan Kanaparthi.pdf`, `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`                                                                                                                 | `K1-W3-DEMO VIDEO.mp4`, `Demo Fotos/VersieBeheer/Versiebeheer.png` |
| **K1-W4** Testen      | 12               | `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`                                                                                                                                                                                                     | –                                                                  |
| **K1-W5** Verbeteren  | 13               | `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`, `K1-W5-Verbeteren-...-Reflectie.pdf`, `K1-W5-Verbeteren-...-Feedback van Stagebegeleider.pdf`, `K1-W5-Verbeteren-...-Oplevering Notities.pdf`                                                  | –                                                                  |
| **K2-W1** Overleggen  | –                | `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`, `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`                                                                                                                                 | –                                                                  |
| **K2-W2** Presenteren | –                | Presentatie-1 (3 PDFs: presentatie + feedback + reflectie), Presentatie-2 (2 PDFs: presentatie + reflectie)                                                                                                                                      | –                                                                  |
| **K2-W3** Reflecteren | –                | `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf`, `K2 W3 Reflectie-...-Feedback Met Handtekening.pdf`, `K2 W3 Reflectie-Portfolio-Website-Presentatie.pdf`, `Beoordelingsrubrics Stagiaire- Harsha .pdf`, `Feedback Stage Harsha Kanaparthi .pdf` | –                                                                  |

**Totaal bewijsstukken:** 21 PDF-documenten + 12 screenshots + 1 demovideo + 1 versiebeheer-screenshot + README.md (1600+ regels) + 22+ bronbestanden

---

_Dit document beschrijft de volledige GamePlan Scheduler applicatie van A tot Z. Alle code, validaties, flows, beveiligingen en examencriteria (Crebo 25998, K1-W1 t/m K2-W3) zijn hierin gedocumenteerd._

_Auteur: Harsha Vardhan Kanaparthi | Studentnummer: 2195344 | Opleiding: MBO-4 Software Development_
