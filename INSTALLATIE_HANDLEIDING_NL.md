# 🛠️ INSTALLATIE HANDLEIDING
## GamePlan Scheduler - Stap-voor-stap installeren

Volg deze gids om de GamePlan Scheduler binnen 5 minuten werkend te krijgen op je eigen computer (localhost).

---

### Benodigdheden
*   **XAMPP**: Zorg dat XAMPP (Apache en MySQL) is geïnstalleerd.
*   **Browser**: Chrome, Edge of Firefox.

---

### Stap 1: Bestanden Plaatsen
1.  Open de map van XAMPP (meestal `C:\xampp`).
2.  Ga naar de map `htdocs`.
3.  Kopieer de hele map `gameplan-scheduler` naar deze `htdocs` map.
    *   *Pad*: `C:\xampp\htdocs\gameplan-scheduler`

### Stap 2: Database Aanmaken
1.  Start de **XAMPP Control Panel**.
2.  Zet **Apache** en **MySQL** aan (klik op 'Start').
3.  Klik bij MySQL op de knop **'Admin'** (of ga in je browser naar `http://localhost/phpmyadmin`).
4.  Klik bovenaan op het tabblad **'Import'** (Importeren).
5.  Klik op 'Bestand kiezen' en selecteer het bestand `database.sql` uit de projectmap.
6.  Scroll naar beneden en klik op **'Go'** (Starten).
    *   *Resultaat*: De database `gameplan_scheduler` is nu aangemaakt met alle tabellen.

### Stap 3: De Website Openen
1.  Open je browser.
2.  Typ het volgende adres in:
    ```
    http://localhost/gameplan-scheduler/
    ```
3.  Je ziet nu het login-scherm van de GamePlan Scheduler!

---

### Tips voor het Examen
*   **Login**: Maak eerst een account aan via 'Register'.
*   **Database check**: Als de site een fout geeft over de database, controleer dan in `db.php` of de login-gegevens (username/password) overeenkomen met jouw lokale XAMPP instellingen (standaard is dit `root` zonder wachtwoord).
*   **Responsive**: Druk op `F12` in je browser en kies het mobiele icoon om de site op een telefoon te testen.

---
**VEEL SUCCES!**
