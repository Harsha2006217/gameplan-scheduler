/**
 * ============================================================================
 * SCRIPT.JS - CLIENT-SIDE JAVASCRIPT / CLIENT-SIDE JAVASCRIPT
 * ============================================================================
 * Author / Auteur: Harsha Kanaparthi | Student: 2195344 | Date: 30-09-2025
 * 
 * ENGLISH: Form validations, reminder pop-ups, and interactive features.
 * DUTCH: Formulier validaties, herinnering pop-ups, en interactieve functies.
 * 
 * FEATURES:
 * - Login/Register form validation
 * - Schedule/Event form validation (BUG FIX #1001: spaces check)
 * - Date validation (BUG FIX #1004: strict format)
 * - Reminder pop-up system
 * ============================================================================
 */

// ============================================================================
// SECTION 1: LOGIN FORM VALIDATION / LOGIN FORMULIER VALIDATIE
// ============================================================================

/**
 * validateLoginForm - Validates the login form before submission
 * validateLoginForm - Valideert het login formulier voor verzending
 * 
 * ENGLISH:
 * This function runs when user clicks "Login" button.
 * It checks if email and password are filled in correctly.
 * Returns true to allow form submission, false to block it.
 * 
 * DUTCH:
 * Deze functie draait wanneer gebruiker op "Login" knop klikt.
 * Het controleert of e-mail en wachtwoord correct ingevuld zijn.
 * Retourneert true om formulierverzending toe te staan, false om te blokkeren.
 * 
 * @returns {boolean} True if valid, false if invalid
 */
function validateLoginForm() {
    // Get the email input value and remove whitespace from start/end
    // Haal de e-mail input waarde op en verwijder witruimte van begin/einde
    const email = document.getElementById('email').value.trim();

    // Get the password input value and remove whitespace
    // Haal de wachtwoord input waarde op en verwijder witruimte
    const password = document.getElementById('password').value.trim();

    // Check if both fields are filled in
    // Controleer of beide velden ingevuld zijn
    if (!email || !password) {
        // Show alert with error message in Dutch and English
        // Toon alert met foutmelding in Nederlands en Engels
        alert('Email and password are required. / E-mail en wachtwoord zijn verplicht.');
        return false; // Block form submission / Blokkeer formulierverzending
    }

    // Validate email format using regular expression (regex)
    // Valideer e-mail formaat met reguliere expressie (regex)
    // Regex explanation: [^\s@]+ means "one or more characters that are not space or @"
    // Regex uitleg: [^\s@]+ betekent "één of meer tekens die geen spatie of @ zijn"
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Invalid email format. / Ongeldig e-mail formaat.');
        return false;
    }

    // All validations passed - allow form submission
    // Alle validaties geslaagd - sta formulierverzending toe
    return true;
}


// ============================================================================
// SECTION 2: REGISTER FORM VALIDATION / REGISTRATIE FORMULIER VALIDATIE
// ============================================================================

/**
 * validateRegisterForm - Validates the registration form
 * validateRegisterForm - Valideert het registratie formulier
 * 
 * ENGLISH:
 * Checks username, email, and password meet requirements:
 * - Username: max 50 characters, not empty
 * - Email: valid format
 * - Password: minimum 8 characters
 * 
 * DUTCH:
 * Controleert of gebruikersnaam, e-mail en wachtwoord aan eisen voldoen:
 * - Gebruikersnaam: max 50 tekens, niet leeg
 * - E-mail: geldig formaat
 * - Wachtwoord: minimaal 8 tekens
 * 
 * @returns {boolean} True if valid, false if invalid
 */
function validateRegisterForm() {
    // Get all form field values with trim to remove whitespace
    // Haal alle formulierveld waarden op met trim om witruimte te verwijderen
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // Check all required fields are filled
    // Controleer of alle verplichte velden ingevuld zijn
    if (!username || !email || !password) {
        alert('All fields are required. / Alle velden zijn verplicht.');
        return false;
    }

    // BUG FIX #1001: Check for spaces-only input using regex
    // BUG FIX #1001: Controleer op alleen-spaties input met regex
    if (/^\s*$/.test(username)) {
        alert('Username cannot be only spaces. / Gebruikersnaam kan niet alleen spaties zijn.');
        return false;
    }

    // Check username length (max 50 characters as per database)
    // Controleer gebruikersnaam lengte (max 50 tekens volgens database)
    if (username.length > 50) {
        alert('Username too long (max 50 characters). / Gebruikersnaam te lang (max 50 tekens).');
        return false;
    }

    // Validate email format
    // Valideer e-mail formaat
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Invalid email format. / Ongeldig e-mail formaat.');
        return false;
    }

    // Check password minimum length (8 characters for security)
    // Controleer wachtwoord minimum lengte (8 tekens voor veiligheid)
    if (password.length < 8) {
        alert('Password must be at least 8 characters. / Wachtwoord moet minimaal 8 tekens zijn.');
        return false;
    }

    return true; // All valid / Alles geldig
}


