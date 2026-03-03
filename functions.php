<?php
/**
 * ==========================================================================
 * FUNCTIONS.PHP - ALLE FUNCTIES VAN DE APPLICATIE
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand bevat alle functies die de applicatie nodig heeft:
 * - Sessie beheer (inloggen, uitloggen, timeout)
 * - Validatie (invoer controleren op fouten)
 * - Database bewerkingen (toevoegen, ophalen, bewerken, verwijderen)
 * - Hulpfuncties (veilige uitvoer, berichten tonen)
 *
 * Alle database queries gebruiken prepared statements tegen SQL-injectie.
 * Alle uitvoer wordt beveiligd met htmlspecialchars tegen XSS-aanvallen.
 * ==========================================================================
 */

// Start output buffering om "headers already sent" fouten te voorkomen
ob_start();

// Laad de database verbinding uit db.php
require_once 'db.php';

// --------------------------------------------------------------------------
// SESSIE STARTEN
// --------------------------------------------------------------------------
// Controleer of er al een sessie actief is, zo niet: start er een.
// Een sessie onthoudt wie er ingelogd is tussen pagina-verzoeken.
// --------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==========================================================================
// SECTIE 1: HULPFUNCTIES
// ==========================================================================

/**
 * safeEcho - Maakt tekst veilig om te tonen in HTML (beschermt tegen XSS)
 *
 * XSS (Cross-Site Scripting) is een aanval waarbij iemand kwaadaardige
 * code invoert in een formulier. htmlspecialchars zet gevaarlijke tekens
 * om naar veilige HTML-codes. Voorbeeld: <script> wordt &lt;script&gt;
 *
 * @param string $tekst  De tekst om veilig te maken
 * @return string        Veilige tekst die getoond kan worden
 */
function safeEcho($tekst)
{
    return htmlspecialchars($tekst ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * validateRequired - Controleer of een verplicht veld correct is ingevuld
 *
 * Deze functie controleert twee dingen:
 * 1. Is het veld niet leeg?
 * 2. Bevat het veld niet alleen spaties? (Bug fix #1001)
 *
 * @param string $waarde     De ingevulde waarde
 * @param string $veldnaam   Naam van het veld (voor de foutmelding)
 * @param int    $maxLengte  Maximum aantal tekens (0 = geen limiet)
 * @return string|null       Foutmelding of null als alles goed is
 */
function validateRequired($waarde, $veldnaam, $maxLengte = 0)
{
    // Verwijder spaties aan het begin en einde
    $waarde = trim($waarde);

    // Controleer of het veld leeg is of alleen spaties bevat
    if (empty($waarde) || preg_match('/^\s*$/', $waarde)) {
        return "$veldnaam mag niet leeg zijn of alleen spaties bevatten.";
    }

    // Controleer of de tekst niet te lang is
    if ($maxLengte > 0 && strlen($waarde) > $maxLengte) {
        return "$veldnaam is te lang (maximaal $maxLengte tekens).";
    }

    return null; // Geen fout gevonden
}

/**
 * validateDate - Controleer of een datum geldig is en in de toekomst ligt
 *
 * Deze functie beschermt tegen ongeldige datums zoals "2025-13-45".
 * Het gebruikt DateTime::createFromFormat voor strikte controle. (Bug fix #1004)
 *
 * @param string $datum  Datum in formaat JJJJ-MM-DD
 * @return string|null   Foutmelding of null als alles goed is
 */
function validateDate($datum)
{
    // Maak een DateTime object van de ingevoerde datum
    $datumObject = DateTime::createFromFormat('Y-m-d', $datum);

    // Controleer of de datum geldig is (bijv. geen 31 februari)
    if (!$datumObject || $datumObject->format('Y-m-d') !== $datum) {
        return "Ongeldig datum formaat. Gebruik JJJJ-MM-DD.";
    }

    // Controleer of de datum vandaag of in de toekomst is
    $vandaag = new DateTime('today');
    if ($datumObject < $vandaag) {
        return "Datum moet vandaag of in de toekomst zijn.";
    }

    return null;
}

/**
 * validateTime - Controleer of een tijd geldig is (formaat UU:MM)
 *
 * @param string $tijd  Tijd om te controleren
 * @return string|null  Foutmelding of null als alles goed is
 */
function validateTime($tijd)
{
    // Controleer met regex: uren 00-23, minuten 00-59
    if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $tijd)) {
        return "Ongeldig tijd formaat. Gebruik UU:MM (bijv. 15:00).";
    }
    return null;
}

/**
 * validateEmail - Controleer of een e-mailadres geldig is
 *
 * @param string $email  E-mailadres om te controleren
 * @return string|null   Foutmelding of null als alles goed is
 */
function validateEmail($email)
{
    // PHP filter_var controleert het e-mail formaat automatisch
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Ongeldig e-mail formaat.";
    }
    return null;
}

