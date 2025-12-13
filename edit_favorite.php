<?php
// ============================================================================
// EDIT_FAVORITE.PHP - Edit Favorite Game
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// Allows users to edit their favorite game details: title, description, note.
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
    header("Location: profile.php");
    exit;
}

// Get favorite game and verify ownership
$favorites = getFavoriteGames($userId);
$game = array_filter($favorites, function ($g) use ($id) {
    return $g['game_id'] == $id;
});
$game = reset($game);

if (!$game) {
    setMessage('danger', 'Game not found or no permission.');
    header("Location: profile.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $note = $_POST['note'] ?? '';

    $error = updateFavoriteGame($userId, $id, $title, $description, $note);

    if (!$error) {
        setMessage('success', 'Favorite game updated!');
        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Favorite - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5 pb-5">

        <?php echo getMessage(); ?>

        <h1 class="h3 fw-bold mb-4">✏️ Edit Favorite Game</h1>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card bg-secondary border-0 rounded-4 shadow">
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">
                                <label for="title" class="form-label">🎮 Game Title *</label>
                                <input type="text" id="title" name="title"
                                    class="form-control bg-dark text-light border-secondary" required maxlength="100"
                                    value="<?php echo safeEcho($game['titel']); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">📝 Description</label>
                                <textarea id="description" name="description"
                                    class="form-control bg-dark text-light border-secondary" rows="2"
                                    maxlength="500"><?php echo safeEcho($game['description']); ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="note" class="form-label">💬 Personal Note</label>
                                <textarea id="note" name="note" class="form-control bg-dark text-light border-secondary"
                                    rows="2"><?php echo safeEcho($game['note']); ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg px-5">💾 Update</button>
                                <a href="profile.php" class="btn btn-outline-secondary btn-lg">Cancel</a>
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