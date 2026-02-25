<?php
/**
 * ============================================================================
 * PROFILE.PHP - PROFIEL BEHEER
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers hun favoriete spellen beheren.
 * Gebruikers kunnen favoriete spellen toevoegen, bekijken, bewerken en verwijderen.
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId    = getUserId();
$favorites = getFavoriteGames($userId);
$error     = '';

// Verwerk het formulier voor het toevoegen van een favoriet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $title       = $_POST['title']       ?? '';
    $description = $_POST['description'] ?? '';
    $note        = $_POST['note']        ?? '';

    $error = addFavoriteGame($userId, $title, $description, $note);

    if (!$error) {
        setMessage('success', 'Favoriet spel toegevoegd!');
        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel - GamePlan Scheduler</title>
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

        <!-- Formulier: favoriet spel toevoegen -->
        <section class="mb-5">
            <h2>➕ Favoriet spel toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">🎮 Speltitel *</label>
                            <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                placeholder="Fortnite, Minecraft, etc.">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">📝 Beschrijving (optioneel)</label>
                            <textarea id="description" name="description" class="form-control" rows="2" maxlength="500"
                                placeholder="Waar gaat dit spel over?"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">📌 Notitie (optioneel)</label>
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="Persoonlijke notitie, bijv. 'Mijn hoofdspel!'"></textarea>
                        </div>
                        <button type="submit" name="add_favorite" class="btn btn-primary">➕ Toevoegen</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Overzicht: jouw favorieten -->
        <section class="mb-5">
            <h2>⭐ Jouw favorieten</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Beschrijving</th>
                            <th>Notitie</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($favorites)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary">Nog geen favorieten!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td><?php echo safeEcho($game['titel']); ?></td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Uit favorieten verwijderen?');">🗑️ Verwijderen</a>
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