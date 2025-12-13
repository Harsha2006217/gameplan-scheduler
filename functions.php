<?php
/**
 * ============================================================================
 * functions.php - Kern Functies en Database Queries
 * ============================================================================
 * 
 * @author      Harsha Kanaparthi
 * @student     2195344
 * @date        30-09-2025
 * @version     1.0
 * @project     GamePlan Scheduler
 * 
 * ============================================================================
 * BESCHRIJVING / DESCRIPTION:
 * ============================================================================
 * Dit is het belangrijkste bestand van de applicatie. Het bevat ALLE functies
 * die de applicatie nodig heeft voor:
 * 
 * 1. HELPER FUNCTIES - Algemene hulpfuncties (validatie, berichten, etc.)
 * 2. GEBRUIKER AUTHENTICATIE - Registreren, inloggen, uitloggen
 * 3. PROFIEL BEHEER - Favoriete games toevoegen/bewerken/verwijderen
 * 4. VRIENDEN BEHEER - Vrienden toevoegen/bewerken/verwijderen
 * 5. SCHEMA'S BEHEER - Speelschema's toevoegen/bewerken/verwijderen
 * 6. EVENEMENTEN BEHEER - Evenementen toevoegen/bewerken/verwijderen
 * 
 * This is the most important file of the application. It contains ALL functions
 * the application needs for user auth, profile, friends, schedules, and events.
 * 
 * ============================================================================
 * BEVEILIGING / SECURITY:
 * ============================================================================
 * - Prepared statements tegen SQL-injectie
 * - htmlspecialchars() tegen XSS-aanvallen
 * - password_hash() met bcrypt voor veilige wachtwoorden
 * - Sessie timeout na 30 minuten inactiviteit
 * - Eigendom controles bij bewerken/verwijderen
 * 
 * ============================================================================
 * BUG FIXES GEÏMPLEMENTEERD / BUG FIXES IMPLEMENTED:
 * ============================================================================
 * #1001: Validatie voor lege velden/spaties - validateRequired() met trim()
 * #1004: Datum validatie in validateDate() - Controleert geldig formaat
 * ============================================================================
 */

// ============================================================================
// OUTPUT BUFFERING STARTEN
// ============================================================================
// ob_start() start output buffering. Dit voorkomt "headers already sent"
// fouten wanneer we header() of session functies gebruiken na HTML output.
// Alle output wordt vastgehouden totdat we klaar zijn met alle PHP code.
// ============================================================================
ob_start();

// ============================================================================
// DATABASE VERBINDING LADEN
// ============================================================================
// require_once zorgt ervoor dat db.php exact één keer wordt geladen.
// Dit voorkomt fouten als het bestand meerdere keren wordt included.
// ============================================================================
require_once 'db.php';

// ============================================================================
// SESSIE STARTEN
// ============================================================================
// Sessies zijn nodig om gebruikers ingelogd te houden tussen pagina's.
// Een sessie slaat gegevens op de server op en koppelt deze aan de browser
// via een cookie met een unieke sessie ID.
// 
// session_status() controleert of er al een sessie actief is:
// - PHP_SESSION_NONE = geen sessie gestart
// - PHP_SESSION_ACTIVE = sessie al gestart
// ============================================================================
if (session_status() === PHP_SESSION_NONE) {
    // Start een nieuwe sessie
    session_start();

    // ========================================================================
    // SESSIE ID REGENEREREN VOOR VEILIGHEID
    // ========================================================================
    // session_regenerate_id(true) maakt een nieuwe sessie ID aan.
    // Dit beschermt tegen "session fixation" aanvallen waarbij een
    // aanvaller een slachtoffer een bekende sessie ID geeft.
    // ========================================================================
    session_regenerate_id(true);
}

// ############################################################################
// ##                                                                        ##
// ##                    HELPER FUNCTIES / HELPER FUNCTIONS                  ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * safeEcho() - Veilige Output Functie (XSS Bescherming)
 * ============================================================================
 * 
 * Deze functie is ESSENTIEEL voor beveiliging! Het beschermt tegen
 * XSS (Cross-Site Scripting) aanvallen door speciale HTML tekens om te
 * zetten naar veilige HTML entities.
 * 
 * VOORBEELD:
 * - Input:  <script>alert('hack')</script>
 * - Output: &lt;script&gt;alert('hack')&lt;/script&gt;
 * 
 * Nu wordt de kwaadaardige code getoond als tekst, niet uitgevoerd!
 * 
 * @param string $string De tekst om veilig te maken
 * @return string De veilige tekst die in HTML kan worden getoond
 * 
 * GEBRUIK:
 * echo safeEcho($gebruikersnaam); // Veilig
 * echo $gebruikersnaam;           // ONVEILIG! Nooit doen!
 */
