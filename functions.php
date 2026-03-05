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
 * @param string $emailAdres  E-mailadres om te controleren
 * @return string|null   Foutmelding of null als alles goed is
 */
function validateEmail($emailAdres)
{
    // PHP filter_var controleert het e-mail formaat automatisch
    if (!filter_var($emailAdres, FILTER_VALIDATE_EMAIL)) {
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

    // Splits op komma en controleer elk deel
    $delen = explode(',', $waarde);
    foreach ($delen as $deel) {
        if (empty(trim($deel))) {
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
 * Na 30 minuten zonder activiteit wordt de sessie automatisch beëindigd.
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
 * @param string $gebruikersnaam   Gekozen gebruikersnaam
 * @param string $emailAdres       E-mailadres
 * @param string $wachtwoord       Gekozen wachtwoord
 * @return string|null             Foutmelding of null bij succes
 */
function registerUser($gebruikersnaam, $emailAdres, $wachtwoord)
{
    $pdo = getDBConnection();

    // Controleer alle invoer
    if ($fout = validateRequired($gebruikersnaam, "Gebruikersnaam", 50))
        return $fout;
    if ($fout = validateEmail($emailAdres))
        return $fout;
    if ($fout = validateRequired($wachtwoord, "Wachtwoord"))
        return $fout;
    if (strlen($wachtwoord) < 8)
        return "Wachtwoord moet minimaal 8 tekens zijn.";

    // Controleer of e-mail al bestaat in de database
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM Users WHERE email = :email AND deleted_at IS NULL"
    );
    $stmt->execute(['email' => $emailAdres]);
    if ($stmt->fetchColumn() > 0)
        return "Dit e-mailadres is al geregistreerd.";

    // Versleutel het wachtwoord met bcrypt (veilig en niet terug te draaien)
    $hashWachtwoord = password_hash($wachtwoord, PASSWORD_BCRYPT);

    // Sla de nieuwe gebruiker op in de database
    $stmt = $pdo->prepare(
        "INSERT INTO Users (username, email, password_hash)
         VALUES (:username, :email, :hash)"
    );
    try {
        $stmt->execute(['username' => $gebruikersnaam, 'email' => $emailAdres, 'hash' => $hashWachtwoord]);
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
 * @param string $emailAdres   E-mailadres
 * @param string $wachtwoord   Wachtwoord
 * @return string|null         Foutmelding of null bij succes
 */
function loginUser($emailAdres, $wachtwoord)
{
    $pdo = getDBConnection();

    // Controleer invoer
    if ($fout = validateRequired($emailAdres, "E-mail"))
        return $fout;
    if ($fout = validateRequired($wachtwoord, "Wachtwoord"))
        return $fout;

    // Zoek gebruiker op e-mailadres
    $stmt = $pdo->prepare(
        "SELECT user_id, username, password_hash
         FROM Users WHERE email = :email AND deleted_at IS NULL"
    );
    $stmt->execute(['email' => $emailAdres]);
    $gebruiker = $stmt->fetch();

    // Controleer wachtwoord (password_verify vergelijkt met de hash)
    if (!$gebruiker || !password_verify($wachtwoord, $gebruiker['password_hash'])) {
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

    if ($fout = validateRequired($titel, "Speltitel", 100))
        return $fout;

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
 * updateFavoriteGame - Werk een bestaand favoriet spel bij in de database
 *
 * Deze functie wordt aangeroepen vanuit edit_favorite.php wanneer de gebruiker
 * de gegevens van een favoriet spel wil wijzigen. Het werkt twee tabellen bij:
 * 1. De Games tabel (voor titel en beschrijving)
 * 2. De UserGames tabel (voor de persoonlijke notitie)
 *
 * BEVEILIGING: voordat er iets wordt gewijzigd, controleren we of de gebruiker
 * daadwerkelijk de eigenaar is van dit favoriete spel. Zonder deze controle zou
 * een kwaadwillende gebruiker via URL-manipulatie andermans spellen kunnen bewerken.
 *
 * @param int    $userId       Het ID van de ingelogde gebruiker (om eigenaarschap te controleren)
 * @param int    $gameId       Het ID van het spel dat bewerkt wordt (uit de URL parameter)
 * @param string $titel        De (eventueel gewijzigde) titulo van het spel
 * @param string $beschrijving De (eventueel gewijzigde) beschrijving van het spel
 * @param string $notitie      De (eventueel gewijzigde) persoonlijke notitie
 * @return string|null         Een foutmelding als string, of null als alles goed is gegaan
 */
function updateFavoriteGame($userId, $gameId, $titel, $beschrijving, $notitie)
{
    // Haal de database verbinding op via de singleton functie in db.php
    $pdo = getDBConnection();

    // Valideer dat de titel is ingevuld en niet langer is dan 100 tekens
    // validateRequired() retourneert een foutmelding als de validatie faalt, of null als het goed is
    // Door het resultaat toe te wijzen aan $fout EN tegelijk te controleren ($fout = validateRequired(...)),
    // kunnen we in een regel zowel valideren als de fout opslaan
    if ($fout = validateRequired($titel, "Speltitel", 100))
        return $fout;

    // EIGENAARSCHAP CONTROLE: controleer of dit spel daadwerkelijk in de favorieten
    // van DEZE gebruiker staat. We tellen het aantal rijen in de UserGames tabel
    // waar zowel het user_id als het game_id overeenkomen.
    // COUNT(*) retourneert een getal: 0 als het spel niet bij de gebruiker hoort, of 1+ als wel.
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM UserGames
         WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);
    // fetchColumn() haalt de eerste kolom op van het resultaat (= het getal van COUNT(*))
    // Als het 0 is, heeft de gebruiker geen recht om dit spel te bewerken
    if ($stmt->fetchColumn() == 0)
        return "Geen toestemming om te bewerken.";

    // STAP 1: Werk de spelgegevens bij in de Games tabel
    // UPDATE wijzigt bestaande rijen in de tabel
    // SET titel = :titel zet de nieuwe titel, SET description = :beschrijving de nieuwe beschrijving
    // WHERE game_id = :game_id zorgt dat alleen HET JUISTE spel wordt bijgewerkt
    // AND deleted_at IS NULL zorgt dat verwijderde spellen niet worden bijgewerkt
    $stmt = $pdo->prepare(
        "UPDATE Games SET titel = :titel, description = :beschrijving
         WHERE game_id = :game_id AND deleted_at IS NULL"
    );
    $stmt->execute(['titel' => $titel, 'beschrijving' => $beschrijving, 'game_id' => $gameId]);

    // STAP 2: Werk de persoonlijke notitie bij in de UserGames koppeltabel
    // De notitie staat in UserGames (niet in Games) omdat elke gebruiker een EIGEN
    // notitie kan hebben bij hetzelfde spel. De Games tabel bevat algemene info,
    // de UserGames tabel bevat gebruiker-specifieke info.
    $stmt = $pdo->prepare(
        "UPDATE UserGames SET note = :notitie
         WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['notitie' => $notitie, 'user_id' => $userId, 'game_id' => $gameId]);

    // Retourneer null om aan te geven dat alles goed is gegaan (geen foutmelding)
    return null;
}

/**
 * deleteFavoriteGame - Verwijder een spel uit de favorieten van de gebruiker
 *
 * LET OP: dit is een HARDE verwijdering (DELETE), geen soft delete.
 * De rij wordt permanent verwijderd uit de UserGames koppeltabel.
 * Het spel zelf blijft bestaan in de Games tabel (voor andere gebruikers).
 *
 * VERSCHIL MET SOFT DELETE:
 * - Soft delete = de rij blijft bestaan maar krijgt een deleted_at datum
 *   (gebruikt bij Friends, Events, Schedules)
 * - Harde delete = de rij wordt PERMANENT verwijderd uit de database
 *   (gebruikt hier bij UserGames, omdat favorieten eenvoudig opnieuw
 *   kunnen worden toegevoegd)
 *
 * @param int $userId  Het ID van de ingelogde gebruiker
 * @param int $gameId  Het ID van het spel dat uit de favorieten wordt verwijderd
 * @return null        Retourneert altijd null (geen foutmelding)
 */
function deleteFavoriteGame($userId, $gameId)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // DELETE FROM verwijdert de rij permanent uit de UserGames koppeltabel
    // WHERE user_id = :user_id AND game_id = :game_id zorgt ervoor dat we ALLEEN
    // de koppeling van DEZE gebruiker met DIT spel verwijderen, niet die van andere gebruikers
    // Prepared statements (:user_id, :game_id) beschermen tegen SQL-injectie
    $stmt = $pdo->prepare(
        "DELETE FROM UserGames WHERE user_id = :user_id AND game_id = :game_id"
    );
    $stmt->execute(['user_id' => $userId, 'game_id' => $gameId]);

    // Retourneer null om aan te geven dat de actie is geslaagd
    return null;
}

/**
 * getFavoriteGames - Haal alle favoriete spellen van een gebruiker op
 *
 * Deze functie haalt een lijst op van alle spellen die de gebruiker als favoriet
 * heeft gemarkeerd. Het gebruikt een JOIN query om gegevens uit twee tabellen
 * (UserGames en Games) te combineren.
 *
 * HOE DE JOIN WERKT:
 * - UserGames bevat de koppeling: welk user_id hoort bij welk game_id (+ notitie)
 * - Games bevat de spelgegevens: titel en beschrijving
 * - JOIN combineert deze twee tabellen op basis van het gemeenschappelijke game_id
 *
 * @param int $userId  Het ID van de ingelogde gebruiker
 * @return array       Een array van associatieve arrays met game_id, titel, description, note
 */
function getFavoriteGames($userId)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Bouw de SELECT query met een JOIN tussen UserGames (ug) en Games (g)
    // g.game_id, g.titel, g.description komen uit de Games tabel
    // ug.note komt uit de UserGames koppeltabel (persoonlijke notitie van de gebruiker)
    // JOIN Games g ON ug.game_id = g.game_id koppelt de twee tabellen op game_id
    // WHERE ug.user_id = :user_id filtert op alleen de spellen van DEZE gebruiker
    // AND g.deleted_at IS NULL sluit soft-deleted spellen uit
    $stmt = $pdo->prepare(
        "SELECT g.game_id, g.titel, g.description, ug.note
         FROM UserGames ug
         JOIN Games g ON ug.game_id = g.game_id
         WHERE ug.user_id = :user_id AND g.deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId]);

    // fetchAll() haalt ALLE resultaatrijen op als een array van associatieve arrays
    // Elk element in de array is een spel met keys: game_id, titel, description, note
    return $stmt->fetchAll();
}

/**
 * getGames - Haal alle beschikbare spellen op uit de database
 *
 * Deze functie haalt een gesorteerde lijst op van ALLE spellen in de Games tabel,
 * ongeacht welke gebruiker ze heeft aangemaakt. Dit wordt bijvoorbeeld gebruikt
 * om een keuzelijst te tonen met beschikbare spellen.
 *
 * VERSCHIL MET getFavoriteGames():
 * - getFavoriteGames() haalt alleen de favorieten van EEN specifieke gebruiker op
 * - getGames() haalt ALLE spellen op die in de database staan
 *
 * @return array  Een array van associatieve arrays met game_id, titel, description
 */
function getGames()
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // $pdo->query() wordt gebruikt in plaats van prepare() omdat er geen
    // gebruikersinvoer in de query zit (geen parameters). Dit is veilig omdat
    // de query volledig hardcoded is en niet beïnvloed kan worden door gebruikers.
    // ORDER BY titel sorteert de resultaten alfabetisch op speltitel
    // deleted_at IS NULL sluit soft-deleted spellen uit
    $stmt = $pdo->query(
        "SELECT game_id, titel, description
         FROM Games WHERE deleted_at IS NULL ORDER BY titel"
    );

    // Geef alle spellen terug als een array
    return $stmt->fetchAll();
}


// ==========================================================================
// SECTIE 5: VRIENDEN FUNCTIES
// ==========================================================================

/**
 * addFriend - Voeg een gaming vriend toe aan de vriendenlijst
 *
 * Deze functie voegt een nieuwe vriend toe aan de Friends tabel.
 * De vriend wordt opgeslagen met een gebruikersnaam, optionele notitie
 * en een online-status (standaard 'Offline').
 *
 * DUPLICAAT CONTROLE: voordat de vriend wordt toegevoegd, controleren we
 * of deze gebruikersnaam al in de vriendenlijst staat. Dit voorkomt
 * dat dezelfde vriend twee keer wordt toegevoegd. De vergelijking is
 * niet hoofdlettergevoelig (LOWER()) zodat "Jan" en "jan" als dezelfde
 * vriend worden beschouwd.
 *
 * @param int    $userId                Het ID van de ingelogde gebruiker
 * @param string $vriendGebruikersnaam  De gebruikersnaam van de vriend
 * @param string $notitie               Optionele persoonlijke notitie over de vriend
 * @param string $status                Online-status: 'Online', 'Offline', of 'In Game'
 * @return string|null                  Foutmelding of null bij succes
 */
function addFriend($userId, $vriendGebruikersnaam, $notitie = '', $status = 'Offline')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Valideer de gebruikersnaam: mag niet leeg zijn en maximaal 50 tekens
    if ($fout = validateRequired($vriendGebruikersnaam, "Gebruikersnaam vriend", 50))
        return $fout;
    // Valideer de status: mag niet leeg zijn en maximaal 50 tekens
    if ($fout = validateRequired($status, "Status", 50))
        return $fout;

    // DUPLICAAT CONTROLE: controleer of deze vriend al in de lijst staat
    // LOWER() maakt de vergelijking niet hoofdlettergevoelig
    // deleted_at IS NULL zorgt ervoor dat verwijderde vrienden opnieuw kunnen worden toegevoegd
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM Friends
         WHERE user_id = :user_id
         AND LOWER(friend_username) = LOWER(:vriend)
         AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId, 'vriend' => $vriendGebruikersnaam]);
    // Als COUNT(*) groter is dan 0, bestaat de vriend al in de lijst
    if ($stmt->fetchColumn() > 0)
        return "Deze vriend is al toegevoegd.";

    // Voeg de nieuwe vriend toe aan de Friends tabel
    // INSERT INTO voegt een nieuwe rij toe met de opgegeven waarden
    $stmt = $pdo->prepare(
        "INSERT INTO Friends (user_id, friend_username, note, status)
         VALUES (:user_id, :vriend, :notitie, :status)"
    );
    $stmt->execute([
        'user_id' => $userId,
        'vriend' => $vriendGebruikersnaam,
        'notitie' => $notitie,
        'status' => $status,
    ]);

    // Retourneer null om aan te geven dat het toevoegen is gelukt
    return null;
}

