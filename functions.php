<?php
/**
 * ============================================================================
 * functions.php - KERN FUNCTIES BESTAND / CORE FUNCTIONS FILE
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Dit is het "brein" van de applicatie. Alle belangrijke functies staan hier:
 * - Gebruiker authenticatie (inloggen, registreren, uitloggen)
 * - Database queries (data ophalen, opslaan, bewerken, verwijderen)
 * - Validatie (controleren of invoer correct is)
 * - Sessie beheer (onthouden wie ingelogd is)
 * 
 * BEVEILIGING GEÏMPLEMENTEERD:
 * - PDO prepared statements tegen SQL injectie
 * - Password hashing met bcrypt
 * - XSS bescherming met htmlspecialchars
 * - Sessie timeout na 30 minuten
 * ============================================================================
 */

// Start output buffering - voorkomt "headers already sent" fouten bij redirects
ob_start();

// Laad de database connectie uit db.php
require_once 'db.php';

// Start sessie als deze nog niet actief is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Regenereer sessie ID voor extra beveiliging tegen session hijacking
    session_regenerate_id(true);
}

// ============================================================================
// SECTIE 1: HELPER FUNCTIES - Kleine hulpfuncties die overal worden gebruikt
// ============================================================================

/**
 * safeEcho() - Veilig tekst weergeven tegen XSS aanvallen
 * 
 * XSS (Cross-Site Scripting) is een aanval waarbij hackers JavaScript
 * in je website proberen te injecteren. Deze functie voorkomt dat.
 * 
 * VOORBEELD:
 * Invoer: "<script>alert('hacked!')</script>"
 * Uitvoer: "&lt;script&gt;alert('hacked!')&lt;/script&gt;"
 * 
 * @param string $string - De tekst die veilig weergegeven moet worden
 * @return string - De veilig geëscapede tekst
 */
function safeEcho($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * validateRequired() - Controleert of een veld correct is ingevuld
 * 
 * Deze functie lost BUG #1001 op: voorkomt dat velden met alleen spaties
 * worden opgeslagen als lege waarden in de database.
 * 
 * @param string $value - De waarde om te controleren
 * @param string $fieldName - Naam van het veld (voor foutmelding)
 * @param int $maxLength - Maximale lengte (0 = geen limiet)
 * @return string|null - Foutmelding of null als alles OK is
 */
function validateRequired($value, $fieldName, $maxLength = 0)
{
    // trim() verwijdert spaties aan begin en eind
    $value = trim($value);

    // Controleer of veld leeg is OF alleen spaties bevat
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        return "$fieldName may not be empty or contain only spaces.";
    }

    // Controleer maximale lengte als opgegeven
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName exceeds maximum length of $maxLength characters.";
    }

    return null; // Geen fout = null teruggeven
}

/**
 * validateDate() - Controleert of datum geldig is en in de toekomst ligt
 * 
 * Dit lost BUG #1004 op: ongeldige datums zoals "2025-13-45" worden afgewezen
 * 
 * @param string $date - Datum in YYYY-MM-DD formaat
 * @return string|null - Foutmelding of null als OK
 */
function validateDate($date)
{
    // strtotime() zet tekst om naar timestamp, false = ongeldige datum
    if (strtotime($date) === false) {
        return "Invalid date format.";
    }
    // Datum moet in de toekomst liggen
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        return "Date must be in the future.";
    }
    return null;
}

/**
 * validateTime() - Controleert of tijd in HH:MM formaat is
 * 
 * @param string $time - Tijd om te controleren
 * @return string|null - Foutmelding of null als OK
 */
function validateTime($time)
{
    // Regex patroon: 00:00 tot 23:59
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
        return "Invalid time format (HH:MM).";
    }
    return null;
}

/**
 * validateEmail() - Controleert of email adres geldig is
 */
function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return null;
}

/**
 * validateUrl() - Controleert of URL geldig is (alleen als ingevuld)
 */
function validateUrl($url)
{
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid URL format.";
    }
    return null;
}

/**
 * validateCommaSeparated() - Controleert komma-gescheiden lijst
 */
function validateCommaSeparated($value, $fieldName)
{
    if (empty($value))
        return null;
    $items = explode(',', $value);
    foreach ($items as $item) {
        if (empty(trim($item)))
            return "$fieldName contains empty items.";
    }
    return null;
}

// ============================================================================
// SECTIE 2: SESSIE FUNCTIES - Beheer wie is ingelogd
// ============================================================================

/**
 * setMessage() - Sla een melding op in de sessie voor de volgende pagina
 * 
 * GEBRUIK: Na een actie (bijv. "Event toegevoegd!") redirect je naar een
 * andere pagina. Deze functie onthoudt de melding tot die pagina laadt.
 * 
 * @param string $type - 'success' (groen), 'danger' (rood), 'warning' (geel)
 * @param string $msg - De tekst van de melding
 */
