# 📧 UITLEG CONTACT.PHP (ELITE MASTER EDITIE)
## GamePlan Scheduler - Contactformulier

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "De brug tussen gebruiker en beheerder: een professioneel contactformulier."

---

# 📑 Inhoudsopgave

1.  **Functionele Beschrijving**
2.  **Code Analyse (Regel voor Regel)**
3.  **Validatie & XSS Preventie**
4.  **Email Functionaliteit (Optioneel)**
5.  **GIGANTISCH CONTACT WOORDENBOEK (50+ TERMEN)**
6.  **EXAMEN TRAINING: 20 Formulier & Validatie Vragen**
7.  **Conclusie**

---

# 1. Functionele Beschrijving 📬

De `contact.php` pagina biedt bezoekers de mogelijkheid om een bericht te sturen naar de beheerder. Het is een statische pagina met een simpel formulier dat:
- Naam, email en bericht opvangt.
- Valideert dat alle velden gevuld zijn.
- Eventueel een email kan versturen (afhankelijk van server-configuratie).

---

# 2. Code Analyse

```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    if ($name && $email && $message) {
        // Optioneel: mail($to, $subject, $body) of opslaan in database
        $success = "Bedankt voor je bericht!";
    } else {
        $error = "Alle velden zijn verplicht.";
    }
}
?>
```

- `htmlspecialchars()`: **XSS Preventie**. Zet speciale HTML-tekens om naar veilige entiteiten.
- `filter_var(..., FILTER_VALIDATE_EMAIL)`: Controleert of het email-adres geldig is.
- `trim()`: Verwijdert spaties om lege invoer te voorkomen.

---

# 5. GIGANTISCH CONTACT WOORDENBOEK (50 TERMEN)

1. **Contact Form**: Een formulier waarmee bezoekers berichten kunnen sturen.
2. **XSS (Cross-Site Scripting)**: Een aanval waarbij kwaadaardige scripts worden geïnjecteerd.
3. **htmlspecialchars()**: PHP-functie om XSS te voorkomen.
4. **filter_var()**: PHP-functie voor validatie en sanitisatie.
5. **FILTER_VALIDATE_EMAIL**: Een filter om email-adressen te valideren.
6. **Trim Function**: Verwijdert witruimte aan begin en einde.
7. **POST Method**: HTTP-methode voor het verzenden van formulierdata.
8. **mail() Function**: PHP-functie om emails te versturen (server-afhankelijk).
9. **Success Message**: Feedback aan de gebruiker na een succesvolle actie.
10. **Error Message**: Feedback aan de gebruiker bij validatiefouten.
11. **Form Validation**: Controleren van formulierinvoer.
12. **Input Sanitization**: Invoer schoonmaken van gevaarlijke tekens.
13. **Email Validation**: Controleren of email-formaat geldig is.
14. **Name Field**: Veld voor de naam van de afzender.
15. **Email Field**: Veld voor het email-adres.
16. **Message Field**: Veld voor het bericht.
17. **Subject Field**: Veld voor het onderwerp.
18. **Textarea**: Multi-line tekstinvoer.
19. **Required Attribute**: HTML5 verplicht-veld indicator.
20. **Placeholder Text**: Voorbeeldtekst in een veld.
21. **Form Action**: URL waar formulier naartoe stuurt.
22. **Form Method**: GET of POST.
23. **Submit Button**: Knop om formulier te verzenden.
24. **CAPTCHA**: Robot-detectie.
25. **Honeypot Field**: Verborgen veld voor spam-detectie.
26. **Rate Limiting**: Beperken van aantal submissions.
27. **Spam Prevention**: Voorkomen van ongewenste berichten.
28. **Email Headers**: Metadata van een email.
29. **Reply-To Header**: Antwoordadres in email.
30. **From Header**: Afzenderadres in email.
31. **Plain Text Email**: Email zonder opmaak.
32. **HTML Email**: Email met opmaak.
33. **Email Template**: Sjabloon voor emails.
34. **Autoresponder**: Automatisch antwoord.
35. **Confirmation Email**: Bevestiging van ontvangst.
36. **Contact Database**: Opslag van berichten in database.
37. **Support Ticket**: Ondersteuningsverzoek.
38. **FAQ Link**: Link naar veelgestelde vragen.
39. **Response Time**: Verwachte antwoordtijd.
40. **Privacy Notice**: Mededeling over privacy.
41. **Consent Checkbox**: Toestemmingsvinkje.
42. **Terms Agreement**: Akkoord met voorwaarden.
43. **File Upload**: Bijlage uploaden.
44. **Character Limit**: Maximum aantal tekens.
45. **Word Count**: Aantal woorden in bericht.
46. **Contact Info**: Alternatieve contactgegevens.
47. **Business Hours**: Openingstijden.
48. **Location Map**: Kaart met locatie.
49. **Social Links**: Links naar sociale media.
50. **HART Contact Form**: Veilig contactformulier volgens HART.

