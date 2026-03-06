# GamePlan Scheduler – Projectdocumentatie & Examenuitleg

**Auteur:** Harsha Kanaparthi  
**Studentnummer:** 2195344  
**Opleiding:** MBO-4 Software Development  
**Stagebedrijf:** Kompas Publishing B.V.  
**Begeleider:** Marius Restua  
**Datum:** 30-09-2025  
**Versie:** 1.0 (Examendocumentatie)

---

# 📋 1. Wat is dit project?

**GamePlan Scheduler** is een website waarmee jonge gamers hun spelactiviteiten kunnen plannen.

**Het probleem:**  
Gamers vinden het lastig om samen afspraken te maken.

**De oplossing:**  
Een centrale plek om vrienden toe te voegen, schema's te delen en herinneringen in te stellen.

**Gemaakt door:**  
Ik (Harsha), in 1 maand tijd (49 uur werken).

**Techniek:**  
PHP (logica), MySQL (database), HTML/CSS (ontwerp), JavaScript (interactie).

---

# 🗓️ 2. Werkproces K1: Het Project (Planning t/m Verbeteren)

Hier leg ik uit hoe ik het project heb gebouwd, stap voor stap.

---

## K1-W1: Planning (Wat ga ik doen?)

Ik heb niet zomaar begonnen, ik heb een plan gemaakt.

**Doel (SMART):**  
Een werkende app voor gamers, klaar op 30 september 2025.

**Taken:**  
12 taken verdeeld over 4 weken (bijv. Week 1: Database, Week 4: Testen).

**Prioriteit (MOSCOW):**

- **Must have:** Inloggen, database (dit móét werken).
- **Should have:** Vriendenlijst (maakt het beter).
- **Could have:** Herinneringen (extraatje).

**Resultaat:**  
Alles op tijd af, 49 uur gewerkt (meer dan de 40 uur eis).

---

## K1-W2: Ontwerp (Hoe ziet het eruit?)

Voordat ik codeerde, heb ik getekend hoe het werkt.

**Database:**  
6 tabellen (Users, Games, Friends, etc.) met relaties.

**Veiligheid:**  
Wachtwoorden versleuteld, geen data lekken.

**Ontwerp:**  
Donker thema (voor gamers), grote knoppen (voor mobiel).

**Schermen:**

- Inlog
- Dashboard
- Profiel
- Vrienden
- Schema's
- Evenementen

---

## K1-W3: Realisatie (Het coderen)

Hier heb ik de app echt gebouwd.

**Code:**

- PHP voor de backend
- HTML/CSS voor de frontend

**Structuur:**  
Alles netjes gescheiden (database apart, logica apart, ontwerp apart).

**Versiebeheer:**  
Ik heb Git gebruikt. Elke dag een update op GitHub zodat je mijn voortgang ziet.

**Uren:**  
49 uur geregistreerd in een logboek.

---

## K1-W4: Testen (Werkt het?)

Ik heb alles getest voordat ik het inleverde.

**Aantal tests:**  
30 tests uitgevoerd (5 per functie).

**Resultaat:**  
28 direct goed (93%). Na fixes 100% goed.

**Bugs gevonden:**

1. **Spaties bug**  
   Systeem accepteerde alleen spaties als naam.  
   **Oplossing:** Check toegevoegd.

2. **Datum bug**  
   Ongeldige datum (2025-13-45) werd geaccepteerd.  
   **Oplossing:** Strengere check toegevoegd.

**Apparaten:**

- Laptop
- Mobiel (Samsung S21)

---

## K1-W5: Verbeteren (Wat kan beter?)

Ik heb nagedacht over versie 2.0.

**6 Voorstellen:**

- Bijv. e-mail notificaties
- betere navigatie
- meer tests

**Bronnen:**

- Mijn tests
- Feedback van gebruikers
- Mijn eigen reflectie

**Belangrijkste:**  
Validatie verbeteren en notificaties toevoegen.

---

# 🤝 3. Werkproces K2: Samenwerken (Stage & Soft Skills)

Hier leg ik uit hoe ik heb samengewerkt tijdens mijn stage.

---

## K2-W1: Overleggen

**Bedrijf:** Kompas Publishing B.V.

**Begeleider:** Marius Restua

**Momenten:**  
3 keer overleg gehad:

- planning
- feedback
- oplevering

**Afspraken:**  
Alles vastgelegd in verslagen. Ik kwam afspraken na (bijv. deadline gehaald).

---

## K2-W2: Presenteren

Ik heb 2 presentaties gegeven om te laten zien wat ik kan.

### 1. Aan Marius (Stagebegeleider)

Onderwerp: mijn portfolio website.  
Feedback:  
_"Laat meer eindresultaat zien."_

