# FILE DOCUMENTATION: privacy.php (A-Z Deep Dive)
## GamePlan Scheduler - GDPR Compliance Statement

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 02-02-2026
**File Analyzed**: `privacy.php` | **Total Lines**: 92

---

# 1. Introduction / Inleiding

**File Location**: `c:\xampp\htdocs\...\gameplan-scheduler\privacy.php`
**Purpose**: To legally inform users about their data rights.
**Mandate**: Under EU Law (AVG / GDPR), this page is **Mandatory** for any site holding personal data like Emails.

---

# 2. Detailed Code Analysis (Section by Section)

## SECTION 1: Data Collection Transparency (Lines 42-49)

```html
<ul>
    <li>Username</li>
    <li>Email (For login only)</li>
</ul>
```
*   **Principle of Minimization**: We state that we only collect what is needed for the app to work. We don't ask for phone numbers or addresses.

## SECTION 2: Security Transparency (Lines 51-57)

This section proves to the examiner (and user) that the developer understands security mechanisms:
1.  **Encryption**: Mentions `bcrypt` hashing for passwords.
2.  **Protection**: Mentions Prepared Statements against SQL Injection.
3.  **Timeouts**: Mentions the 30-minute session expiry rule.

## SECTION 3: "What We Don't Do" (Lines 59-65)

*   **No Selling Data**: Explicit promise.
*   **No Tracking Cookies**: The only cookie used is `PHPSESSID` (essential functional cookie), which does not require a "Cookie Banner" under EU law.

## SECTION 4: User Rights (Lines 67-72)

```html
<li>You can edit or delete any of your information</li>
```
*   **Right to Erasure ("Right to be Forgotten")**: We confirm users can delete friends, schedules, and events themselves. For full account deletion, we provide a contact email.

---

# 3. Access Control

**Public Access? YES**.
*   Privacy policies must be visible *before* registration.
*   Even though it includes `require_once 'functions.php'`, it does NOT check `isLoggedIn()`, so guests can read it to feel safe before signing up.

---

**END OF FILE DOCUMENTATION**