---

# 6. EXAMEN TRAINING: 20 Formulier & Validatie Vragen

1. **Vraag**: Waarom is `htmlspecialchars()` essentieel?
   **Antwoord**: Het voorkomt XSS-aanvallen door HTML-tags om te zetten naar entiteiten.

2. **Vraag**: Wat is XSS?
   **Antwoord**: Cross-Site Scripting - een aanval waarbij kwaadaardige scripts worden geïnjecteerd.

3. **Vraag**: Hoe werkt `filter_var()` met FILTER_VALIDATE_EMAIL?
   **Antwoord**: Het controleert of de string een geldig email-formaat heeft.

4. **Vraag**: Waarom valideren we email-adressen?
   **Antwoord**: Om te zorgen dat we een geldig adres hebben voor antwoord.

5. **Vraag**: Wat is Input Sanitization?
   **Antwoord**: Het schoonmaken van invoer om gevaarlijke tekens te verwijderen.

6. **Vraag**: Wat is een Honeypot Field?
   **Antwoord**: Een verborgen veld dat bots invullen, maar mensen niet zien.

7. **Vraag**: Wat is Rate Limiting?
   **Antwoord**: Het beperken van het aantal formulier-submissions per tijdseenheid.

8. **Vraag**: Wat is CAPTCHA?
   **Antwoord**: Een test om te bepalen of de gebruiker een mens is.

9. **Vraag**: Waarom zou je berichten opslaan in een database?
   **Antwoord**: Voor archivering, tracking en als email faalt.

10. **Vraag**: Wat is een Autoresponder?
    **Antwoord**: Een automatisch email die bevestigt dat het bericht is ontvangen.

11. **Vraag**: Waarom is de Consent Checkbox vaak nodig?
    **Antwoord**: Voor AVG/GDPR-compliance moet de gebruiker toestemming geven.

12. **Vraag**: Wat is het verschil tussen Plain Text en HTML Email?
    **Antwoord**: Plain text heeft geen opmaak; HTML heeft styling en media.

13. **Vraag**: Wat is een Reply-To Header?
    **Antwoord**: Het email-adres waar antwoorden naartoe gaan.

14. **Vraag**: Wat is een Character Limit op berichten?
    **Antwoord**: Een maximum aantal tekens om extreem lange berichten te voorkomen.

15. **Vraag**: Waarom tonen we Response Time?
    **Antwoord**: Om verwachtingen te managen over wanneer de gebruiker antwoord krijgt.

16. **Vraag**: Wat is een Privacy Notice op een formulier?
    **Antwoord**: Informatie over hoe de ingevulde data wordt verwerkt.

17. **Vraag**: Hoe zou je File Upload toevoegen?
    **Antwoord**: Met enctype="multipart/form-data" en $_FILES verwerking.

18. **Vraag**: Wat is een Support Ticket?
    **Antwoord**: Een geregistreerd ondersteuningsverzoek met tracking-nummer.

19. **Vraag**: Waarom zou je FAQ linken op de contactpagina?
    **Antwoord**: Om veelgestelde vragen te beantwoorden zonder contact.

20. **Vraag**: Wat is HART Contact Form?
    **Antwoord**: Een veilig contactformulier met sanitisatie en validatie.

---

# 7. Conclusie

De `contact.php` demonstreert veilige invoerverwerking met `htmlspecialchars()` en `filter_var()`.

---
**GEAUTORISEERD VOOR MBO-4 EXAMENPORTFOLIO**
*Harsha Kanaparthi - Elite Master Software Developer - 2026*
