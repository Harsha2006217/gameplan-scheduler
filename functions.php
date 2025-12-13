<?php
// functions.php - Core Functions and Database Queries
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Version: 1.1 (Legendary Edition)
// Description: 
// This file is the "Engine Room" of the application. It contains all the reusable code (functions)
// for database interactions, user validation, and security measures.
//
// NL: Dit bestand is de "Motor" van de applicatie. Het bevat alle functies voor database interactie,
// validatie van invoer en beveiliging.

// --- 0. Setup ---

// Start output buffering. This is a technical trick to capture all HTML output in a memory buffer 
// before sending it to the browser. This prevents "Headers already sent" errors when we use header().
ob_start();

// Include the database connection script. 'require_once' ensures it's only loaded once.
require_once 'db.php';

// --- Session Management ---
// Sessions allow us to remember who the user is as they move from page to page.
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already running.
    session_regenerate_id(true); // Security: Generate a new session ID to prevent Session Hijacking.
}

// --- 1. Helper Functions (Hulp Functies) ---

/**
 * Function: safeEcho
 * Purpose: Protects against XSS (Cross-Site Scripting) attacks.
 * Explanation: Before printing user input to the screen, we must convert special characters 
 * (like < and >) into harmless codes. This prevents malicious scripts from running.
 * 
 * @param string $string The unsafe text.
 * @return string The safe, clean text.
 */
function safeEcho($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Function: validateRequired (BUG FIX #1001 Implemented)
 * Purpose: Checks if a field is empty or contains only spaces.
 * Explanation: We use trim() to remove spaces from the start and end. 
 * If the result is empty, the user just typed spaces.
 * 
 * @param string $value The input to check.
 * @param string $fieldName The name of the field for the error message.
 * @param int $maxLength Optional maximum length check.
 * @return string|null Returns error message string if invalid, or null if valid.
 */
function validateRequired($value, $fieldName, $maxLength = 0)
{
    // BUG FIX #1001: trim() removes invisible spaces.
    // ' ' -> '' (empty).
    $value = trim($value);

    // Check if empty OR if it matches a pattern of only whitespace
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        return "$fieldName may not be empty or contain only spaces.";
    }

    // Check line length if a max length is set
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName exceeds maximum length of $maxLength characters.";
    }

    return null; // No errors
}

/**
 * Function: validateDate (BUG FIX #1004 Implemented)
 * Purpose: Ensures the date is a real calendar date and is in the future.
 * Explanation: '2025-02-30' is invalid (Feb has max 29 days). We catch this here.
 * 
 * @param string $date The date string (YYYY-MM-DD).
 * @return string|null Error message or null.
 */
function validateDate($date)
{
    // BUG FIX #1004: Strict date checking using checkdate().
    // Format expected: YYYY-MM-DD
    $parts = explode('-', $date);
    if (count($parts) == 3) {
        // specific check: month, day, year
        if (!checkdate($parts[1], $parts[2], $parts[0])) {
            return "Invalid date: This date does not exist in the calendar.";
        }
    } else {
        return "Invalid date format.";
    }

    // Check if date is in the past (only future events allowed)
    // strtotime converts string to a timestamp number.
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        return "Date must be in the future.";
    }

    return null; // Valid
}

/**
 * Function: validateTime
 * Purpose: Checks if time is in HH:MM format using Regular Expressions (Regex).
 */
function validateTime($time)
{
    // Regex explanation:
    // ^ = start
    // ([01]?[0-9]|2[0-3]) = 00-19 OR 20-23 (Hours)
    // : = separator
    // [0-5][0-9] = 00-59 (Minutes)
    // $ = end
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
        return "Invalid time format (HH:MM).";
    }
    return null;
}

/**
 * Function: validateEmail
 * Purpose: Uses PHP's built-in filter to check for valid email format (@ and .).
 */
function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return null;
}

/**
 * Function: validateUrl
 * Purpose: Checks if a string is a valid URL (http://...).
 */
function validateUrl($url)
{
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid URL format.";
    }
    return null;
}

/**
 * Function: validateCommaSeparated
 * Purpose: Checks lists like "Tom, Jerry, Spike" to ensure no empty items exist.
 */
function validateCommaSeparated($value, $fieldName)
{
    if (empty($value))
        return null; // Empty is allowed (optional field)
    $items = explode(',', $value); // Split by comma
    foreach ($items as $item) {
        $item = trim($item); // Remove spaces
        if (empty($item))
            return "$fieldName contains empty items.";
    }
    return null;
}

// --- Session Message Functions ---
// These functions help show green success bars or red error bars on the next page.

function setMessage($type, $msg)
{
    // Store message in user session variable (server-side memory)
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg];
}

