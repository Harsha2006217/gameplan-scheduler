<?php
/**
 * ============================================================================
 * delete.php - VERWIJDER HANDLER
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Dit bestand handelt ALLE verwijder-acties af voor:
 * - Schedules (speelschema's)
 * - Events (evenementen)
 * - Favorites (favoriete games)
 * - Friends (vrienden)
 * 
 * SOFT DELETE:
 * In plaats van data permanent te verwijderen, zetten we een "deleted_at"
 * timestamp. Dit is veiliger omdat data kan worden hersteld indien nodig.
 * 
 * BEVEILIGING:
 * - Alleen ingelogde gebruikers kunnen verwijderen
 * - Eigenaarschapscontrole voorkomt verwijderen van andermans data
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

// Beveiligingscontrole
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Haal parameters op uit URL
$type = $_GET['type'] ?? '';  // Wat wordt verwijderd (schedule, event, etc.)
$id = $_GET['id'] ?? 0;       // ID van het te verwijderen item
$userId = getUserId();
$error = '';

// Bepaal welke functie moet worden aangeroepen op basis van type
switch ($type) {
    case 'schedule':
        $error = deleteSchedule($userId, $id);
        $redirect = 'index.php';
        break;

    case 'event':
        $error = deleteEvent($userId, $id);
        $redirect = 'index.php';
        break;

    case 'favorite':
        $error = deleteFavoriteGame($userId, $id);
        $redirect = 'profile.php';
        break;

    case 'friend':
        $error = deleteFriend($userId, $id);
        $redirect = 'add_friend.php';
        break;

    default:
        $error = 'Invalid type specified.';
        $redirect = 'index.php';
}

// Stel sessie bericht in
if ($error) {
    setMessage('danger', $error);
} else {
    setMessage('success', ucfirst($type) . ' deleted successfully!');
}

// Redirect naar de juiste pagina
header("Location: " . $redirect);
exit;