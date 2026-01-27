# ERROR MESSAGES REFERENCE
## GamePlan Scheduler - All Error Messages & Solutions

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 27-01-2026

---

# 1. Validation Error Messages

## 1.1 Required Field Errors (BUG FIX #1001)

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Field may not be empty" | No input provided | Enter a value |
| "Field may not contain only spaces" | Only whitespace entered | Enter actual text |
| "Field exceeds maximum length" | Too many characters | Shorten the input |

---

## 1.2 Date Errors (BUG FIX #1004)

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Invalid date format. Use YYYY-MM-DD" | Wrong format (e.g., DD-MM-YYYY) | Use 2026-01-27 format |
| "Date must be today or in the future" | Past date entered | Select today or future date |
| "Invalid date" | Impossible date (e.g., Feb 30) | Enter a real calendar date |

---

## 1.3 Time Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Invalid time format (HH:MM)" | Wrong format | Use 14:30 format |
| "Invalid time: hours must be 0-23" | Hour > 23 | Use 00-23 for hours |
| "Invalid time: minutes must be 0-59" | Minutes > 59 | Use 00-59 for minutes |

---

## 1.4 Email Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Invalid email format" | Missing @ or domain | Use user@example.com format |
| "Email is required" | Empty email field | Enter your email |

---

## 1.5 URL Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Invalid URL format" | Missing http/https | Use https://example.com format |

---

## 1.6 Comma-Separated Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "List contains empty items" | Double comma or trailing comma | Remove extra commas |

---

# 2. Authentication Error Messages

## 2.1 Login Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Invalid email or password" | Wrong credentials | Check email and password |
| "Email is required" | Empty email | Enter your email |
| "Password is required" | Empty password | Enter your password |
| "Account not found" | Email not registered | Register first |

---

## 2.2 Registration Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Email already registered" | Email exists in database | Use different email or login |
| "Username is required" | Empty username | Enter a username |
| "Password must be at least 8 characters" | Password too short | Use longer password |
| "Invalid email format" | Malformed email | Use valid email format |

---

## 2.3 Session Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "Session expired" | Inactive for 30+ minutes | Login again |
| "Please login to continue" | Not logged in | Go to login page |

---

# 3. Authorization Error Messages

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "You do not have permission" | Trying to access another user's data | Only access your own data |
| "Access denied" | Unauthorized operation | Check your permissions |
| "Record not found" | Item doesn't exist or was deleted | Refresh the page |

---

# 4. Database Error Messages

| Error Message (User Sees) | Actual Cause | Developer Action |
|---------------------------|--------------|------------------|
| "An error occurred. Please try again." | Database connection failed | Check db.php configuration |
| "Unable to save. Please try again." | Insert/update failed | Check query and data |
| "Unable to delete. Please try again." | Delete operation failed | Check ownership |

> **Note**: Detailed errors are logged, not shown to users (for security).

---

# 5. Form-Specific Error Messages

## 5.1 Schedule Form

| Error Message | Field | Solution |
|---------------|-------|----------|
| "Game title is required" | Game Title | Enter a game name |
| "Date is required" | Date | Select a date |
| "Time is required" | Time | Enter time in HH:MM |
| "Invalid characters in friends list" | Friends | Use only letters, numbers, commas |

---

## 5.2 Event Form

| Error Message | Field | Solution |
|---------------|-------|----------|
| "Title is required" | Title | Enter event title |
| "Title too long (max 100)" | Title | Shorten the title |
| "Description too long (max 500)" | Description | Shorten description |
| "Invalid external link" | External Link | Use valid URL or leave empty |

---

## 5.3 Friend Form

| Error Message | Field | Solution |
|---------------|-------|----------|
| "Username is required" | Friend Username | Enter friend's gamertag |
| "Invalid status" | Status | Select from dropdown |

---

# 6. JavaScript Alert Messages

| Alert Message | Form | When Shown |
|---------------|------|------------|
| "Email and password are required" | Login | Empty fields |
| "Invalid email format" | Login/Register | Bad email |
| "All fields are required" | Register | Missing fields |
| "Password must be at least 8 characters" | Register | Short password |
| "Username too long (max 50 characters)" | Register | Long username |
| "Game title is required" | Schedule | Empty game |
| "Date must be today or in the future" | Schedule/Event | Past date |
| "Invalid time format" | Schedule/Event | Bad time |

---

# 7. Success Messages

| Message | Operation | Location |
|---------|-----------|----------|
| "Login successful!" | Login | Redirect to dashboard |
| "Registration successful!" | Register | Redirect to login |
| "Schedule added successfully!" | Add Schedule | Dashboard |
| "Schedule updated successfully!" | Edit Schedule | Dashboard |
| "Schedule deleted successfully!" | Delete Schedule | Dashboard |
| "Event added successfully!" | Add Event | Dashboard |
| "Friend added successfully!" | Add Friend | Friends list |
| "Changes saved!" | Any edit | Respective page |
| "Logged out successfully" | Logout | Login page |

---

# 8. Error Message Locations

| Error Type | Where Displayed | CSS Class |
|------------|-----------------|-----------|
| Form validation | Above form | .alert-danger |
| Success message | Above form | .alert-success |
| JS validation | Browser alert | alert() |
| Session message | Top of page | .alert-info |

---

**END OF ERROR MESSAGES REFERENCE**
