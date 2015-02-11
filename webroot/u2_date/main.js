$(document).ready(function(){
    'use strict';
    var text, date, html='';

    text = document.getElementById('text');

    date = new Date();

    html += '<p>toJson: ' + date.toJSON() + '</p>';
    html += '<p>toLocaleDateString: ' + date.toLocaleDateString() + '</p>';

    date = Date();
    html += '<p>Without new: ' + date + '</p>';

    date = new Date();
    date.setYear('1995');
    html += '<p>Set year: ' + date.toISOString() + '</p>';

    text.innerHTML = html;

});