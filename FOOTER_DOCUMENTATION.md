# FILE DOCUMENTATION: footer.php (A-Z Deep Dive)
## GamePlan Scheduler - Reusable Footer Component

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `footer.php` | **Total Lines**: 90

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\footer.php`
**Purpose**: A standardized footer included on EVERY page of the site to ensure navigation consistency and legal compliance.
**Implementation**: Included via `<?php include 'footer.php'; ?>`.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Structural Design (Lines 34)

```html
<footer class="bg-secondary p-2 text-center fixed-bottom">
```
**Bootstrap Classes Explained**:
1.  **`bg-secondary`**: Sets a grey background.
2.  **`p-2`**: Adds Padding size 2 (approx 0.5rem) to make it breathable.
3.  **`text-center`**: Centers the copyright text and links.
4.  **`fixed-bottom`**: **CRITICAL**. This CSS class forces the footer to stick to the very bottom of the browser viewport (screen), even if the page content is short.

## SECTION 2: Content (Lines 44-73)

### Copyright (Lines 44-46)
```html
<span>© 2025 GamePlan Scheduler by Harsha Kanaparthi</span>
```
*   **Compliance**: Identifying the student/developer is a requirement for the exam submission.

### Privacy Policy Link (Lines 58-60)
```html
<a href="privacy.php" class="text-info text-decoration-none">
    Privacy Policy
</a>
```
*   **GDPR/AVG**: Every site collecting user data (like email/password) requires a Privacy Policy. Only by linking to it here (on every page) do we satisfy the requirement for "Accessible Legal Info".

### Contact Link (Lines 71-73)
*   Provides a quick route to support from anywhere in the app.

---

# 3. Exam Tips

**Q: Why separate headers and footers?**
A: **DRY (Don't Repeat Yourself)**. If I need to change the copyright year to 2026, I change it **once** in `footer.php`, and it updates on all 20+ pages of the application instantly. This is a core software development principle.

---

**END OF FILE DOCUMENTATION**
