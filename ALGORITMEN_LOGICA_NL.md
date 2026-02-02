# 🧠 ALGORITMEN & LOGICA (ELITE MASTER GIDS)
## GamePlan Scheduler - De Wiskunde en Logica achter de Software

---

> **Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Project**: GamePlan Scheduler
> 
> "Een algoritme is een stapsgewijze instructie om een probleem op te lossen. In dit document leggen we de 12 meest kritieke algoritmes uit die de GamePlan Scheduler stabiel, veilig en slim maken."

---

# 1. Inleiding: De architectuur van Logica

In de software-engineering is de code slechts de vertaling van een logisch denkproces. De GamePlan Scheduler vertrouwt op precieze algoritmes om te voorkomen dat er corrupte data in de database terechtkomt of dat gebruikers onjuiste informatie te zien krijgen. We maken onderscheid tussen **validatie-algoritmes** (controle) en **business-logica** (berekening).

---

# 2. Server-Side Algoritmes (PHP)

## 2.1 Het "Spatie-Blocker" Algoritme (`validateRequired`)
**Doel**: Voorkomen dat gebruikers lege velden indienen die alleen maar spaties bevatten (**BUG FIX #1001**).
**Functie**: `functions.php`, Lijnen 68-86

```php
function validateRequired($value, $fieldName, $maxLength = 0) {
    // We trimmen de waarde om spatie-fraude te voorkomen.
    $trimmed = trim($value); 
    if (empty($trimmed) || preg_match('/^\s*$/', $value)) {
        return "$fieldName mag niet leeg zijn of alleen uit spaties bestaan.";
    }
    return null;
}
```

**Complexity Analysis**:
- **Tijdscomplexiteit**: O(n) waarbij n de lengte van de string is.
- **Ruimtecomplexiteit**: O(n) voor de tijdelijk getrimde string.
- **Impact**: Garandeert dat de database niet vervuild raakt met zinloze spaties.

## 2.2 Het "Tijdreiziger" Filter (`validateDate`)
**Doel**: Zorgen dat afspraken alleen in de toekomst worden gepland en dat de datum-notatie (JJJJ-MM-DD) strikt wordt gevolgd (**BUG FIX #1004**).

```php
// De implementatie:
$d = DateTime::createFromFormat('Y-m-d', $date);
if (!$d || $d->format('Y-m-d') !== $date) {
    return "Ongeldig datumformaat.";
}
$today = new DateTime('today');
if ($d < $today) {
    return "Datum moet vandaag of in de toekomst liggen.";
}
```

---

# 3. Business Logica: Spel-Referentie Systeem

### 📍 Het `getOrCreateGameId` Algoritme
Dit is een cruciaal algoritme voor database-normalisatie. Het voorkomt dat we hetzelfde spel 100 keer opslaan.

**Algoritme Stappen**:
1. START: Ontvang speltitel van de gebruiker (bijv. "Halo").
2. NORMALISATIE: Zet titel om naar kleine letters.
3. SEARCH: Voer een SELECT query uit in de `Games` tabel.
4. **BESLISSING**:
   - Gevonden? Retourneer de bestaande `game_id`.
   - Niet gevonden?
     - Voer `INSERT INTO Games` uit.
     - Gebruik `lastInsertId()` om het nieuwe ID op te halen.
5. RETOURNEER ID.

---

# 4. Sorteer-Logica (Dashboard)

De applicatie combineert afspraken en evenementen tot één dynamische tijdlijn.

1. Haal Array A (Schedules) op.
2. Haal Array B (Events) op.
3. Gebruik `array_merge()` om ze te combineren.
4. Gebruik `usort()` met een custom callback-functie:
   ```php
   usort($items, function($a, $b) {
       // We vergelijken de datum-strings chronologisch.
       return strtotime($a['date']) - strtotime($b['date']);
   });
   ```

---

# 5. Beveiliging: Het Hashing Algoritme

**Wachtwoord-Logica**:
1. START: Ontvang Plain Text.
2. VOEG Salt toe (Automatisch via PHP).
3. PAS `BCRYPT` toe (minimaal 10 'costs').
4. SLA OP: 60 characters string.

---

# Conclusie

De algoritmes in de GamePlan Scheduler zijn ontworpen met de "Trias Politica" van software in gedachten: **Snelheid, Veiligheid en Gebruikersgemak**. Door elk stukje logica goed te documenteren, is een systeem ontstaan dat robuust genoeg is voor het MBO-4 diploma.

---
**GEAUTORISEERD VOOR EXAMEN - Harsha Kanaparthi**
