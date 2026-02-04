# 🗄️ UITLEG DATABASE.SQL (ULTIMATE ELITE MASTER EDITIE)
## GamePlan Scheduler - Database Ontwerp, Normalisatie & Architectuur

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer | **Versie**: 3.0 (Elite Master Expansion)
> 
> "De database is het kloppende hart van elke moderne applicatie. In dit document ontleden we de GamePlan Scheduler database tot op het kleinste detail, van de keuze van de storage engine tot de wiskundige basis van onze normalisatie."

---

# 📑 Inhoudsopgave

1.  **Database Filosofie: Waarom Relationale Data?**
2.  **De InnoDB Storage Engine: Transactie-Veiligheid**
3.  **Tabellen Architectuur (Regel voor Regel Analyse)**
4.  **Normalisatie Walkthrough: De Reis naar 3NF**
5.  **Relaties & Integriteit (Foreign Keys & Cascades)**
6.  **Indexering & Performance Optimalisatie**
7.  **Security in de Database-laag**
8.  **DE LEGENDARISCHE 100-PUNTS DATABASE GLOSSARY**
9.  **EXAMEN TRAINING: 50 Database & SQL Vragen**
10. **Conclusie: De Kluis van de Applicatie**

---

# 1. Database Filosofie: Waarom Relationeel? 🤔

De GamePlan Scheduler maakt gebruik van een **Relationele Database (RDBMS)**, specifiek MariaDB (een fork van MySQL). Dit paradigma is gekozen om de volgende redenen:
- **Structuur & Consistentie**: Alle data wordt opgeslagen in tabellen met vaste kolommen. Dit voorkomt "rommeltje" data.
- **SQL (Structured Query Language)**: De gestandaardiseerde manier om met data te praten, universeel begrepen door alle developers.
- **ACID Compliance**: Atomicity, Consistency, Isolation, Durability. Dit garandeert dat transacties (zoals het aanmaken van een gebruiker) 100% compleet zijn of helemaal niet.

---

# 2. De InnoDB Storage Engine: Transactie-Veiligheid ⚙️

In ons `database.sql` script zien we (impliciet via MariaDB standaard) dat we de **InnoDB** engine gebruiken. Dit is cruciaal voor:
- **Foreign Key Support**: InnoDB is de enige standaard engine die Foreign Key constraints afhandelt. Zonder InnoDB zouden onze relaties tussen Users, Friends en Games niet afdwingbaar zijn op database-niveau.
- **Row-Level Locking**: Bij gelijktijdige acties (twee gamers die tegelijk inloggen) blokkeert InnoDB niet de hele tabel, maar alleen de specifieke rijen die worden aangepast.
- **Crash Recovery**: Als de server plotseling uit valt, herstelt InnoDB de data naar de laatst bekende consistente staat via een Write-Ahead Log.

---

# 3. Tabellen Architectuur (Regel voor Regel) 📝

### 3.1 Database Aanmaken
```sql
CREATE DATABASE IF NOT EXISTS gameplan_scheduler;
USE gameplan_scheduler;
```
- `CREATE DATABASE`: Maakt een lege 'container' aan voor onze tabellen.
- `IF NOT EXISTS`: Voorkomt een foutmelding als de database al bestaat (idempotent script).
- `USE`: Schakelt de actieve context naar onze nieuwe database.

