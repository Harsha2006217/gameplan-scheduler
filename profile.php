<?php
// ============================================================================
// PROFILE.PHP - User Profile and Favorite Games Management
// ============================================================================
// Author       : Harsha Kanaparthi (Student Number: 2195344)
// Date         : 30-09-2025
// Version      : 1.0
// Project      : GamePlan Scheduler - MBO-4 Software Development Examination
// ============================================================================
// DESCRIPTION:
// This page lets users manage their profile by adding, viewing, editing,
// and deleting their favorite games. It implements full CRUD operations.
//
// CRUD = Create, Read, Update, Delete
// - CREATE: Add new favorite game via form
// - READ: Display list of all favorite games
// - UPDATE: Edit link to edit_favorite.php
// - DELETE: Delete link with confirmation
//
// USER STORY: "Create a profile with favorite games"
// - User can add games they like to play
// - Each game can have a description and personal note
// - Games are shown in a table with actions
// ============================================================================


// Include core functions
require_once 'functions.php';


// Check session timeout
checkSessionTimeout();


// Redirect if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}


// Get user ID
$userId = getUserId();


// Get user's favorite games from database
$favorites = getFavoriteGames($userId);


// Initialize error variable
$error = '';


// ============================================================================
// PROCESS ADD FAVORITE FORM SUBMISSION
// ============================================================================
// When form is submitted with POST and has add_favorite button

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {

    // Get form data
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $note = $_POST['note'] ?? '';

    // Attempt to add favorite game
    // addFavoriteGame() validates input and adds to database
    $error = addFavoriteGame($userId, $title, $description, $note);

    // If successful (no error)
    if (!$error) {
        // Set success message
        setMessage('success', 'Favorite game added successfully!');

        // Redirect to same page (prevents form resubmission on refresh)
        header("Location: profile.php");
        exit;
    }
    // If error, it will be displayed below
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - GamePlan Scheduler</title>
    <meta name="description" content="Manage your profile and favorite games in GamePlan Scheduler.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-dark text-light">

    <!-- Navigation header -->
    <?php include 'header.php'; ?>


    <!-- Main content -->
    <main class="container mt-5 pt-5 pb-5">

        <!-- Flash messages -->
        <?php echo getMessage(); ?>


        <!-- Page title -->
        <h1 class="h3 fw-bold mb-4">👤 My Profile</h1>


        <!-- ================================================================
             ADD FAVORITE GAME FORM
             ================================================================ -->
        <section class="mb-5">
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h5 fw-bold mb-0">➕ Add Favorite Game</h2>
                </div>
                <div class="card-body px-4 pb-4">

                    <!-- Error display -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo safeEcho($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>


                    <!-- Add game form -->
                    <form method="POST">

                        <!-- Game Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                🎮 Game Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                class="form-control bg-dark text-light border-secondary" required maxlength="100"
                                placeholder="e.g., Fortnite, Minecraft, League of Legends" aria-label="Game Title">
                            <div class="form-text text-muted">
                                Enter the name of your favorite game (required)
                            </div>
                        </div>


                        <!-- Description Field (Optional) -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                📝 Description
                            </label>
                            <textarea id="description" name="description"
                                class="form-control bg-dark text-light border-secondary" rows="2" maxlength="500"
                                placeholder="What type of game is this?" aria-label="Game Description"></textarea>
                            <div class="form-text text-muted">
                                Brief description of the game (optional)
                            </div>
                        </div>


                        <!-- Personal Note Field (Optional) -->
                        <div class="mb-4">
                            <label for="note" class="form-label">
                                💬 Personal Note
                            </label>
                            <textarea id="note" name="note" class="form-control bg-dark text-light border-secondary"
                                rows="2" placeholder="Why do you like this game? What's your rank?"
                                aria-label="Note"></textarea>
                            <div class="form-text text-muted">
                                Your personal notes about this game (optional)
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" name="add_favorite" class="btn btn-success btn-lg px-5">
                            ✅ Add to Favorites
                        </button>

                    </form>

                </div>
            </div>
        </section>


        <!-- ================================================================
             FAVORITE GAMES LIST
             ================================================================ -->
        <section>
            <div class="card bg-secondary border-0 rounded-4 shadow">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h2 class="h5 fw-bold mb-0">⭐ Your Favorite Games</h2>
                </div>
                <div class="card-body px-4 pb-4">

                    <?php if (empty($favorites)): ?>
                        <!-- Empty state -->
                        <div class="text-center py-5">
                            <div class="display-1 mb-3">🎮</div>
                            <p class="text-muted mb-0">
                                No favorite games yet. Add your first one above!
                            </p>
                        </div>
                    <?php else: ?>
                        <!-- Favorites table -->
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Game Title</th>
                                        <th>Description</th>
                                        <th>My Note</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($favorites as $game): ?>
                                        <tr>
                                            <!-- Game title -->
                                            <td class="fw-semibold">
                                                🎮 <?php echo safeEcho($game['titel']); ?>
                                            </td>

                                            <!-- Description -->
                                            <td class="text-muted">
                                                <?php echo safeEcho($game['description']) ?: '-'; ?>
                                            </td>

                                            <!-- Personal note -->
                                            <td class="text-muted">
                                                <?php echo safeEcho($game['note']) ?: '-'; ?>
                                            </td>

                                            <!-- Action buttons -->
                                            <td class="text-end">
                                                <a href="edit_favorite.php?id=<?php echo $game['game_id']; ?>"
                                                    class="btn btn-sm btn-outline-warning me-1">
                                                    ✏️ Edit
                                                </a>
                                                <a href="delete.php?type=favorite&id=<?php echo $game['game_id']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Remove this game from favorites?');">
                                                    🗑️ Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </section>

    </main>


    <!-- Footer -->
    <?php include 'footer.php'; ?>


    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>