<?php
// add_friend.php - Friend Management
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Add new friends and view existing list.

require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$friends = getFriends($userId);
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friendUsername = $_POST['friend_username'] ?? '';
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';
    
    $error = addFriend($userId, $friendUsername, $note, $status);
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
    <title>Friends - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    
    <?php include 'header.php'; ?>

    <main class="container">
        <?php echo getMessage(); ?>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Add Friend Form -->
                <div class="card p-4 mb-4">
                    <h2 class="text-primary">Add New Friend</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">Username</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required maxlength="50" placeholder="Their exact username">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Initial Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Online">Online</option>
                                <option value="Offline" selected>Offline</option>
                                <option value="Away">Away</option>
                                <option value="In-Game">In-Game</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Note (Private)</label>
                            <textarea id="note" name="note" class="form-control" rows="2" placeholder="e.g. Met in Fortnite lobby"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Friend</button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Friends List -->
                <h3>Your Squad</h3>
                <div class="list-group">
                    <?php foreach ($friends as $friend): ?>
                        <div class="list-group-item bg-secondary text-light mb-2 rounded border-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo safeEcho($friend['username']); ?></h5>
                                <small class="text-light"><?php echo safeEcho($friend['status']); ?></small>
                            </div>
                            <p class="mb-1"><?php echo safeEcho($friend['note']); ?></p>
                            <div class="mt-2">
                                <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" class="text-warning me-2 text-decoration-none">Edit</a>
                                <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" class="text-danger text-decoration-none" onclick="return confirm('Are you sure?');">Remove</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>