function setMessage($type, $msg)
{
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg];
}

/**
 * getMessage() - Haal melding op en wis deze uit sessie
 * 
 * @return string - HTML voor Bootstrap alert, of lege string
 */
function getMessage()
{
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']); // Wis na ophalen
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show'>{$msg['msg']}<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    return '';
}

/**
 * isLoggedIn() - Controleer of gebruiker is ingelogd
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * getUserId() - Haal ID van ingelogde gebruiker op
 */
function getUserId()
{
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}

/**
 * checkSessionTimeout() - Controleer of sessie verlopen is (30 minuten)
 * 
 * BEVEILIGING: Als iemand weggaat en vergeet uit te loggen, wordt de
 * sessie automatisch beëindigd na 30 minuten inactiviteit.
 */
function checkSessionTimeout()
{
    if (isLoggedIn() && isset($_SESSION['last_activity'])) {
        // 1800 seconden = 30 minuten
        if (time() - $_SESSION['last_activity'] > 1800) {
            session_destroy();
            header("Location: login.php?msg=session_timeout");
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
}

/**
 * updateLastActivity() - Update laatste activiteit timestamp
 */
function updateLastActivity($pdo, $userId)
{
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
}

/**
 * logout() - Log gebruiker uit
 */
function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}

// ============================================================================
// SECTIE 3: AUTHENTICATIE - Registratie en Login
// ============================================================================

/**
 * registerUser() - Registreer een nieuwe gebruiker
 * 
 * PROCES:
 * 1. Valideer alle invoer
 * 2. Controleer of email al bestaat
 * 3. Hash het wachtwoord met bcrypt
 * 4. Sla gebruiker op in database
 * 
 * @return string|null - Foutmelding of null bij succes
 */
function registerUser($username, $email, $password)
{
    $pdo = getDBConnection();

    // Validatie
    if ($err = validateRequired($username, "Username", 50))
        return $err;
    if ($err = validateEmail($email))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;
    if (strlen($password) < 8)
        return "Password must be at least 8 characters.";

    // Controleer of email al bestaat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0)
        return "Email already registered.";

    // Hash wachtwoord met bcrypt (veiligste methode)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Voeg gebruiker toe aan database
    try {
        $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null; // Succes
    } catch (PDOException $e) {
        error_log("Registration failed: " . $e->getMessage());
        return "Registration failed. Please try again.";
    }
}

/**
 * loginUser() - Log een gebruiker in
 * 
 * PROCES:
 * 1. Zoek gebruiker op email
 * 2. Verifieer wachtwoord met password_verify()
 * 3. Start sessie met user_id
 */
function loginUser($email, $password)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($email, "Email"))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;

    // Haal gebruiker op
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Verifieer wachtwoord
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return "Invalid email or password.";
    }

    // Start sessie
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    session_regenerate_id(true);
    updateLastActivity($pdo, $user['user_id']);
    return null;
}

// ============================================================================
// SECTIE 4: GAMES BEHEER - Favoriete games
// ============================================================================

/**
 * getOrCreateGameId() - Zoek of maak een game aan
 */
function getOrCreateGameId($pdo, $title, $description = '')
{
    $title = trim($title);
    if (empty($title))
        return 0;

    // Controleer of game bestaat
    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL");
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();
    if ($row)
        return $row['game_id'];

    // Maak nieuwe game aan
    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);
    return $pdo->lastInsertId();
}

/**
 * addFavoriteGame() - Voeg favoriete game toe aan profiel
 */
function addFavoriteGame($userId, $title, $description = '', $note = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    $gameId = getOrCreateGameId($pdo, $title, $description);

    // Controleer of al toegevoegd
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0)
        return "Game already in favorites.";

    // Voeg toe
    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);
    return null;
}

/**
 * updateFavoriteGame() - Bewerk favoriete game
 */
function updateFavoriteGame($userId, $gameId, $title, $description, $note)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Check eigendom
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0)
        return "No permission to edit.";

    // Update Games tabel
    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => $title, 'description' => $description, 'game_id' => $gameId]);

    // Update note in UserGames
    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * deleteFavoriteGame() - Verwijder favoriete game
 */
function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * getFavoriteGames() - Haal alle favoriete games op
 */
function getFavoriteGames($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT g.game_id, g.titel, g.description, ug.note FROM UserGames ug JOIN Games g ON ug.game_id = g.game_id WHERE ug.user_id = :user_id AND g.deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// ============================================================================
// SECTIE 5: VRIENDEN BEHEER
// ============================================================================

/**
 * addFriend() - Voeg een vriend toe
 */
function addFriend($userId, $friendUsername, $note = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Check of al vrienden
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername]);
    if ($stmt->fetchColumn() > 0)
        return "Already friends.";

    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_username, note, status) VALUES (:user_id, :friend_username, :note, :status)");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername, 'note' => $note, 'status' => $status]);
    return null;
}

