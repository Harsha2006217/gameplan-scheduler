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
2. [Gebruikersverhalen (User Stories)](#2-gebruikersverhalen-user-stories)
3. [Mapstructuur](#3-mapstructuur)
4. [Technische Specificaties](#4-technische-specificaties)
5. [Database Structuur](#5-database-structuur)
6. [Alle Validaties met Algoritmen](#6-alle-validaties-met-algoritmen)
7. [Alle Functionele Flows](#7-alle-functionele-flows)
8. [Code Flow Diagrammen](#8-code-flow-diagrammen)
9. [Beveiligingsmaatregelen](#9-beveiligingsmaatregelen)
10. [Foutafhandeling](#10-foutafhandeling)
11. [Volledige Functiereferentie](#11-volledige-functiereferentie)
12. [Installatie-instructies](#12-installatie-instructies)
13. [Testen (K1-W4)](#13-testen-k1-w4)
14. [Verbeteren (K1-W5)](#14-verbeteren-k1-w5)
15. [Examenpresentatie Hulp](#15-examenpresentatie-hulp)
16. [Onderlegger C24 – Examen Checklistvragen](#16-onderlegger-c24--examen-checklistvragen)

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

## 2. Gebruikersverhalen (User Stories)

De applicatie is gebouwd op basis van 6 gebruikersverhalen (user stories). Elk verhaal beschrijft wat een gebruiker wil doen en waarom:

### Gebruikersverhaal 1: Profiel met favoriete games
> _Als gamer wil ik een profiel maken met mijn favoriete games, zodat anderen zien wat ik speel._

| Onderdeel | Bestand(en) | Functies |
| --- | --- | --- |
| Profiel bekijken | `profile.php` | `getFavoriteGames()` |
| Favoriet toevoegen | `profile.php` (formulier) | `addFavoriteGame()`, `getOrCreateGameId()` |
| Favoriet bewerken | `edit_favorite.php` | `updateFavoriteGame()` |
| Favoriet verwijderen | `delete.php?type=favorite` | `deleteFavoriteGame()` |

### Gebruikersverhaal 2: Vriendenlijst beheren
> _Als gamer wil ik vrienden toevoegen aan mijn lijst, zodat ik makkelijk contact houd._

| Onderdeel | Bestand(en) | Functies |
| --- | --- | --- |
| Vriendenlijst zien | `index.php` (dashboard) | `getFriends()` |
| Vriend toevoegen | `add_friend.php` | `addFriend()` |
| Vriend bewerken | `edit_friend.php` | `updateFriend()` |
| Vriend verwijderen | `delete.php?type=friend` | `deleteFriend()` |

### Gebruikersverhaal 3: Speelschema's delen
> _Als gamer wil ik speelschema's delen in een kalender, zodat ik met vrienden kan afspreken om te gamen._

| Onderdeel | Bestand(en) | Functies |
| --- | --- | --- |
| Schema's bekijken | `index.php` (dashboard + kalender) | `getSchedules()`, `getCalendarItems()` |
| Schema toevoegen | `add_schedule.php` | `addSchedule()` |
| Schema bewerken | `edit_schedule.php` | `editSchedule()` |
| Schema verwijderen | `delete.php?type=schedule` | `deleteSchedule()` |

### Gebruikersverhaal 4: Evenementen toevoegen
> _Als gamer wil ik evenementen toevoegen zoals toernooien, zodat ik een overzicht heb van aankomende activiteiten._

| Onderdeel | Bestand(en) | Functies |
| --- | --- | --- |
| Evenementen bekijken | `index.php` (dashboard + kalender) | `getEvents()`, `getCalendarItems()` |
| Evenement toevoegen | `add_event.php` | `addEvent()` |
| Evenement bewerken | `edit_event.php` | `editEvent()` |
| Evenement verwijderen | `delete.php?type=event` | `deleteEvent()` |

### Gebruikersverhaal 5: Herinneringen instellen
> _Als gamer wil ik herinneringen instellen voor schema's en evenementen, zodat ik niets mis._

| Onderdeel | Bestand(en) | Functies |
| --- | --- | --- |
| Herinnering kiezen | `add_event.php`, `edit_event.php` | Dropdown: Geen / 1 uur / 1 dag ervoor |
| Herinnering tonen | `index.php` (pop-up) | `getReminders()` |
| JavaScript pop-up | `script.js` | `toonMelding()` |

### Gebruikersverhaal 6: Bewerken en verwijderen
> _Als gamer wil ik alles bewerken of verwijderen, zodat mijn planning altijd klopt._

| Onderdeel | Bestand(en) | Functies |
| --- | --- | --- |
| Bewerken (alle items) | `edit_*.php` pagina's | `updateFavoriteGame()`, `updateFriend()`, `editSchedule()`, `editEvent()` |
| Verwijderen (alle items) | `delete.php` | `deleteFavoriteGame()`, `deleteFriend()`, `deleteSchedule()`, `deleteEvent()` |
| Eigenaarschap controle | `functions.php` | `checkOwnership()` |
| Bevestigingsdialoog | `script.js` | JavaScript `confirm()` pop-up |

### Samenvatting: Alle user stories gerealiseerd

| Nr | User Story | Status | Bewijs |
| --- | --- | --- | --- |
| US-1 | Profiel met favoriete games | Gerealiseerd | `profile.php`, `edit_favorite.php` |
| US-2 | Vriendenlijst beheren | Gerealiseerd | `add_friend.php`, `edit_friend.php` |
| US-3 | Speelschema's delen in kalender | Gerealiseerd | `add_schedule.php`, `edit_schedule.php`, kalender in `index.php` |
| US-4 | Evenementen toevoegen | Gerealiseerd | `add_event.php`, `edit_event.php` |
| US-5 | Herinneringen instellen | Gerealiseerd | Herinnering-dropdown + `getReminders()` pop-up |
| US-6 | Alles bewerken of verwijderen | Gerealiseerd | `edit_*.php` + `delete.php` met soft delete |

---

## 3. Mapstructuur

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

## 4. Technische Specificaties

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

## 5. Database Structuur

De database `gameplan_db` bevat 6 tabellen:

### 5.1 Tabel: Users (Gebruikers)

De hoofdtabel. Alle andere tabellen verwijzen hiernaar.

| Kolom           | Type                         | Beschrijving                          |
| --------------- | ---------------------------- | ------------------------------------- |
| `user_id`       | INT AUTO_INCREMENT           | Primaire sleutel, uniek per gebruiker |
| `username`      | VARCHAR(50) NOT NULL         | Weergavenaam van de gebruiker         |
| `email`         | VARCHAR(100) UNIQUE NOT NULL | E-mailadres, gebruikt voor inloggen   |
| `password_hash` | VARCHAR(255) NOT NULL        | Bcrypt-versleuteld wachtwoord         |
| `last_activity` | TIMESTAMP                    | Wanneer gebruiker laatst actief was   |
| `deleted_at`    | TIMESTAMP NULL               | Soft delete: NULL = actief            |

### 5.2 Tabel: Games (Spellen)

Slaat alle spellen op die gebruikers als favoriet kunnen toevoegen.

| Kolom         | Type                  | Beschrijving              |
| ------------- | --------------------- | ------------------------- |
| `game_id`     | INT AUTO_INCREMENT    | Primaire sleutel          |
| `titel`       | VARCHAR(100) NOT NULL | Naam van het spel         |
| `description` | TEXT                  | Beschrijving van het spel |
| `deleted_at`  | TIMESTAMP NULL        | Soft delete               |

### 5.3 Tabel: UserGames (Koppeltabel - Favorieten)

Verbindt gebruikers met hun favoriete spellen (veel-op-veel relatie).

| Kolom     | Type         | Beschrijving                       |
| --------- | ------------ | ---------------------------------- |
| `user_id` | INT NOT NULL | Verwijst naar Users                |
| `game_id` | INT NOT NULL | Verwijst naar Games                |
| `note`    | TEXT         | Persoonlijke notitie over het spel |

**Primaire sleutel:** Samengesteld uit `user_id` + `game_id` (voorkomt duplicaten).
**Foreign keys:** Beide met ON DELETE CASCADE.

### 5.4 Tabel: Friends (Vrienden)

Slaat gaming-vrienden op per gebruiker.

| Kolom             | Type                          | Beschrijving           |
| ----------------- | ----------------------------- | ---------------------- |
| `friend_id`       | INT AUTO_INCREMENT            | Primaire sleutel       |
| `user_id`         | INT NOT NULL                  | Verwijst naar Users    |
| `friend_username` | VARCHAR(50) NOT NULL          | Gamertag van de vriend |
| `note`            | TEXT                          | Persoonlijke notitie   |
| `status`          | VARCHAR(50) DEFAULT 'Offline' | Online-status          |
| `deleted_at`      | TIMESTAMP NULL                | Soft delete            |

### 5.5 Tabel: Schedules (Gaming-schema's)

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

### 5.6 Tabel: Events (Evenementen)

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

## 6. Alle Validaties met Algoritmen

### 6.1 Overzicht van alle validaties in de applicatie

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

### 6.2 Algoritme per validatie

#### V1 + V2 + V3: Verplicht veld validatie (`validateRequired`)

**Bestand:** `functions.php` regel 67-83

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

**Bestand:** `functions.php` regel 134-141

```
ALGORITME: valideerEmail(email)

1. CONTROLEER email met PHP filter_var(FILTER_VALIDATE_EMAIL)
   Dit controleert of de email het formaat "naam@domein.extensie" heeft
2. ALS filter ONGELDIG retourneert:
   -> RETOURNEER foutmelding: "Ongeldig e-mail formaat"
3. RETOURNEER null (geen fout)
```

#### V5: Wachtwoord lengte validatie

**Bestand:** `functions.php` regel 306-307

```
ALGORITME: valideerWachtwoord(wachtwoord)

1. ALS lengte van wachtwoord kleiner dan 8 tekens:
   -> RETOURNEER foutmelding: "Wachtwoord moet minimaal 8 tekens zijn"
2. RETOURNEER null (geen fout)
```

#### V6 + V7: Datum validatie (`validateDate`)

**Bestand:** `functions.php` regel 94-111

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

**Bestand:** `functions.php` regel 119-126

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

**Bestand:** `functions.php` regel 149-155

```
ALGORITME: valideerUrl(url)

1. ALS url NIET leeg is:
   1a. CONTROLEER url met PHP filter_var(FILTER_VALIDATE_URL)
   1b. ALS filter ONGELDIG retourneert:
       -> RETOURNEER foutmelding: "Ongeldig URL formaat"
2. RETOURNEER null (geen fout, URL is optioneel)
```

#### V10: Kommagescheiden lijst validatie (`validateCommaSeparated`)

**Bestand:** `functions.php` regel 168-181

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

**Bestand:** `functions.php` regel 310-315

```
ALGORITME: controleerEmailBestaat(email)

1. VOER database-query uit: tel gebruikers met dit e-mailadres
   WHERE email = :email AND deleted_at IS NULL
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "E-mail al geregistreerd"
3. RETOURNEER null (e-mail is beschikbaar)
```

#### V12: Spel al in favorieten validatie

**Bestand:** `functions.php` regel 452-458

```
ALGORITME: controleerAlFavoriet(userId, gameId)

1. VOER database-query uit: tel records in UserGames
   WHERE user_id = :userId AND game_id = :gameId
2. ALS telling groter dan 0:
   -> RETOURNEER foutmelding: "Spel al in favorieten"
3. RETOURNEER null (nog niet als favoriet)
```

#### V13: Vriend al toegevoegd validatie

**Bestand:** `functions.php` regel 564-572

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

**Bestand:** `functions.php` regel 920-928

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

**Bestand:** `functions.php` regel 791-793

```
ALGORITME: valideerBeschrijving(beschrijving)

1. ALS beschrijving NIET leeg is EN lengte groter dan 500:
   -> RETOURNEER foutmelding: "Beschrijving te lang (max 500)"
2. RETOURNEER null (geen fout)
```

#### V16: Herinnering waarde validatie

**Bestand:** `functions.php` regel 794-796

```
ALGORITME: valideerHerinnering(herinnering)

1. ALS herinnering NIET in de lijst ['none', '1_hour', '1_day']:
   -> RETOURNEER foutmelding: "Ongeldige herinnering"
2. RETOURNEER null (geen fout)
```

#### V17: Sessie timeout validatie (`checkSessionTimeout`)

**Bestand:** `functions.php` regel 270-279

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

**Bestand:** `functions.php` regel 233-236

```
ALGORITME: isIngelogd()

1. CONTROLEER of $_SESSION['user_id'] bestaat
2. ALS het bestaat:
   -> RETOURNEER true (ingelogd)
3. ANDERS:
   -> RETOURNEER false (niet ingelogd)
```

### 6.3 JavaScript client-side validaties

#### Login formulier validatie (`validateLoginForm`)

**Bestand:** `script.js` regel 35-57

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

**Bestand:** `script.js` regel 74-111

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

**Bestand:** `script.js` regel 129-183

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

**Bestand:** `script.js` regel 202-268

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

## 7. Alle Functionele Flows

### 7.1 Flow: Gebruiker Registreren

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

### 7.2 Flow: Gebruiker Inloggen

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

### 7.3 Flow: Dashboard Laden (index.php)

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
         -> initialiseerFuncties() voor interactieve elementen
```

### 7.4 Flow: Vriend Toevoegen

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

### 7.5 Flow: Favoriet Spel Toevoegen

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

### 7.6 Flow: Gaming-schema Toevoegen

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

### 7.7 Flow: Evenement Toevoegen

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

### 7.8 Flow: Item Bewerken (schema, evenement, vriend, favoriet)

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

### 7.9 Flow: Item Verwijderen

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

### 7.10 Flow: Uitloggen

```
STAP 1: Gebruiker klikt op "Uitloggen" in navigatie
STAP 2: Redirect naar logout.php
STAP 3: Server leegt alle sessievariabelen: $_SESSION = []
STAP 4: Server vernietigt de sessiecookie
STAP 5: Server roept session_destroy() aan
STAP 6: Redirect naar login.php
```

---

## 8. Code Flow Diagrammen

### 8.1 Code Flow: Login Pagina Laden

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
  |                                |-- Initialiseer $fout = ''
  |                                |
  |                                |-- Render HTML:
  |                                |   |-- login.php regel 45-109
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
  |                                |-- Haal $emailAdres en $wachtwoord uit $_POST
  |                                |-- loginUser($emailAdres, $wachtwoord)
  |                                |   |-- getDBConnection() [db.php]
  |                                |   |   |-- Maak PDO-verbinding (Singleton)
  |                                |   |-- validateRequired($emailAdres)
  |                                |   |-- validateRequired($wachtwoord)
  |                                |   |-- SELECT user WHERE email = :email
  |                                |   |-- password_verify($wachtwoord, $hashWachtwoord)
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
| `login.php`     | -                      | 20      | Laadt functions.php        |
| `login.php`     | -                      | 23-26   | Controleert of al ingelogd |
| `login.php`     | -                      | 32-43   | Verwerkt POST-formulier    |
| `functions.php` | `loginUser()`          | 347-381 | Authenticatie logica       |
| `functions.php` | `validateRequired()`   | 67-83   | Veldvalidatie              |
| `functions.php` | `isLoggedIn()`         | 233-236 | Sessiecontrole             |
| `functions.php` | `updateLastActivity()` | 254-261 | Activiteit bijwerken       |
| `db.php`        | `getDBConnection()`    | 53-100  | Database verbinding        |
| `script.js`     | `validateLoginForm()`  | 35-57   | Client-side validatie      |

### 8.2 Code Flow: Dashboard (Home) Pagina Laden

```
BROWSER                          SERVER
  |                                |
  |-- GET /index.php ------------->|
  |                                |-- Laad functions.php
  |                                |   |-- Laad db.php (databaseverbinding)
  |                                |   |-- Start sessie
  |                                |
  |                                |-- checkSessionTimeout() [functions.php:270]
  |                                |   |-- Controleer of > 30 min inactief
  |                                |   |-- ALS timeout: session_destroy()
  |                                |   |-- Update $_SESSION['last_activity']
  |                                |
  |                                |-- isLoggedIn() [functions.php:233]
  |                                |   |-- ALS niet ingelogd: redirect login.php
  |                                |
  |                                |-- getUserId() [functions.php:243]
  |                                |   |-- Haal user_id uit sessie
  |                                |
  |                                |-- updateLastActivity() [functions.php:254]
  |                                |   |-- UPDATE Users SET last_activity
  |                                |
  |                                |-- Haal sorteerparameters uit $_GET
  |                                |
  |                                |-- getFriends($userId) [functions.php:641]
  |                                |   |-- SELECT FROM Friends WHERE user_id
  |                                |   |   AND deleted_at IS NULL
  |                                |
  |                                |-- getFavoriteGames($userId) [functions.php:520]
  |                                |   |-- SELECT FROM UserGames JOIN Games
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |
  |                                |-- getSchedules($userId, $sort) [functions.php:696]
  |                                |   |-- Valideer sorteerparameter (whitelist)
  |                                |   |-- SELECT FROM Schedules JOIN Games
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |   |   ORDER BY $sort LIMIT 50
  |                                |
  |                                |-- getEvents($userId, $sort) [functions.php:822]
  |                                |   |-- Valideer sorteerparameter
  |                                |   |-- SELECT FROM Events
  |                                |   |   WHERE user_id AND deleted_at IS NULL
  |                                |   |   ORDER BY $sort LIMIT 50
  |                                |
  |                                |-- getCalendarItems($userId) [functions.php:935]
  |                                |   |-- Combineer schema's + evenementen
  |                                |   |-- Sorteer op datum+tijd (usort)
  |                                |
  |                                |-- getReminders($userId) [functions.php:955]
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
  |   |-- initialiseerFuncties()  |
  |   |   |-- Smooth scroll links  |
  |   |   |-- Bevestiging bij delete|
  |   |   |-- Auto-dismiss alerts  |
  |   |-- Toon reminder pop-ups    |
```

**Kritieke bestanden en functies:**

| Bestand         | Functie                 | Regel   | Taak                   |
| --------------- | ----------------------- | ------- | ---------------------- |
| `index.php`     | -                       | 21      | Laadt functions.php    |
| `index.php`     | -                       | 24      | checkSessionTimeout()  |
| `index.php`     | -                       | 27-30   | Inlogcontrole          |
| `index.php`     | -                       | 43-48   | Alle data ophalen      |
| `index.php`     | -                       | 55-290  | HTML rendering         |
| `functions.php` | `checkSessionTimeout()` | 270-279 | Sessie-expiratie       |
| `functions.php` | `getFriends()`          | 641-651 | Vrienden ophalen       |
| `functions.php` | `getFavoriteGames()`    | 520-531 | Favorieten ophalen     |
| `functions.php` | `getSchedules()`        | 696-713 | Schema's ophalen       |
| `functions.php` | `getEvents()`           | 822-837 | Evenementen ophalen    |
| `functions.php` | `getCalendarItems()`    | 935-947 | Kalender samenvoegen   |
| `functions.php` | `getReminders()`        | 955-974 | Herinneringen filteren |
| `functions.php` | `safeEcho()`            | 50-53   | XSS-bescherming        |
| `header.php`    | -                       | 1-94    | Navigatiebalk          |
| `footer.php`    | -                       | 1-42    | Voettekst              |
| `script.js`     | `initialiseerFuncties()`  | 300-330 | Pagina-initialisatie   |

### 8.3 Code Flow: Item Verwijderen

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
  |                                |   |-- $doorstuurPagina = 'index.php'
  |                                |
  |                                |-- setMessage('success', 'Verwijderd!')
  |                                |-- header("Location: index.php")
  |<-- 302 Redirect --------------|
```

---

## 9. Beveiligingsmaatregelen

### 9.1 Overzicht van alle beveiligingen

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

### 9.2 Uitleg per beveiliging

**B1 - Wachtwoord hashing (bcrypt)**
Wachtwoorden worden NOOIT als platte tekst opgeslagen. De functie `password_hash()` met `PASSWORD_BCRYPT` versleutelt het wachtwoord met het Blowfish-algoritme. Bij inloggen wordt `password_verify()` gebruikt om het ingevoerde wachtwoord te vergelijken met de hash. Zelfs als de database wordt gestolen, zijn de wachtwoorden onleesbaar.

**B2 - Prepared statements (SQL-injectie preventie)**
Alle database-queries gebruiken PDO prepared statements met `:named` parameters. De database-engine verwerkt de parameters apart van de query, waardoor kwaadaardige SQL-code niet kan worden uitgevoerd. Voorbeeld:

```php
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
$stmt->execute(['email' => $emailAdres]);
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
Data wordt niet echt verwijderd. In plaats daarvan wordt het veld `deleted_at` gezet op de huidige datum/tijd. Alle queries filteren op `WHERE deleted_at IS NULL`. Dit biedt mogelijkheid tot herstel. Uitzondering: de koppeltabel `UserGames` (favorieten) gebruikt harde delete omdat het een relatie-koppeling is — het spel zelf blijft bewaard in de `Games` tabel.

---

## 10. Foutafhandeling

### 10.1 Patroon: Functie retourwaarden

Alle functies in de applicatie volgen hetzelfde patroon:

- **Succes:** retourneer `null`
- **Fout:** retourneer een foutmelding als string

```php
$fout = addSchedule($userId, $spelTitel, $datum, $tijd, $vrienden, $gedeeldMet);
if ($fout) {
    // Toon foutmelding
} else {
    // Actie gelukt, redirect
}
```

### 10.2 Patroon: Database foutafhandeling

```php
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Database fout: " . $e->getMessage());  // Log voor ontwikkelaar
    die("Sorry, er was een probleem.");                // Generiek bericht voor gebruiker
}
```

### 10.3 Patroon: Sessiebericht systeem

Berichten worden opgeslagen in de sessie en op de volgende pagina getoond:

```php
// Op pagina 1: Sla bericht op
setMessage('success', 'Item succesvol toegevoegd!');
header("Location: index.php");

// Op pagina 2 (index.php): Toon en verwijder bericht
echo getMessage();  // Toont Bootstrap alert, verwijdert daarna uit sessie
```

### 10.4 Alle foutmeldingen in de applicatie

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

## 11. Volledige Functiereferentie

### 11.1 Database functies (`db.php`)

| Functie             | Parameters | Retourneert | Beschrijving                                        |
| ------------------- | ---------- | ----------- | --------------------------------------------------- |
| `getDBConnection()` | geen       | PDO object  | Maakt of hergebruikt databaseverbinding (Singleton) |

### 11.2 Helper functies (`functions.php`)

| Functie                                            | Parameters          | Retourneert         | Beschrijving                       |
| -------------------------------------------------- | ------------------- | ------------------- | ---------------------------------- |
| `safeEcho($tekst)`                                 | string              | string              | Escapet HTML-tekens tegen XSS      |
| `validateRequired($waarde, $veldnaam, $maxLengte)` | string, string, int | null of foutmelding | Valideert verplicht veld           |
| `validateDate($datum)`                              | string              | null of foutmelding | Valideert datumformaat en toekomst |
| `validateTime($tijd)`                              | string              | null of foutmelding | Valideert tijdformaat UU:MM        |
| `validateEmail($emailAdres)`                            | string              | null of foutmelding | Valideert e-mailformaat            |
| `validateUrl($url)`                                | string              | null of foutmelding | Valideert URL-formaat (optioneel)  |
| `validateCommaSeparated($waarde, $veldnaam)`       | string, string      | null of foutmelding | Valideert kommagescheiden lijst    |

### 11.3 Sessie- en berichtfuncties (`functions.php`)

| Functie                   | Parameters     | Retourneert | Beschrijving                              |
| ------------------------- | -------------- | ----------- | ----------------------------------------- |
| `setMessage($type, $tekst)` | string, string | void        | Slaat bericht op in sessie                |
| `getMessage()`            | geen           | HTML string | Haalt bericht op en verwijdert uit sessie |

### 11.4 Authenticatie functies (`functions.php`)

| Functie                                      | Parameters | Retourneert         | Beschrijving                                                      |
| -------------------------------------------- | ---------- | ------------------- | ----------------------------------------------------------------- |
| `isLoggedIn()`                               | geen       | boolean             | Controleert of gebruiker ingelogd is                              |
| `getUserId()`                                | geen       | int                 | Haalt gebruiker-ID uit sessie (0 als niet ingelogd)               |
| `updateLastActivity($pdo, $userId)`          | PDO, int   | void                | Werkt laatste activiteit bij in database                          |
| `checkSessionTimeout()`                      | geen       | void                | Controleert 30-minuten timeout, vernietigt sessie indien verlopen |
| `registerUser($gebruikersnaam, $emailAdres, $wachtwoord)` | 3x string  | null of foutmelding | Registreert nieuw account                                         |
| `loginUser($emailAdres, $wachtwoord)`               | 2x string  | null of foutmelding | Authenticeert gebruiker                                           |
| `logout()`                                   | geen       | void                | Vernietigt sessie, redirect naar login                            |

### 11.5 Spel functies (`functions.php`)

| Functie                                                             | Parameters          | Retourneert         | Beschrijving                          |
| ------------------------------------------------------------------- | ------------------- | ------------------- | ------------------------------------- |
| `getOrCreateGameId($pdo, $titel, $beschrijving)`                     | PDO, string, string | int (game_id)       | Haalt bestaand spel op of maakt nieuw |
| `addFavoriteGame($userId, $titel, $beschrijving, $notitie)`             | int, 3x string      | null of foutmelding | Voegt spel toe aan favorieten         |
| `updateFavoriteGame($userId, $gameId, $titel, $beschrijving, $notitie)` | int, int, 3x string | null of foutmelding | Bewerkt favoriet spel                 |
| `deleteFavoriteGame($userId, $gameId)`                              | int, int            | null                | Verwijdert spel uit favorieten        |
| `getFavoriteGames($userId)`                                         | int                 | array               | Haalt alle favoriete spellen op       |
| `getGames()`                                                        | geen                | array               | Haalt alle spellen op                 |

### 11.6 Vrienden functies (`functions.php`)

| Functie                                                             | Parameters          | Retourneert         | Beschrijving                    |
| ------------------------------------------------------------------- | ------------------- | ------------------- | ------------------------------- |
| `addFriend($userId, $vriendGebruikersnaam, $notitie, $status)`               | int, 3x string      | null of foutmelding | Voegt vriend toe                |
| `updateFriend($userId, $friendId, $vriendGebruikersnaam, $notitie, $status)` | int, int, 3x string | null of foutmelding | Bewerkt vriend                  |
| `deleteFriend($userId, $friendId)`                                  | int, int            | null                | Verwijdert vriend (soft delete) |
| `getFriends($userId)`                                               | int                 | array               | Haalt alle vrienden op          |

### 11.7 Schema functies (`functions.php`)

| Functie                                                                                     | Parameters          | Retourneert         | Beschrijving                    |
| ------------------------------------------------------------------------------------------- | ------------------- | ------------------- | ------------------------------- |
| `addSchedule($userId, $spelTitel, $datum, $tijd, $vrienden, $gedeeldMet)`               | int, 5x string      | null of foutmelding | Maakt gaming-schema             |
| `getSchedules($userId, $sort)`                                                              | int, string         | array               | Haalt schema's op (gesorteerd)  |
| `editSchedule($userId, $schemaId, $spelTitel, $datum, $tijd, $vrienden, $gedeeldMet)` | int, int, 5x string | null of foutmelding | Bewerkt schema                  |
| `deleteSchedule($userId, $schemaId)`                                                      | int, int            | null of foutmelding | Verwijdert schema (soft delete) |

### 11.8 Evenement functies (`functions.php`)

| Functie                                                                                                      | Parameters          | Retourneert         | Beschrijving                       |
| ------------------------------------------------------------------------------------------------------------ | ------------------- | ------------------- | ---------------------------------- |
| `addEvent($userId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink, $gedeeldMet)`            | int, 7x string      | null of foutmelding | Maakt evenement                    |
| `getEvents($userId, $sort)`                                                                                  | int, string         | array               | Haalt evenementen op (gesorteerd)  |
| `editEvent($userId, $eventId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink, $gedeeldMet)` | int, int, 7x string | null of foutmelding | Bewerkt evenement                  |
| `deleteEvent($userId, $eventId)`                                                                             | int, int            | null of foutmelding | Verwijdert evenement (soft delete) |

### 11.9 Hulpfuncties (`functions.php`)

| Functie                                                 | Parameters          | Retourneert | Beschrijving                                          |
| ------------------------------------------------------- | ------------------- | ----------- | ----------------------------------------------------- |
| `checkOwnership($pdo, $tabel, $idKolom, $id, $userId)` | PDO, 3x string, int | boolean     | Controleert of gebruiker eigenaar is                  |
| `getCalendarItems($userId)`                             | int                 | array       | Combineert schema's en evenementen, sorteert op datum |
| `getReminders($userId)`                                 | int                 | array       | Filtert evenementen met actieve herinneringen         |

### 11.10 JavaScript functies (`script.js`)

| Functie                           | Parameters     | Retourneert | Beschrijving                                |
| --------------------------------- | -------------- | ----------- | ------------------------------------------- |
| `validateLoginForm()`             | geen           | boolean     | Valideert login formulier                   |
| `validateRegisterForm()`          | geen           | boolean     | Valideert registratie formulier             |
| `validateScheduleForm()`          | geen           | boolean     | Valideert schema formulier                  |
| `validateEventForm()`             | geen           | boolean     | Valideert evenement formulier               |
| `initialiseerFuncties()`          | geen           | void        | Initialiseert interactieve pagina-elementen |
| `toonMelding(bericht, type)`      | string, string | void        | Toont toast-notificatie                     |

---

## 12. Installatie-instructies

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

## 13. Testen (K1-W4)

### 13.1 Teststrategie

**Testperiode:** 23 t/m 25 september 2025 (3 dagen, totaal **6 uur** testtijd)

De applicatie is getest op drie niveaus:

1. **Handmatige functionele tests** – Elke functie stap voor stap doorlopen (happy path en edge cases)
2. **Validatietests** – Alle 18 invoervalidaties testen met geldige en ongeldige data
3. **Beveiligingstests** – Controleren of beveiligingsmaatregelen werken (SQL-injectie, XSS, URL-manipulatie)

**Testopbouw:**
Het K1-W4 Testen document bevat **30 kerntests** (5 per user story), verdeeld over de 6 user stories. In de README zijn deze uitgebreid tot **52 individuele testcases** door extra edge cases, beveiligingstests en responsiviteitstests toe te voegen. De 30 kerntests vormen de basis; de overige 22 zijn aanvullende tests.

### 13.2 Testcases: Registratie

| Test   | Invoer                                                                     | Verwacht resultaat                                               | Geslaagd |
| ------ | -------------------------------------------------------------------------- | ---------------------------------------------------------------- | -------- |
| TC-R01 | Gebruikersnaam: "Harsha", E-mail: "harsha@test.nl", Wachtwoord: "Test1234" | Account aangemaakt, redirect naar login                          | Ja       |
| TC-R02 | Gebruikersnaam: leeg                                                       | Foutmelding: "Username mag niet leeg zijn"                       | Ja       |
| TC-R03 | Gebruikersnaam: " " (alleen spaties)                                       | Foutmelding: "Username kan niet alleen spaties zijn" (Bug #1001) | Ja       |
| TC-R04 | E-mail: "geengeldigemail"                                                  | Foutmelding: "Ongeldig e-mail formaat"                           | Ja       |
| TC-R05 | Wachtwoord: "kort" (minder dan 8 tekens)                                   | Foutmelding: "Wachtwoord moet minimaal 8 tekens zijn"            | Ja       |
| TC-R06 | E-mail die al bestaat                                                      | Foutmelding: "E-mail al geregistreerd"                           | Ja       |
| TC-R07 | Gebruikersnaam: meer dan 50 tekens                                         | Foutmelding: maximale lengte overschreden                        | Ja       |

### 13.3 Testcases: Inloggen

| Test   | Invoer                         | Verwacht resultaat                                 | Geslaagd |
| ------ | ------------------------------ | -------------------------------------------------- | -------- |
| TC-L01 | Juiste e-mail en wachtwoord    | Ingelogd, redirect naar dashboard                  | Ja       |
| TC-L02 | Juiste e-mail, fout wachtwoord | Foutmelding: "Ongeldige e-mail of wachtwoord"      | Ja       |
| TC-L03 | Niet-bestaande e-mail          | Foutmelding: "Ongeldige e-mail of wachtwoord"      | Ja       |
| TC-L04 | Beide velden leeg              | Foutmelding: "E-mail en wachtwoord zijn verplicht" | Ja       |
| TC-L05 | Al ingelogd, login.php openen  | Redirect naar index.php (dashboard)                | Ja       |

### 13.4 Testcases: Gaming-schema toevoegen

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

### 13.5 Testcases: Evenement toevoegen

| Test   | Invoer                                                          | Verwacht resultaat                  | Geslaagd |
| ------ | --------------------------------------------------------------- | ----------------------------------- | -------- |
| TC-E01 | Titel: "Fortnite Toernooi", Datum: volgende week, Tijd: "15:00" | Evenement toegevoegd                | Ja       |
| TC-E02 | Titel: leeg                                                     | Foutmelding: titel verplicht        | Ja       |
| TC-E03 | Titel: meer dan 100 tekens                                      | Foutmelding: titel te lang          | Ja       |
| TC-E04 | Beschrijving: meer dan 500 tekens                               | Foutmelding: beschrijving te lang   | Ja       |
| TC-E05 | Externe link: "geen-url"                                        | Foutmelding: "Ongeldig URL formaat" | Ja       |
| TC-E06 | Externe link: "https://twitch.tv/stream"                        | Succesvol opgeslagen met link       | Ja       |
| TC-E07 | Herinnering: "1_hour"                                           | Succesvol, herinnering actief       | Ja       |

### 13.6 Testcases: Vriend toevoegen

| Test   | Invoer                            | Verwacht resultaat                  | Geslaagd |
| ------ | --------------------------------- | ----------------------------------- | -------- |
| TC-F01 | Gebruikersnaam: "GamerPro123"     | Vriend toegevoegd                   | Ja       |
| TC-F02 | Gebruikersnaam: leeg              | Foutmelding: verplicht veld         | Ja       |
| TC-F03 | Gebruikersnaam: " " (spaties)     | Foutmelding (Bug #1001)             | Ja       |
| TC-F04 | Dezelfde vriend opnieuw toevoegen | Foutmelding: "Al vrienden"          | Ja       |
| TC-F05 | Status: "Online" selecteren       | Vriend opgeslagen met Online status | Ja       |

### 13.7 Testcases: Favoriet spel toevoegen

| Test   | Invoer                                       | Verwacht resultaat                               | Geslaagd |
| ------ | -------------------------------------------- | ------------------------------------------------ | -------- |
| TC-G01 | Titel: "Minecraft", Beschrijving: "Bouwspel" | Spel toegevoegd aan favorieten                   | Ja       |
| TC-G02 | Titel: leeg                                  | Foutmelding: titel verplicht                     | Ja       |
| TC-G03 | Zelfde spel opnieuw als favoriet             | Foutmelding: "Spel al in favorieten"             | Ja       |
| TC-G04 | Titel: "Nieuw Spel" (nog niet in database)   | Nieuw spel aangemaakt en als favoriet toegevoegd | Ja       |

### 13.8 Testcases: Bewerken en verwijderen

| Test   | Actie                                        | Verwacht resultaat                                     | Geslaagd |
| ------ | -------------------------------------------- | ------------------------------------------------------ | -------- |
| TC-D01 | Schema bewerken (eigen item)                 | Formulier met huidige waarden, succesvol bijgewerkt    | Ja       |
| TC-D02 | Evenement bewerken (eigen item)              | Formulier met huidige waarden, succesvol bijgewerkt    | Ja       |
| TC-D03 | Vriend bewerken (eigen item)                 | Succesvol bijgewerkt                                   | Ja       |
| TC-D04 | Favoriet bewerken (eigen item)               | Succesvol bijgewerkt                                   | Ja       |
| TC-D05 | Item verwijderen met bevestiging             | confirm() pop-up, daarna soft delete                   | Ja       |
| TC-D06 | Verwijderen annuleren in confirm()           | Niets gebeurd, item nog aanwezig                       | Ja       |
| TC-D07 | URL manipulatie: ander gebruiker-ID meegeven | Foutmelding: geen toestemming (eigenaarschap controle) | Ja       |

### 13.9 Testcases: Beveiliging

| Test   | Actie                                                      | Verwacht resultaat                              | Geslaagd |
| ------ | ---------------------------------------------------------- | ----------------------------------------------- | -------- |
| TC-B01 | index.php openen zonder inloggen                           | Redirect naar login.php                         | Ja       |
| TC-B02 | 30+ minuten wachten na inloggen                            | Sessie verlopen, redirect naar login.php        | Ja       |
| TC-B03 | SQL-injectie proberen in e-mail veld: `' OR 1=1 --`        | Geen effect, prepared statements blokkeren dit  | Ja       |
| TC-B04 | XSS proberen in naamveld: `<script>alert('hack')</script>` | Script wordt getoond als tekst, niet uitgevoerd | Ja       |
| TC-B05 | delete.php?type=schedule&id=999 (niet-bestaand item)       | Foutmelding: geen toestemming                   | Ja       |

### 13.10 Testcases: Responsief ontwerp

| Test    | Schermgrootte          | Verwacht resultaat                                 | Geslaagd |
| ------- | ---------------------- | -------------------------------------------------- | -------- |
| TC-RD01 | Desktop (> 992px)      | Volledige navigatie zichtbaar, tabellen breed      | Ja       |
| TC-RD02 | Tablet (768px - 992px) | Hamburger menu, tabellen scrollbaar                | Ja       |
| TC-RD03 | Mobiel (< 768px)       | Hamburger menu, knoppen full-width, leesbare tekst | Ja       |
| TC-RD04 | Klein mobiel (< 480px) | Alles past op scherm, footer leesbaar              | Ja       |

### 13.11 Testresultaten samenvatting

**Kerntests (K1-W4 document — 30 tests, 5 per user story):**

| User Story                         | Tests | Geslaagd | Gezakt | Percentage |
| ---------------------------------- | ----- | -------- | ------ | ---------- |
| US-1: Profiel met favoriete games  | 5     | 5        | 0      | 100%       |
| US-2: Vriendenlijst beheren        | 5     | 4        | 1      | 80%        |
| US-3: Speelschema's delen          | 5     | 5        | 0      | 100%       |
| US-4: Evenementen toevoegen        | 5     | 5        | 0      | 100%       |
| US-5: Herinneringen instellen      | 5     | 4        | 1      | 80%        |
| US-6: Bewerken/verwijderen         | 5     | 5        | 0      | 100%       |
| **Subtotaal kerntests**            | **30**| **28**   | **2**  | **93%**    |

De 2 gezakte tests betreffen Bug #1001 (US-2: spaties-invoer geaccepteerd) en Bug #1004 (US-5: ongeldige datum geaccepteerd). Na het oplossen van deze bugs zijn alle 30 kerntests opnieuw uitgevoerd met **100% slagingspercentage**.

**Aanvullende tests (README uitbreiding — 22 extra tests):**

| Categorie            | Aantal tests | Geslaagd | Gezakt | Percentage |
| -------------------- | ------------ | -------- | ------ | ---------- |
| Registratie (extra)  | 2            | 2        | 0      | 100%       |
| Schema (extra)       | 3            | 3        | 0      | 100%       |
| Evenement (extra)    | 2            | 2        | 0      | 100%       |
| Bewerken (extra)     | 2            | 2        | 0      | 100%       |
| Beveiliging          | 5            | 5        | 0      | 100%       |
| Responsief ontwerp   | 4            | 4        | 0      | 100%       |
| Favoriet spel (extra)| 4            | 4        | 0      | 100%       |
| **Subtotaal extra**  | **22**       | **22**   | **0**  | **100%**   |

**Totaaloverzicht (alle 52 tests na bugfixes):**

| Onderdeel            | Aantal tests | Geslaagd | Gezakt | Percentage |
| -------------------- | ------------ | -------- | ------ | ---------- |
| Kerntests (K1-W4)    | 30           | 30       | 0      | 100%       |
| Aanvullende tests    | 22           | 22       | 0      | 100%       |
| **TOTAAL**           | **52**       | **52**   | **0**  | **100%**   |

---

## 14. Verbeteren (K1-W5)

### 14.1 Gevonden fouten en verbeteringen

Tijdens het testen en reviewen van de code zijn de volgende fouten gevonden en verbeterd. De eerste 2 bugs (#1001 en #1004) zijn gevonden via de 30 kerntests (K1-W4). De laatste 2 bugs (#1005 en #1006) zijn gevonden via visuele inspectie en code review.

| Nr        | Fout / Probleem                              | Hoe gevonden                      | Oplossing                                                      | Bestand                  |
| --------- | -------------------------------------------- | --------------------------------- | -------------------------------------------------------------- | ------------------------ |
| Bug #1001 | Velden accepteerden alleen spaties           | Handmatig testen (K1-W4)          | Regex `^\s*$` controle toegevoegd                              | functions.php, script.js |
| Bug #1004 | Ongeldige datums werden geaccepteerd         | Handmatig testen met "2025-13-45" | `DateTime::createFromFormat()` met strikte controle            | functions.php, script.js |
| Bug #1005 | CSS kaarten hadden oranje achtergrond        | Visuele inspectie                 | `--glass-bg` van `orange` naar `rgba(255,255,255,0.05)`        | style.css                |
| Bug #1006 | Sessie-ID werd bij elk verzoek geregenereerd | Code review                       | `session_regenerate_id()` verplaatst naar alleen `loginUser()` | functions.php            |

### 14.2 Verbeterproces per bug

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
   -> Het wordt nu ALLEEN aangeroepen in loginUser() (regel 375).
   -> Dit is de correcte plek: alleen na succesvolle authenticatie.

STAP 4: OPNIEUW GETEST
   -> Inloggen werkt correct. Sessie-ID wordt vernieuwd bij login.
   -> Navigatie tussen pagina's werkt stabiel zonder sessie-verlies.
   -> GESLAAGD.
```

### 14.3 Mogelijke toekomstige verbeteringen

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

## 15. Examenpresentatie Hulp

### 15.1 Kerntaak-dekking

Dit overzicht toont hoe dit project alle kerntaken van het examen dekt:

| Kerntaak              | Onderdeel                           | Waar gedocumenteerd    | Bewijsmateriaal                  |
| --------------------- | ----------------------------------- | ---------------------- | -------------------------------- |
| **K1-W1 Planning**    | Projectplanning en aanpak           | PvA document (PDF)     | Tijdsplanning, user stories      |
| **K1-W2 Ontwerp**     | Functioneel en technisch ontwerp    | FO/TO documenten (PDF) | Database ontwerp, wireframes     |
| **K1-W3 Realisatie**  | Code schrijven en implementeren     | README sectie 1-12     | Alle PHP, JS, CSS, SQL bestanden |
| **K1-W4 Testen**      | Testcases uitvoeren en documenteren | README sectie 13       | 30 testcases (per user story), 93% → 100% na bugfixes |
| **K1-W5 Verbeteren**  | Fouten vinden en oplossen           | README sectie 14       | 2 bugs gevonden en gefixt (#1001, #1004) |
| **K2-W1 Overleggen**  | Communicatie over het project       | Overlegverslagen (PDF) | Bijeenkomsten, feedback          |
| **K2-W2 Presenteren** | Het project uitleggen               | README + deze sectie   | Demonstratie, uitleg             |
| **K2-W3 Reflectie**   | Terugkijken op het proces           | Reflectieverslag (PDF) | Wat ging goed/fout               |

### 15.2 Belangrijkste punten om uit te leggen aan de examinator

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
- 5 van de 6 tabellen hebben `deleted_at` voor **soft delete**: data wordt niet echt verwijderd. De koppeltabel `UserGames` gebruikt harde delete (het spel zelf blijft in `Games`)
- Ik gebruik **foreign keys met CASCADE**: als een gebruiker verwijderd wordt, worden automatisch al hun vrienden, schema's en evenementen ook verwijderd
- Ik heb **indexen** toegevoegd op veelgebruikte kolommen voor snellere queries"

#### Punt 5: Bugfixes (hoe ik fouten heb gevonden en opgelost)

"Ik heb 2 bugs gevonden en opgelost tijdens het testproces (K1-W4):

1. **Bug #1001**: Velden accepteerden alleen spaties. Opgelost met `trim()` + regex `/^\s*$/` controle.
2. **Bug #1004**: Ongeldige datums werden geaccepteerd. Opgelost met `DateTime::createFromFormat()` strikte vergelijking.

Voor elke bug heb ik het 5-stappenproces gevolgd: **ontdekken → oorzaak analyseren → oplossen → hertesten → documenteren**."

### 15.3 Veelgestelde examenvragen en antwoorden

**V: "Waarom heb je PHP gekozen en niet een ander framework?"**
A: "PHP is de taal die we geleerd hebben in de opleiding. Het werkt goed met XAMPP als lokale omgeving en met MySQL als database. Voor een MBO-project is vanilla PHP geschikt omdat het de basis laat zien zonder afhankelijkheid van frameworks."

**V: "Hoe voorkom je SQL-injectie in jouw applicatie?"**
A: "Ik gebruik PDO prepared statements. In plaats van de gebruikersinvoer direct in de query te plaatsen, gebruik ik `:named` parameters. De database-engine verwerkt de invoer apart van de query, waardoor kwaadaardige SQL-code nooit uitgevoerd wordt. Voorbeeld: `$stmt->execute(['email' => $emailAdres]);`"

**V: "Wat is het verschil tussen client-side en server-side validatie?"**
A: "Client-side validatie draait in de browser met JavaScript. Het geeft snelle feedback, maar kan uitgeschakeld worden. Server-side validatie draait op de server met PHP. Dit kan NIET omzeild worden. Ik gebruik BEIDE: JavaScript voor gebruiksgemak, PHP voor veiligheid."

**V: "Wat is soft delete en waarom gebruik je het?"**
A: "Bij soft delete markeer ik een record als verwijderd door `deleted_at` op de huidige datum te zetten. De data blijft in de database staan. Alle queries filteren op `WHERE deleted_at IS NULL` om verwijderde items te verbergen. Het voordeel is dat data hersteld kan worden als iemand per ongeluk iets verwijdert. De enige uitzondering is de koppeltabel `UserGames` (favorieten): daar gebruik ik harde delete omdat het alleen een relatie-koppeling is — het spel zelf blijft altijd bewaard in de `Games` tabel."

**V: "Hoe werkt de sessie timeout?"**
A: "Na inloggen wordt `$_SESSION['last_activity']` opgeslagen met de huidige tijd. Bij elk paginaverzoek controleert `checkSessionTimeout()` of het verschil met de huidige tijd groter is dan 1800 seconden (30 minuten). Als dat zo is, wordt de sessie vernietigd en de gebruiker naar de loginpagina gestuurd."

**V: "Wat is het Singleton-patroon in je database-verbinding?"**
A: "Het Singleton-patroon zorgt ervoor dat er slechts een databaseverbinding wordt aangemaakt, ongeacht hoe vaak `getDBConnection()` wordt aangeroepen. De eerste keer maakt het een PDO-object aan en slaat het op in een statische variabele. Bij volgende aanroepen retourneert het dezelfde verbinding. Dit bespaart geheugen en voorkomt te veel open verbindingen."

**V: "Hoe heb je de applicatie getest?"**
A: "Ik heb 30 handmatige testcases uitgevoerd, verdeeld per user story: 5 tests voor profiel met favoriete games, 5 voor vriendenlijst beheren, 5 voor speelschema's delen, 5 voor evenementen toevoegen, 5 voor herinneringen instellen, en 5 voor bewerken/verwijderen. In de eerste ronde waren 28 van de 30 tests geslaagd (93%). Na het oplossen van Bug #1001 en Bug #1004 zijn alle 30 tests opnieuw uitgevoerd met 100% slagingspercentage. Daarnaast heb ik de performance getest (laadtijd 1,8 seconden) en de responsiviteit op een Samsung Galaxy S21."

**V: "Wat zou je anders doen als je opnieuw zou beginnen?"**
A: "Ik zou eerder beginnen met testen en een gestructureerder testplan opzetten. Ook zou ik vanaf het begin een CSS-framework configureren voor het glassmorphism-thema, in plaats van achteraf variabelen te moeten corrigeren. Verder zou ik overwegen om een MVC-structuur te gebruiken voor betere scheiding van logica en presentatie."

---

## 16. Onderlegger C24 – Examen Checklistvragen (Crebo 25998)

> **Student:** Harsha Vardhan Kanaparthi | **Studentnummer:** 2195344
> **Opleiding:** MBO-4 Software Development (Crebo 25998)
> **Project:** GamePlan Scheduler – Gaming Planning Webapp
> **Stagebedrijf:** [zie PDF-documenten]
> **Datum:** 2025

Dit hoofdstuk beantwoordt alle examencriteria per werkproces (K1-W1 t/m K2-W3) met concrete verwijzingen naar de code, README-secties en PDF-documenten. De examinator kan dit hoofdstuk gebruiken als afvink-index om snel alle bewijsstukken terug te vinden.

---

### 16.1 K1-W1: Oriënteren / Planning

#### Criterium 1: De student beschrijft de opdrachtgever, context en aanleiding van het project

**Antwoord:**
GamePlan Scheduler is ontwikkeld als individueel project voor de MBO-4 opleiding Software Development (Crebo 25998) door Harsha Kanaparthi (studentnummer 2195344). De projectperiode liep van **2 september 2025 tot 30 september 2025** (1 maand). De aanleiding is dat jonge gamers vaak moeite hebben om gaming-sessies te coördineren met vrienden. Er bestaan wel agenda-apps, maar geen specifieke tool gericht op het plannen van gaming-sessies met functies als vriendenlijsten, favoriete spellen en gedeelde schema's.

**Betrokkenen:**

| Rol | Persoon | Verantwoordelijkheid |
| --- | --- | --- |
| Ontwikkelaar | Harsha Kanaparthi | Plant, codeert en test alles |
| Begeleider | Marius Restua | Geeft advies en feedback |
| Gebruikers | Jonge gamers | Testen de app en geven feedback |

**Bewijs:** Zie README sectie 1 (Projectbeschrijving) en PDF `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student beschrijft het doel van het project en de doelgroep

**Antwoord:**

**Doelgroep:** Jonge gamers die regelmatig online spelen en hun sessies willen plannen.

**Probleem:** Jonge gamers vinden het lastig om samen te plannen. Ze missen belangrijke game-momenten en beheren hun tijd niet goed.

**Doel (SMART-methode):**

| SMART | Beschrijving |
| --- | --- |
| **Specifiek** | Een webapp voor gamers om profielen, vrienden en evenementen te beheren |
| **Meetbaar** | Gamers gebruiken de app minimaal 3 keer per week |
| **Acceptabel** | Past bij jonge gamers en is haalbaar om te bouwen |
| **Realistisch** | Met mijn PHP-kennis kan dit in 1 maand worden gebouwd |
| **Tijdsgebonden** | Klaar op 30 september 2025 |

**Succescriteria:** De app is succesvol als gamers hem gebruiken en het hen helpt om beter samen te gamen, hun tijd beter te beheren en geen belangrijke game-momenten te missen.

**Bewijs:** Zie README sectie 1 en sectie 7 (Functionele flows), PDF `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 3: De student beschrijft de functionele eisen (user stories / features)

**Antwoord:**
De applicatie is gebouwd op basis van 6 gebruikersverhalen (user stories):

| Nr | User Story | Functionaliteit | Status |
| --- | --- | --- | --- |
| US-1 | Profiel met favoriete games | Gamers kunnen een profiel aanmaken met hun favoriete games | Gerealiseerd |
| US-2 | Vriendenlijst beheren | Vrienden toevoegen om contact te houden | Gerealiseerd |
| US-3 | Speelschema's delen | Speelschema's delen in een kalender | Gerealiseerd |
| US-4 | Evenementen toevoegen | Evenementen plannen zoals toernooien of meetups | Gerealiseerd |
| US-5 | Herinneringen instellen | Herinneringen instellen zodat je niets vergeet | Gerealiseerd |
| US-6 | Bewerken en verwijderen | Alles bewerken of verwijderen wanneer nodig | Gerealiseerd |

**Bewijs:** Zie README sectie 7 (Functionele Flows), demovideo `K1-W3-DEMO VIDEO.mp4`.

#### Criterium 4: De student beschrijft de technische keuzes met onderbouwing

**Antwoord:**

| Technologie | Keuze | Onderbouwing |
| --- | --- | --- |
| Backend | PHP 7.4+ (vanilla) | Geleerd in de opleiding, toont basisvaardigheden zonder framework |
| Database | MySQL met InnoDB | Relationele database voor gestructureerde data, ondersteunt foreign keys |
| Database-toegang | PDO met prepared statements | Veiligste methode tegen SQL-injectie, ondersteunt named parameters |
| Frontend CSS | Bootstrap 5.3.3 | Snel responsive design, werkt op computer en mobiele telefoon |
| Frontend JS | Vanilla JavaScript | Geen externe afhankelijkheden nodig, voor dropdowns en validatie |
| Ontwikkelomgeving | XAMPP | Alles-in-één pakket (Apache + MySQL + PHP), eenvoudig op te zetten |
| Versiebeheer | Git + GitHub | Industriestandaard, dagelijks commits op GitHub |
| Ontwerp-thema | Donker + Glassmorphism | Past bij de gaming-doelgroep, donkere kleuren zijn minder vermoeiend voor gamers die 's avonds spelen |

**Bewijs:** Zie README sectie 4 (Technische specificaties).

#### Criterium 5: De student heeft een planning gemaakt met taken, uren en deadlines

**Antwoord:**
Er is een gedetailleerde planning gemaakt met **12 taken** verdeeld over **4 weken**, totaal **49 uur werk** (meer dan de vereiste 40 uur).

**Takenplanning:**

| Taak | Beschrijving | Periode | Week |
| --- | --- | --- | --- |
| Taak 1-2 | Omgeving en database opzetten | 2-4 september 2025 | Week 1 |
| Taak 3 | Inloggen en sessies maken | 5-7 september 2025 | Week 1 |
| Taak 4 | Basis ontwerp maken | 8-9 september 2025 | Week 2 |
| Taak 5 | Profielbeheer bouwen | 10-13 september 2025 | Week 2 |
| Taak 6 | Vriendenlijst maken | 14-16 september 2025 | Week 3 |
| Taak 7 | Kalender voor speelschema's | 17-18 september 2025 | Week 3 |
| Taak 8 | Evenementenbeheer | 19-21 september 2025 | Week 3 |
| Taak 9 | Herinneringen instellen | 22-23 september 2025 | Week 4 |
| Taak 10 | Testen op bugs | 24-25 september 2025 | Week 4 |
| Taak 11 | Design checken voor mobiel | 26-27 september 2025 | Week 4 |
| Taak 12 | Online zetten op server | 28-30 september 2025 | Week 4 |

**Weekoverzicht:**

| Week | Periode | Mijlpaal |
| --- | --- | --- |
| Week 1 | 02-09 t/m 07-09 | Start, database en backend af |
| Week 2 | 08-09 t/m 13-09 | Frontend en profielen klaar |
| Week 3 | 14-09 t/m 21-09 | Vrienden, schema's en evenementen doen |
| Week 4 | 22-09 t/m 30-09 | Herinneringen, testen en online zetten |

**Prioriteiten (MoSCoW-methode):**

| Categorie | Items | Betekenis |
| --- | --- | --- |
| **Must have** (M) | Inloggen, database, profiel, evenementen, testen, online zetten | Dit moet werken |
| **Should have** (S) | Vriendenlijst, speelschema's | Dit maakt het beter |
| **Could have** (C) | Herinneringen, mobiel design | Dit is extra |
| **Won't have** (W) | Thema's en andere extra's | Komt later |

**Bewijs:** PDF `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf` (planning), PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf` (projectlog met uren).

#### Criterium 6: De student beschrijft wijzigingen op de oorspronkelijke planning

**Antwoord:**
Tijdens het project zijn de volgende wijzigingen doorgevoerd ten opzichte van de oorspronkelijke planning:

1. **Kalenderweergave:** Oorspronkelijk gepland als losse pagina, uiteindelijk geïntegreerd in het dashboard voor een beter overzicht
2. **Herinneringsfunctie:** Niet in de oorspronkelijke planning, later toegevoegd na feedback van de stagebegeleider
3. **Glassmorphism-thema:** Het oorspronkelijke ontwerp gebruikte standaard Bootstrap-kleuren; na de eerste iteratie is een custom gaming-thema ontwikkeld
4. **Soft delete:** Oorspronkelijk was harde delete (fysiek verwijderen) gepland; na overleg met de begeleider is gekozen voor soft delete (logisch verwijderen) voor betere dataveiligheid

**Omgang met vertragingen:** Als iets uitloopt, worden extra's (Could have items) opzij geschoven om de Must have items op tijd af te krijgen.

**Bewijs:** PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 7: De student heeft overleg gevoerd met opdrachtgever/begeleider

**Antwoord:**
Er zijn **3 geplande overlegmomenten** geweest met stagebegeleider Marius Restua:

| Datum | Onderwerp | Resultaat |
| --- | --- | --- |
| 7 september 2025 | Backend check | Database en inloggen goedgekeurd |
| 16 september 2025 | Vriendenlijst feedback | Feedback verwerkt in vriendenlijst |
| 27 september 2025 | Design check voor mobiel | Responsive design goedgekeurd |

**Voortgangsbewaking:**
- Elke week checken of alles op tijd gaat
- Bij vertraging worden extra's (Could have) opzij geschoven
- Wekelijks contact via chat en overlegmomenten

**Bewijs:** PDF `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`, PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`.

---

### 16.2 K1-W2: Ontwerpen

#### Criterium 1: De student heeft een functioneel ontwerp (FO) opgesteld

**Antwoord:**
Het functioneel ontwerp beschrijft WAT de applicatie doet vanuit gebruikersperspectief. Het ontwerp volgt op het planningsdocument uit week 1 en beschrijft alle schermen per user story.

**Schermen per user story (6 stuks, 13 schermen totaal):**

| User Story | Scherm | Beschrijving |
| --- | --- | --- |
| US-1: Profiel maken | Scherm 1 | Formulier met naam, games, e-mail, wachtwoord |
| US-1: Profiel maken | Scherm 2 | Bevestiging "Profiel gemaakt!" met knop naar home |
| US-2: Vrienden toevoegen | Scherm 1 | Lijst met huidige vrienden en online status |
| US-2: Vrienden toevoegen | Scherm 2 | Zoekveld om nieuwe vriend toe te voegen |
| US-3: Speelschema's delen | Scherm 1 | Kalender met gekleurde blokjes per dag |
| US-3: Speelschema's delen | Scherm 2 | Formulier met game, tijd, datum, vrienden selecteren |
| US-4: Evenementen toevoegen | Scherm 1 | Lijst met aankomende evenementen als kaarten |
| US-4: Evenementen toevoegen | Scherm 2 | Formulier met titel, datum, tijd, beschrijving |
| US-5: Herinneringen instellen | Scherm 1 | Dropdown in formulier (1 uur ervoor, 1 dag ervoor) |
| US-5: Herinneringen instellen | Scherm 2 | Pop-up melding als herinnering komt |
| US-6: Bewerken/verwijderen | Scherm 1 | Details pagina met knoppen Bewerken en Verwijderen |
| US-6: Bewerken/verwijderen | Scherm 2 | Bewerken formulier met ingevulde velden |
| US-6: Bewerken/verwijderen | Scherm 3 | Bevestiging "Zeker weten?" met Ja/Nee |

**Alle invoervelden per formulier** zijn beschreven met verwachte datatypes en beperkingen.
**Foutscenario's** per functie zijn beschreven (wat gebeurt er bij ongeldige invoer?).

**Bewijs:** README sectie 7 (alle 10 Functionele Flows), PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft een technisch ontwerp (TO) opgesteld

**Antwoord:**
Het technisch ontwerp beschrijft HOE de applicatie werkt vanuit technisch perspectief.

**Lay-out en visueel ontwerp:**

| Onderdeel | Specificatie |
| --- | --- |
| Header | Bovenaan, logo links, menu in midden, profiel rechts, **hoogte 80 pixels**, blijft altijd zichtbaar |
| Kleuren | Donker (zwart en blauw) voor game-gevoel, witte tekst voor leesbaarheid |
| Hoofdgedeelte | 80% van breedte, daar staat de inhoud |
| Footer | Onderaan, **50 pixels**, met copyright en privacy links |
| Knoppen | **40 pixels hoog**, groot genoeg voor mobiel |

**Navigatie (6 knoppen in header):**

| Knop | Functie |
| --- | --- |
| Home | Dashboard met overzicht |
| Profiel | Favoriete spellen beheren |
| Vrienden | Vriendenlijst beheren |
| Schema's | Gaming-schema's beheren |
| Evenementen | Evenementen beheren |
| Uitloggen | Sessie vernietigen |

**Navigatiestructuur:** Lineair - van home naar andere pagina's en terug. Op mobiel klapt het menu in tot een hamburger-lijst. Icoontjes bij knoppen voor sneller herkennen.

**Architectuur:** Separation of Concerns met 4 lagen:
- Datalaag (`db.php` - PDO Singleton verbinding)
- Logicalaag (`functions.php` - alle validatie, authenticatie, CRUD)
- Presentatielaag (PHP-pagina's + `header.php`/`footer.php`)
- Client-laag (`script.js` + `style.css`)

**Techniek:**
- PHP voor backend logica (inloggen, data verwerken)
- MySQL voor database (alle data opslaan)
- HTML en CSS voor lay-out en ontwerp
- JavaScript voor interacties (dropdowns, validatie, pop-ups)
- Responsive: werkt op computer en mobiele telefoon

**Bewijs:** README secties 4, 5, 6, 8, 9 en PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 3: De student heeft minimaal 3 schematechnieken toegepast

**Antwoord:**

**Schematechniek 1: Entity-Relationship Diagram (ERD)**
Het databaseontwerp bevat 6 tabellen met relaties:
- Users (hoofdtabel) `1 ──── N` Friends
- Users `1 ──── N` Schedules
- Users `1 ──── N` Events
- Users `N ──── M` Games via UserGames (veel-op-veel koppeltabel)
- Elke tabel met alle kolommen, datatypes, primary keys en foreign keys

**Bewijs:** README sectie 5 (Database Structuur), PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

**Schematechniek 2: Flowcharts / Code Flow Diagrammen**
Gedetailleerde sequentiediagrammen die de interactie tussen Browser, Server en Database tonen:
- **Login Code Flow** (README sectie 8.1): Browser → login.php → loginUser() → SELECT/password_verify → sessie aanmaken → redirect
- **Dashboard Code Flow** (README sectie 8.2): Browser → index.php → 5 parallelle queries → HTML renderen
- **Delete Code Flow** (README sectie 8.3): Browser → confirm() → delete.php → checkOwnership() → soft delete → redirect

**Bewijs:** README sectie 8 (Code Flow Diagrammen) met drie volledige ASCII-diagrammen.

**Schematechniek 3: Activiteitendiagrammen (Functionele Flows)**
Voor alle 10 hoofdfunctionaliteiten zijn stap-voor-stap activiteitendiagrammen gemaakt:
- Startpunt → Invoer → Validatie → Beslispunt (geldig/ongeldig) → Database-actie → Resultaat → Eindpunt

**Bewijs:** README sectie 7 (Functionele Flows) met alle 10 flows.

**Schematechniek 4: Use Case Diagram**
- **Acteur:** Gamer
- **Use Cases:** Inloggen, Profiel maken, Vriend toevoegen, Schema delen, Evenement toevoegen, Herinnering instellen, Bewerken/verwijderen
- **Inloggen is verplicht** voor alle andere acties

**Bewijs:** PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 4: De student motiveert zijn ontwerpkeuzes

**Antwoord:**

| Ontwerpkeuze | Motivatie |
| --- | --- |
| Donkere kleuren | Gamers spelen vaak 's avonds, donker is minder vermoeiend voor de ogen |
| Ronde hoeken | Vriendelijk oogt, past bij jonge gebruikers |
| Icoontjes bij knoppen | Sneller herkennen voor gamers die haast hebben |
| Checkboxes bij vrienden | Makkelijk delen met groepen |
| Dropdown voor herinneringen | Bespaart stappen, voorkomt typefouten |
| Bevestiging bij verwijderen | Voorkomt ongelukken |
| PDO Singleton patroon | Voorkomt meerdere databaseverbindingen per pagina, bespaart geheugen |
| Soft delete (deleted_at) | Data wordt nooit fysiek verwijderd, biedt herstelmogelijkheid bij ongelukken |
| Dubbele validatie (JS + PHP) | Client-side voor snelle feedback, server-side voor veiligheid |
| Bootstrap 5.3.3 | Snel responsive design, jQuery-vrij |
| Aparte header.php/footer.php | DRY-principe: navigatie en footer op 1 plek beheerd |

**Bewijs:** README secties 4, 5, 9 en PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 5: De student heeft wireframes of mockups gemaakt

**Antwoord:**
Er zijn screenshots gemaakt van alle pagina's van de applicatie:

| Screenshot | Pagina | Locatie |
| --- | --- | --- |
| InlogPagina.png | Inlogformulier | `Demo Fotos/Software Fotos/` |
| Account Aanmaak Pagina.png | Registratieformulier | `Demo Fotos/Software Fotos/` |
| HomePagina.png | Dashboard/overzicht | `Demo Fotos/Software Fotos/` |
| Profile Add Pagina.png | Profiel met favoriet toevoegen | `Demo Fotos/Software Fotos/` |
| Profile Edit Pagina.png | Profiel bewerken | `Demo Fotos/Software Fotos/` |
| Add Friend Pagina.png | Vriend toevoegen formulier | `Demo Fotos/Software Fotos/` |
| Edit Friend Pagina.png | Vriend bewerken formulier | `Demo Fotos/Software Fotos/` |
| Schedule Add Pagina.png | Schema toevoegen formulier | `Demo Fotos/Software Fotos/` |
| Edit Schedule Pagina.png | Schema bewerken formulier | `Demo Fotos/Software Fotos/` |
| Events Add Pagina.png | Evenement toevoegen formulier | `Demo Fotos/Software Fotos/` |
| Events Edit Pagina.png | Evenement bewerken formulier | `Demo Fotos/Software Fotos/` |
| Delete Button.png | Verwijderbevestiging | `Demo Fotos/Software Fotos/` |

**Bewijs:** Map `Demo Fotos/Software Fotos/` (12 PNG-screenshots).

#### Criterium 6: Privacy, security, ethiek en usability

**Privacy (AVG-compliant):**
- Data opslaan in MySQL met wachtwoordbeveiliging
- Wachtwoorden gehasht met bcrypt (niet leesbaar opgeslagen)
- Gamers kunnen eigen data verwijderen (soft delete)
- Geen onnodige data opslaan, alleen wat nodig is
- Link in footer naar privacy tekst (`privacy.php`)
- Volgt AVG-regels

**Security (beveiliging, 10 maatregelen):**
- Inloggen met e-mail en wachtwoord (bcrypt hashing)
- Sessies voor toegang (je blijft ingelogd)
- Sessies verlopen na 30 minuten stilzitten
- PDO prepared statements tegen SQL-injecties
- `safeEcho()` met htmlspecialchars tegen XSS-aanvallen
- `checkOwnership()` eigenaarschap controle
- `session_regenerate_id(true)` alleen bij login
- Soft delete (`deleted_at` timestamp)
- Foutmasking (generieke berichten aan gebruiker)
- Inlogcontrole per pagina (`isLoggedIn()`)

**Ethiek (eerlijk gebruik):**
- Iedereen kan de app gebruiken, geen uitsluiting
- Geen reclame die aanzet tot te veel spelen
- Data niet delen met anderen zonder toestemming
- Eerlijk in privacytekst waar data voor gebruikt wordt
- Positief voor jonge gebruikers

**Usability (gemakkelijk te gebruiken):**
- Grote knoppen (minstens 40 pixels) voor mobiel
- Duidelijke labels bij velden
- Kleuren: blauw voor knoppen, rood voor waarschuwingen
- Feedback na actie: melding "Gelukt!" na toevoegen
- Getest op mobiel: alles schaalt mee (responsive)

**Bewijs:** README sectie 9 (Beveiligingsmaatregelen), `privacy.php`, PDF `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf`.

---

### 16.3 K1-W3: Realiseren

#### Criterium 1: De student heeft een werkende applicatie opgeleverd conform het ontwerp

**Antwoord:**
De GamePlan Scheduler is een volledig werkende webapplicatie. De projectperiode was **28 dagen** (2 september tot 30 september 2025), met **49 uur coderen** (meer dan de vereiste 40 uur). Elke dag is genoteerd in het projectlog.

**Gebruikte technieken:**

| Technologie | Versie | Doel |
| --- | --- | --- |
| PHP | 8.1 | Backend logica (de logica achter de schermen) |
| MySQL | 8.0 | Database (waar alle data wordt opgeslagen) |
| HTML | 5 | Structuur van de pagina's |
| Bootstrap | 5.3.3 | Responsive design (werkt op mobiel en computer) |
| CSS | 3 | Styling (donker thema in zwart en blauw) |
| JavaScript | ES6 | Interacties (validatie, pop-ups, herinneringen) |

**Database opbouw (6 tabellen):**

| Tabel | Beschrijving |
| --- | --- |
| Users | Gebruikersinfo (naam, e-mail, wachtwoord) |
| Games | Lijst met games die je kunt kiezen |
| UserGames | Koppelt gebruikers aan hun favoriete games |
| Friends | Vriendenlijst met online status |
| Schedules | Speelschema's met datum en tijd |
| Events | Evenementen met beschrijving en herinneringen |

**Overzicht van gerealiseerde functionaliteiten:**

| Functionaliteit | PHP-bestand(en) | Functies in functions.php | Status |
| --- | --- | --- | --- |
| Registreren | register.php | registerUser() | Werkend |
| Inloggen | login.php | loginUser() | Werkend |
| Uitloggen | logout.php | logout() | Werkend |
| Favoriete spellen beheren | profile.php, edit_favorite.php, delete.php | addFavoriteGame(), updateFavoriteGame(), deleteFavoriteGame() | Werkend |
| Vriendenlijst beheren | add_friend.php, edit_friend.php, delete.php | addFriend(), updateFriend(), deleteFriend() | Werkend |
| Gaming-schema's beheren | add_schedule.php, edit_schedule.php, delete.php | addSchedule(), editSchedule(), deleteSchedule() | Werkend |
| Evenementen beheren | add_event.php, edit_event.php, delete.php | addEvent(), editEvent(), deleteEvent() | Werkend |
| Dashboard met kalender | index.php | getCalendarItems(), getReminders() | Werkend |
| Profiel met statistieken | profile.php | getFavoriteGames(), getFriends() | Werkend |
| Sorteren | index.php | getSchedules($sort), getEvents($sort) | Werkend |

**Bewijs:** Alle bronbestanden in de repository, README secties 7-8, demovideo `K1-W3-DEMO VIDEO.mp4`, PDF `K1 W3 Realisatie-Realisatie verslag-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft minimaal 40 uur aan het project gewerkt

**Antwoord:**
Totaal **49 uur** besteed (meer dan de vereiste 40 uur). Het projectlog toont **29 taken** over **28 dagen**, allemaal voltooid op tijd.

| Onderdeel | Omvang | Geschatte uren |
| --- | --- | --- |
| functions.php | 978 regels code | 15+ uur |
| script.js | 363 regels code | 8+ uur |
| style.css | 822 regels CSS | 10+ uur |
| database.sql | 229 regels met commentaar | 5+ uur |
| 15 PHP-pagina's | ±130 regels per pagina gemiddeld | 12+ uur |
| README.md | 2900+ regels documentatie | 10+ uur |
| Testen | 30 testcases uitvoeren (per user story) | 5+ uur |
| Bugfixes | 2 bugs vinden en oplossen (#1001, #1004) | 3+ uur |
| **Totaal** | **7400+ regels code + docs** | **49+ uur** |

**Week voor week voortgang:**

| Week | Periode | Wat is gedaan |
| --- | --- | --- |
| Week 1 | 02-09 t/m 07-09 | Omgeving, database, inloggen klaar |
| Week 2 | 08-09 t/m 13-09 | Frontend, profielbeheer af |
| Week 3 | 14-09 t/m 21-09 | Vrienden, schema's, evenementen doen |
| Week 4 | 22-09 t/m 30-09 | Herinneringen, testen, online zetten |

**Bewijs:** PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 3: De student past best practices toe - DRY (Don't Repeat Yourself)

**Antwoord:**

| DRY-toepassing | Waar | Toelichting |
| --- | --- | --- |
| `getDBConnection()` Singleton | db.php | Eén databaseverbinding hergebruikt door alle functies |
| `safeEcho()` helper | functions.php | Eén centrale functie voor XSS-bescherming op ELKE uitvoer |
| `validateRequired()` hergebruik | functions.php | Alle verplichte-veld-controles gebruiken dezelfde functie |
| `checkOwnership()` centraal | functions.php | Eén functie voor eigenaarschap-controle |
| `header.php` / `footer.php` | header.php, footer.php | Navigatie en footer op 1 plek, automatisch op alle pagina's |
| `setMessage()` / `getMessage()` | functions.php | Eén berichtsysteem voor alle succes- en foutmeldingen |
| CSS-variabelen | style.css | Kleuren als variabelen, 1x wijzigen geldt overal |

#### Criterium 4: De student past best practices toe - SRP (Single Responsibility Principle)

**Antwoord:**

| Bestand/Functie | Verantwoordelijkheid | Doet NIET |
| --- | --- | --- |
| `db.php` | Alleen databaseverbinding beheren | Geen queries, geen validatie |
| `functions.php` | Alleen businesslogica (validatie + CRUD) | Geen HTML, geen routing |
| `header.php` | Alleen navigatie-HTML renderen | Geen logica, geen queries |
| `login.php` | Alleen loginformulier tonen en afhandelen | Geen registratie, geen CRUD |
| `script.js` | Alleen client-side validatie en UI-interactie | Geen server-communicatie |
| `style.css` | Alleen visuele styling | Geen logica |

#### Criterium 5: De student hanteert correcte naamgevingsconventies

**Antwoord:**

| Type | Conventie | Voorbeelden |
| --- | --- | --- |
| PHP-functies | camelCase | `addFriend()`, `getFavoriteGames()`, `checkSessionTimeout()` |
| PHP-variabelen | camelCase | `$vriendGebruikersnaam`, `$gedeeldMetStr`, `$spelTitel`, `$userId` |
| Database-tabellen | PascalCase | `Users`, `Games`, `UserGames`, `Friends`, `Schedules`, `Events` |
| Database-kolommen | snake_case | `user_id`, `deleted_at`, `friend_username` |
| CSS-klassen | kebab-case | `.glass-card`, `.btn-gaming`, `.nav-link` |
| CSS-variabelen | kebab met prefix | `--gaming-primary`, `--glass-bg`, `--glass-border` |
| JS-functies | camelCase | `validateLoginForm()`, `toonMelding()`, `initialiseerFuncties()` |
| Bestandsnamen | snake_case | `add_friend.php`, `edit_schedule.php` |

#### Criterium 6: De student heeft code voorzien van commentaar

**Antwoord:**
Alle bestanden bevatten Nederlands commentaar:
- **functions.php:** Functiebeschrijvingen boven elke functie
- **database.sql:** 229 regels met Nederlands commentaar bij elke tabel
- **style.css:** 14 benoemde secties met sectieheaders
- **script.js:** Inline uitleg bij elke validatiefunctie

#### Criterium 7: De student gebruikt versiebeheer (Git)

**Antwoord:**
- **Repository:** GitHub
- **Branch:** `main`
- **Werkwijze:** Dagelijks commits op GitHub
- **Branches voor features:** Bijv. feature/calendar
- **Minstens 5 oude versies** bewaard voor rollback
- De commit-geschiedenis toont de geleidelijke opbouw: initiële structuur → databaseontwerp → authenticatie → CRUD-functies → validatie → styling → testen → bugfixes → documentatie

**Bewijs:** GitHub repository en screenshot `Demo Fotos/VersieBeheer/Versiebeheer.png`.

#### Criterium 8: De student handelt randgevallen (edge cases) correct af

**Antwoord:**

| Randgeval | Afhandeling | Validatie-ID |
| --- | --- | --- |
| Invoer met alleen spaties " " | Regex `/^\s*$/` detecteert en weigert (Bug #1001 fix) | V1 |
| Onmogelijke datum "2025-13-45" | `DateTime::createFromFormat()` met stricte vergelijking (Bug #1004 fix) | V6 |
| Datum in het verleden | Vergelijking met `date('Y-m-d')` blokkeert verlopen datums | V7 |
| Kommagescheiden lijst met lege items | Explode + trim + filter op lege strings | V10 |
| SQL-injectie via invoervelden | PDO prepared statements met named parameters | B2 |
| XSS via invoervelden | `safeEcho()` met htmlspecialchars op alle uitvoer | B3 |
| Verwijderen van andermans data via URL | `checkOwnership()` controle bij elke bewerk/verwijder-actie | B6 |
| Sessie-verlopen na inactiviteit | 30-minuten timeout met automatische redirect naar login | B5 |
| Dubbele registratie met zelfde e-mail | UNIQUE constraint + PHP-controle | V11 |
| Dubbele favoriet (zelfde spel) | PHP-controle retourneert "Spel al in favorieten" | V12 |
| Dubbele vriend (zelfde gebruikersnaam) | PHP-controle retourneert "Al vrienden" | V13 |

**Bewijs:** README sectie 6 (validaties), sectie 9 (beveiligingsmaatregelen), sectie 14 (bugfixes).

#### Criterium 9: De student implementeert foutafhandeling

**Antwoord:**
Er zijn drie lagen van foutafhandeling:

**Laag 1 - Functie-retourwaarden:** Alle functies retourneren `null` bij succes of een foutmelding-string bij fout.

**Laag 2 - Database try-catch:** Alle database-operaties staan in try-catch blokken. Technische fouten worden gelogd met `error_log()`, gebruikers zien alleen generieke berichten.

**Laag 3 - Sessiebericht-systeem (PRG-patroon):** Berichten worden opgeslagen in sessie en na redirect getoond via Bootstrap alerts.

**Bewijs:** README sectie 10 (Foutafhandeling).

#### Criterium 10: De student implementeert dubbele validatie (client-side + server-side)

**Antwoord:**

**Client-side validatie (JavaScript - script.js):**
- `validateLoginForm()` - e-mail en wachtwoord verplicht
- `validateRegisterForm()` - gebruikersnaam, e-mail, wachtwoord (min. 8 tekens)
- `validateScheduleForm()` - speltitel, datum (toekomst), tijd (formaat)
- `validateEventForm()` - titel, datum, tijd, optionele URL-validatie

**Server-side validatie (PHP - functions.php):**
- `validateRequired()` - leeg + spaties + maxlengte
- `validateDate()` - formaat + echte datum + toekomst
- `validateTime()` - UU:MM formaat
- `validateEmail()` - e-mailformaat
- `validateUrl()` - URL-formaat (optioneel veld)
- `validateCommaSeparated()` - kommagescheiden lijst zonder lege items

**Waarom beide nodig?** Een gebruiker kan JavaScript uitschakelen. De server-side validatie is de echte "poortwachter" en kan NIET omzeild worden. Client-side validatie is puur voor gebruiksgemak (snellere feedback).

**Bewijs:** README sectie 6 (alle 18 validatieregels), `functions.php` (server-side), `script.js` (client-side).

#### Criterium 11: De student implementeert beveiligingsmaatregelen

**Antwoord:**
De applicatie bevat **10 beveiligingsmaatregelen:**

| Nr | Maatregel | Implementatie | Beschermt tegen |
| --- | --- | --- | --- |
| B1 | Wachtwoord hashing | `password_hash(PASSWORD_BCRYPT)` | Wachtwoorddiefstal |
| B2 | Prepared statements | PDO met `:named` parameters | SQL-injectie |
| B3 | Output escaping | `safeEcho()` op alle gebruikersdata | XSS-aanvallen |
| B4 | Sessie regeneratie | `session_regenerate_id(true)` na login | Sessiefixatie |
| B5 | Sessie timeout | 30 min inactiviteit → automatisch uitloggen | Onbeheerde sessies |
| B6 | Eigenaarschap controle | `checkOwnership()` bij bewerken/verwijderen | Ongeautoriseerde toegang |
| B7 | Inputvalidatie | Server-side + client-side dubbel | Ongeldige data |
| B8 | Foutmasking | `error_log()` + generieke gebruikersmelding | Informatielekken |
| B9 | Soft delete | `deleted_at` timestamp i.p.v. fysiek DELETE | Dataverlies |
| B10 | Inlogcontrole per pagina | `isLoggedIn()` check op elke beveiligde pagina | Ongeautoriseerde paginatoegang |

**Bewijs:** README sectie 9 (Beveiligingsmaatregelen).

#### Criterium 12: Projectlog en overlegmomenten

**Antwoord:**
Het projectlog bevat **29 taken** over **28 dagen**, allemaal voltooid op tijd.

**Bugs opgelost tijdens realisatie:**
- 09-09: Dubbele insert bij games → opgelost met unique key
- 19-09: Delete fout door FK constraint → opgelost met cascade delete

**3 overlegmomenten met begeleider:**

| Datum | Onderwerp | Resultaat |
| --- | --- | --- |
| 07-09-2025 | Backend check | Database en inloggen goedgekeurd |
| 14-09-2025 | Vriendenlijst feedback | Feedback verwerkt in code |
| 27-09-2025 | Design check voor mobiel | Responsive design goedgekeurd |

**Bewijs:** PDF `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf`, PDF `K1 W3 Realisatie-Realisatie verslag-Harsha Vardhan Kanaparthi.pdf`.

---

### 16.4 K1-W4: Testen

#### Criterium 1: De student heeft een teststrategie opgesteld

**Antwoord:**

**Testperiode:** 23 t/m 25 september 2025 (3 dagen, totaal **6 uur** testtijd)

De teststrategie is gebaseerd op vier testniveaus:

1. **Functionele tests:** Elke user story is stap voor stap doorlopen met geldige invoer om te controleren dat het verwachte resultaat wordt bereikt (happy path). Per user story zijn testcases opgesteld.
2. **Validatietests:** Alle 18 validatieregels (V1 t/m V18) zijn getest met ongeldige invoer om te verifiëren dat foutmeldingen correct worden getoond (edge cases en foutscenario's).
3. **Beveiligingstests:** De 10 beveiligingsmaatregelen (B1 t/m B10) zijn getest door aanvallen te simuleren (SQL-injectie, XSS, URL-manipulatie, sessie-timeout).
4. **Responsiviteitstests:** De applicatie is getest op 4 schermgrootten (desktop, tablet, mobiel, klein mobiel) en specifiek op een **Samsung Galaxy S21** smartphone.

**Testtools gebruikt:**
- Browser DevTools (Chrome) voor responsiviteitscontrole en netwerk-analyse
- Handmatig testen met voorgeschreven testcases per user story
- Samsung Galaxy S21 voor echte mobiele test

**Bewijs:** README sectie 13.1 (Teststrategie) en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft testscenario's opgesteld voor alle features

**Antwoord:**
Er zijn **30 testcases** opgesteld en uitgevoerd, verdeeld per user story:

| User Story | Functionaliteit | Aantal tests | Geslaagd | Gezakt |
| --- | --- | --- | --- | --- |
| US-1 | Profiel met favoriete games | 5 | 5 | 0 |
| US-2 | Vriendenlijst beheren | 5 | 4 | 1 |
| US-3 | Speelschema's delen in kalender | 5 | 5 | 0 |
| US-4 | Evenementen toevoegen | 5 | 5 | 0 |
| US-5 | Herinneringen instellen | 5 | 4 | 1 |
| US-6 | Alles bewerken of verwijderen | 5 | 5 | 0 |
| **Totaal** | | **30** | **28** | **2** |

**Slagingspercentage: 28 van 30 = 93%**

De 2 gezakte tests betreffen:
- **Bug #1001** (US-2): Vriend toevoegen met alleen spaties werd geaccepteerd → opgelost met `trim()` + regex `/^\s*$/`
- **Bug #1004** (US-5): Ongeldige datum "2025-13-45" werd geaccepteerd → opgelost met `DateTime::createFromFormat()` strikte controle

Elk testscenario bevat: testcase-ID, invoer, verwacht resultaat, werkelijk resultaat en status (geslaagd/gezakt).

**Bewijs:** README secties 13.2 t/m 13.10 met alle testcases in tabelvorm en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 3: De student test happy paths, edge cases en foutscenario's

**Antwoord:**

**Happy path tests (alles gaat goed) – per user story:**

| User Story | Happy path test | Resultaat |
| --- | --- | --- |
| US-1 | Favoriet spel toevoegen met geldige titel en beschrijving | Geslaagd |
| US-2 | Vriend toevoegen met geldige gebruikersnaam en status | Geslaagd |
| US-3 | Gaming-schema toevoegen met speltitel, datum in toekomst, tijd | Geslaagd |
| US-4 | Evenement toevoegen met titel, datum, tijd, beschrijving | Geslaagd |
| US-5 | Herinnering instellen op "1 uur ervoor" bij evenement | Geslaagd |
| US-6 | Item bewerken en verwijderen met eigenaarschapscontrole | Geslaagd |

**Edge case tests (grensgevallen):**

- Gebruikersnaam met alleen spaties " " → foutmelding (Bug #1001 – opgelost)
- Ongeldige datum "2025-13-45" → foutmelding (Bug #1004 – opgelost)
- Kommagescheiden lijst met lege items "a,,b" → foutmelding
- Gebruikersnaam langer dan 50 tekens → foutmelding maximale lengte
- Beschrijving langer dan 500 tekens → foutmelding te lang
- Dubbele favoriet (zelfde spel opnieuw) → foutmelding "Spel al in favorieten"
- Dubbele vriend (zelfde gebruikersnaam) → foutmelding "Al vrienden"

**Foutscenario tests (intentionele fouten):**

- Fout wachtwoord bij inloggen → "Ongeldige e-mail of wachtwoord"
- Dubbele e-mail registratie → "E-mail al geregistreerd"
- SQL-injectie `' OR 1=1 --` in e-mailveld → geen effect (prepared statements)
- XSS `<script>alert('hack')</script>` in naamveld → geëscaped als tekst
- URL-manipulatie met ander gebruiker-ID → "Geen toestemming" (eigenaarschapscontrole)

**Bewijs:** README secties 13.2-13.9 en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 4: De student heeft een testrapport met resultaten opgesteld

**Antwoord:**

**Testresultaten eerste ronde (23-25 september 2025):**

| User Story | Tests | Geslaagd | Gezakt | Score |
| --- | --- | --- | --- | --- |
| US-1: Profiel met favoriete games | 5 | 5 | 0 | 100% |
| US-2: Vriendenlijst beheren | 5 | 4 | 1 | 80% |
| US-3: Speelschema's delen | 5 | 5 | 0 | 100% |
| US-4: Evenementen toevoegen | 5 | 5 | 0 | 100% |
| US-5: Herinneringen instellen | 5 | 4 | 1 | 80% |
| US-6: Bewerken/verwijderen | 5 | 5 | 0 | 100% |
| **TOTAAL** | **30** | **28** | **2** | **93%** |

**Gevonden bugs tijdens testen:**

| Bug-ID | Beschrijving | User Story | Ernst | Status |
| --- | --- | --- | --- | --- |
| Bug #1001 | Velden accepteerden alleen spaties als geldige invoer | US-2 | Hoog | Opgelost |
| Bug #1004 | Ongeldige datums (bijv. 2025-13-45) werden geaccepteerd | US-5 | Hoog | Opgelost |

**Na bugfixes zijn alle 30 tests opnieuw uitgevoerd met 100% slagingspercentage.**

**Performance test:**
De gemiddelde laadtijd van de applicatie is gemeten op **1,8 seconden**. Dit is getest met browser DevTools (Chrome Network tab) en valt binnen de acceptabele norm van < 3 seconden.

**Responsiviteitstest:**

| Apparaat/Scherm | Breedte | Resultaat |
| --- | --- | --- |
| Desktop | > 992px | Volledige navigatie, tabellen breed – Geslaagd |
| Tablet | 768px - 992px | Hamburger menu, tabellen scrollbaar – Geslaagd |
| Mobiel (Samsung Galaxy S21) | < 768px | Hamburger menu, knoppen full-width – Geslaagd |
| Klein mobiel | < 480px | Alles past op scherm, footer leesbaar – Geslaagd |

**Bewijs:** README sectie 13.11 (Testresultaten samenvatting) en PDF `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf`.

---

### 16.5 K1-W5: Verbeteren / Opleveren

#### Criterium 1: De student heeft informatiebronnen geraadpleegd en geanalyseerd

**Antwoord:**

Tijdens het verbeterproces zijn **3 informatiebronnen** geraadpleegd om verbeterpunten te identificeren:

| Nr | Bron | Wat geanalyseerd | Resultaat |
| --- | --- | --- | --- |
| 1 | **Testrapport** (K1-W4) | 30 testcases met 2 gezakte tests (93%) | Bug #1001 en Bug #1004 geïdentificeerd en opgelost |
| 2 | **Opleveringsdocument** | Functionaliteitschecklist per user story | Alle 6 user stories gerealiseerd, 6 verbetervoorstellen opgesteld |
| 3 | **Reflectieverslag** | Eigen analyse van het ontwikkelproces | Leermomenten vastgelegd voor toekomstige projecten |

Daarnaast zijn de volgende technische bronnen geraadpleegd voor het oplossen van de gevonden bugs:

| Bron | Gebruikt voor | Voorbeeld |
| --- | --- | --- |
| PHP.net (officieel) | Functiedocumentatie, best practices | `password_hash()`, `DateTime::createFromFormat()` |
| MDN Web Docs | JavaScript validatie, DOM-manipulatie | `addEventListener()`, regex patronen, `Date` object |
| OWASP | Beveiligingsrichtlijnen | SQL-injectie preventie, XSS-bescherming |

Per bron is kritisch beoordeeld of de informatie actueel, betrouwbaar en toepasbaar is. Officiële documentatie (PHP.net, MDN) heeft altijd voorrang boven community-antwoorden.

**Bewijs:** PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student beschrijft gevonden problemen met oorzaakanalyse

**Antwoord:**

Tijdens het formele testproces (K1-W4) zijn **2 bugs** gevonden die zijn opgelost:

**Bug #1001 – Velden accepteerden alleen spaties**

- **Probleem:** Een gebruiker kon " " (alleen spaties) invullen als gebruikersnaam bij het toevoegen van een vriend
- **Ontdekt tijdens:** Handmatig testen van US-2 (Vriendenlijst beheren)
- **Oorzaak:** De `empty()` functie in PHP retourneert `false` voor een string die alleen spaties bevat. Hierdoor passeerde de invoer de validatie.
- **Oplossing:** `trim()` toegevoegd om witruimte te verwijderen + regex `/^\s*$/` controle om strings met alleen spaties te detecteren
- **Bestanden gewijzigd:** `functions.php` (server-validatie) en `script.js` (client-validatie)
- **Hertest:** Na de fix wordt " " correct geweigerd met foutmelding → **Geslaagd**

**Bug #1004 – Ongeldige datums werden geaccepteerd**

- **Probleem:** Datum "2025-13-45" werd geaccepteerd als geldige datum (maand 13, dag 45 bestaan niet)
- **Ontdekt tijdens:** Handmatig testen van US-5 (Herinneringen instellen)
- **Oorzaak:** De datumvalidatie gebruikte alleen een regex-patroon dat het formaat JJJJ-MM-DD controleerde, maar NIET of de datum daadwerkelijk bestaat
- **Oplossing:** `DateTime::createFromFormat('Y-m-d', $datum)` met strikte vergelijking: de geformatteerde datum wordt terug vergeleken met de invoer. Als ze niet exact overeenkomen, is de datum ongeldig.
- **Bestanden gewijzigd:** `functions.php` (server-validatie) en `script.js` (client-validatie met `new Date()` + `isNaN()`)
- **Hertest:** Na de fix wordt "2025-13-45" correct geweigerd met foutmelding → **Geslaagd**

**Verbeterproces per bug (5-stappenmodel):**

```
STAP 1: ONTDEKKEN  → Bug gevonden via handmatig testen
STAP 2: ANALYSEREN → Oorzaak achterhalen door code te doorlopen
STAP 3: OPLOSSEN   → Fix implementeren in server + client
STAP 4: HERTESTEN  → Dezelfde testcase opnieuw uitvoeren
STAP 5: DOCUMENTEREN → Bug, oorzaak, oplossing en hertest vastleggen
```

**Bewijs:** README sectie 14.1 (overzichtstabel) en sectie 14.2 (uitgebreid verbeterproces per bug).

#### Criterium 3: De student doet verbetervoorstellen met impactbeschrijving

**Antwoord:**

Er zijn **6 verbetervoorstellen** opgesteld op basis van de testresultaten, gebruikersfeedback en eigen analyse:

| Nr | Voorstel | Beschrijving | Impact | Prioriteit |
| --- | --- | --- | --- | --- |
| #1001 | Wachtwoord vergeten functie | E-mail met reset-link sturen zodat gebruikers hun wachtwoord kunnen herstellen | Gebruikers die hun wachtwoord kwijtraken hoeven geen nieuw account te maken | **Hoog** |
| #1002 | Profielfoto uploaden | Gebruikers kunnen een avatar uploaden voor een persoonlijkere ervaring | Meer betrokkenheid en herkenning tussen gamers | **Gemiddeld** |
| #1003 | Zoekfunctie | Zoeken in schema's, evenementen en vrienden op trefwoord | Sneller items terugvinden bij veel data, betere bruikbaarheid | **Gemiddeld** |
| #1004 | Real-time vriendenlijst | WebSocket-verbinding voor live online-status updates zonder pagina te verversen | Live statusupdates verbeteren de sociale ervaring | **Laag** |
| #1005 | Meerdere talen (NL/EN) | Volledig taalwisselaar voor Nederlands en Engels | Breder publiek bereiken, internationalisatie | **Laag** |
| #1006 | E-mail notificaties | Automatische herinnering per e-mail versturen bij evenementen | Hogere opkomst bij evenementen, minder gemiste afspraken | **Gemiddeld** |

**Prioritering met onderbouwing:**

| Prioriteit | Voorstellen | Onderbouwing |
| --- | --- | --- |
| **Hoog** | #1001 | Directe impact op gebruikerservaring, relatief eenvoudig te implementeren met bestaande PHP-mailfuncties |
| **Gemiddeld** | #1002, #1003, #1006 | Verbetert de ervaring aanzienlijk, maar vereist nieuwe functionaliteit en testen |
| **Laag** | #1004, #1005 | Vereist fundamentele architectuurwijzigingen (WebSocket-server, i18n-framework), beter geschikt voor een volgende projectfase |

**Gebruikersfeedback:**
De applicatie is getest door **3 testgebruikers** (gamers uit de doelgroep). Hun feedback is meegenomen in de verbetervoorstellen:
- Testgebruiker 1: "Een zoekfunctie zou handig zijn als je veel schema's hebt" → Voorstel #1003
- Testgebruiker 2: "Het zou leuk zijn om een profielfoto te hebben" → Voorstel #1002
- Testgebruiker 3: "E-mail herinneringen zouden helpen om afspraken niet te vergeten" → Voorstel #1006

**Bewijs:** README sectie 14.3 en PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 4: De student reflecteert op het verbeterproces

**Antwoord:**

Het verbeterproces is systematisch uitgevoerd volgens het 5-stappenmodel (ontdekken → analyseren → oplossen → hertesten → documenteren). Dit proces is voor beide bugs volledig doorlopen.

**Belangrijkste leerpunten uit het verbeterproces:**

| Nr | Leerpunt | Uitleg |
| --- | --- | --- |
| 1 | Regex-validatie alleen is NIET voldoende voor datumcontrole | Gebruik altijd ook de `DateTime`-klasse om te controleren of een datum echt bestaat |
| 2 | PHP's `empty()` functie heeft onverwacht gedrag met spaties | Altijd combineren met `trim()` om witruimte te verwijderen |
| 3 | Dubbele validatie is essentieel | Client-side validatie voor gebruiksgemak, server-side validatie voor veiligheid |
| 4 | Testen onthult verborgen bugs | Zonder systematisch testen waren Bug #1001 en #1004 in productie gekomen |

**Bewijs:** README sectie 14.2 (volledig verbeterproces per bug) en PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 5: De student heeft feedback ontvangen van de begeleider

**Antwoord:**

Stagebegeleider **Marius Restua** heeft feedback gegeven op het verbeterproces en de opgeleverde applicatie:

**Feedback van Marius Restua:**

| Onderdeel | Feedback | Verwerkt |
| --- | --- | --- |
| Code-kwaliteit | "Goede scheiding van verantwoordelijkheden, nette structuur" | Ja – Separation of Concerns consequent toegepast |
| Beveiliging | "Prepared statements en XSS-bescherming correct geïmplementeerd" | Ja – 10 beveiligingsmaatregelen |
| Validatie | "Dubbele validatie (client + server) is een goede keuze" | Ja – 18 validatieregels op beide lagen |
| Documentatie | "README is uitgebreid en duidelijk, goed als naslagwerk" | Ja – 2900+ regels documentatie |
| Verbeterpunten | "Overweeg geautomatiseerde tests voor toekomstige projecten" | Opgenomen als leerpunt |

**Bewijs:** PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Feedback van Stagebegeleider.pdf`.

#### Criterium 6: De student heeft het product opgeleverd met oplevernotities

**Antwoord:**

Het project is opgeleverd met de volgende onderdelen:

| Nr | Onderdeel | Beschrijving |
| --- | --- | --- |
| 1 | Volledige broncode | 22+ bestanden (PHP, JS, CSS, SQL) in de GitHub-repository |
| 2 | Database-script | `database.sql` voor eenvoudige installatie van de database |
| 3 | Installatie-instructies | README sectie 12 met stap-voor-stap uitleg |
| 4 | Documentatie | README.md met 2900+ regels technische documentatie |
| 5 | Demovideo | `K1-W3-DEMO VIDEO.mp4` met demonstratie van alle functionaliteiten |
| 6 | Screenshots | `Demo Fotos/Software Fotos/` met 12 PNG-screenshots van alle pagina's |
| 7 | Oplevernotities | Aandachtspunten voor overdracht aan eventuele opvolger |

**Oplevernotities (aandachtspunten):**

1. De applicatie draait op XAMPP (Apache + MySQL) en is bereikbaar via `http://localhost/gameplan-scheduler/`
2. De database kan worden aangemaakt door `database.sql` te importeren in phpMyAdmin
3. Alle wachtwoorden zijn versleuteld met bcrypt en kunnen NIET worden teruggelezen
4. De sessie verloopt na 30 minuten inactiviteit – dit is configureerbaar in `functions.php`
5. Soft delete is actief: verwijderde data kan worden hersteld door `deleted_at` op NULL te zetten

**Bewijs:** PDF `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi-Oplevering Notities.pdf` en alle bestanden in de repository.

---

### 16.6 K2-W1: Overleggen

#### Criterium 1: De student voert overleg over het project

**Antwoord:**

**Stagebedrijf:** Kompas Publishing B.V.
**Stagebegeleider:** Marius Restua

Er zijn meerdere overlegmomenten geweest gedurende het project:

| Datum | Type overleg | Deelnemers | Onderwerp | Resultaat |
| --- | --- | --- | --- | --- |
| 7 september 2025 | Voortgangsoverleg | Harsha Kanaparthi, Marius Restua | Backend check: database en inloggen | Database-ontwerp en authenticatie goedgekeurd |
| 16 september 2025 | Voortgangsoverleg | Harsha Kanaparthi, Marius Restua | Vriendenlijst feedback en functionaliteit | Feedback verwerkt in vriendenlijst-implementatie |
| 27 september 2025 | Voortgangsoverleg | Harsha Kanaparthi, Marius Restua | Design check voor mobiel en responsiviteit | Responsive design goedgekeurd |
| 29 januari 2025 | Evaluatie-overleg | Harsha Kanaparthi, Marius Restua | Eindevaluatie en beoordeling | Beoordelingsrubric ingevuld |

Bij elk overleg zijn de volgende zaken gedocumenteerd:

- **Datum en deelnemers** (wie was aanwezig)
- **Besproken onderwerpen** (welke aspecten van het project)
- **Genomen beslissingen** (wat is afgesproken)
- **Actiepunten** (wat moet er nog gebeuren)

**Actiepunten uit overleggen:**

| Overleg | Actiepunt | Status |
| --- | --- | --- |
| 7 september | Foreign keys toevoegen aan database-tabellen | Afgerond |
| 7 september | Soft delete implementeren i.p.v. harde delete | Afgerond |
| 16 september | Dubbele-vriend controle toevoegen (hoofdletterongevoelig) | Afgerond |
| 16 september | Online-status dropdown toevoegen bij vriend toevoegen | Afgerond |
| 27 september | Minimale knophoogte 40px voor mobiel | Afgerond |
| 27 september | Hamburger menu testen op klein scherm | Afgerond |

**Bewijs:** PDF `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf` (overlegverslagen).

#### Criterium 2: De student ontvangt en verwerkt feedback

**Antwoord:**

**Beoordelingsrubric stagebedrijf:**

De stagebegeleider Marius Restua (Kompas Publishing B.V.) heeft een beoordelingsrubric ingevuld met de volgende resultaten:

**Totaalscore: 19 van 54 punten (gemiddelde: 4,4 op schaal van 10)**

| Criterium | Score | Toelichting |
| --- | --- | --- |
| Communicatie | Voldoende | Duidelijk in overleg, stelt goede vragen |
| Technische vaardigheden | Voldoende | PHP, MySQL en JavaScript correct toegepast |
| Zelfstandigheid | Voldoende | Werkt zelfstandig na instructie |
| Documentatie | Goed | Uitgebreide README en technische docs |
| Code-kwaliteit | Voldoende | Separation of Concerns, DRY-principe |
| Samenwerking | Voldoende | Werkt mee in feedbackmomenten |

**Pluspunten (benoemd door begeleider):**

- Nette code-structuur met Separation of Concerns
- Uitgebreide documentatie (README met 2900+ regels)
- Goede beveiligingsmaatregelen (10 maatregelen geïmplementeerd)
- Consequente dubbele validatie (client + server)

**Verbeterpunten (benoemd door begeleider):**

- Eerder beginnen met testen in het ontwikkelproces
- Meer geautomatiseerde tests overwegen (unit tests met PHPUnit)
- Communicatie kan proactiever: wijzigingen eerder melden

**Hoe feedback is verwerkt:**

| Feedback | Verwerking |
| --- | --- |
| "Voeg eigenaarschapscontrole toe" | `checkOwnership()` functie geïmplementeerd in functions.php |
| "Dubbele validatie toepassen" | 18 validatieregels op zowel client (JS) als server (PHP) |
| "Documentatie uitbreiden" | README uitgebreid naar 2900+ regels met alle 16 secties |
| "Soft delete toevoegen" | `deleted_at` kolom in 5 tabellen (Users, Games, Friends, Schedules, Events); koppeltabel UserGames gebruikt harde delete |

**Bewijs:** PDF `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`, PDF `Feedback Stage Harsha Kanaparthi .pdf` en PDF `Beoordelingsrubrics Stagiaire- Harsha .pdf`.

---

### 16.7 K2-W2: Presenteren

#### Criterium 1: De student geeft minimaal 2 presentaties over het project

**Antwoord:**

Er zijn **2 presentaties** gegeven op **10 maart 2025**:

---

**Presentatie 1: Portfolio Website – met stagebegeleider**

| Onderdeel | Details |
| --- | --- |
| **Datum** | 10 maart 2025 |
| **Presentator** | Harsha Kanaparthi |
| **Publiek** | Marius Restua (stagebegeleider, Kompas Publishing B.V.) |
| **Onderwerp** | Portfolio Website – demonstratie van de GamePlan Scheduler applicatie |
| **Duur** | Circa 15 minuten presentatie + 10 minuten vragen |

**Inhoud van presentatie 1:**

1. **Projectoverzicht:** Wat is GamePlan Scheduler en voor wie is het bedoeld?
2. **Technische architectuur:** PHP + MySQL + Bootstrap, Separation of Concerns
3. **Live demonstratie:** Registreren, inloggen, vrienden toevoegen, schema maken, evenement maken, herinnering instellen, bewerken, verwijderen
4. **Beveiligingsmaatregelen:** PDO prepared statements, bcrypt hashing, safeEcho(), sessie-timeout
5. **Testresultaten:** 30 testcases, 93% slagingspercentage (na bugfixes 100%)
6. **Verbeteringen:** Bug #1001 en #1004 opgelost, 6 verbetervoorstellen

**Feedback van Marius Restua op presentatie 1:**

| Aspect | Feedback |
| --- | --- |
| Inhoud | "Goed opgebouwd, duidelijke structuur van begin tot eind" |
| Technisch | "Goede uitleg van beveiligingsmaatregelen en validatie" |
| Demonstratie | "Live demo liet alle functies goed zien" |
| Verbeterpunt | "Iets meer nadruk op de 'waarom' achter technische keuzes" |

**Bewijs presentatie 1:**
- PDF `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1.pdf`
- PDF `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1-Feedback Van de stage begeleider-Harsha Vardhan Kanaparthi (1).pdf`
- PDF `K2 W2 Presenteren-Presentatie met stage begeleider-Presentatie-1- Reflectie Verslag_ Portfolio Website-Harsha Vardhan Kanaparthi.pdf`

---

**Presentatie 2: MBO-4 Opleiding Software Development – met studiegenoot**

| Onderdeel | Details |
| --- | --- |
| **Datum** | 10 maart 2025 |
| **Presentator** | Harsha Kanaparthi |
| **Publiek** | Billy den Ouden (studiegenoot) |
| **Onderwerp** | MBO-4 Opleiding Software Development – wat heb ik geleerd? |
| **Duur** | Circa 15 minuten presentatie + 10 minuten vragen |

**Inhoud van presentatie 2:**

1. **Opleidingsoverzicht:** MBO-4 Software Development (Crebo 25998), kerntaken K1 en K2
2. **Projectoverzicht:** GamePlan Scheduler als eindproject
3. **Gebruikte technologieën:** PHP, MySQL, HTML/CSS, JavaScript, Bootstrap, Git
4. **Leerervaringen:** Wat heb ik geleerd tijdens de opleiding en stage?
5. **Tips en advies:** Wat zou ik aanraden aan medestudenten?

**Feedback van Billy den Ouden op presentatie 2:**

| Aspect | Feedback |
| --- | --- |
| Inhoud | "Duidelijk overzicht van wat je geleerd hebt" |
| Presentatievaardigheden | "Helder en gestructureerd gepresenteerd" |
| Verbeterpunt | "Meer voorbeelden uit de praktijk toevoegen" |

**Bewijs presentatie 2:**
- PDF `K2 W2 Presenteren-Presentatie met studiegenoot-Presentatie-2-MBO-4-Opleiding-Software-Development.pdf`
- PDF `K2 W2 Presenteren-Presentatie met studiegenoot-Presentatie-2-MBO-4-Opleiding-Software-Development-  Reflectie- Verslag- Portfolio- Website-Harsha -Vardhan- Kanaparthi.pdf`

#### Criterium 2: De student reflecteert op de gegeven presentaties

**Antwoord:**

Na elke presentatie is een **reflectieverslag** geschreven:

**Reflectie op presentatie 1 (Portfolio Website – Marius Restua):**

| Reflectievraag | Antwoord |
| --- | --- |
| Wat ging goed? | De live demonstratie verliep soepel, alle functies werkten correct. De structuur van de presentatie was duidelijk. |
| Wat kon beter? | Meer nadruk op de 'waarom' achter technische keuzes. Dieper ingaan op de onderbouwing van ontwerpbeslissingen. |
| Hoe was de interactie? | Marius stelde goede vragen over beveiliging en architectuur. Ik kon deze vragen goed beantwoorden. |
| Welke vragen werden gesteld? | "Waarom PDO en niet MySQLi?", "Hoe werkt de sessie-timeout precies?" |
| Wat neem ik mee? | Bij de volgende presentatie meer nadruk op de onderbouwing van keuzes, niet alleen het resultaat. |

**Reflectie op presentatie 2 (MBO-4 Opleiding – Billy den Ouden):**

| Reflectievraag | Antwoord |
| --- | --- |
| Wat ging goed? | De presentatie was helder en gestructureerd. Billy begreep de inhoud goed. |
| Wat kon beter? | Meer concrete voorbeelden uit de stagepraktijk toevoegen om de leerervaringen te illustreren. |
| Hoe was de interactie? | Billy stelde vragen over de opleiding en technologiekeuzes. Goede dialoog. |
| Welke vragen werden gesteld? | "Welke taal vond je het moeilijkst?", "Wat is je favoriete project geweest?" |
| Wat neem ik mee? | Voorbeelden maken een presentatie levendiger. In het vervolg meer praktijkvoorbeelden gebruiken. |

**Bewijs:** Reflectieverslagen zijn opgenomen in de presentatie-PDFs (zie hierboven).

---

### 16.8 K2-W3: Reflecteren

#### Criterium 1: De student reflecteert op het gehele projectproces

**Antwoord:**

Het reflectieverslag is geschreven met de **STARR-methode** (Situatie, Taak, Actie, Resultaat, Reflectie):

**S – Situatie:**
Ik, Harsha Kanaparthi (studentnummer 2195344), liep stage bij **Kompas Publishing B.V.** onder begeleiding van **Marius Restua**. Als eindopdracht voor de MBO-4 opleiding Software Development (Crebo 25998) heb ik de GamePlan Scheduler webapplicatie ontwikkeld.

**T – Taak:**
Mijn taak was om in **4 weken** (2 september t/m 30 september 2025, totaal **49 uur**) een volledig werkende webapplicatie te bouwen die gamers helpt om hun gaming-sessies te plannen. De applicatie moest voldoen aan alle exameneisen (K1-W1 t/m K2-W3) en 6 user stories realiseren.

**A – Actie:**
Ik heb het project systematisch aangepakt in 5 fasen:

| Fase | Werkproces | Activiteiten |
| --- | --- | --- |
| Week 1 | K1-W1 Planning | Projectplan, user stories, MoSCoW, SMART-doel, takenplanning |
| Week 1-2 | K1-W2 Ontwerp | Database-ontwerp (ERD), functioneel ontwerp, technisch ontwerp, wireframes |
| Week 1-4 | K1-W3 Realisatie | 22+ bestanden coderen (PHP, JS, CSS, SQL), 10 beveiligingsmaatregelen, 18 validatieregels |
| Week 4 | K1-W4 Testen | 30 testcases uitvoeren, 2 bugs vinden en oplossen, performance test |
| Week 4 | K1-W5 Verbeteren | 6 verbetervoorstellen, feedback verwerken, oplevering |

**R – Resultaat:**
De GamePlan Scheduler is een volledig werkende webapplicatie met:
- 6 gerealiseerde user stories (100%)
- 22+ bronbestanden (PHP, JS, CSS, SQL)
- 6 database-tabellen met relaties
- 10 beveiligingsmaatregelen
- 18 validatieregels (dubbel: client + server)
- 30 testcases uitgevoerd (93% eerste ronde, 100% na bugfixes)
- 4 bugs gevonden en opgelost (2 via testen, 2 via code review)
- 6 verbetervoorstellen gedocumenteerd
- 2900+ regels documentatie (README.md)

**R – Reflectie:**

**Wat ging goed:**

| Nr | Aspect | Toelichting |
| --- | --- | --- |
| 1 | Separation of Concerns | De code-structuur is overzichtelijk en onderhoudbaar dankzij de scheiding van lagen |
| 2 | Dubbele validatie | Client-side + server-side validatie heeft veel potentiële problemen voorkomen |
| 3 | Beveiligingsmaatregelen | 10 maatregelen geïmplementeerd, waaronder bcrypt, prepared statements en XSS-bescherming |
| 4 | Documentatie | Uitgebreide README met 16 secties maakt het project overdraagbaar |
| 5 | Systematisch testen | Het testproces heeft 2 bugs aan het licht gebracht die anders in productie waren gekomen |

**Wat kon beter:**

| Nr | Aspect | Toelichting |
| --- | --- | --- |
| 1 | Eerder testen | Eerder beginnen met testen in plaats van alles in de laatste week |
| 2 | Geautomatiseerde tests | Unit tests met PHPUnit overwegen voor snellere en herhaalbare tests |
| 3 | Proactievere communicatie | Wijzigingen eerder melden aan de stagebegeleider |
| 4 | MVC-patroon | Een MVC-structuur overwegen voor nog betere scheiding van logica en presentatie |

**Probleemsituatie (STARR):**

Tijdens het project liep ik tegen een probleem aan met de datumvalidatie:

- **Situatie:** Bij het testen van de schema-functie bleek dat ongeldige datums werden geaccepteerd
- **Taak:** De validatie moest zowel het formaat als de geldigheid van de datum controleren
- **Actie:** Ik heb `DateTime::createFromFormat()` onderzocht op PHP.net en een strikte vergelijking geïmplementeerd
- **Resultaat:** Na de fix worden ongeldige datums (zoals "2025-13-45") correct geweigerd
- **Reflectie:** Ik heb geleerd dat regex alleen niet voldoende is voor datumvalidatie; je moet altijd ook de inhoud controleren

**Bewijs:** PDF `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf`.

#### Criterium 2: De student heeft feedback ontvangen met handtekening van begeleider

**Antwoord:**

Stagebegeleider **Marius Restua** (Kompas Publishing B.V.) heeft een feedbackformulier ingevuld en **ondertekend**. Dit document bevat:

| Onderdeel | Inhoud |
| --- | --- |
| Beoordeling technische vaardigheden | PHP, MySQL en JavaScript correct toegepast, goede beveiliging |
| Beoordeling werkhouding | Zelfstandig, gemotiveerd, houdt zich aan afspraken |
| Beoordeling communicatie | Duidelijk in overleg, stelt goede vragen, kan beter in pro-actief melden van wijzigingen |
| Beoordeling samenwerking | Werkt goed samen, verwerkt feedback constructief |
| Opmerkingen | "Harsha heeft een goed project opgeleverd met nette code en uitgebreide documentatie" |
| Aanbevelingen | Geautomatiseerde tests overwegen, eerder beginnen met testen |
| **Handtekening** | **Ondertekend door Marius Restua** |

**Bewijs:** PDF `K2 W3 Reflectie-Harsha Vardhan Kanaparthi-Feedback  bij Stage-Begeleider  Met Handtekening.pdf`.

#### Criterium 3: De student heeft een portfolio-presentatie voorbereid

**Antwoord:**

Er is een portfolio-presentatie voorbereid die het volledige project samenvat en geschikt is voor het examenmoment. De presentatie bevat:

| Slide | Onderwerp | Inhoud |
| --- | --- | --- |
| 1 | Titelslide | GamePlan Scheduler – Harsha Kanaparthi – 2195344 |
| 2 | Projectoverzicht | Wat is het, voor wie, welk probleem lost het op? |
| 3 | Technische architectuur | PHP + MySQL + Bootstrap, Separation of Concerns, 4 lagen |
| 4 | Database-ontwerp | 6 tabellen, ERD, relaties, indexen |
| 5 | Beveiligingsmaatregelen | 10 maatregelen met uitleg (BCrypt, PDO, safeEcho, etc.) |
| 6 | Validaties | 18 regels, dubbele validatie (client + server) |
| 7 | Live demonstratie | Alle functionaliteiten doorlopen |
| 8 | Testresultaten | 30 testcases, 93%→100%, 2 bugs gevonden en opgelost |
| 9 | Verbeteringen | 6 voorstellen met prioritering |
| 10 | Reflectie | STARR-methode, leermomenten, wat ging goed/kon beter |

**Bewijs:** PDF `K2 W3 Reflectie-Portfolio-Website-Presentatie.pdf`.

#### Criterium 4: De student heeft een beoordelingsrubric en eindfeedback

**Antwoord:**

De stagebegeleider Marius Restua heeft een **beoordelingsrubric** ingevuld en **eindfeedback** gegeven over de hele stageperiode.

**Beoordelingsrubric:**
- Totaalscore: **19 van 54 punten** (gemiddelde: **4,4** op schaal van 10)
- Alle criteria zijn beoordeeld op een schaal van onvoldoende tot goed
- Documentatie scoorde het hoogst (goed)

**Eindfeedback stage:**
- Positief: nette code, uitgebreide documentatie, goede beveiligingsmaatregelen
- Verbeterpunten: eerder testen, geautomatiseerde tests, proactievere communicatie
- Eindoordeel: "Harsha heeft het project succesvol afgerond en alle user stories gerealiseerd"

**Bewijs:**
- PDF `Beoordelingsrubrics Stagiaire- Harsha .pdf` (beoordelingsrubric)
- PDF `Feedback Stage Harsha Kanaparthi .pdf` (eindfeedback stage)

---

### 16.9 Bewijs-index: Overzicht van alle bewijsstukken per werkproces

De onderstaande tabel geeft per werkproces een compleet overzicht van alle bewijsstukken:

| Werkproces | README Sectie(s) | PDF Document(en) | Screenshots / Video |
| --- | --- | --- | --- |
| **K1-W1** Planning | 1, 4 | `K1-W1-Planning-Harsha Vardhan Kanaparthi.pdf`, `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf` | – |
| **K1-W2** Ontwerpen | 4, 5, 6, 7, 8 | `K1-W2-Ontwerp-Harsha Vardhan Kanaparthi.pdf` | `Demo Fotos/Software Fotos/` (12 screenshots) |
| **K1-W3** Realiseren | 1–12 | `K1 W3 Realisatie-Realisatie verslag-Harsha Vardhan Kanaparthi.pdf`, `K1 W3 Realisatie-Projectlog-Harsha Vardhan Kanaparthi.pdf` | `K1-W3-DEMO VIDEO.mp4`, `Demo Fotos/VersieBeheer/Versiebeheer.png` |
| **K1-W4** Testen | 13 | `K1-W4-Testen-Harsha Vardhan Kanaparthi.pdf` | – |
| **K1-W5** Verbeteren | 14 | `K1-W5-Verbeteren-Harsha Vardhan Kanaparthi.pdf`, `K1-W5-Verbeteren-...-Feedback van Stagebegeleider.pdf`, `K1-W5-Verbeteren-...-Oplevering Notities.pdf` | – |
| **K2-W1** Overleggen | – | `K2 W1 Overleggen-Harsha Vardhan Kanaparthi.pdf`, `Feedback Stage-Begeleider van Harsha Kanaparthi- K2 - W1.pdf`, `Beoordelingsrubrics Stagiaire- Harsha .pdf` | – |
| **K2-W2** Presenteren | – | Presentatie-1 (3 PDFs: presentatie + feedback + reflectie), Presentatie-2 (2 PDFs: presentatie + reflectie) | – |
| **K2-W3** Reflecteren | – | `K2 W3 Reflectie-Harsha Vardhan Kanaparthi.pdf`, `K2 W3 Reflectie-...-Feedback Met Handtekening.pdf`, `K2 W3 Reflectie-Portfolio-Website-Presentatie.pdf`, `Beoordelingsrubrics Stagiaire- Harsha .pdf`, `Feedback Stage Harsha Kanaparthi .pdf` | – |

**Totaal bewijsstukken:** 21 PDF-documenten + 12 screenshots + 1 demovideo + 1 versiebeheer-screenshot + README.md (2900+ regels) + 22+ bronbestanden

---

_Dit document beschrijft de volledige GamePlan Scheduler applicatie van A tot Z. Alle code, validaties, flows, beveiligingen en examencriteria (Crebo 25998, K1-W1 t/m K2-W3) zijn hierin gedocumenteerd._

_Auteur: Harsha Vardhan Kanaparthi | Studentnummer: 2195344 | Opleiding: MBO-4 Software Development_
