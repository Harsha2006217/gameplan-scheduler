<?php
/**
 * ============================================================================
 * edit_friend.php - VRIEND BEWERKEN PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Formulier om vriend gegevens te bewerken.
 * ============================================================================
 */

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

$friends = getFriends($userId);
$friend = array_filter($friends, function ($f) use ($id) {
    return $f['friend_id'] == $id; });
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-4">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div><?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Friend</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="friend_username" class="form-label">Username *</label>
                                <input type="text" id="friend_username" name="friend_username" class="form-control"
                                    required maxlength="50" value="<?php echo safeEcho($friend['username']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="Offline" <?php if ($friend['status'] == 'Offline')
                                        echo 'selected'; ?>>
                                        Offline</option>
                                    <option value="Online" <?php if ($friend['status'] == 'Online')
                                        echo 'selected'; ?>>
                                        Online</option>
                                    <option value="Gaming" <?php if ($friend['status'] == 'Gaming')
                                        echo 'selected'; ?>>
                                        Gaming</option>
                                    <option value="Away" <?php if ($friend['status'] == 'Away')
                                        echo 'selected'; ?>>Away
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="note" class="form-label">Note</label>
                                <textarea id="note" name="note" class="form-control"
                                    rows="2"><?php echo safeEcho($friend['note']); ?></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle me-1"></i>Update
                                </button>
                                <a href="add_friend.php" class="btn btn-outline-secondary">Cancel</a>
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