ezyRead
-------

<p class='ezyRead'>Texter på nätet kan ibland vara svåra att läsa, speciellt längre texter. Raderna kan vara långa, bakgrunden störande, typsnittet svårläst ... Då kommer jQuery-pluginen ezyRead till din hjälp. Den tar texten från ett element och presenterar den på ett mer läsvänligt format. Prova hur det fungerar genom att klicka på symbolen här efter texten.</p>

###Att använda
På de textelement där du vill använda ezyRead så gör du t.ex. så här:
```html
    <p class='ezyRead'>Lite text</p>
```
och i din JavaScript-fil så kan du sedan göra detta:
```js
    $('.ezyRead').ezyRead();
```
Då kommer ezyRead att lägga till en symbol efter texten, som i första stycket på denna sida, som man sedan kan klicka på.

###Hur du installerar
Börja med att se till att jQuery inkluderas i din sida. Sen inkluderar du helt enkelt [ezyRead-filen](../webroot/js/jquery.ezyread.js), så här:
```html
    <script src='jquery.ezyread.js'></script>
```
Nu är det bara att använda ezyRead i din egen JavaScript-kod.


###Fler möjligheter
Du kan själv i anropet till ezyRead ange inställningar för typsnitt, linjehöjd, bredd och hur stor den vita ytan runt texten ska vara:
```js
    $('#intro').ezyRead({
        'font-face': 'times, serif',
        'line-height': '1.5em',
        'padding': '3em',
        'width': '27em'
    });
```