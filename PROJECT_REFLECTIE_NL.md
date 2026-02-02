# 🧠 TECHNISCHE PROJECT REFLECTIE
## GamePlan Scheduler - Analyse van Proces & Architectuur

---

> **Auteur**: Harsha Kanaparthi | **Examen**: MBO-4 Software Developer
> 
> "Dit document reflecteert niet alleen op de bouw van de app, maar ook op de technische keuzes en professionele groei tijdens het project."

---

# 1. Technische Successen & Doorbraken

### 🏗️ Architectuur & Structuur
De keuze voor een **gecentraliseerde logica-laag** in `functions.php` was een kritieke succesfactor. In plaats van database-queries over de hele app te verspreiden, heb ik alles gebundeld. Dit bevordert de onderhoudbaarheid en maakt het debuggen 10x makkelijker. 
- **Geleerd**: Het principe van *"Separation of Concerns"* (Scheiding van Belangen).

### 🎨 Design & Gebruikerservaring
Het implementeren van **Glassmorphism** was een uitdaging voor de leesbaarheid. Door gebruik te maken van `backdrop-filter: blur()` en zorgvuldig gekozen contrastratio's, heb ik een balans gevonden tussen esthetiek en toegankelijkheid (Accessibility).

### 🔒 Beveiliging als Standaard
Ik ben trots op de implementatie van **Defense in Depth**. In plaats van alleen maar 'gegevens op te slaan', heb ik nagedacht over angsten van gebruikers (zoals gestolen wachtwoorden) en deze geadresseerd met Bcrypt en PDO.

# 2. Uitdagingen & Problem Solving

### 🐛 De "Spook-Data" Uitdaging (Bug #1001)
Een grote uitdaging was gebruikers die invoervelden probeerden te omzeilen door alleen spaties in te vullen. 
- **Oplossing**: Ik heb een robuuste validatie-functie gebouwd die gebruik maakt van `trim()` en reguliere expressies (`/^\s*$/`). Dit heeft mijn inzicht in data-integriteit enorm vergroot.

### 📅 De Datum-Integriteit Puzzel (Bug #1004)
Het valideren van datums bleek complexer dan gedacht (rekening houdend met schrikkeljaren en tijdzones).
- **Oplossing**: De overstap van simpele string-checks naar de PHP `DateTime` klasse was een openbaring. Dit garandeert dat alleen 'echte' datums de kluis bereiken.

# 3. Professionele Reflectie

### Wat zou ik anders doen?
1.  **Object-Georiënteerd Programmeren (OOP)**: Hoewel de functionele opbouw erg stabiel is, zou ik in een volgend project overstappen op "Klassen" en "Objecten" om de code nog herbruikbaarder te maken.
2.  **API-Driven Design**: Ik zou de backend bouwen als een losse API (JSON), zodat de website later makkelijk uitgebreid kan worden met een mobiele app (bijv. in React Native).

### Conclusie
Dit project heeft mij bewezen dat ik niet alleen syntax begrijp, maar ook de **logica** en **verantwoordelijkheid** achter de code. De GamePlan Scheduler staat als een huis: veilig, snel en professioneel gedocumenteerd. 🏆
