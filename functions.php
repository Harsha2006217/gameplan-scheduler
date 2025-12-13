<?php
// ============================================================================
// FUNCTIONS.PHP - Core Functions and Database Queries
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0 (with Bug Fixes #1001 and #1004)
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This is the BRAIN of the application! It contains all the important
// functions that handle database operations, user authentication, and
// data validation.
//
// SECTIONS IN THIS FILE:
// 1. Session & Security Setup
// 2. Helper Functions (validation, messages, escaping)
// 3. User Authentication (login, register, logout)
// 4. Profile Management (favorite games)
// 5. Friends Management (add, edit, delete friends)
// 6. Schedules Management (gaming sessions)
// 7. Events Management (tournaments, events)
// 8. Calendar Functions (merged view)
//
// BUG FIXES IMPLEMENTED:
// - #1001: Validation for empty fields/spaces (trim() check)
// - #1004: Improved date validation with format checking
//
// SECURITY FEATURES:
// - Input validation on all user data
// - PDO prepared statements (SQL injection protection)
// - Output escaping (XSS protection)
// - Session timeout (30 minutes)
// - Password hashing (bcrypt)
// ============================================================================


// ============================================================================
// STEP 1: START OUTPUT BUFFERING
// ============================================================================
// Output buffering prevents "headers already sent" errors.
// When we use header() to redirect, PHP needs to send headers BEFORE any output.
// ob_start() stores all output in a buffer until we're ready to send it.

ob_start();


// ============================================================================
// STEP 2: INCLUDE DATABASE CONNECTION
// ============================================================================
// require_once loads the db.php file exactly once.
// If the file was already loaded, it won't load again (prevents errors).
// This gives us access to the getDBConnection() function.

require_once 'db.php';


// ============================================================================
// STEP 3: START SESSION IF NOT ALREADY STARTED
// ============================================================================
// Sessions allow us to store user data (like user_id) across page requests.
// Without sessions, the website wouldn't know who is logged in.

// Check if a session is already running
// PHP_SESSION_NONE means no session has started yet
if (session_status() === PHP_SESSION_NONE) {
    // Start a new session
    // This creates a unique session ID stored in a cookie
    session_start();

    // SECURITY: Regenerate session ID to prevent session fixation attacks
    // Session fixation is when an attacker sets a known session ID
    // Regenerating creates a new random ID, making the old one useless
    session_regenerate_id(true);
}


// ============================================================================
// SECTION: HELPER FUNCTIONS
// ============================================================================
// These small functions are used throughout the application.
// They handle common tasks like escaping output and showing messages.


/**
 * safeEcho - Secure Output Escaping
 * 
 * WHAT IT DOES:
 * Converts special HTML characters to safe versions that won't execute.
 * This prevents XSS (Cross-Site Scripting) attacks.
 * 
 * XSS ATTACK EXAMPLE:
 * If a user enters: <script>alert('hacked')</script>
 * Without escaping: The script would RUN in the browser!
 * With escaping: It displays as harmless text: &lt;script&gt;...
 * 
 * HOW IT WORKS:
 * < becomes &lt;
 * > becomes &gt;
 * " becomes &quot;
 * ' becomes &#039;
 * & becomes &amp;
 * 
 * @param string $string The text to make safe
 * @return string Safe text that can be displayed in HTML
 * 
 * USAGE EXAMPLE:
 * echo safeEcho($user['username']); // Safe to display
 */
function safeEcho($string)
{
    // htmlspecialchars() converts special characters to HTML entities
    // ENT_QUOTES: Convert both single (') and double (") quotes
    // 'UTF-8': Use UTF-8 encoding for international characters
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}


/**
 * validateRequired - Check Required Fields (BUG FIX #1001)
 * 
 * WHAT IT DOES:
 * Validates that a field has a value and is not just empty spaces.
 * This fixes Bug #1001 where fields with only spaces were accepted.
 * 
 * BUG #1001 EXPLANATION:
 * Before: A user could enter "     " (only spaces) as a game title
 *         This would save to the database as empty/blank
 * After:  We use trim() to remove spaces, then check if anything remains
 *         "     " becomes "" (empty) = REJECTED with error message
 * 
 * @param string $value The value to validate
 * @param string $fieldName Name of the field (for error messages)
 * @param int $maxLength Maximum allowed length (0 = no limit)
 * @return string|null Error message if invalid, null if valid
 * 
 * USAGE EXAMPLE:
 * if ($error = validateRequired($title, "Game Title", 100)) {
 *     return $error; // Stop and show error
 * }
 */
function validateRequired($value, $fieldName, $maxLength = 0)
{
    // TRIM: Remove whitespace from beginning and end
    // "  Hello World  " becomes "Hello World"
    // "     " becomes "" (empty string)
    $value = trim($value);

    // CHECK IF EMPTY OR ONLY SPACES
    // empty() returns true if: "", 0, null, false, "0"
    // preg_match checks for strings that are ONLY whitespace
    // ^\s*$ means: start (^), any whitespace (\s*), end ($)
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        // Return error message with field name
        return "$fieldName may not be empty or contain only spaces.";
    }

    // CHECK MAXIMUM LENGTH
    // Only check if maxLength was specified (> 0)
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName exceeds maximum length of $maxLength characters.";
    }

    // No errors - return null to indicate success
    return null;
}