function safeEcho($string)
{
    // htmlspecialchars() zet om:
    // & wordt &amp;
    // < wordt &lt;
    // > wordt &gt;
    // " wordt &quot;
    // ' wordt &#039;
    // 
    // ENT_QUOTES = zet zowel enkele als dubbele aanhalingstekens om
    // UTF-8 = de karakterset die we gebruiken
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * ============================================================================
 * validateRequired() - Valideer Verplichte Velden
 * ============================================================================
 * 
 * Deze functie controleert of een veld:
 * 1. Niet leeg is
 * 2. Niet alleen spaties bevat (BUG FIX #1001!)
 * 3. Niet langer is dan toegestaan
 * 
 * @param string $value     De waarde om te controleren
 * @param string $fieldName De naam van het veld (voor foutmeldingen)
 * @param int    $maxLength Maximale lengte (0 = geen limiet)
 * @return string|null      Foutmelding of null als alles OK is
 * 
 * VOORBEELD:
 * $error = validateRequired($username, "Username", 50);
 * if ($error) {
 *     echo $error; // "Username may not be empty..."
 * }
 */
function validateRequired($value, $fieldName, $maxLength = 0)
{
    // ========================================================================
    // STAP 1: TRIM - Verwijder spaties aan begin en eind
    // ========================================================================
    // Dit is onderdeel van BUG FIX #1001!
    // trim() verwijdert spaties, tabs, newlines aan beide kanten
    // ========================================================================
    $value = trim($value);

    // ========================================================================
    // STAP 2: CONTROLEER OF LEEG OF ALLEEN SPATIES
    // ========================================================================
    // empty() controleert of de waarde leeg is na trimmen
    // preg_match('/^\s*$/') controleert of de string ALLEEN whitespace is
    // \s = whitespace karakter (spatie, tab, newline, etc.)
    // * = nul of meer keer
    // ^ = begin van string
    // $ = eind van string
    // ========================================================================
    if (empty($value) || preg_match('/^\s*$/', $value)) {
        return "$fieldName may not be empty or contain only spaces.";
    }

    // ========================================================================
    // STAP 3: CONTROLEER MAXIMALE LENGTE
    // ========================================================================
    // strlen() geeft het aantal karakters in de string
    // We controleren alleen als maxLength groter dan 0 is
    // ========================================================================
    if ($maxLength > 0 && strlen($value) > $maxLength) {
        return "$fieldName exceeds maximum length of $maxLength characters.";
    }

    // ========================================================================
    // STAP 4: ALLES OK - GEEF NULL TERUG
    // ========================================================================
    // null betekent "geen fout"
    // ========================================================================
    return null;
}

/**
 * ============================================================================
 * validateDate() - Valideer Datum (BUG FIX #1004!)
 * ============================================================================
 * 
 * Deze functie controleert of een datum:
 * 1. Een geldig formaat heeft (YYYY-MM-DD)
 * 2. Een geldige datum is (niet 2025-13-45)
 * 3. In de toekomst ligt (voor events en schedules)
 * 
 * BUG FIX #1004: Voorheen crashte de database bij ongeldige datums
 *                zoals "2025-13-45". Nu controleren we dit vooraf!
 * 
 * @param string $date De datum string om te controleren (YYYY-MM-DD)
 * @return string|null Foutmelding of null als alles OK is
 * 
 * VOORBEELD:
 * $error = validateDate("2025-12-25"); // null (OK, kerst in de toekomst)
 * $error = validateDate("2025-13-45"); // "Invalid date format."
 * $error = validateDate("2020-01-01"); // "Date must be in the future."
 */
function validateDate($date)
{
    // ========================================================================
    // STAP 1: CONTROLEER DATUM FORMAAT MET REGEX
    // ========================================================================
    // We verwachten formaat: YYYY-MM-DD
    // \d{4} = exact 4 cijfers (jaar)
    // - = letterlijk streepje
    // \d{2} = exact 2 cijfers (maand en dag)
    // ========================================================================
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return "Invalid date format. Use YYYY-MM-DD.";
    }

    // ========================================================================
    // STAP 2: SPLITS DE DATUM IN DELEN
    // ========================================================================
    // explode() splitst de string op het streepje
    // list() wijst de delen toe aan variabelen
    // ========================================================================
    list($year, $month, $day) = explode('-', $date);

    // ========================================================================
    // STAP 3: CONTROLEER OF HET EEN ECHTE DATUM IS
    // ========================================================================
    // checkdate() controleert of de combinatie maand/dag/jaar geldig is
    // Dit vangt ongeldige datums zoals 30 februari of maand 13
    // 
    // intval() zet de strings om naar integers
    // ========================================================================
    if (!checkdate(intval($month), intval($day), intval($year))) {
        return "Invalid date. This date does not exist.";
    }

    // ========================================================================
    // STAP 4: CONTROLEER OF DATUM IN DE TOEKOMST IS
    // ========================================================================
    // strtotime() zet de datum om naar een Unix timestamp
    // time() geeft de huidige Unix timestamp
    // strtotime('today') geeft het begin van vandaag (00:00:00)
    // 
    // We vergelijken met vandaag zodat een schema voor vandaag nog geldig is
    // ========================================================================
    if (strtotime($date) < strtotime('today')) {
        return "Date must be today or in the future.";
    }

    // ========================================================================
    // STAP 5: ALLES OK
    // ========================================================================
    return null;
}

/**
 * ============================================================================
 * validateTime() - Valideer Tijd Formaat
 * ============================================================================
 * 
 * Controleert of de tijd in geldig HH:MM formaat is.
 * - Uren: 00-23
 * - Minuten: 00-59
 * 
 * @param string $time De tijd string om te controleren
 * @return string|null Foutmelding of null als alles OK is
 * 
 * VOORBEELD:
 * $error = validateTime("14:30"); // null (OK)
 * $error = validateTime("25:00"); // "Invalid time format..."
 * $error = validateTime("12:60"); // "Invalid time format..."
 */
function validateTime($time)
{
    // ========================================================================
    // REGEX UITLEG:
    // ========================================================================
    // ^ = begin van string
    // ([01]?[0-9]|2[0-3]) = uren 0-23
    //   - [01]?[0-9] = 0-19 (optionele 0 of 1 gevolgd door 0-9)
    //   - 2[0-3] = 20-23
    // : = letterlijk dubbele punt
    // [0-5][0-9] = minuten 00-59
    // $ = eind van string
    // ========================================================================
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
        return "Invalid time format. Use HH:MM (24-hour format).";
    }
    return null;
}

