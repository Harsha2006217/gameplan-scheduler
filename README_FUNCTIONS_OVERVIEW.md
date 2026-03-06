# GamePlan Scheduler — Volledig Functieoverzicht

## Overzicht per Database-tabel

| Tabel         | Bestand       | Functie                | Regels  | Uitleg                                  |
| ------------- | ------------- | ---------------------- | ------- | --------------------------------------- |
| Users         | functions.php | registerUser           | 172-220 | Gebruiker registreren (validatie, hash) |
| Users         | functions.php | loginUser              | 222-261 | Gebruiker inloggen (sessie)             |
| Users         | functions.php | validateEmail          | 70-80   | E-mail validatie                        |
| Users         | functions.php | validateRequired       | 37-56   | Verplicht veld, lengte                  |
| Games         | functions.php | getOrCreateGameId      | 269-292 | Game zoeken of aanmaken                 |
| Games         | functions.php | validateRequired       | 37-56   | Titel validatie                         |
| UserGames     | functions.php | addFavoriteGame        | 294-326 | Favoriet spel toevoegen                 |
| UserGames     | functions.php | updateFavoriteGame     | 328-374 | Favoriet spel bewerken (eigenaarschap)  |
| UserGames     | functions.php | deleteFavoriteGame     | 376-397 | Favoriet spel verwijderen (hard delete) |
| UserGames     | functions.php | getFavoriteGames       | 399-423 | Favorieten ophalen                      |
| Friends       | functions.php | addFriend              | 441-486 | Vriend toevoegen (validatie, duplicaat) |
| Friends       | functions.php | updateFriend           | 488-533 | Vriend bewerken (eigenaarschap)         |
| Friends       | functions.php | deleteFriend           | 535-556 | Vriend verwijderen (soft delete)        |
| Friends       | functions.php | getFriends             | 558-582 | Vriendenlijst ophalen                   |
| Friends       | functions.php | validateRequired       | 37-56   | Gamertag validatie                      |
| Friends       | functions.php | validateCommaSeparated | 90-109  | Komma-gescheiden lijst validatie        |
| Schedules     | functions.php | addSchedule            | 584-627 | Schema toevoegen (validatie, game)      |
| Schedules     | functions.php | getSchedules           | 629-667 | Schema's ophalen                        |
| Schedules     | functions.php | editSchedule           | 669-714 | Schema bewerken (eigenaarschap)         |
| Schedules     | functions.php | validateDate           | 58-68   | Datum validatie                         |
| Schedules     | functions.php | validateTime           | 82-88   | Tijd validatie                          |
| Schedules     | functions.php | validateCommaSeparated | 90-109  | Komma-gescheiden lijst validatie        |
| Events        | functions.php | addEvent               | 739-782 | Evenement toevoegen (validatie, game)   |
| Events        | functions.php | getEvents              | 784-822 | Evenementen ophalen                     |
| Events        | functions.php | editEvent              | 824-869 | Evenement bewerken (eigenaarschap)      |
| Events        | functions.php | deleteEvent            | 871-892 | Evenement verwijderen (soft delete)     |
| Events        | functions.php | validateRequired       | 37-56   | Titel validatie                         |
| Events        | functions.php | validateDate           | 58-68   | Datum validatie                         |
| Events        | functions.php | validateTime           | 82-88   | Tijd validatie                          |
| Events        | functions.php | validateUrl            | 110-115 | URL validatie                           |
| Eigenaarschap | functions.php | checkOwnership         | 894-910 | Eigenaarschap controleren               |

---

## functions.php — Alle functies, van begin tot eind

### Hulpfuncties (Validatie, veilige uitvoer)

