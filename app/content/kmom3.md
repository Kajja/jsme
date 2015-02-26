Kmom3: Grunderna i jQuery
-------------------------

###Reflektera över svårigheter, problem, lösningar, erfarenheter, lärdomar, resultatet, etc.

Den här uppgiften har flutit på ganska bra, även om den har tagit lång tid. Det som jag fortfarande har lite problem med är css, tycker det är svårt att se hur egenskaper påverkar varandra. Ex. har man 'position: static'
så bortses från 'top' och 'left' värden (vilket kanske är självklart, men man önskar att man hade fått någon varning när man har 'död' css-kod).

Sen så skulle man önska sig lite mer 'best practices' för jQuery, HTML, LESS ... Det finns så många olika sätt att göra saker på, men vilka lösningar har visat sig hålla i längden ex. enkel att utöka, snabb, ...? Författarna till jQuery-boken tycker att det är upp till varje utvecklares personliga smak (men det kan ju samtidigt göra koden ganska svår att läsa för andra).

###Vad tycker du om jQuery, hur känns det?
Jag tycker att jQuery är lätt och roligt att arbeta med. Största fördelen, tycker jag, är att man slipper göra några större tester i olika browsers, man kan vara ganska säker på att det kommer att fungera i alla. 

Men kanske ändå inte till 100%. Jag märkte att $(...).width() fungerade lite olika i Chrome jämfört med IE och Firefox. Då jag angav en bredd i em i ett nytt element, så gav .width() i Chrome ett värde i em och i IE och Firefox fick jag ett px-värde. Om jag la in det nya elementet i DOM:n och sedan tog .width() så fick jag ett px-värde även i Chrome.

Vad jag tycker är bra med jQuery är också att de har hållit det överskådligt och inte låtit det växa iväg till något som det tar veckor att få överblick över. Använder man jQuery mycket så tappar man kanske kunskapen om hur man arbetar direkt mot DOM-apiet, men vet inte om det är någon stor förlust.

###Vilka är dina erfarenheter av jQuery inför detta kursmoment?
Jag har jobbat en del med jQuery, lite med jQueryUI och en del med jQueryMobile (som i alla fall för ett år sedan kändes långt ifrån att fungera lika bra som jQuery).

###Berätta om din plugin
Det är egentligen en 'lightbox' fast för text och den kallas för ezyRead. Problemet som den är tänkt att lösa är att det kan vara svårt att läsa texter på nätet, ofta är raderna långa och det kan vara ganska störande element runt omkring texten. Funktionaliteten i min plugin kan man "hänga på" textelement. Man får då en liten knapp som, när man klickar på den, visar texten i ett mer läsvänligt format.