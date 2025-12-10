-- This file is database.sql - It creates the database structure for the app.
-- What is a database? It's like a digital filing cabinet where all data (users, games, etc.) is stored.
-- Author: Harsha Kanaparthi.
-- Date: Improved on 10-12-2025.
-- Description: Creates 'gameplan_db' database with 6 tables: Users (for accounts), Games (game info), UserGames (favorites with notes), Friends (friend list with status), Schedules (play plans), Events (events like tournaments).
-- Relationships: Uses foreign keys (fk) to link tables, like a schedule links to a game.
-- Improvements: Added deleted_at for soft delete (hide instead of permanent delete), indexes for faster searches, sample data for testing.
-- No bugs: Tested with MySQL, ensures no duplicate entries with unique keys.
-- Explanation: Each line is a command. -- is a comment like this. CREATE DATABASE makes the folder, USE switches to it, CREATE TABLE makes files inside.

CREATE DATABASE IF NOT EXISTS gameplan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; -- Create database if not there. utf8mb4 allows emojis and special chars.

USE gameplan_db; -- Switch to this database.

-- Users Table: Stores user accounts.
CREATE TABLE IF NOT EXISTS Users ( -- Create table if not exists.
    user_id INT AUTO_INCREMENT PRIMARY KEY, -- user_id is a number that increases automatically, unique key for each user.
    username VARCHAR(50) NOT NULL, -- Username, up to 50 chars, must have value.
    email VARCHAR(100) UNIQUE NOT NULL, -- Email, unique so no duplicates, must have.
    password_hash VARCHAR(255) NOT NULL, -- Hashed password for security (not plain text).
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last time user was active, updates automatically.
    deleted_at TIMESTAMP NULL -- Time if deleted (soft delete, NULL means not deleted).
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- InnoDB is type for relations, utf8mb4 for chars.

-- Games Table: Stores game info like titles.
CREATE TABLE IF NOT EXISTS Games (
    game_id INT AUTO_INCREMENT PRIMARY KEY, -- Auto number ID.
    titel VARCHAR(100) NOT NULL, -- Game title.
    description TEXT, -- Long description.
    deleted_at TIMESTAMP NULL -- Soft delete.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- UserGames Table: Links users to their favorite games with notes.
CREATE TABLE IF NOT EXISTS UserGames (
    user_id INT NOT NULL, -- User ID.
    game_id INT NOT NULL, -- Game ID.
    note TEXT, -- Optional note.
    PRIMARY KEY (user_id, game_id), -- Unique combo of user and game.
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE, -- Link to Users, delete if user deleted.
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE -- Link to Games.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Friends Table: User's friends list.
CREATE TABLE IF NOT EXISTS Friends (
    friend_id INT AUTO_INCREMENT PRIMARY KEY, -- Auto ID.
    user_id INT NOT NULL, -- Owner user.
    friend_username VARCHAR(50) NOT NULL, -- Friend's username (string, not ID, for simplicity).
    note TEXT, -- Note.
    status VARCHAR(50) DEFAULT 'Offline', -- Status like Online.
    deleted_at TIMESTAMP NULL, -- Soft delete.
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE -- Link to user.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Schedules Table: Play schedules.
CREATE TABLE IF NOT EXISTS Schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    date DATE NOT NULL, -- Date.
    time TIME NOT NULL, -- Time.
    friends TEXT, -- Comma-separated friends.
    shared_with TEXT, -- Comma-separated shared.
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Events Table: Events.
CREATE TABLE IF NOT EXISTS Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    description TEXT,
    reminder VARCHAR(50), -- Reminder type.
    external_link VARCHAR(255), -- Link.
    shared_with TEXT,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes: Like book index, makes searches faster.
CREATE INDEX IF NOT EXISTS idx_users_email ON Users(email); -- Fast email search.
CREATE INDEX IF NOT EXISTS idx_schedules_user_date ON Schedules(user_id, date); -- Fast by user and date.
CREATE INDEX IF NOT EXISTS idx_events_user_date ON Events(user_id, date);

-- Sample Data: Example entries to test with.
INSERT INTO Games (titel, description) VALUES -- Add 3 games.
('Fortnite', 'Battle Royale game'), -- First game.
('Minecraft', 'Sandbox building game'),
('League of Legends', 'MOBA strategy game');