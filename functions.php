<?php
// This file is functions.php - All helper functions for the app.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Has functions for validation, database operations, sessions, etc. Organized by sections.
// Improvements: Added more validations (e.g., no self-friend), improved efficiency, extensive comments.
// Simple: Functions are like recipes - reusable code blocks. Call them when needed.

require_once 'db.php'; // Include database connection.

if (session_status() === PHP_SESSION_NONE) { // If no session, start one.
    session_start(); // Start session for login data.
    session_regenerate_id(true); // Change ID for security.
}

// --- Helper Functions ---
// safeEcho: Protects output from bad code (XSS attack).
function safeEcho($string) { // Function takes string, returns safe version.
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); // htmlspecialchars converts special chars to harmless.
}

// validateRequired: Checks if value is not empty and within length.
function validateRequired($value, $fieldName, $maxLength = 0) {
    $value = trim($value); // Trim removes spaces from start/end.
    if (empty($value) || preg_match('/^\s*$/', $value)) { // If empty or only spaces.
        return "$fieldName may not be empty or contain only spaces.";
    }
    if ($maxLength > 0 && strlen($value) > $maxLength) { // If too long.
        return "$fieldName exceeds maximum length of $maxLength characters.";
    }
    return null; // No error.
}

// validateDate: Check if date is valid and future.
function validateDate($date) {
    if (strtotime($date) === false) { // strtotime turns string to time number.
        return "Invalid date format.";
    }
    if (strtotime($date) < time()) { // time() is now.
        return "Date must be in the future.";
    }
    return null;
}

// validateTime: Check HH:MM format.
function validateTime($time) {
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) { // preg_match checks pattern.
        return "Invalid time format (HH:MM).";
    }
    return null;
}

// validateEmail: Check email format.
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Built-in filter.
        return "Invalid email format.";
    }
    return null;
}

// validateUrl: Check optional URL.
function validateUrl($url) {
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid URL format.";
    }
    return null;
}

// validateCommaSeparated: Check comma list, no empty items.
function validateCommaSeparated($value, $fieldName) {
    if (empty($value)) return null;
    $items = explode(',', $value); // Split by comma.
    foreach ($items as $item) { // Loop each.
        $item = trim($item);
        if (empty($item)) return "$fieldName contains empty items.";
    }
    return null;
}

// setMessage: Save message in session for next page.
function setMessage($type, $msg) {
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg]; // $_SESSION is global storage.
}

// getMessage: Get and clear message.
function getMessage() {
    if (isset($_SESSION['message'])) { // If set.
        $msg = $_SESSION['message'];
        unset($_SESSION['message']); // Clear.
        return "<div class='alert alert-{$msg['type']}'>{$msg['msg']}</div>"; // Return HTML box.
    }
    return '';
}

// isLoggedIn: Check if user logged.
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// getUserId: Get ID or 0.
function getUserId() {
    return isLoggedIn() ? (int)$_SESSION['user_id'] : 0;
}

// updateLastActivity: Update timestamp in DB.
function updateLastActivity($pdo, $userId) {
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL"); // Prepare query.
    $stmt->execute(['user_id' => $userId]); // Run.
}

// checkSessionTimeout: Logout if inactive 30 min.
function checkSessionTimeout() {
    if (isLoggedIn() && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) { // 1800 seconds = 30 min.
        session_destroy(); // End session.
        header("Location: login.php?msg=session_timeout");
        exit;
    }
    $_SESSION['last_activity'] = time(); // Update time.
}

// --- User Authentication ---
// registerUser: Create new user.
function registerUser($username, $email, $password) {
    $pdo = getDBConnection();

    if ($err = validateRequired($username, "Username", 50)) return $err;
    if ($err = validateEmail($email)) return $err;
    if ($err = validateRequired($password, "Password")) return $err;
    if (strlen($password) < 8) return "Password must be at least 8 characters.";

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) return "Email already registered.";

    $hash = password_hash($password, PASSWORD_BCRYPT); // Hash for security.

    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");
    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null;
    } catch (PDOException $e) {
        error_log("Registration failed: " . $e->getMessage());
        return "Registration failed. Please try again.";
    }
}

// loginUser: Log in.
function loginUser($email, $password) {
    $pdo = getDBConnection();

    if ($err = validateRequired($email, "Email")) return $err;
    if ($err = validateRequired($password, "Password")) return $err;

    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(); // Get row.

    if (!$user || !password_verify($password, $user['password_hash'])) { // Verify hash.
        return "Invalid email or password.";
    }

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    session_regenerate_id(true);
    updateLastActivity($pdo, $user['user_id']);
    return null;
}

