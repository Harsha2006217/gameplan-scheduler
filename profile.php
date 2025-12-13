<?php
/**
 * ============================================================================
 * profile.php - PROFIEL BEHEER PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Hier kan de gebruiker zijn profiel beheren door favoriete games toe te voegen,
 * bewerken of verwijderen. Dit is User Story 1: "Profiel aanmaken met favoriete games".
 * 
 * FUNCTIONALITEIT:
 * - Formulier om nieuwe favoriete game toe te voegen
 * - Tabel met bestaande favoriete games
 * - Edit en Delete knoppen per game
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

// Beveiligingscontrole: alleen ingelogde gebruikers
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$favorites = getFavoriteGames($userId);
$error = '';

// Verwerk formulier voor nieuwe favoriete game
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $note = $_POST['note'] ?? '';

    // addFavoriteGame() valideert en slaat op
    $error = addFavoriteGame($userId, $title, $description, $note);

    if (!$error) {
        setMessage('success', 'Favorite game added successfully!');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-4">
        <?php echo getMessage(); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo safeEcho($error); ?></div>
        <?php endif; ?>

        <!-- Profiel Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1><i class="bi bi-person-circle me-2"></i>Your Profile</h1>
                <p class="text-muted">Manage your favorite games</p>
            </div>
        </div>

        <!-- Formulier: Nieuwe Game Toevoegen -->
        <section class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Favorite Game</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="title" class="form-label">Game Title *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="e.g., Fortnite">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="1" maxlength="500"
                                placeholder="What type of game?"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="note" class="form-label">Personal Note</label>
                            <textarea id="note" name="note" class="form-control" rows="1"
                                placeholder="Why is this your favorite?"></textarea>
                        </div>
                    </div>
                    <button type="submit" name="add_favorite" class="btn btn-primary">
                        <i class="bi bi-heart-fill me-1"></i>Add to Favorites
                    </button>
                </form>
            </div>
        </section>

        <!-- Tabel: Favoriete Games -->
        <section>
            <h2><i class="bi bi-heart me-2"></i>Your Favorite Games</h2>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Game Title</th>
                            <th>Description</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($favorites)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No favorite games yet. Add your first one above!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo safeEcho($game['titel']); ?></td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Remove from favorites?');">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
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