/**
 * updateFriend - Werk de gegevens van een bestaande vriend bij
 *
 * Deze functie wordt aangeroepen vanuit edit_friend.php wanneer de gebruiker
 * de gegevens van een vriend wil wijzigen (gebruikersnaam, notitie of status).
 *
 * BEVEILIGING: voordat de wijziging wordt doorgevoerd, controleren we of
 * de vriend daadwerkelijk bij DEZE gebruiker hoort. Dit voorkomt dat
 * een gebruiker via URL-manipulatie andermans vrienden kan bewerken.
 *
 * @param int    $userId                Het ID van de ingelogde gebruiker
 * @param int    $friendId              Het ID van de vriend die bewerkt wordt
 * @param string $vriendGebruikersnaam  De (eventueel gewijzigde) gebruikersnaam
 * @param string $notitie               De (eventueel gewijzigde) notitie
 * @param string $status                De (eventueel gewijzigde) status
 * @return string|null                  Foutmelding of null bij succes
 */
function updateFriend($userId, $friendId, $vriendGebruikersnaam, $notitie, $status)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Valideer de invoervelden
    if ($fout = validateRequired($vriendGebruikersnaam, "Gebruikersnaam vriend", 50))
        return $fout;
    if ($fout = validateRequired($status, "Status", 50))
        return $fout;

    // EIGENAARSCHAP CONTROLE: controleer of deze vriend bij de ingelogde gebruiker hoort
    // We tellen het aantal rijen waar zowel user_id als friend_id overeenkomen
    // AND deleted_at IS NULL zorgt dat verwijderde vrienden niet bewerkt kunnen worden
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM Friends
         WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);
    // Als COUNT(*) gelijk is aan 0, heeft de gebruiker geen toegang tot deze vriend
    if ($stmt->fetchColumn() == 0)
        return "Geen toestemming om te bewerken.";

    // Werk de vriendgegevens bij met UPDATE
    // SET wijzigt de kolommen friend_username, note en status
    // WHERE zorgt ervoor dat alleen de juiste rij wordt bijgewerkt
    $stmt = $pdo->prepare(
        "UPDATE Friends SET friend_username = :vriend, note = :notitie, status = :status
         WHERE user_id = :user_id AND friend_id = :friend_id AND deleted_at IS NULL"
    );
    $stmt->execute([
        'vriend' => $vriendGebruikersnaam,
        'notitie' => $notitie,
        'status' => $status,
        'user_id' => $userId,
        'friend_id' => $friendId,
    ]);

    // Retourneer null om aan te geven dat de wijziging is gelukt
    return null;
}

