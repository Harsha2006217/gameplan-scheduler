<?php
/**
 * ============================================================================
 * FUNCTIONS.PHP - KERNFUNCTIES
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bevat alle database queries, validatie, authenticatie en helperfuncties.
 *
 * BUGFIXES:
 * - #1001: Alleen-spaties validatie met trim() en regex
 * - #1004: Strenge datumvalidatie met DateTime::createFromFormat()
 * - #1006: session_regenerate_id() verplaatst naar alleen loginUser()
 * ============================================================================
 */

// Start output buffering om "headers already sent" fouten te voorkomen
ob_start();

// Laad de database verbinding
require_once 'db.php';

// ============================================================================
// SESSIEBEHEER
// ============================================================================

// Start de sessie als deze nog niet gestart is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// HELPERFUNCTIES
// ============================================================================

/**
 * safeEcho - Veilige output escaping tegen XSS-aanvallen.
 *
 * htmlspecialchars zet speciale tekens om naar HTML-entiteiten.
 * Voorbeeld: <script> wordt &lt;script&gt; (veilig om weer te geven).
 *
 * @param string $string Tekst om te escapen
 * @return string Veilige HTML-escaped tekst
 */
function safeEcho($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * validateRequired - Valideer verplichte velden (BUGFIX #1001).
 *
 * BUGFIX #1001: Voorkomt opslaan van velden met alleen spaties.
 * trim() verwijdert witruimte van begin en einde.
 * Regex /^\s*$/ controleert op alleen-spaties invoer.
 *
 * @param string $value     Waarde om te valideren
 * @param string $fieldName Veldnaam voor de foutmelding
 * @param int    $maxLength Maximum toegestane lengte (0 = geen limiet)
 * @return string|null Foutmelding of null als geldig
 */
function validateRequired($value, $fieldName, $maxLength = 0)
{
    $value = trim($value);

    // BUGFIX #1001: Controleer op leeg of alleen-spaties invoer
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        return "$fieldName mag niet leeg zijn of alleen spaties bevatten.";
    }

    // Controleer maximale lengte indien opgegeven
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName overschrijdt de maximale lengte van $maxLength tekens.";
    }

    return null; // Geldig
}

/**
 * validateDate - Valideer datum formaat en toekomstige datum (BUGFIX #1004).
 *
 * BUGFIX #1004: Voorkomt ongeldige datums zoals 2025-13-45.
 * DateTime::createFromFormat() controleert STRIKT of de datum bestaat.
 * De geformatteerde datum wordt vergeleken met de invoer ter verificatie.
 *
 * @param string $date Datum string om te valideren (verwacht: JJJJ-MM-DD)
 * @return string|null Foutmelding of null als geldig
 */
function validateDate($date)
{
    // BUGFIX #1004: Gebruik DateTime voor STRIKTE datumvalidatie
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);

    // Controleer of datum correct geparsed is EN exact overeenkomt met invoer
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        return "Ongeldig datumformaat. Gebruik JJJJ-MM-DD.";
    }

    // Controleer of datum vandaag of in de toekomst is
    $today = new DateTime('today');
    if ($dateObj < $today) {
        return "De datum moet vandaag of in de toekomst zijn.";
    }

    return null;
}

/**
 * validateTime - Valideer tijdformaat UU:MM.
 * Regex: 00-23 voor uren, 00-59 voor minuten.
 */
function validateTime($time)
{
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
        return "Ongeldig tijdformaat. Gebruik UU:MM.";
    }
    return null;
}

/**
 * validateEmail - Valideer e-mailformaat.
 */
function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Ongeldig e-mailformaat.";
    }
    return null;
}

/**
 * validateUrl - Valideer URL-formaat (optioneel veld).
 */
function validateUrl($url)
{
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Ongeldig URL-formaat.";
    }
    return null;
}

/**
 * validateCommaSeparated - Valideer kommagescheiden waarden.
 */
