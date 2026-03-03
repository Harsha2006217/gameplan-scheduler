<?php
/**
 * ==========================================================================
 * DELETE.PHP - VERWIJDER HANDLER
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand handelt het verwijderen af van items:
 * - Schema's (speelschema's)
 * - Evenementen
 * - Favoriete spellen
 * - Vrienden
 *
 * Gebruikt SOFT DELETE: Zet een deleted_at timestamp in plaats van
 * data echt te verwijderen. Dit bewaart data voor herstel doeleinden.
 *
 * Beveiliging:
 * - Controleert of gebruiker ingelogd is
 * - Valideert eigenaarschap voor verwijdering
 * - Geeft juiste succes/fout meldingen
 * ==========================================================================
 */

require_once 'functions.php';

// Controleer sessie timeout
checkSessionTimeout();

// Stuur door als niet ingelogd
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Haal parameters uit de URL
// type: wat verwijderen (schedule, event, favorite, friend)
// id: het ID van het item
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
$userId = getUserId();
$error = '';

/**
 * VERWIJDER LOGICA
 *
 * Op basis van het type wordt de juiste verwijder-functie aangeroepen.
 * Elke functie controleert eigenaarschap (je kunt alleen je eigen items verwijderen).
 */
if ($type == 'schedule') {
    // Verwijder speelschema
    $error = deleteSchedule($userId, $id);
    $redirect = 'index.php';

} elseif ($type == 'event') {
    // Verwijder evenement
    $error = deleteEvent($userId, $id);
    $redirect = 'index.php';

} elseif ($type == 'favorite') {
    // Verwijder spel uit favorieten
    $error = deleteFavoriteGame($userId, $id);
    $redirect = 'profile.php';

} elseif ($type == 'friend') {
    // Verwijder vriend uit lijst
    $error = deleteFriend($userId, $id);
    $redirect = 'add_friend.php';

} else {
    // Ongeldig type opgegeven
    $error = 'Ongeldig type opgegeven.';
    $redirect = 'index.php';
}

/**
 * ZET MELDING EN STUUR DOOR
 *
 * Toon een succes- of foutmelding op de doorstuur pagina.
 */

// Nederlandse namen voor de types
$typeNamen = [
    'schedule' => 'Schema',
    'event' => 'Evenement',
    'favorite' => 'Favoriet',
    'friend' => 'Vriend',
];

if ($error) {
    setMessage('danger', $error);
} else {
    $naam = $typeNamen[$type] ?? $type;
    setMessage('success', $naam . ' succesvol verwijderd!');
}

// Stuur door naar de juiste pagina
header("Location: " . $redirect);
exit;
?>