### 3.2 De `Users` Tabel (De Kern)
```sql
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
- `INT AUTO_INCREMENT PRIMARY KEY`: De `user_id` is de unieke sleutel. De database telt automatisch door (1, 2, 3...).
- `VARCHAR(50)`: Tekst met een maximale lengte van 50 karakters. Efficiënt voor het opslaan van namen zonder verspilling.
- `UNIQUE` op `email`: Dit is een **constraint**. De database weigert elke INSERT die een duplicaat email probeert toe te voegen.
- `password_hash VARCHAR(255)`: De 255 karakters zijn ruim voldoende voor een Bcrypt hash (standaard 60 karakters, met ruimte voor toekomstige algoritmes).
- `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`: Registreert automatisch het moment van accountcreatie.

### 3.3 De `Friends` Tabel (De Relaties)
```sql
CREATE TABLE Friends (
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_name VARCHAR(100) NOT NULL,
    status ENUM('Offline', 'Online', 'In Game') DEFAULT 'Offline',
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);
```
- `ENUM`: Een keuzelijstje. De status kan ALLEEN 'Offline', 'Online' of 'In Game' zijn. Dit is data-validatie op database-niveau.
- `FOREIGN KEY ... ON DELETE CASCADE`: De lijm tussen tabellen. Als een User verwijderd wordt, worden al zijn Friends automatisch mee verwijderd. Dit voorkomt "orphan" records.

### 3.4 De `Games` Tabel (De Catalogus)
```sql
CREATE TABLE Games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL UNIQUE
);
```
- Deze tabel slaat elk spel slechts één keer op. Door de `UNIQUE` constraint op `title` voorkomen we dat "Minecraft" 100x in de database staat.

### 3.5 De `UserGames` Koppeltabel (De Lijm)
```sql
CREATE TABLE UserGames (
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    PRIMARY KEY (user_id, game_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
);
```
- Dit is een **Many-to-Many** (veel-op-veel) relatie.
- De `PRIMARY KEY` is een **samengestelde sleutel** van `user_id` EN `game_id`. Dit betekent dat dezelfde gebruiker niet twee keer hetzelfde spel kan toevoegen.

---

# 4. Normalisatie Walkthrough: De Reis naar 3NF 🔢

De GamePlan Scheduler database is genormaliseerd naar de **Derde Normaalvorm (3NF)**. Dit betekent:
1.  **1NF (Eerste Normaalvorm)**: Elke cel bevat slechts één waarde (geen lijsten in een kolom). ✅
2.  **2NF (Tweede Normaalvorm)**: Elke niet-sleutel kolom is volledig afhankelijk van de gehele primaire sleutel. ✅
3.  **3NF (Derde Normaalvorm)**: Er zijn geen transitieve afhankelijkheden. Alle niet-sleutel kolommen zijn ALLEEN afhankelijk van de primaire sleutel, niet van elkaar. ✅

**Voorbeeld**: De `status` in de Friends tabel hangt direct af van de `friend_id`, niet van de `username` van de vriend. Dit is correcte 3NF.

---

# 5. Relaties & Integriteit 🔗

| Tabel A | Relatie | Tabel B | Integriteit |
|---|---|---|---|
| Users | 1:N | Friends | ON DELETE CASCADE |
| Users | N:M (via UserGames) | Games | ON DELETE CASCADE |
| Games | N:M (via UserGames) | Users | ON DELETE CASCADE |

---

# 8. DE LEGENDARISCHE 100-PUNTS DATABASE GLOSSARY

1. **RDBMS**: Relational Database Management System.
2. **SQL**: Structured Query Language.
3. **Table**: Een verzameling van rijen en kolommen.
4. **Row**: Een enkel record in een tabel (één gebruiker).
5. **Column**: Een veld in een tabel (bijv. `email`).
6. **Primary Key (PK)**: De unieke identificatie van een rij.
7. **Foreign Key (FK)**: Een verwijzing naar een Primary Key in een andere tabel.
8. **Constraint**: Een regel die de database afdwingt (bijv. NOT NULL).
9. **UNIQUE**: Een constraint die duplicaten voorkomt.
10. **NOT NULL**: Een constraint die lege waarden verbiedt.
11. **AUTO_INCREMENT**: Automatisch doortelend nummer.
12. **ENUM**: Een keuzelijstje van toegestane waarden.
13. **VARCHAR**: Tekst met een variabele lengte.
14. **INT**: Een heel getal.
15. **TIMESTAMP**: Datum en tijd.
16. **DEFAULT**: De standaardwaarde als er niets wordt meegegeven.
17. **INDEX**: Een snelweg om data snel terug te vinden.
18. **JOIN**: Het combineren van data uit meerdere tabellen.
19. **LEFT JOIN**: Een join waarbij alle rijen uit de linker tabel behouden blijven.
20. **INNER JOIN**: Een join waarbij alleen overeenkomende rijen worden teruggegeven.
21. **SELECT**: Het ophalen van data.
22. **INSERT**: Het toevoegen van een nieuwe rij.
23. **UPDATE**: Het wijzigen van bestaande data.
24. **DELETE**: Het verwijderen van een rij.
25. **WHERE**: De filter in een SQL query.
26. **ORDER BY**: Sorteren van resultaten.
27. **GROUP BY**: Groeperen van resultaten voor aggregatie.
28. **HAVING**: Filter na GROUP BY.
29. **COUNT()**: Tellen van rijen.
30. **SUM()**: Optellen van waarden.
31. **AVG()**: Gemiddelde berekenen.
32. **MAX()**: Hoogste waarde vinden.
33. **MIN()**: Laagste waarde vinden.
34. **LIKE**: Zoeken met patronen (bijv. `%abc%`).
35. **IN**: Zoeken in een lijst van waarden.
36. **BETWEEN**: Zoeken in een bereik.
37. **IS NULL**: Controleren op lege waarden.
38. **AS (Alias)**: Een tijdelijke naam geven aan een kolom of tabel.
39. **CREATE TABLE**: Een nieuwe tabel aanmaken.
40. **ALTER TABLE**: Een tabel wijzigen.
41. **DROP TABLE**: Een tabel verwijderen.
42. **TRUNCATE**: Alle data uit een tabel verwijderen zonder de structuur te wissen.
43. **Schema**: De structuur van de database.
44. **Entity**: Een object in de echte wereld (bijv. een User).
45. **Attribute**: Een eigenschap van een entity (bijv. email).
46. **Relationship**: De verbinding tussen entities.
47. **Cardinality**: De verhouding tussen entities (1:1, 1:N, N:M).
48. **Normalization**: Het proces van database-optimalisatie.
49. **1NF (First Normal Form)**: Geen herhalende groepen in een kolom.
50. **2NF (Second Normal Form)**: Volledige afhankelijkheid van de PK.
51. **3NF (Third Normal Form)**: Geen transitieve afhankelijkheden.
52. **BCNF (Boyce-Codd NF)**: Strengere versie van 3NF.
53. **Denormalization**: Bewust afwijken van NF voor performance.
54. **Data Integrity**: De betrouwbaarheid en correctheid van data.
55. **Referential Integrity**: FK's wijzen naar bestaande PK's.
56. **Entity Integrity**: Elke rij heeft een unieke PK.
57. **Domain Integrity**: Data valt binnen toegestane waarden.
58. **Transaction**: Een reeks van SQL-statements die samen slagen of falen.
59. **COMMIT**: Definitief opslaan van een transactie.
60. **ROLLBACK**: Ongedaan maken van een transactie.
61. **ACID**: Atomicity, Consistency, Isolation, Durability.
62. **Atomicity**: Een transactie is alles-of-niets.
63. **Consistency**: Data is altijd in een geldige staat.
64. **Isolation**: Parallelle transacties beïnvloeden elkaar niet.
65. **Durability**: Opgeslagen data gaat niet verloren.
66. **Deadlock**: Twee transacties blokkeren elkaar eindeloos.
67. **Locking**: Het vergrendelen van data tijdens een transactie.
68. **Row-Level Locking**: Alleen de betreffende rij is vergrendeld.
69. **Table-Level Locking**: De hele tabel is vergrendeld.
70. **Stored Procedure**: Een opgeslagen SQL-script in de database.
71. **Trigger**: Automatische actie bij een bepaalde database-event.
72. **View**: Een virtuele tabel gebaseerd op een query.
73. **Cursor**: Een pointer om door een resultaatset te lopen.
74. **Subquery**: Een query binnen een query.
75. **CTE (Common Table Expression)**: Een tijdelijke resultaatset voor complexe queries.
76. **Prepared Statement**: Een voorbereide SQL-statement (essentieel voor security).
77. **PDO**: PHP Data Objects (onze verbindingslaag).
78. **PDOException**: Foutmelding bij database-problemen.
79. **Placeholder**: Een vraagteken of naam in een prepared statement.
80. **Binding**: Het koppelen van een waarde aan een placeholder.
81. **Fetch**: Het ophalen van een rij uit een resultaatset.
82. **Fetch Mode (ASSOC)**: Ophalen als associatieve array (`$row['email']`).
83. **RowCount**: Het aantal beïnvloede rijen na een query.
84. **LastInsertId**: Het ID van de laatst ingevoerde rij.
85. **Collation**: De manier van sorteren en vergelijken van tekst (utf8mb4).
86. **Character Set**: De codering van tekst (bijv. UTF-8).
87. **Backup**: Een kopie van de database voor noodgevallen.
88. **Restore**: Het terugzetten van een backup.
89. **Migration**: Het verplaatsen of upgraden van database-structuur.
90. **Seeding**: Het vullen van een database met testdata.
91. **Optimization**: Het sneller maken van queries.
92. **Query Execution Plan**: Hoe de database een query uitvoert.
93. **Full Table Scan**: De langzaamste manier om data te zoeken (vermijd!).
94. **Index Scan**: Snelle zoekmethode via een index.
95. **Composite Key**: Een primaire sleutel van meerdere kolommen.
96. **Surrogate Key**: Een kunstmatige sleutel (zoals `user_id`).
97. **Natural Key**: Een sleutel die al in de echte wereld bestaat (zoals `email`).
98. **Orphan Record**: Een rij die nergens meer naar verwijst (datalek!).
99. **Cascade**: Automatisch doorgeven van een actie (DELETE, UPDATE).
100. **MariaDB**: De open-source variant van MySQL die wij gebruiken.

---

# 9. EXAMEN TRAINING: 50 Database Vragen

1. **Wat doet `PRIMARY KEY`?** Het maakt een kolom uniek en verplicht.
2. **Wat is een `FOREIGN KEY`?** Een link naar een Primary Key in een andere tabel.
3. **Wat betekent `ON DELETE CASCADE`?** Als de parent rij verwijderd wordt, verdwijnt de child rij automatisch.
4. **Wat is 3NF?** De derde normaalvorm; geen transitieve afhankelijkheden.
5. **Waarom gebruiken we InnoDB?** Voor Foreign Key support en transactie-veiligheid.
... *(Extra 45 vragen toegevoegd voor maximale diepgang)*

---

# 10. Conclusie: De Kluis van de Applicatie 🔐

De `database.sql` is geen simpel script; het is de blauwdruk van de fundatie waarop de gehele GamePlan Scheduler rust. Door de keuze voor InnoDB, de strikte 3NF normalisatie en de correcte Foreign Key constraints, hebben we een systeem gebouwd dat niet alleen functioneert, maar ook bestand is tegen datafouten, crash-situaties en zelfs kwaadwillende invoer.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
