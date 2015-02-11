$(document).ready(function(){
    'use strict';
    var literals = [
        42, 
        4.2, 
        "hello world",
        'hej', 
        true, 
        false, 
        null, 
        undefined, 
        /javascript/, 
        {x: 1}, 
        [1, 'hej', 2], 
        function(){'use strict';}
    ];

    literalsCheck(literals);

});

function literalsCheck(literals) {
    var i = 0, html = '';

    for (; i < literals.length; i++) {

        html += '<li>' + literals[i] + ' - ' + typeof literals[i] + '</li>';
    }

    document.getElementById('literals').innerHTML = html;
} 