/**
 * validateDate - Check Date Format and Value (BUG FIX #1004)
 * 
 * WHAT IT DOES:
 * Validates that a date is in correct format AND is in the future.
 * This fixes Bug #1004 where invalid dates like "2025-13-45" were accepted.
 * 
 * BUG #1004 EXPLANATION:
 * Before: Invalid dates caused database errors
 *         "2025-13-45" (month 13, day 45) was not caught
 * After:  We validate format YYYY-MM-DD with checkdate()
 *         Only real dates are accepted
 * 
 * THE VALIDATION PROCESS:
 * 1. Check if date string matches YYYY-MM-DD pattern
 * 2. Extract year, month, day from the string
 * 3. Use PHP's checkdate() to verify it's a real date
 * 4. Compare with current date to ensure it's in the future
 * 
 * @param string $date The date to validate (format: YYYY-MM-DD)
 * @return string|null Error message if invalid, null if valid
 * 
 * USAGE EXAMPLE:
 * if ($error = validateDate($eventDate)) {
 *     return $error; // Stop and show error
 * }
 */
function validateDate($date)
{
    // STEP 1: Check basic format with regex
    // ^ = start of string
    // \d{4} = exactly 4 digits (year)
    // - = literal dash
    // \d{2} = exactly 2 digits (month and day)
    // $ = end of string
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return "Invalid date format. Use YYYY-MM-DD.";
    }

    // STEP 2: Split the date into parts
    // explode splits string by delimiter (-)
    // list assigns values to three variables
    // "2025-09-30" becomes $year=2025, $month=09, $day=30
    list($year, $month, $day) = explode('-', $date);

    // STEP 3: Verify it's a real date using checkdate()
    // checkdate(month, day, year) returns true if valid
    // Examples:
    // checkdate(2, 29, 2024) = true (leap year)
    // checkdate(2, 29, 2025) = false (not leap year)
    // checkdate(13, 1, 2025) = false (no month 13)
    // checkdate(4, 31, 2025) = false (April has 30 days)
    if (!checkdate((int) $month, (int) $day, (int) $year)) {
        return "Invalid date. Please enter a real calendar date.";
    }

    // STEP 4: Check if date is in the future
    // strtotime converts date string to Unix timestamp (seconds since 1970)
    // time() returns current Unix timestamp
    // strtotime('today') gives today at 00:00:00 (allow scheduling for today)
    if (strtotime($date) < strtotime('today')) {
        return "Date must be today or in the future.";
    }

    // All checks passed - date is valid
    return null;
}


/**
 * validateTime - Check Time Format
 * 
 * WHAT IT DOES:
 * Validates that time is in correct HH:MM format.
 * 
 * VALID EXAMPLES: 09:30, 14:00, 23:59, 00:00
 * INVALID EXAMPLES: 25:00, 12:60, 1:30, abc
 * 
 * THE REGEX EXPLAINED:
 * ^         = Start of string
 * ([01]?    = Optionally 0 or 1 (for 00-19)
 * [0-9]     = Followed by any digit (01-09, 10-19)
 * |         = OR
 * 2[0-3])   = 20-23
 * :         = Literal colon
 * [0-5][0-9] = 00-59 (minutes)
 * $         = End of string
 * 
 * @param string $time The time to validate (format: HH:MM)
 * @return string|null Error message if invalid, null if valid
 */
function validateTime($time)
{
    // Check if time matches valid 24-hour format
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
        return "Invalid time format. Use HH:MM (24-hour format).";
    }
    return null;
}


