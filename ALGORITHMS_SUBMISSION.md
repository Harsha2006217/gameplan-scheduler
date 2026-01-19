# ALGORITHM SUBMISSION DOCUMENT
## GamePlan Scheduler - All Validation Algorithms

**Author**: Harsha Kanaparthi | **Student**: 2195344 | **Date**: 2026-01-19

---

# 1. REQUIRED FIELD VALIDATION ALGORITHM (BUG FIX #1001)

```
ALGORITHM: validateRequired(value, fieldName, maxLength)
─────────────────────────────────────────────────────────
FUNCTION: functions.php, Lines 68-86

INPUT:
   - value: The text to validate
   - fieldName: Name for error messages
   - maxLength: Maximum allowed characters (0 = no limit)

OUTPUT:
   - Error message string if invalid
   - NULL if valid

STEPS:
1. START
2. value = trim(value)                    // Remove leading/trailing whitespace
3. IF value is empty OR matches /^\s*$/   // Check empty or spaces-only
      RETURN "fieldName may not be empty or contain only spaces"
   END IF
4. IF maxLength > 0 AND length(value) > maxLength
      RETURN "fieldName exceeds maximum length"
   END IF
5. RETURN null                            // Valid
6. END
```

---

# 2. DATE VALIDATION ALGORITHM (BUG FIX #1004)

```
ALGORITHM: validateDate(date)
─────────────────────────────
FUNCTION: functions.php, Lines 97-117

INPUT:
   - date: Date string in format YYYY-MM-DD

OUTPUT:
   - Error message string if invalid
   - NULL if valid

STEPS:
1. START
2. dateObj = DateTime::createFromFormat('Y-m-d', date)
3. IF dateObj is FALSE OR dateObj.format('Y-m-d') != date
      RETURN "Invalid date format. Use YYYY-MM-DD."
   END IF
4. today = new DateTime('today')
5. IF dateObj < today
      RETURN "Date must be today or in the future."
   END IF
6. RETURN null                            // Valid
7. END
```

---

# 3. TIME VALIDATION ALGORITHM

```
ALGORITHM: validateTime(time)
─────────────────────────────
FUNCTION: functions.php, Lines 123-130

INPUT:
   - time: Time string in format HH:MM

OUTPUT:
   - Error message string if invalid
   - NULL if valid

STEPS:
1. START
2. regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
3. IF time does NOT match regex
      RETURN "Invalid time format (HH:MM)"
   END IF
4. RETURN null                            // Valid
5. END
```

---

# 4. EMAIL VALIDATION ALGORITHM

```
ALGORITHM: validateEmail(email)
───────────────────────────────
FUNCTION: functions.php, Lines 136-142

INPUT:
   - email: Email address string

OUTPUT:
   - Error message string if invalid
   - NULL if valid

STEPS:
1. START
2. IF filter_var(email, FILTER_VALIDATE_EMAIL) is FALSE
      RETURN "Invalid email format"
   END IF
3. RETURN null                            // Valid
4. END
```

---

# 5. URL VALIDATION ALGORITHM

```
ALGORITHM: validateUrl(url)
───────────────────────────
FUNCTION: functions.php, Lines 148-154

INPUT:
   - url: URL string (optional field)

OUTPUT:
   - Error message string if invalid
   - NULL if valid

STEPS:
1. START
2. IF url is NOT empty AND filter_var(url, FILTER_VALIDATE_URL) is FALSE
      RETURN "Invalid URL format"
   END IF
3. RETURN null                            // Valid (empty is also valid)
4. END
```

---

# 6. COMMA-SEPARATED VALUES ALGORITHM

```
ALGORITHM: validateCommaSeparated(value, fieldName)
───────────────────────────────────────────────────
FUNCTION: functions.php, Lines 160-171

INPUT:
   - value: Comma-separated string
   - fieldName: Name for error messages

OUTPUT:
   - Error message string if invalid
   - NULL if valid

STEPS:
1. START
2. IF value is empty
      RETURN null                         // Optional field, empty is valid
   END IF
3. items = split value by comma (',')
4. FOR EACH item IN items
      IF trim(item) is empty
         RETURN "fieldName contains empty items"
      END IF
   END FOR
5. RETURN null                            // Valid
6. END
```

---

