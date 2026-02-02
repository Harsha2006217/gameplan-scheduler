# FILE DOCUMENTATION: edit_favorite.php (A-Z Deep Dive)
## GamePlan Scheduler - Edit Favorite Game Handler

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `edit_favorite.php` | **Total Lines**: 107

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\edit_favorite.php`
**Purpose**: Updates the information about a specific game in a user's Favorites list.
**Complex Logic**: This page updates TWO tables at once or handles a relationship update (`UserGames`) alongside a game update (`Games`), depending on how the backend is structured.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Initialization & Validation (Lines 1-38)

```php
$id = $_GET['id'] ?? 0;
// Validation: Is ID numeric?
if (!is_numeric($id)) { header("Location: profile.php"); ... }

$favorites = getFavoriteGames($userId);
$game = array_filter($favorites, ...);
```
**Ownership Logic**:
*   Just like `edit_event.php`, we fetch *all* favorites first, then filter for the specific ID.
*   This ensures you can only edit a game that is currently in *your* list.

## SECTION 2: Form Handling (Lines 42-53)

```php
$error = updateFavoriteGame($userId, $id, $title, $description, $note);
```
**Parameter Breakdown**:
*   `$userId`: Who is doing the editing?
*   `$id`: Which game ID?
*   `$title` / `$description`: Core game details.
*   `$note`: The *User's personal note* about the game (e.g., "I play this on weekends").

## SECTION 3: Form Pre-filling (Lines 76-97)

The form pre-fills 3 fields:
1.  **Title**: Name of the game.
2.  **Description**: Generic game description.
3.  **Note**: User-specific note.

**Difference**:
*   `title` and `description` belong to the `Games` table.
*   `note` belongs to the `UserGames` table.
*   The `updateFavoriteGame` function (in `functions.php`) handles the SQL complexity of updating this data correctly.

---

# 3. Use Case Scenarios

**Scenario: Updating a Note**
*   User changes "I play this on weekends" to "I play this daily".
*   Only the `UserGames` table is updated.

**Scenario: Correcting a Title**
*   User changes "Fortnite" to "Fortnite Chapter 5".
*   The `Games` table record is updated.
*   *Note*: In a complex real-world app, editing a shared Game title might affect other users. In this simple MBO-4 scope, we allow the user to manage their game entry directly.

---

**END OF FILE DOCUMENTATION**
