<?php
// ============================================================================
// EDIT_FRIEND.PHP - Edit Friend Details
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// Allows users to edit friend details: username, note, and status.
// ============================================================================


require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$id = $_GET['id'] ?? 0;

if (!is_numeric($id)) {
    header("Location: add_friend.php");
    exit;
}

// Get friend and verify ownership
$friends = getFriends($userId);
$friend = array_filter($friends, function ($f) use ($id) {
    return $f['friend_id'] == $id;
});
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Friend - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">✏️ Edit Friend</h1>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">
                                <label for="friend_username" class="form-label">👤 Username *</label>
                                <input type="text" id="friend_username" name="friend_username"
                                    class="form-control bg-dark text-light border-secondary" required maxlength="50"
                                    value="<?php echo safeEcho($friend['username']); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">💬 Note</label>
                                <textarea id="note" name="note" class="form-control bg-dark text-light border-secondary"
                                    rows="2"><?php echo safeEcho($friend['note']); ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label">📊 Status</label>
                                <select id="status" name="status"
                                    class="form-select bg-dark text-light border-secondary">
                                    <option value="Offline" <?php if ($friend['status'] == 'Offline')
                                        echo 'selected'; ?>>
                                        Offline</option>
                                    <option value="Online" <?php if ($friend['status'] == 'Online')
                                        echo 'selected'; ?>>
                                        Online</option>
                                    <option value="Playing" <?php if ($friend['status'] == 'Playing')
                                        echo 'selected'; ?>>
                                        Playing</option>
                                    <option value="Away" <?php if ($friend['status'] == 'Away')
                                        echo 'selected'; ?>>Away
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg px-5">💾 Update</button>
                                <a href="add_friend.php" class="btn btn-outline-secondary btn-lg">Cancel</a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>