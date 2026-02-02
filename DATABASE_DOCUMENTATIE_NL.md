# 🗄️ DATABASE DOCUMENTATIE (NEDERLANDS)
## GamePlan Scheduler - Database Architectuur & ERD

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer

---

# 1. ERD (Entity Relationship Diagram)

```mermaid
erDiagram
    Users ||--o{ Schedules : creates
    Users ||--o{ Events : organizes
    Users ||--o{ Friends : has
    Users ||--o{ User_Games : plays
    Games ||--o{ User_Games : is_played_by
    Games ||--o{ Schedules : is_scheduled

    Users {
        int user_id PK
        string username
        string email
        string password_hash
        timestamp created_at
    }

    Games {
        int game_id PK
        string game_title
        string description
    }

    Schedules {
        int schedule_id PK
        int user_id FK
        int game_id FK
        date date
        time time
    }

    Friends {
        int friend_id PK
        int user_id FK
        string friend_name
        string status
    }
```

# 2. Tabel Definities

## 2.1 USERS
Deze tabel slaat de kerngegevens van de gebruikers op.
- **user_id**: Unieke sleutel (Primary Key).
- **username**: De publieke naam.
- **email**: Uniek veld voor inloggen.
- **password_hash**: Gehasht met **BCRYPT** (nooit plain text!).

## 2.2 GAMES
Slaat alle unieke spellen in het systeem op.
- **getOrCreateGameId()**: Deze logica zorgt dat we geen dubbele games in de tabel krijgen.

## 2.3 SCHEDULES
De relatie tussen een gebruiker, een spel en een tijdstip.
- **Foreign Keys**: Koppelt Gebruiker en Spel.

# 3. SQL Queries (Voorbeelden)

### Gebruiker Inloggen:
```sql
SELECT user_id, username, password_hash 
FROM Users 
WHERE email = :email AND deleted_at IS NULL;
```

### Dashboard Overzicht:
```sql
SELECT s.*, g.game_title 
FROM Schedules s 
JOIN Games g ON s.game_id = g.game_id 
WHERE s.user_id = :user_id 
ORDER BY s.date ASC;
```

---
**EINDE DATABASE DOCUMENTATIE**
