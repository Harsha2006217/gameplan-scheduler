# FILE DOCUMENTATION: database.sql (A-Z Deep Dive)
## GamePlan Scheduler - Database Schema & Creation Script

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `database.sql` | **Total Lines**: 510

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\database.sql`
**Purpose**: This script is the "Blueprint" for the entire data storage. It creates the database, all tables, defines relationships (foreign keys), and sets up performance indexes.
**Format**: Standard SQL (Structured Query Language).
**Engine**: `InnoDB` (Required for Foreign Key support).
**Charset**: `utf8mb4` (Supports full Unicode, including Emojis 🎮).

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Database Initialization (Lines 24-47)

```sql
CREATE DATABASE IF NOT EXISTS gameplan_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;
USE gameplan_db;
```

**Logic Explained**:
1.  **`IF NOT EXISTS`**: Safe to run multiple times without error.
2.  **`utf8mb4`**: Modern standard. "utf8" in MySQL is technically incomplete; `utf8mb4` is the real UTF-8.
3.  **`COLLATE ..._ci`**: Case Insensitive. Searching for "Mario" finds "mario".

---

## SECTION 2: Users Table (Lines 51-104)

**The Core Table**. Almost everything links back to this.

**Colums Explained**:
*   `user_id INT AUTO_INCREMENT`: The unique ID (1, 2, 3...).
*   `username`: Max 50 chars.
*   `email`: **UNIQUE** constraint ensures no duplicate accounts.
*   `password_hash`: **VARCHAR(255)**.
    *   *Exam Tip*: Never use `VARCHAR(50)` for passwords because hashed passwords are long strings!
*   `last_activity`: Auto-updates when user moves around (Session Tracking).
*   `deleted_at`: Supports "Soft Delete" (marking as deleted instead of erasing data).

---

## SECTION 3: Games Table (Lines 110-151)

**Purpose**: Stores the library of games (e.g., "Fortnite", "Minecraft").

**Structure**:
*   `game_id`: Primary Key.
*   `titel`: Name of the game.
*   `description`: `TEXT` type (allows long descriptions).

---

## SECTION 4: UserGames (Junction Table) (Lines 155-214)

**Concept**: Many-to-Many Relationship.
*   User 1 likes Game A and Game B.
*   Game A is liked by User 1 and User 2.

**Key Constraints**:
1.  **Composite Primary Key (`PRIMARY KEY (user_id, game_id)`)**:
    *   Prevents duplicates. You can't start "liking" Fortnite twice.
2.  **Foreign Keys (`ON DELETE CASCADE`)**:
    *   If User 1 deletes their account -> their favorites are auto-deleted.
    *   If Game A is deleted -> everyone's favorite entry for it is auto-deleted.

---

## SECTION 5: Friends Table (Lines 218-270)

**Unique Design Decision**:
*   Links `user_id` (record owner) to `friend_username` (string).
*   **Why?** Allows adding friends across platforms (Xbox, Steam) who might not have an account on this specific GamePlan Scheduler website.

**Columns**:
*   `status`: 'Online', 'Offline', 'Playing'.
*   `note`: Private notes about the friend.

---

## SECTION 6 & 7: Schedules & Events (Lines 274-410)

These tables store the actual planning data.

### Schedules (`Schedules`)
*   **Purpose**: "I am playing Game X on Date Y".
*   **Foreign Keys**: Links to both `Users` (Creator) and `Games` (What is being played).
*   **Data Types**: `DATE` (YYYY-MM-DD), `TIME` (HH:MM:SS), `TEXT` (List of friends).

### Events (`Events`)
*   **Purpose**: "Tournament/Stream happening on Date Y".
*   **Extra Columns**:
    *   `external_link`: URL to Twitch/Tournament site.
    *   `reminder`: '1_hour', '1_day' setting.

---

## SECTION 8: Performance Indexes (Lines 413-458)

**Why Indexes?**
*   Without them, finding "Event on 2026-02-02" requires scanning EVERY event.
*   With them, the database jumps instantly to that date.

**Implemented Indexes**:
1.  `idx_users_email`: For fast Login (SELECT * FROM Users WHERE email = ?).
2.  `idx_schedules_user_date`: For Dashboard (Show MY schedules for TODAY).
3.  `idx_events_user_date`: For Dashboard (Show MY events for TODAY).

---

## SECTION 9: Sample Data (Lines 462-480)

```sql
INSERT INTO Games VALUES ('Fortnite', ...), ('Minecraft', ...);
```
*   **Seeding**: Pre-fills the database so it's not empty when you first install it.

---

# 3. Entity Relationship Diagram (ERD) Text Description

```
[Users] 1 ----< [Schedules] >---- 1 [Games]
   1                ^
   |                |
   +----< [Events]  |
   |                |
   +----< [Friends] |
   |                |
   +----< [UserGames] >---- 1 [Games]
```

*   **1 ----<**: One-to-Many Relationship.
*   **>----<**: Many-to-Many Relationship (via `UserGames`).

---

# 4. Security Measures in SQL

1.  **No Plaintext**: Passwords column is named `password_hash` to enforce best practice.
2.  **Referential Integrity**: Foreign keys ensure no "orphan data" (e.g., a schedule pointing to a non-existent user).
3.  **Strict Types**: `DATE` and `INT` types prevent garbage data (like text in a date field) at the lowest level.

---

**END OF FILE DOCUMENTATION**
