<?php
/**
 * ============================================================================
 * EDIT_FAVORITE.PHP - EDIT FAVORITE GAME / FAVORIET SPEL BEWERKEN
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH: Edit favorite game's title, description, and personal note.
 * DUTCH: Bewerk favoriet spel's titel, beschrijving, en persoonlijke notitie.
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
    header("Location: profile.php");
    exit;
}

$favorites = getFavoriteGames($userId);
$game = array_filter($favorites, function ($g) use ($id) {
    return $g['game_id'] == $id; });
$game = reset($game);

if (!$game) {
    setMessage('danger', 'Game not found. / Spel niet gevonden.');
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
        setMessage('success', 'Game updated! / Spel bijgewerkt!');
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

    <main class="container mt-5 pt-5">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div><?php endif; ?>

        <section class="mb-5">
            <h2>✏️ Edit Favorite Game / Favoriet Spel Bewerken</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">🎮 Game Title / Speltitel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                value="<?php echo safeEcho($game['titel']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Description / Beschrijving</label>
                            <textarea id="description" name="description" class="form-control" rows="2"
                                maxlength="500"><?php echo safeEcho($game['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">📌 Note / Notitie</label>
                            <textarea id="note" name="note" class="form-control"
                                rows="2"><?php echo safeEcho($game['note']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">💾 Update / Bijwerken</button>
                        <a href="profile.php" class="btn btn-secondary">↩️ Cancel / Annuleren</a>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>