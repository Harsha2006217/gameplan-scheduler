-- database.sql - SQL Database Schema Definition
-- Author: Harsha Kanaparthi
-- Date: 30-09-2025
-- Description:
-- This script builds the entire database structure (tables, relationships) 
-- for the GamePlan Scheduler application.
-- 
-- Instructions: Run this in phpMyAdmin's "SQL" tab to install.

-- 1. Create Database if it doesn't exist
-- Character set utf8mb4 supports all languages and emojis.
CREATE DATABASE IF NOT EXISTS gameplan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gameplan_db;

-- 2. Users Table
-- Stores login information. Passwords are encrypted (hashed).
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each user
    username VARCHAR(50) NOT NULL,          -- Display name
    email VARCHAR(100) UNIQUE NOT NULL,     -- Email (must be unique)
    password_hash VARCHAR(255) NOT NULL,    -- Encrypted password (Bcrypt)
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- For session tracking
    deleted_at TIMESTAMP NULL               -- For 'Soft Delete' (account removal without data loss)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Games Table
-- Global list of games. Can be added to by users, but shared structure.
CREATE TABLE IF NOT EXISTS Games (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(100) NOT NULL,
    description TEXT,
    deleted_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. UserGames Table (Junction Table)
-- Connects Users to Games ("Favorites"). 
-- Allows a Many-to-Many relationship (User likes many games, Game liked by many users).
CREATE TABLE IF NOT EXISTS UserGames (
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    note TEXT, -- Personal note about why they like it
    PRIMARY KEY (user_id, game_id), -- Composite Key: prevent duplicate favorite of same game
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE, -- If User deleted, delete favorites
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Friends Table
-- Stores friend connections. Simple list per user.
CREATE TABLE IF NOT EXISTS Friends (
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_username VARCHAR(50) NOT NULL, -- We link by name string for simplicity in this MVP
    note TEXT,                            -- Private note about friend
    status VARCHAR(50) DEFAULT 'Offline', -- Online/Offline status
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Schedules Table
-- Tracks gaming sessions.
CREATE TABLE IF NOT EXISTS Schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    friends TEXT,        -- Comma separated list (denormalized for simplicity)
    shared_with TEXT,    -- Comma separated list of viewers
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Events Table
-- Tracks larger events like Tournaments.
CREATE TABLE IF NOT EXISTS Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    description TEXT,
    reminder VARCHAR(50),      -- 'none', '1_hour', '1_day'
    external_link VARCHAR(255), -- Discord or Tournament URL
    shared_with TEXT,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Performance Indexes
-- Indexes make searching faster.
CREATE INDEX IF NOT EXISTS idx_users_email ON Users(email);
CREATE INDEX IF NOT EXISTS idx_schedules_user_date ON Schedules(user_id, date);
CREATE INDEX IF NOT EXISTS idx_events_user_date ON Events(user_id, date);

-- 9. Sample Data
-- Initial data to populate the app for testing.
INSERT INTO Games (titel, description) VALUES
('Fortnite', 'Battle Royale game'),
('Minecraft', 'Sandbox building game'),
('League of Legends', 'MOBA strategy game');