# 🔗 BESTANDS-SAMENHANG & ARCHITECTUUR
## GamePlan Scheduler - Hoe de bestanden samenwerken

In dit document leggen we uit hoe de verschillende onderdelen van de applicatie (PHP, SQL, CSS, JS) met elkaar verbonden zijn.

---

### 1. De "Hoofdmotor" (Functions & DB)
Bijna elke pagina in het project begint met de connectie naar de motor:
*   **db.php**: Maakt de tunnel naar de database open.
*   **functions.php**: Bevat de hersenen. Elke keer als er data moet worden opgeslagen of gecontroleerd, roept een pagina een functie aan uit dit bestand.

---

### 2. De Schil (Header & Footer)
Om te voorkomen dat we de navigatiebalk op elke pagina handmatig moeten typen, gebruiken we `include 'header.php'` en `include 'footer.php'`.
*   **Header**: Bevat de menubalk en de sessie-check.
*   **Footer**: Bevat de copyright-informatie en de afsluitende HTML-tags.

---

### 3. De Gebruikerservaring (Forms & Dashboards)
De kern van de site bestaat uit formulieren:
*   **Toevoegen (`add_*.php`)**: Stuurt data naar `functions.php`.
*   **Bewerken (`edit_*.php`)**: Haalt eerst data op uit de database, vult het formulier in, en stuurt de wijzigingen terug.
*   **Dashboard (`index.php`)**: De centrale plek waar alle data uit verschillende tabellen samenkomt.

---

### 4. Visuele & Interactieve Lagen
*   **style.css**: Wordt door de `header.php` ingeladen en geeft alle pagina's hetzelfde gaming-thema.
*   **script.js**: Draait in de browser en voert snelle checks uit voordat de server wordt belast.

---

### 5. Data Opslag (SQL)
*   **database.sql**: Dit is het bouwplan voor de database. Zonder dit bestand kan de PHP-code nergens data opslaan.

---
**CONCLUSIE**: De GamePlan Scheduler is modulair opgebouwd. Dit betekent dat onderdelen los van elkaar kunnen worden aangepast zonder dat de hele site kapot gaat. Dit maakt de code zeer professioneel en makkelijk te onderhouden.
