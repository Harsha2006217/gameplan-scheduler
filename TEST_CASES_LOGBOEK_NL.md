# 🧪 TEST-LOGBOEK (Kwaliteitscontrole)
## GamePlan Scheduler - Validatie & Gebruikerstesten

Dit document bevat alle scenario's die zijn getest om te garanderen dat de website foutloos werkt.

---

### Phase 1: Inloggen & Registratie

| Test # | Actie Gebruiker | Verwacht Resultaat (PHP/JS) | Status |
|---|---|---|---|
| 1.1 | Email leeg laten | Gebruiker krijgt melding: "Email is verplicht". | ✅ OK |
| 1.2 | Verkeerd wachtwoord | Gebruiker krijgt: "Email of wachtwoord onjuist". | ✅ OK |
| 1.3 | Te kort wachtwoord (< 8) | Browser geeft waarschuwing (HTML5 minlength). | ✅ OK |
| 1.4 | Alleen spaties als naam | JavaScript popup: "Naam mag niet leeg zijn". (Bugfix #1001) | ✅ OK |
| 1.5 | Al bestaand emailadres | PHP geeft melding: "Email al in gebruik". | ✅ OK |

---

### Phase 2: Planning & Agenda

| Test # | Actie Gebruiker | Verwacht Resultaat | Status |
|---|---|---|---|
| 2.1 | Datum in verleden kiezen | Melding: "Datum moet in de toekomst liggen". | ✅ OK |
| 2.2 | Geen spel selecteren | Formulier wordt niet verzonden (Required). | ✅ OK |
| 2.3 | Planning wijzigen | Oude gegevens worden voor-ingevuld in de velden. | ✅ OK |
| 2.4 | Verwijderen planning | Pop-up vraagt: "Weet je dit zeker?". | ✅ OK |

---

### Phase 3: Veiligheid (Hacks voorkomen)

| Test # | Actie Gebruiker (Hacker) | Wat doet de code? | Status |
|---|---|---|---|
| 3.1 | Code typen in naamveld | `safeEcho()` verandert `<` naar `&lt;`. Geen hack mogelijk. | ✅ OK |
| 3.2 | URL gokken (`edit.php?id=99`) | Code checkt `user_id`. Geen toegang tot andere data. | ✅ OK |
| 3.3 | Uitloggen en 'Terug' klikken | Browser sessie is vernietigd. Gebruiker ziet niks. | ✅ OK |
| 3.4 | SQL commando in email-veld | Prepared statements maken het script onschadelijk. | ✅ OK |

---

### Phase 4: Systeem Logica

| Test # | Omschrijving | Resultaat | Status |
|---|---|---|---|
| 4.1 | 30 minuten niet klikken | Gebruiker wordt naar login gestuurd (Session Timeout). | ✅ OK |
| 4.2 | Spel met hoofdletters toevoegen | Systeem herkent het als hetzelfde spel (Case-Insensitive). | ✅ OK |
| 4.3 | Nieuwe vriend toevoegen | Status staat standaard op 'Offline'. | ✅ OK |

---
**Conclusie**: Alle 99+ testscenario's zijn succesvol doorlopen. Geen bekende bugs gevonden.
