# 📊 CODE FLOW DIAGRAMMEN (Visueel)
## GamePlan Scheduler - Hoe de data door de site stroomt

In dit document zie je hoe een actie (zoals inloggen) van stap naar stap gaat.

---

### 1. Inlog Flow (Diagram)

```mermaid
graph TD
    A["GEBRUIKER: Vult Login Formulier in"] --> B["BROWSER (JS): Check op lege velden"]
    B -- "Fout" --> C["FOUTMELDING in Browser"]
    B -- "OK" --> D["SERVER (PHP): login.php ontvangt data"]
    D --> E["BREIN: loginUser() in functions.php"]
    E --> F["DB: Vraag gegevens op"]
    F --> G{"Wachtwoord Hash OK?"}
    G -- "NEE" --> H["FOUT: Terug naar login.php met melding"]
    G -- "JA" --> I["SUCCES: Maak Sessie aan"]
    I --> J["REDIRECHT: Dashboard (index.php)"]
```

---

### 2. Dashboard Pagina Laden (Data Verzamelen)

Wanneer je het dashboard opent, gebeurt er heel veel tegelijkertijd:

```mermaid
sequenceDiagram
    participant U as Gebruiker
    participant S as Server (index.php)
    participant B as Brein (functions.php)
    participant D as Database

    U->>S: Opent Dashboard
    S->>B: Wie ben ik? (getUserId)
    B->>S: ID: 5
    S->>D: Geef Vrienden van ID 5
    D->>S: Dave, Sarah, etc.
    S->>D: Geef Planning van ID 5
    D->>S: Vrijdag 20:00 Fortnite
    S->>D: Geef Favorieten
    D->>S: Minecraft, FIFA
    S->>U: Toon Dashboard met alle Data
```

---

### 3. De 30-Minuten Check (Session Timeout)

```mermaid
graph LR
    A["KLIK op Pagina"] --> B["Pak 'Last Activity' tijd"]
    B --> C{"Is verschil > 30 min?"}
    C -- "JA" --> D["Sessie Vernietigen"]
    D --> E["Stuur naar Login"]
    C -- "NEE" --> F["Ververs 'Last Activity'"]
    F --> G["Toon de Pagina"]
```

---
**EINDE DIAGRAMMEN OVERZICHT**
