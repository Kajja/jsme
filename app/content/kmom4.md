Kmom4: AJAX och JSON med jQuery
-------------------------------

###Reflektera över svårigheter, problem, lösningar, erfarenheter, lärdomar, resultatet, etc.

Den här uppgiften tyckte jag löpte på ganska bra. Det var intressant att läsa om hur man ska tänka omkring "templating" när man använder Ajax, dvs. hur man på ett bra sätt skapar "krokar" och mallar i sin sida för att lätt kunna lägga in den information som man kan få som svar. Tittade även på Mustach, men det kändes lite överkurs för denna uppgift.

Jag fick lite olika beteende i Chrome och Firefox, Firefox verkar cacha sidor och uppdaterar dem inte pss. som Chrome.

###Vad tycker du om Ajax, hur känns det att jobba med?

Jag tycker det är ganska lätt och kul att jobba med Ajax när man använder sig av jQuery. Har försökt använda mig av "promises" för att få lite förståelse för dem.

###Vilka är dina erfarenheter av Ajax inför detta kursmoment?

Jag har jobbat lite med Ajax sedan tidigare, främst med jQuery.

###Berätta om din webbshop på din me-sida, hur gjorde du?

Min webbshop liknar den i övningsuppgiften, även om koden bakom är min egen från scratch. Själva innehållet i butiken ligger i shop_setup.php och inte direkt i index.php sidan. Jag försökte samla HTML:n på två ställen, index.php och checkout.php, och skapa så lite HTML som möjligt i JavaScript för att inte blanda struktur och beteende.

Index.php sidan använder sig av kundvagnen (cart.php) som jag ser som en tjänst med ett API med vilket man kan lägga till, tömma och hämta innehållet i kundvagnen med. I 'Köp'-knapparna så lägger jag information om den artikel som en viss knapp hör till (som attribut hos knapparna).

På checkout-sidan så använder jag både HTML5-, jQuery- och servervalidering. Laddade ner "jQuery Validation"-pluginen och den visade sig ganska lätt och praktiskt att använda. Skrev formuläret direkt i HTML, har provat CForm i en tidigare kurs men ville öva på HTML5 här. Checkout-sidan använder sig av en tjänst, pay_service.php, som anropas via Ajax och som sköter valideringen av informationen och sedan utför själva betalningen. La också in en liten fördröjning på serversidan för att simulera tiden det brukar ta att genomföra en betalning.

I checkout.js så använder jag mig av kundvagnstjänsten (cart.php) genom att jag har en metod, checkCart(), som returnerar ett promise som jag sedan kopplar "callbacks" till, ville se hur det fungerar.

Jag använde mig också av jQueryUI, framförallt för att jag ville att länken till Checkout-sidan från kundvagnssidan skulle vara stylad som en knapp.


###Lyckades du göra extra-uppgiften och paketera din kod?

Nej, jag gjorde inte extrauppgiften.