// logout: End session.
function logout() {
    session_destroy();
    header("Location: login.php");
    exit;
}

// --- Profile Management ---
// getOrCreateGameId: Find or add game by title.
function getOrCreateGameId($pdo, $title, $description = '') {
    $title = trim($title);
    if (empty($title)) return 0;

    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL"); // Case insensitive.
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();
    if ($row) return $row['game_id'];

    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);
    return $pdo->lastInsertId(); // New ID.
}

// addFavoriteGame: Add to favorites.
function addFavoriteGame($userId, $title, $description = '', $note = '') {
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100)) return $err;

    $gameId = getOrCreateGameId($pdo, $title, $description);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0) return "Game already in favorites.";

    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);
    return null;
}

// updateFavoriteGame: Update.
function updateFavoriteGame($userId, $gameId, $title, $description, $note) {
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100)) return $err;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0) return "No permission to edit.";

    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => $title, 'description' => $description, 'game_id' => $gameId]);

    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

// deleteFavoriteGame: Remove.
function deleteFavoriteGame($userId, $gameId) {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

// getFavoriteGames: Get list.
function getFavoriteGames($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT g.game_id, g.titel, g.description, ug.note FROM UserGames ug JOIN Games g ON ug.game_id = g.game_id WHERE ug.user_id = :user_id AND g.deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll(); // All rows.
}

// --- Friends Management ---
// addFriend: Add.
function addFriend($userId, $friendUsername, $note = '', $status = 'Offline') {
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50)) return $err;
    if ($err = validateRequired($status, "Status", 50)) return $err;

    $stmt = $pdo->prepare("SELECT username FROM Users WHERE user_id = :user_id"); // Get own username.
    $stmt->execute(['user_id' => $userId]);
    $ownUsername = $stmt->fetchColumn();
    if (strtolower($friendUsername) == strtolower($ownUsername)) return "Cannot add yourself as friend."; // No self-add.

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername]);
    if ($stmt->fetchColumn() > 0) return "Already friends.";

    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_username, note, status) VALUES (:user_id, :friend_username, :note, :status)");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername, 'note' => $note, 'status' => $status]);
    return null;
}