/**
 * validateUrl - Controleer of een URL geldig is (optioneel veld)
 *
 * @param string $url  URL om te controleren
 * @return string|null Foutmelding of null als alles goed is
 */
function validateUrl($url)
{
    if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
        return "Ongeldig URL formaat.";
    }
    return null;
}

/**
 * validateCommaSeparated - Controleer komma-gescheiden waarden
 *
 * Controleert of er geen lege items tussen de komma's staan.
 * Voorbeeld goed: "speler1, speler2"
 * Voorbeeld fout: "speler1, , speler2"
 *
 * @param string $waarde    De komma-gescheiden tekst
 * @param string $veldnaam  Naam voor de foutmelding
 * @return string|null      Foutmelding of null als alles goed is
 */
function validateCommaSeparated($waarde, $veldnaam)
{
    if (empty($waarde))
        return null;

    // Splits op komma en controleer elk item
    $items = explode(',', $waarde);
    foreach ($items as $item) {
        if (empty(trim($item))) {
            return "$veldnaam bevat lege items.";
        }
    }
    return null;
}


// ==========================================================================
// SECTIE 2: SESSIE BERICHTEN
// ==========================================================================

/**
 * setMessage - Sla een bericht op in de sessie om op de volgende pagina te tonen
 *
 * Wordt gebruikt na een actie (bijv. "Vriend toegevoegd!") om de gebruiker
 * een bevestiging te tonen na een redirect.
 *
 * @param string $type  Type bericht: 'success' (groen) of 'danger' (rood)
 * @param string $tekst De tekst van het bericht
 */
function setMessage($type, $tekst)
{
    $_SESSION['message'] = ['type' => $type, 'msg' => $tekst];
}

/**
 * getMessage - Haal het sessie bericht op en toon het als HTML
 *
 * Het bericht wordt na het ophalen verwijderd uit de sessie,
 * zodat het maar een keer getoond wordt.
 *
 * @return string HTML code van het bericht, of lege string
 */
function getMessage()
{
    if (isset($_SESSION['message'])) {
        $msg = $_SESSION['message'];
        unset($_SESSION['message']); // Verwijder na ophalen
        return "<div class='alert alert-{$msg['type']} alert-dismissible fade show' role='alert'>
                    {$msg['msg']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    }
    return '';
}


// ==========================================================================
// SECTIE 3: AUTHENTICATIE FUNCTIES (INLOGGEN / REGISTREREN)
// ==========================================================================

/**
 * isLoggedIn - Controleer of de gebruiker ingelogd is
 *
 * @return bool true als ingelogd, false als niet
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * getUserId - Haal het ID van de ingelogde gebruiker op
 *
 * @return int Het gebruiker ID, of 0 als niet ingelogd
 */
function getUserId()
{
    return isLoggedIn() ? (int) $_SESSION['user_id'] : 0;
}

/**
 * updateLastActivity - Werk de laatste activiteit bij in de database
 *
 * @param PDO $pdo      Database verbinding
 * @param int $userId   ID van de gebruiker
 */
function updateLastActivity($pdo, $userId)
{
    $stmt = $pdo->prepare(
        "UPDATE Users SET last_activity = CURRENT_TIMESTAMP
         WHERE user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId]);
}