/**
 * deleteFriend - Verwijder een vriend uit de vriendenlijst (soft delete)
 *
 * Dit is een SOFT DELETE: de rij wordt niet echt verwijderd uit de database,
 * maar krijgt een deleted_at tijdstempel. Hierdoor:
 * - Kan de verwijdering eventueel ongedaan worden gemaakt
 * - Blijft er een historisch overzicht van eerdere vriendschappen
 * - Worden de gegevens niet permanent verloren
 *
 * De vriend verschijnt niet meer in de vriendenlijst omdat alle queries
 * filteren op "deleted_at IS NULL".
 *
 * @param int $userId    Het ID van de ingelogde gebruiker
 * @param int $friendId  Het ID van de vriend die verwijderd wordt
 * @return null          Retourneert altijd null (geen foutmelding)
 */
function deleteFriend($userId, $friendId)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Soft delete: zet deleted_at op het huidige tijdstip met NOW()
    // De rij blijft bestaan maar wordt uitgefilterd door "deleted_at IS NULL"
    // in alle andere queries. WHERE zorgt dat alleen de juiste vriend wordt gemarkeerd.
    $stmt = $pdo->prepare(
        "UPDATE Friends SET deleted_at = NOW()
         WHERE user_id = :user_id AND friend_id = :friend_id"
    );
    $stmt->execute(['user_id' => $userId, 'friend_id' => $friendId]);

    // Retourneer null om aan te geven dat de actie is geslaagd
    return null;
}

