/**
 * ============================================================================
 * script.js - CLIENT-SIDE JAVASCRIPT
 * ============================================================================
 * 
 * AUTEUR: Harsha Kanaparthi | STUDENTNUMMER: 2195344 | DATUM: 30-09-2025
 * 
 * WAT DOET DIT BESTAND?
 * Alle JavaScript code voor de GamePlan Scheduler applicatie.
 * Dit bestand zorgt voor:
 * - Formulier validatie (voordat data naar server wordt gestuurd)
 * - Reminder pop-ups voor events
 * - Interactieve feedback voor gebruikers
 * 
 * WAAROM CLIENT-SIDE VALIDATIE?
 * - Snellere feedback (geen server request nodig)
 * - Betere gebruikerservaring
 * - Minder serverbelasting
 * 
 * LET OP: Server-side validatie (in PHP) is ALTIJD nog nodig!
 * Client-side validatie kan worden omzeild door hackers.
 * ============================================================================
 */


// ============================================================================
// LOGIN FORMULIER VALIDATIE
// ============================================================================

/**
 * validateLoginForm() - Valideert het login formulier
 * 
 * WORDT AANGEROEPEN: onsubmit="return validateLoginForm();"
 * RETURN: true = formulier mag verzonden, false = stopped verzenden
 * 
 * CONTROLES:
 * 1. Email is niet leeg
 * 2. Email heeft geldig formaat (bevat @ en .)
 * 3. Wachtwoord is niet leeg
 */
function validateLoginForm() {
    // Haal waarden op uit formulier velden
    // .value = de ingevoerde tekst
    // .trim() = verwijder spaties aan begin en eind
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    // Controle 1: Zijn beide velden ingevuld?
    if (!email || !password) {
        // alert() toont een pop-up bericht
        alert('Email and password are required.');
        // return false = stop het verzenden van het formulier
        return false;
    }
    
    // Controle 2: Is het email formaat geldig?
    // Regex uitleg: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    // ^ = begin van string
    // [^\s@]+ = één of meer tekens dat geen spatie of @ is
    // @ = letterlijke @
    // [^\s@]+ = domein (bijv. "gmail")
    // \. = letterlijke punt
    // [^\s@]+ = extensie (bijv. "com")
    // $ = einde van string
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Invalid email format. Please use: name@example.com');
        return false;
    }
    
    // Alles OK - formulier mag verzonden worden
    return true;
}


// ============================================================================
// REGISTRATIE FORMULIER VALIDATIE
// ============================================================================

/**
 * validateRegisterForm() - Valideert het registratie formulier
 * 
 * CONTROLES:
 * 1. Alle velden zijn ingevuld
 * 2. Gebruikersnaam maximaal 50 karakters
 * 3. Email heeft geldig formaat
 * 4. Wachtwoord minimaal 8 karakters
 */
function validateRegisterForm() {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    // Controle 1: Alle velden verplicht
    if (!username || !email || !password) {
        alert('All fields are required.');
        return false;
    }
    
    // Controle 2: Gebruikersnaam lengte
    if (username.length > 50) {
        alert('Username is too long (maximum 50 characters).');
        return false;
    }
    
    // Controle 3: Email formaat
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Invalid email format.');
        return false;
    }
    
    // Controle 4: Wachtwoord lengte (beveiliging)
    if (password.length < 8) {
        alert('Password must be at least 8 characters for security.');
        return false;
    }
    
    return true;
}


// ============================================================================
// SCHEDULE FORMULIER VALIDATIE
// ============================================================================

/**
 * validateScheduleForm() - Valideert het speelschema formulier
 * 
 * CONTROLES:
 * 1. Game titel is niet leeg en geen spaties alleen
 * 2. Datum is in de toekomst
 * 3. Tijd heeft correct formaat
 * 4. Vrienden lijst heeft geen lege items
 */
