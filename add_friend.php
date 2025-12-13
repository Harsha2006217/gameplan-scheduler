<?php
/**
 * ============================================================================
 * add_friend.php - VRIENDEN TOEVOEGEN PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Formulier om vrienden toe te voegen (User Story 2).
 * Toont ook de lijst van bestaande vrienden met CRUD opties.
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

// Formulier verwerking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friendUsername = $_POST['friend_username'] ?? '';
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Offline';

    $error = addFriend($userId, $friendUsername, $note, $status);

    if (!$error) {
        setMessage('success', 'Friend added successfully!');
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

        <h1><i class="bi bi-people me-2"></i>Friends</h1>

        <!-- Formulier: Vriend Toevoegen -->
        <section class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Add New Friend</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="friend_username" class="form-label">Username *</label>
                            <input type="text" id="friend_username" name="friend_username" class="form-control" required
                                maxlength="50" placeholder="Friend's gamer tag">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                                <option value="Gaming">Gaming</option>
                                <option value="Away">Away</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea id="note" name="note" class="form-control" rows="1"
                                placeholder="How do you know them?"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>Add Friend
                    </button>
                </form>
            </div>
        </section>

        <!-- Tabel: Vrienden Lijst -->
        <section>
            <h2><i class="bi bi-people me-2"></i>Your Friends (<?php echo count($friends); ?>)</h2>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person me-1"></i>Username</th>
                            <th><i class="bi bi-circle-fill me-1"></i>Status</th>
                            <th><i class="bi bi-sticky me-1"></i>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($friends)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No friends yet. Add your first gaming buddy above!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($friends as $friend): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo safeEcho($friend['username']); ?></td>
                                    <td>
                                        <?php
                                        $statusClass = match ($friend['status']) {
                                            'Online' => 'bg-success',
                                            'Gaming' => 'bg-primary',
                                            'Away' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <?php echo safeEcho($friend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($friend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Remove this friend?');">
                                            <i class="bi bi-trash"></i>
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