/**
 * getFriends - Haal alle vrienden van een gebruiker op
 *
 * Deze functie haalt de volledige vriendenlijst op van de ingelogde gebruiker.
 * Alleen actieve vrienden worden opgehaald (niet soft-deleted).
 *
 * LET OP: friend_username wordt hernoemd naar 'username' met AS in de query.
 * Dit maakt het eenvoudiger om in de HTML-templates te gebruiken:
 * $vriend['username'] in plaats van $vriend['friend_username'].
 *
 * @param int $userId  Het ID van de ingelogde gebruiker
 * @return array       Een array van associatieve arrays met friend_id, username, status, note
 */
function getFriends($userId)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // SELECT haalt de gewenste kolommen op uit de Friends tabel
    // friend_username AS username hernoemt de kolom in het resultaat voor gemak
    // WHERE user_id = :user_id filtert op alleen de vrienden van DEZE gebruiker
    // AND deleted_at IS NULL sluit soft-deleted vrienden uit
    $stmt = $pdo->prepare(
        "SELECT friend_id, friend_username AS username, status, note
         FROM Friends
         WHERE user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute(['user_id' => $userId]);

    // fetchAll() retourneert alle resultaatrijen als een array
    return $stmt->fetchAll();
}


// ==========================================================================
// SECTIE 6: SPEELSCHEMA FUNCTIES
// ==========================================================================

/**
 * addSchedule - Voeg een nieuw speelschema toe aan de database
 *
 * Een speelschema is een geplande gamesessie: wanneer ga je welk spel spelen,
 * met welke vrienden, en met wie deel je dit schema? Deze functie valideert
 * alle invoervelden en slaat het schema op in de Schedules tabel.
 *
 * STAPPEN:
 * 1. Valideer alle invoervelden (titel, datum, tijd, vrienden, gedeeld met)
 * 2. Zoek of maak het spel aan via getOrCreateGameId()
 * 3. Sla het schema op met INSERT INTO Schedules
 *
 * @param int    $userId      Het ID van de ingelogde gebruiker
 * @param string $spelTitel   De titel van het spel dat gespeeld wordt
 * @param string $datum       De datum van de sessie (formaat JJJJ-MM-DD)
 * @param string $tijd        De tijd van de sessie (formaat UU:MM)
 * @param string $vrienden    Komma-gescheiden lijst van vrienden die meespelen
 * @param string $gedeeldMet  Komma-gescheiden lijst van gebruikers om mee te delen
 * @return string|null        Foutmelding of null bij succes
 */
function addSchedule($userId, $spelTitel, $datum, $tijd, $vrienden = '', $gedeeldMet = '')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Valideer alle invoervelden een voor een
    // Als een validatie faalt, wordt de foutmelding direct teruggegeven
    if ($fout = validateRequired($spelTitel, "Speltitel", 100))
        return $fout;
    if ($fout = validateDate($datum))
        return $fout;
    if ($fout = validateTime($tijd))
        return $fout;
    // Controleer of de komma-gescheiden lijsten geen lege items bevatten
    if ($fout = validateCommaSeparated($vrienden, "Vrienden"))
        return $fout;
    if ($fout = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $fout;

    // Zoek het spel op titel, of maak het aan als het nog niet bestaat
    // Dit retourneert het game_id dat we nodig hebben voor de koppeling
    $gameId = getOrCreateGameId($pdo, $spelTitel);

    // Sla het speelschema op in de Schedules tabel
    // INSERT INTO voegt een nieuwe rij toe met alle gegevens van het schema
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

    // Retourneer null om aan te geven dat het toevoegen is gelukt
    return null;
}

