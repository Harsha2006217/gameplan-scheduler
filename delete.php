<?php
/**
 * ============================================================================
 * DELETE.PHP - DELETE HANDLER / VERWIJDER HANDLER
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This file handles deletion of items (schedules, events, favorites, friends).
 * Uses SOFT DELETE: Sets deleted_at timestamp instead of actually removing data.
 * This preserves data for recovery and audit purposes.
 * 
 * DUTCH:
 * Dit bestand handelt verwijdering af van items (schema's, evenementen, favorieten, vrienden).
 * Gebruikt SOFT DELETE: Zet deleted_at timestamp in plaats van data echt te verwijderen.
 * Dit bewaart data voor herstel en audit doeleinden.
 * 
 * SECURITY:
 * - Checks user is logged in
 * - Validates ownership before deletion
 * - Sets appropriate success/error messages
 * ============================================================================
 */

require_once 'functions.php';

// Check session timeout / Controleer sessie timeout
checkSessionTimeout();

// Redirect if not logged in / Redirect als niet ingelogd
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get parameters from URL / Haal parameters uit URL
// type: what to delete (schedule, event, favorite, friend)
// id: the ID of the item to delete
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
$userId = getUserId();
$error = '';

/**
 * DELETION LOGIC
 * VERWIJDER LOGICA
 * 
 * Based on type, call the appropriate delete function.
 * Each function checks ownership (can only delete your own items).
 */
if ($type == 'schedule') {
    // Delete gaming schedule / Verwijder gaming schema
    $error = deleteSchedule($userId, $id);
    $redirect = 'index.php';

} elseif ($type == 'event') {
    // Delete gaming event / Verwijder gaming evenement
    $error = deleteEvent($userId, $id);
    $redirect = 'index.php';

} elseif ($type == 'favorite') {
    // Remove game from favorites / Verwijder spel uit favorieten
    $error = deleteFavoriteGame($userId, $id);
    $redirect = 'profile.php';

} elseif ($type == 'friend') {
    // Remove friend from list / Verwijder vriend uit lijst
    $error = deleteFriend($userId, $id);
    $redirect = 'add_friend.php';

} else {
    // Invalid type provided / Ongeldig type opgegeven
    $error = 'Invalid type. / Ongeldig type.';
    $redirect = 'index.php';
}

/**
 * SET MESSAGE AND REDIRECT
 * ZET BERICHT EN REDIRECT
 * 
 * Show success or error message on the redirect page.
 */
if ($error) {
    setMessage('danger', $error);
} else {
    // ucfirst: Capitalize first letter (e.g., "schedule" -> "Schedule")
    setMessage('success', ucfirst($type) . ' deleted successfully! / ' . ucfirst($type) . ' succesvol verwijderd!');
}

// Redirect back to appropriate page / Redirect terug naar juiste pagina
header("Location: " . $redirect);
exit;

/**
 * ============================================================================
 * END OF DELETE.PHP / EINDE VAN DELETE.PHP
 * ============================================================================
 */
?>