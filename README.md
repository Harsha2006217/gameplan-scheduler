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

#

# ---

#

# 📝 Uitgebreide K1/K2 Werkproces Details

#

# ## K1 Planning (W1)

# - Ik maakte een planning voor de GamePlan Scheduler app voor gamers.

# - De app laat gamers profielen maken, vrienden toevoegen, schema’s delen en evenementen plannen in een kalender.

# - Ik gebruikte PHP, MySQL, HTML, CSS en JavaScript.

# - Het doel is SMART: specifiek, meetbaar, acceptabel, realistisch en klaar op 30 september 2025.

# - Er zijn zes user stories: profiel maken, vrienden toevoegen, schema’s delen, evenementen toevoegen, herinneringen instellen en bewerken/verwijderen.

# - Aanvullende eisen: responsive design, veilige database, simpele knoppen en basis inloggen met sessies.

# - Takenlijst met MOSCOW: must-have taken zoals database en inloggen, should-have zoals vrienden.

# - Totaal 49 uur werk van 2 september tot 30 september 2025.

# - Ik hield voortgang per week en overlegde drie keer met begeleider Marius Restua.

# - Betrokkenen zijn ikzelf, Marius en jonge gamers als testers.

#

# ## K1 Ontwerp (W2)

# - Ik ontwierp de GamePlan Scheduler met donkere kleuren zwart en blauw voor gamers.

# - Header heeft logo, menu en profiel icoon, midden heeft inhoud en footer copyright.

# - Navigatie is simpel: home, profiel, vrienden, schema’s, evenementen en uitloggen.

# - Hoofdmenu knoppen zijn blauw met hover-effect en werken op mobiel.

# - Voor user story 1: profielformulier met games en bevestigingsscherm.

# - Voor user story 2: vrienden zoeken en lijst tonen.

# - Voor user story 3: schema toevoegen met kalenderweergave.

# - Voor user story 4: evenementenformulier met titel en datum.

# - Voor user story 5: herinneringen dropdown en pop-up.

# - Voor user story 6: bewerken en verwijderen met bevestiging.

# - Privacy: alleen eigen data via sessies, security met prepared statements.

# - Usability: grote knoppen, ronde hoeken en mobiel vriendelijk.

# - Ik maakte wireframes en flowcharts voor alle schermen.

#

# ## K1 Realiseren (W3)

# - Ik bouwde de app in 49 uur met PHP PDO, MySQL en Bootstrap.

# - Database heeft tabellen, Users, Games, Friends, Schedules, Events en koppelingen.

# - Inloggen met hashed wachtwoorden en sessies van 30 minuten.

# - Profiel opslaan met favoriete games via User Games tabel.

# - Vrienden toevoegen met zoekfunctie en geen zelf-toevoegen.

# - Schema’s delen met checkboxes voor vrienden en kalender grid.

# - Evenementen CRUD met beschrijving en sharing via Event User Map.

# - Herinneringen met JavaScript pop-up op tijd.

# - Bewerken en verwijderen met bevestigings- en rechten check.

# - GitHub versiebeheer met dagelijkse commits en branches.

# - Project Log toont elke taak, uren en opgeloste bugs zoals fk constraints.

# - Realisatie Verslag beschrijft code structuur, security en 95% functionaliteit.

#

# ## K1 Testen (W4)

# - Ik testte de app 6 uur op laptop en telefoon van 23 tot 25 september 2025.

# - 30 tests: 5 per user story met normaal, leeg, edge cases zoals spaties of verleden datum.

# - User story 1: profiel maken slaagt, maar spaties in games gaf bug #1001.

# - User story 2: vrienden toevoegen werkt, geen dubbele of zelf-toevoegen.

# - User story 3: schema’s in kalender met toekomstige datum check.

# - User story 4: evenementen opslaan, maar ongeldige datum gaf DB error #1004.

# - User story 5 en 6: herinneringen en bewerken, werken met pop-up en confirm.

# - Ik noteerde verwacht, werkelijk resultaat en aanpassingen.

# - Overleg met Marius Restua op 24 september voor extra edge cases.

#

# K1 Verbeteren (W5)

# - Ik maakte 6 verbetervoorstellen uit testrapport, oplevering en reflectie.

# - #1001: trim check tegen alleen spaties in games.

# - #1004: extra tests voor ongeldige datums en lange tekst.

# - #1002: e-mail en push notificaties naast pop-up.

# - #1003: grotere knoppen en hamburgermenu op mobiel.

# - #1005: 5 screenshots toevoegen aan testrapport.

# - #1006: sorteer knoppen op datum en game in lijsten.

# - Elk voorstel heeft issue, bron, aanpassing en beargumentering.

# - Overleg met Marius op 2 oktober voor realistische planning.

# - Voorstellen voor versie 1.1, kosten 1-4 uur per stuk.

# - De app wordt gebruiksvriendelijker en sterker.

#

# ## K2 Overleggen

# - Ik overlegde drie keer met Marius Restua tijdens planning, realisatie en testen.

# - Op 7 september over backend inloggen.

# - Op 14 september over vriendenlijst en geen zelf-toevoegen.

# - Op 27 september over mobiel design.

# - Op 24 september check van tests en edge cases.

# - Op 2 oktober check van verbetervoorstellen.

# - Ik vroeg specifiek om hulp bij bugs en liet code zien.

# - Marius gaf tips zoals URL fix en console checken.

# - Ik paste meteen aan en vroeg wat ik nu kon doen.

#

# ## K2 Presenteren (met Marius en Billy)

# - Ik presenteerde portfolio website met React en Laravel op 10 maart 2025.

# - Intro: hallo ik ben Harsha, vandaag mijn portfolio.

# - Ik legde stap voor stap homepage, projecten en blog uit.

# - Voorbeelden: router-systeem en API voor blogs.

# - Vragen gesteld: snappen jullie de API? Hebben jullie vragen?

# - Feedback Marius: meer eindresultaat laten zien, niet alleen plan.

# - Feedback Billy: meer demo en AI uitleg.

# - Ik zei: bedankt, ik ga dat doen en tijdlijn toevoegen.

#

# ## K2 Reflectie (W3)

# - Ik gebruikte STARR voor portfolio website project in februari 2025.

# - Situatie: API koppelen aan React blogpagina vastgelopen.

# - Taak: blogberichten uit database tonen voor overleg 29 januari.

# - Actie: Postman testen, zelf zoeken, toen hulp vragen aan Marius.

# - Resultaat: URL fix, blog werkt op 31 januari.

# - Reflectie: zelfstandig goed, maar eerder hulp vragen beter.

# - Feedback Marius: sneller vragen en console beter lezen.

# - Reactie: ik paste toe en stel nu wekelijks overleg voor.

# - Proactief: ik liet probleem meteen zien in overleg.

# - Geleerd: foutmeldingen goed lezen en binnen 1 dag hulp vragen.
