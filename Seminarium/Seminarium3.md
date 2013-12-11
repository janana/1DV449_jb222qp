# Diskussionsfrågor - Mashup

## Del 1 - Projektidé

### Beskrivning

Min tanke är att göra en applikation som kombinerar texten till en låt med att kunna lyssna på låten direkt. Det finns ett flertal liknande applikationer idag, vilka oftast refererar till youtube-videor. Designen brukar oftast vara väldigt tafatt på liknande sidor, och de brukar innehålla 80% reklam som hoppar och blinkar överallt. Jag vill göra en egen stilren applikation med snyggare design.

### Apier
1. SoundCloud
2. LyricWiki

### Tankar kring apierna
Jag valde mellan att använda youtube, SoundCloud och Grooveshark till musikkällor. Det som fick mig att välja SoundCloud var att deras api var använt i flest mashup-applikationer på [programmableweb.com](http://www.programmableweb.com), samt att deras api-dokumentation var lättast att hitta i. Samma sak gäller för mitt val att använda LyricWiki för låttexter, och även att de erbjuder sin data i format jag gärna vill arbeta med.

### Dataformat
- SoundCloud: XML, Json, Jsonp
- LyricWiki: Text, HTML, XML, Json

### Krav
På SoundCloud måste man ha ett konto och registrera sin applikation för att få en key att arbeta med. LyricWiki har inga begränsningar eller krav vad jag lyckats hitta.

### Risker
- En risk är att någon av utvecklarna av apierna väljer att ändra dem.
- Jag vet inte hur seriös LyricsWiki är, då deras hemsida inte ser så proffessionell ut. En risk jag ställer mig för är att låttexterna inte skulle stämma.
- Att användaren söker på låtar som inte finns, eller som bara finns i ett av apierna. Jag kan aldrig vara riktigt säker på vilket omfång apierna har av låtar.


## Del 2 - Fallstudie - Exempel på en bra befintlig mashup-applikation
Jag hittade två mashup-applikationer som jag tycker är riktigt smarta, och jag kunde inte välja vilken jag skulle dela med mig av så jag skrev om båda!

1. [Musicnectar.com](http://www.musicnectar.com/)

   Musikhemsida med information om artisterna där låtar även kan spelas och köas i spellista.
  - Youtube.com
  - Last.fm

   Mer informativt än att lyssna på musik från youtube, och dessutom enklare att hitta rätt, och att köa låtar. På last.fm kan man skapa radiostationer från en artist med liknande artister, och även spela upp videor från youtube. Men mashup-applikationen är enligt mig mycket smidigare att använda än last.fm också.

2. [2lingual.com](http://www.2lingual.com/)

   Genererar google-sökning på två olika språk samtidigt
  - Google
  - GoogleTranslate
  - GoogleAppEngine
  - GoogleAjaxSearch
  - GoogleAJAXLanguage

   Mer effektivt än att söka två gånger. Man kan få fler relevanta sökningar, alternativt konstiga sökningar om översättningen blir fel.