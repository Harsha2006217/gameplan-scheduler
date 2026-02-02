# 🎬 DEMO SCRIPT - EXAMEN PRESENTATIE
## GamePlan Scheduler - Stap-voor-stap demonstratie

Dit script helpt je om je applicatie op een professionele manier te laten zien aan de examencommissie. Volg deze stappen voor een perfecte demo.

---

### Stap 1: De Start (Login & Register)
*   **Actie**: Laat de homepagina zien (je bent nog niet ingelogd).
*   **Vertel**: "Dit is de GamePlan Scheduler. Zoals u ziet, wordt ik direct naar de login-pagina gestuurd omdat de app beveiligd is."
*   **Actie**: Ga naar **Registreren**. Vul een naam in met alleen spaties.
*   **Vertel**: "Hier ziet u een van mijn bugfixes (#1001). De app weigert namen die alleen uit spaties bestaan. Dit verbetert de datakwaliteit."
*   **Actie**: Maak een écht account aan en log in.

---

### Stap 2: Het Dashboard (Overzicht)
*   **Actie**: Laat het dashboard zien met de tabel 'Vrienden' en 'Mijn Planning'.
*   **Vertel**: "Het dashboard is het hart van de app. Ik gebruik Glassmorphism voor een moderne gaming-look. De navigatie is tweetalig (EN/NL) voor een professionele uitstraling."
*   **Actie**: Klik op de sorteer-knop bij de planning.
*   **Vertel**: "Ik heb een sorteer-functie toegevoegd zodat gamers hun afspraken op datum kunnen ordenen."

---

### Stap 3: Data toevoegen (Validatie)
*   **Actie**: Voeg een nieuwe planning toe met een datum in het verleden.
*   **Vertel**: "Hier ziet u bugfix #1004. De PHP-code controleert strikt of de datum in de toekomst ligt. Zo voorkom ik onmogelijke afspraken."
*   **Actie**: Voeg een geldige planning toe.

---

### Stap 4: Beveiliging (Ownership)
*   **Actie**: Probeer via de URL een ID te bewerken dat niet van jou is (bijv. verander `id=5` naar `id=99`).
*   **Vertel**: "Beveiliging is cruciaal. Mijn code checkt bij elk verzoek of de data wel echt bij de ingelogde gebruiker hoort (`checkOwnership`). Je kunt dus nooit data van een ander verwijderen."

---

### Stap 5: De Afronding (Logout)
*   **Actie**: Klik op 'Logout'.
*   **Vertel**: "Bij het uitloggen wordt de sessie volledig vernietigd op de server. De gebruiker wordt veilig teruggestuurd naar de login."

---
**TIP**: Wees enthousiast en laat zien dat je trots bent op je code!
