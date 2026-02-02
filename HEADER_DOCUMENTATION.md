# FILE DOCUMENTATION: header.php (A-Z Deep Dive)
## GamePlan Scheduler - Responsive Navigation

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `header.php` | **Total Lines**: 170

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\header.php`
**Purpose**: Contains the main navigation bar.
**Functionality**:
1.  **Fixed Position**: Stays at the top while scrolling (`fixed-top`).
2.  **Responsive**: Collapses into a "Hamburger Menu" on mobile screens.
3.  **Prominent Actions**: Highlights the "Add Event" button.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Bootstrap Navbar Structure (Lines 29-55)

```html
<header class="fixed-top bg-primary p-0 mb-4">
    <nav class="navbar navbar-expand-lg navbar-dark p-0">
```
**Classes Explained**:
*   `navbar-expand-lg`: The menu is fully visible on Large screens (Desktop) but collapses on smaller screens (Tablet/Mobile).
*   `navbar-dark`: Tells Bootstrap that the background is dark (blue), so text should be white.
*   `bg-primary`: Uses the standard Bootstrap Blue (or custom theme blue).

## SECTION 2: Hamburger Menu (Lines 65-73)

```html
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" ...>
    <span class="navbar-toggler-icon"></span>
</button>
```
**How it works**:
*   **Target**: `data-bs-target="#navbarNav"` connects this button to the `div` with `id="navbarNav"`.
*   **JavaScript**: Bootstrap's JS (loaded in the footer) listens for clicks here to toggle the menu up/down.

## SECTION 3: Navigation Links (Lines 92-149)

### The link list (`<ul>`)
*   **`ms-auto`**: Margin Start Auto. Pushes the menu items to the **Right** side of the screen.

### Special Item: "Add Event" (Lines 135-139)
```html
<a class="nav-link text-white btn btn-success ms-2 px-3" href="add_event.php">
    🎯 Add Event
</a>
```
*   **Visual Hierarchy**: By adding `btn btn-success` (Green button class) to a standard nav link, it becomes a "Call to Action" button.
*   **Purpose**: Encourages users to create content.

### Logout Logic (Lines 143-145)
```html
<a href="index.php?logout=1">🚪 Logout</a>
```
*   **Trigger**: Sends a GET request to `index.php` with parameter `logout=1`.
*   **Handler**: In `index.php`, PHP detects this parameter and calls `logout()` from `functions.php`.

---

# 3. User Experience (UX)

**Consistency**
*   This file is `include`d in every single page.
*   Benefit: If we add a new page (e.g., "Settings"), we add the link ONCE here, and it appears everywhere.

---

**END OF FILE DOCUMENTATION**
