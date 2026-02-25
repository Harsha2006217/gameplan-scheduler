<?php
/**
 * ============================================================================
 * LOGOUT.PHP - UITLOGSCRIPT
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit script vernietigt de huidige sessie en stuurt de gebruiker
 * terug naar de loginpagina.
 * ============================================================================
 */

require_once 'functions.php';

// Start sessie als nog niet gestart (afgehandeld in functions.php, maar voor de zekerheid)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verwijder alle sessievariabelen
$_SESSION = [];

// Vernietig de sessiecookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Vernietig de sessie
session_destroy();

// Stuur door naar de loginpagina
header("Location: login.php?msg=logged_out");
exit;
?>