/**
 * ============================================================================
 * validateEmail() - Valideer E-mail Adres
 * ============================================================================
 * 
 * Controleert of een e-mail adres geldig formaat heeft.
 * Gebruikt PHP's ingebouwde filter_var() functie.
 * 
 * @param string $email Het e-mail adres om te controleren
 * @return string|null Foutmelding of null als geldig
 */
function validateEmail($email)
{
    // FILTER_VALIDATE_EMAIL controleert of het een geldig e-mail formaat is
    // Dit is betrouwbaarder dan zelf een regex schrijven
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return null;
}

/**
 * ============================================================================
 * validateUrl() - Valideer URL
 * ============================================================================
 * 
 * Controleert of een URL geldig formaat heeft.
 * Leeg is toegestaan (URL is optioneel).
 * 
 * @param string $url De URL om te controleren
 * @return string|null Foutmelding of null als geldig
 */
function validateUrl($url)
{
    // Als leeg, is het OK (URL is optioneel)
    if (empty($url)) {
        return null;
    }
    // FILTER_VALIDATE_URL controleert of het een geldige URL is
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return "Invalid URL format. Include http:// or https://";
    }
    return null;
}

/**
 * ============================================================================
 * validateCommaSeparated() - Valideer Komma-gescheiden Lijst
 * ============================================================================
 * 
 * Controleert of een komma-gescheiden lijst (bijv. "jan,piet,klaas")
 * geen lege items bevat.
 * 
 * @param string $value     De komma-gescheiden string
 * @param string $fieldName De veldnaam voor foutmeldingen
 * @return string|null      Foutmelding of null als geldig
 */
function validateCommaSeparated($value, $fieldName)
{
    // Leeg is OK (veld is optioneel)
    if (empty($value)) {
        return null;
    }

    // Splits de string op komma's
    $items = explode(',', $value);

    // Controleer elk item
    foreach ($items as $item) {
        $item = trim($item); // Verwijder spaties
        if (empty($item)) {
            return "$fieldName contains empty items. Check for double commas.";
        }
    }
    return null;
}

/**
 * ============================================================================
 * setMessage() - Sla een Sessie Bericht Op
 * ============================================================================
 * 
 * Slaat een bericht op in de sessie dat op de volgende pagina getoond
 * kan worden. Gebruikt voor succes- en foutmeldingen.
 * 
 * @param string $type Het type bericht ('success', 'danger', 'warning')
 * @param string $msg  De berichttekst
 * 
 * VOORBEELD:
 * setMessage('success', 'Profile updated!');
 * header("Location: profile.php");
 * // Op profile.php wordt het bericht getoond
 */
function setMessage($type, $msg)
{
    $_SESSION['message'] = ['type' => $type, 'msg' => $msg];
}

