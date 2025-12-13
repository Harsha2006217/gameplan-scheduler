<?php
// edit_favorite.php - Update Favorite Game
// Author: Harsha Kanaparthi
// Date: 30-09-2025
// Description: Change game details or personal note.

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

$favorites = getFavoriteGames($userId);
$game = array_filter($favorites, function($g) use ($id) { return $g['game_id'] == $id; });
$game = reset($game);

if (!$game) {
    setMessage('danger', 'Game not found.');
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
    <title>Edit Favorite - GamePlan Scheduler</title>
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
                    <h2 class="text-primary mb-4">Edit Favorite Game</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Game Title</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100" value="<?php echo safeEcho($game['titel']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="2" maxlength="500"><?php echo safeEcho($game['description']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea id="note" name="note" class="form-control" rows="2"><?php echo safeEcho($game['note']); ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Update Library</button>
                            <a href="profile.php" class="btn btn-secondary">Cancel</a>
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