### 2. Aan Billy (Medestudent)

Onderwerp: de MBO-opleiding.  
Feedback:  
_"Laat een live demo zien."_

**Leerpoint:**  
Volgende keer eerst afmaken, dan presenteren én live demo tonen.

---

## K2-W3: Reflectie

Ik kijk terug op wat goed ging en wat beter kan.

**Methode:**  
STARR (Situation, Task, Action, Result, Reflectie)

**Voorbeeld:**  
Ik liep vast op een API koppeling. Eerst 2 dagen zelf geprobeerd, toen hulp gevraagd.

**Leerpoint:**  
Vaker en eerder hulp vragen als ik vastloop (binnen 1 dag).

**Cijfer Stage:**  
4.4 (met bonus opleidingsjaar 2).

---

# 💻 4. Code Uitleg (Simpele taal voor niet-programmeurs)

Hier leg ik uit wat de bestanden doen, alsof het een gebouw is.

---

## db.php (De Sleutel)

**Wat doet het?**  
Dit bestand maakt de verbinding met de database (waar alle data staat).

**Simpel gezegd:**  
Dit is de sleutel op de deur van het archief. Zonder dit bestand kan de website geen gegevens opslaan of ophalen.

**Belangrijk:**  
Het zorgt ervoor dat we maar één verbinding openen (zuinig op geheugen).

---

## functions.php (De Hersenen)

**Wat doet het?**  
Hier staat alle logica.

Bijvoorbeeld:

- Is het wachtwoord goed?
- Mag deze gebruiker dit zien?

**Simpel gezegd:**  
Dit is de beveiligingsagent en de rekenmachine. Het controleert alles voordat er iets gebeurt.

**Belangrijk:**  
Hier zit de beveiliging tegen hackers (SQL-injectie en XSS).

---

## index.php (De Voorpagina)

**Wat doet het?**  
Dit is het dashboard dat je ziet na het inloggen.

**Simpel gezegd:**  
Dit is de huiskamer. Hier zie je je vrienden, je schema's en je evenementen bij elkaar.

**Belangrijk:**  
Het haalt informatie op uit de database en toont het mooi op het scherm.

---

## login.php (De Entree)

**Wat doet het?**  
Het inlogscherm.

**Simpel gezegd:**  
De portier aan de deur. Hij checkt je naam en wachtwoord. Als het goed is, mag je naar binnen (`index.php`).

**Belangrijk:**  
Hij versleutelt het wachtwoord zodat niemand het kan lezen.

---

## style.css (De Verf & Inrichting)

**Wat doet het?**  
Bepaalt hoe alles eruit ziet (kleuren, lettertypes, knoppen).

**Simpel gezegd:**  
Dit is de interieurontwerper. Het zorgt dat het donker thema is en knoppen groot genoeg zijn voor mobiel.

**Belangrijk:**  
Zorgt dat het er professioneel uitziet voor gamers.

---

## script.js (De Interactie)

**Wat doet het?**  
Zorgt voor actie zonder de pagina te verversen (bijv. pop-ups, validatie).

**Simpel gezegd:**  
Dit is de assistent die direct reageert. Als je een fout invulveld hebt, zegt hij meteen:

_"Hé, dit klopt niet!"_

zonder dat de pagina laadt.

**Belangrijk:**  
Maakt de website snel en gebruiksvriendelijk.

---

# 🔒 5. Veiligheid (Waarom is het veilig?)

Ik heb 10 maatregelen genomen om de data te beschermen.

1. Wachtwoorden versleuteld (bcrypt)
2. SQL-injectie voorkomen met prepared statements
3. XSS voorkomen door invoer veilig te maken
4. Sessies verlopen na 30 minuten inactiviteit
5. Alleen eigen gegevens bewerken
6. Input validatie
7. Geen technische foutmeldingen voor gebruikers
8. Soft delete voor herstel
9. HTTPS verbinding
10. Inlogcontrole op elke pagina

---

# 📊 6. Database (Waar staat de data?)

Ik gebruik **6 tabellen** om alles netjes op te slaan.

1. **Users**  
   Naam, e-mail, wachtwoord

2. **Games**  
   Welke spellen bestaan er

3. **UserGames**  
   Koppeling tussen gebruikers en games

4. **Friends**  
   Wie is vrienden met wie

5. **Schedules**  
   Datum en tijd van speelsessies

6. **Events**  
   Bijzondere evenementen zoals toernooien

---

# 🚀 7. Installatie (Hoe start je het?)

1. Installeer **XAMPP**
2. Start **Apache** en **MySQL**
3. Zet bestanden in `htdocs`
4. Maak database via `database.sql`
5. Ga naar `localhost/gameplan-scheduler`
6. Maak account aan en log in

---

# Einde documentatie