- **safeEcho** — [functions.php#L24-L29](functions.php#L24-L29)
  - Maakt tekst veilig voor HTML (XSS-bescherming)
- **validateRequired** — [functions.php#L37-L56](functions.php#L37-L56)
  - Verplicht veld, spaties, lengte
- **validateDate** — [functions.php#L58-L68](functions.php#L58-L68)
  - Datum validatie, toekomst-check
- **validateTime** — [functions.php#L82-L88](functions.php#L82-L88)
  - Tijd validatie (UU:MM)
- **validateEmail** — [functions.php#L70-L80](functions.php#L70-L80)
  - E-mail validatie
- **validateUrl** — [functions.php#L110-L115](functions.php#L110-L115)
  - URL validatie
- **validateCommaSeparated** — [functions.php#L90-L109](functions.php#L90-L109)
  - Komma-gescheiden lijst validatie

---

### Sessie berichten

- **setMessage** — [functions.php#L117-L124](functions.php#L117-L124)
  - Sessiebericht opslaan
- **getMessage** — [functions.php#L126-L137](functions.php#L126-L137)
  - Sessiebericht ophalen en tonen

---

### Authenticatie (Login, registratie, sessie)

- **isLoggedIn** — [functions.php#L139-L142](functions.php#L139-L142)
  - Check of gebruiker ingelogd is
- **getUserId** — [functions.php#L144-L147](functions.php#L144-L147)
  - Haal user_id op
- **updateLastActivity** — [functions.php#L149-L157](functions.php#L149-L157)
  - Update laatste activiteit
- **checkSessionTimeout** — [functions.php#L159-L170](functions.php#L159-L170)
  - Sessie timeout (30 min)
- **registerUser** — [functions.php#L172-L220](functions.php#L172-L220)
  - Gebruiker registreren (validatie, hashing, database)
- **loginUser** — [functions.php#L222-L261](functions.php#L222-L261)
  - Gebruiker inloggen (validatie, sessie)
- **logout** — [functions.php#L263-L267](functions.php#L263-L267)
  - Uitloggen (sessie vernietigen)

---

### Favoriete games (Games/UserGames)

- **getOrCreateGameId** — [functions.php#L269-L292](functions.php#L269-L292)
  - Game zoeken of aanmaken
- **addFavoriteGame** — [functions.php#L294-L326](functions.php#L294-L326)
  - Favoriet spel toevoegen
- **updateFavoriteGame** — [functions.php#L328-L374](functions.php#L328-L374)
  - Favoriet spel bewerken (met eigenaarschap)
- **deleteFavoriteGame** — [functions.php#L376-L397](functions.php#L376-L397)
  - Favoriet spel verwijderen (hard delete)
- **getFavoriteGames** — [functions.php#L399-L423](functions.php#L399-L423)
  - Favorieten ophalen
- **getGames** — [functions.php#L425-L439](functions.php#L425-L439)
  - Alle spellen ophalen

---

### Vrienden (Friends)

- **addFriend** — [functions.php#L441-L486](functions.php#L441-L486)
  - Vriend toevoegen (validatie, duplicaatcontrole)
- **updateFriend** — [functions.php#L488-L533](functions.php#L488-L533)
  - Vriend bewerken (met eigenaarschap)
- **deleteFriend** — [functions.php#L535-L556](functions.php#L535-L556)
  - Vriend verwijderen (soft delete)
- **getFriends** — [functions.php#L558-L582](functions.php#L558-L582)
  - Vriendenlijst ophalen

---

### Speelschema's (Schedules)

- **addSchedule** — [functions.php#L584-L627](functions.php#L584-L627)
  - Schema toevoegen (validatie, game-koppeling)
- **getSchedules** — [functions.php#L629-L667](functions.php#L629-L667)
  - Schema's ophalen
- **editSchedule** — [functions.php#L669-L714](functions.php#L669-L714)
  - Schema bewerken (met eigenaarschap)
- **deleteSchedule** — [functions.php#L716-L737](functions.php#L716-L737)
  - Schema verwijderen (soft delete)

---

### Evenementen (Events)

- **addEvent** — [functions.php#L739-L782](functions.php#L739-L782)
  - Evenement toevoegen (validatie, game-koppeling)
- **getEvents** — [functions.php#L784-L822](functions.php#L784-L822)
  - Evenementen ophalen
- **editEvent** — [functions.php#L824-L869](functions.php#L824-L869)
  - Evenement bewerken (met eigenaarschap)
- **deleteEvent** — [functions.php#L871-L892](functions.php#L871-L892)
  - Evenement verwijderen (soft delete)

---

### Generieke helpers (Eigenaarschap, kalender, herinneringen)

- **checkOwnership** — [functions.php#L894-L910](functions.php#L894-L910)
  - Eigenaarschap controleren
- **getCalendarItems** — [functions.php#L912-L950](functions.php#L912-L950)
  - Kalenderitems ophalen (schema's + events)
- **getReminders** — [functions.php#L952-L983](functions.php#L952-L983)
  - Herinneringen ophalen
