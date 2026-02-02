# 📅 PLAN VAN AANPAK (PvA)
## GamePlan Scheduler - Projectbeheersing & Planning

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer

---

# 1. Project-achtergrond
Dit project is gestart om de behoefte aan een gecentraliseerde gaming-agenda voor hobby-gamers te vervullen. Het project is uitgevoerd binnen een Agile/Scrum-achtige setting, waarbij iteratieve verbeteringen zijn doorgevoerd op basis van testresultaten.

# 2. Projectgrenzen (Scope)
### Wel in scope:
- Gebruikersbeheer (Registratie/Login).
- Agenda management (Create, Read, Update, Delete).
- Vriendenlijst & Favoriete games.
- Beveiliging (SQLi & XSS preventie).

### Niet in scope:
- Real-time chat (WebSocket).
- Betalingssystemen of premium accounts.

# 3. Kwaliteitswaarborging
Kwaliteit is gewaarborgd door:
1.  **Peer Reviews**: Continue code checks.
2.  **Validatie-Matrix**: 99+ testcases die elke functie controleren.
3.  **Versiebeheer**: Gebruik van Git om wijzigingen te loggen en reverts mogelijk te maken.

# 4. Risico-beheersing
| Risico | Impact | Mitigatie (Oplossing) |
|---|---|---|
| Dataverlies | Hoog | Regelmatige SQL dumps en Git commits. |
| Beveiligingslek | Hoog | Gebruik van Bcrypt en Prepared Statements. |
| Browser incompatibiliteit | Laag | Gebruik van Bootstrap voor een solide CSS basis. |

# 5. Planning Iteraties
- **Week 1-2**: Database ontwerp en kern-PHP functies.
- **Week 3-4**: Front-end realisatie (Glassmorphism) en Validatie-laag.
- **Week 5**: Documentatie-overhaal en Examen-voorbereiding.

---
**GEAUTORISEERD VOOR PORTFOLIO** - Harsha Kanaparthi
