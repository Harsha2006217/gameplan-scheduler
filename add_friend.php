<?php
/**
 * ==========================================================================
 * ADD_FRIEND.PHP - VRIEND TOEVOEGEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Deze pagina laat gebruikers gaming vrienden toevoegen op gebruikersnaam.
 * Vrienden kunnen een status hebben (Online/Offline) en persoonlijke notities.
 * Toont ook de huidige vriendenlijst met bewerk/verwijder opties.
 *
 * Gebruikersverhaal: "Voeg vrienden toe voor contact"
 * ==========================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$vrienden = getFriends($userId);
$fout = '';

// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vriendUsername = $_POST['friend_username'] ?? '';
    $notitie = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';

    $fout = addFriend($userId, $vriendUsername, $notitie, $status);

    if (!$fout) {
        setMessage('success', 'Vriend toegevoegd!');
        header("Location: add_friend.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vrienden - GamePlan Scheduler</title>
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

        <!-- VRIEND TOEVOEGEN FORMULIER -->
        <section class="mb-5">
            <h2>👥 Vriend Toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">🎮 Gebruikersnaam Vriend *</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required
                                maxlength="50" placeholder="Gaming naam van je vriend">
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">📝 Notitie (optioneel)</label>
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="bijv. 'Goed in Fortnite'"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">🔘 Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                                <option value="Playing">Aan het spelen</option>
                                <option value="Away">Afwezig</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">➕ Vriend Toevoegen</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- VRIENDENLIJST -->
        <section class="mb-5">
            <h2>📋 Jouw Vrienden</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Gebruikersnaam</th>
                            <th>Status</th>
                            <th>Notitie</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vrienden)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary">Nog geen vrienden!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vrienden as $vriend): ?>
                                <tr>
                                    <td><?php echo safeEcho($vriend['username']); ?></td>
                                    <td>
                                        <span
                                            class="badge <?php echo $vriend['status'] === 'Online' ? 'bg-success' : ($vriend['status'] === 'Playing' ? 'bg-primary' : 'bg-secondary'); ?>">
                                            <?php echo safeEcho($vriend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($vriend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $vriend['friend_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=friend&id=<?php echo $vriend['friend_id']; ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Vriend verwijderen?');">🗑️
                                            Verwijderen</a>
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