# ⚠️ FOUTMELDINGEN OVERZICHT
## GamePlan Scheduler - Wat betekenen de meldingen?

Dit overzicht helpt je om te begrijpen wat er mis gaat als je een rode melding ziet op de website.

---

### 1. Inloggen & Registratie

| Melding | Betekenis | Oplossing |
|---|---|---|
| "Email already registered" | Dit e-mailadres is al bekend in de database. | Gebruik een ander e-mailadres of log in. |
| "Invalid credentials" | Het e-mailadres of wachtwoord klopt niet. | Controleer op typefouten. |
| "Password too short" | Je wachtwoord moet minimaal 8 tekens lang zijn. | Kies een langer wachtwoord voor de veiligheid. |
| "Invalid email format" | De tekst ziet er niet uit als een mailtje. | Zorg dat er een `@` en een `.` in staat. |

---

### 2. Formulieren (Planning & Events)

| Melding | Betekenis | Oplossing |
|---|---|---|
| "May not be empty or contain only spaces" | Je hebt alleen spaties getypt. | Typ een echte naam of titel in. |
| "Invalid date format" | De datum is niet leesbaar voor de computer. | Gebruik de datum-prikker in je browser. |
| "Date must be today or in the future" | Je probeert een afspraak in het verleden te maken. | Kies een datum van vandaag of later. |
| "Exceeds maximum length" | Je hebt te veel tekst getypt. | Maak de tekst korter (bijv. max 50 of 255 tekens). |

---

### 3. Systeem & Beveiliging

| Melding | Betekenis | Oplossing |
|---|---|---|
| "Session timeout" | Je bent te lang niet actief geweest (30 min). | Log opnieuw in om verder te gaan. |
| "Access denied" | Je probeert iets aan te passen dat niet van jou is. | Je kunt alleen je eigen data bewerken. |
| "Database connection failed" | De server kan de kluis niet openen. | Controleer in XAMPP of MySQL aan staat. |

---
**TIP**: Krijg je een melding die hier niet bij staat? Kijk dan in `functions.php` om te zien welke validatie-regel wordt geactiveerd.
