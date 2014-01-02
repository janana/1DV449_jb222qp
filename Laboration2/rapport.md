#Rapport Laboration 2

Min version av applikationen finns att testa på [janinaeb.se](http://janinaeb.se/kurs/webbteknik2/Laboration2/).


## 1. Optimering

Alla tider är genomsnittliga av åtminstone 4 försök

| Namn							| Teori							| Observation innan åtgärd	| Observation efter åtgärd	| Reflektion  |
| ----------------------------- | ----------------------------- | ------------------------- | ------------------------- | ----------- |
| 1. Sleep-funktion				| Koden anropar funtionen sleep(2) i filen img/middle.php som stannar applikationen i två sekunder, att ta bort denna bör göra att sidan laddar 2 sekunder snabbare. | Att logga in tar 6.78s där 2MB läses in | Att logga in tar 5.88s där 2MB läses in | Sidan borde ha blivit ännu snabbare att läsa in än den blev, då den endast blev ca 1 sekund snabbare istället för 2. |
| 2. Minimera redirects vid login  | Koden sätter header-location tre gånger vid inloggning, en av dessa är helt onödiga, vilket jag bestämt att ta bort. Vid korrekt inloggning sätts headern till en ny fil som endast innehåller en redirect till en annan fil. Att ta bort denna redirect, samt den överflödiga extra filen bör göra sidan snabbare att ladda, då färre resurser behövs läsas in. (filen img/middle.php tas bort) | Att logga in tar 5.88s där 2MB läses in | Att logga in tar 4.61s där 2MB läses in | Resultatet blev ungefär som väntat, sidan blev betydligt mycket snabbare. Dock trodde jag att mängden data att läsa in skulle blivit mindre, men eftersom filen som togs bort inte var så stor så märks det inte i resultatet. |
| 3. Extrahera css till egen fil och länka i head | Enligt vår lärobok bör man alltid skriva sin css i egna filer, då kan de cachas, vilket de inte kan när de är skrivna mitt i en html-sida, och även återanvändas till flera sidor. Eftersom den här optimeringslabben inte tar i hänsyn om någonting cachas, då cachen töms innan varje försök kommer det förmodligen göra att sidan tar längre tid att ladda, då fler resurser måste läsas in. Men det är ändå bra att ha css i egna filer ändå, och om inget annat känns det mer strukturerat. Dessutom i filen mess.php skrivs css in i slutet av ett dokument, vilket gör att sidan väntar med att rendera ut html tills allt är inläst. Om man lägger all css i sidans head ska det gå fortare att rendera ut sidan. | Att ladda första sidan tar 414ms där 126K läses in. Att logga in tar 4.61s där 2MB läses in. | Att ladda första sidan tar 314ms där 126K läses in. Att logga in tar 4.76s där 2MB läses in. | Första sidan, där det var mycket inline-css blev snabbare att läsa in, men inloggningen tog längre tid, då det inte verkade som att det jämnade ut sig att rendera ut css i html och att man instället var tvungen att läsa in en till fil. Inloggningssidan är något segare att ladda nu, men koden är mycket lättare att förstå. |
| 4. Extrahera skript och länka in sist | Enligt läroboken om optimering så stannar sidan att ladda in resurser så fort det dyker upp ett skript. Resurser kan annars laddas ned parallellt, men inte medan skript körs. Att lägga in all javascript i en egen fil och länka in den sist i dokumentet bör sidan laddas snabbare, dock kan den också bli långsammare då det blir ännu en resurs att läsa in. Tidigare länkas även in skript i head. | Att logga in tar 4.43s där 2MB läses in | Att logga in tar 2.67s där 2MB läses in | Sidan blev betydligt snabbare som väntat, då sidan inte längre väntar med att läsa in resurserna tills efter skripten har körts. |
| 5. Onödiga resurser läses in | Det finns några filer som läses in till inloggningssidan som inte används/inte finns. Dessa tar upp onödig tid när sidan laddas att läsa in eller försöka hitta. (js/longpolling.js & js/ajax-minified.js) | Att logga in tar 2.67s där 2MB läses in | Att logga in tar 2.47s där 2MB läses in | Sidan blev något snabbare men inte alls märkbart. |
| 6. Samla custom css till en fil | Om man samlar css i samma fil slipper sidan göra så många requests till olika filer och hemsidor. Då det endast är några få rader i varje fil tycker jag att det är enkelt och smidigt att bara kombinera dessa. (Filer läses in från vhost3.lnu.se samt googleapis fonts) | Att logga in tar 2.47s där 2MB läses in | Att logga in tar 694ms där 2MB läses in | Detta gjorde sidan betydligt mycket snabbare att läsa in, då det inte alls är lika många resurser som måste läsas in. Nu läser den in endast en fil som innehåller css från fem olika tidigare resurser. |
| 7. Minifiera js | Skript kan ta mycket kapacitet, och även whitespaces i dem. I bibliotek är det inte så viktigt att koden är förståelig då man kan läsa dokumentation om dem på deras respektive hemsidor, och då tar kommentarer, radbrytningar och namngivna variabler bara plats i filerna man länkar in. Att använda minifierade versioner av dessa kan spara plats, vilket kan göra att sidan laddar in snabbare. (minifierar jquery, modernizr och lightbox) | Att logga in tar 694ms där 2MB läses in | Att logga in tar 768ms där 2MB läses in | Att minifiera gjorde inte sidan snabbare att ladda, och om man kollar på resultatet skulle man kunna komma till slutsatsen att det till och med gjorde sidan segare. Men det är ändå så fruktansvärt lite tid att det skulle kunna vara samma som tidigare. Filerna blev tillsammans nästan hälften i storlek jämfört med tidigare. Men eftersom filerna är på 8-91 kB (väldigt liten del jämfört med det hela som är på 2 MB) så avrundas detta när filer läses in och den totala datamängden skrivs ändå ut som 2 MB av webbläsaren. |


## 2. Säkerhet

| Säkerhetshål					| Hur det kan utnyttjas			| Vilken skada han den göra			| Åtgärd							| 
| ----------------------------- | ----------------------------- | --------------------------------- | --------------------------------- |
| 1.1 Parametrar skickas direkt till databasen vid inloggning | Användare med kunskap om SQL kan skicka in kod | Den potentiella hackern skulle kunna komma förbi inloggningen och dessutom kunna skada den sparade datan i databasen. Skriva drop table users; så försvinner alla sparade användare.| Istället för att skriva in variablerna direkt i sql-queryn används nu funktionen bindParam innan sql-queryn exekveras. Nu kan endast inmatad data ses som värde till parametern i sql-queryn. |
| 1.2 Parametrar skickas direkt till databasen vid tilläggning och hämtning av meddelanden | Se föregående | Se föregående | Se föregående |
| 2.1 Man kan skriva taggar i meddelandeformuläret | Ingen validering sker av inmatad data, vilket skulle kunna vara HTML-taggar och javascript som skulle kunna användas för att hijacka information om användaren. | Sparade cookies skulle kunna bli stulna och hackern kan logga in som den ursprungliga användaren. | Validerar nu input från användaren och tar bort alla HTML-taggar i inlägget. (functions.php:20-21) |
| 2.2 Man kan skriva taggar i inloggningsformuläret | Se föregående | Se föregående | Validerar nu input från användaren och tar bort alla HTML-taggar vid inloggning. (check.php:8-9) |
| 3. Ingen validering av sessionen, bara sessionskakan för inloggning | Eftersom applikationen endast letar efter rätt sessionskaka för åtkomst till sidan, vilket man bara kan kopiera från en användare och använda själv skulle vem som helst kunna få tillgång till deras inloggning. | Användarens inloggning kan bli kapad, likt säkerhetshål 3. | Vid skapandet av sessionskakan sparas även användarens IP-nummer och webbklient och sedan testas detta vid varje åtkomst till den inloggade sidan. (check.php:15-16 & sec.php:28) |
| 4. Användaren blir inte utloggad vid klick på "Logga ut", endast redirect till inloggningssidan sker. | Någon kan stjäla användarens session genom att gå till den inloggade sidan efter att användaren tror att den loggat ut. | Användarens konto kan bli hackat | Vid klick på "Logga ut" genereras sessionen om och den föregående sessionskakan försvinner innan användaren blir redirectad till inloggningssidan. |


Något mer jag skulle ändra på i applikationen är att den sparar sina lösenord i klartext, om jag skulle fixa detta skulle jag hasha lösenorden med ett unikt salt för varje användare, sedan spara både det hashade lösenordet och saltet i databasen.


## 3. AJAX

Tidigare har message.js (tidigare från mess.php) anropat $.ajax(...).done() vilket körs när ajax-funktionen är färdig. Det denna funktion har gjort är att poppa upp en alert som säger att meddelandet har lagts till. Jag tog bort denna alert och lade istället till ett meddelande i div-taggen där meddelanden visas med jquery-funktionen prepend() så att meddelandet ska hamna först i listan (js/message.js:26). Om meddelandet inte läggs till i databasen ger det ett fel till functions.php som gör att den inte ger tillbaka meddelandet och namnet till javascript-funktionen $.ajax(...)done(). Endast när den får svar tillbaka, och att ingen del av sträng-objektet är tomt så visas ett meddelande i listan.