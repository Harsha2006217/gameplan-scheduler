<?php
/**
 * ============================================================================
 * FUNCTIONS.PHP - CORE FUNCTIONS / KERNFUNCTIES
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH: Contains all database queries, validation, and helper functions.
 * DUTCH: Bevat alle database queries, validatie, en helperfuncties.
 * 
 * BUG FIXES IMPLEMENTED:
 * - #1001: Spaces-only validation with trim() and regex
 * - #1004: Strict date format validation with DateTime
 * ============================================================================
 */

// Start output buffering to prevent "headers already sent" errors
// Start output buffering om "headers already sent" fouten te voorkomen
ob_start();

// Include database connection / Include database verbinding
require_once 'db.php';

// ============================================================================
// SESSION MANAGEMENT / SESSIE BEHEER
// ============================================================================

/**
 * Start session if not already started
 * Start sessie als nog niet gestart
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Regenerate session ID for security against session hijacking
    // Regenereer sessie ID voor beveiliging tegen sessie hijacking
    session_regenerate_id(true);
}

// ============================================================================
// HELPER FUNCTIONS / HELPERFUNCTIES
// ============================================================================

/**
 * safeEcho - Secure output escaping against XSS attacks
 * safeEcho - Veilige output escaping tegen XSS aanvallen
 * 
 * @param string $string Text to escape / Tekst om te escapen
 * @return string Safe HTML escaped text / Veilige HTML escaped tekst
 */
function safeEcho($string)
{
    // htmlspecialchars converts special characters to HTML entities
    // Example: <script> becomes &lt;script&gt; (safe to display)
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * validateRequired - Validate required fields (BUG FIX #1001)
 * validateRequired - Valideer verplichte velden (BUG FIX #1001)
 * 
 * FIXES: Prevents saving fields with only spaces
 * 
 * @param string $value Value to validate / Waarde om te valideren
 * @param string $fieldName Name for error message / Naam voor foutmelding
 * @param int $maxLength Maximum allowed length / Maximum toegestane lengte
 * @return string|null Error message or null if valid / Foutmelding of null als geldig
 */
function validateRequired($value, $fieldName, $maxLength = 0)
{
    // Trim removes whitespace from beginning and end
    // Trim verwijdert witruimte van begin en einde
    $value = trim($value);

    // BUG FIX #1001: Check for empty OR spaces-only using regex
    // BUG FIX #1001: Controleer op leeg OF alleen spaties met regex
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        return "$fieldName may not be empty or contain only spaces. / $fieldName mag niet leeg zijn of alleen spaties bevatten.";
    }

    // Check maximum length if specified
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName exceeds maximum length of $maxLength characters. / $fieldName overschrijdt maximale lengte van $maxLength tekens.";
    }

    return null; // Valid / Geldig
}

/**
 * validateDate - Validate date format and future date (BUG FIX #1004)
 * validateDate - Valideer datum formaat en toekomstige datum (BUG FIX #1004)
 * 
 * FIXES: Prevents invalid dates like 2025-13-45
 * 
 * @param string $date Date string to validate / Datum string om te valideren
 * @return string|null Error message or null if valid
 */
function validateDate($date)
{
    // BUG FIX #1004: Use DateTime for STRICT date validation
    // BUG FIX #1004: Gebruik DateTime voor STRIKTE datum validatie
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);

    // Check if date was parsed correctly AND matches input exactly
    // Controleer of datum correct geparsed is EN exact overeenkomt met input
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        return "Invalid date format. Use YYYY-MM-DD. / Ongeldig datum formaat. Gebruik JJJJ-MM-DD.";
    }

    // Check if date is in the future
    // Controleer of datum in de toekomst is
    $today = new DateTime('today');
    if ($dateObj < $today) {
        return "Date must be today or in the future. / Datum moet vandaag of in de toekomst zijn.";
    }

    return null;
}

/**
 * validateTime - Validate time format HH:MM
 * validateTime - Valideer tijd formaat UU:MM
 */
function validateTime($time)
{
    // Regex: 00-23 hours, 00-59 minutes
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
        return "Invalid time format (HH:MM). / Ongeldig tijd formaat (UU:MM).";
    }
    return null;
}

/**
 * validateEmail - Validate email format
 * validateEmail - Valideer e-mail formaat
 */
function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format. / Ongeldig e-mail formaat.";
    }
    return null;
}

/**
 * validateUrl - Validate URL format (optional field)
 * validateUrl - Valideer URL formaat (optioneel veld)
 */
function validateUrl($url)
{
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid URL format. / Ongeldig URL formaat.";
    }
    return null;
}

