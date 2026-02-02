# 🍦 MAKKELIJKE UITLEG GIDS
## GamePlan Scheduler - Hoe werkt het eigenlijk?

Soms is technische taal lastig. Deze gids legt in gewone-mensen-taal uit wat er "onder de motorkap" gebeurt van je website.

---

### 1. Inloggen (De Deurwachter)
*   **Wat je ziet**: Een scherm waar je je email en wachtwoord invult.
*   **Wat er gebeurt**: PHP werkt als een deurwachter. Hij kijkt in een geheime lijst (de database). Als je wachtwoord klopt, krijg je een "toegangskaartje" (een Sessie). Zolang je dat kaartje hebt, mag je binnen blijven.

### 2. Validatie (De Checkpoint)
*   **Wat je ziet**: Een rode melding als je iets vergeet in te vullen.
*   **Wat er gebeurt**: De website heeft twee lijnen van verdediging. 
    1.  Eerst checkt de browser (JavaScript) of je niks vergeten bent. Dit is supersnel.
    2.  Daarna checkt de server (PHP) het NOG een keer. Dit is voor de veiligheid. Zo kunnen hackers niet zomaar valse data naar binnen glippen.

### 3. De Database (De Grote Kluis)
*   **Wat je ziet**: Een lijstje met je vrienden of games.
*   **Wat er gebeurt**: Alles wat je typt wordt opgeslagen in een tabel, net als in Excel. Maar deze kluis is slim; hij weet precies welke vriend bij welke gebruiker hoort. Dat noemen we "Relaties".

### 4. Beveiliging (Het Schild)
*   **Wat je ziet**: Niets, en dat is goed!
*   **Wat er gebeurt**: 
    1.  **Wachtwoorden**: We slaan je wachtwoord niet echt op. We maken er een soort puzzel van (een Hash). Zelfs als iemand de kluis steelt, kunnen ze je wachtwoord niet lezen.
    2.  **Hacks voorkomen**: We maken alle tekst "schoon" voordat we het op een scherm laten zien. Zo kunnen gemene codes van hackers de site niet kapot maken.

### 5. Het Dashboard (Het Controlecentrum)
*   **Wat je ziet**: Een mooi overzicht.
*   **Wat er gebeurt**: Zodra je inlogt, rent PHP naar de database, pakt al jouw lijstjes op, sorteert ze netjes op datum, en plakt ze in een mooi ontwerp zodat jij het makkelijk kunt lezen.

---
**VRAGEN?**
Kijk in de `BEGRIPPENLIJST_NL.md` voor de betekenis van lastige woorden!