/**
 * validateEmail - Check Email Address Format
 * 
 * WHAT IT DOES:
 * Uses PHP's built-in email validation to check if email is valid.
 * 
 * VALID EXAMPLES: user@example.com, name.surname@domain.co.uk
 * INVALID EXAMPLES: user@, @domain.com, user name@domain.com
 * 
 * @param string $email The email address to validate
 * @return string|null Error message if invalid, null if valid
 */
function validateEmail($email)
{
    // FILTER_VALIDATE_EMAIL is PHP's built-in email validator
    // It checks for proper email format: local@domain.tld
    // Returns false if email is invalid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format. Please enter a valid email address.";
    }
    return null;
}


/**
 * validateUrl - Check URL Format
 * 
 * WHAT IT DOES:
 * Validates that a URL has proper format (http:// or https://).
 * Only validates if URL is provided (empty URLs are allowed).
 * 
 * @param string $url The URL to validate
 * @return string|null Error message if invalid, null if valid
 */
function validateUrl($url)
{
    // Only validate if URL is not empty (optional field)
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid URL format. Include http:// or https://";
    }
    return null;
}


/**
 * validateCommaSeparated - Check Comma-Separated List
 * 
 * WHAT IT DOES:
 * Validates that a comma-separated list doesn't have empty items.
 * Used for friend lists and shared-with lists.
 * 
 * VALID: "user1, user2, user3"
 * INVALID: "user1,, user2" (empty item between commas)
 * 
 * @param string $value The comma-separated string
 * @param string $fieldName Name of the field (for error messages)
 * @return string|null Error message if invalid, null if valid
 */
function validateCommaSeparated($value, $fieldName)
{
    // Empty value is valid (optional field)
    if (empty($value))
        return null;

    // Split by comma
    $items = explode(',', $value);

    // Check each item
    foreach ($items as $item) {
        // Trim spaces and check if empty
        $item = trim($item);
        if (empty($item)) {
            return "$fieldName contains empty items. Remove extra commas.";
        }
    }
    return null;
}


/**
 * setMessage - Store Flash Message in Session
 * 
 * WHAT IT DOES:
 * Stores a message in the session to be displayed on the next page.
 * "Flash messages" appear once and then disappear.
 * 
 * MESSAGE TYPES:
 * - 'success' = Green message (operation succeeded)
 * - 'danger'  = Red message (error occurred)
 * - 'warning' = Yellow message (caution)
 * - 'info'    = Blue message (information)
 * 
 * @param string $type The Bootstrap alert type
 * @param string $msg The message to display
 * 
 * USAGE:
 * setMessage('success', 'Profile updated!');
 * header('Location: profile.php');
 */
function setMessage($type, $msg)
{
    // Store in session - will persist until we read it
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg];
}


/**
 * getMessage - Retrieve and Display Flash Message
 * 
 * WHAT IT DOES:
 * Gets the stored message, creates HTML to display it, then deletes it.
 * The message only displays once (that's why it's called "flash").
 * 
 * @return string HTML for the alert, or empty string if no message
 * 
 * USAGE:
 * echo getMessage(); // Shows message if exists, then removes it
 */
