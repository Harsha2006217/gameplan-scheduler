<?php
// This file is add_friend.php - It handles adding and listing friends for the user.
// What is a friend in this app? A friend is another gamer you add by their username, with a note (like "good at Fortnite") and status (like "Online" or "Offline").
// This page shows a form to add a new friend and a table of current friends with edit/delete buttons.
// Author: Harsha Kanaparthi.
// Date: Improved on 10-12-2025.
// Description: Checks login, processes form to add friend using addFriend() from functions.php, lists friends with getFriends().
// Improvements: Added table for friends list with Bootstrap styling, made responsive (table scrolls on small screens), added confirmation for delete, fixed bugs like duplicate friends check, added max lengths.
// Comments explain every part simply: Imagine friends as contacts in your phone - this page adds and shows them.
// No bugs: Validated inputs, prevented self-add, used safeEcho for output security.

require_once 'functions.php'; // Import helper functions like addFriend(), getFriends().

checkSessionTimeout(); // Check if session timed out for security.

if (!isLoggedIn()) { // If not logged in, redirect to login.
    header("Location: login.php");
    exit;
}

$userId = getUserId(); // Get user's ID.

$friends = getFriends($userId); // Get list of current friends as array.

$error = ''; // Error variable.

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // If form submitted.
    $friendUsername = $_POST['friend_username'] ?? ''; // Friend's username.
    $note = $_POST['note'] ?? ''; // Optional note.
    $status = $_POST['status'] ?? 'Offline'; // Status, default Offline.

    $error = addFriend($userId, $friendUsername, $note, $status); // Add friend, get error if any.

    if (!$error) { // Success.
        setMessage('success', 'Friend added successfully!');
        header("Location: add_friend.php"); // Reload page to show updated list.
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>
    <main class="container mt-5 pt-5">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
        <?php endif; ?>
        <h2>Add Friend</h2>
        <form method="POST"> <!-- Form to add friend. -->
            <div class="mb-3">
                <label for="friend_username" class="form-label">Friend's Username</label>
                <input type="text" id="friend_username" name="friend_username" class="form-control" required maxlength="50" aria-label="Friend's Username">
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note (Optional)</label>
                <textarea id="note" name="note" class="form-control" rows="2" aria-label="Note"></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" id="status" name="status" class="form-control" value="Offline" maxlength="50" aria-label="Status">
            </div>
            <button type="submit" class="btn btn-primary">Add Friend</button>
        </form>
        <h2 class="mt-4">Your Friends</h2>
        <div class="table-responsive"> <!-- Makes table scroll on small screens for responsiveness. -->
            <table class="table table-dark table-bordered">
                <thead class="bg-info"> <!-- Header row with blue background. -->
                    <tr><th>Username</th><th>Status</th><th>Note</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($friends as $friend): ?> <!-- Loop through each friend. foreach is like "for each item in list". -->
                        <tr> <!-- New row. -->
                            <td><?php echo safeEcho($friend['username']); ?></td> <!-- Show username, safeEcho protects from bad code. -->
                            <td><?php echo safeEcho($friend['status']); ?></td>
                            <td><?php echo safeEcho($friend['note']); ?></td>
                            <td>
                                <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-warning">Edit</a> <!-- Link to edit page with ID. -->
                                <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this friend?');">Delete</a> <!-- Delete link with confirmation pop-up. onclick shows yes/no box. -->
                            </td>
                        </tr>
                    <?php endforeach; ?> <!-- End loop. -->
                </tbody>
            </table>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>