/**
 * validateCommaSeparated - Validate comma-separated values
 * validateCommaSeparated - Valideer komma-gescheiden waarden
 */
function validateCommaSeparated($value, $fieldName)
{
    if (empty($value))
        return null;
    $items = explode(',', $value);
    foreach ($items as $item) {
        if (empty(trim($item))) {
            return "$fieldName contains empty items. / $fieldName bevat lege items.";
        }
    }
    return null;
}

// ============================================================================
// SESSION MESSAGE FUNCTIONS / SESSIE BERICHT FUNCTIES
// ============================================================================

/**
 * setMessage - Store message in session for display
 * setMessage - Bewaar bericht in sessie voor weergave
 */
function setMessage($type, $msg)
{
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg];
}

/**
 * getMessage - Get and clear session message
 * getMessage - Haal sessie bericht op en wis het
 */
function getMessage()
{
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']);
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show' role='alert'>
                    {$msg['msg']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    }
    return '';
}

// ============================================================================
// AUTHENTICATION FUNCTIONS / AUTHENTICATIE FUNCTIES
// ============================================================================

/**
 * isLoggedIn - Check if user is logged in
 * isLoggedIn - Controleer of gebruiker ingelogd is
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * getUserId - Get current user's ID
 * getUserId - Haal huidige gebruiker's ID op
 */
function getUserId()
{
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}

/**
 * updateLastActivity - Update user's last activity timestamp
 * updateLastActivity - Update gebruiker's laatste activiteit timestamp
 */
function updateLastActivity($pdo, $userId)
{
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
}

/**
 * checkSessionTimeout - Check if session has expired (30 minutes)
 * checkSessionTimeout - Controleer of sessie verlopen is (30 minuten)
 */
function checkSessionTimeout()
{
    // 1800 seconds = 30 minutes
    if (isLoggedIn() && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        header("Location: login.php?msg=session_timeout");
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/**
 * registerUser - Register a new user account
 * registerUser - Registreer een nieuw gebruikersaccount
 */
function registerUser($username, $email, $password)
{
    $pdo = getDBConnection();

    // Validate all inputs
    if ($err = validateRequired($username, "Username", 50))
        return $err;
    if ($err = validateEmail($email))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;
    if (strlen($password) < 8)
        return "Password must be at least 8 characters. / Wachtwoord moet minimaal 8 tekens zijn.";

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0)
        return "Email already registered. / E-mail al geregistreerd.";

    // Hash password with bcrypt (secure!)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");
    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null;
    } catch (PDOException $e) {
        error_log("Registration failed: " . $e->getMessage());
        return "Registration failed. Please try again. / Registratie mislukt. Probeer opnieuw.";
    }
}

/**
 * loginUser - Authenticate and login user
 * loginUser - Authenticeer en login gebruiker
 */
function loginUser($email, $password)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($email, "Email"))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Verify password with bcrypt
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return "Invalid email or password. / Ongeldige e-mail of wachtwoord.";
    }

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    session_regenerate_id(true);
    updateLastActivity($pdo, $user['user_id']);
    return null;
}

/**
 * logout - Destroy session and redirect
 * logout - Vernietig sessie en redirect
 */
function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}

// ============================================================================
// GAME FUNCTIONS / SPEL FUNCTIES
// ============================================================================

/**
 * getOrCreateGameId - Get existing game or create new one
 * getOrCreateGameId - Haal bestaand spel op of maak nieuw
 */
function getOrCreateGameId($pdo, $title, $description = '')
{
    $title = trim($title);
    if (empty($title))
        return 0;

    // Check if game exists (case-insensitive)
    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL");
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();
    if ($row)
        return $row['game_id'];

    // Create new game
    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);
    return $pdo->lastInsertId();
}

/**
 * addFavoriteGame - Add game to user's favorites
 */
function addFavoriteGame($userId, $title, $description = '', $note = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100))
        return $err;
    $gameId = getOrCreateGameId($pdo, $title, $description);

    // Check if already favorited
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0)
        return "Game already in favorites. / Spel al in favorieten.";

    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);
    return null;
}

/**
 * updateFavoriteGame - Update favorite game details
 */
function updateFavoriteGame($userId, $gameId, $title, $description, $note)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Verify ownership
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0)
        return "No permission to edit. / Geen toestemming om te bewerken.";

    // Update game info
    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => $title, 'description' => $description, 'game_id' => $gameId]);

    // Update note
    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * deleteFavoriteGame - Remove game from favorites
 */
function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * getFavoriteGames - Get user's favorite games
 */
