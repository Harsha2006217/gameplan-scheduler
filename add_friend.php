<?php
// ============================================================================
// ADD_FRIEND.PHP - Friends Management Page
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page allows users to add new friends and view their friends list.
// Friends are added by username with optional notes and status.
//
// USER STORY: "Add friends for contact"
// - User can add friends by entering their username
// - Each friend can have a note and status
// - Friends list is displayed with edit/delete options
//
// FEATURES:
// - Add friend form (username, note, status)
// - Friends list table with CRUD actions
// - Status indicators (Online, Offline, Playing)
// ============================================================================


// Include core functions
require_once 'functions.php';


// Check session timeout
checkSessionTimeout();


// Redirect if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}


// Get user ID
$userId = getUserId();


// Get current friends list
$friends = getFriends($userId);


// Initialize error
$error = '';


// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $friendUsername = $_POST['friend_username'] ?? '';
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';

    // Attempt to add friend
    $error = addFriend($userId, $friendUsername, $note, $status);

    // If successful
    if (!$error) {
        setMessage('success', 'Friend added successfully!');
        header("Location: add_friend.php");
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
    <meta name="description" content="Manage your friends list in GamePlan Scheduler.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">👥 Friends Management</h1>


        <!-- ================================================================
             ADD FRIEND FORM
             ================================================================ -->
        <section class="mb-5">
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h5 fw-bold mb-0">➕ Add New Friend</h2>
                </div>
                <div class="card-body px-4 pb-4">

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo safeEcho($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <!-- Friend Username -->
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">
                                👤 Friend's Username <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="friend_username" name="friend_username"
                                class="form-control bg-dark text-light border-secondary" required maxlength="50"
                                placeholder="Enter your friend's username" aria-label="Friend's Username">
                        </div>


                        <!-- Note about friend -->
                        <div class="mb-3">
                            <label for="note" class="form-label">
                                💬 Note
                            </label>
                            <textarea id="note" name="note" class="form-control bg-dark text-light border-secondary"
                                rows="2" placeholder="How do you know this friend? e.g., School friend, Met in Fortnite"
                                aria-label="Note"></textarea>
                        </div>


                        <!-- Friend Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                📊 Status
                            </label>
                            <select id="status" name="status" class="form-select bg-dark text-light border-secondary"
                                aria-label="Status">
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                                <option value="Playing">Playing</option>
                                <option value="Away">Away</option>
                            </select>
                        </div>


                        <!-- Submit -->
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            ✅ Add Friend
                        </button>

                    </form>

                </div>
            </div>
        </section>


        <!-- ================================================================
             FRIENDS LIST
             ================================================================ -->
        <section>
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h5 fw-bold mb-0">
                        📋 Your Friends
                        <span class="badge bg-info ms-2"><?php echo count($friends); ?></span>
                    </h2>
                </div>
                <div class="card-body px-4 pb-4">

                    <?php if (empty($friends)): ?>
                        <div class="text-center py-5">
                            <div class="display-1 mb-3">👥</div>
                            <p class="text-muted mb-0">
                                No friends added yet. Add your first friend above!
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($friends as $friend): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                👤 <?php echo safeEcho($friend['username']); ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php
                                                $status = strtolower($friend['status']);
                                                if ($status === 'online')
                                                    echo 'bg-success';
                                                elseif ($status === 'playing')
                                                    echo 'bg-warning text-dark';
                                                elseif ($status === 'away')
                                                    echo 'bg-info';
                                                else
                                                    echo 'bg-secondary';
                                                ?>">
                                                    <?php echo safeEcho($friend['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-muted">
                                                <?php echo safeEcho($friend['note']) ?: '-'; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>"
                                                    class="btn btn-sm btn-outline-warning me-1">
                                                    ✏️ Edit
                                                </a>
                                                <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Remove this friend?');">
                                                    🗑️ Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>