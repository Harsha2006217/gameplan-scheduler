<?php
/**
 * ==========================================================================
 * INDEX.PHP - DASHBOARD / KALENDER OVERZICHT
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Hoofd dashboard pagina met vrienden, favorieten, schema's, evenementen
 * en kalender. Dit is de homepagina na het inloggen.
 *
 * Functies:
 * - Vriendenlijst met status
 * - Favoriete spellen tabel
 * - Schema's met sorteer opties
 * - Evenementen met sorteer opties
 * - Gecombineerd kalender overzicht
 * - Herinnering pop-ups (JavaScript)
 * ==========================================================================
 */

require_once 'functions.php';

// Controleer sessie timeout (30 min inactiviteit)
checkSessionTimeout();

// Redirect naar login als niet ingelogd
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Haal huidige gebruiker ID op
$userId = getUserId();

// Werk laatste activiteit bij voor sessie tracking
updateLastActivity(getDBConnection(), $userId);

// Haal sorteer parameters uit URL
$sorteerSchemas = $_GET['sort_schedules'] ?? 'date ASC';
$sorteerEvenementen = $_GET['sort_events'] ?? 'date ASC';

// Haal alle gebruikersdata op
$vrienden = getFriends($userId);
$favorieten = getFavoriteGames($userId);
$schemas = getSchedules($userId, $sorteerSchemas);
$evenementen = getEvents($userId, $sorteerEvenementen);
$kalenderItems = getCalendarItems($userId);
$herinneringen = getReminders($userId);

