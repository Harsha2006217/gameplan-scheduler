# 🛠️ ONDERHOUDS- & UITBREIDINGSGIDS
## GamePlan Scheduler - Hoe de app te verbeteren

Dit document is bedoeld voor ontwikkelaars die in de toekomst functies willen toevoegen aan de GamePlan Scheduler.

---

### 1. Een nieuwe pagina toevoegen
1.  Maak een nieuw `.php` bestand aan.
2.  Begin bovenaan met `include 'functions.php';`.
3.  Voeg `include 'header.php';` en `include 'footer.php';` toe voor de styling.
4.  Voeg de nieuwe pagina toe aan de `isActive()` functie in `functions.php` zodat de menubalk werkt.

---

### 2. De Database uitbreiden
Wil je bijvoorbeeld 'Game Reviews' toevoegen?
1.  Maak een nieuwe tabel `reviews` aan in de database.
2.  Voeg een `ForeignKey` toe naar `user_id` en `game_id`.
3.  Maak in `functions.php` een nieuwe functie `getReviews()` en `addReview()`.

---

### 3. Styling aanpassen
De hele site gebruikt CSS variabelen in `style.css`.
*   Wil je een **andere kleur**? Verander dan alleen de `--neon-blue` variabele bovenaan in het CSS bestand en de hele site verandert mee!

---

### 4. Beveiliging bijhouden
*   Zorg dat je altijd de nieuwste versie van PHP gebruikt.
*   Blijf `safeEcho()` gebruiken voor alle tekst die je op het scherm toont.
*   Houd `checkSessionTimeout()` actief om sessie-kaping te voorkomen.

---
**SUGGESTIE VOOR V2.0**: Een chat-functie toevoegen tussen vrienden om direct afspraken te maken!
