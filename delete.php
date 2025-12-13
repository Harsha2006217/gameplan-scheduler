<?php
// delete.php - Delete Action Handler
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: 
// This script doesn't have a visible page. 
// It receives a request (e.g. "delete friend ID 5") and executes it, 
// then sends the user back to the previous page.

require_once 'functions.php';
checkSessionTimeout();

// Security: User must be logged in.
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get the 'type' (what to delete) and 'id' (which one) from the URL.
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
$userId = getUserId();
$error = '';

// --- Switch Logic ---
// Decide which deletion function to call based on the 'type'.
if ($type == 'schedule') {
    $error = deleteSchedule($userId, $id);
    $redirect = 'index.php'; // Schedules are on the dashboard
} elseif ($type == 'event') {
    $error = deleteEvent($userId, $id);
    $redirect = 'index.php'; // Events are on the dashboard
} elseif ($type == 'favorite') {
    $error = deleteFavoriteGame($userId, $id);
    $redirect = 'profile.php'; // Favorites are on profile page
} elseif ($type == 'friend') {
    $error = deleteFriend($userId, $id);
    $redirect = 'add_friend.php'; // Friends are on friend page
} else {
    // If someone tries to hack the URL with an unknown type
    $error = 'Invalid delete type.';
    $redirect = 'index.php';
}

// --- Feedback ---
// Set a Green message (Success) or Red message (Error) for the next page.
if ($error) {
    setMessage('danger', $error);
} else {
    // ucfirst capitalizes first letter: 'friend' -> 'Friend'
    setMessage('success', ucfirst($type) . ' removed successfully!');
}

// --- Redirect ---
// Send user back to the appropriate page.
header("Location: " . $redirect);
exit;
?>