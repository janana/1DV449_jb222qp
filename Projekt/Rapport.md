# Rapport för kurs 1DV449 projekt 
## - FoodGen

### Inledning
Nästan varje dag ställs jag inför ett jobbigt beslut - vad ska jag äta idag? Därför har jag alltid önskat ha någon som kan bestämma åt mig, helst någon som vet vad jag är sugen på. Men eftersom tekniken inte gått så pass långt än, så får det duga med en applikation man kan tala om vad man tycker om och inte för.

Min applikation tar recept från [säsongsmat.nu] och visar ett slumpat för användaren. Användaren kan därefter välja att dela receptet på facebook, favorisera receptet så det sparas i en lista på användarens profil, rata receptet så det inte tas med i slumpningen igen, eller slumpa fram ett nytt recept. På användarens profil kan favoriserade och ratade recept visas och hanteras, och användaren kan även ställa in en kostinställning om denne är vegetarian, vegan eller allätare.

### Screencap med beskrivning av funktionaliteten


### Beskrivning av applikationens serversida - cachning, felhantering
Språk: PHP
Databasspråk: MySQL 
Data hämtas med ajax-anrop.

### Beskrivning av applikationens klientsida - cachning, felhantering
HTML, CSS, Javascript. 
Ramverk: bootstrap och jquery
APIer: Facebook api för inloggning och delning av recept. Receptinformationen från säsongsmat skrapas från deras hemsida. De har ett api för att hämta recept, men hur jag än gjorde fick jag ändå inte ut all information jag behövde från det. Dessutom skulle jag behövt göra 2-3 förfrågningar till deras api per recept, och eftersom det finns ganska många recept tyckte jag att skrapning kändes bättre. Det var inte lätt att skrapa deras hemsida eftersom den är helt ostrukturerad, och efter mycket krångel finns det fortfarande information som inte kommer med på några recept. 

### Egen reflektion
Jag har haft väldigt mycket problem med att få ut information om recepten. De första veckorna i projektet spenderades i maildiskussion med skaparen för att få reda på hur man använder deras api för att få ut recept. Till slut bestämde jag mig för att strunta i apiet och skrapa deras receptsidor istället, och efter det har nästan allt flytit på. 

Jag har även haft problem med text-formatet vid skrapning av information, då det på vissa recept funnits konstiga tecken, vilket John hjälpte mig med på handledningen.

Jag skulle ha velat hunnit klart tidigare än jag gjort, för då skulle jag ha gett mig på att lägga in funktionalitet för att lägga till egna recept till säsongsmat från min applikation. Det är något jag skulle tänka mig lägga till i framtiden.

### Risker med applikationen
Att alla recept inte är fullständiga. Om man hittar ett recept man verkligen vill göra sen finns knappa instruktioner. Användare skulle antingen bli besvikna på min applikation, eller lämna den och gå till originalsidan istället.

En risk är om säsongsmat skulle ändra strukturen på deras receptsidor. I så fall skulle kanske min skrapningsfunktionalitet inte fungera som planerat och receptinformationen kan blir helt fel.

Jag valde att använda säsongsmat för recept-hämtning från första början eftersom de delar med sig av all information och släpper den fritt, vilket är riktigt bra. Etiskt finns ingen risk med att använda deras data.