function validateScheduleForm() {
    const gameTitle = document.getElementById('game_title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const friendsStr = document.getElementById('friends_str').value.trim();
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();
    
    // Controle 1: Game titel verplicht (lost BUG #1001 op)
    // /^\s*$/ = regex die alleen spaties matcht
    if (!gameTitle || /^\s*$/.test(gameTitle)) {
        alert('Game title is required and cannot be only spaces.');
        return false;
    }
    
    // Controle 2: Datum moet in de toekomst liggen
    // new Date(date) = maak Date object van de geselecteerde datum
    // new Date() = huidige datum en tijd
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset tijd naar middernacht voor vergelijking
    
    if (!date || selectedDate < today) {
        alert('Date must be today or in the future.');
        return false;
    }
    
    // Controle 3: Tijd formaat (HH:MM)
    // ([01]?[0-9]|2[0-3]) = uur: 0-9, 10-19, of 20-23
    // : = letterlijke dubbele punt
    // [0-5][0-9] = minuten: 00-59
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Invalid time format. Please use HH:MM (e.g., 14:30).');
        return false;
    }
    
    // Controle 4: Geen lege items in komma-gescheiden lijsten
    if (friendsStr && !/^[a-zA-Z0-9,\s]*$/.test(friendsStr)) {
        alert('Friends list can only contain letters, numbers, and commas.');
        return false;
    }
    
    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Shared with list can only contain letters, numbers, and commas.');
        return false;
    }
    
    return true;
}


// ============================================================================
// EVENT FORMULIER VALIDATIE
// ============================================================================

/**
 * validateEventForm() - Valideert het evenement formulier
 * 
 * CONTROLES:
 * 1. Titel is niet leeg en max 100 karakters
 * 2. Datum is in de toekomst
 * 3. Tijd heeft correct formaat
 * 4. Beschrijving maximaal 500 karakters
 * 5. Externe link is een geldige URL
 */
function validateEventForm() {
    const title = document.getElementById('title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const description = document.getElementById('description').value;
    const externalLink = document.getElementById('external_link').value;
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();
    
    // Controle 1: Titel verplicht met max lengte
    if (!title || /^\s*$/.test(title)) {
        alert('Title is required and cannot be only spaces.');
        return false;
    }
    
    if (title.length > 100) {
        alert('Title is too long (maximum 100 characters).');
        return false;
    }
    
    // Controle 2: Datum in toekomst
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (!date || selectedDate < today) {
        alert('Date must be today or in the future.');
        return false;
    }
    
    // Controle 3: Tijd formaat
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Invalid time format. Please use HH:MM.');
        return false;
    }
    
    // Controle 4: Beschrijving lengte
    if (description.length > 500) {
        alert('Description is too long (maximum 500 characters).');
        return false;
    }
    
    // Controle 5: URL formaat (als ingevuld)
    // Deze regex controleert of het begint met http:// of https://
    if (externalLink && !/^https?:\/\/.+/.test(externalLink)) {
        alert('External link must start with http:// or https://');
        return false;
    }
    
    // Controle 6: Shared with formaat
    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Shared with list can only contain letters, numbers, and commas.');
        return false;
    }
    
    return true;
}


// ============================================================================
// REMINDER FUNCTIONALITEIT
// ============================================================================

/**
 * Wanneer de pagina volledig is geladen, controleer op reminders.
 * 
 * DOMContentLoaded = event dat afgaat wanneer HTML is geparsed
 * Dit is beter dan window.onload omdat we niet wachten op afbeeldingen
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('GamePlan Scheduler loaded - Checking for reminders...');
    
    // De reminders worden via PHP (index.php) als JSON doorgegeven
    // en automatisch getoond via een inline script block
});


// ============================================================================
// HULP FUNCTIES
// ============================================================================

/**
 * showNotification() - Toont een mooie notificatie (alternatief voor alert)
 * 
 * @param {string} message - Het bericht om te tonen
 * @param {string} type - 'success', 'error', 'warning', 'info'
 */
function showNotification(message, type = 'info') {
    // Maak container als deze nog niet bestaat
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 9999;';
        document.body.appendChild(container);
    }
    
    // Bepaal kleur op basis van type
    const colors = {
        success: '#198754',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#0dcaf0'
    };
    
    // Maak notificatie element
    const notification = document.createElement('div');
    notification.style.cssText = `
        background: ${colors[type] || colors.info};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    // Voeg toe aan container
    container.appendChild(notification);
    
    // Verwijder na 5 seconden
    setTimeout(() => {
        notification.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}


/**
 * confirmDelete() - Vraagt bevestiging voor verwijderen
 * 
 * @param {string} itemType - Wat wordt verwijderd (bijv. "friend", "event")
 * @returns {boolean} - true als gebruiker bevestigt
 */
function confirmDelete(itemType) {
    return confirm(`Are you sure you want to delete this ${itemType}? This action cannot be undone.`);
}


// ============================================================================
// EINDE VAN HET BESTAND
// ============================================================================