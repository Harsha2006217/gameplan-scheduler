# UITLEG script.js (Regel voor Regel)
## GamePlan Scheduler - JavaScript (Client-side Interactie)

**Bestand**: `script.js`
**Doel**: Directe feedback geven aan de gebruiker in de browser, ZONDER de pagina te herladen.

---

### Regel 1-8: Introductie
De browser voert dit bestand uit. Dit gebeurt op jouw computer, niet op de server.

### Regel 12-28: `validateRegisterForm`
```javascript
function validateRegisterForm() {
    let username = document.getElementById('username').value;
    // ...
}
```
**Uitleg**:
*   Deze functie wordt aangeroepen als je op "Registreren" klikt.
*   `document.getElementById`: We pakken wat jij in het vakje hebt getypt.

### Regel 18-21: De Spaties Check (Bugfix #1001)
```javascript
if (username.trim() === "") {
    alert("Username cannot be empty or just spaces!");
    return false;
}
```
**Uitleg**:
*   `trim()`: Knipt spaties weg aan begin en eind.
*   Als er na het knippen niks overblijft (`""`), dan had je alleen spaties getypt.
*   `alert(...)`: Toont een pop-up waarschuwing.
*   `return false`: **Stop!** Het formulier wordt NIET naar de server gestuurd.

### Regel 45-60: `validateEventForm`
```javascript
let selectedDate = new Date(dateInput);
let today = new Date();
today.setHours(0,0,0,0);
```
**Uitleg**:
*   Hier vergelijken we de ingevulde datum met de datum van vandaag.
*   `setHours(0,0,0,0)`: We negeren de tijd (uren/minuten), we kijken puur naar de dag.
*   Als `selectedDate < today`: Foutmelding! "Je kunt niet in het verleden plannen!"

### Regel 85: Bevestiging bij Verwijderen
```javascript
function confirmDelete(itemName) {
    return confirm("Are you sure you want to delete " + itemName + "?");
}
```
**Uitleg**:
*   Dit is de functie die wordt gebruikt bij de prullenbak-knopjes.
*   Het voorkomt dat je per ongeluk iets weggooit.
*   De gebruiker MOET op "OK" klikken, anders gebeurt er niks.

---
**Samenvatting**: JavaScript is de "Voorhoede". Het vangt domme foutjes af voordat ze de server bereiken. Dit maakt de website sneller en gebruiksvriendelijker.