/**
 * getSchedules - Haal alle speelschema's van een gebruiker op
 *
 * Deze functie haalt de speelschema's op en koppelt ze aan de speltitel
 * via een JOIN met de Games tabel. De resultaten kunnen gesorteerd worden
 * op datum of tijd (oplopend of aflopend).
 *
 * BEVEILIGING TEGEN SQL-INJECTIE BIJ SORTERING:
 * De $sort parameter komt van de gebruiker (via de URL). We kunnen deze
 * NIET veilig in een prepared statement plaatsen (ORDER BY accepteert geen
 * parameters). Daarom gebruiken we een whitelist: alleen vooraf goedgekeurde
 * sorteerwaarden worden geaccepteerd. Alles anders wordt genegeerd.
 *
 * @param int    $userId  Het ID van de ingelogde gebruiker
 * @param string $sort    Sorteeroptie: 'date ASC', 'date DESC', 'time ASC', 'time DESC'
 * @return array          Een array van speelschema's met schedule_id, game_titel, date, time, etc.
 */
function getSchedules($userId, $sort = 'date ASC')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // WHITELIST BESCHERMING: sta alleen deze specifieke sorteerwaarden toe
    // Als de gebruiker iets anders probeert (bijv. SQL-injectie), wordt
    // de standaardwaarde 'date ASC' gebruikt
    $toegestaan = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $toegestaan) ? $sort : 'date ASC';

    // Haal de speelschema's op met een JOIN naar de Games tabel voor de speltitel
    // g.titel AS game_titel hernoemt de kolom voor duidelijkheid in het resultaat
    // LIMIT 50 beperkt het aantal resultaten om de prestaties te waarborgen
    $stmt = $pdo->prepare(
        "SELECT s.schedule_id, g.titel AS game_titel, s.date, s.time, s.friends, s.shared_with
         FROM Schedules s
         JOIN Games g ON s.game_id = g.game_id
         WHERE s.user_id = :user_id AND s.deleted_at IS NULL
         ORDER BY $sort LIMIT 50"
    );
    $stmt->execute(['user_id' => $userId]);

    // Retourneer alle gevonden schema's als een array
    return $stmt->fetchAll();
}

/**
 * editSchedule - Werk een bestaand speelschema bij
 *
 * Deze functie wordt aangeroepen vanuit edit_schedule.php wanneer de gebruiker
 * een bestaand speelschema wil wijzigen. Alle velden worden opnieuw gevalideerd
 * en het schema wordt bijgewerkt in de database.
 *
 * BEVEILIGING: voordat het schema wordt bijgewerkt, controleren we via
 * checkOwnership() of het schema daadwerkelijk bij de ingelogde gebruiker hoort.
 *
 * @param int    $userId      Het ID van de ingelogde gebruiker
 * @param int    $schemaId    Het ID van het speelschema dat bewerkt wordt
 * @param string $spelTitel   De (eventueel gewijzigde) speltitel
 * @param string $datum       De (eventueel gewijzigde) datum
 * @param string $tijd        De (eventueel gewijzigde) tijd
 * @param string $vrienden    De (eventueel gewijzigde) vriendenlijst
 * @param string $gedeeldMet  De (eventueel gewijzigde) deellijst
 * @return string|null        Foutmelding of null bij succes
 */
function editSchedule($userId, $schemaId, $spelTitel, $datum, $tijd, $vrienden = '', $gedeeldMet = '')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // EIGENAARSCHAP CONTROLE: controleer of dit schema bij de ingelogde gebruiker hoort
    // checkOwnership() is een generieke functie die voor elke tabel werkt
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $schemaId, $userId)) {
        return "Geen toestemming om te bewerken.";
    }

    // Valideer alle invoervelden opnieuw (ook bij bewerken!)
    if ($fout = validateRequired($spelTitel, "Speltitel", 100))
        return $fout;
    if ($fout = validateDate($datum))
        return $fout;
    if ($fout = validateTime($tijd))
        return $fout;
    if ($fout = validateCommaSeparated($vrienden, "Vrienden"))
        return $fout;
    if ($fout = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $fout;

    // Zoek of maak het spel aan (de titel kan gewijzigd zijn)
    $gameId = getOrCreateGameId($pdo, $spelTitel);

    // Werk het speelschema bij met UPDATE
    // SET wijzigt alle kolommen met de nieuwe waarden
    // WHERE zorgt ervoor dat alleen het juiste schema van de juiste gebruiker wordt bijgewerkt
    // AND deleted_at IS NULL voorkomt dat verwijderde schema's worden bewerkt
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

    // Retourneer null om aan te geven dat de wijziging is gelukt
    return null;
}

/**
 * deleteSchedule - Verwijder een speelschema (soft delete)
 *
 * Dit is een SOFT DELETE: het schema wordt niet echt verwijderd maar krijgt
 * een deleted_at tijdstempel. Zo blijft het schema bewaard in de database
 * maar verschijnt het niet meer in de lijsten.
 *
 * BEVEILIGING: eigenaarschap wordt gecontroleerd voordat de verwijdering
 * wordt uitgevoerd via checkOwnership().
 *
 * @param int $userId    Het ID van de ingelogde gebruiker
 * @param int $schemaId  Het ID van het speelschema dat verwijderd wordt
 * @return string|null   Foutmelding of null bij succes
 */
