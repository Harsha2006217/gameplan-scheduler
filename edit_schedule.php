<?php
/**
 * ==========================================================================
 * EDIT_SCHEDULE.PHP - SCHEMA BEWERKEN PAGINA
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bewerk een bestaand gaming speelschema.
 * Bevat validatie voor speltitel, datum en tijd.
 *
 * Gebruikersverhaal: "Deel speelschema's met vrienden in een kalender"
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
    header("Location: index.php");
    exit;
}

// Haal het speelschema op
$schemas = getSchedules($userId);
$schema = array_filter($schemas, function ($s) use ($id) {
    return $s['schedule_id'] == $id;
});
$schema = reset($schema);

if (!$schema) {
    setMessage('danger', 'Schema niet gevonden.');
    header("Location: index.php");
    exit;
}

$error = '';

// Verwerk formulier verzending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $spelTitel = $_POST['game_title'] ?? '';
    $datum = $_POST['date'] ?? '';
    $tijd = $_POST['time'] ?? '';
    $vriendenStr = $_POST['friends_str'] ?? '';
    $gedeeldMetStr = $_POST['shared_with_str'] ?? '';

    $error = editSchedule($userId, $id, $spelTitel, $datum, $tijd, $vriendenStr, $gedeeldMetStr);

    if (!$error) {
        setMessage('success', 'Schema bijgewerkt!');
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schema Bewerken - GamePlan Scheduler</title>
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

        <section class="mb-5">
            <h2>✏️ Schema Bewerken</h2>
            <div class="card">
                <div class="card-body">
                    <form method="POST" onsubmit="return validateScheduleForm();">

                        <!-- Speltitel -->
                        <div class="mb-3">
                            <label for="game_title" class="form-label">🎮 Speltitel *</label>
                            <input type="text" id="game_title" name="game_title" class="form-control" required
                                maxlength="100" value="<?php echo safeEcho($schema['game_titel']); ?>">
                        </div>

                        <!-- Datum -->
                        <div class="mb-3">
                            <label for="date" class="form-label">📆 Datum *</label>
                            <input type="date" id="date" name="date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>" value="<?php echo safeEcho($schema['date']); ?>">
                        </div>

                        <!-- Tijd -->
                        <div class="mb-3">
                            <label for="time" class="form-label">⏰ Tijd *</label>
                            <input type="time" id="time" name="time" class="form-control" required
                                value="<?php echo safeEcho($schema['time']); ?>">
                        </div>

                        <!-- Meespelende vrienden -->
                        <div class="mb-3">
                            <label for="friends_str" class="form-label">👥 Meespelende Vrienden</label>
                            <input type="text" id="friends_str" name="friends_str" class="form-control"
                                value="<?php echo safeEcho($schema['friends']); ?>">
                            <small class="text-secondary">Komma-gescheiden gebruikersnamen</small>
                        </div>

                        <!-- Gedeeld met -->
                        <div class="mb-3">
                            <label for="shared_with_str" class="form-label">👀 Gedeeld Met</label>
                            <input type="text" id="shared_with_str" name="shared_with_str" class="form-control"
                                value="<?php echo safeEcho($schema['shared_with']); ?>">
                            <small class="text-secondary">Wie kan dit schema zien</small>
                        </div>

                        <button type="submit" class="btn btn-primary">💾 Bijwerken</button>
                        <a href="index.php" class="btn btn-secondary">↩️ Annuleren</a>

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