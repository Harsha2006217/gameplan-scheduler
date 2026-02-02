# ⚡ VALIDATIE SNEL-OVERZICHT
## GamePlan Scheduler - Overzicht van alle controles

Dit "spiekbriefje" laat per pagina zien welke controles (validaties) er worden uitgevoerd op de gegevens van de gebruiker.

---

### 1. Pagina: Registreren
| Veld | Controle | Waarom? |
|---|---|---|
| Gebruikersnaam | Mag niet leeg zijn of alleen spaties bevatten. | Uniekheid en leesbaarheid. |
| Email | Moet een geldig email formaat zijn (@ en .). | Communicatie en unieke login. |
| Email | Mag nog niet bestaan in de database. | Voorkom dubbele accounts. |
| Wachtwoord | Minimaal 8 tekens lang. | Beveiliging tegen brute-force hacks. |

---

### 2. Pagina: Planning Toevoegen
| Veld | Controle | Waarom? |
|---|---|---|
| Spel | Moet een geldig spel uit jouw lijst zijn. | Correcte database koppeling. |
| Datum | Mag niet in het verleden liggen. | Tijdreizen is niet toegestaan in deze app! |
| Datum | Moet een logische dag zijn (bijv. geen 31 juni). | Dataminimalisatie van fouten. |
| Tijd | Moet in HH:MM formaat zijn. | Duidelijkheid in de dagplanning. |

---

### 3. Pagina: Evenement Toevoegen
| Veld | Controle | Waarom? |
|---|---|---|
| Titel | Verplicht veld. | Je moet weten wat voor event het is. |
| Datum | Standaard geblokkeerd voor verleden (HTML `min`). | Gebruiksgemak (UX). |
| Link | Moet beginnen met `http://` of `https://`. | Zorgen dat de link werkt. |
| Reminder | Keuze uit dropdown (Geen, 1u, 1d). | Functionaliteit. |

---

### 4. Algemene Code-Validaties
*   **XSS**: Alle data die getoond wordt, gaat door `safeEcho`.
*   **SQL**: Alle database acties gaan via `Prepared Statements`.
*   **Eigendom**: Bij bewerken/verwijderen checkt PHP altijd: `user_id == $_SESSION['user_id']`.

---
**EINDE SNEL-OVERZICHT**