# 7. LOGIN FORM VALIDATION ALGORITHM (JavaScript)

```
ALGORITHM: validateLoginForm()
──────────────────────────────
FUNCTION: script.js, Lines 38-68

INPUT:
   - Email field from DOM
   - Password field from DOM

OUTPUT:
   - TRUE: Allow form submission
   - FALSE: Block form submission

STEPS:
1. START
2. email = document.getElementById('email').value.trim()
3. password = document.getElementById('password').value.trim()
4. IF email is empty OR password is empty
      DISPLAY alert: "Email and password are required"
      RETURN false
   END IF
5. emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
6. IF email does NOT match emailRegex
      DISPLAY alert: "Invalid email format"
      RETURN false
   END IF
7. RETURN true                            // Allow submission
8. END
```

---

# 8. REGISTER FORM VALIDATION ALGORITHM (JavaScript)

```
ALGORITHM: validateRegisterForm()
─────────────────────────────────
FUNCTION: script.js, Lines 93-136

INPUT:
   - Username, Email, Password fields from DOM

OUTPUT:
   - TRUE: Allow form submission
   - FALSE: Block form submission

STEPS:
1. START
2. username = getElementById('username').value.trim()
3. email = getElementById('email').value.trim()
4. password = getElementById('password').value.trim()
5. IF username empty OR email empty OR password empty
      DISPLAY alert: "All fields are required"
      RETURN false
   END IF
6. IF username matches /^\s*$/            // Spaces-only check (BUG #1001)
      DISPLAY alert: "Username cannot be only spaces"
      RETURN false
   END IF
7. IF username.length > 50
      DISPLAY alert: "Username too long (max 50 characters)"
      RETURN false
   END IF
8. IF email does NOT match /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      DISPLAY alert: "Invalid email format"
      RETURN false
   END IF
9. IF password.length < 8
      DISPLAY alert: "Password must be at least 8 characters"
      RETURN false
   END IF
10. RETURN true                           // Allow submission
11. END
```

---

# 9. SCHEDULE FORM VALIDATION ALGORITHM (JavaScript)

```
ALGORITHM: validateScheduleForm()
─────────────────────────────────
FUNCTION: script.js, Lines 163-224

INPUT:
   - Game title, Date, Time, Friends, Shared with fields from DOM

OUTPUT:
   - TRUE: Allow form submission
   - FALSE: Block form submission

STEPS:
1. START
2. gameTitle = getElementById('game_title').value.trim()
3. date = getElementById('date').value
4. time = getElementById('time').value
5. friendsStr = getElementById('friends_str').value.trim()
6. sharedWithStr = getElementById('shared_with_str').value.trim()
7. IF gameTitle empty OR matches /^\s*$/  // BUG #1001
      DISPLAY alert: "Game title required, cannot be only spaces"
      RETURN false
   END IF
8. IF date is empty
      DISPLAY alert: "Date is required"
      RETURN false
   END IF
9. selectedDate = new Date(date)
10. today = new Date(); today.setHours(0,0,0,0)
11. IF selectedDate is invalid (isNaN)
       DISPLAY alert: "Invalid date format"
       RETURN false
    END IF
12. IF selectedDate < today              // BUG #1004
       DISPLAY alert: "Date must be today or in the future"
       RETURN false
    END IF
13. IF time NOT match /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
       DISPLAY alert: "Invalid time format. Use HH:MM"
       RETURN false
    END IF
14. IF friendsStr exists AND NOT match /^[a-zA-Z0-9,\s]*$/
       DISPLAY alert: "Friends field contains invalid characters"
       RETURN false
    END IF
15. IF sharedWithStr exists AND NOT match /^[a-zA-Z0-9,\s]*$/
       DISPLAY alert: "Shared with contains invalid characters"
       RETURN false
    END IF
16. RETURN true                          // Allow submission
17. END
```

---

# 10. EVENT FORM VALIDATION ALGORITHM (JavaScript)

