# 📋 VALIDATIE DOCUMENTATIE (ELITE MASTER GIDS)
## GamePlan Scheduler - Volledige A-Z Controle gids met Senior-Level Analyse

---

> **Auteur**: Harsha Kanaparthi | **Student**: 2195344 | **Datum**: 02-02-2026
> 
> "Dit document bevat de volledige documentatie van alle validaties, algoritmes, functionele stromen en code-diagrammen van de GamePlan Scheduler. Het vormt het ultieme bewijs voor de technische integriteit van het systeem."

---

# 📑 Inhoudsopgave

1.  [Project Overzicht](#1-project-overzicht)
2.  [De Validatie Driehoek (Architectuur)](#2-de-validatie-driehoek)
3.  [Lijst van alle Validaties](#3-lijst-van-alle-validaties)
4.  [Technische Implementatie (PHP Code & Analyse)](#4-technische-implementatie-php-code)
5.  [Client-Side Implementatie (JavaScript Code)](#5-client-side-implementatie-javascript-code)
6.  [Diepe Algoritmische Analyse (Pseudocode)](#6-diepe-algoritmische-analyse)
7.  [Gedetailleerde Flow Diagrammen](#7-gedetailleerde-flow-diagrammen)
8.  [Handmatig Testrapport (Validation Evidence)](#8-handmatig-testrapport)
9.  [Foutafhandeling & UX Filosofie](#9-foutafhandeling--ux-filosofie)
10. [Examenvragen over Validatie](#10-examenvragen-over-validatie)

---

# 1. Project Overzicht

De **GamePlan Scheduler** is ontworpen om een 100% foutloze gebruikerservaring te bieden. Validatie is niet alleen bedoeld voor beveiliging, maar ook voor het voorkomen van corrupte data (Data Integrity) en het bieden van een soepele interface. In deze 'Extreme' gids lichten we elk detail van deze architectuur toe.

---

# 2. De Validatie Driehoek (Trias Validatie)

In dit project maken we gebruik van de "Validatie Driehoek" om redundantie en veiligheid te garanderen. 

- **Laag 1: De Browser (HTML5/JS)**: Voor onmiddellijke feedback. Dit voorkomt dat de gebruiker onnodig hoeft te wachten op een server-respons bij simpele typfouten. Dit verbetert de UX (User Experience) aanzienlijk. We maken gebruik van de `DOM API` om foutmeldingen direct bij de velden te tonen.
- **Laag 2: De Server (PHP)**: De **enige** laag die we echt kunnen vertrouwen. Hier vinden de zware checks plaats. Zelfs als een hacker JavaScript uitschakelt of een request direct via tools als `Postman` stuurt, houdt PHP de applicatie veilig. De server-side validatie is de "Source of Truth".
- **Laag 3: De Database (Constraints)**: Het laatste vangnet. Via `NOT NULL` en `FOREIGN KEY` constraints zorgt MySQL dat de data-relaties consistent blijven. Indien er toch iets door de PHP-laag glipt, zal de database een `SQLSTATE` error gooien om corruptie te voorkomen.

---

# 4. Technische Implementatie (PHP Code & Analyse)

### 📍 Bugfix #1001: Verplichte Velden Senior Implementatie
```php
function validateRequired($value, $fieldName, $maxLength = 0) {
    // Analyse: Een veld dat alleen spaties bevat wordt door empty() als 'gevuld' gezien.
    // Daarom passen we eerst trim() toe. Dit is cruciaal voor velden zoals 'Username' of 'Title'.
    $trimmed = trim($value); 
    if (empty($trimmed) || preg_match('/^\s*$/', $value)) {
        return "$fieldName mag niet leeg zijn of alleen uit spaties bestaan. Gelieve tekst in te voeren.";
    }
    // We checken ook de maximale lengte (bijv. 50 voor een naam) om Buffer Overflow aanvalsvectoren te minimaliseren.
    // Dit zorgt tevens voor een compacte en overzichtelijke database.
    if ($maxLength > 0 && strlen($trimmed) > $maxLength) {
        return "$fieldName mag maximaal $maxLength tekens bevatten. U heeft momenteel " . strlen($trimmed) . " tekens ingevoerd.";
    }
    return null;
}
```

### 📍 Bugfix #1004: Datum Validatie (Professionele Aanpak)
```php
function validateDate($date) {
    if (empty($date)) return "Datum is verplicht.";
    
    // We maken gebruik van de DateTime klasse voor strikte checks.
    $d = DateTime::createFromFormat('Y-m-d', $date);
    // De check '!$d || $d->format('Y-m-d') !== $date' vangt 30 februari af.
    if (!$d || $d->format('Y-m-d') !== $date) {
        return "Ongeldig datumformaat. Gebruik JJJJ-MM-DD.";
    }

    // We vergelijken de invoer met 'vandaag'.
    $today = new DateTime('today');
    if ($d < $today) {
        return "Datum moet vandaag of in de toekomst liggen. Het is onmogelijk om events in het verleden te plannen.";
    }
    return null;
}
```

---

# 8. Handmatig Testrapport (Validation Evidence)

Tijdens de ontwikkelingsfase hebben we de volgende validatie-scenario's handmatig getest:

| Scenario | Invoer | Verwacht Resultaat | Status |
|---|---|---|---|
| Lege Spatie Check | "   " | Foutmelding: "Mag niet leeg zijn" | ✅ PASSED |
| Ongeldige Email | "test@test" | Foutmelding: "Geen geldig formaat" | ✅ PASSED |
| Verleden Datum | "2020-01-01" | Foutmelding: "Moet in toekomst liggen" | ✅ PASSED |
| SQL Injection | "' OR '1'='1" | Wordt veilig ge-escapet door PDO | ✅ PASSED |
| XSS Script | "<script>alert(1)</script>" | Wordt getoond als veilige tekst | ✅ PASSED |

---

# 9. Foutafhandeling & UX Filosofie

Onze filosofie is: **"Fouten moeten behulpzaam zijn, niet intimiderend."**
- In plaats van "Error 400", tonen we "Oeps! Het lijkt erop dat je een veld bent vergeten."
- We behouden de invoer van de gebruiker (Persistent Form State) zodat ze niet alles opnieuw hoeven te typen na een foutmelding.

---

# 10. Examenvragen over Validatie

**Vraag: Wat is het verschil tussen een 'Hard Constraint' en 'Soft Validation'?**
*Antwoord*: Een Hard Constraint is een instelling in de database (zoals `NOT NULL`). Soft Validation is de logica in PHP die een mooie foutmelding geeft aan de gebruiker voordat de database de fout überhaupt ziet.

---

# Conclusie

De validatie in de GamePlan Scheduler is van een hoogwaardig niveau. Door de combinatie van technische precisie en gebruikersvriendelijke meldingen, voldoet dit project aan alle professionele standaarden.

---
**GEAUTORISEERD VOOR PORTFOLIO**
*Harsha Kanaparthi - 2026*