/**
 * checkSessionTimeout - Controleer of de sessie verlopen is
 *
 * Na 30 minuten zonder activiteit wordt de sessie automatisch beeindigd.
 * Dit is een beveiligingsmaatregel: als iemand vergeet uit te loggen,
 * wordt de sessie na 30 minuten automatisch afgesloten.
 */
function checkSessionTimeout()
{
    // 1800 seconden = 30 minuten
    if (isLoggedIn() && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        header("Location: login.php?msg=sessie_verlopen");
        exit;
    }
    $_SESSION['last_activity'] = time();
}

/**
 * registerUser - Registreer een nieuw gebruikersaccount
 *
 * Stappen:
 * 1. Controleer of alle velden correct zijn ingevuld
 * 2. Controleer of het e-mailadres nog niet bestaat
 * 3. Versleutel het wachtwoord met bcrypt
 * 4. Sla de nieuwe gebruiker op in de database
 *
 * @param string $username   Gekozen gebruikersnaam
 * @param string $email      E-mailadres
 * @param string $password   Gekozen wachtwoord
 * @return string|null       Foutmelding of null bij succes
 */
function registerUser($username, $email, $password)
{
    $pdo = getDBConnection();

    // Controleer alle invoer
    if ($err = validateRequired($username, "Gebruikersnaam", 50))
        return $err;
    if ($err = validateEmail($email))
        return $err;
    if ($err = validateRequired($password, "Wachtwoord"))
        return $err;
    if (strlen($password) < 8)
        return "Wachtwoord moet minimaal 8 tekens zijn.";

    // Controleer of e-mail al bestaat in de database
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL"
    );
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0)
        return "Dit e-mailadres is al geregistreerd.";

    // Versleutel het wachtwoord met bcrypt (veilig en niet terug te draaien)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Sla de nieuwe gebruiker op in de database
    $stmt = $pdo->prepare(
        "INSERT INTO Users (username, email, password_hash)
         VALUES (:username, :email, :hash)"
    );
    try {
        $stmt->execute(['username' => $username, 'email' => $email, 'hash' => $hash]);
        return null; // Succes
    } catch (PDOException $e) {
        error_log("Registratie mislukt: " . $e->getMessage());
        return "Registratie mislukt. Probeer het opnieuw.";
    }
}

/**
 * loginUser - Log een gebruiker in met e-mail en wachtwoord
 *
 * Stappen:
 * 1. Controleer of de velden ingevuld zijn
 * 2. Zoek de gebruiker op basis van e-mailadres
 * 3. Controleer het wachtwoord met password_verify
 * 4. Start een sessie voor de gebruiker
 *
 * @param string $email     E-mailadres
 * @param string $password  Wachtwoord
 * @return string|null      Foutmelding of null bij succes
 */
function loginUser($email, $password)
{
    $pdo = getDBConnection();

    // Controleer invoer
    if ($err = validateRequired($email, "E-mail"))
        return $err;
    if ($err = validateRequired($password, "Wachtwoord"))
        return $err;

    // Zoek gebruiker op e-mailadres
    $stmt = $pdo->prepare(
        "SELECT user_id, username, password_hash
         FROM Users WHERE email = :email AND deleted_at IS NULL"
    );
    $stmt->execute(['email' => $email]);
    $gebruiker = $stmt->fetch();

    // Controleer wachtwoord (password_verify vergelijkt met de hash)
    if (!$gebruiker || !password_verify($password, $gebruiker['password_hash'])) {
        return "Ongeldige e-mail of wachtwoord.";
    }

    // Sla gebruiker gegevens op in de sessie
    $_SESSION['user_id'] = $gebruiker['user_id'];
    $_SESSION['username'] = $gebruiker['username'];

    // Genereer een nieuw sessie-ID (beschermt tegen session hijacking)
    session_regenerate_id(true);

    // Werk de laatste activiteit bij
    updateLastActivity($pdo, $gebruiker['user_id']);

    return null; // Succes
}