function validateCommaSeparated($value, $fieldName)
{
    if (empty($value))
        return null;
    $items = explode(',', $value);
    foreach ($items as $item) {
        if (empty(trim($item))) {
            return "$fieldName bevat lege items.";
        }
    }
    return null;
}

// ============================================================================
// SESSIEBERICHT FUNCTIES
// ============================================================================

/**
 * setMessage - Bewaar een bericht in de sessie voor weergave.
 */
function setMessage($type, $msg)
{
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg];
}

/**
 * getMessage - Haal het sessiebericht op en wis het daarna.
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
// AUTHENTICATIEFUNCTIES
// ============================================================================

/**
 * isLoggedIn - Controleer of de gebruiker ingelogd is.
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * getUserId - Haal het ID van de huidige gebruiker op.
 */
function getUserId()
{
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}

/**
 * updateLastActivity - Werk de laatste activiteitstimestamp van de gebruiker bij.
 */
function updateLastActivity($pdo, $userId)
{
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
}

/**
 * checkSessionTimeout - Controleer of de sessie verlopen is (30 minuten).
 * 1800 seconden = 30 minuten inactiviteit.
 */
function checkSessionTimeout()
{
    if (isLoggedIn() && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        header("Location: login.php?msg=session_timeout");
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/**
 * registerUser - Registreer een nieuw gebruikersaccount.
 *
 * Valideert gebruikersnaam, e-mail en wachtwoord.
 * Slaat het wachtwoord op als bcrypt-hash (NOOIT als leesbare tekst).
 */
function registerUser($username, $email, $password)
{
    $pdo = getDBConnection();

    // Valideer alle invoervelden
    if ($err = validateRequired($username, "Gebruikersnaam", 50))
        return $err;
    if ($err = validateEmail($email))
        return $err;
    if ($err = validateRequired($password, "Wachtwoord"))
        return $err;
    if (strlen($password) < 8)
        return "Het wachtwoord moet minimaal 8 tekens bevatten.";

    // Controleer of het e-mailadres al bestaat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0)
        return "Dit e-mailadres is al geregistreerd.";

    // Hash het wachtwoord met bcrypt (veilig!)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Voeg nieuwe gebruiker in de database in
    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");
    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null;
    } catch (PDOException $e) {
        error_log("Registratie mislukt: " . $e->getMessage());
        return "Registratie mislukt. Probeer het opnieuw.";
    }
}

/**
 * loginUser - Authenticeer en log de gebruiker in.
 *
 * BUGFIX #1006: session_regenerate_id() wordt ALLEEN hier aangeroepen,
 * na succesvolle authenticatie, niet bij elk paginaverzoek.
 */
function loginUser($email, $password)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($email, "E-mailadres"))
        return $err;
    if ($err = validateRequired($password, "Wachtwoord"))
        return $err;

    // Haal de gebruiker op via e-mailadres
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Verifieer het wachtwoord met bcrypt
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return "Ongeldig e-mailadres of wachtwoord.";
    }

    // Sla sessievariabelen op
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    // BUGFIX #1006: Vernieuw sessie-ID alleen na succesvolle login
    session_regenerate_id(true);
    updateLastActivity($pdo, $user['user_id']);
    return null;
}

/**
 * logout - Vernietig de sessie en stuur door naar de loginpagina.
 */
function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}

// ============================================================================
// SPELFUNCTIES
// ============================================================================

/**
 * getOrCreateGameId - Haal bestaand spel op of maak een nieuw spel aan.
 */
function getOrCreateGameId($pdo, $title, $description = '')
{
    $title = trim($title);
    if (empty($title))
        return 0;

    // Controleer of het spel al bestaat (hoofdletterongevoelig)
    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL");
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();
    if ($row)
        return $row['game_id'];

    // Maak een nieuw spel aan
    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);
    return $pdo->lastInsertId();
}

/**
 * addFavoriteGame - Voeg een spel toe aan de favorieten van de gebruiker.
 */
