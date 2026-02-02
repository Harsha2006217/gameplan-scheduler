# 🔧 OMGEVINGS- & DEBUG GIDS
## "Wat te doen als het niet werkt?"

Harsha, als de examinator het project op een andere computer installeert en er gaat iets mis, stuur hem dan direct naar dit bestand.

---

### 1. "Ik zie een witte pagina of database fout"
- **Oorzaak**: De database verbinding mislukt waarschijnlijk.
- **Oplossing**:
    1. Check of MySQL aanstaat in het XAMPP Control Panel.
    2. Open `db.php` en controleer of `DB_USER` op `'root'` staat en `DB_PASS` op `''`.
    3. Controleer in PHPMyAdmin of de database de naam `gameplan_db` heeft.

### 2. "Sommige plaatjes of stijlen laden niet"
- **Oorzaak**: Bestandsrechten of verkeerde map-structuur.
- **Oplossing**: Zorg dat het project direct in de `htdocs` map staat, bijvoorbeeld: `C:\xampp\htdocs\gameplan-scheduler\`. Ga dan naar `localhost/gameplan-scheduler`.

### 3. "De website is traag of geeft een Session Error"
- **Oorzaak**: Verouderde sessie-data in je browser.
- **Oplossing**: Verwijder je cookies of open de site in een Incognito-venster.

### 4. "Ik kan geen datum in het verleden opgeven"
- **Geen fout, maar een feature**: Dit is Bugfix #1004. De website beschermt je tegen onlogische planningen.

---

### 💡 Tip voor de Examinator
Gebruik de [INSTALLATIE_HANDLEIDING_NL.md](file:///c:/xampp/htdocs/K1-W3-gameplan-scheduler-Harsha%20Kanaparthi/gameplan-scheduler/INSTALLATIE_HANDLEIDING_NL.md) voor een vlekkeloze start. 

Mochten er toch problemen zijn, neem dan contact op met de ontwikkelaar (Harsha Kanaparthi).
