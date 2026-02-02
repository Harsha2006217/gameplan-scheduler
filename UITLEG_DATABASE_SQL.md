# UITLEG database.sql (Regel voor Regel)
## GamePlan Scheduler - Database Ontwerp

**Bestand**: `database.sql`
**Doel**: Het bouwen van de tabellen (De kasten waar de data in komt).

---

### Regel 1-8: Database Aanmaken
```sql
CREATE DATABASE IF NOT EXISTS gameplan_scheduler;
USE gameplan_scheduler;
```
**Uitleg**:
*   `CREATE DATABASE`: Maak een nieuwe lege ruimte.
*   `IF NOT EXISTS`: Doe dit alleen als hij nog niet bestaat (voorkomt foutmeldingen).
*   `USE`: Zegt tegen MySQL: "Vanaf nu gaan we in DEZE database werken".

### Regel 15-28: Gebruikers Tabel (`Users`)
```sql
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
**Uitleg**:
*   `user_id INT`: Een heel getal (1, 2, 3...).
*   `AUTO_INCREMENT`: De database telt zelf door. De eerste gebruiker is 1, de tweede is 2. Handig!
*   `PRIMARY KEY`: Dit is het unieke burgerservicenummer van de rij.
*   `VARCHAR(50)`: Tekst van maximaal 50 letters.
*   `UNIQUE` (bij email): Zorgt dat je niet 2x hetzelfde emailadres in de database kunt hebben.
*   `NOT NULL`: Dit veld mag NIET leeg zijn.

### Regel 40-52: Vrienden Tabel (`Friends`)
```sql
CREATE TABLE Friends (
    status ENUM('Offline', 'Online', 'In Game') DEFAULT 'Offline',
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
```
**Uitleg**:
*   `ENUM`: Een keuzelijstje. De status KAN alleen maar 'Offline', 'Online' of 'In Game' zijn. Iets anders past niet.
*   `FOREIGN KEY`: **Heel belangrijk!** Dit legt een link naar de `Users` tabel.
*   `ON DELETE CASCADE`: Als Gebruiker A zijn account verwijdert, worden automtisch al zijn vrienden uit deze lijst verwijderd. De database ruimt zichzelf op!

### Regel 60-70: Spellen Tabel (`Games`)
*   Hier slaan we de titels van spellen op ("Minecraft", "Valorant").
*   We slaan elk spel maar 1x op, om ruimte te besparen.

### Regel 80-90: Koppeltabel (`UserGames`)
```sql
CREATE TABLE UserGames (
    PRIMARY KEY (user_id, game_id)
);
```
**Uitleg**:
*   Dit is een **Veel-op-Veel relatie**.
*   Eén gebruiker kan veel spellen leuk vinden.
*   Eén spel kan door veel gebruikers leuk gevonden worden.
*   Deze tabel is de "Lijm" tussen `Users` en `Games`.

---
**Samenvatting**: 4 tabellen die slim met elkaar verbonden zijn via nummertjes (ID's). Dit zorgt voor een snelle en foutloze structuur.