function deleteSchedule($userId, $schemaId)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Controleer eigenaarschap: mag deze gebruiker dit schema verwijderen?
    if (!checkOwnership($pdo, 'Schedules', 'schedule_id', $schemaId, $userId)) {
        return "Geen toestemming om te verwijderen.";
    }

    // Soft delete: zet deleted_at op het huidige tijdstip
    // Het schema blijft in de database maar wordt uitgefilterd in alle queries
    $stmt = $pdo->prepare(
        "UPDATE Schedules SET deleted_at = NOW()
         WHERE schedule_id = :id AND user_id = :user_id"
    );
    $stmt->execute(['id' => $schemaId, 'user_id' => $userId]);

    // Retourneer null om aan te geven dat de verwijdering is gelukt
    return null;
}


// ==========================================================================
// SECTIE 7: EVENEMENT FUNCTIES
// ==========================================================================

/**
 * addEvent - Voeg een nieuw evenement toe (toernooi, stream, etc.)
 *
 * Een evenement is een gaming-gerelateerde activiteit zoals een toernooi,
 * livestream of meetup. Evenementen kunnen een herinnering hebben die
 * de gebruiker op tijd waarschuwt.
 *
 * VERSCHIL MET SPEELSCHEMA:
 * - Speelschema = wanneer je een spel gaat spelen (gekoppeld aan een game)
 * - Evenement = een speciale gelegenheid (toernooi, stream) met extra velden
 *   zoals beschrijving, herinnering en externe link
 *
 * VALIDATIE:
 * - Titel: verplicht, maximaal 100 tekens
 * - Datum: verplicht, geldig formaat, vandaag of in de toekomst
 * - Tijd: verplicht, geldig formaat UU:MM
 * - Beschrijving: optioneel, maximaal 500 tekens
 * - Herinnering: moet 'none', '1_hour' of '1_day' zijn
 * - Externe link: optioneel, moet een geldige URL zijn
 * - Gedeeld met: optioneel, komma-gescheiden lijst
 *
 * @param int    $userId       Het ID van de ingelogde gebruiker
 * @param string $titel        De titel van het evenement
 * @param string $datum        De datum (formaat JJJJ-MM-DD)
 * @param string $tijd         De tijd (formaat UU:MM)
 * @param string $beschrijving Optionele beschrijving van het evenement
 * @param string $herinnering  Herinneringsoptie: 'none', '1_hour', of '1_day'
 * @param string $externeLink  Optionele externe URL (bijv. naar Twitch of een toernooi site)
 * @param string $gedeeldMet   Komma-gescheiden lijst van gebruikers om mee te delen
 * @return string|null         Foutmelding of null bij succes
 */
function addEvent($userId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink = '', $gedeeldMet = '')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Valideer de verplichte velden
    if ($fout = validateRequired($titel, "Titel", 100))
        return $fout;
    if ($fout = validateDate($datum))
        return $fout;
    if ($fout = validateTime($tijd))
        return $fout;

    // Valideer de optionele beschrijving: als ingevuld, maximaal 500 tekens
    if (!empty($beschrijving) && strlen($beschrijving) > 500) {
        return "Beschrijving is te lang (maximaal 500 tekens).";
    }

    // Valideer de herinnering: moet een van de drie toegestane waarden zijn
    // in_array() controleert of de waarde voorkomt in de array
    if (!in_array($herinnering, ['none', '1_hour', '1_day'])) {
        return "Ongeldige herinnering keuze.";
    }

    // Valideer de optionele externe link: als ingevuld, moet het een geldige URL zijn
    if ($fout = validateUrl($externeLink))
        return $fout;
    // Valideer de optionele komma-gescheiden lijst
    if ($fout = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $fout;

    // Sla het evenement op in de Events tabel
    // INSERT INTO voegt een nieuwe rij toe met alle evenementgegevens
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

    // Retourneer null om aan te geven dat het toevoegen is gelukt
    return null;
}

/**
 * getEvents - Haal alle evenementen van een gebruiker op
 *
 * Deze functie haalt alle actieve evenementen op uit de Events tabel.
 * De resultaten kunnen gesorteerd worden op datum of tijd.
 *
 * BEVEILIGING TEGEN SQL-INJECTIE BIJ SORTERING:
 * Net als bij getSchedules() wordt een whitelist gebruikt om alleen
 * veilige sorteerwaarden toe te staan.
 *
 * @param int    $userId  Het ID van de ingelogde gebruiker
 * @param string $sort    Sorteeroptie: 'date ASC', 'date DESC', 'time ASC', 'time DESC'
 * @return array          Een array van evenementen
 */
function getEvents($userId, $sort = 'date ASC')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // WHITELIST BESCHERMING: sta alleen veilige sorteerwaarden toe
    $toegestaan = ['date ASC', 'date DESC', 'time ASC', 'time DESC'];
    $sort = in_array($sort, $toegestaan) ? $sort : 'date ASC';

    // Haal alle evenementen op voor deze gebruiker
    // deleted_at IS NULL sluit soft-deleted evenementen uit
    // LIMIT 50 beperkt het aantal resultaten voor prestaties
    $stmt = $pdo->prepare(
        "SELECT event_id, title, date, time, description, reminder, external_link, shared_with
         FROM Events
         WHERE user_id = :user_id AND deleted_at IS NULL
         ORDER BY $sort LIMIT 50"
    );
    $stmt->execute(['user_id' => $userId]);

    // Retourneer alle gevonden evenementen als een array
    return $stmt->fetchAll();
}

