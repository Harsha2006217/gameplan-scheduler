# 🎮 GamePlan Scheduler (Legendary Edition)
## The Ultimate Gaming Planner for the MBO-4 Examination

**A secure, responsive web application for gamers to manage profiles, add friends, share play schedules, plan events, and set reminders.**

---

## 📌 Overview

- **Author**: Harsha Kanaparthi  
- **Student Number**: 2195344  
- **Date**: 2026-02-02  
- **Version**: 1.1 (Legendary Update)  
- **Technologies**: PHP 8.x, MySQL (PDO), HTML/CSS/Bootstrap 5, JavaScript  
- **Support**: Desktop & Mobile (Fully Responsive)  

---

## ✨ Key Features (Version 1.1)

### 🛠️ Bug Fixes & Technical Improvements
1.  **#1001 Empty Field Validation**: Strict `trim()` check added in `functions.php`. Fields containing only spaces are now rejected to ensure data integrity.
2.  **#1004 Date Integrity**: Implemented `DateTime` validation. Invalid dates (e.g., February 30th) and past dates are strictly rejected.
3.  **#1002 Notifications Ext.**: `add_event.php` enhanced with a dropdown for notification types (1 hour, 1 day, etc.).
4.  **#1003 Navigation Polish**: "Add Event" button is now prominently placed in the header for extreme usability.
5.  **#1006 Chronological Sorting**: Dashboard lists are now sorted by Date/Time using custom sorting algorithms.

### 🎨 Design Excellence ("Legendary" Theme)
- **Glassmorphism**: Semi-transparent UI cards over an animated gaming background.
- **Neon Accents**: Cyberpunk-inspired blue and purple glow for a modern aesthetic.
- **Micro-interactions**: Smooth hover effects on buttons, inputs, and tables.
- **Bootstrap 5**: Full integration for professional layout and modal components.

### 📝 Documentation
- **Line-by-Line Technical Guides**: Every core file includes a Dutch explanation (`UITLEG_*.md`) for examiners.
- **Defense-in-Depth**: Comprehensive security documentation covering OWASP Top 10 mitigations.

---

## ⚙️ Installation

1. **Environment**: Ensure XAMPP (Apache + MySQL) is running.
2. **Database**: Import `database.sql` via PHPMyAdmin.
3. **Storage**: Copy the `gameplan-scheduler` folder to your `htdocs` directory.
4. **Run**: Navigate to `http://localhost/gameplan-scheduler` (or your local IP).

---

## 📂 Documentation entry point

For a full overview of all 70+ documents, please start at the [SUBMISSION_INDEX.md](file:///c:/xampp/htdocs/K1-W3-gameplan-scheduler-Harsha%20Kanaparthi/gameplan-scheduler/SUBMISSION_INDEX.md).

---

## 🎓 Exam Preparation
If you are Harsha, please review:
*   [DEMO_SCRIPT_EXAMEN_NL.md](file:///c:/xampp/htdocs/K1-W3-gameplan-scheduler-Harsha%20Kanaparthi/gameplan-scheduler/DEMO_SCRIPT_EXAMEN_NL.md)
*   [PRESENTATIE_SLIDES_NL.md](file:///c:/xampp/htdocs/K1-W3-gameplan-scheduler-Harsha%20Kanaparthi/gameplan-scheduler/PRESENTATIE_SLIDES_NL.md)

---
**Developed by Harsha Kanaparthi**  
*MBO-4 Software Development Examination Portfolio*