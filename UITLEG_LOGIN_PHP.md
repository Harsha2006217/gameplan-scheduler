# UITLEG login.php (Regel voor Regel)
## GamePlan Scheduler - Inloggen

**Bestand**: `login.php`
**Doel**: Gebruikers veilig toegang geven tot hun account.

---

### Regel 26: Functies Laden
```php
require_once 'functions.php';
```
**Uitleg**: We laden onze trukendoos (`functions.php`). Hierdoor kunnen we functies als `isLoggedIn()` en `loginUser()` gebruiken.

### Regel 35-38: "Al Ingelogd?" Check
```php
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}
```
**Uitleg**: Slimme check!
*   Als ik al ingelogd ben, heeft het geen zin om dit formulier te zien.
*   `header("Location: ...")`: Stuur de browser direct door naar het dashboard (`index.php`).
*   `exit`: **Cruciaal**. Stop met het laden van de rest van de pagina. Veiligheid!

### Regel 51: Formulier Afhandeling (POST)
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
```
**Uitleg**:
*   Deze code wordt *alleen* uitgevoerd als de gebruiker op de knop "Login" heeft geklikt.
*   Bij het laden van de pagina is de methode `GET`.
*   Bij het verzenden is de methode `POST`.

### Regel 56-57: Inputs Ophalen
```php
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
```
**Uitleg**:
*   `$_POST['email']`: Haalt op wat de gebruiker in het vakje 'email' typte.
*   `?? ''`: Als het vakje leeg was (of niet bestaat), gebruiken we een lege tekst. Dit voorkomt foutmeldingen.

### Regel 61: De Login Poging
```php
    $error = loginUser($email, $password);
```
**Uitleg**:
*   We roepen de functie `loginUser` aan (die staat in `functions.php`).
*   Die functie doet het zware werk: Database checken, wachtwoord hash controleren.
*   Als het lukt, is `$error` leeg (`null`).
*   Als het mislukt, zit er een foutmelding in (bijv. "Ongeldig wachtwoord").

### Regel 65-68: Succes?
```php
    if (!$error) {
        header("Location: index.php");
        exit;
    }
```
**Uitleg**: Geen fout? Mooi! Stuur de gebruiker door naar het dashboard.

### Regel 111: HTML & CSS (Glassmorphism)
```html
<div class="auth-container">
```
**Uitleg**:
*   Hier begint de HTML.
*   `auth-container`: Een speciale CSS klasse (uit `style.css`) die zorgt voor dat mooie, doorzichtige "glas effect" op de donkere achtergrond.

### Regel 127-131: Foutmelding Tonen
```php
<?php if ($error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo safeEcho($error); ?>
    </div>
<?php endif; ?>
```
**Uitleg**:
*   Alleen als er een foutmelding is (`if ($error)`)...
*   Tonen we een rood blokje (`alert-danger`).
*   `safeEcho($error)`: We zorgen dat de foutmelding veilige tekst is (geen HTML code van hackers).

### Regel 140: Client-Side Validatie
```html
<form method="POST" onsubmit="return validateLoginForm();">
```
**Uitleg**:
*   `onsubmit`: Voordat we data naar de server sturen, roepen we JavaScript aan (`script.js`).
*   `validateLoginForm()`: Controleert in de browser of velden leeg zijn.
*   Dit is de "Eerste Verdedigingslinie". Het is sneller voor de gebruiker (geen pagina herladen).

### Regel 153-159: Email Veld
```html
<input type="email" required ...>
```
**Uitleg**:
*   `type="email"`: De browser (Chrome/Edge) controleert uit zichzelf of er een `@` in staat.
*   `required`: Je mag het niet leeg laten.

### Regel 172: Wachtwoord Veld
```html
<input type="password" required ...>
```
**Uitleg**: `type="password"` zorgt dat de tekens veranderen in bolletjes (••••), zodat niemand kan meekijken.

---
**Samenvatting**: Login.php is de "Uitsmijter" van de club. Hij controleert ID (email) en toegangscode (wachtwoord) voordat je naar binnen mag (dashboard).
