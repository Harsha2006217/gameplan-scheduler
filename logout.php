<?php
/**
 * ==========================================================================
 * LOGOUT.PHP - UITLOG SCRIPT
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Dit script vernietigt de huidige sessie en stuurt de gebruiker
 * terug naar de inlog pagina.
 *
 * Stappen:
 * 1. Alle sessie variabelen verwijderen
 * 2. Sessie cookie vernietigen
 * 3. Sessie vernietigen
 * 4. Doorsturen naar login pagina
 * ==========================================================================
 */

require_once 'functions.php';

// Start sessie als nog niet gestart
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verwijder alle sessie variabelen
$_SESSION = [];

// Vernietig de sessie cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Vernietig de sessie
session_destroy();

// Stuur naar login pagina met bericht
header("Location: login.php?msg=logged_out");
exit;
?>