/**
 * updateFriend() - Bewerk vriend gegevens
 */
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
        return "Not friends or no permission.";

    $stmt = $pdo->prepare("UPDATE Friends SET friend_username = :friend_username, note = :note, status = :status WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['friend_username' => $friendUsername, 'note' => $note, 'status' => $status, 'user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

/**
 * deleteFriend() - Soft delete een vriend
 */
function deleteFriend($userId, $friendId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE Friends SET deleted_at = NOW() WHERE user_id = :user_id AND friend_id = :friend_id");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

/**
 * getFriends() - Haal alle vrienden op
 */
function getFriends($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT friend_id, friend_username as username, status, note FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// ============================================================================
// SECTIE 6: SCHEDULES BEHEER - Speelschema's
// ============================================================================

/**
 * addSchedule() - Voeg een speelschema toe
 */
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

/**
 * getSchedules() - Haal alle speelschema's op met sortering
 */
function getSchedules($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();
    $sort = in_array($sort, ['date ASC', 'date DESC', 'time ASC', 'time DESC']) ? $sort : 'date ASC';
    $stmt = $pdo->prepare("SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with FROM Schedules s JOIN Games g ON s.game_id = g.game_id WHERE s.user_id = :user_id AND s.deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * editSchedule() - Bewerk een speelschema
 */
function editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId))
        return "No permission to edit.";

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

/**
 * deleteSchedule() - Soft delete een speelschema
 */
function deleteSchedule($userId, $scheduleId)
{
    $pdo = getDBConnection();
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId))
        return "No permission to delete.";
    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// ============================================================================
// SECTIE 7: EVENTS BEHEER - Evenementen
// ============================================================================

/**
 * addEvent() - Voeg een evenement toe
 */
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
        return "Description too long (max 500 characters).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Invalid reminder option.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    $stmt = $pdo->prepare("INSERT INTO Events (user_id, title, date, time, description, reminder, external_link, shared_with) VALUES (:user_id, :title, :date, :time, :description, :reminder, :external_link, :shared_with)");
    $stmt->execute(['user_id' => $userId, 'title' => $title, 'date' => $date, 'time' => $time, 'description' => $description, 'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr]);
    return null;
}

/**
 * getEvents() - Haal alle evenementen op met sortering
 */
function getEvents($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();
    $sort = in_array($sort, ['date ASC', 'date DESC', 'time ASC', 'time DESC']) ? $sort : 'date ASC';
    $stmt = $pdo->prepare("SELECT event_id, title, date, time, description, reminder, external_link, shared_with FROM Events WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * editEvent() - Bewerk een evenement
 */
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
    $stmt->execute(['title' => $title, 'date' => $date, 'time' => $time, 'description' => $description, 'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr, 'id' => $eventId, 'user_id' => $userId]);
    return null;
}

/**
 * deleteEvent() - Soft delete een evenement
 */
function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId))
        return "No permission to delete.";
    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}

// ============================================================================
// SECTIE 8: HULP FUNCTIES
// ============================================================================

/**
 * getGames() - Haal alle beschikbare games op
 */
function getGames()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel");
    return $stmt->fetchAll();
}

/**
 * checkOwnership() - Controleer of gebruiker eigenaar is van een item
 * 
 * BEVEILIGING: Voorkomt dat gebruikers elkaars data kunnen bewerken/verwijderen
 */
function checkOwnership($pdo, $table, $idColumn, $id, $userId)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}

/**
 * getCalendarItems() - Combineer schedules en events voor kalender
 */
function getCalendarItems($userId)
{
    $schedules = getSchedules($userId);
    $events = getEvents($userId);
    $items = array_merge($schedules, $events);

    // Sorteer op datum en tijd
    usort($items, function ($a, $b) {
        $dateA = strtotime($a['date'] . ' ' . $a['time']);
        $dateB = strtotime($b['date'] . ' ' . $b['time']);
        return $dateA <=> $dateB;
    });

    return $items;
}

/**
 * getReminders() - Haal actieve herinneringen op voor JavaScript pop-ups
 */
function getReminders($userId)
{
    $events = getEvents($userId);
    $reminders = [];

    foreach ($events as $event) {
        if ($event['reminder'] != 'none') {
            $eventTime = strtotime($event['date'] . ' ' . $event['time']);
            $reminderOffset = ($event['reminder'] == '1_hour') ? 3600 : 86400;
            $reminderTime = $eventTime - $reminderOffset;

            // Toon herinnering als het binnen de afgelopen minuut valt
            if ($reminderTime <= time() && $reminderTime > time() - 60) {
                $reminders[] = $event;
            }
        }
    }

    return $reminders;
}