/**
 * ============================================================================
 * getMessage() - Haal en Toon Sessie Bericht
 * ============================================================================
 * 
 * Haalt het sessie bericht op, maakt er HTML van, en verwijdert het
 * uit de sessie (zodat het maar één keer wordt getoond).
 * 
 * @return string HTML van het bericht, of lege string als er geen is
 */
function getMessage()
{
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']); // Verwijder na ophalen
        // Bootstrap alert class: alert-success, alert-danger, etc.
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show' role='alert'>
                    {$msg['msg']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }
    return '';
}

/**
 * ============================================================================
 * isLoggedIn() - Controleer of Gebruiker is Ingelogd
 * ============================================================================
 * 
 * Controleert of de user_id in de sessie is opgeslagen, wat betekent
 * dat de gebruiker succesvol is ingelogd.
 * 
 * @return bool true als ingelogd, false als niet
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * ============================================================================
 * getUserId() - Haal Huidige Gebruiker ID Op
 * ============================================================================
 * 
 * Geeft de user_id van de ingelogde gebruiker.
 * Geeft 0 als niet ingelogd.
 * 
 * @return int De user_id of 0 als niet ingelogd
 */
function getUserId()
{
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}

/**
 * ============================================================================
 * updateLastActivity() - Update Laatst Actief Tijdstempel
 * ============================================================================
 * 
 * Update de last_activity kolom in de database. Dit kan worden gebruikt
 * om te zien wanneer een gebruiker voor het laatst online was.
 * 
 * @param PDO $pdo    De database verbinding
 * @param int $userId De gebruiker ID om te updaten
 */
function updateLastActivity($pdo, $userId)
{
    $stmt = $pdo->prepare("UPDATE Users SET last_activity = CURRENT_TIMESTAMP WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId]);
}

/**
 * ============================================================================
 * checkSessionTimeout() - Controleer Sessie Timeout
 * ============================================================================
 * 
 * Controleert of de sessie is verlopen (na 30 minuten inactiviteit).
 * Als verlopen, wordt de gebruiker uitgelogd en doorverwezen naar login.
 * 
 * Dit is een belangrijke beveiligingsmaatregel!
 */
function checkSessionTimeout()
{
    // Controleer alleen als gebruiker is ingelogd
    if (isLoggedIn() && isset($_SESSION['last_activity'])) {
        // 1800 seconden = 30 minuten
        if (time() - $_SESSION['last_activity'] > 1800) {
            // Verwijder alle sessie data
            session_destroy();
            // Verwijs door naar login met timeout bericht
            header("Location: login.php?msg=session_timeout");
            exit;
        }
    }
    // Update last_activity naar nu
    $_SESSION['last_activity'] = time();
}

// ############################################################################
// ##                                                                        ##
// ##                GEBRUIKER AUTHENTICATIE / USER AUTHENTICATION           ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * registerUser() - Registreer Nieuwe Gebruiker
 * ============================================================================
 * 
 * Registreert een nieuwe gebruiker in de database:
 * 1. Valideert alle invoer
 * 2. Controleert of e-mail al bestaat
 * 3. Hasht het wachtwoord veilig met bcrypt
 * 4. Slaat de gebruiker op in de database
 * 
 * @param string $username De gewenste gebruikersnaam
 * @param string $email    Het e-mail adres
 * @param string $password Het gekozen wachtwoord
 * @return string|null     Foutmelding of null bij succes
 * 
 * BEVEILIGING:
 * - password_hash() met PASSWORD_BCRYPT maakt wachtwoord onleesbaar
 * - email wordt gecontroleerd op duplicaten
 * - prepared statements beschermen tegen SQL-injectie
 */
function registerUser($username, $email, $password)
{
    $pdo = getDBConnection();

    // ========================================================================
    // STAP 1: VALIDEER ALLE INVOER
    // ========================================================================
    if ($err = validateRequired($username, "Username", 50))
        return $err;
    if ($err = validateEmail($email))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;

    // Extra wachtwoord eis: minimaal 8 karakters
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters.";
    }

    // ========================================================================
    // STAP 2: CONTROLEER OF EMAIL AL BESTAAT
    // ========================================================================
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        return "Email already registered. Please use a different email or login.";
    }

    // ========================================================================
    // STAP 3: HASH HET WACHTWOORD
    // ========================================================================
    // password_hash() met PASSWORD_BCRYPT:
    // - Maakt het wachtwoord onleesbaar
    // - Voegt automatisch een "salt" toe (extra beveiliging)
    // - Is one-way: je kunt de hash NIET terugzetten naar het wachtwoord
    // ========================================================================
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // ========================================================================
    // STAP 4: SLA GEBRUIKER OP IN DATABASE
    // ========================================================================
    $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash) VALUES (:username, :email, :hash)");

    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null; // Succes!
    } catch (PDOException $e) {
        error_log("Registration failed: " . $e->getMessage());
        return "Registration failed. Please try again.";
    }
}

