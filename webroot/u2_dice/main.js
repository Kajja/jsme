myDice = {};

$(document).ready(function(){
  'use strict';
  
  myDice.writeDice(myDice.rollDice(6, myDice.random(1, 6)));
  myDice.writeDice(myDice.rollDice(12, myDice.random(1, 6)));
  myDice.writeDice(myDice.rollDice(100, myDice.random(1, 6)));

});

myDice.random = function(min, max) {

        return function() { return Math.floor((Math.random() * (max - min + 1))) + min; };
};

myDice.rollDice = function(times, randFunc) {

    var i, result = {'throws': [], average: 0};

    for (i = 0 ; i < times ; i++) {
        result['throws'][i] = randFunc();
        result.average += result['throws'][i];
    }

    result.average = (result.average/times).toFixed(2);
    return result;
};

myDice.writeDice = function(result) {

    var html, div = document.getElementById('flash');
    html = '<p>Kastar tärningen ' + result['throws'].length + ' gånger:<br>' +
        'Medelvärde: ' + result.average + ' Serie: ' + result['throws'].join(', ');
    div.innerHTML += html;

};