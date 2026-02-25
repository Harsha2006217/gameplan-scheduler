<?php
/**
 * ============================================================================
 * INDEX.PHP - DASHBOARD / KALENDEROVERZICHT
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Hoofd-dashboardpagina met vrienden, favorieten, schema's, evenementen
 * en kalender. Dit is de homepagina na het inloggen — het centrale punt
 * van de applicatie.
 *
 * FUNCTIES:
 * - Vriendenlijst met status
 * - Tabel met favoriete spellen
 * - Schema's met sorteermogelijkheid
 * - Evenementen met sorteermogelijkheid
 * - Gecombineerd kalenderoverzicht
 * - Herinnerings-pop-ups (JavaScript)
 * ============================================================================
 */

require_once 'functions.php';

// Controleer sessie-timeout (30 min. inactiviteit)
checkSessionTimeout();

// Stuur door naar loginpagina als niet ingelogd
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Haal het huidige gebruikers-ID op
$userId = getUserId();

// Werk de laatste activiteit bij voor sessietracking
updateLastActivity(getDBConnection(), $userId);

// Haal sorteerparameters op uit de URL
$sortSchedules = $_GET['sort_schedules'] ?? 'date ASC';
$sortEvents    = $_GET['sort_events']    ?? 'date ASC';

// Haal alle gebruikersdata op
$friends       = getFriends($userId);
$favorites     = getFavoriteGames($userId);
$schedules     = getSchedules($userId, $sortSchedules);
$events        = getEvents($userId, $sortEvents);
$calendarItems = getCalendarItems($userId);
$reminders     = getReminders($userId);

// Verwerk uitloggen
if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GamePlan Scheduler Dashboard - Jouw gaming-kalender en schema-beheer">
    <title>Dashboard - GamePlan Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">
    <?php include 'header.php'; ?>
    
    <main class="container mt-5 pt-5">
        <!-- Sessieberichten (succes/fout) -->
        <?php echo getMessage(); ?>
        
        <!-- ================================================================
             SECTIE 1: VRIENDENLIJST
             ================================================================ -->
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
                        <?php if (empty($friends)): ?>
                            <tr><td colspan="4" class="text-center text-secondary">Nog geen vrienden. Voeg er een toe!</td></tr>
                        <?php else: ?>
                            <?php foreach ($friends as $friend): ?>
                                <tr>
                                    <td><?php echo safeEcho($friend['username']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $friend['status'] === 'Online' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo safeEcho($friend['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safeEcho($friend['note']); ?></td>
                                    <td>
                                        <a href="edit_friend.php?id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=friend&id=<?php echo $friend['friend_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je deze vriend wilt verwijderen?');">🗑️ Verwijderen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_friend.php" class="btn btn-primary">➕ Vriend toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTIE 2: FAVORIETE SPELLEN
             ================================================================ -->
        <section class="mb-5">
            <h2>🎮 Favoriete spellen</h2>
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
                            <tr><td colspan="4" class="text-center text-secondary">Nog geen favorieten. Voeg er een toe!</td></tr>
                        <?php else: ?>
                            <?php foreach ($favorites as $game): ?>
                                <tr>
                                    <td><?php echo safeEcho($game['titel']); ?></td>
                                    <td><?php echo safeEcho($game['description']); ?></td>
                                    <td><?php echo safeEcho($game['note']); ?></td>
                                    <td>
                                        <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>" class="btn btn-sm btn-warning">✏️ Bewerken</a>
                                        <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Uit favorieten verwijderen?');">🗑️ Verwijderen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="profile.php" class="btn btn-primary">➕ Favoriet toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTIE 3: SCHEMA'S (met sorteermogelijkheid)
             ================================================================ -->
        <section class="mb-5">
            <h2>
                📅 Schema's
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
                        <?php if (empty($schedules)): ?>
                            <tr><td colspan="6" class="text-center text-secondary">Nog geen schema's.</td></tr>
                        <?php else: ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?php echo safeEcho($schedule['game_titel']); ?></td>
                                    <td><?php echo safeEcho($schedule['date']); ?></td>
                                    <td><?php echo safeEcho($schedule['time']); ?></td>
                                    <td><?php echo safeEcho($schedule['friends']); ?></td>
                                    <td><?php echo safeEcho($schedule['shared_with']); ?></td>
                                    <td>
                                        <a href="edit_schedule.php?id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="delete.php?type=schedule&id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_schedule.php" class="btn btn-primary">➕ Schema toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTIE 4: EVENEMENTEN (met sorteermogelijkheid)
             ================================================================ -->
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
                        <?php if (empty($events)): ?>
                            <tr><td colspan="7" class="text-center text-secondary">Nog geen evenementen.</td></tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo safeEcho($event['title']); ?></td>
                                    <td><?php echo safeEcho($event['date']); ?></td>
                                    <td><?php echo safeEcho($event['time']); ?></td>
                                    <td><?php echo safeEcho(substr($event['description'] ?? '', 0, 50)); ?>...</td>
                                    <td><span class="badge bg-info"><?php echo safeEcho($event['reminder']); ?></span></td>
                                    <td>
                                        <?php if (!empty($event['external_link'])): ?>
                                            <a href="<?php echo safeEcho($event['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info">🔗 Openen</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-warning">✏️</a>
                                        <a href="delete.php?type=event&id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Verwijderen?');">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="add_event.php" class="btn btn-success">➕ Evenement toevoegen</a>
        </section>
        
        <!-- ================================================================
             SECTIE 5: KALENDEROVERZICHT
             ================================================================ -->
        <section class="mb-5">
            <h2>📆 Kalenderoverzicht</h2>
            <div class="row">
                <?php if (empty($calendarItems)): ?>
                    <div class="col-12">
                        <p class="text-secondary text-center">Geen komende items. Voeg schema's of evenementen toe!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($calendarItems as $item): ?>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-cyan">
                                        <?php echo safeEcho($item['title'] ?? $item['game_titel']); ?>
                                    </h5>
                                    <p class="mb-1">
                                        <strong>📅</strong> <?php echo safeEcho($item['date']); ?> 
                                        <strong>⏰</strong> <?php echo safeEcho($item['time']); ?>
                                    </p>
                                    <?php if (!empty($item['description'])): ?>
                                        <p class="text-secondary small"><?php echo safeEcho(substr($item['description'], 0, 100)); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($item['reminder']) && $item['reminder'] !== 'none'): ?>
                                        <span class="badge bg-warning text-dark">🔔 <?php echo safeEcho($item['reminder']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($item['external_link'])): ?>
                                        <a href="<?php echo safeEcho($item['external_link']); ?>" target="_blank" class="btn btn-sm btn-outline-info mt-2">🔗 Link</a>
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
    
    <!-- Herinnerings-pop-ups -->
    <script>
        const reminders = <?php echo json_encode($reminders); ?>;
        reminders.forEach(reminder => {
            alert(`🔔 Herinnering: ${reminder['title']} om ${reminder['time']}`);
        });
    </script>
</body>
</html>