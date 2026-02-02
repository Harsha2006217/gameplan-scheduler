<?php
/**
 * ============================================================================
 * LOGOUT.PHP - LOGOUT SCRIPT / UITLOG SCRIPT
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This script destroys the current session and redirects to the login page.
 * 
 * DUTCH:
 * Dit script vernietigt de huidige sessie en stuurt terug naar de login pagina.
 * ============================================================================
 */

require_once 'functions.php';

// Start session if not already started (handled in functions.php but good practice)
// Start sessie als nog niet gestart (afgehandeld in functions.php maar goede gewoonte)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
// Verwijder alle sessie variabelen
$_SESSION = [];

// Destroy the session cookie
// Vernietig de sessie cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
// Vernietig de sessie
session_destroy();

// Redirect to login page with message
// Stuur naar login pagina met bericht
header("Location: login.php?msg=logged_out");
exit;
?>