/**
 * logout - Log de gebruiker uit door de sessie te vernietigen
 */
function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}


// ==========================================================================
// SECTIE 4: SPEL FUNCTIES (FAVORIETE GAMES)
// ==========================================================================

/**
 * getOrCreateGameId - Zoek een spel op titel, of maak het aan als het niet bestaat
 *
 * @param PDO    $pdo          Database verbinding
 * @param string $titel        Naam van het spel
 * @param string $beschrijving Optionele beschrijving
 * @return int                 Het game_id van het spel
 */
function getOrCreateGameId($pdo, $titel, $beschrijving = '')
{
    $titel = trim($titel);
    if (empty($titel))
        return 0;

    // Zoek het spel (niet hoofdlettergevoelig)
    $stmt = $pdo->prepare(
        "SELECT game_id FROM Games
         WHERE LOWER(titel) = LOWER(:titel) AND deleted_at IS NULL"
    );
    $stmt->execute(['titel' => $titel]);
    $rij = $stmt->fetch();

    // Als het spel al bestaat, geef het ID terug
    if ($rij)
        return $rij['game_id'];

    // Anders: maak een nieuw spel aan
    $stmt = $pdo->prepare(
        "INSERT INTO Games (titel, description) VALUES (:titel, :beschrijving)"
    );
    $stmt->execute(['titel' => $titel, 'beschrijving' => $beschrijving]);

    return $pdo->lastInsertId();
}

/**
 * addFavoriteGame - Voeg een spel toe aan de favorieten
 *
 * @param int    $userId       Gebruiker ID
 * @param string $titel        Naam van het spel
 * @param string $beschrijving Beschrijving
 * @param string $notitie      Persoonlijke notitie
 * @return string|null         Foutmelding of null bij succes
 */
function addFavoriteGame($userId, $titel, $beschrijving = '', $notitie = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($titel, "Speltitel", 100))
        return $err;

    $gameId = getOrCreateGameId($pdo, $titel, $beschrijving);

    // Controleer of het spel al in de favorieten staat
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM UserGames
         WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() > 0)
        return "Dit spel staat al in je favorieten.";

    // Voeg toe
    $stmt = $pdo->prepare(
        "INSERT INTO UserGames (user_id, game_id, note)
         VALUES (:user_id, :game_id, :notitie)"
    );
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId, 'notitie' => $notitie]);
    return null;
}

/**
 * updateFavoriteGame - Werk een favoriet spel bij
 */
