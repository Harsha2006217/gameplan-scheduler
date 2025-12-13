<?php
/**
 * ============================================================================
 * ADD_FRIEND.PHP - ADD FRIEND PAGE / VRIEND TOEVOEGEN PAGINA
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH:
 * This page allows users to add gaming friends by username.
 * Friends can have a status (Online/Offline) and personal notes.
 * Also displays the current friends list with edit/delete options.
 * 
 * DUTCH:
 * Deze pagina laat gebruikers gaming vrienden toevoegen op gebruikersnaam.
 * Vrienden kunnen een status hebben (Online/Offline) en persoonlijke notities.
 * Toont ook de huidige vriendenlijst met bewerk/verwijder opties.
 * 
 * USER STORY: "Add friends for contact"
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$friends = getFriends($userId);
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friendUsername = $_POST['friend_username'] ?? '';
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';

    $error = addFriend($userId, $friendUsername, $note, $status);

    if (!$error) {
        setMessage('success', 'Friend added! / Vriend toegevoegd!');
        header("Location: add_friend.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends - GamePlan Scheduler</title>
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

        <!-- ADD FRIEND FORM -->
        <section class="mb-5">
            <h2>👥 Add Friend / Vriend Toevoegen</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="friend_username" class="form-label">🎮 Friend's Username / Gebruikersnaam Vriend
                                *</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required
                                maxlength="50" placeholder="Their gaming name">
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">📝 Note / Notitie (optional)</label>
                            <textarea id="note" name="note" class="form-control" rows="2"
                                placeholder="e.g., 'Good at Fortnite'"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">🔘 Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                                <option value="Playing">Playing / Speelt</option>
                                <option value="Away">Away / Afwezig</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">➕ Add Friend / Vriend Toevoegen</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FRIENDS LIST -->
        <section class="mb-5">
            <h2>📋 Your Friends / Jouw Vrienden</h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Username / Gebruikersnaam</th>
                            <th>Status</th>
                            <th>Note / Notitie</th>
                            <th>Actions / Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($friends)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary">No friends yet! / Nog geen vrienden!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($friends as $friend): ?>
                                <tr>
                                    <td><?php echo safeEcho($friend['username']); ?></td>
                                    <td>
                                        <span
                                            class="badge <?php echo $friend['status'] === 'Online' ? 'bg-success' : ($friend['status'] === 'Playing' ? 'bg-primary' : 'bg-secondary'); ?>">
                                            <?php echo safeEcho($friend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($friend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>"
                                            class="btn btn-sm btn-warning">✏️ Edit</a>
                                        <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Remove friend?');">🗑️
                                            Delete</a>
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