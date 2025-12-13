<?php
/**
 * ============================================================================
 * add_event.php - EVENEMENT TOEVOEGEN PAGINA
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Formulier om een nieuw evenement toe te voegen (User Story 4).
 * Evenementen kunnen toernooien, streams, game releases, etc. zijn.
 * 
 * VELDEN:
 * - Titel (verplicht)
 * - Datum (toekomst)
 * - Tijd
 * - Beschrijving
 * - Herinnering (geen/1 uur/1 dag)
 * - Externe link
 * - Gedeeld met (vrienden)
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
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $description = $_POST['description'] ?? '';
    $reminder = $_POST['reminder'] ?? 'none';
    $externalLink = $_POST['external_link'] ?? '';
    $sharedWithStr = $_POST['shared_with_str'] ?? '';

    $error = addEvent($userId, $title, $date, $time, $description, $reminder, $externalLink, $sharedWithStr);

    if (!$error) {
        setMessage('success', 'Event added successfully!');
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
    <title>Add Event - GamePlan Scheduler</title>
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
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Add New Event</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="return validateEventForm();">

                            <!-- Titel -->
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="bi bi-type me-1"></i>Event Title *
                                </label>
                                <input type="text" id="title" name="title" class="form-control" required maxlength="100"
                                    placeholder="e.g., Fortnite Tournament">
                            </div>

                            <!-- Datum en Tijd op één rij -->
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

                            <!-- Beschrijving -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>Description
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="3"
                                    maxlength="500" placeholder="What's this event about?"></textarea>
                            </div>

                            <!-- Herinnering -->
                            <div class="mb-3">
                                <label for="reminder" class="form-label">
                                    <i class="bi bi-bell me-1"></i>Reminder
                                </label>
                                <select id="reminder" name="reminder" class="form-select">
                                    <option value="none">No reminder</option>
                                    <option value="1_hour">1 Hour Before</option>
                                    <option value="1_day">1 Day Before</option>
                                </select>
                            </div>

                            <!-- Externe Link -->
                            <div class="mb-3">
                                <label for="external_link" class="form-label">
                                    <i class="bi bi-link-45deg me-1"></i>External Link (Optional)
                                </label>
                                <input type="url" id="external_link" name="external_link" class="form-control"
                                    placeholder="https://tournament.example.com">
                            </div>

                            <!-- Gedeeld Met -->
                            <div class="mb-4">
                                <label for="shared_with_str" class="form-label">
                                    <i class="bi bi-share me-1"></i>Share with Friends
                                </label>
                                <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                    placeholder="john, mike, sarah (comma-separated)">
                                <small class="text-muted">Enter usernames separated by commas</small>
                            </div>

                            <!-- Knoppen -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Add Event
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