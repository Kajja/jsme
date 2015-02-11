$(document).ready(function(){
    'use strict';
  
    roulette.createTable();
});

var roulette = {
    numbers: 37,
    colors: ['green', 'black', 'red'],
    table: [0,2,1,2,1,2,1,2,1,2,1,1,2,1,2,1,2,1,2,2,1,2,1,2,1,2,1,2,1,1,2,1,2,1,2,1,2]   
};

roulette.createTable = function() {

    var i, html = '', color
        tableDiv = document.getElementById('rtable');

    for (i = 0 ; i < this.numbers ; i++) {
        color = this.colors[this.table[i]];
        html += '<div class="number ' + color + '" id="' + i + '">' + i + '</div>';
    }
    tableDiv.innerHTML = html;
};