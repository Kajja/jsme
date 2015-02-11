var MyStrings = {};

$(document).ready(function(){
    'use strict';

    var base = 'Copyright \u00A9 by XXX', mod;

    MyStrings.write('<strong>Strängar</strong>');
    MyStrings.write(base);
    
    // Lägga till Mumintrollet
    base += ' Mumintrollet ';
    MyStrings.write(base);

    // Lägga till numret 1942
    base += 1942;
    MyStrings.write(base);

    // Lägga till punkt och skriva ut längd
    base += '.';
    MyStrings.write(base + '(' + base.length + ')');

    // Ta bort XXX
    mod = base.slice(0, base.indexOf('XXX')) + base.slice(base.indexOf('XXX') + 4);
    MyStrings.write(mod + '(' + mod.length + ')');  

    // Skriv ut "19" + "42"
    mod = "19" + "42";
    MyStrings.write(mod + ' (' + typeof mod + ')');
    
    // Skriv ut "19" + 42
    mod = "19" + 42;
    MyStrings.write(mod + ' (' + typeof mod + ')');

    // Skriv ut 19 + 42
    mod = 19 + 42;
    MyStrings.write(mod + ' (' + typeof mod + ')');

    // Skriv ut 19 + "42"
    mod = 19 + "42";
    MyStrings.write(mod + ' (' + typeof mod + ')');
});

MyStrings.write = function(string) {

    div = document.getElementById('flash');

    div.innerHTML += '<p>' + string + '</p>';
};