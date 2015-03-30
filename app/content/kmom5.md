Kmom5: HTML5 och Canvas
-----------------------

Mitt spel är en variant av det klassiska spelet Frogger, och det heter Ketchup. Det har två levels.

###Reflektera över svårigheter, problem, lösningar, erfarenheter, lärdomar, resultatet, etc.
Jag stötte på en del problem framförallt:

* *Bilder*: Råkade ibland försöka rita ut bilder innan de var färdigladdade. Då visas ju ingenting. Fixades genom att vara lite noggrannare och kolla att de verkligen är laddade.
* *Programstrukturen*: Behövde tänka till ordentligt för att bestämma vart olika "ansvar" ex. spellogiken, skulle ligga. Det var på väg att bli lite spagettikod ett tag. Man lär sig mycket när man bygger något större program själv helt från grunden.
* *Prestanda*: När funktionerna var klara, och jag testade lite mer, så märkte jag att browsern började på 60fps men sedan minskade kontinuerligt ner till runt 15fps och hela spelet hackade. Tog ganska lång tid att försöka hitta var problemet låg. Gick igenom den kod som körs vid varje frame och problemet försvann då jag bytte:

```js
context.rect(...);
context.fill();`
```
mot

```js
context.fillRect(...)
```

Bra saker jag använt mig av (förutom de tips man fick i Kmom5-övningen):

* [Collision detection](http://blog.sklambert.com/html5-canvas-game-2d-collision-detection/)
* [Screens](http://dev.bennage.com/blog/2012/12/07/game-dev-01/)

###Vilka möjligheter ser du med HTML5 Canvas?

Ja, det finns ju i princip oändliga möjligheter. Man skulle t.ex. kunna göra nya sorters interfacekomponenter istället för att använda de gamla vanliga med dropdowns etc.

###Hur avancerat gjorde du din spelfysik (om du överhuvudtaget har någon i ditt spel)?

Mitt spel har ingen fysik. Bra dock att man i övningen går igenom hur man kan hantera detta med vektorer. Det kan nog komma till användning framöver.

###Beskriv och berätta om ditt spel. Förklara hur du använder objekt och prototyper.

Mitt spel består framförallt av dessa delar:

* Screens: Dessa är objekt som i sig innehåller spelobjekt och ser till att dessa uppdateras och ritas. Det finns en Start-screen, en screen för själva spelplanen, en för när man har klarat spelet osv. Screens skapas av en ScreenFactory, i denna fabrik specar man hur en viss screen ska vara uppbyggd.
* Spelsekvens-modul (GameSeq): Denna hanterar logiken/sekvensen i spelet. Den avgöra vilka "screens" som ska skapas och visas utifrån olika händelser. Ex. vilken screen som ska visas när spelet startar, vad som händer då en spelare inte har några liv kvar osv. Visade sig väldigt praktisk att ha.
* Spelobjekt (GameObj, Player, Panel, vehicle): Det finns en "huvudklass" som heter GameObj (konstruktorfunktion) där jag har försökt samla allt som är generellt för spelobjekt. Subklasser till denna är Player och Panel som skapas genom "klassiskt" arv. Objekt av typen fordon 'vehicles' skapas genom Crockfords "functional inheritance pattern" (som jag ville testa), och baseras även de på GameObj-"klassen". 
* Gameloop: Denna hanterar start och stop av animeringen för en viss Screen.

###Gjorde du något på extrauppgiften?

Ja, när man blir påkörd så spelas ett "splash"-ljud upp. Letade efter fler ljud men det var inte så lätt att hitta några som inte var på flera MB. Tiden rann iväg också då jag höll på att felsöka prestandaproblemet.

Till slut hamnade jag i tidsnöd, så fokus blev att få det att fungera än att göra det "snyggt", så det finns en hel del saker som jag vill förbättra i koden (viss kodduplicering m.m.), och så klart i gränssnittet, snyggare grafik med info om liv kvar, poäng...