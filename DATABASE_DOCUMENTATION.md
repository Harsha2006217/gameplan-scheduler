# DATABASE DOCUMENTATION
## GamePlan Scheduler - Complete Database Schema

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 19-01-2026

---

# 1. Database Overview / Database Overzicht

**Database Name**: `gameplan_db`
**Character Set**: `utf8mb4` (supports emojis and all Unicode)
**Engine**: `InnoDB` (supports foreign keys and transactions)

## Number of Tables / Aantal Tabellen: 6

| # | Table | Purpose EN | Purpose NL |
|---|-------|------------|------------|
| 1 | **Users** | Stores user accounts | Slaat gebruikersaccounts op |
| 2 | **Games** | Stores all games | Slaat alle spellen op |
| 3 | **UserGames** | Links users to favorite games | Linkt gebruikers aan favoriete spellen |
| 4 | **Friends** | Stores friend lists | Slaat vriendenlijsten op |
| 5 | **Schedules** | Stores gaming schedules | Slaat gaming schema's op |
| 6 | **Events** | Stores gaming events | Slaat gaming evenementen op |

---

# 2. Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ENTITY RELATIONSHIP DIAGRAM (ERD)                         │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌───────────────┐
    │    USERS      │ ─── Main table, all others link to this
    │───────────────│
    │ user_id (PK)  │
    │ username      │
    │ email         │
    │ password_hash │
    │ last_activity │
    │ deleted_at    │
    └───────┬───────┘
            │
            │ 1:N (One user has many...)
            │
    ┌───────┼───────────────────────────────────────────┐
    │       │               │               │           │
    │       │               │               │           │
    ▼       ▼               ▼               ▼           ▼
┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────────┐
│ Friends │ │Schedules│ │ Events  │ │UserGames│ │   Games     │
│─────────│ │─────────│ │─────────│ │─────────│ │─────────────│
│friend_id│ │sched_id │ │event_id │ │user_id  │◄──────────────┘
│user_id  │ │user_id  │ │user_id  │ │game_id  │     N:M
│friend_  │ │game_id  │ │title    │ │note     │  (Many-to-Many)
│username │ │date     │ │date     │ │         │
│note     │ │time     │ │time     │ └─────────┘
│status   │ │friends  │ │descript │
│deleted  │ │shared   │ │reminder │
└─────────┘ │deleted  │ │ext_link │
            └────┬────┘ │shared   │
                 │      │deleted  │
                 ▼      └─────────┘
            ┌─────────┐
            │  Games  │
            │─────────│
            │game_id  │
            │titel    │
            │descript │
            │deleted  │
            └─────────┘

LEGEND:
───────
PK = Primary Key (unique identifier)
FK = Foreign Key (links to another table)
1:N = One-to-Many relationship
N:M = Many-to-Many relationship (uses junction table)
```

---

# 3. Detailed Table Specifications

## 3.1 Users Table / Gebruikers Tabel

**Purpose**: Stores all registered user accounts.
**Doel**: Slaat alle geregistreerde gebruikersaccounts op.

| Column | Data Type | Constraints | Description EN | Description NL |
|--------|-----------|-------------|----------------|----------------|
| `user_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique user ID | Unieke gebruiker ID |
| `username` | VARCHAR(50) | NOT NULL | Display name | Weergavenaam |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Login email | Login e-mail |
| `password_hash` | VARCHAR(255) | NOT NULL | Bcrypt encrypted password | Bcrypt versleuteld wachtwoord |
| `last_activity` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE | Last active time | Laatst actieve tijd |
| `deleted_at` | TIMESTAMP | NULL | Soft delete marker | Soft delete markering |

**Relationships**:
- Has many Friends (1:N)
- Has many Schedules (1:N)
- Has many Events (1:N)
- Has many UserGames (1:N) → Links to Games

---

## 3.2 Games Table / Spellen Tabel

**Purpose**: Stores all games available in the system.
**Doel**: Slaat alle beschikbare spellen in het systeem op.