```
ALGORITHM: validateEventForm()
──────────────────────────────
FUNCTION: script.js, Lines 253-327

INPUT:
   - Title, Date, Time, Description, External Link, Shared with from DOM

OUTPUT:
   - TRUE: Allow form submission
   - FALSE: Block form submission

STEPS:
1. START
2. title = getElementById('title').value.trim()
3. date = getElementById('date').value
4. time = getElementById('time').value
5. description = getElementById('description').value
6. externalLink = getElementById('external_link').value
7. sharedWithStr = getElementById('shared_with_str').value.trim()
8. IF title empty OR matches /^\s*$/     // BUG #1001
      DISPLAY alert: "Title required, cannot be only spaces"
      RETURN false
   END IF
9. IF title.length > 100
      DISPLAY alert: "Title too long (max 100 characters)"
      RETURN false
   END IF
10. IF date is empty
       DISPLAY alert: "Date is required"
       RETURN false
    END IF
11. selectedDate = new Date(date)
12. today = new Date(); today.setHours(0,0,0,0)
13. IF selectedDate is invalid
       DISPLAY alert: "Invalid date format. Use YYYY-MM-DD"
       RETURN false
    END IF
14. IF selectedDate < today              // BUG #1004
       DISPLAY alert: "Date must be today or in the future"
       RETURN false
    END IF
15. IF time NOT match /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
       DISPLAY alert: "Invalid time format. Use HH:MM"
       RETURN false
    END IF
16. IF description.length > 500
       DISPLAY alert: "Description too long (max 500 characters)"
       RETURN false
    END IF
17. urlRegex = /^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/
18. IF externalLink exists AND NOT match urlRegex
       DISPLAY alert: "Invalid external link format"
       RETURN false
    END IF
19. IF sharedWithStr exists AND NOT match /^[a-zA-Z0-9,\s]*$/
       DISPLAY alert: "Shared with contains invalid characters"
       RETURN false
    END IF
20. RETURN true                          // Allow submission
21. END
```

---

# 11. COMPLETE LOGIN FLOW ALGORITHM

```
ALGORITHM: Complete Login Process
─────────────────────────────────
FILES: login.php, functions.php, script.js, db.php

PHASE 1 - PAGE LOAD:
1. Browser requests login.php
2. Include functions.php
3. Start session (session_start)
4. Check isLoggedIn()
5. IF logged in → redirect to index.php
6. Initialize $error = ''
7. Render HTML form

PHASE 2 - CLIENT VALIDATION (User submits):
8. onsubmit calls validateLoginForm()
9. Get email and password with trim()
10. IF empty → alert error, RETURN false
11. Validate email format with regex
12. IF invalid → alert error, RETURN false
13. RETURN true → submit to server

PHASE 3 - SERVER PROCESSING:
14. login.php receives POST
15. Get email and password from $_POST
16. Call loginUser(email, password)

PHASE 4 - loginUser() FUNCTION:
17. Get database connection
18. validateRequired(email, "Email")
19. IF error → return error
20. validateRequired(password, "Password")
21. IF error → return error
22. Query: SELECT FROM Users WHERE email = ?
23. IF no user found → return "Invalid credentials"
24. password_verify(password, hash)
25. IF not match → return "Invalid credentials"
26. Set $_SESSION['user_id']
27. Set $_SESSION['username']
28. session_regenerate_id(true)
29. updateLastActivity()
30. RETURN null (success)

PHASE 5 - POST-LOGIN:
31. IF no error → redirect to index.php
32. ELSE → display error in form
```

---

# 12. OWNERSHIP CHECK ALGORITHM

```
ALGORITHM: checkOwnership(pdo, table, idColumn, id, userId)
───────────────────────────────────────────────────────────
FUNCTION: functions.php, Lines 640-645

INPUT:
   - pdo: Database connection
   - table: Table name (Schedules, Events, Friends)
   - idColumn: Primary key column name
   - id: Record ID to check
   - userId: Current user's ID

OUTPUT:
   - TRUE: User owns this record
   - FALSE: User does NOT own this record

STEPS:
1. START
2. query = "SELECT COUNT(*) FROM {table} 
            WHERE {idColumn} = :id 
            AND user_id = :user_id 
            AND deleted_at IS NULL"
3. Execute query with id and user_id parameters
4. count = fetchColumn()
5. IF count > 0
      RETURN true                         // User owns record
   ELSE
      RETURN false                        // No permission
   END IF
6. END
```

---

**END OF ALGORITHMS DOCUMENT**

This document contains all validation algorithms used in the GamePlan Scheduler application.
Ready for MBO-4 submission.
