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
$fout = '';

/**
 * VERWIJDER LOGICA
 *
 * Op basis van het type wordt de juiste verwijder-functie aangeroepen.
 * Elke functie controleert eigenaarschap (je kunt alleen je eigen items verwijderen).
 */
if ($type == 'schedule') {
    // Verwijder speelschema
    $fout = deleteSchedule($userId, $id);
    $doorstuurPagina = 'index.php';

} elseif ($type == 'event') {
    // Verwijder evenement
    $fout = deleteEvent($userId, $id);
    $doorstuurPagina = 'index.php';

} elseif ($type == 'favorite') {
    // Verwijder spel uit favorieten
    $fout = deleteFavoriteGame($userId, $id);
    $doorstuurPagina = 'profile.php';

} elseif ($type == 'friend') {
    // Verwijder vriend uit lijst
    $fout = deleteFriend($userId, $id);
    $doorstuurPagina = 'add_friend.php';

} else {
    // Ongeldig type opgegeven
    $fout = 'Ongeldig type opgegeven.';
    $doorstuurPagina = 'index.php';
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

if ($fout) {
    setMessage('danger', $fout);
} else {
    $naam = $typeNamen[$type] ?? $type;
    setMessage('success', $naam . ' succesvol verwijderd!');
}

// Stuur door naar de juiste pagina
header("Location: " . $doorstuurPagina);
exit;
?>