# USER MANUAL / GEBRUIKERSHANDLEIDING
## GamePlan Scheduler - How to Use the Application

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# Table of Contents / Inhoudsopgave

1. [Getting Started](#1-getting-started)
2. [Registration](#2-registration)
3. [Login](#3-login)
4. [Dashboard](#4-dashboard)
5. [Managing Friends](#5-managing-friends)
6. [Managing Schedules](#6-managing-schedules)
7. [Managing Events](#7-managing-events)
8. [Managing Favorites](#8-managing-favorites)
9. [Profile Settings](#9-profile-settings)
10. [Logout](#10-logout)

---

# 1. Getting Started / Aan de Slag

## System Requirements / Systeemvereisten:
- Web browser (Chrome, Firefox, Edge, Safari)
- Internet connection (or localhost for development)

## Access the Application / Toegang tot de Applicatie:
```
URL: http://localhost/gameplan-scheduler/
```

---

# 2. Registration / Registratie

## Steps to Register / Stappen om te Registreren:

1. **Open the login page** / Open de login pagina
2. **Click "Register here"** / Klik op "Registreer hier"
3. **Fill in the form** / Vul het formulier in:

| Field | Requirements | Example |
|-------|--------------|---------|
| Username | 1-50 characters | GamerJohn |
| Email | Valid email format | john@example.com |
| Password | Minimum 8 characters | MySecure123 |

4. **Click "Register"** / Klik op "Registreren"
5. **Success!** You will be redirected to login / Succes! Je wordt doorgestuurd naar login

## Registration Errors / Registratie Fouten:

| Error Message | Solution |
|---------------|----------|
| "Email already registered" | Use a different email address |
| "Password too short" | Use at least 8 characters |
| "Invalid email format" | Enter a valid email (user@domain.com) |

---

# 3. Login / Inloggen

## Steps to Login / Stappen om in te Loggen:

1. **Go to login page** / Ga naar de login pagina
2. **Enter your email** / Voer je e-mail in
3. **Enter your password** / Voer je wachtwoord in
4. **Click "Login"** / Klik op "Inloggen"
5. **Success!** You are now on the Dashboard / Succes! Je bent nu op het Dashboard

## Login Errors / Login Fouten:

| Error Message | Solution |
|---------------|----------|
| "Invalid credentials" | Check email and password |
| "Email required" | Enter your email address |
| "Password required" | Enter your password |

---

# 4. Dashboard / Dashboard

The Dashboard is your home page after login. It shows:

## Dashboard Sections / Dashboard Secties:

```
┌─────────────────────────────────────────────────────────────┐
│                     DASHBOARD                                │
├─────────────────────────────────────────────────────────────┤
│  👥 FRIENDS        │ Your gaming friends list               │
├─────────────────────────────────────────────────────────────┤
│  🎮 FAVORITES      │ Your favorite games                    │
├─────────────────────────────────────────────────────────────┤
│  📅 SCHEDULES      │ Your gaming schedules (sortable)       │
├─────────────────────────────────────────────────────────────┤
│  🎯 EVENTS         │ Your gaming events (sortable)          │
└─────────────────────────────────────────────────────────────┘
```

## Dashboard Actions / Dashboard Acties:

| Button | Action |
|--------|--------|
| ➕ Add Friend | Add a new gaming friend |
| ➕ Add Schedule | Create a new gaming schedule |
| ➕ Add Event | Create a new gaming event |
| ✏️ Edit | Edit existing item |
| 🗑️ Delete | Delete item (with confirmation) |

---

# 5. Managing Friends / Vrienden Beheren

## Add a Friend / Vriend Toevoegen:

1. Click **"Add Friend"** on Dashboard
2. Fill in the form:

| Field | Description | Required |
|-------|-------------|----------|
| Username | Friend's gamertag/username | Yes |
| Note | Personal note (e.g., "Good at Fortnite") | No |
| Status | Online/Offline/Playing | No |

3. Click **"Add Friend"**
4. Friend appears in your list!

## Edit a Friend / Vriend Bewerken:

1. Click **✏️ Edit** next to friend's name
2. Update information
3. Click **"Save Changes"**

## Delete a Friend / Vriend Verwijderen:

1. Click **🗑️ Delete** next to friend's name
2. Confirm deletion
3. Friend is removed from list

---

# 6. Managing Schedules / Schema's Beheren

## Add a Schedule / Schema Toevoegen:

1. Click **"Add Schedule"** on Dashboard
2. Fill in the form:

| Field | Description | Required |
|-------|-------------|----------|
| Game Title | Name of the game | Yes |
| Date | When to play (today or future) | Yes |
| Time | Start time (HH:MM) | Yes |
| Friends | Who's joining (comma-separated) | No |
| Shared With | Who can see this schedule | No |

3. Click **"Add Schedule"**
4. Schedule appears in your list!

## Sorting Schedules / Schema's Sorteren:

Click column headers to sort:
- **Date** → Sort by date (A-Z or Z-A)
- **Time** → Sort by time
- **Game** → Sort alphabetically

---

# 7. Managing Events / Evenementen Beheren

## Add an Event / Evenement Toevoegen:

1. Click **"Add Event"** on Dashboard
2. Fill in the form:

| Field | Description | Required |
|-------|-------------|----------|
| Title | Event name (e.g., "Fortnite Tournament") | Yes |
| Date | Event date (today or future) | Yes |
| Time | Start time (HH:MM) | Yes |
| Description | Details about the event | No |
| Reminder | When to remind you | No |
| External Link | URL to event page/stream | No |
| Shared With | Who can see this event | No |

3. Click **"Add Event"**
4. Event appears in your list!

## Reminder Options / Herinnering Opties:

| Option | Description |
|--------|-------------|
| None | No reminder |
| 1 Hour Before | Remind 1 hour before event |
| 1 Day Before | Remind 1 day before event |

---

# 8. Managing Favorites / Favorieten Beheren

## Add a Favorite Game / Favoriet Spel Toevoegen:

1. Go to **Profile** page
2. Select a game from the list OR add new game title
3. Add personal note if desired
4. Click **"Add Favorite"**

## Edit a Favorite / Favoriet Bewerken:

1. Click **✏️ Edit** next to the game
2. Update your note
3. Click **"Save"**

## Remove a Favorite / Favoriet Verwijderen:

1. Click **🗑️ Delete** next to the game
2. Confirm removal

---

# 9. Profile Settings / Profiel Instellingen

Access your profile by clicking **"Profile"** in the navigation.

## Profile Features / Profiel Functies:

| Feature | Description |
|---------|-------------|
| View Username | See your display name |
| View Email | See your email address |
| Manage Favorites | Add/edit/remove favorite games |

---

# 10. Logout / Uitloggen

## How to Logout / Hoe Uitloggen:

1. Click **"Logout"** in the navigation bar
2. You will be redirected to the login page
3. Your session is ended

## Automatic Logout / Automatisch Uitloggen:

- After **30 minutes** of inactivity, you are automatically logged out
- This is for security (someone else might use your computer)

---

# Tips & Tricks / Tips & Trucs

## Keyboard Shortcuts / Sneltoetsen:
- **Tab**: Move between form fields
- **Enter**: Submit forms
- **Escape**: Close modals

## Best Practices / Beste Praktijken:

1. ✅ Use strong passwords (8+ characters, mix of letters/numbers)
2. ✅ Add notes to friends to remember who they are
3. ✅ Set reminders for important events
4. ✅ Use the sorting feature to organize schedules
5. ✅ Log out when using shared computers

---

# Troubleshooting / Problemen Oplossen

## Common Issues / Veelvoorkomende Problemen:

| Problem | Solution |
|---------|----------|
| Can't login | Check email and password are correct |
| Session expired | Login again (automatic after 30 min inactivity) |
| Form won't submit | Check all required fields are filled |
| Date error | Use today or future date (YYYY-MM-DD) |
| Can't delete item | You can only delete your own items |

## Need Help? / Hulp Nodig?

Contact the administrator or visit the contact page.

---

**END OF USER MANUAL / EINDE GEBRUIKERSHANDLEIDING**

This manual provides complete instructions for using GamePlan Scheduler.
Ready for MBO-4 examination!
