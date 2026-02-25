/**
 * ============================================================================
 * SCRIPT.JS - CLIENT-SIDE JAVASCRIPT
 * ============================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bevat alle client-side validatie, herinnerings-pop-ups en interactieve functies.
 *
 * FUNCTIES:
 * - Login/registratie formuliervalidatie
 * - Schema/evenement formuliervalidatie
 * - Herinnerings-pop-up systeem
 * - Pagina-initialisatie (smooth scroll, auto-wegklikken van meldingen)
 *
 * BUGFIXES:
 * - #1001: Alleen-spaties controle met regex /^\s*$/
 * - #1004: Strenge datumvalidatie met new Date() + isNaN()
 * ============================================================================
 */

// ============================================================================
// SECTIE 1: LOGIN FORMULIERVALIDATIE
// ============================================================================

/**
 * validateLoginForm - Valideert het inlogformulier voor verzending.
 *
 * Deze functie draait wanneer de gebruiker op de "Inloggen"-knop klikt.
 * Het controleert of e-mailadres en wachtwoord correct zijn ingevuld.
 * Retourneert true om de verzending toe te staan, false om te blokkeren.
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateLoginForm() {
    // Haal de invoerwaarden op en verwijder witruimte van begin en einde
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // Controleer of beide velden ingevuld zijn
    if (!email || !password) {
        alert('E-mailadres en wachtwoord zijn verplicht.');
        return false; // Blokkeer de formulierverzending
    }

    // Valideer het e-mailformaat met een reguliere expressie
    // Uitleg regex: [^\s@]+ = één of meer tekens die geen spatie of @ zijn
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Ongeldig e-mailformaat.');
        return false;
    }

    // Alle validaties geslaagd — sta de verzending toe
    return true;
}


// ============================================================================
// SECTIE 2: REGISTRATIE FORMULIERVALIDATIE
// ============================================================================

/**
 * validateRegisterForm - Valideert het registratieformulier.
 *
 * Controleert of gebruikersnaam, e-mailadres en wachtwoord aan de eisen voldoen:
 * - Gebruikersnaam: max. 50 tekens, niet leeg (BUGFIX #1001)
 * - E-mailadres: geldig formaat
 * - Wachtwoord: minimaal 8 tekens
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateRegisterForm() {
    // Haal alle formulierwaarden op met trim om witruimte te verwijderen
    const username = document.getElementById('username').value.trim();
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    // Controleer of alle verplichte velden ingevuld zijn
    if (!username || !email || !password) {
        alert('Alle velden zijn verplicht.');
        return false;
    }

    // BUGFIX #1001: Controleer op alleen-spaties invoer met regex
    if (/^\s*$/.test(username)) {
        alert('Gebruikersnaam mag niet alleen uit spaties bestaan.');
        return false;
    }

    // Controleer de maximale lengte (max. 50 tekens)
    if (username.length > 50) {
        alert('Gebruikersnaam is te lang (max. 50 tekens).');
        return false;
    }

    // Valideer het e-mailformaat
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Ongeldig e-mailformaat.');
        return false;
    }

    // Controleer de minimale wachtwoordlengte (8 tekens voor veiligheid)
    if (password.length < 8) {
        alert('Wachtwoord moet minimaal 8 tekens bevatten.');
        return false;
    }

    return true; // Alles geldig
}


// ============================================================================
// SECTIE 3: SCHEMA FORMULIERVALIDATIE
// ============================================================================

/**
 * validateScheduleForm - Valideert het schema toevoegen/bewerken formulier.
 *
 * Valideert het gaming-schema:
 * - Speltitel: verplicht, niet alleen spaties (BUGFIX #1001)
 * - Datum: moet geldig zijn en vandaag of in de toekomst (BUGFIX #1004)
 * - Tijd: moet geldig UU:MM-formaat zijn
 * - Vrienden/Gedeeld met: indien ingevuld, kommagescheiden
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateScheduleForm() {
    // Haal de formulierwaarden op
    const gameTitle    = document.getElementById('game_title').value.trim();
    const date         = document.getElementById('date').value;
    const time         = document.getElementById('time').value;
    const friendsStr   = document.getElementById('friends_str').value.trim();
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();

    // BUGFIX #1001: Controleer of de speltitel niet leeg of alleen spaties is
    if (!gameTitle || /^\s*$/.test(gameTitle)) {
        alert('Speltitel is verplicht en mag niet alleen uit spaties bestaan.');
        return false;
    }

    // BUGFIX #1004: Valideer het datumformaat en controleer of het in de toekomst is
    if (!date) {
        alert('Datum is verplicht.');
        return false;
    }

    // Controleer of de datum geldig is en niet in het verleden
    const selectedDate = new Date(date);
    const today        = new Date();
    today.setHours(0, 0, 0, 0); // Zet naar het begin van de dag

    if (isNaN(selectedDate.getTime())) {
        alert('Ongeldig datumformaat.');
        return false;
    }

    if (selectedDate < today) {
        alert('Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Valideer het tijdformaat (UU:MM)
    // Regex: 00-23 voor uren, 00-59 voor minuten
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Ongeldig tijdformaat. Gebruik UU:MM.');
        return false;
    }

    // Valideer kommagescheiden velden (alleen letters, cijfers, komma's en spaties)
    if (friendsStr && !/^[a-zA-Z0-9,\s]*$/.test(friendsStr)) {
        alert('Het veld Vrienden bevat ongeldige tekens.');
        return false;
    }

    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Het veld Gedeeld met bevat ongeldige tekens.');
        return false;
    }

    return true; // Alles geldig
}


// ============================================================================
// SECTIE 4: EVENEMENT FORMULIERVALIDATIE
// ============================================================================

/**
 * validateEventForm - Valideert het evenement toevoegen/bewerken formulier.
 *
 * Valideert het gaming-evenement (toernooi, stream, etc.):
 * - Titel: verplicht, max. 100 tekens, niet alleen spaties (BUGFIX #1001)
 * - Datum: geldig en in de toekomst (BUGFIX #1004)
 * - Tijd: geldig UU:MM-formaat
 * - Beschrijving: max. 500 tekens
 * - Externe link: indien ingevuld, moet een geldige URL zijn
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateEventForm() {
    // Haal alle formulierwaarden op
    const title         = document.getElementById('title').value.trim();
    const date          = document.getElementById('date').value;
    const time          = document.getElementById('time').value;
    const description   = document.getElementById('description').value;
    const externalLink  = document.getElementById('external_link').value;
    const sharedWithStr = document.getElementById('shared_with_str').value.trim();

    // BUGFIX #1001: Valideer dat de titel niet leeg of alleen spaties is
    if (!title || /^\s*$/.test(title)) {
        alert('Titel is verplicht en mag niet alleen uit spaties bestaan.');
        return false;
    }

    // Controleer de maximale titellengte (100 tekens)
    if (title.length > 100) {
        alert('Titel is te lang (max. 100 tekens).');
        return false;
    }

    // BUGFIX #1004: Valideer de datum
    if (!date) {
        alert('Datum is verplicht.');
        return false;
    }

    const selectedDate = new Date(date);
    const today        = new Date();
    today.setHours(0, 0, 0, 0);

    if (isNaN(selectedDate.getTime())) {
        alert('Ongeldig datumformaat. Gebruik JJJJ-MM-DD.');
        return false;
    }

    if (selectedDate < today) {
        alert('Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Valideer het tijdformaat
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(time)) {
        alert('Ongeldig tijdformaat. Gebruik UU:MM.');
        return false;
    }

    // Controleer de beschrijvingslengte (max. 500 tekens)
    if (description.length > 500) {
        alert('Beschrijving is te lang (max. 500 tekens).');
        return false;
    }

    // Valideer de externe URL indien opgegeven
    if (externalLink && !/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(externalLink)) {
        alert('Ongeldig externe link formaat.');
        return false;
    }

    // Valideer het veld Gedeeld met
    if (sharedWithStr && !/^[a-zA-Z0-9,\s]*$/.test(sharedWithStr)) {
        alert('Het veld Gedeeld met bevat ongeldige tekens.');
        return false;
    }

    return true; // Alles geldig
}


// ============================================================================
// SECTIE 5: PAGINA-INITIALISATIE
// ============================================================================

/**
 * DOMContentLoaded - Draait wanneer de pagina volledig geladen is.
 *
 * Deze code draait automatisch wanneer de pagina klaar is met laden.
 * Gebruikt voor initialisatietaken zoals het activeren van interactieve functies.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Log dat de pagina geladen is (voor debuggen)
    console.log('GamePlan Scheduler succesvol geladen!');

    // Initialiseer alle interactieve functies
    initialiseerFuncties();
});


// ============================================================================
// SECTIE 6: FUNCTIE INITIALISATIE
// ============================================================================

/**
 * initialiseerFuncties - Stelt interactieve paginafuncties in.
 */
function initialiseerFuncties() {
    // Voeg smooth scroll toe aan alle interne links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Voeg bevestiging toe aan alle verwijderknoppen
    document.querySelectorAll('.btn-danger').forEach(btn => {
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function (e) {
                if (!confirm('Weet je zeker dat je dit wilt verwijderen?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // Sluit meldingen automatisch na 5 seconden
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
        }, 5000);
    });
}


// ============================================================================
// SECTIE 7: HULPFUNCTIES
// ============================================================================

/**
 * toonMelding - Toon een toast-melding op de pagina.
 *
 * @param {string} bericht - Bericht om te tonen
 * @param {string} type    - 'success', 'danger', 'warning', 'info'
 */
function toonMelding(bericht, type = 'info') {
    // Maak een meldingselement aan
    const melding = document.createElement('div');
    melding.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
    melding.style.cssText = 'position: fixed; top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
    melding.innerHTML = `
        ${bericht}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Voeg toe aan de pagina
    document.body.appendChild(melding);

    // Verwijder automatisch na 5 seconden
    setTimeout(() => melding.remove(), 5000);
}


// ============================================================================
// EINDE VAN BESTAND
// ============================================================================