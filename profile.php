<?php
/**
 * ==========================================================================
 * PROFILE.PHP - PROFIEL BEHEER
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers hun favoriete spellen beheren.
 * Gebruikers kunnen favoriete spellen toevoegen, bekijken, bewerken
 * en verwijderen met persoonlijke notities.
 *
 * Gebruikersverhaal: "Maak een profiel met favoriete spellen"
 * ==========================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$favorieten = getFavoriteGames($userId);
$fout = '';

// Verwerk formulier voor het toevoegen van een favoriet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    $titel = $_POST['title'] ?? '';
    $beschrijving = $_POST['description'] ?? '';
    $notitie = $_POST['note'] ?? '';

    $fout = addFavoriteGame($userId, $titel, $beschrijving, $notitie);

    if (!$fout) {
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
        <?php if ($fout): ?>
            <div class="alert alert-danger"><?php echo safeEcho($fout); ?></div>
        <?php endif; ?>

        <!-- FAVORIET SPEL TOEVOEGEN FORMULIER -->
        <section class="mb-5">
            <h2>➕ Favoriet Spel Toevoegen</h2>
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
                                placeholder="Persoonlijke notities, bijv. 'Mijn favoriete spel!'"></textarea>
                        </div>
                        <button type="submit" name="add_favorite" class="btn btn-primary">➕ Toevoegen</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- JOUW FAVORIETEN TABEL -->
        <section class="mb-5">
            <h2>⭐ Jouw Favorieten</h2>
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
                        <?php if (empty($favorieten)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary">Nog geen favorieten!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($favorieten as $spel): ?>
                                <tr>
                                    <td><?php echo safeEcho($spel['titel']); ?></td>
                                    <td><?php echo safeEcho($spel['description']); ?></td>
                                    <td><?php echo safeEcho($spel['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $spel['game_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $spel['game_id']; ?>"
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