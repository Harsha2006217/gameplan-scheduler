<?php
/**
 * ==========================================================================
 * EDIT_FRIEND.PHP - VRIEND BEWERKEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk de gebruikersnaam, notitie en status van een vriend.
 * ==========================================================================
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

// Haal vriend gegevens op
$vrienden = getFriends($userId);
$vriend = array_filter($vrienden, function ($f) use ($id) {
    return $f['friend_id'] == $id;
});
$vriend = reset($vriend);

if (!$vriend) {
    setMessage('danger', 'Vriend niet gevonden.');
    header("Location: add_friend.php");
    exit;
}

$fout = '';

// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vriendGebruikersnaam = $_POST['friend_username'] ?? '';
    $notitie = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';

    $fout = updateFriend($userId, $id, $vriendGebruikersnaam, $notitie, $status);

    if (!$fout) {
        setMessage('success', 'Vriend bijgewerkt!');
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
    <title>Vriend Bewerken - GamePlan Scheduler</title>
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

        <section class="mb-5">
            <h2>✏️ Vriend Bewerken</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">🎮 Gebruikersnaam *</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required
                                maxlength="50" value="<?php echo safeEcho($vriend['username']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">📝 Notitie</label>
                            <textarea id="note" name="note" class="form-control"
                                rows="2"><?php echo safeEcho($vriend['note']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">🔘 Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Offline" <?php if ($vriend['status'] === 'Offline')
                                    echo 'selected'; ?>>
                                    Offline</option>
                                <option value="Online" <?php if ($vriend['status'] === 'Online')
                                    echo 'selected'; ?>>
                                    Online</option>
                                <option value="Playing" <?php if ($vriend['status'] === 'Playing')
                                    echo 'selected'; ?>>Aan
                                    het spelen</option>
                                <option value="Away" <?php if ($vriend['status'] === 'Away')
                                    echo 'selected'; ?>>Afwezig
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">💾 Bijwerken</button>
                        <a href="add_friend.php" class="btn btn-secondary">↩️ Annuleren</a>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>