| Column | Data Type | Constraints | Description EN | Description NL |
|--------|-----------|-------------|----------------|----------------|
| `game_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique game ID | Unieke spel ID |
| `titel` | VARCHAR(100) | NOT NULL | Game title | Speltitel |
| `description` | TEXT | NULL | Game description | Spelbeschrijving |
| `deleted_at` | TIMESTAMP | NULL | Soft delete marker | Soft delete markering |

**Relationships**:
- Has many UserGames (1:N) → Links to Users
- Has many Schedules (1:N)

**Sample Data**:
- Fortnite
- Minecraft
- League of Legends

---

## 3.3 UserGames Table (Junction) / Koppeltabel

**Purpose**: Many-to-Many relationship between Users and Games (favorites).
**Doel**: Veel-op-veel relatie tussen Gebruikers en Spellen (favorieten).

| Column | Data Type | Constraints | Description EN | Description NL |
|--------|-----------|-------------|----------------|----------------|
| `user_id` | INT | NOT NULL, FK → Users | User reference | Gebruiker referentie |
| `game_id` | INT | NOT NULL, FK → Games | Game reference | Spel referentie |
| `note` | TEXT | NULL | Personal note about game | Persoonlijke notitie |

**Primary Key**: Composite (user_id, game_id)
**Foreign Keys**:
- user_id → Users(user_id) ON DELETE CASCADE
- game_id → Games(game_id) ON DELETE CASCADE

---

## 3.4 Friends Table / Vrienden Tabel

**Purpose**: Stores user's gaming friends list.
**Doel**: Slaat vriendenlijst van gebruiker op.

| Column | Data Type | Constraints | Description EN | Description NL |
|--------|-----------|-------------|----------------|----------------|
| `friend_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique friend entry ID | Unieke vriend ID |
| `user_id` | INT | NOT NULL, FK → Users | Owner of friend list | Eigenaar van vriendenlijst |
| `friend_username` | VARCHAR(50) | NOT NULL | Friend's gamertag | Gamertag van vriend |
| `note` | TEXT | NULL | Note about friend | Notitie over vriend |
| `status` | VARCHAR(50) | DEFAULT 'Offline' | Online status | Online status |
| `deleted_at` | TIMESTAMP | NULL | Soft delete marker | Soft delete markering |

**Foreign Key**: user_id → Users(user_id) ON DELETE CASCADE

---

## 3.5 Schedules Table / Schema's Tabel

**Purpose**: Stores gaming play schedules.
**Doel**: Slaat gaming speelschema's op.

| Column | Data Type | Constraints | Description EN | Description NL |
|--------|-----------|-------------|----------------|----------------|
| `schedule_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique schedule ID | Unieke schema ID |
| `user_id` | INT | NOT NULL, FK → Users | Creator | Aanmaker |
| `game_id` | INT | NOT NULL, FK → Games | Game to play | Spel om te spelen |
| `date` | DATE | NOT NULL | Schedule date | Schema datum |
| `time` | TIME | NOT NULL | Schedule time | Schema tijd |
| `friends` | TEXT | NULL | Comma-separated friends | Komma-gescheiden vrienden |
| `shared_with` | TEXT | NULL | Comma-separated viewers | Komma-gescheiden kijkers |
| `deleted_at` | TIMESTAMP | NULL | Soft delete marker | Soft delete markering |

**Foreign Keys**:
- user_id → Users(user_id) ON DELETE CASCADE
- game_id → Games(game_id) ON DELETE CASCADE

**Index**: idx_schedules_user_date (user_id, date)

---

## 3.6 Events Table / Evenementen Tabel

**Purpose**: Stores gaming events like tournaments.
**Doel**: Slaat gaming evenementen op zoals toernooien.

| Column | Data Type | Constraints | Description EN | Description NL |
|--------|-----------|-------------|----------------|----------------|
| `event_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique event ID | Unieke evenement ID |
| `user_id` | INT | NOT NULL, FK → Users | Creator | Aanmaker |
| `title` | VARCHAR(100) | NOT NULL | Event title | Evenement titel |
| `date` | DATE | NOT NULL | Event date | Evenement datum |
| `time` | TIME | NOT NULL | Event time | Evenement tijd |
| `description` | TEXT | NULL | Event details | Evenement details |
| `reminder` | VARCHAR(50) | NULL | Reminder setting | Herinnering instelling |
| `external_link` | VARCHAR(255) | NULL | Link to event page | Link naar evenementpagina |
| `shared_with` | TEXT | NULL | Comma-separated viewers | Komma-gescheiden kijkers |
| `deleted_at` | TIMESTAMP | NULL | Soft delete marker | Soft delete markering |

