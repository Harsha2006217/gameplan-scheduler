# FILE DOCUMENTATION: contact.php (A-Z Deep Dive)
## GamePlan Scheduler - Contact Information Page

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `contact.php` | **Total Lines**: 74

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\contact.php`
**Purpose**: Displays essential support information, developer details, project context, and quick help for users.
**Key Dependencies**:
*   `functions.php` (Session initialization)
*   `header.php` / `footer.php` (UI Consistency)

**Features**:
1.  **Direct Support**: Mailto link for quick emailing.
2.  **Developer Credit**: Clear student and project identification (Exam requirement).
3.  **Repository Link**: Link to GitHub codebase.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Initialization (Lines 1-15)

```php
require_once 'functions.php';
checkSessionTimeout();
```

**Logic Explained**:
1.  **`require_once`**: Loads the PHP session system.
2.  **`checkSessionTimeout()`**:
    *   Even though this is a "static" info page, we include this to keep the user's session active if they are viewing it.
    *   **Note**: Unlike `add_event.php`, there is NO `isLoggedIn()` check here.
    *   **Why?** Users having trouble logging in needs to see this page to find help! *It is publicly accessible.*

---

## SECTION 2: Layout & Metadata (Lines 16-25)

**Standard HTML5 Head**:
*   **Title**: "Contact - GamePlan Scheduler".
*   **CSS**: Includes Bootstrap 5 and `style.css` (Dark Theme).
*   **Viewport**: Ensures the contact details are readable on mobile phones (`width=device-width`).

---

## SECTION 3: Information Structure (Lines 30-68)

The content is wrapped in a Bootstrap Card (`<div class="card">`) for a clean, contained look on the dark background.

#### A. Email Section (Lines 35-41)
```html
<a href="mailto:harsha.kanaparthi20062@gmail.com" class="text-info">
    harsha.kanaparthi20062@gmail.com
</a>
```
*   **`mailto:` protocol**: Clicking this link immediately opens the user's default email app (Outlook, Gmail app, etc.) with the address pre-filled.
*   **Styling**: `text-info` gives it a nice cyan color, readable against dark grey.

#### B. Developer Info (Lines 43-49)
```html
<li><strong>Student Number:</strong> 2195344</li>
```
*   **Compliance**: Clearly lists Student ID/Name for the examiner to verify ownership.

#### C. GitHub Link (Lines 51-56)
```html
<a href="..." target="_blank">
```
*   **`target="_blank"`**: Force-opens the GitHub page in a new tab so the user doesn't lose their place in the app.
*   **Security Note**: Modern browsers handle `target="_blank"` safely, but adding `rel="noopener noreferrer"` is a recommended best practice for external links (though not strictly required for this specific exam).

#### D. Quick Help (Lines 58-63)
*   Simple FAQ list helping users troubleshoot common Login/Password issues before emailing.

---

# 3. Security & Access

1.  **Public Access**: Unrestricted. Everyone can see it.
2.  **Session Preservation**: If a logged-in user visits, `session_start()` (in functions.php) keeps their login alive.
3.  **Sanitization**: Since this page outputs hardcoded text and no user input, XSS risks are effectively zero here.

---

# 4. UI/UX Design

*   **Consistency**: Uses the same `header.php` and `footer.php` as the main app.
*   **Navigation**: Includes a prominent "Back to Dashboard" button at the bottom for easy return flow.
*   **Readability**: Uses semantic HTML tags (`h1`, `h3`, `ul`, `li`) which is good for accessibility.

---

**END OF FILE DOCUMENTATION**