// ============================================================================
// SECTION 3: SCHEDULE FORM VALIDATION / SCHEMA FORMULIER VALIDATIE
// ============================================================================

/**
 * validateScheduleForm - Validates the add/edit schedule form
 * validateScheduleForm - Valideert het toevoegen/bewerken schema formulier
 * 
 * ENGLISH:
 * Validates gaming schedule:
 * - Game title: required, no spaces-only
 * - Date: must be valid and in future
 * - Time: must be valid HH:MM format
 * - Friends/Shared with: if filled, must be valid comma-separated
 * 
 * DUTCH:
 * Valideert gaming schema:
 * - Speltitel: verplicht, niet alleen spaties
 * - Datum: moet geldig zijn en in de toekomst
 * - Tijd: moet geldig UU:MM formaat zijn
 * - Vrienden/Gedeeld met: indien ingevuld, geldig komma-gescheiden
 * 
 * @returns {boolean} True if valid, false if invalid
 */
function validateScheduleForm() {
    // Get form values
    // Haal formulier waarden op
    const gameTitle = document.getElementById('game_title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const friendsStr = document.getElementById('friends_str').value.trim();
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();

    // BUG FIX #1001: Check game title is not empty or spaces-only
    // BUG FIX #1001: Controleer of speltitel niet leeg of alleen spaties is
    if (!gameTitle || /^\s*$/.test(gameTitle)) {
        alert('Game title is required and cannot be only spaces. / Speltitel is verplicht en kan niet alleen spaties zijn.');
        return false;
    }

    // BUG FIX #1004: Validate date format and ensure it's in the future
    // BUG FIX #1004: Valideer datum formaat en zorg dat het in de toekomst is
    if (!date) {
        alert('Date is required. / Datum is verplicht.');
        return false;
    }

    // Check if date is valid and not in the past
    // Controleer of datum geldig is en niet in het verleden
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set to start of day / Zet naar begin van dag

    if (isNaN(selectedDate.getTime())) {
        alert('Invalid date format. / Ongeldig datum formaat.');
        return false;
    }

    if (selectedDate < today) {
        alert('Date must be today or in the future. / Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Validate time format (HH:MM)
    // Valideer tijd formaat (UU:MM)
    // Regex: 00-23 for hours, 00-59 for minutes
    // Regex: 00-23 voor uren, 00-59 voor minuten
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Invalid time format. Use HH:MM. / Ongeldig tijd formaat. Gebruik UU:MM.');
        return false;
    }

    // Validate comma-separated fields (only letters, numbers, commas, spaces)
    // Valideer komma-gescheiden velden (alleen letters, cijfers, komma's, spaties)
    if (friendsStr && !/^[a-zA-Z0-9,\s]*$/.test(friendsStr)) {
        alert('Friends field contains invalid characters. / Vrienden veld bevat ongeldige tekens.');
        return false;
    }

    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Shared with field contains invalid characters. / Gedeeld met veld bevat ongeldige tekens.');
        return false;
    }

    return true; // All valid
}


// ============================================================================
// SECTION 4: EVENT FORM VALIDATION / EVENEMENT FORMULIER VALIDATIE
// ============================================================================

/**
 * validateEventForm - Validates the add/edit event form
 * validateEventForm - Valideert het toevoegen/bewerken evenement formulier
 * 
 * ENGLISH:
 * Validates gaming event (tournament, stream, etc.):
 * - Title: required, max 100 chars, no spaces-only
 * - Date: valid and in future
 * - Time: valid HH:MM format
 * - Description: max 500 characters
 * - External link: if filled, must be valid URL
 * 
 * DUTCH:
 * Valideert gaming evenement (toernooi, stream, etc.):
 * - Titel: verplicht, max 100 tekens, niet alleen spaties
 * - Datum: geldig en in de toekomst
 * - Tijd: geldig UU:MM formaat
 * - Beschrijving: max 500 tekens
 * - Externe link: indien ingevuld, moet geldige URL zijn
 * 
 * @returns {boolean} True if valid, false if invalid
 */
function validateEventForm() {
    // Get all form field values
    // Haal alle formulierveld waarden op
    const title = document.getElementById('title').value.trim();
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const description = document.getElementById('description').value;
    const externalLink = document.getElementById('external_link').value;
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();

    // BUG FIX #1001: Validate title is not empty or spaces-only
    // BUG FIX #1001: Valideer dat titel niet leeg of alleen spaties is
    if (!title || /^\s*$/.test(title)) {
        alert('Title is required and cannot be only spaces. / Titel is verplicht en kan niet alleen spaties zijn.');
        return false;
    }

    // Check title maximum length (100 chars as per database)
    // Controleer titel maximum lengte (100 tekens volgens database)
    if (title.length > 100) {
        alert('Title too long (max 100 characters). / Titel te lang (max 100 tekens).');
        return false;
    }

    // BUG FIX #1004: Validate date
    // BUG FIX #1004: Valideer datum
    if (!date) {
        alert('Date is required. / Datum is verplicht.');
        return false;
    }

    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (isNaN(selectedDate.getTime())) {
        alert('Invalid date format. Use YYYY-MM-DD. / Ongeldig datum formaat. Gebruik JJJJ-MM-DD.');
        return false;
    }

    if (selectedDate < today) {
        alert('Date must be today or in the future. / Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Validate time format
    // Valideer tijd formaat
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Invalid time format. Use HH:MM. / Ongeldig tijd formaat. Gebruik UU:MM.');
        return false;
    }

    // Check description length (max 500 chars)
    // Controleer beschrijving lengte (max 500 tekens)
    if (description.length > 500) {
        alert('Description too long (max 500 characters). / Beschrijving te lang (max 500 tekens).');
        return false;
    }

    // Validate external URL if provided
    // Valideer externe URL indien opgegeven
    if (externalLink && !/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(externalLink)) {
        alert('Invalid external link format. / Ongeldig externe link formaat.');
        return false;
    }

    // Validate shared with field
    // Valideer gedeeld met veld
    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Shared with field contains invalid characters. / Gedeeld met veld bevat ongeldige tekens.');
        return false;
    }

    return true; // All valid
}


// ============================================================================
// SECTION 5: PAGE LOAD INITIALIZATION / PAGINA LAAD INITIALISATIE
// ============================================================================

/**
 * DOMContentLoaded Event - Runs when page is fully loaded
 * DOMContentLoaded Event - Draait wanneer pagina volledig geladen is
 * 
 * ENGLISH:
 * This code runs automatically when the page finishes loading.
 * Used for initialization tasks like checking reminders.
 * 
 * DUTCH:
 * Deze code draait automatisch wanneer de pagina klaar is met laden.
 * Gebruikt voor initialisatie taken zoals het controleren van herinneringen.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Log that page has loaded (for debugging)
    // Log dat pagina geladen is (voor debuggen)
    console.log('GamePlan Scheduler loaded successfully! / GamePlan Scheduler succesvol geladen!');

    // Initialize any interactive features
    // Initialiseer eventuele interactieve functies
    initializeFeatures();
});


// ============================================================================
// SECTION 6: FEATURE INITIALIZATION / FUNCTIE INITIALISATIE
// ============================================================================

/**
 * initializeFeatures - Sets up interactive page features
 * initializeFeatures - Stelt interactieve pagina functies in
 */
function initializeFeatures() {
    // Add smooth scroll to all internal links
    // Voeg vloeiend scrollen toe aan alle interne links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Add confirmation to all delete buttons
    // Voeg bevestiging toe aan alle verwijder knoppen
    document.querySelectorAll('.btn-danger').forEach(btn => {
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function (e) {
                if (!confirm('Are you sure you want to delete? / Weet je zeker dat je wilt verwijderen?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // Auto-dismiss alerts after 5 seconds
    // Automatisch alerts sluiten na 5 seconden
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        }, 5000);
    });
}


// ============================================================================
// SECTION 7: UTILITY FUNCTIONS / HULP FUNCTIES
// ============================================================================

/**
 * showNotification - Display a toast notification
 * showNotification - Toon een toast notificatie
 * 
 * @param {string} message - Message to display / Bericht om te tonen
 * @param {string} type - 'success', 'error', 'warning' / 'succes', 'fout', 'waarschuwing'
 */
function showNotification(message, type = 'info') {
    // Create notification element
    // Maak notificatie element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    notification.style.cssText = 'position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => notification.remove(), 5000);
}


// ============================================================================
// END OF FILE / EINDE VAN BESTAND
// ============================================================================