**Foreign Key**: user_id → Users(user_id) ON DELETE CASCADE

**Index**: idx_events_user_date (user_id, date)

---

# 4. Relationships Summary / Relaties Samenvatting

## One-to-Many (1:N) Relationships

| Parent Table | Child Table | Relationship |
|--------------|-------------|--------------|
| Users | Friends | One user has many friends |
| Users | Schedules | One user has many schedules |
| Users | Events | One user has many events |
| Users | UserGames | One user has many favorites |
| Games | Schedules | One game in many schedules |
| Games | UserGames | One game favorited by many |

## Many-to-Many (N:M) Relationship

| Table 1 | Junction Table | Table 2 | Relationship |
|---------|----------------|---------|--------------|
| Users | UserGames | Games | Users ↔ Favorite Games |

---

# 5. Indexes / Indexen

| Index Name | Table | Column(s) | Purpose EN | Purpose NL |
|------------|-------|-----------|------------|------------|
| idx_users_email | Users | email | Speed up login queries | Versnel login queries |
| idx_schedules_user_date | Schedules | user_id, date | Speed up schedule lookup | Versnel schema opzoeken |
| idx_events_user_date | Events | user_id, date | Speed up event lookup | Versnel evenement opzoeken |

---

# 6. Soft Delete Explanation / Soft Delete Uitleg

**What is Soft Delete? / Wat is Soft Delete?**

Instead of permanently deleting records with `DELETE FROM`, we set a `deleted_at` timestamp.

| Hard Delete | Soft Delete |
|-------------|-------------|
| `DELETE FROM Users WHERE id=5` | `UPDATE Users SET deleted_at=NOW() WHERE id=5` |
| Data is gone forever | Data is still in database |
| Cannot recover | Can be recovered |
| No audit trail | Full audit trail |

**Why Use Soft Delete? / Waarom Soft Delete Gebruiken?**

1. **Data Recovery** - Can "undelete" if needed
2. **Audit Trail** - Track when something was deleted
3. **Referential Integrity** - Linked data doesn't break
4. **Legal Compliance** - Some data must be kept

**How to Query Non-Deleted Records**:
```sql
SELECT * FROM Users WHERE deleted_at IS NULL;
```

---

# 7. CASCADE Delete Explanation

**What is CASCADE? / Wat is CASCADE?**

When a parent record is deleted, automatically delete all linked child records.

**Example**:
```
User 1 is deleted
    ├── User 1's Friends → automatically deleted
    ├── User 1's Schedules → automatically deleted
    ├── User 1's Events → automatically deleted
    └── User 1's UserGames → automatically deleted
```

**SQL Foreign Key with CASCADE**:
```sql
FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
```

---

# 8. Security Features in Database / Beveiligingsfuncties

| Feature | Implementation | Why Important |
|---------|----------------|---------------|
| **Password Hashing** | password_hash VARCHAR(255) | Stores bcrypt hash, never plain text |
| **Unique Email** | email UNIQUE | Prevents duplicate accounts |
| **Soft Delete** | deleted_at TIMESTAMP | Maintains data integrity |
| **Foreign Keys** | REFERENCES with CASCADE | Ensures data consistency |
| **utf8mb4** | CHARACTER SET | Supports all characters safely |

---

# 9. Sample SQL Queries / Voorbeeld SQL Queries

## Get all schedules for a user:
```sql
SELECT s.*, g.titel as game_name
FROM Schedules s
JOIN Games g ON s.game_id = g.game_id
WHERE s.user_id = :user_id 
  AND s.deleted_at IS NULL
ORDER BY s.date, s.time;
```

## Get user's favorite games:
```sql
SELECT g.*, ug.note
FROM UserGames ug
JOIN Games g ON ug.game_id = g.game_id
WHERE ug.user_id = :user_id
  AND g.deleted_at IS NULL;
```

## Login query:
```sql
SELECT user_id, username, password_hash
FROM Users
WHERE email = :email
  AND deleted_at IS NULL;
```

---

**END OF DATABASE DOCUMENTATION**

This document provides complete database schema documentation for the GamePlan Scheduler application.
Ready for MBO-4 examination!