/**
 * ============================================================================
 * loginUser() - Log Gebruiker In
 * ============================================================================
 * 
 * Authenticeert een gebruiker:
 * 1. Zoekt de gebruiker op basis van e-mail
 * 2. Verifieert het wachtwoord tegen de hash
 * 3. Start een sessie bij succes
 * 
 * @param string $email    Het e-mail adres
 * @param string $password Het wachtwoord
 * @return string|null     Foutmelding of null bij succes
 */
function loginUser($email, $password)
{
    $pdo = getDBConnection();

    // Valideer invoer
    if ($err = validateRequired($email, "Email"))
        return $err;
    if ($err = validateRequired($password, "Password"))
        return $err;

    // ========================================================================
    // ZOEK GEBRUIKER IN DATABASE
    // ========================================================================
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM Users WHERE email = :email AND deleted_at IS NULL");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // ========================================================================
    // VERIFIEER WACHTWOORD
    // ========================================================================
    // password_verify() vergelijkt het ingevoerde wachtwoord met de hash
    // Dit is veilig: het wachtwoord wordt gehasht en vergeleken
    // ========================================================================
    if (!$user || !password_verify($password, $user['password_hash'])) {
        // Generieke foutmelding (zeg niet of email of wachtwoord verkeerd is)
        return "Invalid email or password.";
    }

    // ========================================================================
    // START SESSIE
    // ========================================================================
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    session_regenerate_id(true); // Nieuwe sessie ID voor veiligheid
    updateLastActivity($pdo, $user['user_id']);

    return null; // Succes!
}

/**
 * ============================================================================
 * logout() - Log Gebruiker Uit
 * ============================================================================
 * 
 * Verwijdert alle sessie data en verwijst door naar login pagina.
 */
function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}

// ############################################################################
// ##                                                                        ##
// ##                    PROFIEL BEHEER / PROFILE MANAGEMENT                 ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * getOrCreateGameId() - Haal of Maak Game ID
 * ============================================================================
 * 
 * Zoekt een game op titel of maakt een nieuw aan als het niet bestaat.
 * Hierdoor kunnen gebruikers elke game toevoegen, niet alleen voorgedefinieerde.
 * 
 * @param PDO    $pdo         De database verbinding
 * @param string $title       De game titel
 * @param string $description De game beschrijving (optioneel)
 * @return int                De game_id
 */
function getOrCreateGameId($pdo, $title, $description = '')
{
    $title = trim($title);
    if (empty($title))
        return 0;

    // Zoek bestaande game (case-insensitive)
    $stmt = $pdo->prepare("SELECT game_id FROM Games WHERE LOWER(titel) = LOWER(:title) AND deleted_at IS NULL");
    $stmt->execute(['title' => $title]);
    $row = $stmt->fetch();

    if ($row) {
        return $row['game_id']; // Game bestaat al
    }

    // Maak nieuwe game aan
    $stmt = $pdo->prepare("INSERT INTO Games (titel, description) VALUES (:titel, :description)");
    $stmt->execute(['titel' => $title, 'description' => $description]);
    return $pdo->lastInsertId(); // Geef de nieuwe ID terug
}

/**
 * ============================================================================
 * addFavoriteGame() - Voeg Favoriete Game Toe
 * ============================================================================
 * 
 * Voegt een game toe aan de favorieten van een gebruiker.
 * Als de game nog niet in de Games tabel staat, wordt deze eerst aangemaakt.
 * 
 * @param int    $userId      De gebruiker ID
 * @param string $title       De game titel
 * @param string $description Beschrijving (optioneel)
 * @param string $note        Persoonlijke notitie (optioneel)
 * @return string|null        Foutmelding of null bij succes
 */
function addFavoriteGame($userId, $title, $description = '', $note = '')
{
    $pdo = getDBConnection();

    // Valideer titel (BUG FIX #1001: ook spaties worden afgevangen)
    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Haal of maak game ID
    $gameId = getOrCreateGameId($pdo, $title, $description);

    // Controleer of al in favorieten
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0) {
        return "This game is already in your favorites.";
    }

    // Voeg toe aan favorieten
    $stmt = $pdo->prepare("INSERT INTO UserGames (user_id, game_id, note) VALUES (:user_id, :game_id, :note)");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'note' => $note]);
    return null; // Succes!
}

