# 🛠️ TECHNISCH ONTWERP (TO)
## GamePlan Scheduler - Systeem Architectuur & Realisatie

---

> **Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Versie**: 1.1 (Examen Editie)

---

# 1. Technologie Stack
- **Server**: Apache (via XAMPP).
- **Backend**: PHP 8.1 (met PDO voor database interactie).
- **Frontend**: HTML5, Vanilla CSS (Glassmorphism), JavaScript (ES6).
- **Database**: MySQL 8.0 (Innodb engine).

# 2. Database Model
De database bestaat uit 4 kern-tabellen:
1.  **Users**: Opslag van accounts (Email uniek, BCrypt hashes).
2.  **Games**: Genormaliseerde tabel voor speltitels (voorkomt dubbele data).
3.  **Schedules**: De koppeling tussen datum, tijd en speler.
4.  **Friends**: Relatietabel voor sociale interactie.

# 3. Beveiligings-Architectuur

### Prepared Statements (Anti-SQLi)
```php
$stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->execute([$email]);
```
Door deze scheiding van commando en data is SQL-injectie onmogelijk.

### XSS Preventie (`safeEcho`)
Data uit de database wordt ALTIJD gefilterd:
```php
function safeEcho($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
```

# 4. Applicatie-Structuur (Modulair)
- **`config/db.php`**: Database verbinding (Singleton patroon).
- **`logic/functions.php`**: Alle business logica en validatie op één plek.
- **`assets/style.css`**: Centrale styling met CSS variabelen voor thema-behoud.

# 5. Algoritmes
Zie het document `ALGORITMEN_LOGICA_NL.md` voor een gedetailleerde uitleg van de sorteer- en validatie-algoritmes.

---
**DOCUMENT STATUS**: Definitief voor inlevering.
