-- ============================================================================
-- database.sql - DATABASE SCHEMA | HARSHA KANAPARTHI | 2195344
-- ============================================================================
-- WAT DOET DIT? Maakt de database structuur aan met alle tabellen.
-- HOE GEBRUIKEN? Importeer dit bestand in phpMyAdmin
-- ============================================================================

CREATE DATABASE IF NOT EXISTS gameplan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gameplan_db;

-- USERS TABEL: Slaat gebruikersaccounts op
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unieke ID voor elke gebruiker
    username VARCHAR(50) NOT NULL,           -- Gebruikersnaam
    email VARCHAR(100) UNIQUE NOT NULL,      -- E-mail (moet uniek zijn)
    password_hash VARCHAR(255) NOT NULL,     -- Gehashte wachtwoord (bcrypt)
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL                -- Soft delete timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- GAMES TABEL: Lijst van alle games
CREATE TABLE IF NOT EXISTS Games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(100) NOT NULL,
    description TEXT,
    deleted_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- USERGAMES TABEL: Koppeling gebruiker-favoriete games
CREATE TABLE IF NOT EXISTS UserGames (
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    note TEXT,
    PRIMARY KEY (user_id, game_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FRIENDS TABEL: Vrienden lijst
CREATE TABLE IF NOT EXISTS Friends (
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_username VARCHAR(50) NOT NULL,
    note TEXT,
    status VARCHAR(50) DEFAULT 'Offline',
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SCHEDULES TABEL: Speelschema's
CREATE TABLE IF NOT EXISTS Schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    friends TEXT,
    shared_with TEXT,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- EVENTS TABEL: Evenementen (toernooien, etc.)
CREATE TABLE IF NOT EXISTS Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    description TEXT,
    reminder VARCHAR(50),
    external_link VARCHAR(255),
    shared_with TEXT,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- INDEXES voor snellere queries
CREATE INDEX idx_users_email ON Users(email);
CREATE INDEX idx_schedules_user_date ON Schedules(user_id, date);
CREATE INDEX idx_events_user_date ON Events(user_id, date);

-- VOORBEELD DATA
INSERT INTO Games (titel, description) VALUES
('Fortnite', 'Battle Royale game'),
('Minecraft', 'Sandbox building game'),
('League of Legends', 'MOBA strategy game');