function getMessage()
{
    // Check if a message exists in the session
    if (isset($_SESSION['message'])) {
        // Get the message
        $msg = $_SESSION['message'];

        // Delete it from session (so it only shows once)
        unset($_SESSION['message']);

        // Return Bootstrap alert HTML
        // alert-{type} gives the appropriate color
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show' role='alert'>
                    {$msg['msg']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
    // No message - return empty string
    return '';
}


// ============================================================================
// SECTION: SESSION MANAGEMENT FUNCTIONS
// ============================================================================

/**
 * isLoggedIn - Check if User is Logged In
 * 
 * WHAT IT DOES:
 * Checks if there's a user_id stored in the session.
 * If yes, user is logged in. If no, user is a guest.
 * 
 * @return bool True if logged in, false if not
 */
function isLoggedIn()
{
    // isset() checks if variable exists and is not null
    // If user_id exists in session, user is logged in
    return isset($_SESSION['user_id']);
}


/**
 * getUserId - Get Current User's ID
 * 
 * WHAT IT DOES:
 * Returns the logged-in user's ID from the session.
 * Returns 0 if no user is logged in.
 * 
 * @return int User ID or 0
 */
function getUserId()
{
    // If logged in, return user_id cast to integer
    // If not logged in, return 0
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}


/**
 * updateLastActivity - Track User Activity
 * 
 * WHAT IT DOES:
 * Updates the last_activity timestamp in the database.
 * Used to track when a user was last active.
 * 
 * @param PDO $pdo Database connection
 * @param int $userId The user's ID
 */
function updateLastActivity($pdo, $userId)
{
    // Update the last_activity field to current time
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
}


/**
 * checkSessionTimeout - Logout Inactive Users
 * 
 * WHAT IT DOES:
 * Checks if user has been inactive for more than 30 minutes.
 * If so, destroys their session and redirects to login page.
 * This is a SECURITY feature to protect user accounts.
 * 
 * WHY 30 MINUTES?
 * - Short enough to protect accounts on shared computers
 * - Long enough that users won't be logged out mid-task
 * - Common standard for web applications
 * 
 * HOW IT WORKS:
 * 1. Check if user is logged in
 * 2. Check if last_activity timestamp exists
 * 3. Calculate time since last activity
 * 4. If > 1800 seconds (30 min), logout
 * 5. Update last_activity to current time
 */
function checkSessionTimeout()
{
    // Only check for logged-in users who have a last_activity timestamp
    if (isLoggedIn() && isset($_SESSION['last_activity'])) {
        // Calculate seconds since last activity
        $inactiveTime = time() - $_SESSION['last_activity'];

        // 1800 seconds = 30 minutes
        if ($inactiveTime > 1800) {
            // Session expired! Destroy and redirect
            session_destroy();
            header("Location: login.php?msg=session_timeout");
            exit;
        }
    }

    // Update last activity to current time
    $_SESSION['last_activity'] = time();
}


// ============================================================================
// SECTION: USER AUTHENTICATION FUNCTIONS
// ============================================================================

/**
 * registerUser - Create New User Account
 * 
 * WHAT IT DOES:
 * Creates a new user account with validated and secure data.
 * 
 * REGISTRATION PROCESS:
 * 1. Validate all input fields
 * 2. Check if email already exists
 * 3. Hash the password with bcrypt
 * 4. Insert new user into database
 * 
 * SECURITY FEATURES:
 * - Input validation with validateRequired()
 * - Email format validation
 * - Password minimum length check
 * - Password hashing with bcrypt (PASSWORD_BCRYPT)
 * - Prepared statements prevent SQL injection
 * 
 * @param string $username User's display name
 * @param string $email User's email address
 * @param string $password User's password (plain text)
 * @return string|null Error message if failed, null if success
 */
function registerUser($username, $email, $password)
{
    // Get database connection
    $pdo = getDBConnection();

    // ========================================================================
    // STEP 1: VALIDATE ALL INPUTS
    // ========================================================================

    // Validate username (required, max 50 chars)
    if ($err = validateRequired($username, "Username", 50))
        return $err;

    // Validate email format
    if ($err = validateEmail($email))
        return $err;

    // Validate password (required)
    if ($err = validateRequired($password, "Password"))
        return $err;

    // Password minimum length (security requirement)
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters for security.";
    }

    // ========================================================================
    // STEP 2: CHECK IF EMAIL ALREADY EXISTS
    // ========================================================================
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);

    if ($stmt->fetchColumn() > 0) {
        return "This email is already registered. Please login or use a different email.";
    }

    // ========================================================================
    // STEP 3: HASH THE PASSWORD
    // ========================================================================
    // NEVER store plain text passwords!
    // password_hash() creates a secure one-way hash
    // PASSWORD_BCRYPT uses blowfish algorithm (very secure)
    // The hash includes a random salt (no two hashes are the same)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // ========================================================================
    // STEP 4: INSERT NEW USER INTO DATABASE
    // ========================================================================
    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");

    try {
        $stmt->execute([
            'username' => trim($username),
            'email' => trim($email),
            'hash' => $hash
        ]);

        // Success! Return null (no error)
        return null;

    } catch (PDOException $e) {
        // Log the actual error for developers
        error_log("Registration failed: " . $e->getMessage());

        // Return user-friendly message (don't expose details)
        return "Registration failed. Please try again later.";
    }
}


/**
 * loginUser - Authenticate User
 * 
 * WHAT IT DOES:
 * Verifies user credentials and starts a login session.
 * 
 * LOGIN PROCESS:
 * 1. Validate input fields
 * 2. Find user by email in database
 * 3. Verify password against stored hash
 * 4. Create session with user data
 * 
 * SECURITY FEATURES:
 * - Password verified with password_verify() (timing-safe comparison)
 * - Session ID regenerated (prevents session fixation)
 * - Last activity updated (for timeout tracking)
 * 
 * @param string $email User's email address
 * @param string $password User's password (plain text)
 * @return string|null Error message if failed, null if success
 */
