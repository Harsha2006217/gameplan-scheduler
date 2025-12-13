<?php
// profile.php - User Profile & Favorites
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Manage "Favorite Games". CRUD (Create, Read, Update, Delete) functionality.

require_once 'functions.php';
checkSessionTimeout();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$favorites = getFavoriteGames($userId);
$error = '';

// Handle Add Favorite Form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $note = $_POST['note'] ?? '';

    $error = addFavoriteGame($userId, $title, $description, $note);
    if (!$error) {
        setMessage('success', 'Game added to favorites!');
        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light d-flex flex-column min-vh-100">

    <?php include 'header.php'; ?>

    <main class="container">
        <?php echo getMessage(); ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <h2 class="text-primary mb-3">Add Favorite Game</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Game Title *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="e.g. Minecraft">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea id="description" name="description" class="form-control" rows="2"
                                maxlength="500"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">My Note</label>
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="e.g. I play this on weekends"></textarea>
                        </div>
                        <!-- Name attribute needed to distinguish submit button -->
                        <button type="submit" name="add_favorite" class="btn btn-primary">Add to Library</button>
                    </form>
                </div>

                <div class="mt-4">
                    <h3>My Library</h3>
                    <?php if (empty($favorites)): ?>
                        <p class="text-muted">No games added yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($favorites as $game): ?>
                                <div
                                    class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1 text-info"><?php echo safeEcho($game['titel']); ?></h5>
                                        <p class="mb-1"><?php echo safeEcho($game['description']); ?></p>
                                        <small class="text-muted">Note: <?php echo safeEcho($game['note']); ?></small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-sm btn-outline-warning">Edit</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Remove from favorites?');">Remove</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>