/**
 * ============================================================================
 * updateFavoriteGame() - Update Favoriete Game
 * ============================================================================
 * 
 * Update de details van een favoriete game.
 * Controleert eerst of de gebruiker eigenaar is.
 * 
 * @param int    $userId      De gebruiker ID
 * @param int    $gameId      De game ID
 * @param string $title       Nieuwe titel
 * @param string $description Nieuwe beschrijving
 * @param string $note        Nieuwe notitie
 * @return string|null        Foutmelding of null bij succes
 */
function updateFavoriteGame($userId, $gameId, $title, $description, $note)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($title, "Game title", 100))
        return $err;

    // Controleer eigendom
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0) {
        return "No permission to edit this game.";
    }

    // Update Games tabel
    $stmt = $pdo->prepare("UPDATE Games SET titel = :titel, description = :description WHERE game_id = :game_id AND deleted_at IS NULL");
    $stmt->execute(['titel' => $title, 'description' => $description, 'game_id' => $gameId]);

    // Update notitie in UserGames
    $stmt = $pdo->prepare("UPDATE UserGames SET note = :note WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['note' => $note, 'user_id' => $userId, 'game_id' => $gameId]);

    return null;
}

/**
 * ============================================================================
 * deleteFavoriteGame() - Verwijder Favoriete Game
 * ============================================================================
 * 
 * Verwijdert een game uit de favorieten van de gebruiker.
 * De game zelf blijft in de Games tabel (andere gebruikers kunnen hem hebben).
 * 
 * @param int $userId De gebruiker ID
 * @param int $gameId De game ID
 * @return string|null Foutmelding of null bij succes
 */
function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id");
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * ============================================================================
 * getFavoriteGames() - Haal Alle Favoriete Games Op
 * ============================================================================
 * 
 * Haalt alle favoriete games van een gebruiker op met hun details.
 * Gebruikt een JOIN om data uit zowel UserGames als Games te halen.
 * 
 * @param int $userId De gebruiker ID
 * @return array      Array met favoriete games
 */
