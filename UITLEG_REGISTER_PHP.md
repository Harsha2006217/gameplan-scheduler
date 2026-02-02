# UITLEG register.php (Regel voor Regel)
## GamePlan Scheduler - Nieuwe Gebruikers (Registratie)

**Bestand**: `register.php`
**Doel**: Nieuwe gebruikers een account laten aanmaken.

---

### Regel 24: Functies Laden
```php
require_once 'functions.php';
```
**Uitleg**: We laden `functions.php`. Dit is nodig omdat de functie `registerUser()` daar in staat. Zonder dit bestand weet PHP niet hoe hij moet registreren.

### Regel 27-30: Ben ik al ingelogd?
```php
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}
```
**Uitleg**:
*   `isLoggedIn()`: Controleert of er een sessie bestaat (`functions.php`).
*   **Logica**: Als ik al ingelogd ben, mag ik geen nieuw account aanmaken.
*   **Actie**: Stuur direct door naar het dashboard (`index.php`).

### Regel 35: Formulier Verzonden? (POST)
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
```
**Uitleg**:
*   De computer kijkt: "Is er op de knop gedrukt?".
*   `GET`: Pagina gewoon bekijken.
*   `POST`: Pagina verwerkt gegevens.

### Regel 36-38: Data Ophalen
```php
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
```
**Uitleg**:
*   `$_POST`: De "envelop" waar de formulier-data in zit.
*   `?? ''`: De "Null Coalescing Operator". In Jip-en-Janneke taal: *"Als het vakje leeg is, doe dan alsof er een lege tekst staat, maar geef geen foutmelding."*

### Regel 41: De Registratie Functie
```php
    $error = registerUser($username, $email, $password);
```
**Uitleg**:
*   Hier gebeurt het echte werk (in `functions.php`).
*   **Wat gebeurt daar?**:
    1.  Check of e-mail al bestaat (voorkom dubbele accounts).
    2.  Check wachtwoord lengte (> 8 tekens).
    3.  Hash het wachtwoord (bcrypt).
    4.  Sla op in database.
*   **Resultaat**: Als het mislukt, komt er tekst in `$error`. Als het lukt, is `$error` leeg.

### Regel 43-48: Succes Afhandeling
```php
    if (!$error) {
        setMessage('success', 'Registration successful! ...');
        header("Location: login.php");
        exit;
    }
```
**Uitleg**:
*   `setMessage`: We stoppen een groen berichtje in de sessie ("Succes!").
*   `header`: We sturen de gebruiker naar `login.php`.
*   **UX**: De gebruiker komt op de login pagina en ziet bovenaan: "Registratie succesvol! Log nu in."

### Regel 74: HTML Formulier & Browser Validatie
```html
<form method="POST" onsubmit="return validateRegisterForm();">
```
**Uitleg**:
*   `onsubmit`: Dit activeert JavaScript `script.js`.
*   **Bugfix #1001**: Hier wordt gecontroleerd of de gebruiker alleen spaties ("   ") als naam heeft ingevuld. Zo ja, dan wordt het formulier NIET verzonden.

### Regel 82-87: Inputs (Gebruikersnaam)
```html
<input type="text" ... maxlength="50" required ...>
```
**Uitleg**:
*   `maxlength="50"`: Voorkomt dat iemand een heel boek als naam invult (Database limiet is 50).
*   `required`: Verplicht veld.

### Regel 116-121: Inputs (Wachtwoord)
```html
<input type="password" ... minlength="8" required ...>
```
**Uitleg**:
*   `minlength="8"`: De browser blokkeert wachtwoorden korter dan 8 tekens.
*   **Double Layer Security**: PHP (`functions.php`) checkt dit OOK. Als een hacker de HTML aanpast, houdt PHP hem alsnog tegen.

---
**Samenvatting**: Dit script is de "Deurwachter" voor nieuwe leden. Het controleert of ze nog geen lid zijn, of hun gegevens geldig zijn, en stuurt ze dan door naar de "Receptie" (Login).