/**
 * editEvent - Werk een bestaand evenement bij
 *
 * Deze functie wordt aangeroepen vanuit edit_event.php wanneer de gebruiker
 * een bestaand evenement wil wijzigen. Alle velden worden opnieuw gevalideerd
 * en het evenement wordt bijgewerkt in de database.
 *
 * BEVEILIGING: eigenaarschap wordt gecontroleerd via checkOwnership()
 * voordat de wijziging wordt doorgevoerd.
 *
 * @param int    $userId       Het ID van de ingelogde gebruiker
 * @param int    $eventId      Het ID van het evenement dat bewerkt wordt
 * @param string $titel        De (eventueel gewijzigde) titel
 * @param string $datum        De (eventueel gewijzigde) datum
 * @param string $tijd         De (eventueel gewijzigde) tijd
 * @param string $beschrijving De (eventueel gewijzigde) beschrijving
 * @param string $herinnering  De (eventueel gewijzigde) herinnering
 * @param string $externeLink  De (eventueel gewijzigde) externe link
 * @param string $gedeeldMet   De (eventueel gewijzigde) deellijst
 * @return string|null         Foutmelding of null bij succes
 */
function editEvent($userId, $eventId, $titel, $datum, $tijd, $beschrijving, $herinnering, $externeLink = '', $gedeeldMet = '')
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // EIGENAARSCHAP CONTROLE: controleer of dit evenement bij de gebruiker hoort
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "Geen toestemming om te bewerken.";
    }

    // Valideer alle invoervelden opnieuw
    if ($fout = validateRequired($titel, "Titel", 100))
        return $fout;
    if ($fout = validateDate($datum))
        return $fout;
    if ($fout = validateTime($tijd))
        return $fout;
    if (!empty($beschrijving) && strlen($beschrijving) > 500) {
        return "Beschrijving is te lang (maximaal 500 tekens).";
    }
    if (!in_array($herinnering, ['none', '1_hour', '1_day'])) {
        return "Ongeldige herinnering keuze.";
    }
    if ($fout = validateUrl($externeLink))
        return $fout;
    if ($fout = validateCommaSeparated($gedeeldMet, "Gedeeld met"))
        return $fout;

    // Werk het evenement bij met UPDATE
    // SET wijzigt alle kolommen met de nieuwe waarden
    // WHERE zorgt ervoor dat alleen het juiste evenement van de juiste gebruiker
    // wordt bijgewerkt, en dat soft-deleted evenementen niet worden bewerkt
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

    // Retourneer null om aan te geven dat de wijziging is gelukt
    return null;
}

/**
 * deleteEvent - Verwijder een evenement (soft delete)
 *
 * Dit is een SOFT DELETE: het evenement wordt niet echt verwijderd maar krijgt
 * een deleted_at tijdstempel. Zo blijft het evenement bewaard in de database
 * maar verschijnt het niet meer in de lijsten.
 *
 * BEVEILIGING: eigenaarschap wordt gecontroleerd via checkOwnership().
 *
 * @param int $userId   Het ID van de ingelogde gebruiker
 * @param int $eventId  Het ID van het evenement dat verwijderd wordt
 * @return string|null  Foutmelding of null bij succes
 */
function deleteEvent($userId, $eventId)
{
    // Haal de database verbinding op
    $pdo = getDBConnection();

    // Controleer eigenaarschap: mag deze gebruiker dit evenement verwijderen?
    if (!checkOwnership($pdo, 'Events', 'event_id', $eventId, $userId)) {
        return "Geen toestemming om te verwijderen.";
    }

    // Soft delete: zet deleted_at op het huidige tijdstip
    // Het evenement blijft in de database maar wordt uitgefilterd in alle queries
    $stmt = $pdo->prepare(
        "UPDATE Events SET deleted_at = NOW()
         WHERE event_id = :id AND user_id = :user_id"
    );
    $stmt->execute(['id' => $eventId, 'user_id' => $userId]);

    // Retourneer null om aan te geven dat de verwijdering is gelukt
    return null;
}


// ==========================================================================
// SECTIE 8: HULPFUNCTIES
// ==========================================================================

/**
 * checkOwnership - Controleer of een item van de ingelogde gebruiker is
 *
 * Dit is een GENERIEKE beveiligingsfunctie die voor elke tabel werkt.
 * Het controleert of een specifiek item (evenement, schema, etc.) daadwerkelijk
 * toebehoort aan de ingelogde gebruiker. Dit voorkomt dat gebruikers via
 * URL-manipulatie andermans gegevens kunnen bewerken of verwijderen.
 *
 * WAAROM GENERIEK?
 * In plaats van voor elke tabel een aparte eigenaarschapscontrole te schrijven,
 * accepteert deze functie de tabelnaam en kolomnaam als parameters. Zo kan
 * dezelfde functie hergebruikt worden voor Schedules, Events, Friends, enz.
 *
 * WAARSCHUWING: $tabel en $idKolom worden NIET via prepared statements ingevoerd
 * omdat MySQL geen parameters toestaat voor tabelnamen en kolomnamen. Deze waarden
 * worden alleen intern in de code gezet (nooit door de gebruiker), dus dit is veilig.
 *
 * @param PDO    $pdo      Database verbinding
 * @param string $tabel    Naam van de tabel (bijv. 'Schedules', 'Events')
 * @param string $idKolom  Naam van de ID kolom (bijv. 'schedule_id', 'event_id')
 * @param int    $id       ID van het item dat gecontroleerd wordt
 * @param int    $userId   ID van de ingelogde gebruiker
 * @return bool            true als de gebruiker eigenaar is, false als niet
 */
