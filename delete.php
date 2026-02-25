<?php
/**
 * ============================================================================
 * DELETE.PHP - VERWIJDER-HANDLER
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit bestand handelt de verwijdering af van items (schema's, evenementen,
 * favorieten, vrienden).
 *
 * SOFT DELETE: Er wordt een deleted_at-timestamp gezet in plaats van de
 * data daadwerkelijk te verwijderen. Dit maakt herstel mogelijk en
 * zorgt voor een auditspoor.
 *
 * BEVEILIGING:
 * - Controleert of de gebruiker ingelogd is
 * - Valideert eigenaarschap voor verwijdering
 * - Zet passende succes- of foutmeldingen
 * ============================================================================
 */

require_once 'functions.php';

// Controleer sessie-timeout
checkSessionTimeout();

// Stuur door naar loginpagina als niet ingelogd
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Haal parameters op uit de URL
// type: wat verwijderd moet worden (schedule, event, favorite, friend)
// id: het ID van het item
$type   = $_GET['type'] ?? '';
$id     = $_GET['id']   ?? 0;
$userId = getUserId();
$error  = '';

// Verwijderlogica: roep de juiste functie aan op basis van het type
if ($type == 'schedule') {
    $error    = deleteSchedule($userId, $id);
    $redirect = 'index.php';

} elseif ($type == 'event') {
    $error    = deleteEvent($userId, $id);
    $redirect = 'index.php';

} elseif ($type == 'favorite') {
    $error    = deleteFavoriteGame($userId, $id);
    $redirect = 'profile.php';

} elseif ($type == 'friend') {
    $error    = deleteFriend($userId, $id);
    $redirect = 'add_friend.php';

} else {
    $error    = 'Ongeldig type opgegeven.';
    $redirect = 'index.php';
}

// Stel bericht in en stuur door
if ($error) {
    setMessage('danger', $error);
} else {
    $typeNamen = [
        'schedule' => 'Schema',
        'event'    => 'Evenement',
        'favorite' => 'Favoriet',
        'friend'   => 'Vriend',
    ];
    $naam = $typeNamen[$type] ?? ucfirst($type);
    setMessage('success', "$naam succesvol verwijderd!");
}

header("Location: " . $redirect);
exit;
?>