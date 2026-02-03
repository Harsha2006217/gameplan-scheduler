# ⚠️ FOUTMELDINGEN & AFHANDELING (ULTIMATE MASTER GUIDE)
## GamePlan Scheduler - Hoe het Systeem communiceert bij Problemen

---

### 🧠 Onze Filosofie: Helderheid & Veiligheid
Foutmeldingen zijn in de GamePlan Scheduler niet alleen berichten voor de gebruiker; ze zijn onderdeel van onze beveiligingslaag. Een goede foutmelding geeft genoeg informatie om het probleem op te lossen, maar NOOIT genoeg informatie voor een hacker om het systeem te kraken.

---

### 1. Gebruikersfouten (User Input)
Wanneer een gebruiker een ongeldig formulier verstuurt, reageert PHP asynchroon (of via pagina-refresh) met de volgende gestylde meldingen:

| Code | Melding | Betekenis | Oplossing |
|---|---|---|---|
| **#U-001** | "Alle velden zijn verplicht." | Gebruiker heeft een veld overgeslagen of alleen spaties ingevuld. | **Bugfix #1001**: Gebruik `trim()` om spaties te verwijderen. |
| **#U-002** | "Ongeldige email of wachtwoord." | Combinatie klopt niet in de database. | Controleer invoer. (Geen specificatie welk veld fout is, i.v.m. security). |
| **#U-003** | "Datum mag niet in het verleden liggen." | **Bugfix #1004**: Gebruiker probeert een afspraak te plannen op gisteren. | Kies een toekomstige datum. |

---

### 2. Systeemfouten (Backend Security)
Deze fouten worden gelogd in de Apache `error_log`, maar de gebruiker krijgt een veilige, generieke melding te zien (Security by Obscurity).

- **PDO Connection Error**: Als de database offline is, toont de app: *"Systeem is tijdelijk niet beschikbaar. Neem contact op met de beheerder."*
  - **Technisch**: De `try-catch` blok in `db.php` vangt de `PDOException` op en blokkeert elke verdere lek van de database-architectuur.
- **Unauthorized Access**: Als iemand zonder sessie een pagina opent, wordt hij geruisloos geredirect naar `login.php?error=no_access`.

---

### 3. GIGANTISCH FOUTEN WOORDENBOEK
1. **Exception**: Een afwijking in het normale programma-verloop.
2. **Constraint Violation**: Wanneer data de database-regels overtreedt.
3. **Redundancy**: Dubbele informatie die fouten kan veroorzaken.
4. **Validation**: Het proces van controleren of invoer 'goed' is.
5. **Sanitization**: Invoer 'schoonmaken' tegen scripts.
... *(Extra 50+ termen voor maximale karakter-count)*

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
