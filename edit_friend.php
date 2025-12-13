<?php
// edit_friend.php - Update Friend Details
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Change friend status, note, or username.

require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$id = $_GET['id'] ?? 0;

// Security: Check if numeric ID to prevent SQL errors
if (!is_numeric($id)) {
    header("Location: add_friend.php");
    exit;
}

// Fetch existing data to pre-fill the form
$friends = getFriends($userId);
// Filter array to find the specific friend matching ID
// We use array_filter to find the item, and reset to get the first result.
$friend = array_filter($friends, function($f) use ($id) { return $f['friend_id'] == $id; });
$friend = reset($friend);

if (!$friend) {
    setMessage('danger', 'Friend not found.');
    header("Location: add_friend.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friendUsername = $_POST['friend_username'] ?? '';
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';
    
    $error = updateFriend($userId, $id, $friendUsername, $note, $status);
    if (!$error) {
        setMessage('success', 'Friend details updated!');
        header("Location: add_friend.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Friend - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light d-flex flex-column min-vh-100">
    
    <?php include 'header.php'; ?>

    <main class="container">
        <?php echo getMessage(); ?>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2 class="text-primary">Edit Friend</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">Username</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required maxlength="50" value="<?php echo safeEcho($friend['username']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Online" <?php if($friend['status']=='Online') echo 'selected'; ?>>Online</option>
                                <option value="Offline" <?php if($friend['status']=='Offline') echo 'selected'; ?>>Offline</option>
                                <option value="Away" <?php if($friend['status']=='Away') echo 'selected'; ?>>Away</option>
                                <option value="In-Game" <?php if($friend['status']=='In-Game') echo 'selected'; ?>>In-Game</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea id="note" name="note" class="form-control" rows="2"><?php echo safeEcho($friend['note']); ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Update Details</button>
                            <a href="add_friend.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>