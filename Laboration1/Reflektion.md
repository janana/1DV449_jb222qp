Motivera ditt val av metod
======================
Jag valde att skriva min applikation i PHP eftersom jag i och med senaste kursen är insatt i språket, vilket gör det lätt att komma igång med kodandet direkt. Jag använde mig sedan av curl för att läsa in själva sidorna och DOMDocument och DOMXPath för att söka rätt element ur sidornas HTML.

Mitt val att använda mig av curl kom helt av det faktum att jag aldrig gjort någonting liknande, att John hade valt att använda sig av metoden i sina instruktionsvideor, och att det helt och hållet uppfyllde de krav jag hade på klassen inom laborationen. Den var smidigt att jobba med då man kunde ställa in alla inställningar man behövde använda och inte, och det kändes som att klassen betedde sig som en webbrowser vilket var precis det jag behövde för laborationen.

Samma sak gäller DOMDocument, det var väldigt smidigt att jobba med, samt att John använde det i instruktionsvideorna. Klassen gjorde det lätt att strukturera sidans HTML och DOMXPath gjorde det riktigt lätt att hämta ut rätt element.

Risker med applikationer som innefattar automatisk skrapning av webbsidor?
=======================
- De kan sakta ned den ursprungliga hemsidan
- Om den ursprungliga hemsidan struktureras om kan skraporna sluta fungera
- Det kan sakta ned applikationen som innefattar skrapan då det kan ta tid att exekvera scriptet
- Applikationerna kan innehålla information som är copyright-skyddad av de ursprungliga hemsidorna/hemsidan

Extra problem med att skrapa en sida gjord i ASP.NET WebForms?
=======================
Ett problem jag skulle kunna tänka mig att man kan få är att WebForms använder sig av gömda input-fält för att spara information till applikationen. För att få med dessa till applikationen skulle man förmodligen behöva göra post-anrop vid varje hemsida.

Förmodligen skulle det också bli ett problem om WebForms-sidan innehöll paging, då man måste gå igenom alla nummer av sidor för att komma åt informationen.

Har du varit ”en god webbskrapare”?
========================
Jag har försökt att skriva min applikation så kort och effektiv som möjligt, men ändå ha en skapligt objektorienterad kod. Utöver det skulle jag inte säga att jag har tänkt så mycket på att vara en god webbskrapare.

Vad har du lärt dig på denna uppgift?
========================
Väldigt mycket, eftersom jag inte visste någonting alls om webbskrapning innan. Jag har lärt mig om olika sätt att spara ned en hemsida och hur man kan ta ut enskilda element ur en sidas HTML. Dessutom har jag lärt mig mycket om hur en webbrowser (och sökmotor) kan se på en hemsida, och det kan vara nyttigt att inte bara se ur programmerarens synpunkt hela tiden!

