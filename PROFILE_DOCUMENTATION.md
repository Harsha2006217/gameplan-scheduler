# FILE DOCUMENTATION: profile.php (A-Z Deep Dive)
## GamePlan Scheduler - User Profile & Game Library

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `profile.php` | **Total Lines**: 139

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\profile.php`
**Purpose**: Manages the user's "Identity" within the app, specifically their Game Library.
**Exam Requirement**: Satisfies the User Story "Create a profile with favorite games".

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Game Management Logic (Lines 33-45)

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_favorite'])) {
    // ...
    $error = addFavoriteGame($userId, $title, $description, $note);
}
```
**Why `isset($_POST['add_favorite'])`?**
*   This specific check allows us to have multiple forms on one page in the future (e.g., "Change Password" form vs "Add Game" form). It identifies *which* button was clicked.
*   **Intelligent Backend**: `addFavoriteGame` checks if the game already exists in the global database to avoid duplicates (See `functions.php`).

## SECTION 2: The "Add" Form (Lines 68-92)

*   **Inputs**:
    1.  **Title** (Required): e.g., "Valorant".
    2.  **Description** (Optional): "Tactical Shooter".
    3.  **Note** (Optional): "I play Sage main".
*   **User Flow**: User adds a game -> Logic links it to their ID -> Page Refreshes -> Game appears in table below.

## SECTION 3: The Favorites Table (Lines 95-132)

```php
<?php foreach ($favorites as $game): ?>
    <td><?php echo safeEcho($game['titel']); ?></td>
```
*   **Dynamic Rendering**: Loops through the array returned by `getFavoriteGames()`.
*   **Actions**:
    *   **Edit**: Links to `edit_favorite.php`.
    *   **Delete**: Links to `delete.php?type=favorite` with a JavaScript confirmation dialog.

---

# 3. Design Decisions

**Why not put this on the Dashboard?**
*   The Dashboard (`index.php`) is for *Status* (What's happening now?).
*   The Profile (`profile.php`) is for *Configuration* (What do I play?).
*   Separating them keeps the UI clean and focused.

---

**END OF FILE DOCUMENTATION**