function getMessage()
{
    // Check if there is a message waiting
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']); // Remove after showing once (Flash message)
        // Return HTML for Bootstrap Alert component
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show' role='alert'>
                    {$msg['msg']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
    return '';
}

// --- Auth Helpers ---

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getUserId()
{
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}

function updateLastActivity($pdo, $userId)
{
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
}

function checkSessionTimeout()
{
    // 1800 seconds = 30 minutes
    if (isLoggedIn() && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy(); // Kill session
        header("Location: login.php?msg=session_timeout"); // Kick to login
        exit;
    }
    $_SESSION['last_activity'] = time(); // Reset timer
}

// --- 2. User Authentication (Inloggen/Registreren) ---

/**
 * Function: registerUser
 * Purpose: Creates a new user in the database.
 * Security: Uses password_hash() (Bcrypt) to secure passwords.
 */
function registerUser($username, $email, $password)
{
    $pdo = getDBConnection(); // Open connection

    // Step 1: Validate all inputs
    if ($err = validateRequired($username, "Username", 50))
        return $err;
    if ($err = validateEmail($email))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;
    if (strlen($password) < 8)
        return "Password must be at least 8 characters.";

    // Step 2: Check for duplicate email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0)
        return "Email already registered.";

    // Step 3: Hash the password (one-way encryption)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Step 4: Save to Database
    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");
    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null; // Returns null on success
    } catch (PDOException $e) {
        error_log("Registration failed: " . $e->getMessage());
        return "Registration failed. Please try again.";
    }
}

/**
 * Function: loginUser
 * Purpose: Verifies credentials and starts a session.
 */
function loginUser($email, $password)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($email, "Email"))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;

    // Retrieve the user by email
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Verify password match against the stored hash
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return "Invalid email or password.";
    }

    // Success: Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    session_regenerate_id(true); // Prevent fixation attacks
    updateLastActivity($pdo, $user['user_id']);

    return null;
}

function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}

// --- 3. Profile & Games (Profiel & Favoriete Spellen) ---

function getOrCreateGameId($pdo, $title, $description = '')
{
    $title = trim($title);
    if (empty($title))
        return 0;

    // Check if game exists globally in the games table
    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL");
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();

    if ($row)
        return $row['game_id'];

    // Else check if it exists but was 'soft deleted' and restore it, OR create new
    // For simplicity, we just insert new if not active.
    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);
    return $pdo->lastInsertId();
}

function addFavoriteGame($userId, $title, $description = '', $note = '')
{
    $pdo = getDBConnection();

    // Bug Fix #1001 applied via validateRequired
    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    $gameId = getOrCreateGameId($pdo, $title, $description);

    // Prevent duplicate favorites for same user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0)
        return "Game already in favorites.";

    // Link user to game
    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);
    return null;
}

function updateFavoriteGame($userId, $gameId, $title, $description, $note)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Check permissions
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0)
        return "No permission to edit.";

    // Update main game info (Global update - cautious decision)
    // Note: In a real multi-user app, we might check if other users use this game before changing title.
    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => $title, 'description' => $description, 'game_id' => $gameId]);

    // Update personal note
    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();
    // Simply unlink the user from the game
    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

function getFavoriteGames($userId)
{
    $pdo = getDBConnection();
    // SQL JOIN: Combine Games info with UserGames note
    $stmt = $pdo->prepare("SELECT g.game_id, g.titel, g.description, ug.note FROM UserGames ug JOIN Games g ON ug.game_id = g.game_id WHERE ug.user_id = :user_id AND g.deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// --- 4. Friends (Vrienden) ---

function addFriend($userId, $friendUsername, $note = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Check duplication
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername]);
    if ($stmt->fetchColumn() > 0)
        return "Already friends.";

    // Insert
    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_username, note, status) VALUES (:user_id, :friend_username, :note, :status)");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername, 'note' => $note, 'status' => $status]);
    return null;
}

