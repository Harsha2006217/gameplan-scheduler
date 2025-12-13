<?php
/**
 * ============================================================================
 * add_schedule.php - SPEELSCHEMA TOEVOEGEN PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Formulier om een nieuw speelschema toe te voegen (User Story 3).
 * Een schedule plant wanneer je een bepaalde game gaat spelen met vrienden.
 * ============================================================================
 */

require_once 'functions.php';
checkSessionTimeout();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = getUserId();
$error = '';

// Formulier verwerking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gameTitle = $_POST['game_title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $friendsStr = $_POST['friends_str'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = addSchedule($userId, $gameTitle, $date, $time, $friendsStr, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Schedule added successfully!');
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule - GamePlan Scheduler</title>
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

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Add Gaming Schedule</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="return validateScheduleForm();">

                            <!-- Game Titel -->
                            <div class="mb-3">
                                <label for="game_title" class="form-label">
                                    <i class="bi bi-controller me-1"></i>Game Title *
                                </label>
                                <input type="text" id="game_title" name="game_title" class="form-control" required
                                    maxlength="100" placeholder="e.g., Fortnite, Minecraft">
                            </div>

                            <!-- Datum en Tijd -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">
                                        <i class="bi bi-calendar me-1"></i>Date *
                                    </label>
                                    <input type="date" id="date" name="date" class="form-control" required
                                        min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="time" class="form-label">
                                        <i class="bi bi-clock me-1"></i>Time *
                                    </label>
                                    <input type="time" id="time" name="time" class="form-control" required>
                                </div>
                            </div>

                            <!-- Vrienden die meedoen -->
                            <div class="mb-3">
                                <label for="friends_str" class="form-label">
                                    <i class="bi bi-people me-1"></i>Friends Playing With You
                                </label>
                                <input type="text" id="friends_str" name="friends_str" class="form-control"
                                    placeholder="john, mike, sarah (comma-separated)">
                                <small class="text-muted">Enter usernames of friends joining this session</small>
                            </div>

                            <!-- Gedeeld Met -->
                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">
                                    <i class="bi bi-share me-1"></i>Share Schedule With
                                </label>
                                <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                    placeholder="Usernames who can see this schedule">
                            </div>

                            <!-- Knoppen -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Add Schedule
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>