function checkOwnership($pdo, $tabel, $idKolom, $id, $userId)
{
    // Tel het aantal rijen waar het item-ID EN het user-ID overeenkomen
    // EN het item niet soft-deleted is
    // Als COUNT(*) > 0, is de gebruiker de eigenaar
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM $tabel
         WHERE $idKolom = :id AND user_id = :user_id AND deleted_at IS NULL"
    );
    $stmt->execute(['id' => $id, 'user_id' => $userId]);

    // fetchColumn() haalt het getal op (0 = niet gevonden, 1+ = gevonden)
    // > 0 converteert het naar een boolean: true als eigenaar, false als niet
    return $stmt->fetchColumn() > 0;
}

/**
 * getCalendarItems - Haal alle items op voor de kalenderweergave
 *
 * Deze functie combineert speelschema's EN evenementen tot een enkele lijst,
 * gesorteerd op datum en tijd. Dit wordt gebruikt voor de kalenderweergave
 * op de hoofdpagina, waar alle geplande activiteiten chronologisch worden getoond.
 *
 * HOE HET WERKT:
 * 1. Haal alle speelschema's op via getSchedules()
 * 2. Haal alle evenementen op via getEvents()
 * 3. Combineer beide arrays met array_merge()
 * 4. Sorteer de gecombineerde lijst chronologisch met usort()
 *
 * @param int $userId  Het ID van de ingelogde gebruiker
 * @return array       Een gesorteerde array van alle kalenderitems (schema's + evenementen)
 */
function getCalendarItems($userId)
{
    // Haal beide types items op via de bestaande functies
    $schemas = getSchedules($userId);
    $evenementen = getEvents($userId);

    // array_merge() combineert twee arrays tot een enkele array
    // De resulterende array bevat zowel schema's als evenementen door elkaar
    $onderdelen = array_merge($schemas, $evenementen);

    // usort() sorteert de array op basis van een aangepaste vergelijkingsfunctie
    // De callback functie vergelijkt de datum+tijd van twee items
    // strtotime() zet een datum/tijd string om naar een Unix timestamp (seconden sinds 1970)
    // De <=> operator (spaceship operator) vergelijkt twee waarden:
    //   retourneert -1 als links kleiner is, 0 als gelijk, 1 als links groter is
    // Dit zorgt ervoor dat de items chronologisch worden gesorteerd (vroegste eerst)
    usort($onderdelen, function ($a, $b) {
        return strtotime($a['date'] . ' ' . $a['time']) <=> strtotime($b['date'] . ' ' . $b['time']);
    });

    // Retourneer de gesorteerde lijst van alle kalenderitems
    return $onderdelen;
}

/**
 * getReminders - Haal actieve herinneringen op die nu getoond moeten worden
 *
 * Deze functie controleert alle evenementen van de gebruiker en bepaalt
 * welke herinneringen op dit moment actief zijn (binnen een tijdvenster
 * van 1 minuut). Dit wordt gebruikt om notificaties te tonen aan de gebruiker.
 *
 * HOE DE BEREKENING WERKT:
 * 1. Haal alle evenementen op
 * 2. Sla evenementen zonder herinnering over ('none')
 * 3. Bereken het herinneringsmoment:
 *    - '1_hour': evenementtijd minus 3600 seconden (1 uur)
 *    - '1_day': evenementtijd minus 86400 seconden (24 uur)
 * 4. Controleer of het huidige tijdstip binnen het herinneringsvenster valt
 *    (herinneringstijd <= nu EN herinneringstijd > nu - 60 seconden)
 *
 * TIJDVENSTER VAN 60 SECONDEN:
 * De herinnering is maar 60 seconden actief. Dit voorkomt dat dezelfde
 * herinnering steeds opnieuw wordt getoond bij elke paginalading.
 *
 * @param int $userId  Het ID van de ingelogde gebruiker
 * @return array       Een array van evenementen waarvoor de herinnering nu actief is
 */
function getReminders($userId)
{
    // Haal alle evenementen op van de gebruiker
    $evenementen = getEvents($userId);
    // Maak een lege array om de actieve herinneringen in op te slaan
    $herinneringen = [];

    // Loop door alle evenementen heen
    foreach ($evenementen as $evenement) {
        // Sla evenementen zonder herinnering over
        // 'none' betekent dat de gebruiker geen herinnering wil
        if ($evenement['reminder'] == 'none')
            continue;

        // Bereken het Unix timestamp van het evenement (datum + tijd)
        // strtotime() zet "2025-03-15 15:00" om naar een getal (seconden sinds 1970)
        $evenementTijd = strtotime($evenement['date'] . ' ' . $evenement['time']);

        // Bereken het herinneringsmoment:
        // 1 uur = 3600 seconden, 1 dag = 86400 seconden
        // Het herinneringsmoment is het evenementtijdstip MINUS de herinneringstijd
        $herinneringTijd = $evenementTijd - ($evenement['reminder'] == '1_hour' ? 3600 : 86400);

        // Controleer of de herinnering NU actief is:
        // - $herinneringTijd <= time(): het herinneringsmoment is bereikt of voorbij
        // - $herinneringTijd > time() - 60: het is niet langer dan 60 seconden geleden
        // Dit creëert een tijdvenster van 1 minuut waarin de herinnering getoond wordt
        if ($herinneringTijd <= time() && $herinneringTijd > time() - 60) {
            $herinneringen[] = $evenement;
        }
    }

    // Retourneer de array met alle actieve herinneringen
    return $herinneringen;
}

// ==========================================================================
// EINDE VAN FUNCTIONS.PHP
// ==========================================================================