function updateFavoriteGame($userId, $gameId, $titel, $beschrijving, $notitie)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($titel, "Speltitel", 100))
        return $err;

    // Controleer eigenaarschap
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM UserGames
         WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    if ($stmt->fetchColumn() == 0)
        return "Geen toestemming om te bewerken.";

    // Werk spelgegevens bij
    $stmt = $pdo->prepare(
        "UPDATE Games SET titel = :titel, description = :beschrijving
         WHERE game_id = :game_id AND deleted_at IS NULL"
    );
    $stmt->execute(['titel' => $titel, 'beschrijving' => $beschrijving, 'game_id' => $gameId]);

    // Werk notitie bij
    $stmt = $pdo->prepare(
        "UPDATE UserGames SET note = :notitie
         WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['notitie' => $notitie, 'user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * deleteFavoriteGame - Verwijder een spel uit de favorieten
 */
function deleteFavoriteGame($userId, $gameId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare(
        "DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    return null;
}

/**
 * getFavoriteGames - Haal alle favoriete spellen van een gebruiker op
 */
function getFavoriteGames($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare(
        "SELECT g.game_id, g.titel, g.description, ug.note
         FROM UserGames ug
         JOIN Games g ON ug.game_id = g.game_id
         WHERE ug.user_id = :user_id AND g.deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * getGames - Haal alle beschikbare spellen op
 */
function getGames()
{
    $pdo = getDBConnection();
    $stmt = $pdo->query(
        "SELECT game_id, titel, description
         FROM Games WHERE deleted_at IS NULL ORDER BY titel"
    );
    return $stmt->fetchAll();
}


// ==========================================================================
// SECTIE 5: VRIENDEN FUNCTIES
// ==========================================================================

/**
 * addFriend - Voeg een gaming vriend toe
 */
function addFriend($userId, $vriendUsername, $notitie = '', $status = 'Offline')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($vriendUsername, "Gebruikersnaam vriend", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Controleer of deze vriend al toegevoegd is
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM Friends
         WHERE user_id = :user_id
         AND LOWER(friend_username) = LOWER(:vriend)
         AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId, 'vriend' => $vriendUsername]);
    if ($stmt->fetchColumn() > 0)
        return "Deze vriend is al toegevoegd.";

    // Voeg de vriend toe
    $stmt = $pdo->prepare(
        "INSERT INTO Friends (user_id, friend_username, note, status)
         VALUES (:user_id, :vriend, :notitie, :status)"
    );
    $stmt->execute([
        'user_id' => $userId,
        'vriend' => $vriendUsername,
        'notitie' => $notitie,
        'status' => $status,
    ]);
    return null;
}

/**
 * updateFriend - Werk de gegevens van een vriend bij
 */
function updateFriend($userId, $friendId, $vriendUsername, $notitie, $status)
{
    $pdo = getDBConnection();

    if ($err = validateRequired($vriendUsername, "Gebruikersnaam vriend", 50))
        return $err;
    if ($err = validateRequired($status, "Status", 50))
        return $err;

    // Controleer eigenaarschap
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM Friends
         WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    if ($stmt->fetchColumn() == 0)
        return "Geen toestemming om te bewerken.";

    // Werk bij
    $stmt = $pdo->prepare(
        "UPDATE Friends SET friend_username = :vriend, note = :notitie, status = :status
         WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL"
    );
    $stmt->execute([
        'vriend' => $vriendUsername,
        'notitie' => $notitie,
        'status' => $status,
        'user_id' => $userId,
        'friend_id' => $friendId,
    ]);
    return null;
}

/**
 * deleteFriend - Verwijder een vriend (soft delete)
 */
function deleteFriend($userId, $friendId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare(
        "UPDATE Friends SET deleted_at = NOW()
         WHERE user_id = :user_id AND friend_id = :friend_id"
    );
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    return null;
}

/**
 * getFriends - Haal alle vrienden van een gebruiker op
 */
function getFriends($userId)
{
    $pdo = getDBConnection();
    $stmt = $pdo->prepare(
        "SELECT friend_id, friend_username AS username, status, note
         FROM Friends
         WHERE user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}


// ==========================================================================
// SECTIE 6: SPEELSCHEMA FUNCTIES
// ==========================================================================

/**
 * addSchedule - Voeg een nieuw speelschema toe
 */
function addSchedule($userId, $spelTitel, $datum, $tijd, $vrienden = '', $gedeeldMet = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($spelTitel, "Speltitel", 100))
        return $err;
    if ($err = validateDate($datum))
        return $err;
    if ($err = validateTime($tijd))
        return $err;
    if ($err = validateCommaSeparated($vrienden, "Vrienden"))
        return $err;
    if ($err = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $err;

    $gameId = getOrCreateGameId($pdo, $spelTitel);

    $stmt = $pdo->prepare(
        "INSERT INTO Schedules (user_id, game_id, date, time, friends, shared_with)
         VALUES (:user_id, :game_id, :datum, :tijd, :vrienden, :gedeeld)"
    );
    $stmt->execute([
        'user_id' => $userId,
        'game_id' => $gameId,
        'datum' => $datum,
        'tijd' => $tijd,
        'vrienden' => $vrienden,
        'gedeeld' => $gedeeldMet,
    ]);
    return null;
}

/**
 * getSchedules - Haal alle speelschema's van een gebruiker op
 */
function getSchedules($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();

    // Sta alleen veilige sorteer opties toe (beschermt tegen SQL-injectie)
    $toegestaan = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $toegestaan) ? $sort : 'date ASC';

    $stmt = $pdo->prepare(
        "SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with
         FROM Schedules s
         JOIN Games g ON s.game_id = g.game_id
         WHERE s.user_id = :user_id AND s.deleted_at IS NULL
         ORDER BY $sort LIMIT 50"
    );
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * editSchedule - Werk een bestaand speelschema bij
 */
function editSchedule($userId, $schemaId, $spelTitel, $datum, $tijd, $vrienden = '', $gedeeldMet = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $schemaId, $userId)) {
        return "Geen toestemming om te bewerken.";
    }
    if ($err = validateRequired($spelTitel, "Speltitel", 100))
        return $err;
    if ($err = validateDate($datum))
        return $err;
    if ($err = validateTime($tijd))
        return $err;
    if ($err = validateCommaSeparated($vrienden, "Vrienden"))
        return $err;
    if ($err = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $err;

    $gameId = getOrCreateGameId($pdo, $spelTitel);

    $stmt = $pdo->prepare(
        "UPDATE Schedules
         SET game_id = :game_id, date = :datum, time = :tijd,
             friends = :vrienden, shared_with = :gedeeld
         WHERE schedule_id = :id AND user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute([
        'game_id' => $gameId,
        'datum' => $datum,
        'tijd' => $tijd,
        'vrienden' => $vrienden,
        'gedeeld' => $gedeeldMet,
        'id' => $schemaId,
        'user_id' => $userId,
    ]);
    return null;
}

/**
 * deleteSchedule - Verwijder een speelschema (soft delete)
 */
function deleteSchedule($userId, $schemaId)
{
    $pdo = getDBConnection();
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $schemaId, $userId)) {
        return "Geen toestemming om te verwijderen.";
    }
    $stmt = $pdo->prepare(
        "UPDATE Schedules SET deleted_at = NOW()
         WHERE schedule_id = :id AND user_id = :user_id"
    );
    $stmt->execute(['id' => $schemaId, 'user_id' => $userId]);
    return null;
}


// ==========================================================================
// SECTIE 7: EVENEMENT FUNCTIES
// ==========================================================================

/**
 * addEvent - Voeg een nieuw evenement toe (toernooi, stream, etc.)
 */
function addEvent($userId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink = '', $gedeeldMet = '')
{
    $pdo = getDBConnection();

    if ($err = validateRequired($titel, "Titel", 100))
        return $err;
    if ($err = validateDate($datum))
        return $err;
    if ($err = validateTime($tijd))
        return $err;
    if (!empty($beschrijving) && strlen($beschrijving) > 500) {
        return "Beschrijving is te lang (maximaal 500 tekens).";
    }
    if (!in_array($herinnering, ['none', '1_hour', '1_day'])) {
        return "Ongeldige herinnering keuze.";
    }
    if ($err = validateUrl($externeLink))
        return $err;
    if ($err = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $err;

    $stmt = $pdo->prepare(
        "INSERT INTO Events (user_id, title, date, time, description, reminder, external_link, shared_with)
         VALUES (:user_id, :titel, :datum, :tijd, :beschrijving, :herinnering, :link, :gedeeld)"
    );
    $stmt->execute([
        'user_id' => $userId,
        'titel' => $titel,
        'datum' => $datum,
        'tijd' => $tijd,
        'beschrijving' => $beschrijving,
        'herinnering' => $herinnering,
        'link' => $externeLink,
        'gedeeld' => $gedeeldMet,
    ]);
    return null;
}

/**
 * getEvents - Haal alle evenementen van een gebruiker op
 */
function getEvents($userId, $sort = 'date ASC')
{
    $pdo = getDBConnection();

    $toegestaan = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $toegestaan) ? $sort : 'date ASC';

    $stmt = $pdo->prepare(
        "SELECT event_id, title, date, time, description, reminder, external_link, shared_with
         FROM Events
         WHERE user_id = :user_id AND deleted_at IS NULL
         ORDER BY $sort LIMIT 50"
    );
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

/**
 * editEvent - Werk een bestaand evenement bij
 */
function editEvent($userId, $eventId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink = '', $gedeeldMet = '')
{
    $pdo = getDBConnection();

    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "Geen toestemming om te bewerken.";
    }
    if ($err = validateRequired($titel, "Titel", 100))
        return $err;
    if ($err = validateDate($datum))
        return $err;
    if ($err = validateTime($tijd))
        return $err;
    if (!empty($beschrijving) && strlen($beschrijving) > 500) {
        return "Beschrijving is te lang (maximaal 500 tekens).";
    }
    if (!in_array($herinnering, ['none', '1_hour', '1_day'])) {
        return "Ongeldige herinnering keuze.";
    }
    if ($err = validateUrl($externeLink))
        return $err;
    if ($err = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $err;

    $stmt = $pdo->prepare(
        "UPDATE Events
         SET title = :titel, date = :datum, time = :tijd, description = :beschrijving,
             reminder = :herinnering, external_link = :link, shared_with = :gedeeld
         WHERE event_id = :id AND user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute([
        'titel' => $titel,
        'datum' => $datum,
        'tijd' => $tijd,
        'beschrijving' => $beschrijving,
        'herinnering' => $herinnering,
        'link' => $externeLink,
        'gedeeld' => $gedeeldMet,
        'id' => $eventId,
        'user_id' => $userId,
    ]);
    return null;
}

/**
 * deleteEvent - Verwijder een evenement (soft delete)
 */
function deleteEvent($userId, $eventId)
{
    $pdo = getDBConnection();
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "Geen toestemming om te verwijderen.";
    }
    $stmt = $pdo->prepare(
        "UPDATE Events SET deleted_at = NOW()
         WHERE event_id = :id AND user_id = :user_id"
    );
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);
    return null;
}


// ==========================================================================
// SECTIE 8: HULPFUNCTIES
// ==========================================================================

/**
 * checkOwnership - Controleer of een item van de ingelogde gebruiker is
 *
 * Dit is een beveiliging: gebruikers mogen alleen hun eigen data bewerken.
 *
 * @param PDO    $pdo      Database verbinding
 * @param string $tabel    Naam van de tabel
 * @param string $idKolom  Naam van de ID kolom
 * @param int    $id       ID van het item
 * @param int    $userId   ID van de gebruiker
 * @return bool            true als eigenaar, false als niet
 */
function checkOwnership($pdo, $tabel, $idKolom, $id, $userId)
{
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM $tabel
         WHERE $idKolom = :id AND user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    return $stmt->fetchColumn() > 0;
}

/**
 * getCalendarItems - Haal alle items op voor de kalender
 *
 * Combineert speelschema's en evenementen, gesorteerd op datum en tijd.
 */
function getCalendarItems($userId)
{
    $schemas = getSchedules($userId);
    $evenementen = getEvents($userId);
    $items = array_merge($schemas, $evenementen);

    // Sorteer op datum en tijd
    usort($items, function ($a, $b) {
        return strtotime($a['date'] . ' ' . $a['time']) <=> strtotime($b['date'] . ' ' . $b['time']);
    });

    return $items;
}

/**
 * getReminders - Haal actieve herinneringen op
 *
 * Controleert welke evenementen een herinnering hebben die nu
 * getoond moet worden (binnen 1 minuut van het herinneringsmoment).
 */
function getReminders($userId)
{
    $evenementen = getEvents($userId);
    $herinneringen = [];

    foreach ($evenementen as $event) {
        if ($event['reminder'] == 'none')
            continue;

        $eventTijd = strtotime($event['date'] . ' ' . $event['time']);
        // 1 uur = 3600 seconden, 1 dag = 86400 seconden
        $herinneringTijd = $eventTijd - ($event['reminder'] == '1_hour' ? 3600 : 86400);

        if ($herinneringTijd <= time() && $herinneringTijd > time() - 60) {
            $herinneringen[] = $event;
        }
    }

    return $herinneringen;
}

// ==========================================================================
// EINDE VAN FUNCTIONS.PHP
// ==========================================================================
