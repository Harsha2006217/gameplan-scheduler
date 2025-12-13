/**
 * ============================================================================
 * script.js - Client-Side JavaScript voor GamePlan Scheduler
 * ============================================================================
 * 
 * @author      Harsha Kanaparthi
 * @student     2195344
 * @date        30-09-2025
 * @version     1.0
 * @project     GamePlan Scheduler
 * 
 * ============================================================================
 * BESCHRIJVING / DESCRIPTION:
 * ============================================================================
 * Dit JavaScript bestand bevat alle client-side functionaliteit:
 * 
 * 1. FORM VALIDATIE - Controleert invoer voordat het naar server gaat
 * 2. BEVESTIGINGSBERICHTEN - Vraagt bevestiging voor destructieve acties
 * 3. REMINDER POP-UPS - Toont herinneringen voor events
 * 4. UI VERBETERINGEN - Interactieve elementen
 * 
 * This JavaScript file contains all client-side functionality for
 * form validation, confirmations, and interactive UI elements.
 * 
 * ============================================================================
 * WAAROM CLIENT-SIDE VALIDATIE?
 * ============================================================================
 * Client-side validatie is AANVULLING op server-side validatie, niet vervanging!
 * 
 * Voordelen:
 * - Snellere feedback aan gebruiker (geen server request nodig)
 * - Betere gebruikerservaring
 * - Minder onnodige server requests
 * 
 * Nadelen / Waarschuwing:
 * - JavaScript kan worden uitgeschakeld
 * - Kan worden omzeild door hackers
 * - ALTIJD server-side validatie nodig!
 * ============================================================================
 */


// ############################################################################
// ##                                                                        ##
// ##            1. LOGIN FORMULIER VALIDATIE                                ##
// ##                                                                        ##
// ############################################################################

/**
 * validateLoginForm() - Valideer Login Formulier
 * 
 * Deze functie wordt aangeroepen wanneer de gebruiker op de login knop klikt.
 * Het controleert of alle velden correct zijn ingevuld voordat het
 * formulier naar de server wordt gestuurd.
 * 
 * @returns {boolean} true om formulier te versturen, false om te blokkeren
 * 
 * HOE WERKT DIT?
 * 1. Functie wordt aangeroepen via onsubmit="return validateLoginForm();"
 * 2. Als functie true teruggeeft: formulier wordt verstuurd
 * 3. Als functie false teruggeeft: formulier wordt NIET verstuurd
 */
function validateLoginForm() {
    // ========================================================================
    // STAP 1: HAAL DE WAARDEN OP UIT DE INVOERVELDEN
    // ========================================================================
    // document.getElementById() vindt een HTML element op basis van zijn id
    // .value haalt de ingevoerde tekst op
    // .trim() verwijdert spaties aan begin en eind (BUG FIX #1001!)
    // ========================================================================
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // ========================================================================
    // STAP 2: CONTROLEER OF VELDEN NIET LEEG ZIJN
    // ========================================================================
    // !email is true als email leeg is (lege string is "falsy" in JavaScript)
    // ========================================================================
    if (!email || !password) {
        // Toon foutmelding aan gebruiker
        alert('Email and password are required.');
        // Return false = formulier wordt NIET verstuurd
        return false;
    }

    // ========================================================================
    // STAP 3: CONTROLEER EMAIL FORMAAT MET REGULIERE EXPRESSIE (REGEX)
    // ========================================================================
    // Regex uitleg:
    // [^\s@]+ = één of meer tekens die NIET spatie of @ zijn
    // @       = letterlijk @-teken
    // [^\s@]+ = één of meer tekens die NIET spatie of @ zijn
    // \.      = letterlijk punt (escaped met \)
    // [^\s@]+ = één of meer tekens die NIET spatie of @ zijn
    // 
    // .test() controleert of de string overeenkomt met het patroon
    // ========================================================================
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address (e.g., name@example.com).');
        return false;
    }

    // ========================================================================
    // STAP 4: ALLES OK - LAAT FORMULIER DOORGAAN
    // ========================================================================
    // Return true = formulier wordt verstuurd naar server
    // ========================================================================
    return true;
}


// ############################################################################
// ##                                                                        ##
// ##            2. REGISTRATIE FORMULIER VALIDATIE                          ##
// ##                                                                        ##
// ############################################################################

/**
 * validateRegisterForm() - Valideer Registratie Formulier
 * 
 * Controleert of alle registratie velden correct zijn:
 * - Username: niet leeg, max 50 karakters
 * - Email: geldig formaat
 * - Password: minimaal 8 karakters
 * 
 * @returns {boolean} true om door te gaan, false om te blokkeren
 */
