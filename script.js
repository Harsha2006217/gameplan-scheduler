/**
 * ==========================================================================
 * SCRIPT.JS - CLIENT-SIDE JAVASCRIPT
 * ==========================================================================
 * Auteur: Harsha Kanaparthi | Studentnummer: 2195344 | Datum: 30-09-2025
 *
 * Bevat alle client-side validatie, herinnering pop-ups en interactieve functies.
 *
 * Functies:
 * - Login/Registratie formulier validatie
 * - Schema/Evenement formulier validatie
 * - Herinnering pop-up systeem
 * - Pagina-initialisatie (vloeiend scrollen, automatisch sluiten meldingen)
 *
 * Bugfixes:
 * - #1001: Alleen-spaties controle met regex /^\s*$/
 * - #1004: Strenge datumvalidatie met new Date() + isNaN()
 * ==========================================================================
 */


// ==========================================================================
// SECTIE 1: LOGIN FORMULIER VALIDATIE
// ==========================================================================

/**
 * validateLoginForm - Valideert het login formulier voor verzending
 *
 * Deze functie draait wanneer de gebruiker op de "Inloggen" knop klikt.
 * Het controleert of e-mail en wachtwoord correct zijn ingevuld.
 * Retourneert true om verzending toe te staan, false om te blokkeren.
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateLoginForm() {
    // Haal de e-mail waarde op en verwijder witruimte
    const emailAdres = document.getElementById('email').value.trim();

    // Haal het wachtwoord op en verwijder witruimte
    const wachtwoord = document.getElementById('password').value.trim();

    // Controleer of beide velden ingevuld zijn
    if (!emailAdres || !wachtwoord) {
        alert('E-mail en wachtwoord zijn verplicht.');
        return false;
    }

    // Valideer e-mail formaat met reguliere expressie
    // [^\s@]+ betekent: een of meer tekens die geen spatie of @ zijn
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAdres)) {
        alert('Ongeldig e-mail formaat.');
        return false;
    }

    // Alle validaties geslaagd - sta verzending toe
    return true;
}


// ==========================================================================
// SECTIE 2: REGISTRATIE FORMULIER VALIDATIE
// ==========================================================================

/**
 * validateRegisterForm - Valideert het registratie formulier
 *
 * Controleert of gebruikersnaam, e-mail en wachtwoord aan eisen voldoen:
 * - Gebruikersnaam: max 50 tekens, niet leeg, geen alleen spaties
 * - E-mail: geldig formaat
 * - Wachtwoord: minimaal 8 tekens
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateRegisterForm() {
    // Haal alle formulier waarden op met trim
    const gebruikersnaam = document.getElementById('username').value.trim();
    const emailAdres = document.getElementById('email').value.trim();
    const wachtwoord = document.getElementById('password').value.trim();

    // Controleer of alle verplichte velden ingevuld zijn
    if (!gebruikersnaam || !emailAdres || !wachtwoord) {
        alert('Alle velden zijn verplicht.');
        return false;
    }

    // BUG FIX #1001: Controleer op alleen-spaties input
    if (/^\s*$/.test(gebruikersnaam)) {
        alert('Gebruikersnaam kan niet alleen spaties bevatten.');
        return false;
    }

    // Controleer gebruikersnaam lengte (max 50 tekens volgens database)
    if (gebruikersnaam.length > 50) {
        alert('Gebruikersnaam is te lang (maximaal 50 tekens).');
        return false;
    }

    // Valideer e-mail formaat
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailAdres)) {
        alert('Ongeldig e-mail formaat.');
        return false;
    }

    // Controleer wachtwoord minimum lengte (8 tekens voor veiligheid)
    if (wachtwoord.length < 8) {
        alert('Wachtwoord moet minimaal 8 tekens zijn.');
        return false;
    }

    return true; // Alles geldig
}


// ==========================================================================
// SECTIE 3: SCHEMA FORMULIER VALIDATIE
// ==========================================================================

/**
 * validateScheduleForm - Valideert het schema formulier
 *
 * Valideert een gaming schema:
 * - Speltitel: verplicht, niet alleen spaties
 * - Datum: moet geldig zijn en in de toekomst liggen
 * - Tijd: moet geldig UU:MM formaat zijn
 * - Vrienden/Gedeeld met: indien ingevuld, geldig komma-gescheiden
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateScheduleForm() {
    // Haal formulier waarden op
    const spelTitel = document.getElementById('game_title').value.trim();
    const datum = document.getElementById('date').value;
    const tijd = document.getElementById('time').value;
    const vriendenStr = document.getElementById('friends_str').value.trim();
    const gedeeldMetStr = document.getElementById('shared_with_str').value.trim();

    // BUG FIX #1001: Controleer of speltitel niet leeg of alleen spaties is
    if (!spelTitel || /^\s*$/.test(spelTitel)) {
        alert('Speltitel is verplicht en kan niet alleen spaties bevatten.');
        return false;
    }

    // BUG FIX #1004: Valideer datum
    if (!datum) {
        alert('Datum is verplicht.');
        return false;
    }

    // Controleer of datum geldig is en niet in het verleden
    const gekozenDatum = new Date(datum);
    const vandaag = new Date();
    vandaag.setHours(0, 0, 0, 0); // Zet naar begin van de dag

    if (isNaN(gekozenDatum.getTime())) {
        alert('Ongeldig datum formaat.');
        return false;
    }

    if (gekozenDatum < vandaag) {
        alert('Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Valideer tijd formaat (UU:MM)
    // Regex: 00-23 voor uren, 00-59 voor minuten
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(tijd)) {
        alert('Ongeldig tijd formaat. Gebruik UU:MM.');
        return false;
    }

    // Valideer komma-gescheiden velden (alleen letters, cijfers, komma's, spaties)
    if (vriendenStr && !/^[a-zA-Z0-9,\s]*$/.test(vriendenStr)) {
        alert('Vrienden veld bevat ongeldige tekens.');
        return false;
    }

    if (gedeeldMetStr && !/^[a-zA-Z0-9,\s]*$/.test(gedeeldMetStr)) {
        alert('Gedeeld met veld bevat ongeldige tekens.');
        return false;
    }

    return true; // Alles geldig
}


// ==========================================================================
// SECTIE 4: EVENEMENT FORMULIER VALIDATIE
// ==========================================================================

/**
 * validateEventForm - Valideert het evenement formulier
 *
 * Valideert een gaming evenement (toernooi, stream, etc.):
 * - Titel: verplicht, max 100 tekens, niet alleen spaties
 * - Datum: geldig en in de toekomst
 * - Tijd: geldig UU:MM formaat
 * - Beschrijving: max 500 tekens
 * - Externe link: indien ingevuld, moet geldige URL zijn
 *
 * @returns {boolean} true als geldig, false als ongeldig
 */