// Afhandeling uitloggen
if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GamePlan Scheduler Dashboard - Jouw gaming kalender en planning beheerder">
    <title>Dashboard - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>

    <main class="container mt-5 pt-5">
        <!-- Sessie berichten (succes/fout) -->
        <?php echo getMessage(); ?>

        <!-- SECTIE 1: VRIENDENLIJST -->
        <section class="mb-5">
            <h2>👥 Vriendenlijst</h2>
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
                            <tr><td colspan="4" class="text-center text-secondary">Nog geen vrienden. Voeg er een toe!</td></tr>
                        <?php else: ?>
                            <?php foreach ($vrienden as $vriend): ?>
                                <tr>
                                    <td><?php echo safeEcho($vriend['username']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $vriend['status'] === 'Online' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo safeEcho($vriend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($vriend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $vriend['friend_id']; ?>" class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=friend&id=<?php echo $vriend['friend_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je deze vriend wilt verwijderen?');">🗑️ Verwijderen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_friend.php" class="btn btn-primary">➕ Vriend Toevoegen</a>
        </section>

        <!-- SECTIE 2: FAVORIETE SPELLEN -->
        <section class="mb-5">
            <h2>🎮 Favoriete Spellen</h2>
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
                            <tr><td colspan="4" class="text-center text-secondary">Nog geen favorieten. Voeg er een toe!</td></tr>
                        <?php else: ?>
                            <?php foreach ($favorieten as $spel): ?>
                                <tr>
                                    <td><?php echo safeEcho($spel['titel']); ?></td>
                                    <td><?php echo safeEcho($spel['description']); ?></td>
                                    <td><?php echo safeEcho($spel['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $spel['game_id']; ?>" class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $spel['game_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Uit favorieten verwijderen?');">🗑️ Verwijderen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="profile.php" class="btn btn-primary">➕ Favoriet Toevoegen</a>
        </section>

        <!-- SECTIE 3: SPEELSCHEMA'S (met sorteer opties) -->
        <section class="mb-5">
            <h2>
                📅 Speelschema's
                <a href="?sort_schedules=date ASC" class="btn btn-sm btn-light ms-2">📆 Datum ↑</a>
                <a href="?sort_schedules=date DESC" class="btn btn-sm btn-light">📆 Datum ↓</a>
            </h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Spel</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Vrienden</th>
                            <th>Gedeeld met</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($schemas)): ?>
                            <tr><td colspan="6" class="text-center text-secondary">Nog geen schema's.</td></tr>
                        <?php else: ?>
                            <?php foreach ($schemas as $schema): ?>
                                <tr>
                                    <td><?php echo safeEcho($schema['game_titel']); ?></td>
                                    <td><?php echo safeEcho($schema['date']); ?></td>
                                    <td><?php echo safeEcho($schema['time']); ?></td>
                                    <td><?php echo safeEcho($schema['friends']); ?></td>
                                    <td><?php echo safeEcho($schema['shared_with']); ?></td>
                                    <td>
                                        <a href="edit_schedule.php?id=<?php echo $schema['schedule_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="delete.php?type=schedule&id=<?php echo $schema['schedule_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit wilt verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_schedule.php" class="btn btn-primary">➕ Schema Toevoegen</a>
        </section>

        <!-- SECTIE 4: EVENEMENTEN (met sorteer opties) -->
        <section class="mb-5">
            <h2>
                🎯 Evenementen
                <a href="?sort_events=date ASC" class="btn btn-sm btn-light ms-2">📆 Datum ↑</a>
                <a href="?sort_events=date DESC" class="btn btn-sm btn-light">📆 Datum ↓</a>
            </h2>
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Beschrijving</th>
                            <th>Herinnering</th>
                            <th>Link</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($evenementen)): ?>
                            <tr><td colspan="7" class="text-center text-secondary">Nog geen evenementen.</td></tr>
                        <?php else: ?>
                            <?php foreach ($evenementen as $evenement): ?>
                                <tr>
                                    <td><?php echo safeEcho($evenement['title']); ?></td>
                                    <td><?php echo safeEcho($evenement['date']); ?></td>
                                    <td><?php echo safeEcho($evenement['time']); ?></td>
                                    <td><?php echo safeEcho(substr($evenement['description'] ?? '', 0, 50)); ?>...</td>
                                    <td><span class="badge bg-info"><?php echo safeEcho($evenement['reminder']); ?></span></td>
                                    <td>
                                        <?php if (!empty($evenement['external_link'])): ?>
                                            <a href="<?php echo safeEcho($evenement['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info">🔗 Openen</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $evenement['event_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="delete.php?type=event&id=<?php echo $evenement['event_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit wilt verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_event.php" class="btn btn-success">➕ Evenement Toevoegen</a>
        </section>

        <!-- SECTIE 5: KALENDER OVERZICHT -->
        <section class="mb-5">
            <h2>📆 Kalender Overzicht</h2>
            <div class="row">
                <?php if (empty($kalenderItems)): ?>
                    <div class="col-12">
                        <p class="text-secondary text-center">Geen komende items. Voeg schema's of evenementen toe!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($kalenderItems as $onderdeel): ?>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-cyan">
                                        <?php echo safeEcho($onderdeel['title'] ?? $onderdeel['game_titel']); ?>
                                    </h5>
                                    <p class="mb-1">
                                        <strong>📅</strong> <?php echo safeEcho($onderdeel['date']); ?>
                                        <strong>⏰</strong> <?php echo safeEcho($onderdeel['time']); ?>
                                    </p>
                                    <?php if (!empty($onderdeel['description'])): ?>
                                        <p class="text-secondary small"><?php echo safeEcho(substr($onderdeel['description'], 0, 100)); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($onderdeel['reminder']) && $onderdeel['reminder'] !== 'none'): ?>
                                        <span class="badge bg-warning text-dark">🔔 <?php echo safeEcho($onderdeel['reminder']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($onderdeel['external_link'])): ?>
                                        <a href="<?php echo safeEcho($onderdeel['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info mt-2">🔗 Link</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

    <!-- Herinnering pop-ups -->
    <script>
        const herinneringen = <?php echo json_encode($herinneringen); ?>;
        herinneringen.forEach(herinnering => {
            alert(`🔔 Herinnering: ${herinnering['title']} om ${herinnering['time']}`);
        });
    </script>
</body>
</html>