function loginUser($email, $password)
{
    // Get database connection
    $pdo = getDBConnection();

    // ========================================================================
    // STEP 1: VALIDATE INPUTS
    // ========================================================================
    if ($err = validateRequired($email, "Email"))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;

    // ========================================================================
    // STEP 2: FIND USER BY EMAIL
    // ========================================================================
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => trim($email)]);
    $user = $stmt->fetch();

    // ========================================================================
    // STEP 3: VERIFY PASSWORD
    // ========================================================================
    // Check if user exists AND password matches
    // password_verify() compares plain password against stored hash
    // It uses timing-safe comparison (prevents timing attacks)
    if (!$user || !password_verify($password, $user['password_hash'])) {
        // Generic error message (don't reveal if email exists or not)
        return "Invalid email or password. Please try again.";
    }

    // ========================================================================
    // STEP 4: CREATE LOGIN SESSION
    // ========================================================================
    // Store user data in session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['last_activity'] = time();

    // Regenerate session ID for security
    session_regenerate_id(true);

    // Update last activity in database
    updateLastActivity($pdo, $user['user_id']);

    // Success! Return null (no error)
    return null;
}


/**
 * logout - End User Session
 * 
 * WHAT IT DOES:
 * Destroys the user session and redirects to login page.
 */
function logout()
{
    // Destroy all session data
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit;
}


// ============================================================================
// SECTION: PROFILE MANAGEMENT (FAVORITE GAMES)
// ============================================================================

/**
 * getOrCreateGameId - Get Existing Game or Create New One
 * 
 * WHAT IT DOES:
 * Looks for a game by title. If found, returns its ID.
 * If not found, creates a new game and returns the new ID.
 * 
 * @param PDO $pdo Database connection
 * @param string $title Game title
 * @param string $description Game description
 * @return int Game ID
 */
function getOrCreateGameId($pdo, $title, $description = '')
{
    // Trim whitespace
    $title = trim($title);

    // Empty title = invalid
    if (empty($title))
        return 0;

    // Try to find existing game (case-insensitive)
    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL");
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();

    // If found, return existing ID
    if ($row)
        return $row['game_id'];

    // Not found - create new game
    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);

    // Return the new game's ID
    return $pdo->lastInsertId();
}


/**
 * addFavoriteGame - Add Game to User's Favorites
 * 
 * WHAT IT DOES:
 * Adds a game to the user's favorites list with an optional note.
 * 
 * @param int $userId User's ID
 * @param string $title Game title
 * @param string $description Game description
 * @param string $note Personal note about the game
 * @return string|null Error message if failed, null if success
 */
function addFavoriteGame($userId, $title, $description = '', $note = '')
{
    $pdo = getDBConnection();

    // Validate game title (Bug Fix #1001)
    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Get or create game ID
    $gameId = getOrCreateGameId($pdo, $title, $description);

    // Check if already in favorites
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);

    if ($stmt->fetchColumn() > 0) {
        return "This game is already in your favorites.";
    }

    // Add to favorites
    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);

    return null;
}


/**
 * updateFavoriteGame - Update Game Details
 * 
 * WHAT IT DOES:
 * Updates the title, description, and note for a favorite game.
 * 
 * @param int $userId User's ID
 * @param int $gameId Game's ID
 * @param string $title New title
 * @param string $description New description
 * @param string $note New note
 * @return string|null Error message if failed, null if success
 */
function updateFavoriteGame($userId, $gameId, $title, $description, $note)
{
    $pdo = getDBConnection();

    // Validate game title (Bug Fix #1001)
    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Check ownership - user must own this favorite
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);

    if ($stmt->fetchColumn() == 0) {
        return "You don't have permission to edit this game.";
    }

    // Update game details
    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => trim($title), 'description' => $description, 'game_id' => $gameId]);

    // Update user's note
    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);

    return null;
}


/**
 * deleteFavoriteGame - Remove Game from Favorites
 * 
 * @param int $userId User's ID
 * @param int $gameId Game's ID
 * @return string|null Error message if failed, null if success
 */
function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();

    // Delete from UserGames (not soft delete - just remove the link)
    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);

    return null;
}


/**
 * getFavoriteGames - Get User's Favorite Games
 * 
 * @param int $userId User's ID
 * @return array Array of favorite games
 */