function validateEventForm() {
    // Haal alle formulier waarden op
    const titel = document.getElementById('title').value.trim();
    const datum = document.getElementById('date').value;
    const tijd = document.getElementById('time').value;
    const beschrijving = document.getElementById('description').value;
    const externeLink = document.getElementById('external_link').value;
    const gedeeldMetStr = document.getElementById('shared_with_str').value.trim();

    // BUG FIX #1001: Valideer dat titel niet leeg of alleen spaties is
    if (!titel || /^\s*$/.test(titel)) {
        alert('Titel is verplicht en kan niet alleen spaties bevatten.');
        return false;
    }

    // Controleer titel maximum lengte (100 tekens volgens database)
    if (titel.length > 100) {
        alert('Titel is te lang (maximaal 100 tekens).');
        return false;
    }

    // BUG FIX #1004: Valideer datum
    if (!datum) {
        alert('Datum is verplicht.');
        return false;
    }

    const gekozenDatum = new Date(datum);
    const vandaag = new Date();
    vandaag.setHours(0, 0, 0, 0);

    if (isNaN(gekozenDatum.getTime())) {
        alert('Ongeldig datum formaat. Gebruik JJJJ-MM-DD.');
        return false;
    }

    if (gekozenDatum < vandaag) {
        alert('Datum moet vandaag of in de toekomst zijn.');
        return false;
    }

    // Valideer tijd formaat
    if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(tijd)) {
        alert('Ongeldig tijd formaat. Gebruik UU:MM.');
        return false;
    }

    // Controleer beschrijving lengte (max 500 tekens)
    if (beschrijving.length > 500) {
        alert('Beschrijving is te lang (maximaal 500 tekens).');
        return false;
    }

    // Valideer externe URL indien opgegeven
    if (externeLink && !/^(https?:\/\/)?[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(externeLink)) {
        alert('Ongeldig externe link formaat.');
        return false;
    }

    // Valideer gedeeld met veld
    if (gedeeldMetStr && !/^[a-zA-Z0-9,\s]*$/.test(gedeeldMetStr)) {
        alert('Gedeeld met veld bevat ongeldige tekens.');
        return false;
    }

    return true; // Alles geldig
}


// ==========================================================================
// SECTIE 5: PAGINA INITIALISATIE
// ==========================================================================

/**
 * DOMContentLoaded - Draait wanneer de pagina volledig geladen is.
 * Wordt gebruikt voor initialisatie taken.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Log dat de pagina geladen is (voor debuggen)
    console.log('GamePlan Scheduler succesvol geladen!');

    // Initialiseer interactieve functies
    initialiseerFuncties();
});


// ==========================================================================
// SECTIE 6: FUNCTIE INITIALISATIE
// ==========================================================================

/**
 * initialiseerFuncties - Stelt interactieve pagina functies in
 *
 * Bevat:
 * - Vloeiend scrollen naar interne links
 * - Bevestiging bij verwijder knoppen
 * - Automatisch sluiten van meldingen na 5 seconden
 */
function initialiseerFuncties() {
    // Voeg vloeiend scrollen toe aan alle interne links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const doel = document.querySelector(this.getAttribute('href'));
            if (doel) {
                doel.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Voeg bevestiging toe aan alle verwijder knoppen
    document.querySelectorAll('.btn-danger').forEach(btn => {
        if (!btn.hasAttribute('onclick')) {
            btn.addEventListener('click', function (e) {
                if (!confirm('Weet je zeker dat je dit wilt verwijderen?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // Automatisch meldingen sluiten na 5 seconden
    document.querySelectorAll('.alert-dismissible').forEach(melding => {
        setTimeout(() => {
            const sluitKnop = melding.querySelector('.btn-close');
            if (sluitKnop) sluitKnop.click();
        }, 5000);
    });
}


// ==========================================================================
// SECTIE 7: HULP FUNCTIES
// ==========================================================================

/**
 * toonMelding - Toont een tijdelijke melding op het scherm
 *
 * @param {string} bericht  Het bericht om te tonen
 * @param {string} type     Type: 'success', 'danger', 'warning', 'info'
 */
function toonMelding(bericht, type = 'info') {
    // Maak melding element aan
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


// ==========================================================================
// EINDE VAN BESTAND
// ==========================================================================
