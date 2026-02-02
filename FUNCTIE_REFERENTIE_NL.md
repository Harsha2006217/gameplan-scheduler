# ⚙️ FUNCTIE REFERENTIE (ULTIMATE LEGENDARY GIDS)
## GamePlan Scheduler - Overzicht van alle Business Logica & Componenten

---

> **Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Versie**: 1.5 (Legendary Edition)
> 
> "Dit document biedt een technisch naslagwerk voor elke functie binnen de GamePlan Scheduler. Het beschrijft de input, output, security-context en de rol binnen het grotere geheel van de applicatie."

---

# 1. PHP Functies (`functions.php`)

## 1.1 Sessie & Authenticatie Management

### 🔵 `initSession()` (Line 32)
- **Doel**: Het veilig opstarten van de PHP-sessie.
- **Security**: Inclusief `cookie_httponly` en `cookie_samesite` vlaggen.

### 🔵 `isLoggedIn()` (Line 211)
- **Output**: `boolean`
- **Doel**: Controleren of de gebruiker geautoriseerd is.

### 🔵 `loginUser($email, $password)` (Line 296)
- **Input**: Email & Wachtwoord (Plain Text).
- **Security**: 
  - Gebruikt `password_verify` tegen de database hash.
  - Voert `session_regenerate_id(true)` uit na succes.
- **Output**: `null` bij succes, of een foutmelding bij falen.

---

## 1.2 Geavanceerde Validatie Functies

### 🟢 `validateRequired($value, $fieldName, $max)` (Line 68)
- **Input**: Veldwaarde, naam en optionele max lengte.
- **Logica**: Gebruikt `trim()` om spatie-fraude te detecteren (**Bugfix #1001**).

### 🟢 `validateDate($date)` (Line 97)
- **Input**: Datum string.
- **Logica**: Gebruikt de PHP `DateTime` klasse voor strikte verificatie (**Bugfix #1004**).

---

## 1.3 Database Interactie (CRUD Master)

### 🟠 `addSchedule($userId, $gameTitle, $date, $time, $friends, $sharedWith)`
- **Doel**: Het opslaan van een nieuwe speelsessie.
- **Transactie**: Roept intern `getOrCreateGameId()` aan.

### 🟠 `getSchedules($userId, $sortBy, $sortOrder)`
- **Security**: Bevat de `deleted_at IS NULL` clausule.
- **Sorteer-Logic**: Gebruikt veilige `ORDER BY` clausules.

---

## 1.4 Beveiligings-Helpers

### 🛡️ `safeEcho($text)` (Line 50)
- **Doel**: XSS Protection.
- **Techniek**: `htmlspecialchars($text, ENT_QUOTES, 'UTF-8')`.

### 🛡️ `checkOwnership($pdo, $table, $id, $userId)` (Line 640)
- **Doel**: Authorization/Toegangscontrole op record-niveau.

---

# 2. JavaScript Functies (`script.js`)

### 🟡 `validateLoginForm()`
- **Check**: Browser-level feedback voor direct gebruikersgemak.

### 🟡 `initializeFeatures()`
- **Features**: Smooth scrolling, confirm dialogs en alert timeouts.

---

# 3. Architectuur Patterns (Senior Analysis)

In dit project zijn verschillende design patterns toegepast:
1. **Singleton-lite (DB)**: Eén centrale `getConnection` functie.
2. **Separation of Concerns**: Logic (PHP) is gescheiden van Interaction (JS).
3. **Defense in Depth**: Meerdere validatie-lagen.

---

# Conclusie

De GamePlan Scheduler codebase is gebouwd om te schalen en veilig te zijn. Elke functie is gedocumenteerd, beveiligd en geoptimaliseerd voor het MBO-4 examen.

---
**GEAUTORISEERD VOOR PORTFOLIO - Harsha Kanaparthi**