function getFavoriteGames($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT g.game_id, g.titel, g.description, ug.note FROM UserGames ug JOIN Games g ON ug.game_id = g.game_id WHERE ug.user_id = :user_id AND g.deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * getGames - Get all games
 */
function getGames()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel");
    return $stmt->fetchAll();
}

// ============================================================================
// FRIENDS FUNCTIONS / VRIENDEN FUNCTIES
// ============================================================================

function addFriend($userId, $friendUsername, $note = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Check if already friends
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername]);
    if ($stmt->fetchColumn() > 0)
        return "Already friends. / Al vrienden.";

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

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    if ($stmt->fetchColumn() == 0)
        return "Not friends or no permission. / Geen vrienden of geen toestemming.";

    $stmt = $pdo->prepare("UPDATE Friends SET friend_username = :friend_username, note = :note, status = :status WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['friend_username' => $friendUsername, 'note' => $note, 'status' => $status, 'user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

function deleteFriend($userId, $friendId)
{
    $pdo = getDBConnection();
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

// ============================================================================
// SCHEDULE FUNCTIONS / SCHEMA FUNCTIES
// ============================================================================

function addSchedule($userId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

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
    $stmt = $pdo->prepare("INSERT INTO Schedules (user_id, game_id, date, time, friends, shared_with) VALUES (:user_id, :game_id, :date, :time, :friends, :shared_with)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'date' => $date, 'time' => $time, 'friends' => $friendsStr, 'shared_with' => $sharedWithStr]);
    return null;
}

function getSchedules($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();
    $sort = in_array($sort, ['date ASC', 'date DESC', 'time ASC', 'time DESC']) ? $sort : 'date ASC';
    $stmt = $pdo->prepare("SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with FROM Schedules s JOIN Games g ON s.game_id = g.game_id WHERE s.user_id = :user_id AND s.deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

function editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId))
        return "No permission. / Geen toestemming.";
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
        return "No permission. / Geen toestemming.";
    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// ============================================================================
// EVENT FUNCTIONS / EVENEMENT FUNCTIES
// ============================================================================

function addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if (!empty($description) && strlen($description) > 500)
        return "Description too long (max 500). / Beschrijving te lang (max 500).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Invalid reminder. / Ongeldige herinnering.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $stmt = $pdo->prepare("INSERT INTO Events (user_id, title, date, time, description, reminder, external_link, shared_with) VALUES (:user_id, :title, :date, :time, :description, :reminder, :external_link, :shared_with)");
    $stmt->execute(['user_id' => $userId, 'title' => $title, 'date' => $date, 'time' => $time, 'description' => $description, 'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr]);
    return null;
}

function getEvents($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();
    $sort = in_array($sort, ['date ASC', 'date DESC', 'time ASC', 'time DESC']) ? $sort : 'date ASC';
    $stmt = $pdo->prepare("SELECT event_id, title, date, time, description, reminder, external_link, shared_with FROM Events WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

function editEvent($userId, $eventId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId))
        return "No permission. / Geen toestemming.";
    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if (!empty($description) && strlen($description) > 500)
        return "Description too long (max 500). / Beschrijving te lang (max 500).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Invalid reminder. / Ongeldige herinnering.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $stmt = $pdo->prepare("UPDATE Events SET title = :title, date = :date, time = :time, description = :description, reminder = :reminder, external_link = :external_link, shared_with = :shared_with WHERE event_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['title' => $title, 'date' => $date, 'time' => $time, 'description' => $description, 'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr, 'id' => $eventId, 'user_id' => $userId]);
    return null;
}

function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId))
        return "No permission. / Geen toestemming.";
    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}

// ============================================================================
// UTILITY FUNCTIONS / HULP FUNCTIES
// ============================================================================

function checkOwnership($pdo, $table, $idColumn, $id, $userId)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}

function getCalendarItems($userId)
{
    $schedules = getSchedules($userId);
    $events = getEvents($userId);
    $items = array_merge($schedules, $events);
    usort($items, function ($a, $b) {
        return strtotime($a['date'] . ' ' . $a['time']) <=> strtotime($b['date'] . ' ' . $b['time']);
    });
    return $items;
}

function getReminders($userId)
{
    $events = getEvents($userId);
    $reminders = [];
    foreach ($events as $event) {
        if ($event['reminder'] != 'none') {
            $eventTime = strtotime($event['date'] . ' ' . $event['time']);
            $reminderTime = $eventTime - ($event['reminder'] == '1_hour' ? 3600 : 86400);
            if ($reminderTime <= time() && $reminderTime > time() - 60) {
                $reminders[] = $event;
            }
        }
    }
    return $reminders;
}