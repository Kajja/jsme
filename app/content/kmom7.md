Kmom7/10: Projekt och examination
---------------------------------

Det jag har utvecklat som projektuppgift består av två delar:

* __multigame__: ett bibliotek/ramverk/plattform för multiplayer spel. Är baserat på Node.js.
* __Tricker__: ett spel utvecklat för multigame-plattformen.

[Projektsida](http://nodejs1.student.bth.se:8043/)

På projektsidan finns även två instanser av spelet Tricker som man kan spela.

### k1: Paketera, presentera och produktifiera
Både multigame och Tricker finns presenterade på projektsidan. De finns också båda på GitHub och som npm paket:

* multigame: [Github](https://github.com/Kajja/multigame), [npm](https://www.npmjs.com/package/multigame)
* Tricker: [Github](https://github.com/Kajja/tricker), [npm](https://www.npmjs.com/package/tricker)

Det finns tydlig dokumentation i README.md och multigame har även en folder, /docs, där api-dokumentation finns.
Både multigame och tricker har ordentligt kommenterad kod enligt JSDOC.

### k2: Ha koll på konkurrenterna och lär av dem
På projektsidan finns en översikt över konkurrerande lösningar till multigame. Många olika ramverk gicks igenom innan de valdes ut som finns beskrivna på projektsidan. De som valdes representerar den spridning av ramverk som finns för spelutveckling, från de enkla, som nodeGame, till de väldigt omfattande, som Isogenic Game Engine. Att gå igenom många olika alternativa lösningar gjorde att jag fick en klarare bild av vart jag skulle vilja ta multigame-plattformen. Många av lösningarna jag hittade kändes som att de begränsade vilken sorts spel man kunde skapa. Deras API:er var ofta också komplexa och många kostade pengar.

Jag skulle vilja hålla multigame-kärnan enkel, generell och gärna snabb och sedan skapa mindre moduler som man kan bygga på kärnan med. Utvecklare ska då kunna, genom att kombinera ihop moduler, sätta ihop den lösning som passar dem bäst utifrån den typ av spel de ska utveckla.

### k3: Kvalitet och omfattning
Från början var tanken att skapa ett multiplayer spel, och jag började att implementera Tricker. Efter ett tag så märkte jag att vissa delar skulle man kunna bryta ut och göra någonting mer generellt av. Det var så multigame-plattformen uppstod. När väl denna fanns så tänkte jag att det var bättre att "lägga krutet" på det som är generellt, så därför "produktifierade" jag denna i högre grad än Tricker.

#### multigame:
Multigame har både serverdelar, som ska köras i en Node.js miljö, och en klientdel som ska köras i browsern. Med multigame skapar man instanser av spel på servern som spelare och åskådare kan ansluta till. Multigame innehåller en server som lyssnar efter om någon vill ansluta till ett spel. Multigame kan hantera många spelinstanser samtidigt som inte behöver vara av samma typ utan kan vara olika sorters spel.

När man använder multigame så skapar man spelinstanser som registreras hos ett kontrollobjekt (Manager.js) som håller ordning på alla spel. När någon försöker ansluta till ett spel så kontrollerar multigame-servern, genom att fråga Manager-objektet, om det finns något spel med angivet id. Om så är fallet kopplas klienten upp mot detta. Man skapar då också en instans som representerar klienten, antingen en spelare (Player.js) eller en åskådare (Observer.js) och kopplar denna till spelet. Jag har använt mig av Socket.io för att bygga upp kommunikationslösningen.

Spel som ska gå att köra på multigame-plattformen ska ha ett Rules-objekt där spellogik m.m. ska läggas. Rules-objektet ska följa det interface som finns definierat i ./lib/Rules.js. När man skapar en spelinstans på servern så måste man ange ett Rules-objekt. Samma Rules-objekt kan användas för många spelinstanser om man ser till att man, när man utvecklar ett spel, lägger tillståndsvariabler i spelinstanserna och inte i Rules-objektet.

Till multigame hör också ett antal unit-testfall. De finns under /test och är framtagna för att köras med [Mocha](http://mochajs.org/).

#### tricker:
Tricker är ett nätverksspel för två spelare. Det är byggt för att passa till multigame-plattformen. Därmed så har det ett Rules-objekt, med spellogik m.m., och en klientdel. Klientdelen använder sig av multigame-plattformens klient (gameProxy.js). Koden för serversidan är också uppdelat i ett antal moduler.

Spelet är implementerat så att alla tillstånd finns på serversidan, dvs. klienterna anslutna till ett spel har inte något eget tillstånd. Detta gjorde jag för att inte behöva fundera på om klienter är i "synk" och hur man ska hantera problem som kan uppstå för att de inte är i synk. Denna lösning kräver dock att allt måste ritas om varje gång klienten får information från spelinstansen på servern att det har skett en förändring.

Spelet ritar upp spelplanen i ett Canvas-element. Det har inget eget GUI för att starta spelet m.m. På projektsidan, där man kan provspela det, så har jag skapat några knappar med vilka man kan starta spelet och ange när man är beredd att påbörja en ny omgång.

Tricker-klienten är också gjord för att kunna hantera åskådare. Det vill säga ansluter man som en åskådare så får man inte se de knappar som en spelare ser och informationen som visas skiljer sig också.

### k4, k5, k6: Valbart krav

#### NPM task runner
Jag har använt npm som "task runner" enligt denna [artikel](http://blog.keithcirkel.co.uk/why-we-should-stop-using-grunt/). Det innebär att om man kör `npm run build`, så körs både jshint och testfallen och api-dokumentation uppdateras. Har även lagt till möjligheten att använda en modul som heter "watch", som övervakar förändringar i /lib-katalogen. Om det har skett några förändringar så körs `npm run build`. Övervakningen startas med `npm run watch`.

#### Projeksida med Express.js
Projektsidan för multigame och tricker har jag skapat speciellt för dessa i Express.js; ville lära mig att bygga webbsidor med Node.js som bas. Per default så används Jade som "template engine" för Express.js. Jag bytte denna mot Hogan för att kunna använda mig av Mustache-mallar istället. [Mustache](https://mustache.github.io/) verkade både enklare och bättre än Jades syntax tycker jag.

Mallsystemet för min Express.js site är uppbyggt på liknande sätt som det vi skapade i PHP MVC-kursen, dvs. med en huvudvy (layout) och sedan andra vyer som man kan lägga in i denna. Jag har försökt att hålla det modulärt men huvudvyn/layouten är dock inte lika avancerad som i PHP MVC-kursen. Jag återanvände också stylingen från min redovisningssida i JS-kursen, med några mindre justeringar.

#### Åskådare till spel
Detta tycker i alla fall jag är en extra "feature". Både multigame-plattformen och Trickerspelet har stöd för att inte bara hantera spelare utan även åskådare till ett spel.

Hur projektet gick att genomföra
--------------------------------
Projektet tog längre tid att genomföra än jag hade tänkt. Att skapa ett eget paket av multigame-funktionaliteten gjorde att uppgiften växte ganska rejält. Det var också mycket jag var tvungen att lära mig då jag skapade projektsidan i Express.js. Förutom att det har varit mycket att göra, så tycker jag inte att jag har fastnat någonstans. Det har flutit på ganska bra. Har lärt mig mycket och har nu en klart bättre koll på JavaScript även på serversidan.

Tankar om kursen
----------------
Jag tycker att det har varit en bra kurs, både innehåll och material. Men, jag tycker att omfattningen är för stor för en 7,5hp kurs. Mitt förslag är att dra ner på mängden uppgifter, och fokusera på centrala delar. De kurser där jag har lärt mig mest i är de där det har funnits utrymme för lite eftertanke, då faller allt på plats och det blir roligare också. Mitt betyg på kursen är 7/10.