function addFavoriteGame($userId, $title, $description = '', $note = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Speltitel", 100))
        return $err;
    $gameId = getOrCreateGameId($pdo, $title, $description);

    // Controleer of het spel al als favoriet is toegevoegd
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0)
        return "Dit spel staat al in uw favorieten.";

    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);
    return null;
}

/**
 * updateFavoriteGame - Werk de details van een favoriet spel bij.
 */
function updateFavoriteGame($userId, $gameId, $title, $description, $note)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Speltitel", 100))
        return $err;

    // Controleer eigenaarschap
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0)
        return "Geen toestemming om te bewerken.";

    // Werk spelgegevens bij
    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => $title, 'description' => $description, 'game_id' => $gameId]);

    // Werk de notitie bij
    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * deleteFavoriteGame - Verwijder een spel uit de favorieten.
 */
function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * getFavoriteGames - Haal de favoriete spellen van de gebruiker op.
 */
function getFavoriteGames($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT g.game_id, g.titel, g.description, ug.note FROM UserGames ug JOIN Games g ON ug.game_id = g.game_id WHERE ug.user_id = :user_id AND g.deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * getGames - Haal alle spellen op uit de database.
 */
function getGames()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel");
    return $stmt->fetchAll();
}

// ============================================================================
// VRIENDFUNCTIES
// ============================================================================

function addFriend($userId, $friendUsername, $note = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Gebruikersnaam vriend", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Controleer of ze al vrienden zijn
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername]);
    if ($stmt->fetchColumn() > 0)
        return "U bent al vrienden met deze gebruiker.";

    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_username, note, status) VALUES (:user_id, :friend_username, :note, :status)");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername, 'note' => $note, 'status' => $status]);
    return null;
}

function updateFriend($userId, $friendId, $friendUsername, $note, $status)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Gebruikersnaam vriend", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    if ($stmt->fetchColumn() == 0)
        return "Geen vrienden of geen toestemming.";

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
// SCHEMAFUNCTIES
// ============================================================================

function addSchedule($userId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($gameTitle, "Speltitel", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if ($err = validateCommaSeparated($friendsStr, "Vrienden"))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Gedeeld met"))
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
        return "Geen toestemming.";
    if ($err = validateRequired($gameTitle, "Speltitel", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if ($err = validateCommaSeparated($friendsStr, "Vrienden"))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Gedeeld met"))
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
        return "Geen toestemming.";
    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// ============================================================================
// EVENEMENTFUNCTIES
// ============================================================================

function addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Titel", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if (!empty($description) && strlen($description) > 500)
        return "Beschrijving te lang (max. 500 tekens).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Ongeldige herinnering.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Gedeeld met"))
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
        return "Geen toestemming.";
    if ($err = validateRequired($title, "Titel", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;
    if (!empty($description) && strlen($description) > 500)
        return "Beschrijving te lang (max. 500 tekens).";
    if (!in_array($reminder, ['none', '1_hour', '1_day']))
        return "Ongeldige herinnering.";
    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Gedeeld met"))
        return $err;

    $stmt = $pdo->prepare("UPDATE Events SET title = :title, date = :date, time = :time, description = :description, reminder = :reminder, external_link = :external_link, shared_with = :shared_with WHERE event_id = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['title' => $title, 'date' => $date, 'time' => $time, 'description' => $description, 'reminder' => $reminder, 'external_link' => $externalLink, 'shared_with' => $sharedWithStr, 'id' => $eventId, 'user_id' => $userId]);
    return null;
}

function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId))
        return "Geen toestemming.";
    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}

// ============================================================================
// HULPFUNCTIES
// ============================================================================

/**
 * checkOwnership - Controleer of de gebruiker eigenaar is van het record.
 */
function checkOwnership($pdo, $table, $idColumn, $id, $userId)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}

/**
 * getCalendarItems - Haal alle schema's en evenementen gecombineerd op, gesorteerd op datum/tijd.
 */
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

/**
 * getReminders - Haal evenementen op waarvoor de herinnering op dit moment actief is.
 */
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