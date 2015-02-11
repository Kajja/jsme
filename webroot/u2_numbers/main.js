var MyObj = {};

$(document).ready(function(){
  'use strict';

  var values = [42, 4.2, 1.221e5, 0xFFB];

  MyObj.constants();
  MyObj.tableWrite(values, 'Exponent', function(num) {return num.toExponential();});
  MyObj.tableWrite(values, 'Tre decimaler', function(num) {return num.toFixed(3);});
  MyObj.tableWrite(values, 'Närmaste heltal', function(num) {return Math.round(num);});
  MyObj.tableWrite(values, 'Kvadratroten', function(num) {return Math.sqrt(num).toPrecision(5);});
  MyObj.tableWrite(values, 'Sinus', function(num) {return Math.sin(num).toPrecision(5);});
});

/**
 *
 *
 */
MyObj.constants = function() {

    var html = '';

    html += '<p>Eulers-konstant E = ' + Math.E + '</p>';
    html += '<p>PI = ' + Math.PI + '</p>';
    html += '<p>Största talet som kan representeras: ' + Number.MAX_VALUE + '</p>';
    html += '<p>Oändligheten: ' + Number.POSITIVE_INFINITY + '</p>';

    document.getElementById('constants').innerHTML = html;
};

/**
 *
 *
 */
MyObj.tableWrite = function(values, funcName, func) {

    var table = document.getElementById('results');
    var html = '<tr><td>' + funcName + '</td>';

    for (num in values) {
        if (values.hasOwnProperty(num)) {
            html += '<td>' + func(values[num]) + '</td>';
        }
    }
    html += '</tr>';
    table.innerHTML += html;
}