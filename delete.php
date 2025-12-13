<?php
// ============================================================================
// DELETE.PHP - Universal Delete Handler
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This file handles DELETE operations for multiple data types.
// It uses URL parameters to determine what to delete:
// - ?type=friend&id=123 → Delete friend with ID 123
// - ?type=schedule&id=456 → Delete schedule with ID 456
// - ?type=event&id=789 → Delete event with ID 789
// - ?type=favorite&id=101 → Delete favorite game with ID 101
//
// SECURITY FEATURES:
// - User must be logged in
// - User can only delete their OWN data (ownership verified)
// - Soft delete used where applicable (deleted_at timestamp)
// - Invalid types/IDs redirect safely
//
// SOFT DELETE EXPLANATION:
// Instead of permanently removing data from the database:
// - We set deleted_at = NOW() (current timestamp)
// - The record still exists but is "marked as deleted"
// - WHERE deleted_at IS NULL filters out deleted records
// - Benefits: Data can be recovered, audit trail maintained
// ============================================================================


// Include core functions
require_once 'functions.php';

// Check session timeout
checkSessionTimeout();

// Must be logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get user ID
$userId = getUserId();


// ============================================================================
// GET DELETE PARAMETERS FROM URL
// ============================================================================
// $_GET contains URL parameters
// Example URL: delete.php?type=friend&id=5

$type = $_GET['type'] ?? '';  // What to delete (friend, schedule, event, favorite)
$id = $_GET['id'] ?? 0;       // ID of the record to delete


// ============================================================================
// VALIDATE ID
// ============================================================================
// ID must be a number greater than 0

if (!is_numeric($id) || $id <= 0) {
    setMessage('danger', 'Invalid item ID.');
    header("Location: index.php");
    exit;
}


// ============================================================================
// PROCESS DELETE BASED ON TYPE
// ============================================================================
// switch statement handles different delete types

switch ($type) {

    // ========================================================================
    // DELETE FRIEND
    // ========================================================================
    case 'friend':
        // deleteFriend() verifies ownership and soft deletes
        $error = deleteFriend($userId, $id);

        if ($error) {
            setMessage('danger', $error);
        } else {
            setMessage('success', 'Friend removed successfully.');
        }

        // Redirect back to friends page
        header("Location: add_friend.php");
        break;


    // ========================================================================
    // DELETE SCHEDULE
    // ========================================================================
    case 'schedule':
        // deleteSchedule() verifies ownership and soft deletes
        $error = deleteSchedule($userId, $id);

        if ($error) {
            setMessage('danger', $error);
        } else {
            setMessage('success', 'Schedule deleted successfully.');
        }

        header("Location: index.php");
        break;


    // ========================================================================
    // DELETE EVENT
    // ========================================================================
    case 'event':
        // deleteEvent() verifies ownership and soft deletes
        $error = deleteEvent($userId, $id);

        if ($error) {
            setMessage('danger', $error);
        } else {
            setMessage('success', 'Event deleted successfully.');
        }

        header("Location: index.php");
        break;


    // ========================================================================
    // DELETE FAVORITE GAME
    // ========================================================================
    case 'favorite':
        // deleteFavoriteGame() removes from UserGames table
        $error = deleteFavoriteGame($userId, $id);

        if ($error) {
            setMessage('danger', $error);
        } else {
            setMessage('success', 'Game removed from favorites.');
        }

        header("Location: profile.php");
        break;


    // ========================================================================
    // INVALID TYPE
    // ========================================================================
    default:
        // Unknown type - redirect with error
        setMessage('danger', 'Invalid delete type specified.');
        header("Location: index.php");
        break;
}

// Always exit after redirect
exit;
?>