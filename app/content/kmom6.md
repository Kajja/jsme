Kmom6: HTML5 och Web Sockets
----------------------------

###Reflektera över svårigheter, problem, lösningar, erfarenheter, lärdomar, resultatet, etc.

Den här uppgiften tycker jag nog har varit den roligaste hittills. Har tittat på node.js tidigare men inte haft tid att fördjupa mig i det.

Det mest flöt på ganska bra tills jag kom till chatservern. Det blir lite struligare att utveckla något när man har kod både på server och klientsidan som ska fungera ihop. I och för sig ska man kanske inte klaga, här har man ju själv kontroll både på server- och klientkoden. Tyckte också att det gick ganska långsamt att felsöka på serversidan. Ska man utveckla mer med node.js så får man nog leta efter en bra debugger, som man har i browsern.

###Vilka möjligheter ser du med HTML5 Websockets?

Att kunna vara uppkopplat kontinuerligt och inte behöva belasta servern med ständiga förfrågningar är ju väldigt bra. Det blir ett mer "naturligt" flöde om servern kan pusha information istället för att klienten ska ligga och fråga.

I sammanhang när man behöver uppdaterad information så snabbt som möjligt kan det nog vara intressant att använda WebSockets. T.ex. om man har sensorer och ett centralt kontrollprogram. Eller i ett nätverksspel där man hela tiden måste ha aktuell information om vad de andra spelarna gör.


###Vad tycker du om Node.js och hur känns det att programmera i det?

Jag har sett fram mot att jobba i node.js, tycker det är väldigt kul. Känner mig mer hemma med JavaScript än PHP så det har alltid känts som ett litet motstånd när man måste få ihop något i PHP på servern. Uppbyggnaden av node.js känns också väldigt enkel och tydlig. Det går snabbt att komma igång och få något att fungera. Kommer helt klart att fortsätta med node.js.


###Beskriv hur du löste echo-servern och broadcast-servern.

Jag utgick från de exempel som fanns i övningen och sen byggde jag vidare på dessa. Dvs. servern kontrollerar om förfrågan kommer från en godkänd domän (här har godkänner jag dock alla klienter för tillfället) sen kontrollerar den om det protokoll som klienten vill använda stöds. Om klienten vill använda 'echo-protocol' så skapas en uppkoppling ('connection') med lyssnare/hanterare som passar detta protokoll (görs i echoSetUp()). Om klienten istället vill använda 'broadcast-protocol' så skapas en uppkoppling med andra lyssnare/hanterare (görs i bradcastSetUp()).

Vid 'broadcast-protocol' så sparas de aktiva uppkopplingar i en array och ett meddelande från någon klient skickas sedan ut på alla uppkopplingar i denna array. När en klient kopplar ner så plockas uppkopplingen bort från arrayen.

I klientens gränssnitt kan man välja vilket protokoll som man vill använda.

Det var en bra övning då man fick lite bättre förståelse för hur servern hänger ihop med connections (ett tag var jag inne på helt fel spår).

###Beskriv och berätta om din chatt. Förklara hur du byggde din chatt-server och förklara protokollet.

Jag fortsatte att använda samma node.js modul för websockets som användes i övningen (funderade ett tag på socket.io men tänkte att man kanske lär sig mer att använda en enklare modul, där man får bygga mer själv).

Chattservern har samma uppbyggnad som den tidigare, echo och broadcast-servern. Man skapar en http-server, sedan en instans av en WebSocket-server och kopplar http-servern till denna. Vid ett 'request'-event till WebSocket-servern (som skapas när http-servern får en 'upgrade'-förfrågan) så kontrollerar man om klienten tillhör en godkänd domän och om protokollet stöds (bara 'chat-protocol' som stöds). Om allt är okej så sätt en uppkoppling upp med lyssnare/hanterare som kan hantera 'chat-protokollet'.

Efter att klienten har blivit uppkopplad mot servern så skickar den ett meddelande att man vill registrera sig med ett visst användarnamn. Om detta användarnamn redan är registrerat så svarar servern att det är upptaget och klienten stänger ner uppkopplingen. Om det inte är registrerat så sparas användarnamnet i en array som heter 'users'. Man lägger också till användarnamnet till den uppkoppling ('connection') som skapas för att om uppkopplingen stängs ner, så ska man veta vilken användare som ska tas bort ur 'users'.

Formaten för meddelanden mellan klient och server baseras på objekt av dessa typer (som skickas som JSON-strängar):

Från klienten: 
```js
{
    type: 'register',
    id: user,
    data: document.getElementById('msgText').value
}
```
Typer av meddelanden som klienten skickar är 'register' (när man vill registrera en användare) och 'message' för övriga meddelanden.

Från servern:
```js
{
    type: 'error',
    data: '-->Användarnamnet är redan taget'
}
```
Typer av meddelanden som servern skickar är 'error' (när ngt gått fel), 'connected_users' (info om vilka användare som är anslutna till chatten) och 'message' för övriga meddelanden.

Koden blev lite rörig tyckte jag, både på klient och serversidan. Det blir eventhantering i flera led, först events för WebSocket-servern, sen events för uppkopplingar och sedan hanteringen av olika typer av meddelanden i chat-protokollet.

###Gjorde du något på extrauppgiften?

Vet inte riktigt vad som räknas som extra, men användarna kan se vilka andra det är som är uppkopplade och även när någon ansluter eller kopplar ner.