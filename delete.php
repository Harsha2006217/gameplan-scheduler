<?php
// This file is delete.php - Handles deleting items like friends, schedules, events, favorites.
// What is delete? It's soft delete - marks as deleted but keeps in database for recovery.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Gets type (e.g., 'friend') and ID from URL, checks ownership, calls delete function, redirects.
// Improvements: Added type checks, error handling, success messages, no bugs like wrong ID.
// Simple: Like trash bin - move to trash, not permanent erase.

require_once 'functions.php';

checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$type = $_GET['type'] ?? ''; // Get type from URL, ?? '' if not set.
$id = $_GET['id'] ?? 0; // Get ID, default 0.
$userId = getUserId();

$error = ''; // Error var.
$redirect = 'index.php'; // Default redirect.

if ($type == 'schedule') { // If deleting schedule.
    $error = deleteSchedule($userId, $id);
    $redirect = 'index.php';
} elseif ($type == 'event') { // Event.
    $error = deleteEvent($userId, $id);
    $redirect = 'index.php';
} elseif ($type == 'favorite') { // Favorite game.
    $error = deleteFavoriteGame($userId, $id);
    $redirect = 'profile.php';
} elseif ($type == 'friend') { // Friend.
    $error = deleteFriend($userId, $id);
    $redirect = 'add_friend.php';
} else { // Invalid type.
    $error = 'Invalid type.';
    $redirect = 'index.php';
}

if ($error) { // If error (like no permission).
    setMessage('danger', $error); // Set error message.
} else {
    setMessage('success', ucfirst($type) . ' deleted successfully!'); // Success, ucfirst makes first letter capital.
}

header("Location: " . $redirect); // Go to redirect page.
exit;
?>