function updateFriend($userId, $friendId, $friendUsername, $note, $status)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Verify ownership first
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    if ($stmt->fetchColumn() == 0)
        return "Not friends or no permission.";

    $stmt = $pdo->prepare("UPDATE Friends SET friend_username = :friend_username, note = :note, status = :status WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['friend_username' => $friendUsername, 'note' => $note, 'status' => $status, 'user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

function deleteFriend($userId, $friendId)
{
    $pdo = getDBConnection();
    // Soft Delete: Mark deleted_at timestamp instead of removing row. Data recovery possible.
    $stmt = $pdo->prepare("UPDATE Friends SET deleted_at = NOW() WHERE user_id = :user_id AND friend_id = :friend_id");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

function getFriends($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT friend_id, friend_username as username, status, note FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// --- 5. Schedules (Speelschema's) ---

function addSchedule($userId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Validate inputs
    if ($err = validateRequired($gameTitle, "Game title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err; // Uses new strict date check
    if ($err = validateTime($time))
        return $err;
    if ($err = validateCommaSeparated($friendsStr, "Friends"))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $gameId = getOrCreateGameId($pdo, $gameTitle);

    // Create Schedule
    $stmt = $pdo->prepare("INSERT INTO Schedules (user_id, game_id, date, time, friends, shared_with) VALUES (:user_id, :game_id, :date, :time, :friends, :shared_with)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'date' => $date, 'time' => $time, 'friends' => $friendsStr, 'shared_with' => $sharedWithStr]);
    return null;
}

function getSchedules($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();
    // Security: Whitelist sort options to prevent SQL injection via ORDER BY
    $validSorts = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $validSorts) ? $sort : 'date ASC';

    $stmt = $pdo->prepare("SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with FROM Schedules s JOIN Games g ON s.game_id = g.game_id WHERE s.user_id = :user_id AND s.deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

function editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Check permission
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId))
        return "No permission to edit.";

    // Validate
    if ($err = validateRequired($gameTitle, "Game title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if ($err = validateCommaSeparated($friendsStr, "Friends"))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $gameId = getOrCreateGameId($pdo, $gameTitle);

    $stmt = $pdo->prepare("UPDATE Schedules SET game_id = :game_id, date = :date, time = :time, friends = :friends, shared_with = :shared_with WHERE schedule_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['game_id' => $gameId, 'date' => $date, 'time' => $time, 'friends' => $friendsStr, 'shared_with' => $sharedWithStr, 'id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

function deleteSchedule($userId, $scheduleId)
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId))
        return "No permission to delete.";

    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// --- 6. Events (Evenementen) ---

function addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Validate
    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if (!empty($description) && strlen($description) > 500)
        return "Description too long (max 500 characters).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Invalid reminder option.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $stmt = $pdo->prepare("INSERT INTO Events (user_id, title, date, time, description, reminder, external_link, shared_with) VALUES (:user_id, :title, :date, :time, :description, :reminder, :external_link, :shared_with)");
    $stmt->execute([
        'user_id' => $userId,
        'title' => $title,
        'date' => $date,
        'time' => $time,
        'description' => $description,
        'reminder' => $reminder,
        'external_link' => $externalLink,
        'shared_with' => $sharedWithStr
    ]);
    return null;
}

function getEvents($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();
    $validSorts = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $validSorts) ? $sort : 'date ASC';

    $stmt = $pdo->prepare("SELECT event_id, title, date, time, description, reminder, external_link, shared_with FROM Events WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

function editEvent($userId, $eventId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId))
        return "No permission to edit.";

    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if (!empty($description) && strlen($description) > 500)
        return "Description too long (max 500 characters).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Invalid reminder option.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $stmt = $pdo->prepare("UPDATE Events SET title = :title, date = :date, time = :time, description = :description, reminder = :reminder, external_link = :external_link, shared_with = :shared_with WHERE event_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'time' => $time,
        'description' => $description,
        'reminder' => $reminder,
        'external_link' => $externalLink,
        'shared_with' => $sharedWithStr,
        'id' => $eventId,
        'user_id' => $userId
    ]);
    return null;
}

function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId))
        return "No permission to delete.";

    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}

// --- 7. General & Helper ---

function getGames()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel");
    return $stmt->fetchAll();
}

function checkOwnership($pdo, $table, $idColumn, $id, $userId)
{
    // Dynamic query helper to check if a user owns a row
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}

function getCalendarItems($userId)
{
    // Merge Schedules + Events into a single chronological list
    $schedules = getSchedules($userId);
    $events = getEvents($userId);

    $items = array_merge($schedules, $events);

    // Sort combined list
    usort($items, function ($a, $b) {
        $dateA = strtotime($a['date'] . ' ' . $a['time']);
        $dateB = strtotime($b['date'] . ' ' . $b['time']);
        return $dateA <=> $dateB;
    });
    return $items;
}

function getReminders($userId)
{
    // Logic to find events near "now" (simulated check for next 24h or hour)
    // In this MVP, we just return upcoming events with reminders enabled for JS to parse.
    $events = getEvents($userId);
    $reminders = [];
    foreach ($events as $event) {
        if ($event['reminder'] != 'none') {
            // Simplified logic: If event is in future, pass to JS to handle specific timing
            $reminders[] = $event;
        }
    }
    return $reminders;
}
?>