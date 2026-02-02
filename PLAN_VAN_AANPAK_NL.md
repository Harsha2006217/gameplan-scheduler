# 📅 PLAN VAN AANPAK (PvA) - MASTER EDITIE
## GamePlan Scheduler - Projectbeheersing, Methodologie & Kwaliteit

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer | **Project**: GamePlan Scheduler
> 
> "Een goed begin is het halve werk. Dit document beschrijft de strategie en planning die zijn gehanteerd om de GamePlan Scheduler te realiseren binnen de gestelde tijd en kwaliteitseisen."

---

# 1. Projectachtergrond & Probleemstelling

Veel fanatieke gamers hebben moeite met het organiseren van hun hobby. De versnippering van afspraken over verschillende platformen (Discord, WhatsApp, in-game) leidt tot "no-shows" en irritatie. 

**De Uitdaging**: Hoe bouwen we een tool die simpel genoeg is voor dagelijks gebruik, maar technisch robuust genoeg om gebruikersgegevens veilig te beheren? Dit project bewijst mijn vermogen om een dergelijk vraagstuk om te zetten in werkende software.

---

# 2. Projectgrenzen (Scope)

Om de focus te behouden op **technische uitmuntendheid** boven kwantiteit, zijn de volgende grenzen getrokken:

### ✅ Binnen Scope:
- **Veiligheids-Core**: Authenticatie met sterke hashing en PDO.
- **Agenda-Logic**: Volledige CRUD (Create, Read, Update, Delete) voor planningen.
- **Sociale Connectie**: Vriendenbeheer en favoriete game-tracking.
- **UX/UI**: Een modern Glassmorphism design dat op alle schermen werkt.

### ❌ Buiten Scope:
- **Echte API-koppelingen**: Live data ophalen van Steam/PlayStation (vanwege tijdgebrek en complexiteit van 3rd party API's).
- **Betalingssystemen**: De app is bedoeld als een gratis community tool.

---

# 3. Ontwikkelmethode: Agile/Scrum (Lite)

Gezien de relatief korte looptijd van het project is gekozen voor een **iteratieve ontwikkelmethode**.

1.  **Iteratie 1 (De Fundering)**: Database ontwerp en de connectie-laag (`db.php`).
2.  **Iteratie 2 (De Motor)**: Realisatie van de PHP backend functies (`functions.php`).
3.  **Iteratie 3 (Het Gezicht)**: Bouwen van de HTML/CSS templates.
4.  **Iteratie 4 (Veiligheid & Testen)**: Implementatie van de validatie-matrix en security fixes.
5.  **Iteratie 5 (Overhaal)**: Documentatie en examen-voorbereiding.

---

# 4. Ontwikkelomgeving & Tools

Voor een professionele workflow zijn de volgende keuzes gemaakt:
- **IDE**: Visual Studio Code (met PHP Intelephense voor statische analyse).
- **Local Host**: XAMPP (Apache 2.4 / PHP 8.1.10).
- **Databasebeheer**: PHPMyAdmin voor visuele modellering.
- **Versiebeheer**: Git (lokaal) voor het loggen van de voortgang en het kunnen 'reverten' bij kritieke bugs.

---

# 5. Kwaliteits- & Risicobeheersing

| Risico | Kans | Impact | Mitigatie (Oplossing) |
|---|---|---|---|
| **SQL Injection** | Laag | Kritiek | Gebruik van de PDO driver met prepared statements. |
| **Data Corruptie** | Middel | Hoog | Gebruik van Foreign Keys en Database-Constraints. |
| **UX-Frictie** | Middel | Laag | Client-side validatie voor directere feedback. |
| **Browser Fouten** | Laag | Laag | Testen in Chrome, Firefox en Edge. |

---

# 6. Kwaliteitscriteria

Het project is pas "geslaagd" als het voldoet aan de volgende eisen:
- **Security Audit**: Geen directie query-interpolatie in de hele app.
- **Code Hygiëne**: Geen onnodige witruimte, consistente inspringing en Nederlands/Engels commentaar.
- **Performance**: Pagina's moeten lokaal binnen < 200ms inladen.
- **Toegankelijkheid**: Kleurcontrasten moeten voldoen aan de basis WCAG-richtlijnen.

---

# 7. Conclusie

Door deze gestructureerde aanpak is de GamePlan Scheduler niet zomaar "bij elkaar geprogrammeerd", maar is er sprake van een **planmatig software-engineering proces**. De focus op veiligheid vanaf de eerste dag (Iteratie 1) heeft geleid tot een product dat klaar is voor de echte wereld.

---
**GEAUTORISEERD VOOR EXAMEN**
*Harsha Kanaparthi - 2026*