function getFavoriteGames($userId)
{
    $pdo = getDBConnection();

    // Get games joined with UserGames for notes
    $stmt = $pdo->prepare("
        SELECT g.game_id, g.titel, g.description, ug.note 
        FROM UserGames ug 
        JOIN Games g ON ug.game_id = g.game_id 
        WHERE ug.user_id = :user_id AND g.deleted_at IS NULL
        ORDER BY g.titel ASC
    ");
    $stmt->execute(['user_id' => $userId]);

    return $stmt->fetchAll();
}


// ============================================================================
// SECTION: FRIENDS MANAGEMENT
// ============================================================================

/**
 * addFriend - Add a Friend by Username
 * 
 * @param int $userId User's ID
 * @param string $friendUsername Friend's username
 * @param string $note Note about the friend
 * @param string $status Friend's status
 * @return string|null Error message if failed, null if success
 */
function addFriend($userId, $friendUsername, $note = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    // Validate username (Bug Fix #1001)
    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Check if already friends (case-insensitive)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => trim($friendUsername)]);

    if ($stmt->fetchColumn() > 0) {
        return "You're already friends with this user.";
    }

    // Add friend
    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_username, note, status) VALUES (:user_id, :friend_username, :note, :status)");
    $stmt->execute([
        'user_id' => $userId,
        'friend_username' => trim($friendUsername),
        'note' => $note,
        'status' => trim($status)
    ]);

    return null;
}


/**
 * updateFriend - Update Friend Details
 * 
 * @param int $userId User's ID
 * @param int $friendId Friend record ID
 * @param string $friendUsername Updated username
 * @param string $note Updated note
 * @param string $status Updated status
 * @return string|null Error message if failed, null if success
 */
function updateFriend($userId, $friendId, $friendUsername, $note, $status)
{
    $pdo = getDBConnection();

    // Validate inputs (Bug Fix #1001)
    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Check ownership
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);

    if ($stmt->fetchColumn() == 0) {
        return "Friend not found or you don't have permission to edit.";
    }

    // Update friend
    $stmt = $pdo->prepare("UPDATE Friends SET friend_username = :friend_username, note = :note, status = :status WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute([
        'friend_username' => trim($friendUsername),
        'note' => $note,
        'status' => trim($status),
        'user_id' => $userId,
        'friend_id' => $friendId
    ]);

    return null;
}


/**
 * deleteFriend - Remove Friend (Soft Delete)
 * 
 * @param int $userId User's ID
 * @param int $friendId Friend record ID
 * @return string|null Error message if failed, null if success
 */
function deleteFriend($userId, $friendId)
{
    $pdo = getDBConnection();

    // Soft delete - set deleted_at timestamp
    $stmt = $pdo->prepare("UPDATE Friends SET deleted_at = NOW() WHERE user_id = :user_id AND friend_id = :friend_id");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);

    return null;
}


/**
 * getFriends - Get User's Friends List
 * 
 * @param int $userId User's ID
 * @return array Array of friends
 */
function getFriends($userId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT friend_id, friend_username as username, status, note FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY friend_username ASC");
    $stmt->execute(['user_id' => $userId]);

    return $stmt->fetchAll();
}


// ============================================================================
// SECTION: SCHEDULES MANAGEMENT
// ============================================================================

/**
 * addSchedule - Create New Gaming Schedule
 * 
 * @param int $userId User's ID
 * @param string $gameTitle Game being played
 * @param string $date Date of session (YYYY-MM-DD)
 * @param string $time Start time (HH:MM)
 * @param string $friendsStr Comma-separated friends
 * @param string $sharedWithStr Comma-separated users to share with
 * @return string|null Error message if failed, null if success
 */
