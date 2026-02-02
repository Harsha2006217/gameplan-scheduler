# 📜 JAVASCRIPT LOGICA REFERENTIE
## GamePlan Scheduler - Interactiviteit in de Browser

Dit document legt uit wat de JavaScript (`script.js`) precies doet om de website sneller en gebruiksvriendelijker te maken.

---

### 1. Waarom JavaScript?
Hoewel PHP alles op de server controleert, gebruiken we JavaScript om de gebruiker **direct** feedback te geven. Dit voorkomt dat de pagina onnodig moet verversen.

---

### 2. Belangrijkste Functies

#### A. Lege velden checken
Voordat een formulier wordt verstuurd, kijkt JS of de verplichte velden zijn ingevuld.
*   **Code**: `if (input.value.trim() === "")`.
*   **Effect**: De gebruiker krijgt direct een rode rand of een melding te zien.

#### B. Datum Vergelijking
JS controleert of de gekozen datum niet in het verleden ligt.
*   **Hoe**: We maken een `new Date()` object aan van de huidige tijd en vergelijken dat met de waarde uit het formulier.
*   **Resultaat**: "Je kunt geen planning maken voor gisteren!"

#### C. Verwijder-bevestiging
Om te voorkomen dat iemand per ongeluk iets verwijdert, gebruiken we een `confirm()` dialoog.
*   **Vraag**: "Weet je zeker dat je deze vriend wilt verwijderen?"
*   **Logica**: Alleen als je op 'OK' klikt, gaat de browser naar `delete.php`.

---

### 3. DOM Manipulatie
We gebruiken JavaScript om elementen op de pagina aan te passen zonder te herladen.
*   **Filteren**: Bij het zoeken naar spellen verbergen we de rijen die niet overeenkomen met wat je typt.
*   **Klassen**: We voegen Bootstrap klassen toe (zoals `is-invalid`) om fouten visueel te maken.

---

### 4. Event Listeners
De code "luistert" constant naar acties van de gebruiker:
*   `submit`: Controleer alles voordat het naar de server gaat.
*   `click`: Open een menu of toon een waarschuwing.
*   `DOMContentLoaded`: Zorg dat alle code pas start als de hele pagina geladen is.

---
**EINDE JS LOGICA DOCUMENTATIE**