function validateRegisterForm() {
    // Haal waarden op
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // ========================================================================
    // VALIDATIE: ALLE VELDEN VERPLICHT
    // ========================================================================
    if (!username || !email || !password) {
        alert('All fields are required. Please fill in username, email, and password.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: USERNAME LENGTE
    // ========================================================================
    // .length geeft het aantal karakters in de string
    // ========================================================================
    if (username.length > 50) {
        alert('Username is too long. Maximum 50 characters allowed.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: GEEN SPATIES IN USERNAME (optioneel maar handig)
    // ========================================================================
    // /^\s*$/ matcht strings die ALLEEN whitespace bevatten
    // ========================================================================
    if (/^\s*$/.test(username)) {
        alert('Username cannot be only spaces.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: EMAIL FORMAAT
    // ========================================================================
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: WACHTWOORD LENGTE (minimaal 8 karakters)
    // ========================================================================
    // 8 karakters is een goede balans tussen veiligheid en usability
    // ========================================================================
    if (password.length < 8) {
        alert('Password must be at least 8 characters for security.');
        return false;
    }

    // Alles OK
    return true;
}


// ############################################################################
// ##                                                                        ##
// ##            3. SCHEDULE FORMULIER VALIDATIE                             ##
// ##                                                                        ##
// ############################################################################

/**
 * validateScheduleForm() - Valideer Schedule Formulier
 * 
 * Controleert speelschema formulier:
 * - Game title: verplicht, niet alleen spaties (BUG FIX #1001!)
 * - Date: verplicht, moet in de toekomst zijn (BUG FIX #1004!)
 * - Time: geldig formaat
 * - Friends/Shared: optioneel, maar geldig formaat als ingevuld
 * 
 * @returns {boolean} true om door te gaan, false om te blokkeren
 */
function validateScheduleForm() {
    // Haal waarden op
    const gameTitle = document.getElementById('game_title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;

    // Optionele velden (kunnen null zijn als niet op pagina)
    const friendsElement = document.getElementById('friends_str');
    const sharedElement = document.getElementById('shared_with_str');
    const friendsStr = friendsElement ? friendsElement.value.trim() : '';
    const sharedWithStr = sharedElement ? sharedElement.value.trim() : '';

    // ========================================================================
    // VALIDATIE: GAME TITLE (BUG FIX #1001!)
    // ========================================================================
    // Controleert op lege string EN string met alleen spaties
    // Dit voorkomt dat het profiel wordt opgeslagen met lege games
    // ========================================================================
    if (!gameTitle || /^\s*$/.test(gameTitle)) {
        alert('Game title is required and cannot contain only spaces.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: DATUM IN DE TOEKOMST (BUG FIX #1004!)
    // ========================================================================
    // new Date(date) maakt een datum object van de string
    // new Date() zonder argument geeft de huidige datum/tijd
    // 
    // We resetten de uren naar 0 zodat een datum VANDAAG ook geldig is
    // ========================================================================
    if (!date) {
        alert('Please select a date.');
        return false;
    }

    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset naar begin van vandaag

    if (selectedDate < today) {
        alert('Date must be today or in the future. You cannot schedule in the past.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: TIJD FORMAAT
    // ========================================================================
    // Regex voor 24-uurs formaat: 00:00 tot 23:59
    // ========================================================================
    const timePattern = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
    if (!time || !timePattern.test(time)) {
        alert('Please enter a valid time in HH:MM format (e.g., 14:30).');
        return false;
    }

    // ========================================================================
    // VALIDATIE: KOMMA-GESCHEIDEN VELDEN
    // ========================================================================
    // Controleert dat usernames alleen geldige karakters bevatten
    // [a-zA-Z0-9,\s]* = alleen letters, cijfers, komma's en spaties
    // ========================================================================
    const listPattern = /^[a-zA-Z0-9_,\s]*$/;
    if (friendsStr && !listPattern.test(friendsStr)) {
        alert('Friends field contains invalid characters. Use only letters, numbers, and commas.');
        return false;
    }
    if (sharedWithStr && !listPattern.test(sharedWithStr)) {
        alert('Shared with field contains invalid characters. Use only letters, numbers, and commas.');
        return false;
    }

    // Alles OK
    return true;
}


// ############################################################################
// ##                                                                        ##
// ##            4. EVENT FORMULIER VALIDATIE                                ##
// ##                                                                        ##
// ############################################################################

/**
 * validateEventForm() - Valideer Event Formulier
 * 
 * Controleert evenement formulier met alle velden.
 * 
 * @returns {boolean} true om door te gaan, false om te blokkeren
 */
function validateEventForm() {
    // Haal waarden op
    const title = document.getElementById('title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const descriptionElement = document.getElementById('description');
    const description = descriptionElement ? descriptionElement.value : '';
    const externalLinkElement = document.getElementById('external_link');
    const externalLink = externalLinkElement ? externalLinkElement.value.trim() : '';
    const sharedElement = document.getElementById('shared_with_str');
    const sharedWithStr = sharedElement ? sharedElement.value.trim() : '';

    // ========================================================================
    // VALIDATIE: TITEL (BUG FIX #1001!)
    // ========================================================================
    if (!title || /^\s*$/.test(title)) {
        alert('Event title is required and cannot contain only spaces.');
        return false;
    }

    if (title.length > 100) {
        alert('Event title is too long. Maximum 100 characters allowed.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: DATUM (BUG FIX #1004!)
    // ========================================================================
    if (!date) {
        alert('Please select a date for the event.');
        return false;
    }

    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        alert('Event date must be today or in the future.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: TIJD FORMAAT
    // ========================================================================
    const timePattern = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
    if (!time || !timePattern.test(time)) {
        alert('Please enter a valid time in HH:MM format.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: BESCHRIJVING LENGTE
    // ========================================================================
    if (description.length > 500) {
        alert('Description is too long. Maximum 500 characters allowed.');
        return false;
    }

    // ========================================================================
    // VALIDATIE: EXTERNE LINK (optioneel, maar moet geldig zijn als ingevuld)
    // ========================================================================
    // Eenvoudige URL check: moet beginnen met http:// of https://
    // ========================================================================
    if (externalLink) {
        const urlPattern = /^https?:\/\/.+/i;
        if (!urlPattern.test(externalLink)) {
            alert('External link must be a valid URL starting with http:// or https://');
            return false;
        }
    }

    // ========================================================================
    // VALIDATIE: SHARED WITH (komma-gescheiden)
    // ========================================================================
    const listPattern = /^[a-zA-Z0-9_,\s]*$/;
    if (sharedWithStr && !listPattern.test(sharedWithStr)) {
        alert('Shared with field contains invalid characters.');
        return false;
    }

    // Alles OK
    return true;
}


// ############################################################################
// ##                                                                        ##
// ##            5. DOCUMENT READY - INITIALISATIE                           ##
// ##                                                                        ##
// ############################################################################

/**
 * DOMContentLoaded Event Handler
 * 
 * Deze code wordt uitgevoerd zodra de DOM volledig is geladen.
 * Hier initialiseren we JavaScript functionaliteit.
 * 
 * DOMContentLoaded vs load:
 * - DOMContentLoaded: DOM is klaar (HTML geparsed)
 * - load: alles is geladen (incl. afbeeldingen, CSS, etc.)
 * 
 * DOMContentLoaded is sneller en meestal voldoende.
 */
document.addEventListener('DOMContentLoaded', function () {
    // ========================================================================
    // CONSOLE LOG VOOR DEBUGGING
    // ========================================================================
    // Dit helpt bij ontwikkeling om te zien of het script laadt
    // ========================================================================
    console.log('GamePlan Scheduler - JavaScript loaded successfully');
    console.log('Checking for reminders...');

    // ========================================================================
    // INITIALISEER TOOLTIPS (ALS BOOTSTRAP AANWEZIG)
    // ========================================================================
    // Tooltips zijn kleine pop-ups die verschijnen bij hover
    // ========================================================================
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
    }

    // ========================================================================
    // AUTO-DISMISS ALERTS NA 5 SECONDEN
    // ========================================================================
    // Zoek alle alert elementen en sluit ze automatisch na 5 seconden
    // ========================================================================
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            // Gebruik Bootstrap's Alert class om netjes te sluiten
            if (typeof bootstrap !== 'undefined') {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000); // 5000 milliseconden = 5 seconden
    });

    // ========================================================================
    // NOTIFICATIE TOESTEMMING VRAGEN
    // ========================================================================
    // Web Notifications API voor reminder pop-ups
    // ========================================================================
    if ('Notification' in window) {
        if (Notification.permission === 'default') {
            // Vraag toestemming aan gebruiker
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log('Notification permission granted');
                }
            });
        }
    }
});


// ############################################################################
// ##                                                                        ##
// ##            6. HELPER FUNCTIES / UTILITY FUNCTIONS                      ##
// ##                                                                        ##
// ############################################################################

/**
 * showNotification() - Toon Browser Notificatie
 * 
 * Toont een native browser notificatie als toestemming is gegeven.
 * 
 * @param {string} title   De titel van de notificatie
 * @param {string} message Het bericht
 * @param {string} icon    (optioneel) URL naar icoon
 */
function showNotification(title, message, icon = null) {
    if ('Notification' in window && Notification.permission === 'granted') {
        const options = {
            body: message,
            icon: icon || '/favicon.ico',
            badge: icon || '/favicon.ico',
            vibrate: [200, 100, 200] // Vibratie patroon voor mobiel
        };
        new Notification(title, options);
    } else {
        // Fallback naar alert
        alert(`${title}\n\n${message}`);
    }
}

/**
 * formatDate() - Formatteer Datum voor Weergave
 * 
 * Zet een ISO datum (YYYY-MM-DD) om naar een leesbaar formaat.
 * 
 * @param {string} dateString De datum string
 * @returns {string} Geformatteerde datum (bijv. "15 October 2025")
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

/**
 * formatTime() - Formatteer Tijd voor Weergave
 * 
 * Zet 24-uurs tijd om naar 12-uurs formaat.
 * 
 * @param {string} timeString De tijd string (HH:MM)
 * @returns {string} Geformatteerde tijd (bijv. "2:30 PM")
 */
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}


// ============================================================================
// EINDE VAN JAVASCRIPT BESTAND / END OF JAVASCRIPT FILE
// ============================================================================