function addSchedule($userId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Validate all inputs (Bug Fix #1001 and #1004)
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

    // Get or create game
    $gameId = getOrCreateGameId($pdo, $gameTitle);

    // Insert schedule
    $stmt = $pdo->prepare("INSERT INTO Schedules (user_id, game_id, date, time, friends, shared_with) VALUES (:user_id, :game_id, :date, :time, :friends, :shared_with)");
    $stmt->execute([
        'user_id' => $userId,
        'game_id' => $gameId,
        'date' => $date,
        'time' => $time,
        'friends' => $friendsStr,
        'shared_with' => $sharedWithStr
    ]);

    return null;
}


/**
 * getSchedules - Get User's Schedules
 * 
 * @param int $userId User's ID
 * @param string $sort Sort order (date ASC, date DESC, etc.)
 * @return array Array of schedules
 */
function getSchedules($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();

    // Whitelist sort options to prevent SQL injection
    $allowedSorts = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'date ASC';
    }

    $stmt = $pdo->prepare("
        SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with 
        FROM Schedules s 
        JOIN Games g ON s.game_id = g.game_id 
        WHERE s.user_id = :user_id AND s.deleted_at IS NULL 
        ORDER BY $sort 
        LIMIT 50
    ");
    $stmt->execute(['user_id' => $userId]);

    return $stmt->fetchAll();
}


/**
 * editSchedule - Update Existing Schedule
 * 
 * @param int $userId User's ID
 * @param int $scheduleId Schedule ID
 * @param string $gameTitle Game title
 * @param string $date Date
 * @param string $time Time
 * @param string $friendsStr Friends
 * @param string $sharedWithStr Shared with
 * @return string|null Error message if failed, null if success
 */
function editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Check ownership
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId)) {
        return "You don't have permission to edit this schedule.";
    }

    // Validate all inputs (Bug Fix #1001 and #1004)
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

    // Get or create game
    $gameId = getOrCreateGameId($pdo, $gameTitle);

    // Update schedule
    $stmt = $pdo->prepare("UPDATE Schedules SET game_id = :game_id, date = :date, time = :time, friends = :friends, shared_with = :shared_with WHERE schedule_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute([
        'game_id' => $gameId,
        'date' => $date,
        'time' => $time,
        'friends' => $friendsStr,
        'shared_with' => $sharedWithStr,
        'id' => $scheduleId,
        'user_id' => $userId
    ]);

    return null;
}


/**
 * deleteSchedule - Remove Schedule (Soft Delete)
 * 
 * @param int $userId User's ID
 * @param int $scheduleId Schedule ID
 * @return string|null Error message if failed, null if success
 */
function deleteSchedule($userId, $scheduleId)
{
    $pdo = getDBConnection();

    // Check ownership
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId)) {
        return "You don't have permission to delete this schedule.";
    }

    // Soft delete
    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);

    return null;
}


// ============================================================================
// SECTION: EVENTS MANAGEMENT
// ============================================================================

/**
 * addEvent - Create New Event
 * 
 * @param int $userId User's ID
 * @param string $title Event title
 * @param string $date Event date
 * @param string $time Event time
 * @param string $description Event description
 * @param string $reminder Reminder setting
 * @param string $externalLink External URL
 * @param string $sharedWithStr Users to share with
 * @return string|null Error message if failed, null if success
 */
function addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Validate all inputs (Bug Fix #1001 and #1004)
    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;

    // Description is optional, but check length if provided
    if (!empty($description) && strlen($description) > 500) {
        return "Description too long (maximum 500 characters).";
    }

    // Validate reminder option
    if (!in_array($reminder, ['none', '1_hour', '1_day'])) {
        return "Invalid reminder option selected.";
    }

    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    // Insert event
    $stmt = $pdo->prepare("
        INSERT INTO Events (user_id, title, date, time, description, reminder, external_link, shared_with) 
        VALUES (:user_id, :title, :date, :time, :description, :reminder, :external_link, :shared_with)
    ");
    $stmt->execute([
        'user_id' => $userId,
        'title' => trim($title),
        'date' => $date,
        'time' => $time,
        'description' => $description,
        'reminder' => $reminder,
        'external_link' => $externalLink,
        'shared_with' => $sharedWithStr
    ]);

    return null;
}


/**
 * getEvents - Get User's Events
 * 
 * @param int $userId User's ID
 * @param string $sort Sort order
 * @return array Array of events
 */
function getEvents($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();

    // Whitelist sort options
    $allowedSorts = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'date ASC';
    }

    $stmt = $pdo->prepare("
        SELECT event_id, title, date, time, description, reminder, external_link, shared_with 
        FROM Events 
        WHERE user_id = :user_id AND deleted_at IS NULL 
        ORDER BY $sort 
        LIMIT 50
    ");
    $stmt->execute(['user_id' => $userId]);

    return $stmt->fetchAll();
}


/**
 * editEvent - Update Existing Event
 * 
 * @param int $userId User's ID
 * @param int $eventId Event ID
 * @param string $title Event title
 * @param string $date Event date
 * @param string $time Event time
 * @param string $description Event description
 * @param string $reminder Reminder setting
 * @param string $externalLink External URL
 * @param string $sharedWithStr Users to share with
 * @return string|null Error message if failed, null if success
 */
function editEvent($userId, $eventId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Check ownership
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "You don't have permission to edit this event.";
    }

    // Validate all inputs (Bug Fix #1001 and #1004)
    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;

    if (!empty($description) && strlen($description) > 500) {
        return "Description too long (maximum 500 characters).";
    }

    if (!in_array($reminder, ['none', '1_hour', '1_day'])) {
        return "Invalid reminder option selected.";
    }

    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    // Update event
    $stmt = $pdo->prepare("
        UPDATE Events 
        SET title = :title, date = :date, time = :time, description = :description, 
            reminder = :reminder, external_link = :external_link, shared_with = :shared_with 
        WHERE event_id = :id AND user_id = :user_id AND deleted_at IS NULL
    ");
    $stmt->execute([
        'title' => trim($title),
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


/**
 * deleteEvent - Remove Event (Soft Delete)
 * 
 * @param int $userId User's ID
 * @param int $eventId Event ID
 * @return string|null Error message if failed, null if success
 */
function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();

    // Check ownership
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "You don't have permission to delete this event.";
    }

    // Soft delete
    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);

    return null;
}


// ============================================================================
// SECTION: HELPER FUNCTIONS FOR DATA MANAGEMENT
// ============================================================================

/**
 * getGames - Get All Games
 * 
 * @return array Array of all games
 */
function getGames()
{
    $pdo = getDBConnection();

    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel");

    return $stmt->fetchAll();
}


/**
 * checkOwnership - Verify User Owns a Record
 * 
 * WHAT IT DOES:
 * Checks if a specific record belongs to the current user.
 * Used before editing or deleting to prevent unauthorized access.
 * 
 * @param PDO $pdo Database connection
 * @param string $table Table name
 * @param string $idColumn ID column name
 * @param int $id Record ID
 * @param int $userId User's ID
 * @return bool True if user owns the record, false otherwise
 */
function checkOwnership($pdo, $table, $idColumn, $id, $userId)
{
    // Prepared statement to check if record exists and belongs to user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);

    // Return true if count > 0 (record exists and belongs to user)
    return $stmt->fetchColumn() > 0;
}


// ============================================================================
// SECTION: CALENDAR FUNCTIONS
// ============================================================================

/**
 * getCalendarItems - Get Merged Calendar View
 * 
 * WHAT IT DOES:
 * Combines schedules and events into a single sorted list.
 * Used for the calendar overview on the dashboard.
 * 
 * @param int $userId User's ID
 * @return array Merged and sorted array of schedules and events
 */
function getCalendarItems($userId)
{
    // Get schedules and events
    $schedules = getSchedules($userId);
    $events = getEvents($userId);

    // Merge into single array
    $items = array_merge($schedules, $events);

    // Sort by date and time
    usort($items, function ($a, $b) {
        // Create timestamps for comparison
        $dateA = strtotime($a['date'] . ' ' . $a['time']);
        $dateB = strtotime($b['date'] . ' ' . $b['time']);

        // Return comparison result (-1, 0, or 1)
        return $dateA <=> $dateB;
    });

    return $items;
}


/**
 * getReminders - Get Events That Need Reminders
 * 
 * WHAT IT DOES:
 * Finds events that should show a reminder pop-up.
 * Used by JavaScript to display reminder notifications.
 * 
 * @param int $userId User's ID
 * @return array Events that need reminder notification
 */
function getReminders($userId)
{
    // Get all events
    $events = getEvents($userId);

    $reminders = [];

    foreach ($events as $event) {
        // Skip events with no reminder set
        if ($event['reminder'] == 'none')
            continue;

        // Calculate event time
        $eventTime = strtotime($event['date'] . ' ' . $event['time']);

        // Calculate reminder time based on setting
        // 1 hour = 3600 seconds, 1 day = 86400 seconds
        $offset = ($event['reminder'] == '1_hour') ? 3600 : 86400;
        $reminderTime = $eventTime - $offset;

        // Check if reminder time is now (within last minute)
        $now = time();
        if ($reminderTime <= $now && $reminderTime > ($now - 60)) {
            $reminders[] = $event;
        }
    }

    return $reminders;
}


// ============================================================================
// FILE COMPLETE
// ============================================================================
// This file contains all the core functions for GamePlan Scheduler.
//
// SUMMARY OF BUG FIXES:
// ✓ #1001: validateRequired() now checks for empty strings and whitespace-only
// ✓ #1004: validateDate() now validates format AND checks with checkdate()
//
// SECURITY FEATURES IMPLEMENTED:
// ✓ Input validation on all user input
// ✓ Output escaping with safeEcho()
// ✓ Prepared statements for all database queries
// ✓ Password hashing with bcrypt
// ✓ Session timeout after 30 minutes
// ✓ Ownership checks before editing/deleting
//
// © 2025 GamePlan Scheduler by Harsha Kanaparthi
// ============================================================================
?>