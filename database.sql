-- ============================================================================
-- DATABASE.SQL - Complete Database Schema for GamePlan Scheduler
-- ============================================================================
-- Author       : Harsha Kanaparthi (Student Number: 2195344)
-- Date         : 30-09-2025
-- Version      : 1.0
-- Project      : GamePlan Scheduler - MBO-4 Software Development Examination
-- ============================================================================
-- DESCRIPTION:
-- This file creates the complete database structure for the GamePlan Scheduler
-- application. It contains 6 interconnected tables that store all user data,
-- games, friends, schedules, and events.
--
-- TABLES OVERVIEW:
-- 1. Users       - Stores user accounts (username, email, password)
-- 2. Games       - Stores game titles and descriptions
-- 3. UserGames   - Links users to their favorite games (many-to-many)
-- 4. Friends     - Stores friend relationships between users
-- 5. Schedules   - Stores gaming schedules with dates and times
-- 6. Events      - Stores gaming events like tournaments
--
-- SECURITY FEATURES:
-- - Passwords are NOT stored here (hashed in PHP with bcrypt)
-- - Soft delete via deleted_at column (data recovery possible)
-- - Foreign keys ensure data integrity
-- - Indexes for faster queries
--
-- HOW TO USE:
-- 1. Open phpMyAdmin in your browser (http://localhost/phpmyadmin)
-- 2. Click "Import" tab
-- 3. Select this file (database.sql)
-- 4. Click "Go" to execute
-- ============================================================================


-- ============================================================================
-- STEP 1: CREATE THE DATABASE
-- ============================================================================
-- This command creates a new database called "gameplan_db"
-- IF NOT EXISTS = Only create if it doesn't already exist (prevents errors)
-- CHARACTER SET utf8mb4 = Supports all characters including emojis
-- COLLATE utf8mb4_unicode_ci = Case-insensitive sorting and comparison

CREATE DATABASE IF NOT EXISTS gameplan_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Switch to use the gameplan_db database for all following commands
USE gameplan_db;


-- ============================================================================
-- STEP 2: CREATE USERS TABLE
-- ============================================================================
-- This table stores all registered user accounts.
-- Each user has a unique ID, username, email, and hashed password.
--
-- COLUMNS EXPLAINED:
-- user_id       = Unique number for each user (auto-increments: 1, 2, 3...)
-- username      = The user's display name (max 50 characters)
-- email         = User's email address (must be unique, max 100 characters)
-- password_hash = Bcrypt hashed password (never store plain text passwords!)
-- last_activity = Timestamp of last user activity (for session management)
-- deleted_at    = NULL if active, timestamp if soft-deleted

CREATE TABLE IF NOT EXISTS Users (
    -- PRIMARY KEY: Unique identifier for each user
    -- AUTO_INCREMENT: Automatically assigns next number (1, 2, 3, etc.)
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Username: The name displayed in the app
    -- NOT NULL: This field is required, cannot be empty
    -- VARCHAR(50): Variable length text, maximum 50 characters
    username VARCHAR(50) NOT NULL,
    
    -- Email: Used for login, must be unique per user
    -- UNIQUE: No two users can have the same email
    -- This prevents duplicate accounts
    email VARCHAR(100) UNIQUE NOT NULL,
    
    -- Password Hash: Secure hashed password using bcrypt
    -- VARCHAR(255): Bcrypt hashes are about 60 characters, we use 255 for safety
    -- NEVER store plain text passwords - always hash them!
    password_hash VARCHAR(255) NOT NULL,
    
    -- Last Activity: Tracks when user was last active
    -- Used for session timeout (30 minutes of inactivity = logout)
    -- DEFAULT CURRENT_TIMESTAMP: Sets to current time when row is created
    -- ON UPDATE CURRENT_TIMESTAMP: Updates automatically when row changes
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Deleted At: Soft delete marker
    -- NULL = user is active
    -- Timestamp = user was deleted at that time
    -- Soft delete means data is kept but marked as deleted (can be recovered)
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- ENGINE=InnoDB: Supports foreign keys and transactions (safer than MyISAM)
-- DEFAULT CHARSET=utf8mb4: Full Unicode support including emojis


-- ============================================================================
-- STEP 3: CREATE GAMES TABLE
-- ============================================================================
-- This table stores all games that users can add as favorites.
-- Games can be shared between multiple users.
--
-- COLUMNS EXPLAINED:
-- game_id     = Unique identifier for each game
-- titel       = The game's name (e.g., "Fortnite", "Minecraft")
-- description = Optional description of the game
-- deleted_at  = Soft delete marker

CREATE TABLE IF NOT EXISTS Games (
    -- Primary Key: Unique ID for each game
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Title: The name of the game
    -- VARCHAR(100): Maximum 100 characters for game title
    -- NOT NULL: Game must have a title
    titel VARCHAR(100) NOT NULL,
    
    -- Description: Optional text about the game
    -- TEXT: Can hold much longer text than VARCHAR
    -- Can be NULL (not required)
    description TEXT,
    
    -- Soft delete marker (same as Users table)
    deleted_at TIMESTAMP NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STEP 4: CREATE USERGAMES TABLE (Favorites)
-- ============================================================================
-- This is a JUNCTION TABLE (also called bridge table or link table).
-- It connects Users to Games in a many-to-many relationship.
-- 
-- MANY-TO-MANY means:
-- - One user can have MANY favorite games
-- - One game can be favorited by MANY users
--
-- COLUMNS EXPLAINED:
-- user_id = References which user owns this favorite
-- game_id = References which game is favorited
-- note    = Optional personal note about the game

CREATE TABLE IF NOT EXISTS UserGames (
    -- Foreign Key to Users table
    -- Links this record to a specific user
    user_id INT NOT NULL,
    
    -- Foreign Key to Games table
    -- Links this record to a specific game
    game_id INT NOT NULL,
    
    -- Personal note about the game (optional)
    -- User can write why they like this game
    note TEXT,
    
    -- COMPOSITE PRIMARY KEY: Combination of user_id AND game_id must be unique
    -- This prevents the same user from adding the same game twice
    PRIMARY KEY (user_id, game_id),
    
    -- FOREIGN KEY to Users: Ensures user_id exists in Users table
    -- ON DELETE CASCADE: If user is deleted, their favorites are also deleted
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    
    -- FOREIGN KEY to Games: Ensures game_id exists in Games table
    -- ON DELETE CASCADE: If game is deleted, all favorites for that game are deleted
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STEP 5: CREATE FRIENDS TABLE
-- ============================================================================
-- This table stores friend relationships between users.
-- Users can add friends by username, with optional notes and status.
--
-- NOTE: friend_username is stored as text, not a foreign key.
-- This allows adding friends even if they haven't registered yet.
--
-- COLUMNS EXPLAINED:
-- friend_id        = Unique ID for this friend relationship
-- user_id          = The user who added the friend
-- friend_username  = Username of the friend
-- note             = Optional note about the friend
-- status           = Friend's current status (Online, Offline, Playing, etc.)
-- deleted_at       = Soft delete marker

CREATE TABLE IF NOT EXISTS Friends (
    -- Primary Key: Unique ID for each friend relationship
    friend_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Foreign Key to Users: Who owns this friend entry
    user_id INT NOT NULL,
    
    -- Friend's Username: Stored as text (not a foreign key)
    -- VARCHAR(50): Maximum 50 characters, same as username field
    -- NOT NULL: Must provide a username
    friend_username VARCHAR(50) NOT NULL,
    
    -- Note: Optional personal note about this friend
    -- Example: "Met in Fortnite tournament", "School friend"
    note TEXT,
    
    -- Status: Current status of the friend
    -- DEFAULT 'Offline': If not specified, friend is shown as offline
    status VARCHAR(50) DEFAULT 'Offline',
    
    -- Soft delete marker
    deleted_at TIMESTAMP NULL,
    
    -- FOREIGN KEY: Links to Users table
    -- ON DELETE CASCADE: If user deletes account, their friends list is also deleted
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STEP 6: CREATE SCHEDULES TABLE
-- ============================================================================
-- This table stores gaming schedules - when users plan to play games.
-- Users can share schedules with friends.
--
-- COLUMNS EXPLAINED:
-- schedule_id = Unique ID for each schedule
-- user_id     = Who created this schedule
-- game_id     = Which game will be played
-- date        = The date of the gaming session
-- time        = The time of the gaming session
-- friends     = Comma-separated list of friends joining
-- shared_with = Comma-separated list of users who can see this schedule
-- deleted_at  = Soft delete marker

CREATE TABLE IF NOT EXISTS Schedules (
    -- Primary Key: Unique ID for each schedule
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Foreign Key to Users: Who created this schedule
    user_id INT NOT NULL,
    
    -- Foreign Key to Games: Which game will be played
    game_id INT NOT NULL,
    
    -- Date: When the gaming session is planned
    -- DATE format: YYYY-MM-DD (e.g., 2025-09-30)
    -- NOT NULL: Date is required
    date DATE NOT NULL,
    
    -- Time: At what time the session starts
    -- TIME format: HH:MM:SS (e.g., 20:00:00)
    -- NOT NULL: Time is required
    time TIME NOT NULL,
    
    -- Friends: Comma-separated usernames of friends joining
    -- TEXT: Can hold long list of usernames
    -- Example: "Player1, Player2, Player3"
    friends TEXT,
    
    -- Shared With: Comma-separated usernames who can see this schedule
    -- Used for privacy - only shared users can view
    shared_with TEXT,
    
    -- Soft delete marker
    deleted_at TIMESTAMP NULL,
    
    -- FOREIGN KEYS with CASCADE delete
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES Games(game_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STEP 7: CREATE EVENTS TABLE
-- ============================================================================
-- This table stores gaming events like tournaments, streams, or special occasions.
-- Events can have reminders and external links.
--
-- COLUMNS EXPLAINED:
-- event_id      = Unique ID for each event
-- user_id       = Who created this event
-- title         = Name of the event (e.g., "Fortnite Tournament")
-- date          = Date of the event
-- time          = Time of the event
-- description   = Details about the event
-- reminder      = When to remind user (none, 1_hour, 1_day)
-- external_link = URL to event page, stream, etc.
-- shared_with   = Users who can see this event
-- deleted_at    = Soft delete marker

CREATE TABLE IF NOT EXISTS Events (
    -- Primary Key: Unique ID for each event
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Foreign Key to Users: Who created this event
    user_id INT NOT NULL,
    
    -- Title: Name of the event
    -- VARCHAR(100): Maximum 100 characters
    -- NOT NULL: Every event needs a title
    title VARCHAR(100) NOT NULL,
    
    -- Date: When the event takes place
    -- DATE format: YYYY-MM-DD
    date DATE NOT NULL,
    
    -- Time: What time the event starts
    -- TIME format: HH:MM:SS
    time TIME NOT NULL,
    
    -- Description: Details about the event
    -- TEXT: Can hold long descriptions
    -- Optional field (can be NULL)
    description TEXT,
    
    -- Reminder: When to show reminder notification
    -- Options: 'none', '1_hour', '1_day'
    -- VARCHAR(50): Stores the reminder type as text
    reminder VARCHAR(50),
    
    -- External Link: URL to more information
    -- VARCHAR(255): URLs can be long, 255 chars should be enough
    -- Example: "https://twitch.tv/tournament"
    external_link VARCHAR(255),
    
    -- Shared With: Comma-separated usernames
    -- Who can see this event
    shared_with TEXT,
    
    -- Soft delete marker
    deleted_at TIMESTAMP NULL,
    
    -- FOREIGN KEY with CASCADE delete
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================================================
-- STEP 8: CREATE INDEXES FOR PERFORMANCE
-- ============================================================================
-- INDEXES make database queries faster by creating a lookup table.
-- Think of it like an index in a book - you can find pages faster.
--
-- We create indexes on columns that are frequently searched:
-- - email (used for login)
-- - user_id + date (used for calendar views)

-- Index on Users.email for faster login queries
-- When user logs in, we search by email - this makes it fast
CREATE INDEX IF NOT EXISTS idx_users_email 
    ON Users(email);

-- Index on Schedules for calendar queries
-- We often query schedules by user_id and date together
CREATE INDEX IF NOT EXISTS idx_schedules_user_date 
    ON Schedules(user_id, date);

-- Index on Events for calendar queries
-- Same reason as schedules - faster calendar loading
CREATE INDEX IF NOT EXISTS idx_events_user_date 
    ON Events(user_id, date);


-- ============================================================================
-- STEP 9: INSERT SAMPLE DATA
-- ============================================================================
-- These are example games so the application starts with some content.
-- Users can add their own games later.

INSERT INTO Games (titel, description) VALUES
    ('Fortnite', 'Popular Battle Royale game with building mechanics'),
    ('Minecraft', 'Creative sandbox game with blocks and building'),
    ('League of Legends', 'Competitive MOBA team strategy game');


-- ============================================================================
-- DATABASE SCHEMA COMPLETE!
-- ============================================================================
-- Summary of what was created:
-- ✓ 1 Database: gameplan_db
-- ✓ 6 Tables: Users, Games, UserGames, Friends, Schedules, Events
-- ✓ 3 Indexes: For faster queries on email and calendar views
-- ✓ 3 Sample Games: Fortnite, Minecraft, League of Legends
--
-- NEXT STEPS:
-- 1. Import this file in phpMyAdmin
-- 2. Configure db.php with correct credentials
-- 3. Start using the application!
--
-- © 2025 GamePlan Scheduler by Harsha Kanaparthi
-- ============================================================================