// updateFriend: Update.
function updateFriend($userId, $friendId, $friendUsername, $note, $status) {
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50)) return $err;
    if ($err = validateRequired($status, "Status", 50)) return $err;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    if ($stmt->fetchColumn() == 0) return "Not friends or no permission.";

    $stmt = $pdo->prepare("UPDATE Friends SET friend_username = :friend_username, note = :note, status = :status WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['friend_username' => $friendUsername, 'note' => $note, 'status' => $status, 'user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

// deleteFriend: Soft delete.
function deleteFriend($userId, $friendId) {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("UPDATE Friends SET deleted_at = NOW() WHERE user_id = :user_id AND friend_id = :friend_id"); // NOW() is current time.
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

// getFriends: Get list.
function getFriends($userId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT friend_id, friend_username as username, status, note FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// --- Schedules Management ---
// addSchedule: Add.
function addSchedule($userId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '') {
    $pdo = getDBConnection();

    if ($err = validateRequired($gameTitle, "Game title", 100)) return $err;
    if ($err = validateDate($date)) return $err;
    if ($err = validateTime($time)) return $err;
    if ($err = validateCommaSeparated($friendsStr, "Friends")) return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With")) return $err;

    $gameId = getOrCreateGameId($pdo, $gameTitle);

    $stmt = $pdo->prepare("INSERT INTO Schedules (user_id, game_id, date, time, friends, shared_with) VALUES (:user_id, :game_id, :date, :time, :friends, :shared_with)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'date' => $date, 'time' => $time, 'friends' => $friendsStr, 'shared_with' => $sharedWithStr]);
    return null;
}

// getSchedules: Get list with sort.
function getSchedules($userId, $sort = 'date ASC') {
    $pdo = getDBConnection();
    $sort = in_array($sort, ['date ASC', 'date DESC', 'time ASC', 'time DESC']) ? $sort : 'date ASC'; // Safe sort.
    $stmt = $pdo->prepare("SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with FROM Schedules s JOIN Games g ON s.game_id = g.game_id WHERE s.user_id = :user_id AND s.deleted_at IS NULL ORDER BY $sort LIMIT 50"); // Limit to 50 for performance.
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// editSchedule: Update.
function editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '') {
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId)) return "No permission to edit.";

    if ($err = validateRequired($gameTitle, "Game title", 100)) return $err;
    if ($err = validateDate($date)) return $err;
    if ($err = validateTime($time)) return $err;
    if ($err = validateCommaSeparated($friendsStr, "Friends")) return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With")) return $err;

    $gameId = getOrCreateGameId($pdo, $gameTitle);

    $stmt = $pdo->prepare("UPDATE Schedules SET game_id = :game_id, date = :date, time = :time, friends = :friends, shared_with = :shared_with WHERE schedule_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['game_id' => $gameId, 'date' => $date, 'time' => $time, 'friends' => $friendsStr, 'shared_with' => $sharedWithStr, 'id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// deleteSchedule: Soft delete.
function deleteSchedule($userId, $scheduleId) {
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId)) return "No permission to delete.";

    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// --- Events Management ---
// addEvent: Add.
function addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '') {
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Title", 100)) return $err;
    if ($err = validateDate($date)) return $err;
    if ($err = validateTime($time)) return $err;
    if (!empty($description) && strlen($description) > 500) return "Description too long (max 500 characters).";
    if (!in_array($reminder, ['none', '1_hour', '1_day'])) return "Invalid reminder option.";
    if ($err = validateUrl($externalLink)) return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With")) return $err;

    $stmt = $pdo->prepare("INSERT INTO Events (user_id, title, date, time, description, reminder, external_link, shared_with) VALUES (:user_id, :title, :date, :time, :description, :reminder, :external_link, :shared_with)");
    $stmt->execute([
        'user_id' => $userId, 'title' => $title, 'date' => $date, 'time' => $time,
        'description' => $description, 'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr
    ]);
    return null;
}

// getEvents: Get list.
function getEvents($userId, $sort = 'date ASC') {
    $pdo = getDBConnection();
    $sort = in_array($sort, ['date ASC', 'date DESC', 'time ASC', 'time DESC']) ? $sort : 'date ASC';
    $stmt = $pdo->prepare("SELECT event_id, title, date, time, description, reminder, external_link, shared_with FROM Events WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// editEvent: Update.
function editEvent($userId, $eventId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '') {
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) return "No permission to edit.";

    if ($err = validateRequired($title, "Title", 100)) return $err;
    if ($err = validateDate($date)) return $err;
    if ($err = validateTime($time)) return $err;
    if (!empty($description) && strlen($description) > 500) return "Description too long (max 500 characters).";
    if (!in_array($reminder, ['none', '1_hour', '1_day'])) return "Invalid reminder option.";
    if ($err = validateUrl($externalLink)) return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With")) return $err;

    $stmt = $pdo->prepare("UPDATE Events SET title = :title, date = :date, time = :time, description = :description, reminder = :reminder, external_link = :external_link, shared_with = :shared_with WHERE event_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute([
        'title' => $title, 'date' => $date, 'time' => $time, 'description' => $description,
        'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr, 'id' => $eventId, 'user_id' => $userId
    ]);
    return null;
}

// deleteEvent: Soft delete.
function deleteEvent($userId, $eventId) {
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) return "No permission to delete.";

    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}

// --- Games Management ---
// getGames: Get all games.
function getGames() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel"); // query is simple select.
    return $stmt->fetchAll();
}

// --- Ownership Check Helper ---
// checkOwnership: Check if user owns item.
function checkOwnership($pdo, $table, $idColumn, $id, $userId) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0; // True if count > 0.
}

// --- Calendar Merge ---
// getCalendarItems: Merge schedules and events, sort by time.
function getCalendarItems($userId) {
    $schedules = getSchedules($userId);
    $events = getEvents($userId);
    $items = array_merge($schedules, $events); // Combine lists.
    usort($items, function($a, $b) { // Sort by full datetime.
        $dateA = strtotime($a['date'] . ' ' . $a['time']);
        $dateB = strtotime($b['date'] . ' ' . $b['time']);
        return $dateA <=> $dateB; // <=> compares.
    });
    return $items;
}

// --- Reminder Handling ---
// getReminders: Get events with reminders due now (for JS).
function getReminders($userId) {
    $events = getEvents($userId);
    $reminders = [];
    foreach ($events as $event) {
        if ($event['reminder'] != 'none') {
            $eventTime = strtotime($event['date'] . ' ' . $event['time']);
            $reminderTime = $eventTime - ($event['reminder'] == '1_hour' ? 3600 : 86400); // Subtract seconds.
            if ($reminderTime <= time() && $reminderTime > time() - 60) { // Due within last minute for demo.
                $reminders[] = $event;
            }
        }
    }
    return $reminders;
}
?>