function getFavoriteGames($userId)
{
    $pdo = getDBConnection();

    // JOIN combineert UserGames en Games tabellen
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

// ############################################################################
// ##                                                                        ##
// ##                   VRIENDEN BEHEER / FRIENDS MANAGEMENT                 ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * addFriend() - Voeg Vriend Toe
 * ============================================================================
 * 
 * Voegt een vriend toe op basis van gebruikersnaam.
 * Let op: de vriend hoeft geen account te hebben - dit is voor het
 * bijhouden van gamer-vrienden in het algemeen.
 * 
 * @param int    $userId         De gebruiker ID
 * @param string $friendUsername De gebruikersnaam van de vriend
 * @param string $note           Optionele notitie
 * @param string $status         Status (Online, Offline, Gaming, etc.)
 * @return string|null           Foutmelding of null bij succes
 */
function addFriend($userId, $friendUsername, $note = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    // Valideer invoer
    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Controleer of al vrienden
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND LOWER(friend_username) = LOWER(:friend_username) AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_username' => $friendUsername]);
    if ($stmt->fetchColumn() > 0) {
        return "You are already friends with this person.";
    }

    // Voeg vriend toe
    $stmt = $pdo->prepare("INSERT INTO Friends (user_id, friend_username, note, status) VALUES (:user_id, :friend_username, :note, :status)");
    $stmt->execute([
        'user_id' => $userId,
        'friend_username' => $friendUsername,
        'note' => $note,
        'status' => $status
    ]);
    return null;
}

/**
 * ============================================================================
 * updateFriend() - Update Vriend Details
 * ============================================================================
 * 
 * Update de informatie van een vriend.
 * 
 * @param int    $userId         De gebruiker ID
 * @param int    $friendId       De vriend ID
 * @param string $friendUsername Nieuwe gebruikersnaam
 * @param string $note           Nieuwe notitie
 * @param string $status         Nieuwe status
 * @return string|null           Foutmelding of null bij succes
 */
function updateFriend($userId, $friendId, $friendUsername, $note, $status)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($friendUsername, "Friend username", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Controleer eigendom
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Friends WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    if ($stmt->fetchColumn() == 0) {
        return "Friend not found or no permission to edit.";
    }

    // Update
    $stmt = $pdo->prepare("UPDATE Friends SET friend_username = :friend_username, note = :note, status = :status WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL");
    $stmt->execute([
        'friend_username' => $friendUsername,
        'note' => $note,
        'status' => $status,
        'user_id' => $userId,
        'friend_id' => $friendId
    ]);
    return null;
}

/**
 * ============================================================================
 * deleteFriend() - Verwijder Vriend (Soft Delete)
 * ============================================================================
 * 
 * "Soft delete" - zet deleted_at in plaats van echt verwijderen.
 * Dit behoudt data-integriteit en maakt het mogelijk om te "herstellen".
 * 
 * @param int $userId   De gebruiker ID
 * @param int $friendId De vriend ID
 * @return string|null  Foutmelding of null bij succes
 */
function deleteFriend($userId, $friendId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("UPDATE Friends SET deleted_at = NOW() WHERE user_id = :user_id AND friend_id = :friend_id");
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

/**
 * ============================================================================
 * getFriends() - Haal Alle Vrienden Op
 * ============================================================================
 * 
 * Haalt alle niet-verwijderde vrienden van een gebruiker op.
 * 
 * @param int $userId De gebruiker ID
 * @return array      Array met vrienden
 */
function getFriends($userId)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT friend_id, friend_username as username, status, note FROM Friends WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY friend_username ASC");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// ############################################################################
// ##                                                                        ##
// ##                  SCHEMA'S BEHEER / SCHEDULES MANAGEMENT                ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * addSchedule() - Voeg Speelschema Toe
 * ============================================================================
 * 
 * Voegt een nieuw speelschema toe voor een game.
 * 
 * @param int    $userId        De gebruiker ID
 * @param string $gameTitle     De game titel
 * @param string $date          De datum (YYYY-MM-DD)
 * @param string $time          De tijd (HH:MM)
 * @param string $friendsStr    Komma-gescheiden vrienden (optioneel)
 * @param string $sharedWithStr Komma-gescheiden delen met (optioneel)
 * @return string|null          Foutmelding of null bij succes
 */
function addSchedule($userId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Valideer alle invoer
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

    // Haal of maak game ID
    $gameId = getOrCreateGameId($pdo, $gameTitle);

    // Voeg schedule toe
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
 * ============================================================================
 * getSchedules() - Haal Alle Schema's Op
 * ============================================================================
 * 
 * Haalt alle schema's van een gebruiker op met sorteer optie.
 * 
 * @param int    $userId De gebruiker ID
 * @param string $sort   Sorteer volgorde (bijv. 'date ASC')
 * @return array         Array met schema's
 */
function getSchedules($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();

    // Whitelist van toegestane sorteer opties (voorkomt SQL-injectie)
    $allowedSorts = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $allowedSorts) ? $sort : 'date ASC';

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
 * ============================================================================
 * editSchedule() - Bewerk Schema
 * ============================================================================
 * 
 * Update een bestaand speelschema.
 * Controleert eerst eigendom.
 * 
 * @param int    $userId        De gebruiker ID
 * @param int    $scheduleId    De schema ID
 * @param string $gameTitle     Nieuwe game titel
 * @param string $date          Nieuwe datum
 * @param string $time          Nieuwe tijd
 * @param string $friendsStr    Nieuwe vrienden lijst
 * @param string $sharedWithStr Nieuwe gedeeld met lijst
 * @return string|null          Foutmelding of null bij succes
 */
function editSchedule($userId, $scheduleId, $gameTitle, $date, $time, $friendsStr = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Controleer eigendom
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId)) {
        return "No permission to edit this schedule.";
    }

    // Valideer invoer
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

    // Update
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
 * ============================================================================
 * deleteSchedule() - Verwijder Schema (Soft Delete)
 * ============================================================================
 */
function deleteSchedule($userId, $scheduleId)
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $scheduleId, $userId)) {
        return "No permission to delete this schedule.";
    }

    $stmt = $pdo->prepare("UPDATE Schedules SET deleted_at = NOW() WHERE schedule_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $scheduleId, 'user_id' => $userId]);
    return null;
}

// ############################################################################
// ##                                                                        ##
// ##                EVENEMENTEN BEHEER / EVENTS MANAGEMENT                  ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * addEvent() - Voeg Evenement Toe
 * ============================================================================
 * 
 * Voegt een nieuw evenement toe (bijv. toernooi).
 * 
 * @param int    $userId        De gebruiker ID
 * @param string $title         Evenement titel
 * @param string $date          Datum (YYYY-MM-DD)
 * @param string $time          Tijd (HH:MM)
 * @param string $description   Beschrijving
 * @param string $reminder      Herinnering type (none, 1_hour, 1_day)
 * @param string $externalLink  Optionele externe link
 * @param string $sharedWithStr Komma-gescheiden delen met
 * @return string|null          Foutmelding of null bij succes
 */
function addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    // Valideer alle invoer
    if ($err = validateRequired($title, "Title", 100))
        return $err;
    if ($err = validateDate($date))
        return $err;
    if ($err = validateTime($time))
        return $err;

    // Beschrijving lengte check
    if (!empty($description) && strlen($description) > 500) {
        return "Description too long (max 500 characters).";
    }

    // Reminder validatie (alleen toegestane waardes)
    $allowedReminders = ['none', '1_hour', '1_day'];
    if (!in_array($reminder, $allowedReminders)) {
        return "Invalid reminder option.";
    }

    if ($err = validateUrl($externalLink))
        return $err;
    if ($err = validateCommaSeparated($sharedWithStr, "Shared With"))
        return $err;

    // Voeg event toe
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

