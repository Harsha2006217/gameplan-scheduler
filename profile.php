<?php
/**
 * ============================================================================
 * PROFILE.PHP - PROFILE MANAGEMENT / PROFIEL BEHEER
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page allows users to manage their favorite games.
 * Users can add, view, edit, and delete their favorite games with notes.
 * 
 * DUTCH:
 * Deze pagina laat gebruikers hun favoriete spellen beheren.
 * Gebruikers kunnen favoriete spellen toevoegen, bekijken, bewerken, en verwijderen met notities.
 * 
 * USER STORY: "Create a profile with favorite games"
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$favorites = getFavoriteGames($userId);
$error = '';

// Process form submission for adding favorite
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $note = $_POST['note'] ?? '';

    $error = addFavoriteGame($userId, $title, $description, $note);

    if (!$error) {
        setMessage('success', 'Favorite game added! / Favoriet spel toegevoegd!');
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
    <title>Profile - GamePlan Scheduler</title>
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

        <!-- ADD FAVORITE GAME FORM -->
        <section class="mb-5">
            <h2>➕ Add Favorite Game / Favoriet Spel Toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">🎮 Game Title / Speltitel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="Fortnite, Minecraft, etc.">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Description / Beschrijving (optional)</label>
                            <textarea id="description" name="description" class="form-control" rows="2" maxlength="500"
                                placeholder="What's this game about?"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">📌 Note / Notitie (optional)</label>
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="Personal notes, e.g., 'My main game!'"></textarea>
                        </div>
                        <button type="submit" name="add_favorite" class="btn btn-primary">➕ Add / Toevoegen</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- YOUR FAVORITES TABLE -->
        <section class="mb-5">
            <h2>⭐ Your Favorites / Jouw Favorieten</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title / Titel</th>
                            <th>Description / Beschrijving</th>
                            <th>Note / Notitie</th>
                            <th>Actions / Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($favorites)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary">No favorites yet! / Nog geen favorieten!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td><?php echo safeEcho($game['titel']); ?></td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Edit</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Remove from favorites?');">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>