/**
 * ============================================================================
 * getEvents() - Haal Alle Evenementen Op
 * ============================================================================
 */
function getEvents($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();

    $allowedSorts = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $allowedSorts) ? $sort : 'date ASC';

    $stmt = $pdo->prepare("SELECT event_id, title, date, time, description, reminder, external_link, shared_with FROM Events WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY $sort LIMIT 50");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * ============================================================================
 * editEvent() - Bewerk Evenement
 * ============================================================================
 */
function editEvent($userId, $eventId, $title, $date, $time, $description, $reminder, $externalLink = '', $sharedWithStr = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "No permission to edit this event.";
    }

    // Zelfde validatie als addEvent
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

    // Update
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

/**
 * ============================================================================
 * deleteEvent() - Verwijder Evenement (Soft Delete)
 * ============================================================================
 */
function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "No permission to delete this event.";
    }

    $stmt = $pdo->prepare("UPDATE Events SET deleted_at = NOW() WHERE event_id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}

// ############################################################################
// ##                                                                        ##
// ##                    EXTRA FUNCTIES / ADDITIONAL FUNCTIONS               ##
// ##                                                                        ##
// ############################################################################

/**
 * ============================================================================
 * getGames() - Haal Alle Games Op
 * ============================================================================
 * 
 * Haalt alle games uit de database op (voor dropdown lijsten etc.)
 */
function getGames()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT game_id, titel, description FROM Games WHERE deleted_at IS NULL ORDER BY titel");
    return $stmt->fetchAll();
}

/**
 * ============================================================================
 * checkOwnership() - Controleer Eigendom
 * ============================================================================
 * 
 * Controleert of een item (schedule, event, etc.) eigendom is van de gebruiker.
 * Dit is een belangrijke beveiligingscontrole!
 * 
 * @param PDO    $pdo      De database verbinding
 * @param string $table    De tabel naam
 * @param string $idColumn De ID kolom naam
 * @param int    $id       De item ID
 * @param int    $userId   De gebruiker ID
 * @return bool            true als eigenaar, false als niet
 */
function checkOwnership($pdo, $table, $idColumn, $id, $userId)
{
    // We kunnen geen prepared statement gebruiken voor tabel/kolom namen
    // Dus valideren we deze handmatig
    $allowedTables = ['Schedules', 'Events', 'Friends'];
    $allowedColumns = ['schedule_id', 'event_id', 'friend_id'];

    if (!in_array($table, $allowedTables) || !in_array($idColumn, $allowedColumns)) {
        return false;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $idColumn = :id AND user_id = :user_id AND deleted_at IS NULL");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}

/**
 * ============================================================================
 * getCalendarItems() - Haal Gecombineerde Kalender Items Op
 * ============================================================================
 * 
 * Combineert schedules en events voor de kalender weergave.
 * Sorteert op datum en tijd.
 * 
 * @param int $userId De gebruiker ID
 * @return array      Gecombineerde en gesorteerde items
 */
function getCalendarItems($userId)
{
    $schedules = getSchedules($userId);
    $events = getEvents($userId);

    // Combineer beide arrays
    $items = array_merge($schedules, $events);

    // Sorteer op datum en tijd
    usort($items, function ($a, $b) {
        $dateA = strtotime($a['date'] . ' ' . $a['time']);
        $dateB = strtotime($b['date'] . ' ' . $b['time']);
        return $dateA <=> $dateB; // Spaceship operator
    });

    return $items;
}

/**
 * ============================================================================
 * getReminders() - Haal Actieve Herinneringen Op
 * ============================================================================
 * 
 * Haalt events op die een herinnering moeten tonen.
 * Wordt gebruikt door JavaScript voor pop-up meldingen.
 * 
 * @param int $userId De gebruiker ID
 * @return array      Array van events met actieve herinneringen
 */
function getReminders($userId)
{
    $events = getEvents($userId);
    $reminders = [];

    foreach ($events as $event) {
        if ($event['reminder'] !== 'none') {
            $eventTime = strtotime($event['date'] . ' ' . $event['time']);

            // Bereken herinnerings tijd
            $reminderOffset = ($event['reminder'] === '1_hour') ? 3600 : 86400;
            $reminderTime = $eventTime - $reminderOffset;

            // Toon als we binnen de herinnerings tijdsperiode zijn
            if ($reminderTime <= time() && $reminderTime > time() - 300) {
                $reminders[] = $event;
            }
        }
    }

    return $reminders;
}